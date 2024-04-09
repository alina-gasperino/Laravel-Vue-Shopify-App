<?php

namespace App\Models;

use App\Models\Shopify\Orders;
use App\Models\User\SocialProviders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cron
{
    function fetchOrderHistory() {
        $shops = Shop::all();

        foreach($shops as $shop) {
            $social = SocialProviders::where(['provider_id' => 1, 'nickname' => $shop->shop_name])->first();
            (new Orders())->upsertOrder($social, $shop);
        }

        return true;
    }
}
