<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupPeternaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grup_peternaks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_grup');
            $table->string('alamat');
            $table->string('provinsi', 50);
            $table->string('kab_kota', 50);
            $table->string('kecamatan', 50);
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('peternakans');
    }
}
