<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('series', function (Blueprint $table) {
            $table->text('full_name')->after('name')->nullable();
            $table->text('logo')->after('full_name')->nullable();
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

        Schema::table('series', function (Blueprint $table) {
            if (Schema::hasColumn('series', 'full_name')) {
                Schema::table('series', function (Blueprint $table) {
                    $table->dropColumn('full_name');
                });
            }

            if (Schema::hasColumn('series', 'logo')) {
                Schema::table('series', function (Blueprint $table) {
                    $table->dropColumn('logo');
                });
            }
        });

        Schema::enableForeignKeyConstraints();
    }
}
