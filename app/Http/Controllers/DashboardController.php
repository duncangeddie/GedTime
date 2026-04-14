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
        $DashboardLeftColumnClass = 'flex flex-col gap-3';
        $DashboardChartSectionClass = 'w-full';
        $DashboardWorldClockSectionClass = 'w-full';
        $DashboardRightColumnClass = 'flex flex-col gap-3';
        $DashboardCalendarSectionClass = 'w-full';
        $DashboardPublicHolidaySectionClass = 'w-full';

        // Pie Chart Variables
        $DashboardProjectPieCardClass = 'flex h-[340px] flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm overflow-hidden';
        $DashboardProjectPieTitle = 'Project Hours';
        $DashboardProjectPieContentClass = 'mt-6 flex min-h-0 flex-1 items-center gap-6';
        $DashboardProjectPieChartWrapperClass = 'relative h-[140px] w-[140px] shrink-0 sm:h-[160px] sm:w-[160px] lg:h-[170px] lg:w-[170px]';
        $DashboardProjectPieChartId = 'DashboardProjectPieChart';
        $DashboardProjectPieKeyListClass = 'min-h-0 flex-1 space-y-2 overflow-y-auto pr-1';
        $DashboardProjectPieKeyItemClass = 'flex items-center rounded-xl border border-slate-200 bg-slate-50 px-3 py-2';
        $DashboardProjectPieKeyLabelClass = 'truncate text-sm font-medium text-slate-700';
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

        // World Clock Card Variables
        $DashboardWorldClockCardClass = 'flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm overflow-hidden';
        $DashboardWorldClockTitle = 'World Clocks';
        $DashboardWorldClockTitleClass = 'text-xl font-semibold text-slate-900';
        $DashboardWorldClockContentClass = 'mt-6 flex flex-nowrap items-start justify-center gap-4';
        $DashboardWorldClockItemClass = 'flex w-28 shrink-0 flex-col items-center justify-start';
        $DashboardWorldClockFaceClass = 'relative flex h-24 w-24 items-center justify-center rounded-full border-4 border-slate-900 bg-white shadow-sm transition-colors duration-300';
        $DashboardWorldClockHourMarkerContainerClass = 'pointer-events-none absolute inset-0';
        $DashboardWorldClockHourNumberClass = 'absolute left-1/2 top-1/2 text-[10px] font-semibold text-slate-500 transition-colors duration-300';
        $DashboardWorldClockHourMarkerRadius = '2.2rem';
        $DashboardWorldClockHourHandClass = 'absolute bottom-1/2 left-1/2 z-10 h-7 w-1.5 origin-bottom rounded-full bg-slate-900 transition-colors duration-300';
        $DashboardWorldClockMinuteHandClass = 'absolute bottom-1/2 left-1/2 z-10 h-9 w-1 origin-bottom rounded-full bg-slate-700 transition-colors duration-300';
        $DashboardWorldClockSecondHandClass = 'absolute bottom-1/2 left-1/2 z-10 h-9 w-0.5 origin-bottom rounded-full bg-rose-500 transition-colors duration-300';
        $DashboardWorldClockCenterDotClass = 'absolute z-20 h-3 w-3 rounded-full bg-slate-900 transition-colors duration-300';
        $DashboardWorldClockLabelClass = 'mt-4 text-center text-sm font-semibold text-slate-900';
        $DashboardWorldClockDateLabelClass = 'mt-1 text-center text-[11px] text-slate-500';
        $DashboardWorldClockMessageClass = 'mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500';
        $DashboardWorldClockDisabledMessage = 'World clocks are turned off in settings.';
        $DashboardWorldClockEmptyMessage = 'No world clocks selected yet.';
        $DashboardBaseTimeZone = config('app.timezone');

        // World Clock Hour Marker Variables
        $DashboardWorldClockHourMarkers = [
            ['Label' => '12', 'Rotation' => 0],
            ['Label' => '1', 'Rotation' => 30],
            ['Label' => '2', 'Rotation' => 60],
            ['Label' => '3', 'Rotation' => 90],
            ['Label' => '4', 'Rotation' => 120],
            ['Label' => '5', 'Rotation' => 150],
            ['Label' => '6', 'Rotation' => 180],
            ['Label' => '7', 'Rotation' => 210],
            ['Label' => '8', 'Rotation' => 240],
            ['Label' => '9', 'Rotation' => 270],
            ['Label' => '10', 'Rotation' => 300],
            ['Label' => '11', 'Rotation' => 330],
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

        $DashboardUseWorldClocks = (int) ($DashboardSettingsRow->use_world_clocks ?? 0);
        $DashboardWorldClockCount = (int) ($DashboardSettingsRow->world_clock_count ?? 0);

        // World Clock Data Variables
        $DashboardWorldClockSelections = [
            [
                'TimeZone' => trim((string) ($DashboardSettingsRow->world_clock_one ?? '')),
                'MinimumCount' => 1,
            ],
            [
                'TimeZone' => trim((string) ($DashboardSettingsRow->world_clock_two ?? '')),
                'MinimumCount' => 2,
            ],
            [
                'TimeZone' => trim((string) ($DashboardSettingsRow->world_clock_three ?? '')),
                'MinimumCount' => 3,
            ],
        ];

        $DashboardWorldClockLabels = $this->WorldClockLabels();

        $DashboardWorldClocks = collect($DashboardWorldClockSelections)
            ->values()
            ->filter(function (array $DashboardWorldClockSelection) use ($DashboardUseWorldClocks, $DashboardWorldClockCount) {
                return $DashboardUseWorldClocks === 1
                    && $DashboardWorldClockCount >= $DashboardWorldClockSelection['MinimumCount']
                    && $DashboardWorldClockSelection['TimeZone'] !== '';
            })
            ->values()
            ->map(function (array $DashboardWorldClockSelection, int $DashboardWorldClockIndex) use ($DashboardWorldClockLabels) {
                // Clock Variables
                $DashboardWorldClockTimeZone = $DashboardWorldClockSelection['TimeZone'];

                return [
                    'ClockId' => 'DashboardWorldClock' . ($DashboardWorldClockIndex + 1),
                    'TimeZone' => $DashboardWorldClockTimeZone,
                    'LocationLabel' => $DashboardWorldClockLabels[$DashboardWorldClockTimeZone]
                        ?? $this->FormatWorldClockLocationLabel($DashboardWorldClockTimeZone),
                ];
            })
            ->values()
            ->all();

        $DashboardWorldClockMessage = null;

        if ($DashboardUseWorldClocks !== 1) {
            $DashboardWorldClockMessage = $DashboardWorldClockDisabledMessage;
        } elseif (count($DashboardWorldClocks) === 0) {
            $DashboardWorldClockMessage = $DashboardWorldClockEmptyMessage;
        }

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
            'DashboardLeftColumnClass' => $DashboardLeftColumnClass,
            'DashboardChartSectionClass' => $DashboardChartSectionClass,
            'DashboardWorldClockSectionClass' => $DashboardWorldClockSectionClass,
            'DashboardRightColumnClass' => $DashboardRightColumnClass,
            'DashboardCalendarSectionClass' => $DashboardCalendarSectionClass,
            'DashboardPublicHolidaySectionClass' => $DashboardPublicHolidaySectionClass,
            'DashboardProjectPieCardClass' => $DashboardProjectPieCardClass,
            'DashboardProjectPieTitle' => $DashboardProjectPieTitle,
            'DashboardProjectPieContentClass' => $DashboardProjectPieContentClass,
            'DashboardProjectPieChartWrapperClass' => $DashboardProjectPieChartWrapperClass,
            'DashboardProjectPieChartId' => $DashboardProjectPieChartId,
            'DashboardProjectPieKeyListClass' => $DashboardProjectPieKeyListClass,
            'DashboardProjectPieKeyItemClass' => $DashboardProjectPieKeyItemClass,
            'DashboardProjectPieKeyLabelClass' => $DashboardProjectPieKeyLabelClass,
            'DashboardProjectPieEmptyMessage' => $DashboardProjectPieEmptyMessage,
            'DashboardProjectPieTotalLabel' => $DashboardProjectPieTotalLabel,
            'DashboardProjectPieTotalValue' => $DashboardProjectPieTotalValue,
            'DashboardProjectPieTotalValueClass' => $DashboardProjectPieTotalValueClass,
            'DashboardProjectPieChartColors' => $DashboardProjectPieChartColors,
            'DashboardProjectHoursDistribution' => $DashboardProjectHoursDistribution,
            'DashboardProjectHoursChartLabels' => $DashboardProjectHoursChartLabels,
            'DashboardProjectHoursChartMinutes' => $DashboardProjectHoursChartMinutes,
            'DashboardWorldClockCardClass' => $DashboardWorldClockCardClass,
            'DashboardWorldClockTitle' => $DashboardWorldClockTitle,
            'DashboardWorldClockTitleClass' => $DashboardWorldClockTitleClass,
            'DashboardWorldClockContentClass' => $DashboardWorldClockContentClass,
            'DashboardWorldClockItemClass' => $DashboardWorldClockItemClass,
            'DashboardWorldClockFaceClass' => $DashboardWorldClockFaceClass,
            'DashboardWorldClockHourMarkerContainerClass' => $DashboardWorldClockHourMarkerContainerClass,
            'DashboardWorldClockHourNumberClass' => $DashboardWorldClockHourNumberClass,
            'DashboardWorldClockHourMarkerRadius' => $DashboardWorldClockHourMarkerRadius,
            'DashboardWorldClockHourMarkers' => $DashboardWorldClockHourMarkers,
            'DashboardWorldClockHourHandClass' => $DashboardWorldClockHourHandClass,
            'DashboardWorldClockMinuteHandClass' => $DashboardWorldClockMinuteHandClass,
            'DashboardWorldClockSecondHandClass' => $DashboardWorldClockSecondHandClass,
            'DashboardWorldClockCenterDotClass' => $DashboardWorldClockCenterDotClass,
            'DashboardWorldClockLabelClass' => $DashboardWorldClockLabelClass,
            'DashboardWorldClockDateLabelClass' => $DashboardWorldClockDateLabelClass,
            'DashboardWorldClockMessageClass' => $DashboardWorldClockMessageClass,
            'DashboardWorldClocks' => $DashboardWorldClocks,
            'DashboardWorldClockMessage' => $DashboardWorldClockMessage,
            'DashboardBaseTimeZone' => $DashboardBaseTimeZone,
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

    protected function WorldClockLabels(): array
    {
        return [
            'Africa/Johannesburg' => '🇿🇦 Joburg',
            'Europe/London' => '🇬🇧 London',
            'Europe/Paris' => '🇫🇷 Paris',
            'Europe/Berlin' => '🇩🇪 Berlin',
            'America/New_York' => '🇺🇸 New York',
            'America/Los_Angeles' => '🇺🇸 Los Angeles',
            'America/Toronto' => '🇨🇦 Toronto',
            'Asia/Dubai' => '🇦🇪 Dubai',
            'Asia/Tokyo' => '🇯🇵 Tokyo',
            'Asia/Singapore' => '🇸🇬 Singapore',
            'Australia/Sydney' => '🇦🇺 Sydney',
            'Pacific/Auckland' => '🇳🇿 Auckland',
        ];
    }

    protected function FormatWorldClockLocationLabel(string $TimeZone): string
    {
        // Time Zone Variables
        $TimeZoneParts = explode('/', $TimeZone);
        $LocationPart = end($TimeZoneParts);

        return str_replace('_', ' ', $LocationPart !== false ? $LocationPart : $TimeZone);
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
