<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('room_number')->unique();
            $table->string('type');
            $table->decimal('monthly_price', 12, 2);
            $table->unsignedTinyInteger('floor');
            $table->unsignedTinyInteger('capacity')->default(1);
            $table->string('status');
            $table->json('facilities');
            $table->text('description');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
