<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('fullName');
                $table->string('email');
                $table->string('phoneNumber')->nullable();
                $table->string('companyName')->nullable();
                $table->string('gender')->nullable();
                $table->boolean('ifTrail')->default(0);
                $table->string('password');
                $table->string('role')->default('user');
                $table->string('verify_code')->nullable();
                $table->boolean('verified')->default(0);
                $table->string('img')->nullable();
                $table->string('subscription')->nullable();
                $table->string('created_at')->nullable();
                $table->string('updated_at')->nullable();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
