@php
    $EditDialogId = 'EditTimesheetEntryDialog_' . $TimesheetEntry->id;
    $EditProjectFieldId = 'EditProjectId_' . $TimesheetEntry->id;
    $EditCategoryFieldId = 'EditCategoryId_' . $TimesheetEntry->id;
    $EditBreakFieldId = 'EditIsBreak_' . $TimesheetEntry->id;
    $EditDateFieldId = 'EditDate_' . $TimesheetEntry->id;
    $EditStartFieldId = 'EditTimeStart_' . $TimesheetEntry->id;
    $EditEndFieldId = 'EditTimeEnd_' . $TimesheetEntry->id;
    $EditTaskFieldId = 'EditTask_' . $TimesheetEntry->id;

    $IsCurrentEditEntry = old('edit_timesheet_id') == $TimesheetEntry->id;
    $IsBreakEntry = $IsCurrentEditEntry
        ? (bool) old('edit_is_break')
        : ($TimesheetEntry->project === 'Break' && $TimesheetEntry->category === 'Break');

    $EditDateValue = $IsCurrentEditEntry ? old('edit_date') : $TimesheetEntry->date;
    $EditStartValue = $IsCurrentEditEntry ? old('edit_time_start') : substr((string) $TimesheetEntry->time_start, 0, 5);
    $EditEndValue = $IsCurrentEditEntry ? old('edit_time_end') : substr((string) $TimesheetEntry->time_end, 0, 5);
    $EditTaskValue = $IsCurrentEditEntry ? old('edit_task') : $TimesheetEntry->task;
@endphp

