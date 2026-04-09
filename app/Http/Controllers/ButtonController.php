<?php

namespace App\Http\Controllers;

class ButtonController extends Controller
{
    public function AuthButtons(): array
    {
        // Button Variables
        $LoginLabel = 'Login';
        $LoginLink = route('login');

        $RegisterLabel = 'Register';
        $RegisterLink = route('register');

        return [
            'LoginLabel' => $LoginLabel,
            'LoginLink' => $LoginLink,
            'RegisterLabel' => $RegisterLabel,
            'RegisterLink' => $RegisterLink,
        ];
    }

    public function NavigationButtons(): array
    {
        // Navigation Button Variables
        $ProjectsLabel = 'Projects';
        $ProjectsLink = route('projects');

        $CategoriesLabel = 'Categories';
        $CategoriesLink = route('categories');

        $TimesheetLabel = 'Timesheet';
        $TimesheetLink = route('timesheet');

        $ReportsLabel = 'Reports';
        $ReportsLink = route('reports');

        $SettingsLabel = 'Settings';
        $SettingsLink = route('settings');

        return [
            [
                'Label' => $ProjectsLabel,
                'Link' => $ProjectsLink,
            ],
            [
                'Label' => $CategoriesLabel,
                'Link' => $CategoriesLink,
            ],
            [
                'Label' => $TimesheetLabel,
                'Link' => $TimesheetLink,
            ],
            [
                'Label' => $ReportsLabel,
                'Link' => $ReportsLink,
            ],
            [
                'Label' => $SettingsLabel,
                'Link' => $SettingsLink,
            ],
        ];
    }
}
