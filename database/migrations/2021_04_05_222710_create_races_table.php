<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('length', 500)->nullable();
            $table->foreignId('series_id');
            $table->foreignId('track_id');
            $table->foreignId('season_id');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('finishes_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('races');
    }
}
