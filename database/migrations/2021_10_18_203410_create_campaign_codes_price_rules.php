<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignCodesPriceRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_codes_price_rules', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('value_type');
            $table->text('value');
            $table->text('customer_selection');
            $table->text('target_type');
            $table->text('target_selection');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_codes_price_rules');
    }
}
