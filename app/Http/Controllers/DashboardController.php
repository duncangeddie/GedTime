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

        // Favicon Variables
        $DashboardFaviconEmoji = '⏰';
        $DashboardFaviconType = 'image/svg+xml';
        $DashboardFaviconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="0.9em" x="50%" text-anchor="middle" font-size="90">' . $DashboardFaviconEmoji . '</text></svg>';
        $DashboardFaviconHref = 'data:' . $DashboardFaviconType . ';charset=UTF-8,' . rawurlencode($DashboardFaviconSvg);
        $DashboardAppleTouchIconHref = $DashboardFaviconHref;

        // Layout Variables
        $DashboardGridClass = 'grid grid-cols-1 gap-6 lg:grid-cols-2 lg:items-start';
        $DashboardChartSectionClass = 'w-full';
        $DashboardRightColumnClass = 'flex flex-col gap-3';
        $DashboardCalendarSectionClass = 'w-full';
        $DashboardPublicHolidaySectionClass = 'w-full';

        // Pie Chart Variables
        $DashboardProjectPieCardClass = 'flex h-[340px] flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm';
        $DashboardProjectPieTitle = 'Project Hours';
        $DashboardProjectPieChartWrapperClass = 'relative mx-auto mt-6 h-[140px] w-[140px] sm:h-[160px] sm:w-[160px] lg:h-[180px] lg:w-[180px]';
        $DashboardProjectPieChartId = 'DashboardProjectPieChart';
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
        $DashboardCalendarCardClass = 'flex h-[340px] flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm overflow-hidden';
        $DashboardCalendarTitle = 'Calendar';
        $DashboardCalendarTitleClass = 'text-xl font-semibold text-slate-900';
        $DashboardCalendarMonthLabelClass = 'mt-1 text-sm text-slate-500';
        $DashboardCalendarDayHeaderGridClass = 'mt-4 grid grid-cols-7 gap-1';
        $DashboardCalendarDayHeaderCellClass = 'text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500';
        $DashboardCalendarGridClass = 'mt-2 grid grid-cols-7 gap-1';
        $DashboardCalendarEmptyCellClass = 'h-8';
        $DashboardCalendarDayCellClass = 'flex h-8 items-center justify-center rounded-xl bg-slate-50';
        $DashboardCalendarDayCellTodayClass = 'flex h-8 items-center justify-center rounded-xl bg-slate-100';
        $DashboardCalendarDayNumberClass = 'flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold text-slate-700';
        $DashboardCalendarDayNumberTodayClass = 'flex h-7 w-7 items-center justify-center rounded-full bg-slate-900 text-xs font-semibold text-white shadow-sm';

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
            'DashboardFaviconEmoji' => $DashboardFaviconEmoji,
            'DashboardFaviconType' => $DashboardFaviconType,
            'DashboardFaviconSvg' => $DashboardFaviconSvg,
            'DashboardFaviconHref' => $DashboardFaviconHref,
            'DashboardAppleTouchIconHref' => $DashboardAppleTouchIconHref,
            'DashboardGridClass' => $DashboardGridClass,
            'DashboardChartSectionClass' => $DashboardChartSectionClass,
            'DashboardRightColumnClass' => $DashboardRightColumnClass,
            'DashboardCalendarSectionClass' => $DashboardCalendarSectionClass,
            'DashboardPublicHolidaySectionClass' => $DashboardPublicHolidaySectionClass,
            'DashboardProjectPieCardClass' => $DashboardProjectPieCardClass,
            'DashboardProjectPieTitle' => $DashboardProjectPieTitle,
            'DashboardProjectPieChartWrapperClass' => $DashboardProjectPieChartWrapperClass,
            'DashboardProjectPieChartId' => $DashboardProjectPieChartId,
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
        $PublicHolidaysController = app(PublicHolidaysController::class);

        // Holiday Variables
        $HolidayCollection = collect($YearsToCheck)->flatMap(function (int $Year) use ($NormalizedCountry, $PublicHolidaysController) {
            if ($NormalizedCountry === 'south_africa') {
                return $PublicHolidaysController->SouthAfricaPublicHolidays($Year);
            }

            if ($NormalizedCountry === 'usa') {
                return $PublicHolidaysController->USAPublicHolidays($Year);
            }

            if ($NormalizedCountry === 'uk') {
                return $PublicHolidaysController->UKPublicHolidays($Year);
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

        return in_array($NormalizedCountry, ['south_africa', 'usa', 'uk'], true);
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

        if (in_array($NormalizedCountry, ['united kingdom', 'uk', 'great britain', 'britain'], true)) {
            return 'uk';
        }

        return '';
    }
}
