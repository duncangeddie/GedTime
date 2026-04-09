@php
    $MenuController = app(\App\Http\Controllers\MenuController::class);
    $HamburgerMenu = $MenuController->HamburgerMenu();
    $HeaderPageTitle = $PageTitle ?? '';
@endphp

<header class="AppHeader">
    <div class="AppHeaderContainer px-6 lg:px-8">
        <div class="AppHeaderContent">
            <div class="AppHeaderBrand">
                <a href="{{ url('/dashboard') }}">
                    <img
                        src="{{ $LogoPath }}"
                        alt="GedTime Logo"
                        class="AppHeaderLogo"
                    >
                </a>
            </div>

            <div class="AppHeaderTitleWrap">
                <h1 class="AppHeaderTitle">{{ $HeaderPageTitle }}</h1>
            </div>

            <div class="AppHeaderActions">
                <button
                    type="button"
                    id="{{ $HamburgerMenu['MenuButtonId'] }}"
                    class="AppHeaderHamburgerButton"
                    aria-label="{{ $HamburgerMenu['MenuButtonAriaLabel'] }}"
                    aria-expanded="false"
                    aria-controls="{{ $HamburgerMenu['MenuPanelId'] }}"
                >
                    <span class="AppHeaderHamburgerLine"></span>
                    <span class="AppHeaderHamburgerLine"></span>
                    <span class="AppHeaderHamburgerLine"></span>
                </button>

                <div id="{{ $HamburgerMenu['MenuPanelId'] }}" class="AppHeaderHamburgerPanel" hidden>
                    <div class="AppHeaderHamburgerMenu">
                        @foreach ($HamburgerMenu['MenuItems'] as $MenuItem)
                            <a href="{{ $MenuItem['Link'] }}" class="AppHeaderHamburgerLink">
                                {{ $MenuItem['Label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const MenuButton = document.getElementById('{{ $HamburgerMenu['MenuButtonId'] }}');
        const MenuPanel = document.getElementById('{{ $HamburgerMenu['MenuPanelId'] }}');

        if (! MenuButton || ! MenuPanel) {
            return;
        }

        const CloseMenu = function () {
            MenuPanel.hidden = true;
            MenuButton.setAttribute('aria-expanded', 'false');
        };

        const OpenMenu = function () {
            MenuPanel.hidden = false;
            MenuButton.setAttribute('aria-expanded', 'true');
        };

        MenuButton.addEventListener('click', function () {
            if (MenuPanel.hidden) {
                OpenMenu();
            } else {
                CloseMenu();
            }
        });

        document.addEventListener('click', function (Event) {
            if (! MenuButton.contains(Event.target) && ! MenuPanel.contains(Event.target)) {
                CloseMenu();
            }
        });

        document.addEventListener('keydown', function (Event) {
            if (Event.key === 'Escape') {
                CloseMenu();
            }
        });
    });
</script>
