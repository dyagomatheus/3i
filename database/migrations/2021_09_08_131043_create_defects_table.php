<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDefectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('defects')->insert([
            'name' => 'Blocks the Page (Tranca a Pag.)',
        ]);

        DB::table('defects')->insert([
            'name' => 'Exploded (explodiu)',
        ]);

        DB::table('defects')->insert([
            'name' => 'Leaking (vazando)',
        ]);

        DB::table('defects')->insert([
            'name' => 'Not recognize (não reconhece)',
        ]);

        DB::table('defects')->insert([
            'name' => 'Print failures (falha impressão)',
        ]);

        DB::table('defects')->insert([
            'name' => 'Problem of Gear (Engrenagem)',
        ]);

        DB::table('defects')->insert([
            'name' => 'Smudge on pag (Mancha)',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('defects');
    }
}
