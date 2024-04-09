<?php

namespace App\Models\Campaign;

use App\Models\Shopify\Shopify;
use App\Models\User\SocialProviders;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Mockery\Exception\InvalidOrderException;

class CampaignCodes
{
    use HasFactory;


    public function createUniqueCode(SocialProviders $social, Campaigns $campaign)
    {
        $random = Str::random(6);

        //nCR tells us we'll get 1.9Million possible values as long as campaigns aren't
        // more than 100k per prefix we're probably fine.  If we think they will grow beyond that we
        // may want to consider a better method here.  We could also do a checker to see when we approach this number
        for ($x = 0; $x <= 25; $x++) {
            $discountCode = $campaign->discount_prefix.'-'.$random;
            $res = CampaignTargetHistory::where([
                'campaign_id' => $campaign->id,
                'discount_code' => $discountCode
            ])->first();

            if ($res === null) {
                //Send to Shopify
                $response = (new Shopify())->submitDiscountCode($social, $campaign, $discountCode);
                return $discountCode;
            }
        }

        throw new \Exception('Unable to Find a unique discount code after 25 attempts');
    }

}
