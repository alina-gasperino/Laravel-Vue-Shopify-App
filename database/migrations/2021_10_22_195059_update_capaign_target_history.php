<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCapaignTargetHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->renameColumn('user_ip', 'browser_ip');
            $table->renameColumn('external_user_id', 'browser_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_target_history', function (Blueprint $table) {
            $table->renameColumn('browser_ip', 'user_ip');
            $table->renameColumn('browser_user_id','external_user_id');
        });
    }
}
