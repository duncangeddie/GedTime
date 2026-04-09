<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TimesheetController extends Controller
{
    public function ViewTimesheet(Request $Request): View
    {
        // Component Variables
        $ComponentController = app(ComponentController::class);
        $AppHeaderLogo = $ComponentController->AppHeaderLogo();
        $AppFooterLogo = $ComponentController->AppFooterLogo();

        // Page Variables
        $PageTitle = 'Timesheet';
        $TimesheetPageClass = 'TimesheetPage';
        $TimesheetPageMainClass = 'TimesheetPageMain';
        $TimesheetPageContentClass = 'TimesheetPageContent';
        $TimesheetPageActionsClass = 'TimesheetPageActions';
        $TimesheetPageButtonClass = 'TimesheetPageButton';
        $TimesheetPageMessageClass = 'TimesheetPageMessage';
        $TimesheetPageErrorMessageClass = 'TimesheetPageErrorMessage';
        $AddTimesheetEntryDialogId = 'AddTimesheetEntryDialog';
        $AddTimesheetEntryButtonLabel = 'Add Timesheet Entry';
        $AddTimesheetEntryTitle = 'Add Timesheet Entry';
        $AddTimesheetEntryText = 'Create a new timesheet entry for your account.';
        $AddTimesheetEntryFormAction = route('timesheet.add');
        $AddTimesheetEntryCancelLabel = 'Cancel';
        $AddTimesheetEntrySubmitLabel = 'Save Entry';
        $EditTimesheetButtonLabel = 'Edit';
        $DeleteTimesheetButtonLabel = 'Delete';
        $NoProjectsMessage = 'No projects found. Tick Break to save this entry as Break.';
        $NoCategoriesMessage = 'No categories found. Tick Break to save this entry as Break.';

        // Field Variables
        $ProjectFieldLabel = 'Project';
        $ProjectFieldName = 'project_id';
        $ProjectFieldPlaceholder = 'Select project';

        $CategoryFieldLabel = 'Category';
        $CategoryFieldName = 'category_id';
        $CategoryFieldPlaceholder = 'Select category';

        $BreakFieldLabel = 'Break';
        $BreakFieldName = 'is_break';

        $DateFieldLabel = 'Date';
        $DateFieldName = 'date';

        $StartFieldLabel = 'Start';
        $StartFieldName = 'time_start';

        $EndFieldLabel = 'End';
        $EndFieldName = 'time_end';

        $TaskFieldLabel = 'Task';
        $TaskFieldName = 'task';
        $TaskFieldPlaceholder = 'Enter task';

        // Table Variables
        $TimesheetTableSectionClass = 'TimesheetTableSection';
        $TimesheetTableWrapperClass = 'TimesheetTableWrapper';
        $TimesheetTableClass = 'TimesheetTable';
        $TimesheetTableHeadClass = 'TimesheetTableHead';
        $TimesheetTableHeadingClass = 'TimesheetTableHeading';
        $TimesheetTableBodyClass = 'TimesheetTableBody';
        $TimesheetTableRowClass = 'TimesheetTableRow';
        $TimesheetTableCellClass = 'TimesheetTableCell';
        $TimesheetTableEmptyCellClass = 'TimesheetTableEmptyCell';
        $TimesheetTableActionCellClass = 'TimesheetTableActionCell';
        $TimesheetTableActionButtonsClass = 'TimesheetTableActionButtons';
        $TimesheetTableActionButtonClass = 'TimesheetTableActionButton';
        $TimesheetTableDeleteButtonClass = 'TimesheetTableDeleteButton';
        $TimesheetTableColumns = [
            'Date',
            'Start',
            'End',
            'Duration',
            'Project',
            'Category',
            'Task',
            'Actions',
        ];
        $TimesheetTableEmptyMessage = 'No data';

        // Project Variables
        $Projects = DB::table('projects')
            ->where('user_id', $Request->user()->id)
            ->orderBy('project_name')
            ->get(['id', 'project_name']);

        $HasProjects = $Projects->isNotEmpty();

        // Category Variables
        $Categories = DB::table('categories')
            ->where('user_id', $Request->user()->id)
            ->orderBy('category_name')
            ->get(['id', 'category_name']);

        $HasCategories = $Categories->isNotEmpty();

        // Timesheet Variables
        $TimesheetEntries = DB::table('timesheet')
            ->where('user_id', $Request->user()->id)
            ->orderByDesc('date')
            ->orderByDesc('time_start')
            ->get([
                'id',
                'date',
                'time_start',
                'time_end',
                'duration',
                'project',
                'category',
                'task',
            ])
            ->map(function ($TimesheetEntry) {
                // Duration Variables
                $DurationMinutes = (int) ($TimesheetEntry->duration ?? 0);
                $Hours = intdiv($DurationMinutes, 60);
                $Minutes = $DurationMinutes % 60;
                $DurationDisplay = $Hours . ':' . str_pad((string) $Minutes, 2, '0', STR_PAD_LEFT);

                $TimesheetEntry->DurationDisplay = $DurationDisplay;

                return $TimesheetEntry;
            });

        return view('timesheet', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
            'TimesheetPageClass' => $TimesheetPageClass,
            'TimesheetPageMainClass' => $TimesheetPageMainClass,
            'TimesheetPageContentClass' => $TimesheetPageContentClass,
            'TimesheetPageActionsClass' => $TimesheetPageActionsClass,
            'TimesheetPageButtonClass' => $TimesheetPageButtonClass,
            'TimesheetPageMessageClass' => $TimesheetPageMessageClass,
            'TimesheetPageErrorMessageClass' => $TimesheetPageErrorMessageClass,
            'AddTimesheetEntryDialogId' => $AddTimesheetEntryDialogId,
            'AddTimesheetEntryButtonLabel' => $AddTimesheetEntryButtonLabel,
            'AddTimesheetEntryTitle' => $AddTimesheetEntryTitle,
            'AddTimesheetEntryText' => $AddTimesheetEntryText,
            'AddTimesheetEntryFormAction' => $AddTimesheetEntryFormAction,
            'AddTimesheetEntryCancelLabel' => $AddTimesheetEntryCancelLabel,
            'AddTimesheetEntrySubmitLabel' => $AddTimesheetEntrySubmitLabel,
            'EditTimesheetButtonLabel' => $EditTimesheetButtonLabel,
            'DeleteTimesheetButtonLabel' => $DeleteTimesheetButtonLabel,
            'NoProjectsMessage' => $NoProjectsMessage,
            'NoCategoriesMessage' => $NoCategoriesMessage,
            'ProjectFieldLabel' => $ProjectFieldLabel,
            'ProjectFieldName' => $ProjectFieldName,
            'ProjectFieldPlaceholder' => $ProjectFieldPlaceholder,
            'CategoryFieldLabel' => $CategoryFieldLabel,
            'CategoryFieldName' => $CategoryFieldName,
            'CategoryFieldPlaceholder' => $CategoryFieldPlaceholder,
            'BreakFieldLabel' => $BreakFieldLabel,
            'BreakFieldName' => $BreakFieldName,
            'DateFieldLabel' => $DateFieldLabel,
            'DateFieldName' => $DateFieldName,
            'StartFieldLabel' => $StartFieldLabel,
            'StartFieldName' => $StartFieldName,
            'EndFieldLabel' => $EndFieldLabel,
            'EndFieldName' => $EndFieldName,
            'TaskFieldLabel' => $TaskFieldLabel,
            'TaskFieldName' => $TaskFieldName,
            'TaskFieldPlaceholder' => $TaskFieldPlaceholder,
            'TimesheetTableSectionClass' => $TimesheetTableSectionClass,
            'TimesheetTableWrapperClass' => $TimesheetTableWrapperClass,
            'TimesheetTableClass' => $TimesheetTableClass,
            'TimesheetTableHeadClass' => $TimesheetTableHeadClass,
            'TimesheetTableHeadingClass' => $TimesheetTableHeadingClass,
            'TimesheetTableBodyClass' => $TimesheetTableBodyClass,
            'TimesheetTableRowClass' => $TimesheetTableRowClass,
            'TimesheetTableCellClass' => $TimesheetTableCellClass,
            'TimesheetTableEmptyCellClass' => $TimesheetTableEmptyCellClass,
            'TimesheetTableActionCellClass' => $TimesheetTableActionCellClass,
            'TimesheetTableActionButtonsClass' => $TimesheetTableActionButtonsClass,
            'TimesheetTableActionButtonClass' => $TimesheetTableActionButtonClass,
            'TimesheetTableDeleteButtonClass' => $TimesheetTableDeleteButtonClass,
            'TimesheetTableColumns' => $TimesheetTableColumns,
            'TimesheetTableEmptyMessage' => $TimesheetTableEmptyMessage,
            'Projects' => $Projects,
            'HasProjects' => $HasProjects,
            'Categories' => $Categories,
            'HasCategories' => $HasCategories,
            'TimesheetEntries' => $TimesheetEntries,
        ]);
    }

    public function AddTimesheetEntry(Request $Request): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Break Variables
        $IsBreak = $Request->boolean('is_break');

        // Validation Variables
        $ProjectRules = [
            'nullable',
            'integer',
            Rule::exists('projects', 'id')->where(function ($Query) use ($User) {
                $Query->where('user_id', $User->id);
            }),
        ];

        if (! $IsBreak) {
            array_unshift($ProjectRules, 'required');
        }

        $CategoryRules = [
            'nullable',
            'integer',
            Rule::exists('categories', 'id')->where(function ($Query) use ($User) {
                $Query->where('user_id', $User->id);
            }),
        ];

        if (! $IsBreak) {
            array_unshift($CategoryRules, 'required');
        }

        $ValidatedData = $Request->validate([
            'is_break' => ['nullable', 'boolean'],
            'project_id' => $ProjectRules,
            'category_id' => $CategoryRules,
            'date' => ['required', 'date'],
            'time_start' => ['required', 'date_format:H:i'],
            'time_end' => ['required', 'date_format:H:i', 'after:time_start'],
            'task' => ['required', 'string', 'max:255'],
        ]);

        // Project Variables
        $ProjectName = 'Break';

        if (! $IsBreak) {
            $SelectedProject = DB::table('projects')
                ->where('id', $ValidatedData['project_id'])
                ->where('user_id', $User->id)
                ->first(['project_name']);

            if (! $SelectedProject) {
                return redirect()
                    ->route('timesheet')
                    ->with('ErrorMessage', 'Selected project could not be found.');
            }

            $ProjectName = $SelectedProject->project_name;
        }

        // Category Variables
        $CategoryName = 'Break';

        if (! $IsBreak) {
            $SelectedCategory = DB::table('categories')
                ->where('id', $ValidatedData['category_id'])
                ->where('user_id', $User->id)
                ->first(['category_name']);

            if (! $SelectedCategory) {
                return redirect()
                    ->route('timesheet')
                    ->with('ErrorMessage', 'Selected category could not be found.');
            }

            $CategoryName = $SelectedCategory->category_name;
        }

        // Timestamp Variables
        $CreatedAt = now();
        $UpdatedAt = now();

        // Timesheet Variables
        DB::table('timesheet')->insert([
            'user_id' => $User->id,
            'project' => $ProjectName,
            'category' => $CategoryName,
            'date' => $ValidatedData['date'],
            'time_start' => $ValidatedData['time_start'],
            'time_end' => $ValidatedData['time_end'],
            'task' => $ValidatedData['task'],
            'created_at' => $CreatedAt,
            'updated_at' => $UpdatedAt,
        ]);

        // Sync Variables
        $this->SyncCategoryStatuses($User->id);

        return redirect()
            ->route('timesheet')
            ->with('SuccessMessage', 'Timesheet entry added successfully.');
    }

    public function EditTimesheetEntry(Request $Request, int $TimesheetId): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Break Variables
        $IsBreak = $Request->boolean('edit_is_break');

        // Validation Variables
        $ProjectRules = [
            'nullable',
            'integer',
            Rule::exists('projects', 'id')->where(function ($Query) use ($User) {
                $Query->where('user_id', $User->id);
            }),
        ];

        if (! $IsBreak) {
            array_unshift($ProjectRules, 'required');
        }

        $CategoryRules = [
            'nullable',
            'integer',
            Rule::exists('categories', 'id')->where(function ($Query) use ($User) {
                $Query->where('user_id', $User->id);
            }),
        ];

        if (! $IsBreak) {
            array_unshift($CategoryRules, 'required');
        }

        $ValidatedData = $Request->validate([
            'edit_timesheet_id' => ['required', 'integer'],
            'edit_is_break' => ['nullable', 'boolean'],
            'edit_project_id' => $ProjectRules,
            'edit_category_id' => $CategoryRules,
            'edit_date' => ['required', 'date'],
            'edit_time_start' => ['required', 'date_format:H:i'],
            'edit_time_end' => ['required', 'date_format:H:i', 'after:edit_time_start'],
            'edit_task' => ['required', 'string', 'max:255'],
        ]);

        if ((int) $ValidatedData['edit_timesheet_id'] !== $TimesheetId) {
            return redirect()
                ->route('timesheet')
                ->with('ErrorMessage', 'Timesheet entry could not be updated.');
        }

        // Project Variables
        $ProjectName = 'Break';

        if (! $IsBreak) {
            $SelectedProject = DB::table('projects')
                ->where('id', $ValidatedData['edit_project_id'])
                ->where('user_id', $User->id)
                ->first(['project_name']);

            if (! $SelectedProject) {
                return redirect()
                    ->route('timesheet')
                    ->with('ErrorMessage', 'Selected project could not be found.');
            }

            $ProjectName = $SelectedProject->project_name;
        }

        // Category Variables
        $CategoryName = 'Break';

        if (! $IsBreak) {
            $SelectedCategory = DB::table('categories')
                ->where('id', $ValidatedData['edit_category_id'])
                ->where('user_id', $User->id)
                ->first(['category_name']);

            if (! $SelectedCategory) {
                return redirect()
                    ->route('timesheet')
                    ->with('ErrorMessage', 'Selected category could not be found.');
            }

            $CategoryName = $SelectedCategory->category_name;
        }

        // Timestamp Variables
        $UpdatedAt = now();

        // Timesheet Variables
        $UpdatedRows = DB::table('timesheet')
            ->where('id', $TimesheetId)
            ->where('user_id', $User->id)
            ->update([
                'project' => $ProjectName,
                'category' => $CategoryName,
                'date' => $ValidatedData['edit_date'],
                'time_start' => $ValidatedData['edit_time_start'],
                'time_end' => $ValidatedData['edit_time_end'],
                'task' => $ValidatedData['edit_task'],
                'updated_at' => $UpdatedAt,
            ]);

        if ($UpdatedRows === 0) {
            return redirect()
                ->route('timesheet')
                ->with('ErrorMessage', 'Timesheet entry could not be updated.');
        }

        // Sync Variables
        $this->SyncCategoryStatuses($User->id);

        return redirect()
            ->route('timesheet')
            ->with('SuccessMessage', 'Timesheet entry updated successfully.');
    }

    public function DeleteTimesheetEntry(Request $Request, int $TimesheetId): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Timesheet Variables
        $DeletedRows = DB::table('timesheet')
            ->where('id', $TimesheetId)
            ->where('user_id', $User->id)
            ->delete();

        if ($DeletedRows === 0) {
            return redirect()
                ->route('timesheet')
                ->with('ErrorMessage', 'Timesheet entry could not be deleted.');
        }

        // Sync Variables
        $this->SyncCategoryStatuses($User->id);

        return redirect()
            ->route('timesheet')
            ->with('SuccessMessage', 'Timesheet entry deleted successfully.');
    }

    protected function SyncCategoryStatuses(int $UserId): void
    {
        // Controller Variables
        $CategoriesController = app(CategoriesController::class);

        $CategoriesController->SyncCategoryStatuses($UserId);
    }
}
