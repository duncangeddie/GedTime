<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $PageTitle }}</title>

        @vite(['resources/css/guest.css', 'resources/js/app.js'])
    </head>
    <body>
        @php
            $ComponentController = app(\App\Http\Controllers\ComponentController::class);
            $GuestFooterLogo = $ComponentController->GuestFooterLogo();
        @endphp

        <div class="{{ $RegisterPageClass }}">
            @include('components.guest-header', ['LogoPath' => $WelcomeHeaderLogo])

            <main class="{{ $RegisterPageMainClass }}">
                <div class="{{ $RegisterCardClass }}">
                    <div class="RegisterCardHeader">
                        <h1 class="RegisterHeading">{{ $RegisterHeading }}</h1>
                        <p class="RegisterSubheading">{{ $RegisterSubheading }}</p>
                    </div>

                    <form method="{{ $FormMethod }}" action="{{ $FormAction }}" class="RegisterForm">
                        @csrf

                        <div class="RegisterField">
                            <label for="{{ $NameName }}" class="RegisterLabel">
                                {{ $NameLabel }}
                            </label>

                            <input
                                id="{{ $NameName }}"
                                name="{{ $NameName }}"
                                type="{{ $NameType }}"
                                value="{{ old($NameName) }}"
                                placeholder="{{ $NamePlaceholder }}"
                                class="RegisterInput"
                                required
                                autofocus
                            >

                            @error($NameName)
                                <p class="RegisterError">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="RegisterField">
                            <label for="{{ $EmailName }}" class="RegisterLabel">
                                {{ $EmailLabel }}
                            </label>

                            <input
                                id="{{ $EmailName }}"
                                name="{{ $EmailName }}"
                                type="{{ $EmailType }}"
                                value="{{ old($EmailName) }}"
                                placeholder="{{ $EmailPlaceholder }}"
                                class="RegisterInput"
                                required
                            >

                            @error($EmailName)
                                <p class="RegisterError">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="RegisterField">
                            <label for="{{ $PasswordName }}" class="RegisterLabel">
                                {{ $PasswordLabel }}
                            </label>

                            <input
                                id="{{ $PasswordName }}"
                                name="{{ $PasswordName }}"
                                type="{{ $PasswordType }}"
                                placeholder="{{ $PasswordPlaceholder }}"
                                class="RegisterInput"
                                required
                            >

                            @error($PasswordName)
                                <p class="RegisterError">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="RegisterField">
                            <label for="{{ $PasswordConfirmationName }}" class="RegisterLabel">
                                {{ $PasswordConfirmationLabel }}
                            </label>

                            <input
                                id="{{ $PasswordConfirmationName }}"
                                name="{{ $PasswordConfirmationName }}"
                                type="{{ $PasswordConfirmationType }}"
                                placeholder="{{ $PasswordConfirmationPlaceholder }}"
                                class="RegisterInput"
                                required
                            >
                        </div>

                        <button type="submit" class="RegisterButton">
                            {{ $SubmitLabel }}
                        </button>
                    </form>
                </div>
            </main>

            @include('components.guest-footer', ['LogoPath' => $GuestFooterLogo])
        </div>
    </body>
</html>
