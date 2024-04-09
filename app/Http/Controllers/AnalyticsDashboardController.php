<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign\CampaignAudienceSizes;
use App\Models\Campaign\DesignHuddle;
use App\Models\Shop;
use App\Models\Shopify\Orders;
use App\Models\Shopify\Shopify;
use App\Models\Visitor;
use App\Models\VisitorIp;
use App\Models\Campaign\Campaigns;
use App\Models\Campaign\CampaignTargetHistory;
use App\Models\User\SocialProviders;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Dashboard\PrepareAnalyticsDataRequest;
use App\Http\Requests\GetQRCodeRequest;
use QrCode;
use App\Models\Analytics\Dynamo;

class AnalyticsDashboardController extends Controller
{
    public function analyticsDashboard($campaign_id = '')
    {
        $campaigns = Campaigns::withTrashed()->where('user_id', auth()->user()->id)->get();
        return view('analytics-dashboard', [
            'campaigns' =>  $campaigns,
            'selected_campaign_id' => $campaign_id,
        ]);
    }

    public function prepareAnalyticsData(PrepareAnalyticsDataRequest $request){
        $input = $request->all();
        $total_revenue = 0;
        $discount_prefix = '';
 
        $user = auth()->user();
        $shop = Shop::where('user_id',$user->id)->first();
        $campaign = null;
        if($input['campaign_id'] != 'all'){
            $campaign = Campaigns::find($input['campaign_id']);
        }

        $startEndDate = [$input['start_date'], $input['end_date']];
        
        $shopWhere = ['shop_id' => $shop->id,];
        

        $browserIps = CampaignTargetHistory::where($shopWhere)->where(function($q) use($campaign) {
            if($campaign) $q->where('campaign_id', $campaign->id);
        })->whereNotNull('browser_ip')->whereBetween(\DB::raw('DATE(`created_at`)'),$startEndDate)->pluck('browser_ip')->toArray();
        
        $dynamo = new Dynamo();
        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s',$input['start_date'] .' 00:00:00');
        $endDate = \DateTime::createFromFormat('Y-m-d H:i:s',$input['end_date'] .' 23:59:59');
        // visitor
        $dy_data = $dynamo->getVisitors($shop->id, $startDate->getTimestamp(), $endDate->getTimestamp(), 'activity', $browserIps);
        $visitor_count = $dy_data['count']; 
        $visitor_daywise_count = $dy_data['daywise']; 

        // add to cart
        $dy_data = $dynamo->getVisitors($shop->id, $startDate->getTimestamp(), $endDate->getTimestamp(), 'add to cart', $browserIps);
        $cart_count = $dy_data['count']; 
        $cart_daywise_count = $dy_data['daywise']; 

        // $visitorQuery = Visitor::join('visitor_ips','visitor_ips.visitor_id','=','visitors.id')
        // ->join('campaign_target_history','campaign_target_history.browser_ip','=','visitor_ips.browser_ip')
        // ->join('campaigns','campaigns.id','=','campaign_target_history.campaign_id')
        // ->where(function($q) use($campaign){
        //     if($campaign != null) $q->where('campaign_target_history.campaign_id', $campaign->id);
        // })
        // ->where('visitors.shop_id', $shop->id)->whereBetween('visitors.timestamp',$startEndDate);
        // $activeQuery = $visitorQuery->where('visitors.type', 'activity');        
        // $visitor_count = $activeQuery->count();
        // $visitor_daywise_count = $activeQuery->orderBy('visitors.timestamp','asc')->select(\DB::raw('COUNT(*) as counter'))->get()->pluck('counter')->toArray();

        // // add to cart
        // $visitorQuery = Visitor::join('visitor_ips','visitor_ips.visitor_id','=','visitors.id')
        // ->join('campaign_target_history','campaign_target_history.browser_ip','=','visitor_ips.browser_ip')
        // ->join('campaigns','campaigns.id','=','campaign_target_history.campaign_id')
        // ->where(function($q) use($campaign){
        //     if($campaign != null) $q->where('campaign_target_history.campaign_id', $campaign->id);
        // })
        // ->where('visitors.shop_id', $shop->id)->whereBetween('visitors.timestamp',$startEndDate);
        // $cartQuery = $visitorQuery->where('visitors.type', 'add to cart');        
        // $cart_count = $cartQuery->count();
        // $cart_daywise_count = $cartQuery->orderBy('visitors.timestamp','asc')->select(\DB::raw('COUNT(*) as counter'))->get()->pluck('counter')->toArray();

        // checkout 
        // $orderQuery = Orders::where($shopWhere)->whereNotNull('browser_ip')->whereBetween(\DB::raw('DATE(`order_date`)'),$startEndDate);         
        $orderQuery = Orders::whereIn('browser_ip',$browserIps)->where($shopWhere)->whereNotNull('browser_ip')->whereBetween(\DB::raw('DATE(`order_date`)'),$startEndDate);       
        $revenue_count = $orderQuery->count('order_total');
        $revenue_daywise_count = $orderQuery->groupBy(\DB::raw("DATE(`order_date`)"))->select(\DB::raw('COUNT(`browser_ip`) as counter'))->get()->pluck('counter')->toArray();

        // Revenue
        // $revenue_total = $orderQuery->sum('order_total');
        $revenue_daywise_total = $orderQuery->groupBy(\DB::raw("DATE(`shop_order_history`.`order_date`)"))->orderBy(\DB::raw("DATE(`order_date`)"))->select(\DB::raw('ROUND(SUM(`order_total`)) as y'), \DB::raw('DATE(`order_date`) as x'))->get()->toArray();

        $revenue_total = $this->sum($revenue_daywise_total, 'y');

        // new_orders_chart   
        // $neworder_total = $orderQuery->count('order_total');
        $neworder_daywise_total = $orderQuery->groupBy(\DB::raw("DATE(`order_date`)"))->orderBy(\DB::raw("DATE(`order_date`)"))->select(\DB::raw('COUNT(`order_total`) as y'), \DB::raw('DATE(`order_date`) as x'))->get()->toArray();
        $neworder_total = $this->sum($neworder_daywise_total, 'y');

        $per_post_card_amount = 1.5; // per post card amount

        // returns_chart
        $returnQuery = CampaignTargetHistory::leftJoin('shop_order_history_discount_codes', 'shop_order_history_discount_codes.discount_code', '=', 'campaign_target_history.discount_code')
        ->join('campaigns','campaigns.id','=','campaign_target_history.campaign_id')
        ->leftJoin('shop_order_history','shop_order_history.id','=','shop_order_history_discount_codes.order_id')
        ->whereIn('campaign_target_history.browser_ip',$browserIps)
        ->where('campaign_target_history.shop_id',$shop->id)->whereNotNull('campaign_target_history.browser_ip')->whereBetween(\DB::raw('DATE(`campaign_target_history`.`created_at`)'),$startEndDate);
        $return_total = $returnQuery->select(\DB::raw("(COUNT(`campaign_target_history`.`discount_code`) * {$per_post_card_amount}/ SUM(IF(`shop_order_history`.`order_total` IS NULL,0,`shop_order_history`.`order_total`))) as total"))->first()->toArray();
        $return_total = $return_total ? $return_total['total'] : 0;
        // dd($return_total);
        $return_daywise_total = $returnQuery->groupBy(\DB::raw("DATE(`campaign_target_history`.`created_at`)"))->orderBy(\DB::raw("DATE(`campaign_target_history`.`created_at`)"))->select(\DB::raw("(COUNT(`campaign_target_history`.`discount_code`) * {$per_post_card_amount} / SUM(IF(`shop_order_history`.`order_total` IS NULL,0,`shop_order_history`.`order_total`))) as y"), \DB::raw('DATE(`campaign_target_history`.`created_at`) as x'))->get()->toArray();
        // $return_total = $this->sum($return_daywise_total, 'y');

        // total_cost_chart
        // $cost_total = $orderQuery->sum('order_total');
        // $cost_total += $returnQuery->sum('shop_order_history_discount_codes.discount_amount');
        $cost_daywise_total = $returnQuery->groupBy(\DB::raw("DATE(`order_date`)"))->orderBy(\DB::raw("DATE(`order_date`)"))->select(\DB::raw('(SUM(`shop_order_history_discount_codes`.`discount_amount`)+SUM(`order_total`)) as y'), \DB::raw('DATE(`order_date`) as x'))->get()->toArray();
        $cost_total = $this->sum($cost_daywise_total, 'y');

        // \DB::connection()->enableQueryLog();

        // $queries = \DB::getQueryLog();
        // dd($queries);
        // customer_aquisition_chart
        $customer_aquiDaywise_total = CampaignTargetHistory::leftJoin('shop_order_history_discount_codes', 'shop_order_history_discount_codes.discount_code', '=', 'campaign_target_history.discount_code')
        ->join('campaigns','campaigns.id','=','campaign_target_history.campaign_id')
        ->whereIn('campaign_target_history.browser_ip',$browserIps)
        ->where('campaign_target_history.shop_id',$shop->id)->whereNotNull('campaign_target_history.browser_ip')->whereBetween(\DB::raw('DATE(`campaign_target_history`.`created_at`)'),$startEndDate)
        ->groupBy(\DB::raw("DATE(`campaign_target_history`.`created_at`)"))
        ->orderBy(\DB::raw("DATE(`campaign_target_history`.`created_at`)"))
        ->select(\DB::raw("TRUNCATE(COUNT(`campaign_target_history`.`discount_code`) * {$per_post_card_amount} / COUNT(`shop_order_history_discount_codes`.`discount_code`),2) as y"), \DB::raw('DATE(`campaign_target_history`.`created_at`) as x'))
        ->get()
        ->toArray();
        $customer_aqui_total = $this->sum($customer_aquiDaywise_total, 'y');

        // CampaignTargetHistory
        // conversion_rate
        $conversionQuery = CampaignTargetHistory::leftJoin('shop_order_history_discount_codes', 'shop_order_history_discount_codes.discount_code', '=', 'campaign_target_history.discount_code')
        ->whereIn('campaign_target_history.browser_ip',$browserIps)
        ->where('campaign_target_history.shop_id',$shop->id)
        ->whereNotNull('campaign_target_history.browser_ip')
        ->whereBetween(\DB::raw('DATE(`campaign_target_history`.`created_at`)'),$startEndDate);
        $conversion_Daywise_total = $conversionQuery->groupBy(\DB::raw("DATE(`campaign_target_history`.`created_at`)"))
        ->orderBy(\DB::raw("DATE(`campaign_target_history`.`created_at`)"))
        ->select(\DB::raw('TRUNCATE(COUNT(`shop_order_history_discount_codes`.`discount_code`) / COUNT(`campaign_target_history`.`discount_code`) * 100, 2) as y'), \DB::raw('DATE(`campaign_target_history`.`created_at`) as x'))
        ->get()
        ->toArray();

        $conversion_count = $this->percentageTotal($conversion_Daywise_total, 'y');
        // $conversion_count = $conversionQuery->select(\DB::raw('TRUNCATE(COUNT(`shop_order_history_discount_codes`.`discount_code`) / COUNT(`campaign_target_history`.`discount_code`) * 100, 2) as rate'))->first()->toArray();
        // $conversion_count = $conversion_count ? $conversion_count['rate'] : 0;
        
        // dd($revenue_daywise_total);
        // $total_orders;
        $preparedData = [
            "site_visitors" => [
                [ "count" => $visitor_count ],
                [ "data" => $visitor_daywise_count ],
            ],
            "add_to_carts" => [
                [ "count" => $cart_count ],
                [ "data" => $cart_daywise_count ],
            ],
            "abandon_checkout" => [
                [ "count" => $revenue_count ],
                [ "data" => $revenue_daywise_count ],
            ],
            "you_made" => [
                ["revenue"  => number_format($revenue_total,2)],
                ["new_orders"   => $neworder_total],
                ["returns"  => number_format($return_total,2)],
            ],
            "revenue_chart" => [
                [ "value"   => number_format($revenue_total,2) ],
                [
                    "data"  => $revenue_daywise_total,
                ],
            ],
            "new_orders_chart" => [
                [ "value"   => number_format($neworder_total,2) ],
                [
                    "data"  => $neworder_daywise_total,
                ],
            ],
            "returns_chart" => [
                [ "value"   =>  number_format($return_total, 4)  ],
                [
                    "data"  => $return_daywise_total,
                ],
            ],
            "total_cost_chart" => [
                [ "value"   =>  number_format($cost_total,2)  ],
                [
                    "data"  => $cost_daywise_total,
                ],
            ],
            "customer_aquisition_chart" => [
                [ "value"   =>  number_format($customer_aqui_total, 2)  ],
                [
                    "data"  => $customer_aquiDaywise_total,
                ],
            ],
            "conversion_rate_chart" => [
                [ "value"   =>  $conversion_count  ],
                [
                    "data"  => $conversion_Daywise_total,
                ],
            ],
        ];

        return response()->json($preparedData);
    }

    public function sum(&$array, $ele = 'y')
    {
        $total = 0;
        foreach($array as $eindex => $each) {
            $value  = $array[$eindex][$ele];
            $value  = number_format($value, 2,'.','');
            $total +=(float) $value;
            $array[$eindex][$ele] = $value;
        }
        return $total;
    }

    public function percentageTotal($array, $ele)
    {
        $total = 0;
        $count = count($array);
        foreach($array as $each) {
            $total+=(float) $each[$ele];
        }
        return $total !=0 ? $total / $count : 0;
    }
}
