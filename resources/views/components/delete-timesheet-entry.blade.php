<dialog id="DeleteTimesheetEntryDialog_{{ $TimesheetEntry->id }}" class="DeleteTimesheetEntryDialog">
    <div class="DeleteTimesheetEntryModal">
        <div class="DeleteTimesheetEntryModalHeader">
            <h2 class="DeleteTimesheetEntryModalTitle">Delete Timesheet Entry</h2>
            <p class="DeleteTimesheetEntryModalText">
                Are you sure you want to delete this timesheet entry?
            </p>
        </div>

        <form method="POST" action="{{ route('timesheet.delete', ['TimesheetId' => $TimesheetEntry->id]) }}">
            @csrf

            <div class="DeleteTimesheetEntryModalActions">
                <button
                    type="button"
                    class="DeleteTimesheetEntryCancelButton"
                    onclick="document.getElementById('DeleteTimesheetEntryDialog_{{ $TimesheetEntry->id }}').close()"
                >
                    Cancel
                </button>

                <button type="submit" class="DeleteTimesheetEntrySubmitButton">
                    Delete
                </button>
            </div>
        </form>
    </div>
</dialog>
