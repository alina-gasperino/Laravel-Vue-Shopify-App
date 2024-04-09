<?php

namespace App\Listeners;

use App\Events\CampaignProcessComplete;
use App\Events\CampaignProcessed;
use App\Models\Campaign\ElToro;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendToCompleteElToro
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
     * @param  CampaignProcessComplete $event
     * @return void
     */
    public function handle(CampaignProcessComplete $event)
    {
        $elToro = new ElToro();
        $elToro->sendComplete($event->campaignLimits);
    }
}
