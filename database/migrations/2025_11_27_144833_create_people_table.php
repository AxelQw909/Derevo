<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_tree_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('patronymic')->nullable(); // Отчество
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('gender'); // 'male', 'female'
            $table->string('role_in_family')->nullable(); // 'son', 'mother', 'father', etc.
            $table->string('photo_path')->nullable();
            $table->text('bio')->nullable(); // Биография
            $table->integer('x_position')->default(0); // Для позиционирования на canvas
            $table->integer('y_position')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
