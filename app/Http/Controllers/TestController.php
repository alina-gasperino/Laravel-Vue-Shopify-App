<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign\Campaigns;
use App\Models\Shopify\Shopify;
use App\Models\Shop;
use App\Models\Visitor;
use App\Models\VisitorIp;
use App\Models\User\User;
use App\Models\User\SocialProviders;
use App\Models\Campaign\CampaignHistory;
use App\Models\Campaign\CampaignTargetHistory;
use App\Models\Campaign\CampaignCodes;
use Auth;
use Ramsey\Uuid\Uuid;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $campaign = Campaigns::find(18);

        $shopify = new Shopify();
        $social = (new SocialProviders)->getShopifyById(Auth::id());
        $priceRule = $shopify->submitPriceRule($social, [
            'discount_prefix'       =>  $campaign->discount_prefix,
            'discount_type'         =>  $campaign->discount_type,
            'discount_amount'       =>  $campaign->discount_amount,
        ]);

        //Save priceRuleId in to Table required for DiscountCode
        $campaign->discount_price_rule_id = $priceRule->price_rule->id;
        $campaign->save();
        dd($priceRule);
    }

    public function createUniqueCode()
    {
        $campaign = Campaigns::find(18);

        $shopify = new Shopify();
        $codes = new CampaignCodes();
        $social = (new SocialProviders)->getShopifyById(Auth::id());

        $campaignHistory = CampaignHistory::create([
            'campaign_id' => $campaign->id,
            'shop_id' => $campaign->shop->id,
            'state_id' => 1
        ]);

        $discountCode = $codes->createUniqueCode($social, $campaign);

        $campaignTarget = CampaignTargetHistory::create([
            'id' => Uuid::uuid4(),
            'shop_id' => $campaign->shop_id,
            'campaign_id' => $campaign->id,
            'campaign_history_id' => $campaignHistory->id,
            'discount_code' => $discountCode,
            'browser_ip' => '24.176.15.117'
        ]);

        dd([$campaignHistory, $discountCode, $campaignTarget]);


    }

    public function customLogin(){
        $users = User::where('email', 'tejasamandaliya@gmail.com')->first();

        Auth::login($users, true);

        $user = Auth::user();

        return redirect()->intended(route('gettingStarted'));
    }

    public function dumpVisitors(){

        for($i=1;$i<=50;$i++){
            VisitorIp::create([
                'visitor_id'    => 14,
                'variant_id'    => NULL,
                'path'          => '/products/evoke-1-5l-jug-kettle-more-colours-available-104405',
                'session'       => 'eyJ0aW1lc3RhbXAiOjE2NDExODkwODA1NDIsImlwIjoi',
                'browser_ip'    => rand(100,255).'.'.rand(100,255).'.'.rand(100,255).'.'.rand(100,255),
            ]);
            VisitorIp::create([
                'visitor_id'    => 15,
                'variant_id'    => NULL,
                'path'          => '/products/morphy-richards-evoke-1-5l-retro-kettle-white-rose-gold',
                'session'       => 'eyJ0aW1lc3RhbXAiOjE2NDExODkwODA1NDIsImlwIjoiM',
                'browser_ip'    => rand(100,255).'.'.rand(100,255).'.'.rand(100,255).'.'.rand(100,255),
            ]);
        }

    }
}
