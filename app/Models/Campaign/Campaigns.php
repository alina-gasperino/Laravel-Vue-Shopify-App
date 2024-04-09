<?php

namespace App\Models\Campaign;

use App\Models\Shop;
use App\Models\User\SocialProviders;
use App\Models\User\User;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Campaigns extends Model
{
    use HasFactory, SoftDeletes;

    const Prior_Customers = 'All unique prior customers';
    const Purchase_More_Then = 'Customers who purchased more than [X]';
    const purchase_More_Then_Times = 'Customers who purchased more than [X] times';
    const Top_Customer_By_Spend = 'Top [X]% of customers by spend';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function history()
    {
        return $this->hasMany(CampaignHistory::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function getAllCampaignsReadable($userId)
    {

        $campaignStates = new CampaignsState();
        $campaignAudienceSizes = new CampaignAudienceSizes();
        $shop = new Shop();

        $campaigns = DB::table($this->getTable())
            ->join($campaignStates->getTable(), $this->getTable().'.state_id', '=',
                $campaignStates->getTable().'.id')
            ->join($campaignAudienceSizes->getTable(), $this->getTable().'.audience_size_id', '=',
                $campaignAudienceSizes->getTable().'.id')
            ->join($shop->getTable(), $this->getTable().'.shop_id', '=',
                $shop->getTable().'.id')
            ->select(
                $this->getTable().'.*',
                $campaignStates->getTable().'.name as state_name',
                $campaignAudienceSizes->getTable().'.name as audience_size',
                $shop->getTable().'.shop_name',
            )
            ->where($this->getTable().'.user_id', $userId)
            ->whereNull($this->getTable().'.deleted_at')
            ->get();

        return $campaigns;
    }

    public function getAllDeletedCampaignsReadable($userId)
    {

        $campaignStates = new CampaignsState();
        $campaignAudienceSizes = new CampaignAudienceSizes();
        $shop = new Shop();

        $campaigns = DB::table($this->getTable())
            ->join($campaignStates->getTable(), $this->table.'.state_id', '=',
                $campaignStates->getTable().'.id')
            ->join($campaignAudienceSizes->getTable(), $this->table.'.audience_size_id', '=',
                $campaignAudienceSizes->getTable().'.id')
            ->join($shop->getTable(), $this->getTable().'.shop_id', '=',
                $shop->getTable().'.id')
            ->select(
                $this->getTable().'.*',
                $campaignStates->getTable().'.name as state_name',
                $campaignAudienceSizes->getTable().'.name as audience_size',
                $shop->getTable().'.shop_name'
            )
            ->where($this->getTable().'.user_id', $userId)
            ->whereNotNull($this->getTable().'.deleted_at')
            ->get();

        return $campaigns;
    }

    public function refreshThumbnail($projectId)
    {
        $project = $this->where(['project_id' => $projectId])->first();
    }
}
