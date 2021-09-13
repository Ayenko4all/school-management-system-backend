<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('martial_status');
            $table->string('photo');
            $table->string('educational_level');
            $table->string('certificate');
            $table->string('tertiary_institution')->nullable();
            $table->string('graduating_date');
            $table->string('primary_school_certificate')->nullable();
            $table->string('secondary_school_certificate')->nullable();
            $table->string('others')->nullable();
            $table->string('guarantor_one');
            $table->string('guarantor_two')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('teachers');
    }
}
