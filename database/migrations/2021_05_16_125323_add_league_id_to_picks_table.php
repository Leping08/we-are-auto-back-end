<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeagueIdToPicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('picks', function (Blueprint $table) {
            $table->foreignId('league_id')->after('user_id');
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

        Schema::table('picks', function (Blueprint $table) {
            if (Schema::hasColumn('picks', 'league_id')) {
                Schema::table('picks', function (Blueprint $table) {
                    $table->dropForeign('picks_league_id_foreign');
                    $table->dropColumn('league_id');
                });
            }
        });

        Schema::enableForeignKeyConstraints();
    }
}
