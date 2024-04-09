<?php

namespace App\Models\Shopify;

use App\Models\Campaign\Campaigns;
use App\Models\User\SocialProviders;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\HelperService;

class Shopify
{

    public function validateRequestor(Request $request) {

        $hmac = $request->header('X-Shopify-Hmac-SHA256');
        if (!$hmac)
            return false;
        $shopifySecret = config('service.shopify.client_secret');
        $body = $request->getContent();
        $calculated_hmac = base64_encode(hash_hmac('sha256', $body, $shopifySecret, true));
        return hash_equals($hmac, $calculated_hmac);

    }

    public function addTrackingPixel($accessToken, $storeName)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $res = $client->request('POST', 'https://'.$storeName.'/admin/api/'.$apiV.'/script_tags.json', [
            'headers' => ['X-Shopify-Access-Token' => $accessToken],
            'json' => [
                'script_tag' => [
                    'src' => config('services.shopify.script_tag'),
                    'event' => 'onload'
                ]
            ]
        ]);

        return $res;
    }

    public function getOrders(SocialProviders $social, $sinceDateTime, $limit = 50)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $url = 'https://'.$social->nickname.'/admin/api/'.$apiV.'/orders.json?status=any&limit='.$limit.'&updated_at_min='.$sinceDateTime.'&order=updated_at asc';
        $res = json_decode($client->request('GET', $url, [
                'headers' => ['X-Shopify-Access-Token' => $social->access_token],
            ])->getBody());

        return $res->orders;
    }

    public function getOrdersFormBegin(SocialProviders $social, $since_id, $limit = 50)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $url = 'https://'.$social->nickname.'/admin/api/'.$apiV.'/orders.json?status=any&limit='.$limit.($since_id != ''?'&since_id='.$since_id:'&order=updated_at asc');
        $res = json_decode($client->request('GET', $url, [
                'headers' => ['X-Shopify-Access-Token' => $social->access_token],
            ])->getBody());

        return $res->orders;
    }

    public function getAllOrders(SocialProviders $social, $token = null, $limit = 50)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $url = 'https://'.$social->nickname.'/admin/api/'.$apiV.'/orders.json?limit='.$limit.($token?'&page_info='.$token:'');
        $response = $client->request('GET', $url, [
            'headers' => ['X-Shopify-Access-Token' => $social->access_token],
        ]);
        $headers = $response->getHeaders();
        
        $nexttoken = HelperService::getPageInfoToken($headers, 'next');
        $prevtoken = HelperService::getPageInfoToken($headers);
        
        $res = json_decode($response->getBody());

        return [
            'data'  => $res->orders,
            'next'  => $nexttoken,
            'prev'  => $prevtoken, 
        ];
    }

    public function getAllProducts(SocialProviders $social, $token = null, $limit = 50)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $url = 'https://'.$social->nickname.'/admin/api/'.$apiV.'/products.json?limit='.$limit.($token?'&page_info='.$token:'');
        $response = $client->request('GET', $url, [
            'headers' => ['X-Shopify-Access-Token' => $social->access_token],
        ]);
        $headers = $response->getHeaders();
        
        $nexttoken = HelperService::getPageInfoToken($headers, 'next');
        $prevtoken = HelperService::getPageInfoToken($headers);
        
        $res = json_decode($response->getBody());

        return [
            'data'  => $res->products,
            'next'  => $nexttoken,
            'prev'  => $prevtoken, 
        ];
    }

    public function getAbandonCart(SocialProviders $social, $sinceDateTime)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $res = json_decode($client->request('GET',
            'https://'.$social->nickname.'/admin/api/'.$apiV.'/checkouts.json?created_at_min='.$sinceDateTime.'&order=updated_at%20asc', [
                'headers' => ['X-Shopify-Access-Token' => $social->access_token],
            ])->getBody());

        return $res->checkouts;
    }

    public function submitPriceRule(SocialProviders $social, $deal)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');
        $discountType = '';
        $title = $deal['discount_prefix'];

        //Get rid of the $/%
        // Shopify wants a BigDecimal, and it needs to be negative
        $discountAmount = -1 * floatval(preg_replace("/[^0-9.]/", "", $deal['discount_amount']));

        if ($deal['discount_type'] == 1) {
            $discountType = 'percentage';
            $title = strtoupper($title.$discountAmount.'PERCENTOFF');
        } elseif ($deal['discount_type'] == 2) {
            $discountType = 'fixed_amount';
            $title = strtoupper($title.$discountAmount.'OFF');
        }

        $startsAt = (new \DateTime())->format(\DateTime::ATOM);

        //Send the price rule to Shopify
        $res = $client->request('POST', 'https://'.$social->nickname.'/admin/api/'.$apiV.'/price_rules.json', [
            'headers' => ['X-Shopify-Access-Token' => $social->access_token],
            'json' => [
                "price_rule" => [
                    "title" => $title,
                    "target_type" => "line_item",
                    "target_selection" => "all",
                    "allocation_method" => "across",
                    "value_type" => $discountType,
                    "value" => $discountAmount,
                    "customer_selection" => "all",
                    "starts_at" => $startsAt
                ]
            ]
        ]);

        return json_decode($res->getBody());
    }

    public function submitDiscountCode(SocialProviders $social, Campaigns $campaign, $discountCode)
    {
        $client = new Client();
        $apiV = config('services.shopify.api_version');

        $res = $client->request(
            'POST',
            'https://'.$social->nickname.'/admin/api/'.$apiV.'/price_rules/'.$campaign->discount_price_rule_id.'/discount_codes.json',
            [
                'headers' => ['X-Shopify-Access-Token' => $social->access_token],
                'json' => [
                    "discount_code" => [
                        "code" => $discountCode
                    ]
                ]
            ]);

        return json_decode($res->getBody());
    }
}
