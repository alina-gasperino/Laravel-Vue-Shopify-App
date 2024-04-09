<?php

namespace App\Models\Shopify;

use App\Models\Campaign\CampaignHistory;
use App\Models\Campaign\Campaigns;
use App\Models\Campaign\CampaignTargetHistory;
use App\Models\Shop;
use App\Models\User\SocialProviders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use App\Models\Mongo\Orders as MongoOrders;
use App\Models\Mongo\OrdersDiscountCodes as MongoOrdersDiscountCodes;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'shop_order_history';

    protected $guarded = [];

    public function codes()
    {
        return $this->hasMany(OrdersDiscountCodes::class, 'order_id');
    }

    public function upsertOrder(SocialProviders $shopifyUser, Shop $shop, $limit = 50)
    {
        $updated = $shop->order_history_last_updated ?? '1970-01-01 00:00:00';
        $ordersSince = \DateTime::createFromFormat('Y-m-d H:i:s', $updated)->format(\DateTime::ISO8601);
        $shopify = new Shopify();
        $count = $limit;
        while ($count == $limit) {
            try {
                $orders = $shopify->getOrders($shopifyUser, $ordersSince, $limit);
            } catch (\Exception $e) {
                $orders = [];
            }

            $count = count($orders);
            foreach ($orders as $order) {
                //Insert into Order History
                $orderRecord = Orders::firstOrNew([
                    'id' => $order->id
                ]);

                $mongoOrderRecord = MongoOrders::updateOrCreate([
                    'id' => $order->id,
                    'shop_id' => $shop->id,
                ], (array) $order);
                
                try {
                    $data = [
                        'shop_id' => $shop->id,
                        'order_total' => $order->total_price,
                        'browser_ip' => $order->browser_ip,
                        'billing_address_street' => $order->billing_address->address1,
                        'billing_address_street2' => $order->billing_address->address2,
                        'billing_address_city' => $order->billing_address->city,
                        'billing_address_province_code' => $order->billing_address->province_code,
                        'billing_address_zip' => $order->billing_address->zip,
                        'order_date' => $order->created_at,
                        'customer_id' => $order->customer->id,
                    ];
                } catch (\Exception $e) {
                    $data = [
                        'shop_id' => $shop->id,
                        'order_total' => $order->total_price,
                        'browser_ip' => $order->browser_ip,
                        'billing_address_street' => '',
                        'billing_address_street2' => '',
                        'billing_address_city' => '',
                        'billing_address_province_code' => '',
                        'billing_address_zip' => '',
                        'order_date' => $order->created_at,
                        'customer_id' => '',
                    ];
                }

                OrdersDiscountCodes::where(['order_id' => $order->id])->delete();
                foreach ($order->discount_codes as $codes) {
                    $ordersDiscountData = [
                        'order_id' => $order->id,
                        'discount_code' => $codes->code,
                        'discount_amount' => $codes->amount,
                        'discount_type' => $codes->type,
                    ];
                    OrdersDiscountCodes::create($ordersDiscountData);

                    //Update Campaign Stats & Campaign History Data
                    $this->updateCampaignStats($shop, $orderRecord, $codes->code, $order);

                }
                $orderRecord->fill($data);
                $orderRecord->save();
                $mongoOrderRecord->fill($data);
                $mongoOrderRecord->save();
                //Get the last date for the next potential run
                $ordersSince = $order->updated_at;
                $date = new \DateTime();
                // Storage::disk('s3-private')->put(
                //     'Year=' . $date->format('Y') .
                //     '/Month=' . $date->format('m') .
                //     '/Day=' . $date->format('d') .
                //     '/' .$shop->id . '/' . Uuid::uuid4().'.json',
                // json_encode($order));

            }
        }
        $lastUpdate = \DateTime::createFromFormat(\DateTime::ISO8601, $ordersSince)->format('Y-m-d H:i:s');
        $shop->order_history_last_updated = $lastUpdate;
        $shop->save();
    }

    public function getExemptUsers($shopId)
    {
        $exempt = [];
        $ordersFrom = (new \DateTime())->sub(new \DateInterval('P30D'));
        $orders = $this->where('shop_id', $shopId)->where('created_at' > $ordersFrom->format('Y-m-d H:i:s'));
        foreach ($orders as $order) {
            $exempt[$order->browser_ip] = 1;
        }

        return $exempt;
    }

    public function updateCampaignStats(Shop $shop, Orders $orderRecord, $discountCode, $order)
    {

        $campaignTarget = CampaignTargetHistory::where([
            'shop_id' => $shop->id, 'discount_code' => $discountCode
        ])->get();

        if (count($campaignTarget) == 0) {
            return null;
        }

        $orderTotal = (float) $order->total_price;
        $existingRevenue = 0;
        //If the Order was just updated make sure we subtract the existing revenue so that we aren't double counting it.
        if ($orderRecord->exists)
            $existingRevenue = $orderRecord->order_total;
        foreach ($campaignTarget as $target) {
            $target->total_revenue = $target->total_revenue + $orderTotal - $existingRevenue;
            $target->browser_user_id = $order->user_id;
            $target->save();

            if($history = CampaignHistory::find($target->campaign_history_id)){
                $history->total_revenue = $history->total_revenue + $orderTotal - $existingRevenue;
                $history->save();    
            }

            if($campaign = Campaigns::find($target->campaign_id)){
                $campaign->total_revenue = $campaign->total_revenue + $orderTotal - $existingRevenue;
                $campaign->save();
            }

        }

        return null;

    }
}
