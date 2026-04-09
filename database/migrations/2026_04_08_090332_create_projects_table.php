<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $Table) {
            $Table->id();
            $Table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $Table->string('project_name');
            $Table->string('status');
            $Table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
