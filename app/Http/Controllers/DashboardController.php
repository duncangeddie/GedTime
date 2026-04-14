<?php

namespace App\Http\Controllers;

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
        $DashboardGridClass = 'grid min-h-[calc(100vh-220px)] grid-cols-1 gap-6 lg:grid-cols-2 lg:grid-rows-2';
        $DashboardChartSectionClass = 'lg:col-start-1 lg:row-start-1 min-h-[420px] lg:min-h-0';

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

        return view('dashboard', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
            'DashboardPageClass' => $DashboardPageClass,
            'DashboardPageMainClass' => $DashboardPageMainClass,
            'DashboardGridClass' => $DashboardGridClass,
            'DashboardChartSectionClass' => $DashboardChartSectionClass,
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
        ]);
    }

    protected function FormatMinutesAsHoursMinutes(int $Minutes): string
    {
        // Time Variables
        $Hours = intdiv($Minutes, 60);
        $RemainingMinutes = $Minutes % 60;

        return $Hours . 'h ' . str_pad((string) $RemainingMinutes, 2, '0', STR_PAD_LEFT) . 'm';
    }
}
