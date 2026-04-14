<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $Table) {
            $Table->boolean('use_world_clocks')->default(false)->after('country');
            $Table->unsignedTinyInteger('world_clock_count')->nullable()->after('use_world_clocks');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $Table) {
            $Table->dropColumn([
                'use_world_clocks',
                'world_clock_count',
            ]);
        });
    }
};