<dialog id="{{ $EditDialogId }}" class="EditTimesheetEntryDialog">
    <div class="EditTimesheetEntryModal">
        <div class="EditTimesheetEntryModalHeader">
            <h2 class="EditTimesheetEntryModalTitle">Edit Timesheet Entry</h2>
            <p class="EditTimesheetEntryModalText">Update this timesheet entry for your account.</p>
        </div>

        <form method="POST" action="{{ route('timesheet.edit', ['TimesheetId' => $TimesheetEntry->id]) }}" class="EditTimesheetEntryForm">
            @csrf

            <input type="hidden" name="edit_timesheet_id" value="{{ $TimesheetEntry->id }}">

            <div class="EditTimesheetEntryField">
                <label for="{{ $EditProjectFieldId }}" class="EditTimesheetEntryLabel">
                    Project
                </label>

                <select
                    id="{{ $EditProjectFieldId }}"
                    name="edit_project_id"
                    class="EditTimesheetEntrySelect"
                    @disabled($IsBreakEntry)
                    @if (! $IsBreakEntry) required @endif
                >
                    <option value="">Select project</option>

                    @foreach ($Projects as $Project)
                        <option
                            value="{{ $Project->id }}"
                            @selected(
                                $IsCurrentEditEntry
                                    ? (string) old('edit_project_id') === (string) $Project->id
                                    : $Project->project_name === $TimesheetEntry->project
                            )
                        >
                            {{ $Project->project_name }}
                        </option>
                    @endforeach
                </select>

                @if (! $HasProjects)
                    <p class="EditTimesheetEntryHelperText">{{ $NoProjectsMessage }}</p>
                @endif

                @if ($IsCurrentEditEntry)
                    @error('edit_project_id')
                        <p class="EditTimesheetEntryError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditTimesheetEntryField">
                <label for="{{ $EditCategoryFieldId }}" class="EditTimesheetEntryLabel">
                    Category
                </label>

                <select
                    id="{{ $EditCategoryFieldId }}"
                    name="edit_category_id"
                    class="EditTimesheetEntrySelect"
                    @disabled($IsBreakEntry)
                    @if (! $IsBreakEntry) required @endif
                >
                    <option value="">Select category</option>

                    @foreach ($Categories as $Category)
                        <option
                            value="{{ $Category->id }}"
                            @selected(
                                $IsCurrentEditEntry
                                    ? (string) old('edit_category_id') === (string) $Category->id
                                    : $Category->category_name === $TimesheetEntry->category
                            )
                        >
                            {{ $Category->category_name }}
                        </option>
                    @endforeach
                </select>

                @if (! $HasCategories)
                    <p class="EditTimesheetEntryHelperText">{{ $NoCategoriesMessage }}</p>
                @endif

                @if ($IsCurrentEditEntry)
                    @error('edit_category_id')
                        <p class="EditTimesheetEntryError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditTimesheetEntryField">
                <div class="EditTimesheetEntryBreakRow">
                    <label for="{{ $EditBreakFieldId }}" class="EditTimesheetEntryCheckboxLabel">
                        <input
                            id="{{ $EditBreakFieldId }}"
                            name="edit_is_break"
                            type="checkbox"
                            value="1"
                            class="EditTimesheetEntryCheckbox"
                            @checked($IsBreakEntry)
                        >
                        <span>Break</span>
                    </label>
                </div>
            </div>

            <div class="EditTimesheetEntryField">
                <label for="{{ $EditDateFieldId }}" class="EditTimesheetEntryLabel">
                    Date
                </label>

                <input
                    id="{{ $EditDateFieldId }}"
                    name="edit_date"
                    type="date"
                    value="{{ $EditDateValue }}"
                    class="EditTimesheetEntryInput"
                    required
                >

                @if ($IsCurrentEditEntry)
                    @error('edit_date')
                        <p class="EditTimesheetEntryError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditTimesheetEntryField">
                <label for="{{ $EditStartFieldId }}" class="EditTimesheetEntryLabel">
                    Start
                </label>

                <input
                    id="{{ $EditStartFieldId }}"
                    name="edit_time_start"
                    type="time"
                    value="{{ $EditStartValue }}"
                    class="EditTimesheetEntryInput"
                    required
                >

                @if ($IsCurrentEditEntry)
                    @error('edit_time_start')
                        <p class="EditTimesheetEntryError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditTimesheetEntryField">
                <label for="{{ $EditEndFieldId }}" class="EditTimesheetEntryLabel">
                    End
                </label>

                <input
                    id="{{ $EditEndFieldId }}"
                    name="edit_time_end"
                    type="time"
                    value="{{ $EditEndValue }}"
                    class="EditTimesheetEntryInput"
                    required
                >

                @if ($IsCurrentEditEntry)
                    @error('edit_time_end')
                        <p class="EditTimesheetEntryError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditTimesheetEntryField">
                <label for="{{ $EditTaskFieldId }}" class="EditTimesheetEntryLabel">
                    Task
                </label>

                <input
                    id="{{ $EditTaskFieldId }}"
                    name="edit_task"
                    type="text"
                    value="{{ $EditTaskValue }}"
                    class="EditTimesheetEntryInput"
                    placeholder="Enter task"
                    required
                >

                @if ($IsCurrentEditEntry)
                    @error('edit_task')
                        <p class="EditTimesheetEntryError">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="EditTimesheetEntryModalActions">
                <button
                    type="button"
                    class="EditTimesheetEntryCancelButton"
                    onclick="document.getElementById('{{ $EditDialogId }}').close()"
                >
                    Cancel
                </button>

                <button type="submit" class="EditTimesheetEntrySubmitButton">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const EditBreakCheckbox = document.getElementById('{{ $EditBreakFieldId }}');
        const EditProjectSelect = document.getElementById('{{ $EditProjectFieldId }}');
        const EditCategorySelect = document.getElementById('{{ $EditCategoryFieldId }}');

        if (! EditBreakCheckbox || ! EditProjectSelect || ! EditCategorySelect) {
            return;
        }

        const ToggleEditBreakFields = function () {
            if (EditBreakCheckbox.checked) {
                EditProjectSelect.value = '';
                EditProjectSelect.disabled = true;
                EditProjectSelect.removeAttribute('required');

                EditCategorySelect.value = '';
                EditCategorySelect.disabled = true;
                EditCategorySelect.removeAttribute('required');
            } else {
                EditProjectSelect.disabled = false;
                EditProjectSelect.setAttribute('required', 'required');

                EditCategorySelect.disabled = false;
                EditCategorySelect.setAttribute('required', 'required');
            }
        };

        EditBreakCheckbox.addEventListener('change', ToggleEditBreakFields);
        ToggleEditBreakFields();
    });
</script>
