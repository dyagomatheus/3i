<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolutionsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolution_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devolution_id')->nullable();
            $table->foreign('devolution_id')->references('id')->on('devolutions')->onDelete('cascade');
            $table->string('status');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('devolution_status');
    }
}
