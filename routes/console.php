<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
Artisan::command('refresh-social', function () {
    $this->comment((new \App\Models\User\SocialProviders())->getExpiredTokens());
})->describe('Refresh Social Tokens');
Artisan::command('process-campaigns', function () {
    $this->comment((new \App\Models\Campaign\CampaignCron())->queueCampaigns());
})->describe('Run the Campaign Process Job Manually');
Artisan::command('postcard-export', function () {
    $this->comment((new \App\Models\Campaign\DesignHuddle())->processPostcardQueue());
})->describe('Get PDF From Design Huddle');
Artisan::command('fetch-orders', function () {
    $this->comment((new \App\Models\Cron())->fetchOrderHistory());
})->describe('Updates the order history of shops');
