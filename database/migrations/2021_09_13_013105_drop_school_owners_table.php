<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSchoolOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('school_owners');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('school_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('school_id')->constrained('schools');
            $table->string('first_name');
            $table->string('last_name' );
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->string('bvn');
            $table->string('id_card_type');
            $table->string('date_of_birth');
            $table->string('id_card_photo');
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }
}
