<?php

namespace App\Events;

use App\Models\Campaign\CampaignHistory;
use App\Models\Campaign\Campaigns;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CampaignProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $campaignHistory;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CampaignHistory $campaignHistory)
    {
        $this->campaignHistory = $campaignHistory;
    }
}
