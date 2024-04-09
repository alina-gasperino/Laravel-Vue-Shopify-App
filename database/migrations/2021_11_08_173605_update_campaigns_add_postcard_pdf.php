<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignsAddPostcardPdf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->text('postcard_design_url')->after('project_id')->nullable();
        });

        Schema::create('design_huddle_postcard_export_queue', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('project_id')->unsigned();
            $table->string('design_huddle_project_id');
            $table->string('job_id');
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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('postcard_design_url');
        });

        Schema::dropIfExists('design_huddle_postcard_export_queue');
    }
}
