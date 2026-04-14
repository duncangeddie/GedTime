<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class PublicHolidaysController extends Controller
{
    public function SouthAfricaPublicHolidays(?int $Year = null): array
    {
        // Year Variables
        $Year = $Year ?? Carbon::now(config('app.timezone'))->year;

        // Easter Variables
        $EasterSunday = $this->GetEasterSunday($Year);

        // South Africa Public Holiday Variables
        $SouthAfricaPublicHolidays = [
            $this->FormatPublicHoliday("New Year's Day", Carbon::createMidnightDate($Year, 1, 1)),
            $this->FormatPublicHoliday('Human Rights Day', Carbon::createMidnightDate($Year, 3, 21)),
            $this->FormatPublicHoliday('Good Friday', $EasterSunday->copy()->subDays(2)),
            $this->FormatPublicHoliday('Family Day', $EasterSunday->copy()->addDay()),
            $this->FormatPublicHoliday('Freedom Day', Carbon::createMidnightDate($Year, 4, 27)),
            $this->FormatPublicHoliday("Workers' Day", Carbon::createMidnightDate($Year, 5, 1)),
            $this->FormatPublicHoliday('Youth Day', Carbon::createMidnightDate($Year, 6, 16)),
            $this->FormatPublicHoliday("National Women's Day", Carbon::createMidnightDate($Year, 8, 9)),
            $this->FormatPublicHoliday('Heritage Day', Carbon::createMidnightDate($Year, 9, 24)),
            $this->FormatPublicHoliday('Day of Reconciliation', Carbon::createMidnightDate($Year, 12, 16)),
            $this->FormatPublicHoliday('Christmas Day', Carbon::createMidnightDate($Year, 12, 25)),
            $this->FormatPublicHoliday('Day of Goodwill', Carbon::createMidnightDate($Year, 12, 26)),
        ];

        return $this->SortPublicHolidays($SouthAfricaPublicHolidays);
    }

    public function USAPublicHolidays(?int $Year = null): array
    {
        // Year Variables
        $Year = $Year ?? Carbon::now(config('app.timezone'))->year;

        // USA Public Holiday Variables
        $USAPublicHolidays = [
            $this->FormatPublicHoliday("New Year's Day", Carbon::createMidnightDate($Year, 1, 1)),
            $this->FormatPublicHoliday('Birthday of Martin Luther King, Jr.', $this->GetNthWeekdayOfMonth($Year, 1, Carbon::MONDAY, 3)),
            $this->FormatPublicHoliday("Washington's Birthday", $this->GetNthWeekdayOfMonth($Year, 2, Carbon::MONDAY, 3)),
            $this->FormatPublicHoliday('Memorial Day', $this->GetLastWeekdayOfMonth($Year, 5, Carbon::MONDAY)),
            $this->FormatPublicHoliday('Juneteenth National Independence Day', Carbon::createMidnightDate($Year, 6, 19)),
            $this->FormatPublicHoliday('Independence Day', Carbon::createMidnightDate($Year, 7, 4)),
            $this->FormatPublicHoliday('Labor Day', $this->GetNthWeekdayOfMonth($Year, 9, Carbon::MONDAY, 1)),
            $this->FormatPublicHoliday('Columbus Day', $this->GetNthWeekdayOfMonth($Year, 10, Carbon::MONDAY, 2)),
            $this->FormatPublicHoliday('Veterans Day', Carbon::createMidnightDate($Year, 11, 11)),
            $this->FormatPublicHoliday('Thanksgiving Day', $this->GetNthWeekdayOfMonth($Year, 11, Carbon::THURSDAY, 4)),
            $this->FormatPublicHoliday('Christmas Day', Carbon::createMidnightDate($Year, 12, 25)),
        ];

        return $this->SortPublicHolidays($USAPublicHolidays);
    }

    public function UKPublicHolidays(?int $Year = null): array
    {
        // Year Variables
        $Year = $Year ?? Carbon::now(config('app.timezone'))->year;

        // Easter Variables
        $EasterSunday = $this->GetEasterSunday($Year);

        // United Kingdom Fixed Holiday Variables
        $UKNewYearsDayHoliday = $this->GetUKNewYearsDayHoliday($Year);
        $UKChristmasAndBoxingDayHolidays = $this->GetUKChristmasAndBoxingDayHolidays($Year);

        // United Kingdom Public Holiday Variables
        $UKPublicHolidays = [
            $UKNewYearsDayHoliday,
            $this->FormatPublicHoliday('Good Friday', $EasterSunday->copy()->subDays(2)),
            $this->FormatPublicHoliday('Easter Monday', $EasterSunday->copy()->addDay()),
            $this->FormatPublicHoliday('Early May bank holiday', $this->GetNthWeekdayOfMonth($Year, 5, Carbon::MONDAY, 1)),
            $this->FormatPublicHoliday('Spring bank holiday', $this->GetLastWeekdayOfMonth($Year, 5, Carbon::MONDAY)),
            $this->FormatPublicHoliday('Summer bank holiday', $this->GetLastWeekdayOfMonth($Year, 8, Carbon::MONDAY)),
            ...$UKChristmasAndBoxingDayHolidays,
        ];

        return $this->SortPublicHolidays($UKPublicHolidays);
    }

    protected function GetEasterSunday(int $Year): Carbon
    {
        // Easter Algorithm Variables
        $A = $Year % 19;
        $B = intdiv($Year, 100);
        $C = $Year % 100;
        $D = intdiv($B, 4);
        $E = $B % 4;
        $F = intdiv($B + 8, 25);
        $G = intdiv($B - $F + 1, 3);
        $H = (19 * $A + $B - $D - $G + 15) % 30;
        $I = intdiv($C, 4);
        $K = $C % 4;
        $L = (32 + (2 * $E) + (2 * $I) - $H - $K) % 7;
        $M = intdiv($A + (11 * $H) + (22 * $L), 451);
        $Month = intdiv($H + $L - (7 * $M) + 114, 31);
        $Day = (($H + $L - (7 * $M) + 114) % 31) + 1;

        return Carbon::createMidnightDate($Year, $Month, $Day);
    }

    protected function GetNthWeekdayOfMonth(int $Year, int $Month, int $Weekday, int $Occurrence): Carbon
    {
        // Date Variables
        $Date = Carbon::createMidnightDate($Year, $Month, 1);

        while ($Date->dayOfWeek !== $Weekday) {
            $Date->addDay();
        }

        return $Date->addWeeks($Occurrence - 1);
    }

    protected function GetLastWeekdayOfMonth(int $Year, int $Month, int $Weekday): Carbon
    {
        // Date Variables
        $Date = Carbon::createMidnightDate($Year, $Month, 1)->endOfMonth()->startOfDay();

        while ($Date->dayOfWeek !== $Weekday) {
            $Date->subDay();
        }

        return $Date;
    }

    protected function GetUKNewYearsDayHoliday(int $Year): array
    {
        // Date Variables
        $NewYearsDayDate = Carbon::createMidnightDate($Year, 1, 1);

        if ($NewYearsDayDate->isSaturday()) {
            return $this->FormatPublicHoliday("New Year's Day", Carbon::createMidnightDate($Year, 1, 3));
        }

        if ($NewYearsDayDate->isSunday()) {
            return $this->FormatPublicHoliday("New Year's Day", Carbon::createMidnightDate($Year, 1, 2));
        }

        return $this->FormatPublicHoliday("New Year's Day", $NewYearsDayDate);
    }

    protected function GetUKChristmasAndBoxingDayHolidays(int $Year): array
    {
        // Date Variables
        $ChristmasDayDate = Carbon::createMidnightDate($Year, 12, 25);
        $BoxingDayDate = Carbon::createMidnightDate($Year, 12, 26);

        if ($ChristmasDayDate->isSaturday()) {
            return [
                $this->FormatPublicHoliday('Christmas Day', Carbon::createMidnightDate($Year, 12, 27)),
                $this->FormatPublicHoliday('Boxing Day', Carbon::createMidnightDate($Year, 12, 28)),
            ];
        }

        if ($ChristmasDayDate->isSunday()) {
            return [
                $this->FormatPublicHoliday('Boxing Day', Carbon::createMidnightDate($Year, 12, 26)),
                $this->FormatPublicHoliday('Christmas Day', Carbon::createMidnightDate($Year, 12, 27)),
            ];
        }

        if ($BoxingDayDate->isSaturday()) {
            return [
                $this->FormatPublicHoliday('Christmas Day', $ChristmasDayDate),
                $this->FormatPublicHoliday('Boxing Day', Carbon::createMidnightDate($Year, 12, 28)),
            ];
        }

        return [
            $this->FormatPublicHoliday('Christmas Day', $ChristmasDayDate),
            $this->FormatPublicHoliday('Boxing Day', $BoxingDayDate),
        ];
    }

    protected function FormatPublicHoliday(string $HolidayName, Carbon $HolidayDate): array
    {
        return [
            'name' => $HolidayName,
            'date' => $HolidayDate->format('Y-m-d'),
        ];
    }

    protected function SortPublicHolidays(array $PublicHolidays): array
    {
        usort($PublicHolidays, function (array $FirstHoliday, array $SecondHoliday): int {
            return strcmp($FirstHoliday['date'], $SecondHoliday['date']);
        });

        return $PublicHolidays;
    }
}
