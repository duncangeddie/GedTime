<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>
        <link
            rel="{{ $ViewFavicon['FaviconRel'] }}"
            type="{{ $ViewFavicon['FaviconType'] }}"
            href="{{ $ViewFavicon['FaviconHref'] }}"
        >

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="{{ $TimesheetPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo])

            <main class="{{ $TimesheetPageMainClass }}">
                <div class="{{ $TimesheetPageContentClass }}">
                    <div class="{{ $TimesheetPageActionsClass }}">
                        <div class="{{ $TimesheetPageActionsGroupClass }}">
                            <button
                                type="button"
                                class="{{ $AddTimesheetButton['ButtonClass'] }}"
                                onclick="document.getElementById('{{ $AddTimesheetButton['DialogId'] }}').showModal()"
                                aria-label="{{ $AddTimesheetButton['ButtonAriaLabel'] }}"
                                title="{{ $AddTimesheetButton['ButtonTitle'] }}"
                            >
                                <img
                                    src="{{ $AddTimesheetButton['ButtonIconPath'] }}"
                                    alt="{{ $AddTimesheetButton['ButtonIconAlt'] }}"
                                    class="{{ $AddTimesheetButton['ButtonIconClass'] }}"
                                >
                                <span class="sr-only">{{ $AddTimesheetButton['ButtonLabel'] }}</span>
                            </button>

                            <div
                                class="{{ $TimesheetDigitalClock['ClockWrapperClass'] }}"
                                aria-label="{{ $TimesheetDigitalClock['ClockLabel'] }}"
                                title="{{ $TimesheetDigitalClock['ClockLabel'] }}"
                            >
                                <span
                                    id="{{ $TimesheetDigitalClock['ClockTimeId'] }}"
                                    class="{{ $TimesheetDigitalClock['ClockTimeClass'] }}"
                                >
                                    00:00:00
                                </span>

                                <span
                                    id="{{ $TimesheetDigitalClock['ClockDateId'] }}"
                                    class="{{ $TimesheetDigitalClock['ClockDateClass'] }}"
                                >
                                    00/00/0000
                                </span>
                            </div>

                            <div
                                class="{{ $TimesheetTodayWorked['TodayWorkedWrapperClass'] }}"
                                aria-label="{{ $TimesheetTodayWorked['TodayWorkedLabel'] }}"
                                title="{{ $TimesheetTodayWorked['TodayWorkedLabel'] }}"
                            >
                                <span class="{{ $TimesheetTodayWorked['TodayWorkedValueClass'] }}">
                                    {{ $TodayWorkedDisplay }}
                                </span>

                                <span class="{{ $TimesheetTodayWorked['TodayWorkedLabelClass'] }}">
                                    {{ $TimesheetTodayWorked['TodayWorkedLabel'] }}
                                </span>
                            </div>

                            <form
                                method="GET"
                                action="{{ $TimesheetPageFiltersFormAction }}"
                                class="{{ $TimesheetPageFiltersFormClass }}"
                            >
                                <label for="{{ $ProjectFilterName }}" class="{{ $TimesheetPageFiltersLabelClass }}">
                                    {{ $ProjectFilterLabel }}
                                </label>

                                <select
                                    id="{{ $ProjectFilterName }}"
                                    name="{{ $ProjectFilterName }}"
                                    class="{{ $TimesheetPageFiltersSelectClass }}"
                                >
                                    <option value="{{ $ProjectFilterAllValue }}" @selected($SelectedProjectFilter === $ProjectFilterAllValue)>
                                        {{ $ProjectFilterAllLabel }}
                                    </option>

                                    @foreach ($Projects as $Project)
                                        <option value="{{ $Project->id }}" @selected((string) $Project->id === $SelectedProjectFilter)>
                                            {{ $Project->project_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="{{ $DateRangeFilterName }}" class="{{ $TimesheetPageFiltersLabelClass }}">
                                    {{ $DateRangeFilterLabel }}
                                </label>

                                <select
                                    id="{{ $DateRangeFilterName }}"
                                    name="{{ $DateRangeFilterName }}"
                                    class="{{ $TimesheetPageFiltersSelectClass }}"
                                >
                                    @foreach ($DateRangeFilterOptions as $DateRangeFilterOption)
                                        <option
                                            value="{{ $DateRangeFilterOption['Value'] }}"
                                            @selected($DateRangeFilterOption['Value'] === $SelectedDateRangeFilter)
                                        >
                                            {{ $DateRangeFilterOption['Label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                <button
                                    type="submit"
                                    class="{{ $TimesheetPageFiltersApplyButtonClass }}"
                                >
                                    {{ $TimesheetPageFiltersApplyButtonLabel }}
                                </button>

                                <a
                                    href="{{ $TimesheetPageFiltersResetUrl }}"
                                    class="{{ $TimesheetPageFiltersResetButtonClass }}"
                                >
                                    {{ $TimesheetPageFiltersResetButtonLabel }}
                                </a>
                            </form>
                        </div>
                    </div>

                    @if (session('SuccessMessage'))
                        <div id="TimesheetSuccessMessage" class="{{ $TimesheetPageMessageClass }}">
                            {{ session('SuccessMessage') }}
                        </div>
                    @endif

                    @if (session('ErrorMessage'))
                        <div id="TimesheetErrorMessage" class="{{ $TimesheetPageErrorMessageClass }}">
                            {{ session('ErrorMessage') }}
                        </div>
                    @endif

                    <div class="{{ $TimesheetTableSectionClass }}">
                        <div class="{{ $TimesheetTableWrapperClass }}">
                            <table class="{{ $TimesheetTableClass }}">
                                <thead class="{{ $TimesheetTableHeadClass }}">
                                    <tr>
                                        @foreach ($TimesheetTableColumns as $TimesheetTableColumn)
                                            <th class="{{ $TimesheetTableHeadingClass }}">
                                                {{ $TimesheetTableColumn }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="{{ $TimesheetTableBodyClass }}">
                                    @forelse ($TimesheetEntries as $TimesheetEntry)
                                        <tr
                                            class="{{ $TimesheetTableRowClass }} {{ $loop->index >= $TimesheetInitialVisibleEntries ? $TimesheetTableHiddenRowClass : '' }}"
                                            data-timesheet-entry-row
                                        >
                                            <td class="{{ $TimesheetTableCellClass }}" data-label="Date">
                                                {{ $TimesheetEntry->date }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }}" data-label="Start">
                                                {{ $TimesheetEntry->time_start }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }}" data-label="End">
                                                {{ $TimesheetEntry->time_end }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }}" data-label="Duration">
                                                {{ $TimesheetEntry->DurationDisplay }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }}" data-label="Project">
                                                {{ $TimesheetEntry->project }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }}" data-label="Category">
                                                {{ $TimesheetEntry->category }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }}" data-label="Task">
                                                {{ $TimesheetEntry->task }}
                                            </td>

                                            <td class="{{ $TimesheetTableCellClass }} {{ $TimesheetTableActionCellClass }}" data-label="Actions">
                                                <div class="{{ $TimesheetTableActionButtonsClass }}">
                                                    <button
                                                        type="button"
                                                        class="{{ $EditTimesheetButton['ButtonClass'] }}"
                                                        onclick="document.getElementById('EditTimesheetEntryDialog_{{ $TimesheetEntry->id }}').showModal()"
                                                        aria-label="{{ $EditTimesheetButton['ButtonAriaLabel'] }}"
                                                        title="{{ $EditTimesheetButton['ButtonTitle'] }}"
                                                    >
                                                        <img
                                                            src="{{ $EditTimesheetButton['ButtonIconPath'] }}"
                                                            alt="{{ $EditTimesheetButton['ButtonIconAlt'] }}"
                                                            class="{{ $EditTimesheetButton['ButtonIconClass'] }}"
                                                        >
                                                        <span class="sr-only">{{ $EditTimesheetButton['ButtonLabel'] }}</span>
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="{{ $DeleteTimesheetButton['ButtonClass'] }}"
                                                        onclick="document.getElementById('DeleteTimesheetEntryDialog_{{ $TimesheetEntry->id }}').showModal()"
                                                        aria-label="{{ $DeleteTimesheetButton['ButtonAriaLabel'] }}"
                                                        title="{{ $DeleteTimesheetButton['ButtonTitle'] }}"
                                                    >
                                                        <img
                                                            src="{{ $DeleteTimesheetButton['ButtonIconPath'] }}"
                                                            alt="{{ $DeleteTimesheetButton['ButtonIconAlt'] }}"
                                                            class="{{ $DeleteTimesheetButton['ButtonIconClass'] }}"
                                                        >
                                                        <span class="sr-only">{{ $DeleteTimesheetButton['ButtonLabel'] }}</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="{{ $TimesheetTableRowClass }}">
                                            <td colspan="{{ count($TimesheetTableColumns) }}" class="{{ $TimesheetTableEmptyCellClass }}">
                                                {{ $TimesheetTableEmptyMessage }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($TimesheetEntries->count() > $TimesheetInitialVisibleEntries)
                            <div class="{{ $TimesheetViewMoreWrapperClass }}">
                                <button
                                    type="button"
                                    id="{{ $TimesheetViewMoreButtonId }}"
                                    class="{{ $TimesheetViewMoreButtonClass }}"
                                >
                                    {{ $TimesheetViewMoreButtonLabel }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                @include('components.add-timesheet-entry')

                @foreach ($TimesheetEntries as $TimesheetEntry)
                    @include('components.edit-timesheet-entry', ['TimesheetEntry' => $TimesheetEntry])
                    @include('components.delete-timesheet-entry', ['TimesheetEntry' => $TimesheetEntry])
                @endforeach
            </main>

            @include('components.app-footer', ['LogoPath' => $AppFooterLogo])
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const SuccessMessage = document.getElementById('TimesheetSuccessMessage');
                const ErrorMessage = document.getElementById('TimesheetErrorMessage');
                const ViewMoreButton = document.getElementById('{{ $TimesheetViewMoreButtonId }}');
                const TimesheetRows = Array.from(document.querySelectorAll('[data-timesheet-entry-row]'));
                const HiddenRowClass = @json($TimesheetTableHiddenRowClass);
                const DigitalClockTime = document.getElementById('{{ $TimesheetDigitalClock['ClockTimeId'] }}');
                const DigitalClockDate = document.getElementById('{{ $TimesheetDigitalClock['ClockDateId'] }}');
                let VisibleEntriesCount = {{ $TimesheetInitialVisibleEntries }};
                const ViewMoreIncrement = {{ $TimesheetViewMoreIncrement }};

                function UpdateDigitalClock() {
                    const CurrentDateTime = new Date();

                    if (DigitalClockTime) {
                        const TimeDisplay = CurrentDateTime.toLocaleTimeString('en-GB', {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                        });

                        DigitalClockTime.textContent = TimeDisplay;
                    }

                    if (DigitalClockDate) {
                        const DateDisplay = CurrentDateTime.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                        });

                        DigitalClockDate.textContent = DateDisplay;
                    }
                }

                UpdateDigitalClock();
                window.setInterval(UpdateDigitalClock, 1000);

                if (SuccessMessage) {
                    window.setTimeout(function () {
                        SuccessMessage.remove();
                    }, 5000);
                }

                if (ErrorMessage) {
                    window.setTimeout(function () {
                        ErrorMessage.remove();
                    }, 5000);
                }

                if (ViewMoreButton) {
                    ViewMoreButton.addEventListener('click', function () {
                        VisibleEntriesCount += ViewMoreIncrement;

                        TimesheetRows.forEach(function (TimesheetRow, Index) {
                            if (Index < VisibleEntriesCount) {
                                TimesheetRow.classList.remove(HiddenRowClass);
                            }
                        });

                        if (VisibleEntriesCount >= TimesheetRows.length) {
                            ViewMoreButton.remove();
                        }
                    });
                }
            });
        </script>

        @if (
            $errors->has($ProjectFieldName) ||
            $errors->has($CategoryFieldName) ||
            $errors->has($DateFieldName) ||
            $errors->has($StartFieldName) ||
            $errors->has($EndFieldName) ||
            $errors->has($TaskFieldName) ||
            $errors->has('edit_project_id') ||
            $errors->has('edit_category_id') ||
            $errors->has('edit_date') ||
            $errors->has('edit_time_start') ||
            $errors->has('edit_time_end') ||
            $errors->has('edit_task')
        )
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const EditTimesheetId = @json(old('edit_timesheet_id'));

                    if (EditTimesheetId) {
                        const EditDialog = document.getElementById(`EditTimesheetEntryDialog_${EditTimesheetId}`);

                        if (EditDialog) {
                            EditDialog.showModal();
                        }

                        return;
                    }

                    const AddDialog = document.getElementById('{{ $AddTimesheetEntryDialogId }}');

                    if (AddDialog) {
                        AddDialog.showModal();
                    }
                });
            </script>
        @endif
    </body>
</html>
