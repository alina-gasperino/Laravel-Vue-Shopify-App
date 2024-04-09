<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign\CampaignAudienceSizes;
use App\Models\User\SocialProviders;
use App\Models\Campaign\DesignHuddle;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class AutomatedRetargetingController extends Controller
{
    public function index()
    {
        $shop = new Shop();
        $available = $shop->shopsWithoutCampaigns($this->userId);
        $audienceSizes = CampaignAudienceSizes::all();
        $shopifyUser = (new SocialProviders)->getShopifyById(Auth::id());
        $DH = (new DesignHuddle)->firstOrCreate($shopifyUser);

        return view('automated-retargeting.index', [
            'availableShops' => $available,
            'audienceSizes' => $audienceSizes,
            'userShop' => $shopifyUser->nickname,
            'userToken' => $DH->access_token,
        ]);
    }
}
