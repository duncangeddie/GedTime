<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="ReportsPage">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo, 'PageTitle' => $PageTitle])

            <main class="ReportsPageMain"></main>

            @include('components.app-footer', ['LogoPath' => $AppFooterLogo])
        </div>
    </body>
</html>
