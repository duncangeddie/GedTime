@php
    $ButtonController = app(\App\Http\Controllers\ButtonController::class);
    $AuthButtons = $ButtonController->AuthButtons();
@endphp

<header class="GuestHeader">
    <div class="GuestHeaderContainer px-6 lg:px-8">
        <div class="GuestHeaderInner flex items-center justify-between">
            <a href="{{ url('/') }}">
                <img
                    src="{{ $LogoPath }}"
                    alt="GedTime Logo"
                    class="GuestHeaderLogo"
                >
            </a>

            <div class="GuestHeaderActions">
                <a href="{{ $AuthButtons['LoginLink'] }}" class="GuestHeaderButton">
                    {{ $AuthButtons['LoginLabel'] }}
                </a>

                <a href="{{ $AuthButtons['RegisterLink'] }}" class="GuestHeaderButton">
                    {{ $AuthButtons['RegisterLabel'] }}
                </a>
            </div>
        </div>
    </div>
</header>
