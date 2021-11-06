<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLgaAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('lga_areas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('lga_areas', function (Blueprint $table) {
            $table->id();
            $table->string('lga');
            $table->foreignId('state_id')->constrained('states');
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
