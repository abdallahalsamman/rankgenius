<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wordpress_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained();
            $table->string('url');
            $table->string('username');
            $table->string('app_password');
            $table->string('categories');
            $table->string('tags');
            $table->string('author');
            $table->string('status');
            $table->integer('time_gap');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wordpress_integrations');
    }
};
