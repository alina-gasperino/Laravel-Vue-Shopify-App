<?php

namespace App\Http\Controllers;

use App\Models\Campaign\CampaignAudienceSizes;
use App\Models\Campaign\DesignHuddle;
use App\Models\Shop;
use App\Models\Shopify\Orders;
use App\Models\Shopify\Shopify;
use App\Models\Visitor;
use App\Models\VisitorIp;
use App\Models\Campaign\Campaigns;
use App\Models\Campaign\CampaignTargetHistory;
use App\Models\User\SocialProviders;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Dashboard\PrepareAnalyticsDataRequest;
use App\Http\Requests\GetQRCodeRequest;
use QrCode;
use App\Models\Analytics\Dynamo;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $userId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id;
            return $next($request);
        });

    }
}
