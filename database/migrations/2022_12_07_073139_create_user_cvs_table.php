<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('user_cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobs_poster_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->string("CV");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_cvs');
    }
};
