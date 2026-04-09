<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="{{ $TimesheetPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo])

            <main class="{{ $TimesheetPageMainClass }}">
                <div class="{{ $TimesheetPageContentClass }}">
                    <div class="{{ $TimesheetPageActionsClass }}">
                        <button
                            type="button"
                            class="{{ $TimesheetPageButtonClass }}"
                            onclick="document.getElementById('{{ $AddTimesheetEntryDialogId }}').showModal()"
                        >
                            {{ $AddTimesheetEntryButtonLabel }}
                        </button>
                    </div>

                    @if (session('SuccessMessage'))
                        <div class="{{ $TimesheetPageMessageClass }}">
                            {{ session('SuccessMessage') }}
                        </div>
                    @endif

                    @if (session('ErrorMessage'))
                        <div class="{{ $TimesheetPageErrorMessageClass }}">
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
                                        <tr class="{{ $TimesheetTableRowClass }}">
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
                                                        class="{{ $TimesheetTableActionButtonClass }}"
                                                        onclick="document.getElementById('EditTimesheetEntryDialog_{{ $TimesheetEntry->id }}').showModal()"
                                                    >
                                                        {{ $EditTimesheetButtonLabel }}
                                                    </button>

                                                    <button
                                                        type="button"
                                                        class="{{ $TimesheetTableDeleteButtonClass }}"
                                                        onclick="document.getElementById('DeleteTimesheetEntryDialog_{{ $TimesheetEntry->id }}').showModal()"
                                                    >
                                                        {{ $DeleteTimesheetButtonLabel }}
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
