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
            $table->id();
            $table->string('nombre',255);
            $table->string('apellido_paterno',255);
            $table->string('apellido_materno')->nullable();
            $table->string('telefono',10);
            $table->string('email',255);
            $table->string('sexo');
            $table->date('fecha_nacimiento');
            $table->string('password',255);
            //preguntar por contraseña
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
        Schema::dropIfExists('users');
    }
}
