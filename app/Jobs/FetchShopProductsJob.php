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
use App\Models\Mongo\Products;

class FetchShopProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shopifyUserId;

    private $shopId;

    private $token;

    private $limit = 50;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($shopId, $token = '')
    {
        $this->shopId         = $shopId;
        $this->token          = $token;
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

            $productsData = $shopify->getAllProducts($shopifyUser, $this->token, $this->limit);
            $products = $productsData['data'];
            $next_token = $productsData['next'];
            $count = count($products);
            $shop->update(['products_next_token' => $next_token]);
            \Log::info('shop products data',[$shop->id, $count]);
            foreach ($products as $product) {
                //Insert into Product History
                $productRecord = Products::updateOrCreate([
                    'id'        => $product->id,
                    'shop_id'   => $shop->id,
                ],(array) $product);
            }

            if($next_token != ''){
                dispatch(new FetchShopProductsJob($this->shopId, $next_token));
            }
        } catch (\Exception $e) {
            \Log::info('Fetch products shops exception', [$e->getMessage()]);
        }
    }
}
