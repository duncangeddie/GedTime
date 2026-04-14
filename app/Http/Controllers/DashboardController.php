<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function ViewDashboard(Request $Request): View
    {
        // Component Variables
        $ComponentController = app(ComponentController::class);
        $AppHeaderLogo = $ComponentController->AppHeaderLogo();
        $AppFooterLogo = $ComponentController->AppFooterLogo();

        // Page Variables
        $PageTitle = 'Dashboard';
        $DashboardPageClass = 'DashboardPage min-h-screen bg-slate-100';
        $DashboardPageMainClass = 'DashboardPageMain w-full px-4 py-6 sm:px-6 lg:px-8';

        // Layout Variables
        $DashboardGridClass = 'grid grid-cols-1 gap-6 lg:grid-cols-2 lg:items-start';
        $DashboardChartSectionClass = 'lg:col-start-1 lg:row-start-1 min-h-[420px] lg:min-h-0';
        $DashboardCalendarSectionClass = 'lg:col-start-2 lg:row-start-1 min-h-[420px] lg:min-h-0';
        $DashboardPublicHolidaySectionClass = 'lg:col-start-2 lg:row-start-2 self-start';

        // Pie Chart Variables
        $DashboardProjectPieCardClass = 'flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm';
        $DashboardProjectPieTitle = 'Project Hours';
        $DashboardProjectPieChartWrapperClass = 'relative mx-auto mt-6 h-[140px] w-[140px] sm:h-[160px] sm:w-[160px] lg:h-[180px] lg:w-[180px]';
        $DashboardProjectPieChartId = 'DashboardProjectPieChart';
        $DashboardProjectPieLegendClass = 'mt-6 space-y-3';
        $DashboardProjectPieEmptyMessage = 'No timesheet data available yet.';
        $DashboardProjectPieTotalLabel = 'Total Tracked';
        $DashboardProjectPieTotalValueClass = 'rounded-xl bg-slate-100 px-3 py-2 text-right';
        $DashboardProjectPieChartColors = [
            '#0f172a',
            '#2563eb',
            '#14b8a6',
            '#f59e0b',
            '#8b5cf6',
            '#ef4444',
            '#10b981',
            '#f97316',
            '#6366f1',
            '#84cc16',
        ];

        // Calendar Variables
        $DashboardCalendarCardClass = 'flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm';
        $DashboardCalendarTitle = 'Calendar';
        $DashboardCalendarTitleClass = 'text-xl font-semibold text-slate-900';
        $DashboardCalendarMonthLabelClass = 'mt-1 text-sm text-slate-500';
        $DashboardCalendarDayHeaderGridClass = 'mt-6 grid grid-cols-7 gap-2';
        $DashboardCalendarDayHeaderCellClass = 'text-center text-xs font-semibold uppercase tracking-wide text-slate-500';
        $DashboardCalendarGridClass = 'mt-4 grid grid-cols-7 gap-2';
        $DashboardCalendarEmptyCellClass = 'min-h-[52px]';
        $DashboardCalendarDayCellClass = 'flex min-h-[52px] items-center justify-center rounded-2xl bg-slate-50';
        $DashboardCalendarDayCellTodayClass = 'flex min-h-[52px] items-center justify-center rounded-2xl bg-slate-100';
        $DashboardCalendarDayNumberClass = 'flex h-10 w-10 items-center justify-center rounded-full text-sm font-semibold text-slate-700';
        $DashboardCalendarDayNumberTodayClass = 'flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white shadow-sm';

        // Public Holiday Card Variables
        $DashboardPublicHolidayCardClass = 'flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm';
        $DashboardPublicHolidayTitle = 'Upcoming Public Holidays';
        $DashboardPublicHolidayTitleClass = 'text-xl font-semibold text-slate-900';
        $DashboardPublicHolidayCountryLabelClass = 'mt-1 text-sm text-slate-500';
        $DashboardPublicHolidayListClass = 'mt-6 space-y-3';
        $DashboardPublicHolidayItemClass = 'flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-4';
        $DashboardPublicHolidayNameClass = 'text-sm font-semibold text-slate-900';
        $DashboardPublicHolidayDateClass = 'text-sm font-semibold text-slate-900';
        $DashboardPublicHolidayDayClass = 'text-xs text-slate-500';
        $DashboardPublicHolidayMessageClass = 'mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500';
        $DashboardPublicHolidaySelectCountryMessage = '⚠️ Select a country in settings';
        $DashboardPublicHolidayUnsupportedCountryMessage = '⚠️ Public holidays are not available for the selected country';
        $DashboardPublicHolidayEmptyMessage = 'No upcoming public holidays found.';

        // Timesheet Variables
        $ProjectDistributionRows = DB::table('timesheet')
            ->where('user_id', $Request->user()->id)
            ->select('project', DB::raw('SUM(COALESCE(duration, 0)) as total_minutes'))
            ->groupBy('project')
            ->get();

        $DashboardProjectHoursDistribution = $ProjectDistributionRows
            ->map(function ($ProjectRow) {
                // Project Variables
                $ProjectLabel = trim((string) ($ProjectRow->project ?? ''));
                $ProjectMinutes = (int) $ProjectRow->total_minutes;

                if ($ProjectLabel === '') {
                    $ProjectLabel = 'Unassigned';
                }

                return [
                    'Label' => $ProjectLabel,
                    'Minutes' => $ProjectMinutes,
                ];
            })
            ->groupBy('Label')
            ->map(function ($ProjectGroup, $ProjectLabel) {
                // Group Variables
                $ProjectMinutes = (int) $ProjectGroup->sum('Minutes');

                return [
                    'Label' => $ProjectLabel,
                    'Minutes' => $ProjectMinutes,
                ];
            })
            ->sortByDesc('Minutes')
            ->values();

        $TotalTrackedMinutes = (int) $DashboardProjectHoursDistribution->sum('Minutes');
        $DashboardProjectPieTotalValue = $this->FormatMinutesAsHoursMinutes($TotalTrackedMinutes);

        $DashboardProjectHoursDistribution = $DashboardProjectHoursDistribution
            ->map(function ($ProjectRow) use ($TotalTrackedMinutes) {
                // Distribution Variables
                $ProjectMinutes = (int) $ProjectRow['Minutes'];
                $ProjectPercentage = $TotalTrackedMinutes > 0
                    ? round(($ProjectMinutes / $TotalTrackedMinutes) * 100, 1)
                    : 0;

                return [
                    'Label' => $ProjectRow['Label'],
                    'Minutes' => $ProjectMinutes,
                    'HoursDisplay' => $this->FormatMinutesAsHoursMinutes($ProjectMinutes),
                    'Percentage' => $ProjectPercentage,
                ];
            })
            ->values();

        $DashboardProjectHoursChartLabels = $DashboardProjectHoursDistribution->pluck('Label')->values()->all();
        $DashboardProjectHoursChartMinutes = $DashboardProjectHoursDistribution->pluck('Minutes')->values()->all();

        // Calendar Data Variables
        $DashboardCalendarNow = Carbon::now(config('app.timezone'));
        $DashboardCalendarMonthStart = $DashboardCalendarNow->copy()->startOfMonth();
        $DashboardCalendarMonthLabel = $DashboardCalendarMonthStart->format('F Y');
        $DashboardCalendarDayHeaders = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $DashboardCalendarLeadingEmptyCells = $DashboardCalendarMonthStart->dayOfWeekIso - 1;
        $DashboardCalendarDaysInMonth = $DashboardCalendarMonthStart->daysInMonth;
        $DashboardCalendarTrailingEmptyCells = 42 - ($DashboardCalendarLeadingEmptyCells + $DashboardCalendarDaysInMonth);
        $DashboardCalendarCells = collect();

        for ($CalendarIndex = 0; $CalendarIndex < $DashboardCalendarLeadingEmptyCells; $CalendarIndex++) {
            $DashboardCalendarCells->push([
                'IsCurrentMonth' => false,
                'IsToday' => false,
                'DayLabel' => '',
                'CellClass' => $DashboardCalendarEmptyCellClass,
                'DayClass' => '',
            ]);
        }

        for ($DayNumber = 1; $DayNumber <= $DashboardCalendarDaysInMonth; $DayNumber++) {
            $CalendarDate = $DashboardCalendarMonthStart->copy()->day($DayNumber);
            $IsToday = $CalendarDate->isSameDay($DashboardCalendarNow);

            $DashboardCalendarCells->push([
                'IsCurrentMonth' => true,
                'IsToday' => $IsToday,
                'DayLabel' => (string) $DayNumber,
                'CellClass' => $IsToday ? $DashboardCalendarDayCellTodayClass : $DashboardCalendarDayCellClass,
                'DayClass' => $IsToday ? $DashboardCalendarDayNumberTodayClass : $DashboardCalendarDayNumberClass,
            ]);
        }

        for ($CalendarIndex = 0; $CalendarIndex < $DashboardCalendarTrailingEmptyCells; $CalendarIndex++) {
            $DashboardCalendarCells->push([
                'IsCurrentMonth' => false,
                'IsToday' => false,
                'DayLabel' => '',
                'CellClass' => $DashboardCalendarEmptyCellClass,
                'DayClass' => '',
            ]);
        }

        // Settings Variables
        $DashboardSettingsRow = DB::table('settings')
            ->where('user_id', $Request->user()->id)
            ->first();

        $DashboardSelectedCountry = $DashboardSettingsRow->country ?? null;
        $DashboardSelectedCountryLabel = $DashboardSelectedCountry !== null
            ? trim((string) $DashboardSelectedCountry)
            : null;

        // Public Holiday Data Variables
        $DashboardPublicHolidayMessage = null;
        $DashboardUpcomingPublicHolidays = [];

        if ($DashboardSelectedCountryLabel === null || $DashboardSelectedCountryLabel === '') {
            $DashboardPublicHolidayMessage = $DashboardPublicHolidaySelectCountryMessage;
        } else {
            $DashboardUpcomingPublicHolidays = $this->GetUpcomingPublicHolidaysForCountry(
                $DashboardSelectedCountryLabel,
                $DashboardCalendarNow
            );

            if (count($DashboardUpcomingPublicHolidays) === 0) {
                $DashboardPublicHolidayMessage = $this->IsSupportedPublicHolidayCountry($DashboardSelectedCountryLabel)
                    ? $DashboardPublicHolidayEmptyMessage
                    : $DashboardPublicHolidayUnsupportedCountryMessage;
            }
        }

        return view('dashboard', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
            'DashboardPageClass' => $DashboardPageClass,
            'DashboardPageMainClass' => $DashboardPageMainClass,
            'DashboardGridClass' => $DashboardGridClass,
            'DashboardChartSectionClass' => $DashboardChartSectionClass,
            'DashboardCalendarSectionClass' => $DashboardCalendarSectionClass,
            'DashboardPublicHolidaySectionClass' => $DashboardPublicHolidaySectionClass,
            'DashboardProjectPieCardClass' => $DashboardProjectPieCardClass,
            'DashboardProjectPieTitle' => $DashboardProjectPieTitle,
            'DashboardProjectPieChartWrapperClass' => $DashboardProjectPieChartWrapperClass,
            'DashboardProjectPieChartId' => $DashboardProjectPieChartId,
            'DashboardProjectPieLegendClass' => $DashboardProjectPieLegendClass,
            'DashboardProjectPieEmptyMessage' => $DashboardProjectPieEmptyMessage,
            'DashboardProjectPieTotalLabel' => $DashboardProjectPieTotalLabel,
            'DashboardProjectPieTotalValue' => $DashboardProjectPieTotalValue,
            'DashboardProjectPieTotalValueClass' => $DashboardProjectPieTotalValueClass,
            'DashboardProjectPieChartColors' => $DashboardProjectPieChartColors,
            'DashboardProjectHoursDistribution' => $DashboardProjectHoursDistribution,
            'DashboardProjectHoursChartLabels' => $DashboardProjectHoursChartLabels,
            'DashboardProjectHoursChartMinutes' => $DashboardProjectHoursChartMinutes,
            'DashboardCalendarCardClass' => $DashboardCalendarCardClass,
            'DashboardCalendarTitle' => $DashboardCalendarTitle,
            'DashboardCalendarTitleClass' => $DashboardCalendarTitleClass,
            'DashboardCalendarMonthLabel' => $DashboardCalendarMonthLabel,
            'DashboardCalendarMonthLabelClass' => $DashboardCalendarMonthLabelClass,
            'DashboardCalendarDayHeaders' => $DashboardCalendarDayHeaders,
            'DashboardCalendarDayHeaderGridClass' => $DashboardCalendarDayHeaderGridClass,
            'DashboardCalendarDayHeaderCellClass' => $DashboardCalendarDayHeaderCellClass,
            'DashboardCalendarGridClass' => $DashboardCalendarGridClass,
            'DashboardCalendarCells' => $DashboardCalendarCells,
            'DashboardPublicHolidayCardClass' => $DashboardPublicHolidayCardClass,
            'DashboardPublicHolidayTitle' => $DashboardPublicHolidayTitle,
            'DashboardPublicHolidayTitleClass' => $DashboardPublicHolidayTitleClass,
            'DashboardPublicHolidayCountryLabelClass' => $DashboardPublicHolidayCountryLabelClass,
            'DashboardPublicHolidayListClass' => $DashboardPublicHolidayListClass,
            'DashboardPublicHolidayItemClass' => $DashboardPublicHolidayItemClass,
            'DashboardPublicHolidayNameClass' => $DashboardPublicHolidayNameClass,
            'DashboardPublicHolidayDateClass' => $DashboardPublicHolidayDateClass,
            'DashboardPublicHolidayDayClass' => $DashboardPublicHolidayDayClass,
            'DashboardPublicHolidayMessageClass' => $DashboardPublicHolidayMessageClass,
            'DashboardPublicHolidayMessage' => $DashboardPublicHolidayMessage,
            'DashboardUpcomingPublicHolidays' => $DashboardUpcomingPublicHolidays,
            'DashboardSelectedCountryLabel' => $DashboardSelectedCountryLabel,
        ]);
    }

    protected function FormatMinutesAsHoursMinutes(int $Minutes): string
    {
        // Time Variables
        $Hours = intdiv($Minutes, 60);
        $RemainingMinutes = $Minutes % 60;

        return $Hours . 'h ' . str_pad((string) $RemainingMinutes, 2, '0', STR_PAD_LEFT) . 'm';
    }

    protected function GetUpcomingPublicHolidaysForCountry(string $Country, Carbon $Today): array
    {
        // Country Variables
        $NormalizedCountry = $this->NormalizePublicHolidayCountry($Country);
        $YearsToCheck = [$Today->year, $Today->copy()->addYear()->year];

        // Holiday Variables
        $HolidayCollection = collect($YearsToCheck)->flatMap(function (int $Year) use ($NormalizedCountry) {
            if ($NormalizedCountry === 'south_africa') {
                return $this->GetSouthAfricaPublicHolidays($Year);
            }

            if ($NormalizedCountry === 'usa') {
                return $this->GetUSAPublicHolidays($Year);
            }

            return [];
        });

        return $HolidayCollection
            ->filter(function (array $Holiday) use ($Today) {
                return Carbon::parse($Holiday['date'])->greaterThanOrEqualTo($Today->copy()->startOfDay());
            })
            ->sortBy('date')
            ->take(3)
            ->map(function (array $Holiday) {
                // Display Variables
                $HolidayDate = Carbon::parse($Holiday['date']);

                return [
                    'name' => $Holiday['name'],
                    'date' => $Holiday['date'],
                    'display_date' => $HolidayDate->format('j F Y'),
                    'day' => $HolidayDate->format('l'),
                ];
            })
            ->values()
            ->all();
    }

    protected function IsSupportedPublicHolidayCountry(?string $Country): bool
    {
        // Country Variables
        $NormalizedCountry = $this->NormalizePublicHolidayCountry($Country);

        return in_array($NormalizedCountry, ['south_africa', 'usa'], true);
    }

    protected function NormalizePublicHolidayCountry(?string $Country): string
    {
        // Country Variables
        $NormalizedCountry = strtolower(trim((string) $Country));
        $NormalizedCountry = str_replace('.', '', $NormalizedCountry);
        $NormalizedCountry = preg_replace('/\s+/', ' ', $NormalizedCountry) ?? '';

        if (in_array($NormalizedCountry, ['south africa', 'rsa'], true)) {
            return 'south_africa';
        }

        if (in_array($NormalizedCountry, ['united states of america', 'united states', 'usa', 'us'], true)) {
            return 'usa';
        }

        return '';
    }

    protected function GetSouthAfricaPublicHolidays(int $Year): array
    {
        // Easter Variables
        $EasterSunday = $this->GetEasterSunday($Year);

        return [
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
    }

    protected function GetUSAPublicHolidays(int $Year): array
    {
        return [
            $this->FormatPublicHoliday("New Year's Day", Carbon::createMidnightDate($Year, 1, 1)),
            $this->FormatPublicHoliday('Martin Luther King Jr. Day', $this->GetNthWeekdayOfMonth($Year, 1, Carbon::MONDAY, 3)),
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

    protected function FormatPublicHoliday(string $HolidayName, Carbon $HolidayDate): array
    {
        return [
            'name' => $HolidayName,
            'date' => $HolidayDate->format('Y-m-d'),
        ];
    }
}
