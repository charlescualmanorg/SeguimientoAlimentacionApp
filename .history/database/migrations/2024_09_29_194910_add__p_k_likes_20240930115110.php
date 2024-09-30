<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPKLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes', function (Blueprint $table) {

            $table->unique(['post_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('likes', function (Blueprint $table) {
            // Eliminar las claves foráneas antes de eliminar la restricción única
            $table->dropForeign(['post_id']);
            $table->dropForeign(['user_id']);
            // Eliminar la restricción única
            $table->dropUnique(['post_id', 'user_id']);
        });

        Schema::dropIfExists('likes');
    }
    }
}
