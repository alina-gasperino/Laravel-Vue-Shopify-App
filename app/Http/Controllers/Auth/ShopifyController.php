<?php

namespace App\Http\Controllers\Auth;

use App\Events\ShopConnected;
use Illuminate\Routing\Controller;
use App\Models\Shopify\Orders;
use App\Models\Shopify\Shopify;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User\User;
use App\Models\User\SocialProviders;


class ShopifyController extends Controller
{
    public function callback(Request $request)
    {
        $shopifyUser = Socialite::driver('shopify')->stateless()->user();
        $users = User::firstOrCreate(
            ['email' => $shopifyUser->getEmail()],
            [
                'name' => $shopifyUser->getName(),
                'password' => User::makePassword()
            ]
        );
        $socialUser = SocialProviders::updateOrCreate(
            [
                'provider_id' => 1,
                'provider_user_id' => $shopifyUser->getId()
            ],
            [
                'user_id' => $users->id,
                'avatar' => $shopifyUser->getAvatar(),
                'nickname' => $shopifyUser->getNickname(),
                'access_token' => $shopifyUser->token,
                'refresh_token' => $shopifyUser->refreshToken,
                'token_expiration' => $shopifyUser->expiresIn
            ]
        );

        if ($socialUser->wasRecentlyCreated) {
            Log::info('Recently Created True');
            $shop = Shop::create([
                'user_id' => $users->id,
                'shop_name' => $shopifyUser->getNickname(),
                'external_shop_id' => $shopifyUser->getId()
            ]);

            (new Shopify())->addTrackingPixel($shopifyUser->token, $shopifyUser->getNickname());

            ShopConnected::dispatch($shop, $socialUser);

        } else {
            ///@ToDo: look into why this is false when it should be true
            $shop = Shop::firstOrCreate([
                'shop_name' => $shopifyUser->getNickname()],[
                'user_id' => $users->id,
                'external_shop_id' => $shopifyUser->getId()
            ]);

            (new Shopify())->addTrackingPixel($shopifyUser->token, $shopifyUser->getNickname());

            ShopConnected::dispatch($shop, $socialUser);
        }

        Auth::login($users, true);

        $user = Auth::user();

        return redirect()->intended(route('gettingStarted'));

    }
}
