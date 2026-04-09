<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timesheet', function (Blueprint $Table) {
            $Table->id();
            $Table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $Table->string('project');
            $Table->date('date');
            $Table->time('time_start');
            $Table->time('time_end');
            $Table->string('task');
            $Table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheet');
    }
};
