<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignHistoryStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_history_states', function (Blueprint $table) {
            $table->id();
            $table->string('state_name');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('campaign_history_states')->insert(
            [
                [
                    'id' => '1',
                    'state_name' => 'Created'
                ],
                [
                    'id' => '10',
                    'state_name' => 'List Compiled'
                ],
                [
                    'id' => '30',
                    'state_name' => 'Sent to El Toro'
                ],
                [
                    'id' => '60',
                    'state_name' => 'Sent to Next Page'
                ],
                [
                    'id' => '80',
                    'state_name' => 'No Recipients'
                ],
                [
                    'id' => '90',
                    'state_name' => 'Mailers Sent'
                ],
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
        Schema::dropIfExists('campaign_history_states');
    }
}
