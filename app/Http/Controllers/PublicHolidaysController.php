<?php

namespace App\Http\Controllers;

class PublicHolidaysController extends Controller
{
    public function SouthAfricaPublicHolidays(): array
    {
        // South Africa public holiday variables
        $SouthAfricaPublicHolidays = [
            [
                'name' => "New Year's Day",
                'date' => '2026-01-01',
            ],
            [
                'name' => 'Human Rights Day',
                'date' => '2026-03-21',
            ],
            [
                'name' => 'Good Friday',
                'date' => '2026-04-03',
            ],
            [
                'name' => 'Family Day',
                'date' => '2026-04-06',
            ],
            [
                'name' => 'Freedom Day',
                'date' => '2026-04-27',
            ],
            [
                'name' => "Workers' Day",
                'date' => '2026-05-01',
            ],
            [
                'name' => 'Youth Day',
                'date' => '2026-06-16',
            ],
            [
                'name' => "National Women's Day",
                'date' => '2026-08-09',
            ],
            [
                'name' => 'Heritage Day',
                'date' => '2026-09-24',
            ],
            [
                'name' => 'Day of Reconciliation',
                'date' => '2026-12-16',
            ],
            [
                'name' => 'Christmas Day',
                'date' => '2026-12-25',
            ],
            [
                'name' => 'Day of Goodwill',
                'date' => '2026-12-26',
            ],
        ];

        return $SouthAfricaPublicHolidays;
    }
}
