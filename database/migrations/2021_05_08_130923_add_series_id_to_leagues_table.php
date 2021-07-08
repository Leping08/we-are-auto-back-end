<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeriesIdToLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('leagues', function (Blueprint $table) {
            $table->foreignId('series_id')->after('name');
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
        Schema::disableForeignKeyConstraints();

        Schema::table('leagues', function (Blueprint $table) {
            if (Schema::hasColumn('leagues', 'series_id')) {
                Schema::table('leagues', function (Blueprint $table) {
                    $table->dropForeign('leagues_series_id_foreign');
                    $table->dropColumn('series_id');
                });
            }
        });

        Schema::enableForeignKeyConstraints();
    }
}
