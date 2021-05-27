<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('grup_id')->unsigned()->nullable();
            $table->char('ktp_user', 16);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password_first', 8)->nullable();
            $table->string('password');
            $table->string('role', 50)->default('peternak');
            $table->timestamp('verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('grup_id')->references('id')->on('grup_peternaks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropForeign('users_peternakan_id_foreign');
        });
        Schema::dropIfExists('users');
    }
}
