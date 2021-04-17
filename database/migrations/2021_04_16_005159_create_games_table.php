<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')
                ->constrained()
                ->onDelete('cascade');
            $table->json('hands');
            $table->unsignedInteger('player_score');
            $table->unsignedInteger('computer_score');
            $table->boolean('win')->default(false);
            $table->boolean('lose')->default(false);
            $table->boolean('tie')->default(false);
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
        Schema::dropIfExists('games');
    }
}
