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
        Schema::create('home_members', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(1);
            $table->unsignedBigInteger('user_id')->comment('Household head');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('home_id');
            $table->foreign('home_id')->references('id')->on('home');
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
        Schema::dropIfExists('home_members');
    }
};
