<?php

namespace App\Models\Campaign;

use Illuminate\Support\Facades\Storage;

class ElToro
{
    public function sendList(CampaignHistory $campaignHistory)
    {
        $targets = CampaignTargetHistory::where([
            'campaign_history_id' => $campaignHistory->id
        ])->get();

        //Stop if there aren't any new visitors
        if ($targets->count() == 0) {
            return null;
        }
        $campaigns = Campaigns::find($campaignHistory->campaign_id);
        $date = (new \DateTime())->format('Y-m-d');
        $fn = $date . '-' . $campaignHistory->campaign_id.'-'.$campaignHistory->id.'.csv';
        $rows[] ='"ip", "discount_code", "store_id"';

        foreach ($targets as $target) {
            $rows[] =  '"'. $target->browser_ip
                . '","' .  $target->discount_code
                . '","' . $campaignHistory->campaign_id
                .'"';
        }

        Storage::disk('eltoro')->put($fn, implode(PHP_EOL,$rows));

    }

    public function sendComplete(array $campaignLimit)
    {
        $date = (new \DateTime())->format('Y-m-d');
        $fn = $date . '-max-to-send.csv';
        $rows[] ='"store_id", "max_to_send", "current_sent", "front", "back"';

        foreach ($campaignLimit as $campaignId => $limit) {
            $rows[] =  $campaignId
                . ',' .  $limit['max']
                . ',' .  $limit['current']
                . ',' .  $limit['front']
                . ',' .  $limit['back'];
        }

        Storage::disk('eltoro')->put($fn, implode(PHP_EOL,$rows));
    }

}
