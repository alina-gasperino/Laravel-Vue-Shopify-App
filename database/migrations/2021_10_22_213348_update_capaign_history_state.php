<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCapaignHistoryState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_history', function (Blueprint $table) {
            $table->integer('state')->default(1)->change();
            $table->renameColumn('state', 'state_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign_history', function (Blueprint $table) {
            $table->renameColumn('state_id', 'state');
        });
    }
}
