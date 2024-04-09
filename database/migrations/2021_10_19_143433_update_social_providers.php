<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSocialProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('social_providers')->insert(
            [
                [
                    'id' => '1',
                    'provider_name' => 'Shopify'
                ],
                [
                    'id' => '2',
                    'provider_name' => 'Design Huddle'
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('social_providers')->delete([1, 2]);
    }
}
