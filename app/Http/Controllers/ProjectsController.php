<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    public function ViewProjects(Request $Request): View
    {
        // Component Variables
        $ComponentController = app(ComponentController::class);
        $AppHeaderLogo = $ComponentController->AppHeaderLogo();
        $AppFooterLogo = $ComponentController->AppFooterLogo();

        // Page Variables
        $PageTitle = 'Projects';
        $ProjectsPageClass = 'ProjectsPage';
        $ProjectsPageMainClass = 'ProjectsPageMain';
        $ProjectsPageContentClass = 'ProjectsPageContent';
        $ProjectsPageActionsClass = 'ProjectsPageActions';
        $ProjectsPageButtonClass = 'ProjectsPageButton';
        $ProjectsPageMessageClass = 'ProjectsPageMessage';
        $ProjectsPageErrorMessageClass = 'ProjectsPageErrorMessage';
        $ProjectsTableSectionClass = 'ProjectsTableSection';
        $ProjectsTableWrapperClass = 'ProjectsTableWrapper';
        $ProjectsTableClass = 'ProjectsTable';
        $ProjectsTableHeadClass = 'ProjectsTableHead';
        $ProjectsTableHeadingClass = 'ProjectsTableHeading';
        $ProjectsTableBodyClass = 'ProjectsTableBody';
        $ProjectsTableRowClass = 'ProjectsTableRow';
        $ProjectsTableCellClass = 'ProjectsTableCell';
        $ProjectsTableEmptyCellClass = 'ProjectsTableEmptyCell';
        $ProjectsTableActionCellClass = 'ProjectsTableActionCell';
        $ProjectsTableActionButtonsClass = 'ProjectsTableActionButtons';
        $ProjectsTableActionButtonClass = 'ProjectsTableActionButton';
        $ProjectsTableDeleteButtonClass = 'ProjectsTableDeleteButton';
        $AddProjectButtonLabel = 'Add Project';
        $EditProjectButtonLabel = 'Edit';
        $DeleteProjectButtonLabel = 'Delete';
        $ProjectsTableEmptyMessage = 'No data';

        // Table Variables
        $ProjectsTableColumns = [
            'Project Name',
            'Status',
            'Actions',
        ];

        $Projects = DB::table('projects')
            ->where('user_id', $Request->user()->id)
            ->orderByDesc('id')
            ->get();

        return view('projects', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
            'ProjectsPageClass' => $ProjectsPageClass,
            'ProjectsPageMainClass' => $ProjectsPageMainClass,
            'ProjectsPageContentClass' => $ProjectsPageContentClass,
            'ProjectsPageActionsClass' => $ProjectsPageActionsClass,
            'ProjectsPageButtonClass' => $ProjectsPageButtonClass,
            'ProjectsPageMessageClass' => $ProjectsPageMessageClass,
            'ProjectsPageErrorMessageClass' => $ProjectsPageErrorMessageClass,
            'ProjectsTableSectionClass' => $ProjectsTableSectionClass,
            'ProjectsTableWrapperClass' => $ProjectsTableWrapperClass,
            'ProjectsTableClass' => $ProjectsTableClass,
            'ProjectsTableHeadClass' => $ProjectsTableHeadClass,
            'ProjectsTableHeadingClass' => $ProjectsTableHeadingClass,
            'ProjectsTableBodyClass' => $ProjectsTableBodyClass,
            'ProjectsTableRowClass' => $ProjectsTableRowClass,
            'ProjectsTableCellClass' => $ProjectsTableCellClass,
            'ProjectsTableEmptyCellClass' => $ProjectsTableEmptyCellClass,
            'ProjectsTableActionCellClass' => $ProjectsTableActionCellClass,
            'ProjectsTableActionButtonsClass' => $ProjectsTableActionButtonsClass,
            'ProjectsTableActionButtonClass' => $ProjectsTableActionButtonClass,
            'ProjectsTableDeleteButtonClass' => $ProjectsTableDeleteButtonClass,
            'AddProjectButtonLabel' => $AddProjectButtonLabel,
            'EditProjectButtonLabel' => $EditProjectButtonLabel,
            'DeleteProjectButtonLabel' => $DeleteProjectButtonLabel,
            'ProjectsTableEmptyMessage' => $ProjectsTableEmptyMessage,
            'ProjectsTableColumns' => $ProjectsTableColumns,
            'Projects' => $Projects,
        ]);
    }

    public function AddProject(Request $Request): RedirectResponse
    {
        // Validation Variables
        $ValidatedData = $Request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Not started,In progress,On hold,Completed,Cancelled'],
        ]);

        // User Variables
        $User = $Request->user();

        // Timestamp Variables
        $CreatedAt = now();
        $UpdatedAt = now();

        // Project Variables
        DB::table('projects')->insert([
            'user_id' => $User->id,
            'project_name' => $ValidatedData['project_name'],
            'status' => $ValidatedData['status'],
            'created_at' => $CreatedAt,
            'updated_at' => $UpdatedAt,
        ]);

        return redirect()
            ->route('projects')
            ->with('SuccessMessage', 'Project added successfully.');
    }

    public function EditProject(Request $Request, int $ProjectId): RedirectResponse
    {
        // Validation Variables
        $ValidatedData = $Request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Not started,In progress,On hold,Completed,Cancelled'],
            'edit_project_id' => ['required', 'integer'],
        ]);

        // User Variables
        $User = $Request->user();

        // Timestamp Variables
        $UpdatedAt = now();

        // Project Variables
        $UpdatedRows = DB::table('projects')
            ->where('id', $ProjectId)
            ->where('user_id', $User->id)
            ->update([
                'project_name' => $ValidatedData['project_name'],
                'status' => $ValidatedData['status'],
                'updated_at' => $UpdatedAt,
            ]);

        if ($UpdatedRows === 0) {
            return redirect()
                ->route('projects')
                ->with('ErrorMessage', 'Project could not be updated.');
        }

        return redirect()
            ->route('projects')
            ->with('SuccessMessage', 'Project updated successfully.');
    }

    public function DeleteProject(Request $Request, int $ProjectId): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Project Variables
        $DeletedRows = DB::table('projects')
            ->where('id', $ProjectId)
            ->where('user_id', $User->id)
            ->delete();

        if ($DeletedRows === 0) {
            return redirect()
                ->route('projects')
                ->with('ErrorMessage', 'Project could not be deleted.');
        }

        return redirect()
            ->route('projects')
            ->with('SuccessMessage', 'Project deleted successfully.');
    }
}
