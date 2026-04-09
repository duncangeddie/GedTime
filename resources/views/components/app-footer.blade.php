<footer class="AppFooter">
    <div class="AppFooterContainer px-6 lg:px-8">
        <div class="AppFooterContent">
            <div class="AppFooterBrand">
                <a href="{{ url('/dashboard') }}">
                    <img
                        src="{{ $LogoPath }}"
                        alt="GedTime Logo"
                        class="AppFooterLogo"
                    >
                </a>
            </div>

            <div class="AppFooterActions">
                <form method="POST" action="{{ route('logout') }}" class="AppFooterForm">
                    @csrf

                    <button type="submit" class="AppFooterButton">
                        Logout
                    </button>
                </form>
            </div>

            <div class="AppFooterSpacer"></div>
        </div>
    </div>
</footer>
