<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        <link rel="icon" type="{{ $DashboardFaviconType }}" href="{{ $DashboardFaviconHref }}">
        <link rel="apple-touch-icon" href="{{ $DashboardAppleTouchIconHref }}">

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 text-slate-900">
        <div class="{{ $DashboardPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo])

            <main class="{{ $DashboardPageMainClass }}">
                <div class="{{ $DashboardGridClass }}">
                    <div class="{{ $DashboardLeftColumnClass }}">
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
                                    <div class="{{ $DashboardProjectPieContentClass }}">
                                        <div class="{{ $DashboardProjectPieChartWrapperClass }}">
                                            <canvas id="{{ $DashboardProjectPieChartId }}"></canvas>
                                        </div>

                                        <div class="{{ $DashboardProjectPieKeyListClass }}">
                                            @foreach ($DashboardProjectHoursDistribution as $ProjectIndex => $ProjectDistribution)
                                                @php
                                                    $SliceColor = $DashboardProjectPieChartColors[$ProjectIndex % count($DashboardProjectPieChartColors)];
                                                @endphp

                                                <div class="{{ $DashboardProjectPieKeyItemClass }}">
                                                    <div class="flex min-w-0 items-center gap-3">
                                                        <span
                                                            class="h-3 w-3 shrink-0 rounded-full"
                                                            style="background-color: {{ $SliceColor }};"
                                                        ></span>

                                                        <span class="{{ $DashboardProjectPieKeyLabelClass }}">
                                                            {{ $ProjectDistribution['Label'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center text-sm text-slate-500">
                                        {{ $DashboardProjectPieEmptyMessage }}
                                    </div>
                                @endif
                            </div>
                        </section>

                        <section class="{{ $DashboardWorldClockSectionClass }}">
                            <div class="{{ $DashboardWorldClockCardClass }}">
                                <div>
                                    <h2 class="{{ $DashboardWorldClockTitleClass }}">
                                        {{ $DashboardWorldClockTitle }}
                                    </h2>
                                </div>

                                @if ($DashboardWorldClockMessage !== null)
                                    <div class="{{ $DashboardWorldClockMessageClass }}">
                                        {{ $DashboardWorldClockMessage }}
                                    </div>
                                @elseif (count($DashboardWorldClocks) > 0)
                                    <div class="{{ $DashboardWorldClockContentClass }}">
                                        @foreach ($DashboardWorldClocks as $DashboardWorldClock)
                                            <div
                                                class="{{ $DashboardWorldClockItemClass }}"
                                                id="{{ $DashboardWorldClock['ClockId'] }}"
                                                data-world-clock="true"
                                                data-time-zone="{{ $DashboardWorldClock['TimeZone'] }}"
                                            >
                                                <div
                                                    class="{{ $DashboardWorldClockFaceClass }}"
                                                    data-world-clock-face
                                                >
                                                    <div class="{{ $DashboardWorldClockHourMarkerContainerClass }}">
                                                        @foreach ($DashboardWorldClockHourMarkers as $DashboardWorldClockHourMarker)
                                                            <span
                                                                class="{{ $DashboardWorldClockHourNumberClass }}"
                                                                data-world-clock-hour-number
                                                                style="transform: translate(-50%, -50%) rotate({{ $DashboardWorldClockHourMarker['Rotation'] }}deg) translateY(-{{ $DashboardWorldClockHourMarkerRadius }}) rotate(-{{ $DashboardWorldClockHourMarker['Rotation'] }}deg);"
                                                            >
                                                                {{ $DashboardWorldClockHourMarker['Label'] }}
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    <div class="{{ $DashboardWorldClockHourHandClass }}" data-world-clock-hour-hand></div>
                                                    <div class="{{ $DashboardWorldClockMinuteHandClass }}" data-world-clock-minute-hand></div>
                                                    <div class="{{ $DashboardWorldClockSecondHandClass }}" data-world-clock-second-hand></div>
                                                    <div class="{{ $DashboardWorldClockCenterDotClass }}" data-world-clock-center-dot></div>
                                                </div>

                                                <p class="{{ $DashboardWorldClockLabelClass }}">
                                                    {{ $DashboardWorldClock['LocationLabel'] }}
                                                </p>

                                                <p
                                                    class="{{ $DashboardWorldClockDateLabelClass }}"
                                                    data-world-clock-date-label
                                                ></p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="{{ $DashboardWorldClockMessageClass }}">
                                        No world clocks selected yet.
                                    </div>
                                @endif
                            </div>
                        </section>
                    </div>

                    <div class="{{ $DashboardRightColumnClass }}">
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

        @if (count($DashboardWorldClocks) > 0)
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const WorldClockElements = document.querySelectorAll('[data-world-clock="true"]');

                    if (WorldClockElements.length === 0 || typeof Intl === 'undefined') {
                        return;
                    }

                    function GetTimePartsForZone(TimeZone) {
                        const Formatter = new Intl.DateTimeFormat('en-GB', {
                            timeZone: TimeZone,
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false,
                        });

                        const Parts = Formatter.formatToParts(new Date());
                        const TimeParts = {
                            year: 0,
                            month: 0,
                            day: 0,
                            hour: 0,
                            minute: 0,
                            second: 0,
                        };

                        Parts.forEach(function (Part) {
                            if (Object.prototype.hasOwnProperty.call(TimeParts, Part.type)) {
                                TimeParts[Part.type] = parseInt(Part.value, 10);
                            }
                        });

                        return TimeParts;
                    }

                    function GetDisplayDateForZone(TimeZone) {
                        return new Intl.DateTimeFormat('en-GB', {
                            timeZone: TimeZone,
                            weekday: 'short',
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                        }).format(new Date());
                    }

                    function ApplyWorldClockTheme(WorldClockElement, UseDayTheme) {
                        const Face = WorldClockElement.querySelector('[data-world-clock-face]');
                        const HourNumbers = WorldClockElement.querySelectorAll('[data-world-clock-hour-number]');
                        const HourHand = WorldClockElement.querySelector('[data-world-clock-hour-hand]');
                        const MinuteHand = WorldClockElement.querySelector('[data-world-clock-minute-hand]');
                        const SecondHand = WorldClockElement.querySelector('[data-world-clock-second-hand]');
                        const CenterDot = WorldClockElement.querySelector('[data-world-clock-center-dot]');

                        if (!Face || !HourHand || !MinuteHand || !SecondHand || !CenterDot) {
                            return;
                        }

                        if (UseDayTheme) {
                            Face.classList.remove('bg-blue-950', 'border-blue-950');
                            Face.classList.add('bg-white', 'border-slate-900');

                            HourNumbers.forEach(function (HourNumber) {
                                HourNumber.classList.remove('text-slate-100');
                                HourNumber.classList.add('text-slate-500');
                            });

                            HourHand.classList.remove('bg-slate-100');
                            HourHand.classList.add('bg-slate-900');

                            MinuteHand.classList.remove('bg-slate-300');
                            MinuteHand.classList.add('bg-slate-700');

                            SecondHand.classList.remove('bg-blue-200');
                            SecondHand.classList.add('bg-rose-500');

                            CenterDot.classList.remove('bg-slate-100');
                            CenterDot.classList.add('bg-slate-900');
                        } else {
                            Face.classList.remove('bg-white', 'border-slate-900');
                            Face.classList.add('bg-blue-950', 'border-blue-950');

                            HourNumbers.forEach(function (HourNumber) {
                                HourNumber.classList.remove('text-slate-500');
                                HourNumber.classList.add('text-slate-100');
                            });

                            HourHand.classList.remove('bg-slate-900');
                            HourHand.classList.add('bg-slate-100');

                            MinuteHand.classList.remove('bg-slate-700');
                            MinuteHand.classList.add('bg-slate-300');

                            SecondHand.classList.remove('bg-rose-500');
                            SecondHand.classList.add('bg-blue-200');

                            CenterDot.classList.remove('bg-slate-900');
                            CenterDot.classList.add('bg-slate-100');
                        }
                    }

                    function UpdateWorldClocks() {
                        WorldClockElements.forEach(function (WorldClockElement) {
                            const TimeZone = WorldClockElement.dataset.timeZone;
                            const HourHand = WorldClockElement.querySelector('[data-world-clock-hour-hand]');
                            const MinuteHand = WorldClockElement.querySelector('[data-world-clock-minute-hand]');
                            const SecondHand = WorldClockElement.querySelector('[data-world-clock-second-hand]');
                            const DateLabel = WorldClockElement.querySelector('[data-world-clock-date-label]');

                            if (!TimeZone || !HourHand || !MinuteHand || !SecondHand || !DateLabel) {
                                return;
                            }

                            const TimeParts = GetTimePartsForZone(TimeZone);
                            const HourRotation = ((TimeParts.hour % 12) + (TimeParts.minute / 60) + (TimeParts.second / 3600)) * 30;
                            const MinuteRotation = (TimeParts.minute + (TimeParts.second / 60)) * 6;
                            const SecondRotation = TimeParts.second * 6;
                            const TotalMinutes = (TimeParts.hour * 60) + TimeParts.minute;
                            const UseDayTheme = TotalMinutes >= 421 && TotalMinutes <= 1135;

                            HourHand.style.transform = 'translateX(-50%) rotate(' + HourRotation + 'deg)';
                            MinuteHand.style.transform = 'translateX(-50%) rotate(' + MinuteRotation + 'deg)';
                            SecondHand.style.transform = 'translateX(-50%) rotate(' + SecondRotation + 'deg)';

                            ApplyWorldClockTheme(WorldClockElement, UseDayTheme);

                            DateLabel.textContent = GetDisplayDateForZone(TimeZone);
                        });
                    }

                    UpdateWorldClocks();
                    window.setInterval(UpdateWorldClocks, 1000);
                });
            </script>
        @endif
    </body>
</html>
