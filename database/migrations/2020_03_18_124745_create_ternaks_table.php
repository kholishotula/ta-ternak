<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTernaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ternaks', function (Blueprint $table) {
            $table->char('necktag', 6)->primary();
            $table->bigInteger('ras_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pemilik_id')->unsigned();
            $table->string('jenis_kelamin', 20);
            $table->date('tgl_lahir')->nullable();
            $table->float('bobot_lahir')->nullable();
            $table->time('pukul_lahir')->nullable();
            $table->integer('lama_dikandungan')->nullable();
            $table->integer('lama_laktasi')->nullable();
            $table->date('tgl_lepas_sapih')->nullable();
            $table->char('necktag_ayah', 6)->nullable();
            $table->char('necktag_ibu', 6)->nullable();
            $table->string('cacat_fisik')->nullable();
            $table->string('ciri_lain')->nullable();
            $table->boolean('status_ada')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('ras_id')->references('id')->on('ras')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pemilik_id')->references('id')->on('pemiliks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ternaks', function(Blueprint $table)
        {
            $table->dropForeign('ternaks_pemilik_id_foreign');
            $table->dropForeign('ternaks_ras_id_foreign');
            $table->dropForeign('ternaks_kematian_id_foreign');
            $table->dropForeign('ternaks_peternakan_id_foreign');
        });
        Schema::dropIfExists('ternaks');
    }
}
