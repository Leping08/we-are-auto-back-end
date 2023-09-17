<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_races', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('youtube_video_id');
            $table->foreignId('series_id')->nullable();
            $table->foreignId('track_id')->nullable();
            $table->foreignId('season_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potential_races');
    }
};
