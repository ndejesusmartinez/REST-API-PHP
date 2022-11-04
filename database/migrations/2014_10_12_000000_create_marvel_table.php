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
        Schema::create('marvel', function (Blueprint $table) {
            $table->id();
            $table->string('idHeroe')->unique();
            $table->string('name');
            $table->string('urlImage'); 
            $table->string('comics');
            $table->string('series');
        });
    }
};