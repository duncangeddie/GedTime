<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $Table) {
            $Table->string('world_clock_one')->nullable()->after('country');
            $Table->string('world_clock_two')->nullable()->after('world_clock_one');
            $Table->string('world_clock_three')->nullable()->after('world_clock_two');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $Table) {
            $Table->dropColumn([
                'world_clock_one',
                'world_clock_two',
                'world_clock_three',
            ]);
        });
    }
};
