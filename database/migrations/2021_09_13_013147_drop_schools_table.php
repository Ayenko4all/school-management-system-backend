<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('schools');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('school_type_id')->constrained('school_types');
            $table->string('school_name');
            $table->string('school_address');
            $table->string('bvn');
            $table->string('city');
            $table->string('lga');
            $table->string('state');
            $table->string('school_email_address')->unique();
            $table->string('school_telephone_address')->unique();
            $table->string('cac_document')->nullable();
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
