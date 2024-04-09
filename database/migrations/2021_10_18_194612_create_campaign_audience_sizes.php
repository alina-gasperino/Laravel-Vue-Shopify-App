<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignAudienceSizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_audience_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });


        \Illuminate\Support\Facades\DB::table('campaign_audience_sizes')->insert(
            [
                [
                    'id' => '10',
                    'name' => 'All Vistors'
                ],
                [
                    'id' => '20',
                    'name' => 'Add to Carts'
                ],
                [
                    'id' => '30',
                    'name' => 'Abandoned Checkouts'
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
        Schema::dropIfExists('campaign_audience_sizes');
    }
}
