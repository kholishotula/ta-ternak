<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerkembangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perkembangans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('necktag', 6);
            $table->date('tgl_perkembangan');
            $table->float('berat_badan')->nullable();
            $table->float('panjang_badan')->nullable();
            $table->float('lingkar_dada')->nullable();
            $table->float('tinggi_pundak')->nullable();
            $table->float('lingkar_skrotum')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            
            $table->foreign('necktag')->references('necktag')->on('ternaks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perkembangans');
    }
}
