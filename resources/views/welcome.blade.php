<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GedTime</title>

        @vite(['resources/css/guest.css', 'resources/js/app.js'])
    </head>
    <body>
        @php
            $ComponentController = app(\App\Http\Controllers\ComponentController::class);
            $WelcomeHeaderLogo = $ComponentController->WelcomeHeaderLogo();
            $GuestFooterLogo = $ComponentController->GuestFooterLogo();
        @endphp

        <div class="WelcomePage">
            @include('components.guest-header', ['LogoPath' => $WelcomeHeaderLogo])

            <main class="WelcomePageMain"></main>

            @include('components.guest-footer', ['LogoPath' => $GuestFooterLogo])
        </div>
    </body>
</html>
