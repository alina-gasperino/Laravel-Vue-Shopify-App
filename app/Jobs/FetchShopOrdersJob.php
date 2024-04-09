<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Shop;
use App\Models\Shopify\Shopify;
use App\Models\User\SocialProviders;
use App\Models\Mongo\Orders;

class FetchShopOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shopifyUserId;

    private $shopId;

    private $ordersSince;

    private $limit = 50;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId, $ordersSince = '')
    {
        $this->shopId           = $shopId;
        $this->ordersSince      = $ordersSince;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $shopify = new Shopify();
            $shop = Shop::find($this->shopId);
            $shopifyUser = SocialProviders::where(['provider_id' => 1, 'nickname' => $shop->shop_name])->first();

            // $ordersData = $shopify->getAllOrders($shopifyUser, $this->token, $this->limit);
            // $orders = $ordersData['data'];
            // $next_token = $ordersData['next'];

            $ordersSince = '';
            if($this->ordersSince && $this->ordersSince != '') {
                $updated =  $this->ordersSince ?? '1970-01-01 00:00:00';
                $ordersSince = \DateTime::createFromFormat('Y-m-d H:i:s', $updated)->format(\DateTime::ISO8601);    
            }
            $orders = $shopify->getOrders($shopifyUser, $ordersSince, $this->limit);

            $ordersSince = '';

            $count = count($orders);
            \Log::info('shop orders data',[$shop->id, $count]);
            foreach ($orders as $order) {
                //Insert into Order History
                $orderRecord = Orders::updateOrCreate([
                    'id'        => $order->id,
                    'shop_id'   => $shop->id,
                ],(array) $order);
                $ordersSince = $order->updated_at;
            }

            $shop->update(['orders_next_token' => $ordersSince]);

            if($ordersSince != ''){
                dispatch(new FetchShopOrdersJob($this->shopId, $lastUpdate));
            }
        } catch (\Exception $e) {
            \Log::info('Fetch orders shops exception', [$e->getMessage()]);
        }
    }
}
