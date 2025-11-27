<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('person_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_person_id')->constrained('people')->onDelete('cascade');
            $table->enum('relation_type', ['parent', 'child', 'spouse']); // Тип связи
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('person_relations');
    }
};
