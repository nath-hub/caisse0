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
         Schema::create('UserCaisse', function (Blueprint $table) {
            $table->string('admin')->default("non");
            $table->string('compta')->default("non");
            $table->string('stock')->default("non");
            $table->string('com')->default("non");
            $table->string('paie')->default("non");
            $table->string('immos')->default("non");
            $table->string('budget')->default("non");
            $table->string('rap')->default("non");
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
        Schema::table('UserCaisse', function (Blueprint $table) {
            $table->dropForeign(['caisse_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
