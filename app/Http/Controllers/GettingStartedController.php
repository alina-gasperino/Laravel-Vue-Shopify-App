<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Campaign\CampaignAudienceSizes;
use App\Models\User\SocialProviders;
use App\Models\Campaign\DesignHuddle;
use App\Http\Requests\GetQRCodeRequest;
use QrCode;
use Illuminate\Support\Facades\Auth;


class GettingStartedController extends Controller
{
    public function index()
    {
        $shop = new Shop();
        $available = $shop->shopsWithoutCampaigns($this->userId);
        $audienceSizes = CampaignAudienceSizes::all();
        $shopifyUser = (new SocialProviders)->getShopifyById(Auth::id());
        $DH = (new DesignHuddle)->firstOrCreate($shopifyUser);

        return view('getting-started', [
            'availableShops' => $available,
            'audienceSizes' => $audienceSizes,
            'userShop' => $shopifyUser->nickname,
            'userToken' => $DH->access_token,
        ]);
    }

    public function getQRCode(GetQRCodeRequest $request)
    {
        if($request->url) {
            return QrCode::generate($request->url);
        }
    }
}
