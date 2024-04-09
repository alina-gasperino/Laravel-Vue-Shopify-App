<?php

namespace App\Models\Campaign;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignTargetHistory extends Model
{
    use HasFactory;

    protected $table = 'campaign_target_history';

    protected $guarded = [];

    public function getExemptUsers($campaignId) {
        $exempt = [];
        $historyFrom = (new \DateTime())->sub(new \DateInterval('P30D'));
        $mailers = $this->where('campaign_id', $campaignId)->where('created_at', '>', $historyFrom->format('Y-m-d H:i:s'))->get();
        foreach ($mailers as $mail) {
            $exempt[$mail->browser_ip] = 1;
        }

        return $exempt;
    }

    public function campaign()
    {
        return $this->belongsTo(Campaigns::class,'campaign_id');
    }


}
