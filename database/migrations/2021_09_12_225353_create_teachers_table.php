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
            $table->string('educational_level');
            $table->string('tertiary_certificate')->nullable();
            $table->string('tertiary_institution')->nullable();
            $table->string('graduating_date');
            $table->string('primary_school')->nullable();
            $table->string('primary_certificate')->nullable();
            $table->string('secondary_school')->nullable();
            $table->string('secondary_certificate')->nullable();
            $table->string('others_institution')->nullable();
            $table->string('others_certificate')->nullable();
            $table->string('identity_card')->nullable();
            $table->string('identity_card_photo')->nullable();
            $table->string('guarantor_one');
            $table->string('guarantor_two')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('lga');
            $table->string('address');
            $table->string('status')->default('inactive');
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
