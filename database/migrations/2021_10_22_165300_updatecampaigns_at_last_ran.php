<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatecampaignsAtLastRan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dateTime('last_ran')->after('audience_size_id')->nullable();
            $table->float('total_revenue')->after('audience_size_id')->nullable();
            $table->float('total_cost')->after('audience_size_id')->nullable()->unsigned();
            $table->integer('total_recipients')->after('audience_size_id')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('last_ran');
            $table->dropColumn('total_revenue');
            $table->dropColumn('total_cost');
            $table->dropColumn('total_recipients');
        });
    }
}
