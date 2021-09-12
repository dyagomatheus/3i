<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberColumnInDevolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devolutions', function (Blueprint $table) {
            $table->string('number')->uniqid()->nullable();
            $table->string('status')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolutions', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropColumn('status');
        });
    }
}
