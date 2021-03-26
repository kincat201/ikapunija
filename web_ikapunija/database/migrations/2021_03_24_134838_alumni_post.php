<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlumniPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumni_posts', function (Blueprint $table) {
            $table->id();
            $table->integer('alumni_id')->nullable();
            $table->string('types')->nullable();
            $table->text('content')->nullable();
            $table->string('media')->nullable();
            $table->integer('likes')->nullable();
            $table->integer('comments')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('alumni_posts');
    }
}
