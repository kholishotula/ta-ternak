<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFkToTernaks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ternaks', function (Blueprint $table) {
            $table->bigInteger('kematian_id')->unsigned()->nullable();
            $table->bigInteger('penjualan_id')->unsigned()->nullable();
            
            $table->foreign('kematian_id')->references('id')->on('kematians')->onDelete('cascade');
            $table->foreign('penjualan_id')->references('id')->on('penjualans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ternaks', function (Blueprint $table) {
            $table->dropForeign(['kematian_id', 'penjualan_id']);
            $table->dropColumn(['kematian_id', 'penjualan_id']);
        });
    }
}
