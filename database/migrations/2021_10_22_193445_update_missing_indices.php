<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMissingIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_history', function (Blueprint $table) {
            $table->index('campaign_id');
        });

        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->index(['campaign_id','user_ip']);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
