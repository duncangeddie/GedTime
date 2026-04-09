<?php

namespace App\Http\Controllers;

class ComponentController extends Controller
{
    public function WelcomeHeaderLogo(): string
    {
        // Header Variables
        $LogoPath = asset('images/logo.png');

        return $LogoPath;
    }

    public function AppHeaderLogo(): string
    {
        // Header Variables
        $LogoPath = asset('images/logo.png');

        return $LogoPath;
    }

    public function GuestFooterLogo(): string
    {
        // Footer Variables
        $LogoPath = asset('images/logo_short.png');

        return $LogoPath;
    }

    public function AppFooterLogo(): string
    {
        // Footer Variables
        $LogoPath = asset('images/logo_short.png');

        return $LogoPath;
    }
}
