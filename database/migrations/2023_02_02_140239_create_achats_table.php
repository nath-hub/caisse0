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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_transaction')->nullable();
            $table->string('mode_paiement'->nullable();
            $table->json('detail_achat')->nullable();
            $table->string('tiers')->nullable();
            $table->string('compte_tresorerie')->nullable();
            $table->string('controle_equilibre')->nullable();
            $table->string('attache')->nullable();
            $table->string('solde_tiers')->nullable();
            $table->string('solde_tresorerie')->nullable();
            $table->string('annalation')->nullable();
            $table->string('date_annulation')->nullable();
            $table->integer('total_achat')->nullable();
            $table->timestamps();
            
        });
    }

    
        protected $casts = [
        'detail_achat' => 'array'
        ];
        
    
    public function down()
    {
        Schema::dropIfExists('achats');
    }
};
