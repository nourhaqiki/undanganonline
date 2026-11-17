<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('edit_token', 64)->unique();
            $table->string('category')->default('pernikahan');
            $table->string('theme_slug');
            $table->string('title');
            $table->json('event_data');
            $table->timestamps();

            $table->foreign('theme_slug')->references('slug')->on('themes')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
