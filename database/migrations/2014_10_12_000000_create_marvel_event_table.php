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
        Schema::create('marvel_event', function (Blueprint $table) {
            $table->id();
            $table->string('idEvento')->unique();
            $table->string('title');
            $table->string('description'); 
            $table->string('start');
            $table->string('end');
            $table->string('img');
        });
    }
};