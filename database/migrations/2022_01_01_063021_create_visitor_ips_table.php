<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitorIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_ips', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('visitor_id')->unsigned();
            $table->string('variant_id')->nullable();
            $table->string('path');
            $table->string('session');
            $table->string('browser_ip');
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
        Schema::dropIfExists('visitor_ips');
    }
}
