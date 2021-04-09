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
            $table->string('name');
            $table->string('username')->unique();
            $table->string('role', 50)->default('peternak');
            $table->bigInteger('peternakan_id')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password_first')->nullable();
            $table->string('password');
            $table->boolean('register_from_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('peternakan_id')->references('id')->on('peternakans')->onDelete('cascade');
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
