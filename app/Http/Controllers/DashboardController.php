<?php

namespace App\Http\Controllers;

use App\Models\Campaign\Campaigns;
use App\Models\User\SocialProviders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $shopifyUser = (new SocialProviders)->getShopifyById(Auth::id());
        $campaigns = Campaigns::all();
        return view('dashboard', [
            'userShop' => $shopifyUser->nickname,
            'campaigns' => $campaigns
        ]);
    }
}
