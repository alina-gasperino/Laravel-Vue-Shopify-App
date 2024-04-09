<?php

namespace App\Listeners;

use App\Events\ShopConnected;
use App\Models\Campaign\ElToro;
use App\Models\Shopify\Orders;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FetchOrderHistory
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ShopConnected  $event
     * @return void
     */
    public function handle($event)
    {

        (new Orders())->upsertOrder($event->socialProvider, $event->shop);
    }
}
