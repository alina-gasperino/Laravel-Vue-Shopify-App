<?php

namespace App\Listeners;

use App\Events\CampaignProcessed;
use App\Models\Campaign\ElToro;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendToElToro
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
     * @param  CampaignProcessed  $event
     * @return void
     */
    public function handle(CampaignProcessed $event)
    {
        $elToro = new ElToro();
        $elToro->sendList($event->campaignHistory);
    }
}
