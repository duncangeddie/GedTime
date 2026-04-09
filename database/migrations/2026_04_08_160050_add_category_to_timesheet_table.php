<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timesheet', function (Blueprint $Table) {
            $Table->string('category')->after('project');
        });
    }

    public function down(): void
    {
        Schema::table('timesheet', function (Blueprint $Table) {
            $Table->dropColumn('category');
        });
    }
};
