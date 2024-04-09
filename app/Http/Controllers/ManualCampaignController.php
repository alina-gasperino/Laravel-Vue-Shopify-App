<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Campaign\ManualDrawRequest;
use App\Models\Campaign\Campaigns;
use App\Models\Shopify\Orders;
use App\Models\User\SocialProviders;
use App\Models\Campaign\DesignHuddle;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Campaign\StartCampaignRequest;

class ManualCampaignController extends Controller
{

    public function index()
    {
        $shopifyUser = (new SocialProviders)->getShopifyById(Auth::id());
        $DH = (new DesignHuddle)->firstOrCreate($shopifyUser);
        return view('manual-campaigns', [
            'userShop' => $shopifyUser->nickname,
            'userToken' => $DH->access_token
        ]);
    }    

    public function draw(ManualDrawRequest $request)
    {
        $operator = $this->getOperator($request->operator);
        $value = $request->value;

        $data = [];
        switch($request->audience){
            case Campaigns::Prior_Customers:
                $data = $this->priorCustomers($operator, $value);
                break;
            case Campaigns::Purchase_More_Then:
                $data = $this->purchaseMoreThen($operator, $value);
                break;
            case Campaigns::purchase_More_Then_Times:
                $data = $this->purchaseMoreThenTimes($operator, $value);
                break;
            case Campaigns::Top_Customer_By_Spend:
                $data = $this->topCustomerBySpend($operator, $value);
                break;
        }

        return response()->json($data);
    }

    public function priorCustomers($operator, $value){
        return [40,60];
    }

    public function purchaseMoreThen($operator, $value){

        $selected = Orders::select(\DB::raw('COUNT(customer_id) as count'))
        ->leftJoin('shop_order_history_discount_codes','shop_order_history_discount_codes.order_id','=','shop_order_history.id')
        ->groupBy('customer_id')->havingRaw("SUM(`order_total`) {$operator['selectedOpr']} {$value}")->sum('count');
        $whole = Orders::select(\DB::raw('COUNT(customer_id) as count'))
        ->leftJoin('shop_order_history_discount_codes','shop_order_history_discount_codes.order_id','=','shop_order_history.id')
        ->groupBy('customer_id')->havingRaw("SUM(`order_total`) {$operator['otherOpr']} {$value}")->sum('count');

        return [round($whole), round($selected)];
    }

    public function purchaseMoreThenTimes($operator, $value){

        $selected = Orders::select(\DB::raw('COUNT(customer_id) as count'))
        ->leftJoin('shop_order_history_discount_codes','shop_order_history_discount_codes.order_id','=','shop_order_history.id')
        ->groupBy('customer_id')->havingRaw("COUNT(shop_order_history_discount_codes.discount_code) {$operator['selectedOpr']} {$value}")->sum('count');
        $whole = Orders::select(\DB::raw('COUNT(customer_id) as count'))
        ->leftJoin('shop_order_history_discount_codes','shop_order_history_discount_codes.order_id','=','shop_order_history.id')
        ->groupBy('customer_id')->havingRaw("COUNT(shop_order_history_discount_codes.discount_code) {$operator['otherOpr']} {$value}")->sum('count');

        return [round($whole), round($selected)];
    }

    public function topCustomerBySpend($operator, $value){
        return [45,55];
    }

    public function getOperator($operator){
        switch($operator){
            case 'less than':
                $selectedOpr = '<';
                $otherOpr = '>=';
                break;
            case 'greator to':
                $selectedOpr = '>';
                $otherOpr = '<=';
                break;
            case 'equals':
                $selectedOpr = '=';
                $otherOpr = '!=';
        }
        return [
            'selectedOpr' => $selectedOpr,
            'otherOpr' => $otherOpr,
        ];
    }

    public function save()
    {
        $shopifyUser = (new SocialProviders)->getShopifyById(Auth::id());
        $DH = (new DesignHuddle)->firstOrCreate($shopifyUser);
        return view('manual-campaigns', [
            'userShop' => $shopifyUser->nickname,
            'userToken' => $DH->access_token
        ]);
    }

    public function startCampaign(StartCampaignRequest $request)
    {
        // Could add validator against audience_states but there's no real need for the extra call as only malicious
        // users could break this, and they can only break their own campaigns, why would they do this.
        $params = $request->all();
        $shop = Shop::where('user_id', $this->userId)->first();
        $params['shop_id'] = $shop->id;
        
        $projectId = $params['project_id'];
        $campaign = Campaigns::firstOrNew(['shop_id' => $params['shop_id'], 'project_id' => $projectId]);
        $campaignState = CampaignsState::where(['name' => 'Active'])->first();

        $campaign->audience_size_id = $params['audience'];


        //Check to see if there is already an active campaign
        $activeCampaigns = Campaigns::where(['shop_id' => $params['shop_id'], 'state_id' => $campaignState->id])->get();
        if ($activeCampaigns->count() == 0) {
            $campaign->state_id = $campaignState->id;
        } else {
            $validator->errors()->add('campaign_id',
                'There is already an active campaign.  You can only have one active campaign at a time, your campaign was saved, however, it was not activated.');
            $campaign->deleted_at = (new \DateTime())->format('Y-m-d H:i:s');
        }

        $shopify = new Shopify();
        $social = (new SocialProviders)->getShopifyById(Auth::id());
        $priceRule = $shopify->submitPriceRule($social, $params);
        //Save priceRuleId in to Table required for DiscountCode
        $campaign->discount_amount = floatval(preg_replace("/[^0-9.]/", "", $params['discount_amount']));
        $campaign->discount_price_rule_id = $priceRule->price_rule->id;
        $campaign->discount_prefix = $params['discount_prefix'];
        $campaign->discount_type = $params['discount_type'];
        $campaign->thumbnail_url = $params['thumbnail_url'];
        $campaign->max_sends_per_period = $params['max_sends'];
        $campaign->campaign_name = $params['campaign_name'];
        $campaign->user_id = $this->userId;
        $campaign->save();

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => $validator->getMessageBag()], 400);
        }
        // Queue the design to be downloaded
        (new DesignHuddle())->exportDesignQueue($campaign);
        $shop = Shop::where([
            'id' => $campaign->shop_id,
            'user_id' => $this->userId
        ])->first();


        return response()->json([
            'errors' => [],
            'success' => [
                'redirect' => route('campaign-overview.index')
            ]
        ], 200);

        }

}
