<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caisses', function (Blueprint $table) {
            $table->id();
            $table->string('nom_prenom')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->string('cnss')->nullable();
             $table->boolean('compte_actif')->nullable();
            $table->string('logo');
            $table->string('nif')->unique();
            $table->string('rccm')->unique();
            $table->string('raison_social')->unique();
            $table->string('inviter')->nullable();
            $table->timestamps();
        });
        
        
          Schema::table('UserCaisse', function (Blueprint $table) {
            $table->integer('caisse_id')->default(0);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->integer('caisse_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caisses');
    }
};
