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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('player_one_id');
            $table->unsignedBigInteger('player_two_id');
            $table->string('status');
            $table->unsignedBigInteger('winner_id')->nullable();
            $table->json('results')->nullable();
            $table->unsignedSmallInteger('fase');
            $table->boolean('champion')->default(0);

            $table->foreign('tournament_id')
                ->references('id')->on('tournaments')->onDelete('cascade');

            $table->foreign('player_one_id')
                ->references('id')->on('players')->onDelete('cascade');

            $table->foreign('player_two_id')
                ->references('id')->on('players')->onDelete('cascade');

            $table->foreign('winner_id')
                ->references('id')->on('players')->onDelete('cascade');

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
};
