<?php

namespace App\Providers;

use App\Events\CampaignProcessComplete;
use App\Events\CampaignProcessed;
use App\Listeners\SendToCompleteElToro;
use App\Listeners\SendToElToro;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\Shopify\ShopifyExtendSocialite::class.'@handle',
        ],
        CampaignProcessed::class => [
            SendToElToro::class,
        ],
        CampaignProcessComplete::class => [
            SendToCompleteElToro::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
