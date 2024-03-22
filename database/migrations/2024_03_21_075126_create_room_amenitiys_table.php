<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_amenitiys', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('added_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('room_relation_amenitiys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room')->constrained('room_classes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('added_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('aminity')->constrained('room_amenitiys')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_amenitiys');
    }
};
