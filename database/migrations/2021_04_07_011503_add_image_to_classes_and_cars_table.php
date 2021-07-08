<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToClassesAndCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->text('image')->after('car_class_id');
        });

        Schema::table('car_classes', function (Blueprint $table) {
            $table->text('image')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'image')) {
                Schema::table('cars', function (Blueprint $table) {
                    $table->dropColumn('image');
                });
            }
        });

        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'car_classes')) {
                Schema::table('cars', function (Blueprint $table) {
                    $table->dropColumn('car_classes');
                });
            }
        });
    }
}
