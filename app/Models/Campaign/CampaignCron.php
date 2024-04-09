<?php

namespace App\Models\Campaign;

use App\Events\CampaignProcessComplete;
use App\Events\CampaignProcessed;
use App\Models\Analytics\CampaignAnalytics;
use App\Models\Analytics\Dynamo;
use App\Models\Shopify;
use App\Models\Shop;
use App\Models\VisitorIp;
use App\Models\User\SocialProviders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class CampaignCron
{

    static public function queueCampaigns()
    {

        // Get List of Active Campaigns
        $campaigns = Campaigns::with('shop')->get();
        $dynamo = new Dynamo();
        $social = new SocialProviders();
        $orders = new Shopify\Orders();
        $history = new CampaignTargetHistory();
        $codes = new CampaignCodes();
        foreach ($campaigns as $campaign) {

            //Can set NextRun now, if there is overlap it's okay as there is no non-breaking code
            $nextRun = now();
            //Search DynamoDB
            unset($exemptVisitors);
            unset($current);
            $i = 0;
            $exemptVisitors = [];
            $current = [];
            $campaignLimits = [];
            $shopifyUser = $social->where([
                'provider_id' => 1,
                'user_id' => $campaign->user_id,
                'nickname' => $campaign->shop->shop_name
            ])->first();

            //Check against Shopify Recent Purchases
            $exemptOrders = $orders->getExemptUsers($campaign->shop_id);
            //Check against Previous Mailings
            $exemptHistory = $history->getExemptUsers($campaign->id);
            $exemptVisitors = array_merge($exemptOrders, $exemptHistory);

            $lastRan = ($campaign->last_ran === null) ? 0 : \DateTime::createFromFormat('Y-m-d H:i:s',$campaign->last_ran)->getTimestamp();
            $visitors = $dynamo->getVisitByShop($campaign->shop->id, $lastRan);
            // $visitors = VisitorIp::whereHas('visitor', function($q) use($campaign){
            //     $q->where('shop_id', $campaign->shop->id);
            // })->where(\DB::raw("DATE(created_at) >= DATE({$lastRan})"))->select(\DB::raw('browser_ip as ip'))->get()->toArray();

            $campaignHistory = CampaignHistory::create([
                'campaign_id' => $campaign->id,
                'shop_id' => $campaign->shop->id,
                'state_id' => 1
            ]);
            foreach ($visitors as $visit) {

                if (!isset($visit['ip']) || empty($visit['ip']))
                    continue;

                //Check Current Send
                if (key_exists($visit['ip'], $current))
                    continue;

                //@ToDo: Put this check back in
//                if($visit['audience_id'] != $campaign->audience_size_id)
//                    continue;

                //Check for Matching Records
                if (array_key_exists($visit['ip'], $exemptVisitors))
                    continue;

                //Find a unique discount code
                $discountCode = $codes->createUniqueCode($shopifyUser, $campaign);

                //Create CampaignHistory Record
                CampaignTargetHistory::create([
                    'id' => Uuid::uuid4(),
                    'shop_id' => $campaign->shop_id,
                    'campaign_id' => $campaign->id,
                    'campaign_history_id' => $campaignHistory->id,
                    'discount_code' => $discountCode,
                    'browser_ip' => $visit['ip']
                ]);
                $current[$visit['ip']] = 1;
                $i++;
                //@ToDo: Change this to be handled outside the loop and using batch
                usleep(500000);
            }

            $campaign->last_ran = $nextRun->format('Y-m-d H:i:s');
            $campaign->save();

            $campaignHistory->state_id = 5;
            $campaignHistory->count_unique_ip = $i;
            $campaignHistory->save();

            //Fire Event
            // CampaignProcessed::dispatch($campaignHistory);
            // $fileUrl = config('filesystems.disks.s3.url');
            // if ($i > 0) {
            //     $campaignLimits[$campaign->id] = [
            //         'max' => empty($campaign->max_sends_per_period) ? 999999999 : $campaign->max_sends_per_period,
            //         'current' => (new CampaignAnalytics())->getMonthlySent($campaign),
            //         'front' => $fileUrl . 'campaigns/postcards/page-1/' . $campaign->id . '.pdf',
            //         'back' => $fileUrl . 'campaigns/postcards/page-2/' . $campaign->id . '.pdf',
            //     ];
            // }
        }

        //Fire Event
        // CampaignProcessComplete::dispatch($campaignLimits);

        return null;
    }

}
