<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id')->index('id');
            $table->string('senderId')->index('senderId')->nullable();
            $table->string('receiverId')->index('receiverId')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('referenceId')->index('referenceId')->nullable();
            $table->string('status')->nullable();
            $table->integer('deleted')->default(0)->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
