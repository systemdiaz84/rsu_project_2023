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
        Schema::create('evolution_photos', function (Blueprint $table) {
            $table->id();
            
            $table->string('url', 100);
            $table->unsignedBigInteger('evolution_id');
            $table->foreign('evolution_id')->references('id')->on('evolutions')->onDelete('cascade');

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
        Schema::dropIfExists('evolution_photos');
    }
};
