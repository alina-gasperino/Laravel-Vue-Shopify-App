<?php

namespace App\Models\Analytics;

use App\Models\Campaign\CampaignHistory;
use App\Models\Campaign\Campaigns;
use App\Models\Shop;
use App\Models\User\SocialProviders;
use App\Models\User\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CampaignAnalytics
{
    public function rollupCampaignHistory(Campaigns $campaigns, \DateTime $startPeriod = null, \DateTime $endPeriod = null)
    {
        if (!$startPeriod) {
            $startPeriod = new \DateTime(date('Y-m-01'));
        }
        if (!$endPeriod) {
            $endPeriod = (new \DateTime(date('Y-m-01')))
            ->add(new \DateInterval('P1M'))
            ->sub(new \DateInterval('PT1S'));
        }

        $campaignHistories = CampaignHistory::where('created_at', '>=', $startPeriod->format('Y-m-d h:i:s'))
            ->where('created_at', '<=', $endPeriod->format('Y-m-d h:i:s'))
            ->where('campaign_id', '=', $campaigns->id)->get();

        $data = [
            'unique_ips' => 0,
            'mailers_sent' => 0,
            'cost_of_send' => 0,
            'total_revenue' => 0
        ];

        foreach ($campaignHistories as $history) {
            $data = [
                'unique_ips' => $data['unique_ips'] + $history->count_unique_ip,
                'mailers_sent' => $data['mailers_sent'] + $history->count_mailers_sent,
                'cost_of_send' => $data['cost_of_send'] + $history->cost_of_send,
                'total_revenue' => $data['total_revenue'] + $history->total_revenue
            ];
        }

        return $data;
    }

    public function getMonthlySent(Campaigns $campaigns)
    {
        $data = $this->rollupCampaignHistory($campaigns);
        return $data['mailers_sent'];
    }
}
