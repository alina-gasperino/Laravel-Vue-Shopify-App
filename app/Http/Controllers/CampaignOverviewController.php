<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign\CampaignAudienceSizes;
use App\Models\User\SocialProviders;
use App\Models\Campaign\DesignHuddle;
use App\Models\Shop;
use App\Models\Campaign\Campaigns;
use App\Models\Campaign\CampaignsState;
use Illuminate\Support\Facades\Redirect;

class CampaignOverviewController extends Controller
{
    public function index()
    {
        $shopifyUser = (new SocialProviders)->getShopifyById($this->userId);
        $campaigns = (new Campaigns)->getAllCampaignsReadable($this->userId);
        $audienceSizes = CampaignAudienceSizes::all();
        $archivedCampaigns = (new Campaigns)->getAllDeletedCampaignsReadable($this->userId);

        $shop = new Shop();
        $available = $shop->shopsWithoutCampaigns($this->userId);
        $DH = (new DesignHuddle)->firstOrCreate($shopifyUser);

        return view('campaign-overview.index', [
            'availableShops' => $available,
            'audienceSizes' => $audienceSizes,
            'userToken' => $DH->access_token,
            'userShop' => $shopifyUser->nickname,
            'campaigns' => $campaigns,
            'archivedCampaigns' => $archivedCampaigns
        ]);

    }

    public function startCampaign(Request $request)
    {
        // Could add validator against audience_states but there's no real need for the extra call as only malicious
        // users could break this, and they can only break their own campaigns, why would they do this.
        $rules = [
            'audience' => 'required|int',
            'discount_type' => ['required', Rule::in(['1', '2'])],
            'discount_amount' => ['required', 'regex:/^\$?([0-9]+)%?$/'],
            'discount_prefix' => ['required', 'string'],
            'project_id' => 'required|alpha_num',
            'shop_id' => 'required|int',
            'thumbnail_url' => ['required', 'url'],
            'max_sends' => ['int', 'min:1', 'nullable']
        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'thumbnail_url.required' => 'You must save your postcard design before proceeding',
            'project_id.required' => 'You must select a postcard template before proceeding',
            'discount_type.required' => 'Discount type is required',
            'discount_amount.required' => 'Discount amount is required',
            'discount_amount.regex' => 'Please enter a valid discount account',
            'max_sends.min' => 'Leave blank or enter a number great than 0',
            'max_sends.int' => 'You can only enter numbers for max sends',
            'shop_id.required' => 'Select a valid shop name',
            'audience.required' => 'You must select your audience size'

        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => $validator->getMessageBag()], 400);
        }

        $params = $validator->validate();
        $projectId = $params['project_id'];
        $campaign = Campaigns::firstOrNew(['shop_id' => $params['shop_id'], 'project_id' => $projectId]);
        $campaignState = CampaignsState::where(['name' => 'Active'])->first();

        $campaign->audience_size_id = $params['audience'];


        //Check to see if there is already an active campaign
        $activeCampaigns = Campaigns::where(['shop_id' => $params['shop_id'], 'state_id' => $campaignState->id])->get();
        if ($activeCampaigns->count() == 0) {
            $campaign->state_id = $campaignState->id;
        } else {
            $validator->errors()->add('campaign_id',
                'There is already an active campaign.  You can only have one active campaign at a time, your campaign was saved, however, it was not activated.');
            $campaign->deleted_at = (new \DateTime())->format('Y-m-d H:i:s');
        }

        $shopify = new Shopify();
        $social = (new SocialProviders)->getShopifyById(Auth::id());
        $priceRule = $shopify->submitPriceRule($social, $params);
        //Save priceRuleId in to Table required for DiscountCode
        $campaign->discount_amount = floatval(preg_replace("/[^0-9.]/", "", $params['discount_amount']));
        $campaign->discount_price_rule_id = $priceRule->price_rule->id;
        $campaign->discount_prefix = $params['discount_prefix'];
        $campaign->discount_type = $params['discount_type'];
        $campaign->thumbnail_url = $params['thumbnail_url'];
        $campaign->max_sends_per_period = $params['max_sends'];
        $campaign->campaign_name = $params['campaign_name'];
        $campaign->user_id = $this->userId;
        $campaign->save();

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => $validator->getMessageBag()], 400);
        }
        // Queue the design to be downloaded
        (new DesignHuddle())->exportDesignQueue($campaign);
        $shop = Shop::where([
            'id' => $campaign->shop_id,
            'user_id' => $this->userId
        ])->first();

        //if ($shop->subscribed(config('cashier.subscription.plan')))
            return response()->json([
                'errors' => [],
                'success' => [
                    'redirect' => route('campaign-overview.index')
                ]
            ], 200);
//        else {
//            $campaign->deleted_at = (new \DateTime())->format('Y-m-d H:i:s');
//            $campaign->save();
//            return response()->json([
//                'errors' => [
//                    'payment' => 'An active subscription is required',
//                ],
//            ], 402);
//        }
    }

    public function restartCampaign(Request $request)
    {

        $projectId = $request->route('project_id');
        $campaign = Campaigns::where([
            'user_id' => $this->userId,
            'project_id' => $projectId
        ])->withTrashed()->first();

        $campaignState = CampaignsState::where(['name' => 'Active'])->first();

        //Check to see if there is already an active campaign
        $activeCampaigns = Campaigns::where([
            'user_id' => $this->userId,
            'state_id' => $campaignState->id,
            'shop_id' => $campaign->shop_id
        ])->get();
        if ($activeCampaigns->count() > 0) {
            $message = [
                'error' => 'You have other active campaigns for this shop.  Please stop those campaigns before restarting this one.'
            ];
            return Redirect::back()->with($message);
        }

        $shop = Shop::where([
            'id' => $campaign->shop_id,
            'user_id' => $this->userId
        ])->first();

        //No Active Subscription
        // if (!$shop->subscribed(config('cashier.subscription.plan')))
        //     return view('form.payment', ['shop' => $shop]);

        if ($campaign === 0) {
            $message = [
                'error' => 'Something went wrong we were unable to restart your campaign.  Please contact us.'
            ];
        } else {
            $campaign->restore();
            $message = [
                'success' => 'Campaign successfully restarted'
            ];
        }

        return Redirect::route('campaign-overview.index')->with($message);
    }

    public function stopCampaign(Request $request)
    {
        $campaign = Campaigns::where([
            'user_id' => $this->userId, 'project_id' => $request->route('project_id')
        ])->delete();

        if ($campaign === 0) {
            $message = [
                'error' => 'Something went wrong we were unable to stop your campaign.  Please contact us.'
            ];
        } else {
            $message = [
                'success' => 'Campaign stopped successfully'
            ];
        }

        return redirect()->route('campaign-overview.index')->with($message);
    }   
}
