<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileCamposUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Asegurarse de que los campos existan y se puedan agregar
            $table->string('surname')->nullable()->after('name'); // Campo apellido
            $table->string('username')->unique()->after('surname'); // Campo usuario
            $table->integer('age')->nullable()->after('username'); // Campo edad
            $table->float('weight')->nullable()->after('age'); // Campo peso
            $table->float('height')->nullable()->after('weight'); // Campo altura
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['surname', 'username', 'age', 'weight', 'height']);
        });
    }
}
