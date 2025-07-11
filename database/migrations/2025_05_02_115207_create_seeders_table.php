<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seeders', function (Blueprint $table): void {
            $table->id();
            $table->string('seeder');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seeders');
    }
};
