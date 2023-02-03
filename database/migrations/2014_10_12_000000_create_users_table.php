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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
              $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('telephone')->nullable();
            $table->boolean('enabled')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('code')->nullable();
            $table->string('date_naissance')->nullable();
             $table->string('role')->nullable();
            $table->boolean('credentials_non_expired')->nullable();
            $table->boolean('compte_actif')->nullable();
            $table->string('adresse')->nullable();
            $table->boolean('account_non_locked')->nullable();
            $table->boolean('account_non_expired')->nullable();
             $table->boolean('proprietaire')->default(0);
            $table->boolean('gestionnaire')->default(0);
            $table->boolean('superAdmin')->default(0);
            $table->string('token')->nullable();
            $table->string('inviter')->nullable();
            $table->timestamps();
        });
        
        Schema::table('UserCaisse', function (Blueprint $table) {
            $table->integer('user_id')->default(0);
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
};


// <?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//     public function up()
//     {
//         Schema::table('users', function (Blueprint $table) {
//             $table->unsignedBigInteger('role_id')->unsigned();
//             $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
//         });
//     }

//     /**
//      * Reverse the migrations.
//      *
//      * @return void
//      */
//     public function down()
//     {
//         Schema::table('users', function (Blueprint $table) {
//              $table->dropForeign(['role_id']);
//         });
//     }
// };
