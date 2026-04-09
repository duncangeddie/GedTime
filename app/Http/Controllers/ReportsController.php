<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ReportsController extends Controller
{
    public function ViewReports(): View
    {
        // Component Variables
        $ComponentController = app(ComponentController::class);
        $AppHeaderLogo = $ComponentController->AppHeaderLogo();
        $AppFooterLogo = $ComponentController->AppFooterLogo();

        // Page Variables
        $PageTitle = 'Reports';

        return view('reports', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
        ]);
    }
}
