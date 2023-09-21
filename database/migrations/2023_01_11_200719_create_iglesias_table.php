<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIglesiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iglesias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('nombre',255);
            $table->string('web',255);
            $table->string('calle',255);
            $table->string('numero',100);
            $table->string('colonia',255);
            $table->string('cp',5);
            $table->string('ciudad',255);
            $table->string('pais',255);
            $table->string('lat',255);
            $table->string('lng',255);
            $table->string('telefono',255);
            $table->string('facebook',255);
            $table->string('instagram',255);
            $table->string('youtube',255);
            $table->text('pastores',255);
            $table->text('horarios',255);
            $table->text('mision',255);
            $table->text('historia',255);
            //preguntar por vision
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
        Schema::dropIfExists('iglesias');
    }
}
