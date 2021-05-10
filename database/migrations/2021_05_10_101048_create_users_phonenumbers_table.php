<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPhonenumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users_phonenumbers')) {
            return;
        }        
        Schema::create('users_phonenumbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('user_id')->index()->comment('ID in users');
            $table->string('phone_number')->comment('User has phone number');            
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
        Schema::dropIfExists('users_phonenumbers');
    }
}
