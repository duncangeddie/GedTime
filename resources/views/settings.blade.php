<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="{{ $SettingsPageClass }}">
            @include('components.app-header', ['LogoPath' => $AppHeaderLogo, 'PageTitle' => $PageTitle])

            <main class="{{ $SettingsPageMainClass }}">
                <div class="{{ $SettingsPageContentClass }}">
                    @if (session('SuccessMessage'))
                        <div class="{{ $SettingsPageMessageClass }}">
                            {{ session('SuccessMessage') }}
                        </div>
                    @endif

                    @if (session('ErrorMessage'))
                        <div class="{{ $SettingsPageErrorMessageClass }}">
                            {{ session('ErrorMessage') }}
                        </div>
                    @endif

                    <div class="{{ $SettingsCardClass }}">
                        <div class="{{ $SettingsCardHeaderClass }}">
                            <h2 class="{{ $SettingsCardTitleClass }}">{{ $SettingsCardTitle }}</h2>
                            <p class="{{ $SettingsCardTextClass }}">{{ $SettingsCardText }}</p>
                        </div>

                        <form method="POST" action="{{ $SettingsFormAction }}" class="{{ $SettingsFormClass }}">
                            @csrf

                            <div class="{{ $SettingsFieldClass }}">
                                <label for="{{ $StartDateName }}" class="{{ $SettingsLabelClass }}">
                                    {{ $StartDateLabel }}
                                </label>

                                <input
                                    id="{{ $StartDateName }}"
                                    name="{{ $StartDateName }}"
                                    type="date"
                                    value="{{ $StartDateValue }}"
                                    class="{{ $SettingsInputClass }}"
                                    required
                                >

                                @error($StartDateName)
                                    <p class="{{ $SettingsErrorClass }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="{{ $SettingsActionsClass }}">
                                <button type="submit" class="{{ $SettingsSubmitButtonClass }}">
                                    {{ $SettingsSubmitButtonLabel }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            @include('components.app-footer', ['LogoPath' => $AppFooterLogo])
        </div>
    </body>
</html>
