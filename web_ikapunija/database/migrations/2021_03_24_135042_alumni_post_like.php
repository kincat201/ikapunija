<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlumniPostLike extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni_post_likes', function (Blueprint $table) {
            $table->id();
            $table->integer('alumni_post_id')->nullable();
            $table->integer('alumni_id')->nullable();
            $table->integer('alumni_like_id')->nullable();
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
        Schema::dropIfExists('alumni_post_likes');
    }
}
