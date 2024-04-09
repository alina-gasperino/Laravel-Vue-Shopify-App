<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\User\SocialProviders;
use App\Jobs\FetchShopOrdersJob;

class AddOrdersInMongodb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongodb:fetch-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch orders from shopify and store it to mongodb';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $shops = Shop::all();

        foreach($shops as $shop) {
            dispatch(new FetchShopOrdersJob($shop->id, $shop->orders_next_token));
        }

        return 0;
    }
}
