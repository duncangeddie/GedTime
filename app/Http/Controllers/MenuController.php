<?php

namespace App\Http\Controllers;

class MenuController extends Controller
{
    public function HamburgerMenu(): array
    {
        // Menu Button Variables
        $MenuButtonId = 'AppHeaderHamburgerButton';
        $MenuButtonAriaLabel = 'Open navigation menu';
        $MenuPanelId = 'AppHeaderHamburgerPanel';

        // Menu Item Variables
        $MenuItems = [
            [
                'Label' => 'Dashboard',
                'Link' => route('dashboard'),
            ],
            [
                'Label' => 'Projects',
                'Link' => route('projects'),
            ],
            [
                'Label' => 'Categories',
                'Link' => route('categories'),
            ],
            [
                'Label' => 'Timesheet',
                'Link' => route('timesheet'),
            ],
            [
                'Label' => 'Reports',
                'Link' => route('reports'),
            ],
            [
                'Label' => 'Settings',
                'Link' => route('settings'),
            ],
        ];

        return [
            'MenuButtonId' => $MenuButtonId,
            'MenuButtonAriaLabel' => $MenuButtonAriaLabel,
            'MenuPanelId' => $MenuPanelId,
            'MenuItems' => $MenuItems,
        ];
    }
}
