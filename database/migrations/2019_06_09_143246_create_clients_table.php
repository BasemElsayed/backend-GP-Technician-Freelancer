<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobileNumber');
            $table->string('address')->nullable();
            $table->double('xCordinate')->nullable();
            $table->double('yCordinate')->nullable();
            $table->string('personalImage')->default("Default_profile_picture.jpg");
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('numberOfJobsDone')->default(0);
            $table->integer('numberOfCurrentRequests')->default(0);
            $table->integer('typeOfUsers');
            $table->boolean('allowedToRequest')->default(1);
            $table->double('totalRate')->default(5);
            $table->rememberToken();
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
        Schema::dropIfExists('clients');
    }
}
