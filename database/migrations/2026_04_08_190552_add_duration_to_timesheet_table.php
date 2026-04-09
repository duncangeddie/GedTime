<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timesheet', function (Blueprint $Table) {
            $Table->integer('duration')->default(0)->after('time_end');
        });

        DB::unprepared('
            CREATE TRIGGER timesheet_set_duration_before_insert
            BEFORE INSERT ON timesheet
            FOR EACH ROW
            SET NEW.duration = IFNULL(
                GREATEST(
                    FLOOR(TIME_TO_SEC(TIMEDIFF(NEW.time_end, NEW.time_start)) / 60),
                    0
                ),
                0
            )
        ');

        DB::unprepared('
            CREATE TRIGGER timesheet_set_duration_before_update
            BEFORE UPDATE ON timesheet
            FOR EACH ROW
            SET NEW.duration = IFNULL(
                GREATEST(
                    FLOOR(TIME_TO_SEC(TIMEDIFF(NEW.time_end, NEW.time_start)) / 60),
                    0
                ),
                0
            )
        ');

        DB::statement('
            UPDATE timesheet
            SET duration = IFNULL(
                GREATEST(
                    FLOOR(TIME_TO_SEC(TIMEDIFF(time_end, time_start)) / 60),
                    0
                ),
                0
            )
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS timesheet_set_duration_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS timesheet_set_duration_before_update');

        Schema::table('timesheet', function (Blueprint $Table) {
            $Table->dropColumn('duration');
        });
    }
};
