<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVerifiedPhoneNumberPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_verified_phone_number', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('verified_phone_number_id')->unsigned()->index();
            $table->foreign('verified_phone_number_id')->references('id')->on('verified_phone_numbers')->onDelete('cascade');
            $table->primary(['user_id', 'verified_phone_number_id'], 'user_verified_phone_number_user_phone_number_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_verified_phone_number');
    }
}
