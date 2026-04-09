<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function ViewCategories(Request $Request): View
    {
        // User Variables
        $User = $Request->user();

        // Sync Variables
        $this->SyncCategoryStatuses($User->id);

        // Component Variables
        $ComponentController = app(ComponentController::class);
        $AppHeaderLogo = $ComponentController->AppHeaderLogo();
        $AppFooterLogo = $ComponentController->AppFooterLogo();

        // Page Variables
        $PageTitle = 'Categories';
        $CategoriesPageClass = 'CategoriesPage';
        $CategoriesPageMainClass = 'CategoriesPageMain';
        $CategoriesPageContentClass = 'CategoriesPageContent';
        $CategoriesPageActionsClass = 'CategoriesPageActions';
        $CategoriesPageButtonClass = 'CategoriesPageButton';
        $CategoriesPageMessageClass = 'CategoriesPageMessage';
        $CategoriesPageErrorMessageClass = 'CategoriesPageErrorMessage';

        // Modal Variables
        $AddCategoryDialogId = 'AddCategoryDialog';
        $AddCategoryButtonLabel = 'Add Category';
        $AddCategoryTitle = 'Add Category';
        $AddCategoryText = 'Create a new category for your account.';
        $AddCategoryFormAction = route('categories.add');
        $AddCategoryCancelLabel = 'Cancel';
        $AddCategorySubmitLabel = 'Save Category';
        $DeleteCategoryButtonLabel = 'Delete';

        // Field Variables
        $CategoryNameLabel = 'Name';
        $CategoryNameName = 'category_name';
        $CategoryNamePlaceholder = 'Enter category name';

        // Table Variables
        $CategoriesTableSectionClass = 'CategoriesTableSection';
        $CategoriesTableWrapperClass = 'CategoriesTableWrapper';
        $CategoriesTableClass = 'CategoriesTable';
        $CategoriesTableHeadClass = 'CategoriesTableHead';
        $CategoriesTableHeadingClass = 'CategoriesTableHeading';
        $CategoriesTableBodyClass = 'CategoriesTableBody';
        $CategoriesTableRowClass = 'CategoriesTableRow';
        $CategoriesTableCellClass = 'CategoriesTableCell';
        $CategoriesTableEmptyCellClass = 'CategoriesTableEmptyCell';
        $CategoriesTableActionCellClass = 'CategoriesTableActionCell';
        $CategoriesTableActionButtonsClass = 'CategoriesTableActionButtons';
        $CategoriesTableDeleteButtonClass = 'CategoriesTableDeleteButton';
        $CategoriesTableStatusBadgeClass = 'CategoriesTableStatusBadge';
        $CategoriesTableStatusBadgeActiveClass = 'CategoriesTableStatusBadgeActive';
        $CategoriesTableStatusBadgeInactiveClass = 'CategoriesTableStatusBadgeInactive';
        $CategoriesTableColumns = [
            'Category Name',
            'Status',
            'Actions',
        ];
        $CategoriesTableEmptyMessage = 'No data';

        // Category Variables
        $Categories = DB::table('categories')
            ->where('user_id', $User->id)
            ->orderBy('category_name')
            ->get([
                'id',
                'category_name',
                'status',
            ])
            ->map(function ($Category) use (
                $CategoriesTableStatusBadgeClass,
                $CategoriesTableStatusBadgeActiveClass,
                $CategoriesTableStatusBadgeInactiveClass
            ) {
                // Status Variables
                $Category->StatusLabel = $Category->status ? '🟢' : '🔴';
                $Category->StatusClass = $Category->status
                    ? $CategoriesTableStatusBadgeClass . ' ' . $CategoriesTableStatusBadgeActiveClass
                    : $CategoriesTableStatusBadgeClass . ' ' . $CategoriesTableStatusBadgeInactiveClass;

                // Action Variables
                $Category->CanDelete = ! (bool) $Category->status;

                return $Category;
            });

        return view('categories', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
            'CategoriesPageClass' => $CategoriesPageClass,
            'CategoriesPageMainClass' => $CategoriesPageMainClass,
            'CategoriesPageContentClass' => $CategoriesPageContentClass,
            'CategoriesPageActionsClass' => $CategoriesPageActionsClass,
            'CategoriesPageButtonClass' => $CategoriesPageButtonClass,
            'CategoriesPageMessageClass' => $CategoriesPageMessageClass,
            'CategoriesPageErrorMessageClass' => $CategoriesPageErrorMessageClass,
            'AddCategoryDialogId' => $AddCategoryDialogId,
            'AddCategoryButtonLabel' => $AddCategoryButtonLabel,
            'AddCategoryTitle' => $AddCategoryTitle,
            'AddCategoryText' => $AddCategoryText,
            'AddCategoryFormAction' => $AddCategoryFormAction,
            'AddCategoryCancelLabel' => $AddCategoryCancelLabel,
            'AddCategorySubmitLabel' => $AddCategorySubmitLabel,
            'DeleteCategoryButtonLabel' => $DeleteCategoryButtonLabel,
            'CategoryNameLabel' => $CategoryNameLabel,
            'CategoryNameName' => $CategoryNameName,
            'CategoryNamePlaceholder' => $CategoryNamePlaceholder,
            'CategoriesTableSectionClass' => $CategoriesTableSectionClass,
            'CategoriesTableWrapperClass' => $CategoriesTableWrapperClass,
            'CategoriesTableClass' => $CategoriesTableClass,
            'CategoriesTableHeadClass' => $CategoriesTableHeadClass,
            'CategoriesTableHeadingClass' => $CategoriesTableHeadingClass,
            'CategoriesTableBodyClass' => $CategoriesTableBodyClass,
            'CategoriesTableRowClass' => $CategoriesTableRowClass,
            'CategoriesTableCellClass' => $CategoriesTableCellClass,
            'CategoriesTableEmptyCellClass' => $CategoriesTableEmptyCellClass,
            'CategoriesTableActionCellClass' => $CategoriesTableActionCellClass,
            'CategoriesTableActionButtonsClass' => $CategoriesTableActionButtonsClass,
            'CategoriesTableDeleteButtonClass' => $CategoriesTableDeleteButtonClass,
            'CategoriesTableStatusBadgeClass' => $CategoriesTableStatusBadgeClass,
            'CategoriesTableStatusBadgeActiveClass' => $CategoriesTableStatusBadgeActiveClass,
            'CategoriesTableStatusBadgeInactiveClass' => $CategoriesTableStatusBadgeInactiveClass,
            'CategoriesTableColumns' => $CategoriesTableColumns,
            'CategoriesTableEmptyMessage' => $CategoriesTableEmptyMessage,
            'Categories' => $Categories,
        ]);
    }

    public function AddCategory(Request $Request): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Input Variables
        $CategoryName = trim((string) $Request->input('category_name'));

        $Request->merge([
            'category_name' => $CategoryName,
        ]);

        // Validation Variables
        $ValidatedData = $Request->validate([
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'category_name')->where(function ($Query) use ($User) {
                    $Query->where('user_id', $User->id);
                }),
            ],
        ], [
            'category_name.unique' => 'This category already exists.',
        ]);

        // Timestamp Variables
        $CreatedAt = now();
        $UpdatedAt = now();

        // Category Variables
        DB::table('categories')->insert([
            'user_id' => $User->id,
            'category_name' => $ValidatedData['category_name'],
            'status' => false,
            'created_at' => $CreatedAt,
            'updated_at' => $UpdatedAt,
        ]);

        // Sync Variables
        $this->SyncCategoryStatuses($User->id);

        return redirect()
            ->route('categories')
            ->with('SuccessMessage', 'Category added successfully.');
    }

    public function DeleteCategory(Request $Request, int $CategoryId): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Delete Validation Variables
        $Category = DB::table('categories')
            ->where('id', $CategoryId)
            ->where('user_id', $User->id)
            ->first(['id', 'status']);

        if (! $Category) {
            return redirect()
                ->route('categories')
                ->with('ErrorMessage', 'Category could not be deleted.');
        }

        if ((bool) $Category->status) {
            return redirect()
                ->route('categories')
                ->with('ErrorMessage', 'Active categories cannot be deleted.');
        }

        // Category Variables
        $DeletedRows = DB::table('categories')
            ->where('id', $CategoryId)
            ->where('user_id', $User->id)
            ->delete();

        if ($DeletedRows === 0) {
            return redirect()
                ->route('categories')
                ->with('ErrorMessage', 'Category could not be deleted.');
        }

        return redirect()
            ->route('categories')
            ->with('SuccessMessage', 'Category deleted successfully.');
    }

    public function SyncCategoryStatuses(int $UserId): void
    {
        // Timestamp Variables
        $UpdatedAt = now();

        // Reset Variables
        DB::table('categories')
            ->where('user_id', $UserId)
            ->update([
                'status' => false,
                'updated_at' => $UpdatedAt,
            ]);

        // Timesheet Variables
        $UsedCategoryNames = DB::table('timesheet')
            ->where('user_id', $UserId)
            ->whereNotNull('category')
            ->pluck('category')
            ->map(function ($CategoryName) {
                return trim((string) $CategoryName);
            })
            ->filter(function ($CategoryName) {
                return $CategoryName !== '';
            })
            ->unique()
            ->values();

        if ($UsedCategoryNames->isEmpty()) {
            return;
        }

        // Status Variables
        DB::table('categories')
            ->where('user_id', $UserId)
            ->whereIn('category_name', $UsedCategoryNames)
            ->update([
                'status' => true,
                'updated_at' => $UpdatedAt,
            ]);
    }
}
