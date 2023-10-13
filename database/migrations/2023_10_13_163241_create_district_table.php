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
        Schema::create('district', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('name', 45)->nullable(false);
            $table->string('province_id', 4)->nullable(false);
            $table->foreign('province_id')->references('id')->on('province');
            $table->string('department_id', 2)->nullable(false);
            $table->foreign('departament_id')->references('id')->on('departament');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('district');
    }
};
