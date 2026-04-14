<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 text-slate-900">
        <div class="{{ $DashboardPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo])

            <main class="{{ $DashboardPageMainClass }}">
                <div class="{{ $DashboardGridClass }}">
                    <section class="{{ $DashboardChartSectionClass }}">
                        <div class="{{ $DashboardProjectPieCardClass }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-slate-900">
                                        {{ $DashboardProjectPieTitle }}
                                    </h2>
                                </div>

                                <div class="{{ $DashboardProjectPieTotalValueClass }}">
                                    <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                                        {{ $DashboardProjectPieTotalLabel }}
                                    </p>

                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $DashboardProjectPieTotalValue }}
                                    </p>
                                </div>
                            </div>

                            @if (count($DashboardProjectHoursDistribution) > 0)
                                <div class="{{ $DashboardProjectPieChartWrapperClass }}">
                                    <canvas id="{{ $DashboardProjectPieChartId }}"></canvas>
                                </div>

                                <div class="{{ $DashboardProjectPieLegendClass }}">
                                    @foreach ($DashboardProjectHoursDistribution as $ProjectIndex => $ProjectDistribution)
                                        @php
                                            $SliceColor = $DashboardProjectPieChartColors[$ProjectIndex % count($DashboardProjectPieChartColors)];
                                        @endphp

                                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                            <div class="flex min-w-0 items-center gap-3">
                                                <span
                                                    class="h-3 w-3 shrink-0 rounded-full"
                                                    style="background-color: {{ $SliceColor }};"
                                                ></span>

                                                <span class="truncate text-sm font-medium text-slate-700">
                                                    {{ $ProjectDistribution['Label'] }}
                                                </span>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-slate-900">
                                                    {{ number_format($ProjectDistribution['Percentage'], 1) }}%
                                                </p>

                                                <p class="text-xs text-slate-500">
                                                    {{ $ProjectDistribution['HoursDisplay'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                                    {{ $DashboardProjectPieEmptyMessage }}
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="{{ $DashboardCalendarSectionClass }}">
                        <div class="{{ $DashboardCalendarCardClass }}">
                            <div>
                                <h2 class="{{ $DashboardCalendarTitleClass }}">
                                    {{ $DashboardCalendarTitle }}
                                </h2>

                                <p class="{{ $DashboardCalendarMonthLabelClass }}">
                                    {{ $DashboardCalendarMonthLabel }}
                                </p>
                            </div>

                            <div class="{{ $DashboardCalendarDayHeaderGridClass }}">
                                @foreach ($DashboardCalendarDayHeaders as $DashboardCalendarDayHeader)
                                    <div class="{{ $DashboardCalendarDayHeaderCellClass }}">
                                        {{ $DashboardCalendarDayHeader }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="{{ $DashboardCalendarGridClass }}">
                                @foreach ($DashboardCalendarCells as $DashboardCalendarCell)
                                    <div class="{{ $DashboardCalendarCell['CellClass'] }}">
                                        @if ($DashboardCalendarCell['IsCurrentMonth'])
                                            <span
                                                class="{{ $DashboardCalendarCell['DayClass'] }}"
                                                @if ($DashboardCalendarCell['IsToday']) aria-current="date" @endif
                                            >
                                                {{ $DashboardCalendarCell['DayLabel'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="{{ $DashboardPublicHolidaySectionClass }}">
                        <div class="{{ $DashboardPublicHolidayCardClass }}">
                            <div>
                                <h2 class="{{ $DashboardPublicHolidayTitleClass }}">
                                    {{ $DashboardPublicHolidayTitle }}
                                </h2>

                                @if (!empty($DashboardSelectedCountryLabel))
                                    <p class="{{ $DashboardPublicHolidayCountryLabelClass }}">
                                        {{ $DashboardSelectedCountryLabel }}
                                    </p>
                                @endif
                            </div>

                            @if ($DashboardPublicHolidayMessage !== null)
                                <div class="{{ $DashboardPublicHolidayMessageClass }}">
                                    {{ $DashboardPublicHolidayMessage }}
                                </div>
                            @elseif (count($DashboardUpcomingPublicHolidays) > 0)
                                <div class="{{ $DashboardPublicHolidayListClass }}">
                                    @foreach ($DashboardUpcomingPublicHolidays as $DashboardUpcomingPublicHoliday)
                                        <div class="{{ $DashboardPublicHolidayItemClass }}">
                                            <div class="min-w-0">
                                                <p class="{{ $DashboardPublicHolidayNameClass }}">
                                                    {{ $DashboardUpcomingPublicHoliday['name'] }}
                                                </p>
                                            </div>

                                            <div class="shrink-0 text-right">
                                                <p class="{{ $DashboardPublicHolidayDateClass }}">
                                                    {{ $DashboardUpcomingPublicHoliday['display_date'] }}
                                                </p>

                                                <p class="{{ $DashboardPublicHolidayDayClass }}">
                                                    {{ $DashboardUpcomingPublicHoliday['day'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="{{ $DashboardPublicHolidayMessageClass }}">
                                    No upcoming public holidays found.
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </main>

            @include('components.app-footer', ['LogoPath' => $AppFooterLogo])
        </div>

        @if (count($DashboardProjectHoursDistribution) > 0)
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ChartElement = document.getElementById(@json($DashboardProjectPieChartId));
                    const ChartBreakdown = @json($DashboardProjectHoursDistribution);

                    if (!ChartElement || typeof Chart === 'undefined') {
                        return;
                    }

                    new Chart(ChartElement, {
                        type: 'pie',
                        data: {
                            labels: @json($DashboardProjectHoursChartLabels),
                            datasets: [
                                {
                                    data: @json($DashboardProjectHoursChartMinutes),
                                    backgroundColor: @json($DashboardProjectPieChartColors),
                                    borderColor: '#ffffff',
                                    borderWidth: 3,
                                    hoverOffset: 8,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (Context) {
                                            const Entry = ChartBreakdown[Context.dataIndex];

                                            if (!Entry) {
                                                return Context.label + ': ' + Context.formattedValue;
                                            }

                                            return Entry.Label + ': ' + Number(Entry.Percentage).toFixed(1) + '% (' + Entry.HoursDisplay + ')';
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endif
    </body>
</html>
