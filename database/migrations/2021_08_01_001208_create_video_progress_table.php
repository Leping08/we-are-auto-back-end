<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id');
            $table->foreignId('user_id');
            $table->integer('percentage')->nullable();
            $table->integer('seconds')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->integer('end_time')->nullable()->after('start_time');
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('video_progress');

        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'end_time')) {
                Schema::table('videos', function (Blueprint $table) {
                    $table->dropColumn('end_time');
                });
            }
        });

        Schema::enableForeignKeyConstraints();
    }
}
