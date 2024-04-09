<?php

namespace App\Providers;

use App\Models\Shop;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias(\SocialiteProviders\Shopify\Provider::class, \App\Overrides\Socialite\Shopify\Provider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\URL::forceScheme('https');
        Cashier::useCustomerModel(Shop::class);
    }
}
