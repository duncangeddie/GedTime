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

        // Favicon Variables
        $ViewFavicon = $this->ViewFavicon();

        // Add Timesheet Button Variables
        $AddTimesheetButton = $this->AddTimesheetButton();
        $AddTimesheetEntryDialogId = $AddTimesheetButton['DialogId'];

        // Edit Timesheet Button Variables
        $EditTimesheetButton = $this->EditTimesheetButton();

        // Delete Timesheet Button Variables
        $DeleteTimesheetButton = $this->DeleteTimesheetButton();

        // Page Variables
        $PageTitle = 'Timesheet';
        $TimesheetPageClass = 'TimesheetPage';
        $TimesheetPageMainClass = 'TimesheetPageMain';
        $TimesheetPageContentClass = 'TimesheetPageContent';
        $TimesheetPageActionsClass = 'TimesheetPageActions';
        $TimesheetPageActionsGroupClass = 'flex w-full flex-col gap-3 md:flex-row md:items-center md:justify-between';
        $TimesheetPageMessageClass = 'TimesheetPageMessage';
        $TimesheetPageErrorMessageClass = 'TimesheetPageErrorMessage';
        $TimesheetPageFiltersFormAction = route('timesheet');
        $TimesheetPageFiltersFormClass = 'flex flex-wrap items-center gap-3 md:justify-end';
        $TimesheetPageFiltersLabelClass = 'sr-only';
        $TimesheetPageFiltersSelectClass = 'min-w-[170px] rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200';
        $TimesheetPageFiltersApplyButtonClass = 'inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300';
        $TimesheetPageFiltersResetButtonClass = 'inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200';
        $TimesheetPageFiltersApplyButtonLabel = 'Apply';
        $TimesheetPageFiltersResetButtonLabel = 'Reset';
        $TimesheetPageFiltersResetUrl = route('timesheet');
        $AddTimesheetEntryTitle = 'Add Timesheet Entry';
        $AddTimesheetEntryText = 'Create a new timesheet entry for your account.';
        $AddTimesheetEntryFormAction = route('timesheet.add');
        $AddTimesheetEntryCancelLabel = 'Cancel';
        $AddTimesheetEntrySubmitLabel = 'Save Entry';
        $NoProjectsMessage = 'No projects found. Tick Break to save this entry as Break.';
        $NoCategoriesMessage = 'No categories found. Tick Break to save this entry as Break.';

        // Filter Variables
        $ProjectFilterLabel = 'Filter by project';
        $ProjectFilterName = 'project_filter';
        $ProjectFilterAllValue = 'all';
        $ProjectFilterAllLabel = 'All Projects';

        $DateRangeFilterLabel = 'Filter by date range';
        $DateRangeFilterName = 'date_range';
        $DateRangeFilterAllValue = 'all';
        $DateRangeFilterAllLabel = 'All Entries';
        $DateRangeFilterTodayValue = 'today';
        $DateRangeFilterTodayLabel = 'Today';
        $DateRangeFilterSevenDaysValue = '7_days';
        $DateRangeFilterSevenDaysLabel = 'Last 7 Days';
        $DateRangeFilterFourteenDaysValue = '14_days';
        $DateRangeFilterFourteenDaysLabel = 'Last 14 Days';

        $DateRangeFilterOptions = [
            [
                'Value' => $DateRangeFilterAllValue,
                'Label' => $DateRangeFilterAllLabel,
            ],
            [
                'Value' => $DateRangeFilterTodayValue,
                'Label' => $DateRangeFilterTodayLabel,
            ],
            [
                'Value' => $DateRangeFilterSevenDaysValue,
                'Label' => $DateRangeFilterSevenDaysLabel,
            ],
            [
                'Value' => $DateRangeFilterFourteenDaysValue,
                'Label' => $DateRangeFilterFourteenDaysLabel,
            ],
        ];

        $SelectedProjectFilter = (string) $Request->query($ProjectFilterName, $ProjectFilterAllValue);
        $SelectedDateRangeFilter = (string) $Request->query($DateRangeFilterName, $DateRangeFilterAllValue);

        $AllowedDateRangeValues = [
            $DateRangeFilterAllValue,
            $DateRangeFilterTodayValue,
            $DateRangeFilterSevenDaysValue,
            $DateRangeFilterFourteenDaysValue,
        ];

        if (! in_array($SelectedDateRangeFilter, $AllowedDateRangeValues, true)) {
            $SelectedDateRangeFilter = $DateRangeFilterAllValue;
        }

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
        $TimesheetTableActionButtonsClass = 'flex flex-row flex-nowrap items-center gap-2';
        $TimesheetTableHiddenRowClass = 'hidden';
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

        // View More Variables
        $TimesheetInitialVisibleEntries = 10;
        $TimesheetViewMoreIncrement = 10;
        $TimesheetViewMoreButtonId = 'TimesheetViewMoreButton';
        $TimesheetViewMoreWrapperClass = 'mt-6 flex justify-center';
        $TimesheetViewMoreButtonClass = 'inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300';
        $TimesheetViewMoreButtonLabel = 'View More';

        // Project Variables
        $Projects = DB::table('projects')
            ->where('user_id', $Request->user()->id)
            ->orderBy('project_name')
            ->get(['id', 'project_name']);

        $HasProjects = $Projects->isNotEmpty();

        $SelectedProject = null;

        if ($SelectedProjectFilter !== $ProjectFilterAllValue) {
            $SelectedProject = $Projects->first(function ($Project) use ($SelectedProjectFilter) {
                return (string) $Project->id === $SelectedProjectFilter;
            });

            if (! $SelectedProject) {
                $SelectedProjectFilter = $ProjectFilterAllValue;
            }
        }

        // Category Variables
        $Categories = DB::table('categories')
            ->where('user_id', $Request->user()->id)
            ->orderBy('category_name')
            ->get(['id', 'category_name']);

        $HasCategories = $Categories->isNotEmpty();

        // Timesheet Variables
        $TimesheetEntriesQuery = DB::table('timesheet')
            ->where('user_id', $Request->user()->id);

        if ($SelectedProjectFilter !== $ProjectFilterAllValue && $SelectedProject) {
            $TimesheetEntriesQuery->where('project', $SelectedProject->project_name);
        }

        $CurrentDate = now()->toDateString();

        if ($SelectedDateRangeFilter === $DateRangeFilterTodayValue) {
            $TimesheetEntriesQuery->whereDate('date', $CurrentDate);
        }

        if ($SelectedDateRangeFilter === $DateRangeFilterSevenDaysValue) {
            $DateRangeStart = now()->subDays(6)->toDateString();

            $TimesheetEntriesQuery->whereBetween('date', [$DateRangeStart, $CurrentDate]);
        }

        if ($SelectedDateRangeFilter === $DateRangeFilterFourteenDaysValue) {
            $DateRangeStart = now()->subDays(13)->toDateString();

            $TimesheetEntriesQuery->whereBetween('date', [$DateRangeStart, $CurrentDate]);
        }

        $TimesheetEntries = $TimesheetEntriesQuery
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
            'ViewFavicon' => $ViewFavicon,
            'AddTimesheetButton' => $AddTimesheetButton,
            'EditTimesheetButton' => $EditTimesheetButton,
            'DeleteTimesheetButton' => $DeleteTimesheetButton,
            'TimesheetPageClass' => $TimesheetPageClass,
            'TimesheetPageMainClass' => $TimesheetPageMainClass,
            'TimesheetPageContentClass' => $TimesheetPageContentClass,
            'TimesheetPageActionsClass' => $TimesheetPageActionsClass,
            'TimesheetPageActionsGroupClass' => $TimesheetPageActionsGroupClass,
            'TimesheetPageMessageClass' => $TimesheetPageMessageClass,
            'TimesheetPageErrorMessageClass' => $TimesheetPageErrorMessageClass,
            'TimesheetPageFiltersFormAction' => $TimesheetPageFiltersFormAction,
            'TimesheetPageFiltersFormClass' => $TimesheetPageFiltersFormClass,
            'TimesheetPageFiltersLabelClass' => $TimesheetPageFiltersLabelClass,
            'TimesheetPageFiltersSelectClass' => $TimesheetPageFiltersSelectClass,
            'TimesheetPageFiltersApplyButtonClass' => $TimesheetPageFiltersApplyButtonClass,
            'TimesheetPageFiltersResetButtonClass' => $TimesheetPageFiltersResetButtonClass,
            'TimesheetPageFiltersApplyButtonLabel' => $TimesheetPageFiltersApplyButtonLabel,
            'TimesheetPageFiltersResetButtonLabel' => $TimesheetPageFiltersResetButtonLabel,
            'TimesheetPageFiltersResetUrl' => $TimesheetPageFiltersResetUrl,
            'AddTimesheetEntryDialogId' => $AddTimesheetEntryDialogId,
            'AddTimesheetEntryTitle' => $AddTimesheetEntryTitle,
            'AddTimesheetEntryText' => $AddTimesheetEntryText,
            'AddTimesheetEntryFormAction' => $AddTimesheetEntryFormAction,
            'AddTimesheetEntryCancelLabel' => $AddTimesheetEntryCancelLabel,
            'AddTimesheetEntrySubmitLabel' => $AddTimesheetEntrySubmitLabel,
            'NoProjectsMessage' => $NoProjectsMessage,
            'NoCategoriesMessage' => $NoCategoriesMessage,
            'ProjectFilterLabel' => $ProjectFilterLabel,
            'ProjectFilterName' => $ProjectFilterName,
            'ProjectFilterAllValue' => $ProjectFilterAllValue,
            'ProjectFilterAllLabel' => $ProjectFilterAllLabel,
            'DateRangeFilterLabel' => $DateRangeFilterLabel,
            'DateRangeFilterName' => $DateRangeFilterName,
            'DateRangeFilterOptions' => $DateRangeFilterOptions,
            'SelectedProjectFilter' => $SelectedProjectFilter,
            'SelectedDateRangeFilter' => $SelectedDateRangeFilter,
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
            'TimesheetTableHiddenRowClass' => $TimesheetTableHiddenRowClass,
            'TimesheetTableColumns' => $TimesheetTableColumns,
            'TimesheetTableEmptyMessage' => $TimesheetTableEmptyMessage,
            'TimesheetInitialVisibleEntries' => $TimesheetInitialVisibleEntries,
            'TimesheetViewMoreIncrement' => $TimesheetViewMoreIncrement,
            'TimesheetViewMoreButtonId' => $TimesheetViewMoreButtonId,
            'TimesheetViewMoreWrapperClass' => $TimesheetViewMoreWrapperClass,
            'TimesheetViewMoreButtonClass' => $TimesheetViewMoreButtonClass,
            'TimesheetViewMoreButtonLabel' => $TimesheetViewMoreButtonLabel,
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

    protected function ViewFavicon(): array
    {
        // Favicon Text Variables
        $FaviconEmoji = '⏰';

        // Favicon Markup Variables
        $FaviconSvgMarkup = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" font-size="82">' . $FaviconEmoji . '</text></svg>';

        // Favicon Link Variables
        $FaviconRel = 'icon';
        $FaviconType = 'image/svg+xml';
        $FaviconHref = 'data:image/svg+xml,' . rawurlencode($FaviconSvgMarkup);

        return [
            'FaviconEmoji' => $FaviconEmoji,
            'FaviconRel' => $FaviconRel,
            'FaviconType' => $FaviconType,
            'FaviconHref' => $FaviconHref,
        ];
    }

    protected function AddTimesheetButton(): array
    {
        // Button Action Variables
        $DialogId = 'AddTimesheetEntryDialog';

        // Button Text Variables
        $ButtonLabel = 'Add Timesheet Entry';
        $ButtonAriaLabel = 'Add Timesheet Entry';
        $ButtonTitle = 'Add Timesheet Entry';
        $ButtonIconAlt = 'Add Timesheet Entry';

        // Button Style Variables
        $ButtonClass = 'inline-flex h-12 w-12 items-center justify-center rounded-lg bg-black p-0 text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300';
        $ButtonIconSizeClass = 'h-8 w-8';
        $ButtonIconClass = $ButtonIconSizeClass . ' object-contain';

        // Button Asset Variables
        $ButtonIconPath = asset('images/add_timesheet_icon.png');

        return [
            'DialogId' => $DialogId,
            'ButtonLabel' => $ButtonLabel,
            'ButtonAriaLabel' => $ButtonAriaLabel,
            'ButtonTitle' => $ButtonTitle,
            'ButtonClass' => $ButtonClass,
            'ButtonIconPath' => $ButtonIconPath,
            'ButtonIconAlt' => $ButtonIconAlt,
            'ButtonIconSizeClass' => $ButtonIconSizeClass,
            'ButtonIconClass' => $ButtonIconClass,
        ];
    }

    protected function EditTimesheetButton(): array
    {
        // Button Text Variables
        $ButtonLabel = 'Edit Timesheet Entry';
        $ButtonAriaLabel = 'Edit Timesheet Entry';
        $ButtonTitle = 'Edit Timesheet Entry';
        $ButtonIconAlt = 'Edit Timesheet Entry';

        // Button Style Variables
        $ButtonClass = 'inline-flex h-16 w-16 items-center justify-center rounded-md bg-transparent p-0 shadow-none transition hover:bg-transparent focus:outline-none focus:ring-0';
        $ButtonIconSizeClass = 'h-8 w-8';
        $ButtonIconClass = $ButtonIconSizeClass . ' object-contain';

        // Button Asset Variables
        $ButtonIconPath = asset('images/edit_timesheet_icon.png');

        return [
            'ButtonLabel' => $ButtonLabel,
            'ButtonAriaLabel' => $ButtonAriaLabel,
            'ButtonTitle' => $ButtonTitle,
            'ButtonClass' => $ButtonClass,
            'ButtonIconPath' => $ButtonIconPath,
            'ButtonIconAlt' => $ButtonIconAlt,
            'ButtonIconSizeClass' => $ButtonIconSizeClass,
            'ButtonIconClass' => $ButtonIconClass,
        ];
    }

    protected function DeleteTimesheetButton(): array
    {
        // Button Text Variables
        $ButtonLabel = 'Delete Timesheet Entry';
        $ButtonAriaLabel = 'Delete Timesheet Entry';
        $ButtonTitle = 'Delete Timesheet Entry';
        $ButtonIconAlt = 'Delete Timesheet Entry';

        // Button Style Variables
        $ButtonClass = 'inline-flex h-16 w-16 items-center justify-center rounded-md bg-transparent p-0 shadow-none transition hover:bg-transparent focus:outline-none focus:ring-0';
        $ButtonIconSizeClass = 'h-8 w-8';
        $ButtonIconClass = $ButtonIconSizeClass . ' object-contain';

        // Button Asset Variables
        $ButtonIconPath = asset('images/delete_timesheet_icon.png');

        return [
            'ButtonLabel' => $ButtonLabel,
            'ButtonAriaLabel' => $ButtonAriaLabel,
            'ButtonTitle' => $ButtonTitle,
            'ButtonClass' => $ButtonClass,
            'ButtonIconPath' => $ButtonIconPath,
            'ButtonIconAlt' => $ButtonIconAlt,
            'ButtonIconSizeClass' => $ButtonIconSizeClass,
            'ButtonIconClass' => $ButtonIconClass,
        ];
    }

    protected function SyncCategoryStatuses(int $UserId): void
    {
        // Controller Variables
        $CategoriesController = app(CategoriesController::class);

        $CategoriesController->SyncCategoryStatuses($UserId);
    }
}
