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

        <div class="{{ $LoginPageClass }}">
            @include('components.guest-header', ['LogoPath' => $WelcomeHeaderLogo])

            <main class="{{ $LoginPageMainClass }}">
                <div class="{{ $LoginCardClass }}">
                    <div class="LoginCardHeader">
                        <h1 class="LoginHeading">{{ $LoginHeading }}</h1>
                        <p class="LoginSubheading">{{ $LoginSubheading }}</p>
                    </div>

                    <form method="{{ $FormMethod }}" action="{{ $FormAction }}" class="LoginForm">
                        @csrf

                        <div class="LoginField">
                            <label for="{{ $EmailName }}" class="LoginLabel">
                                {{ $EmailLabel }}
                            </label>

                            <input
                                id="{{ $EmailName }}"
                                name="{{ $EmailName }}"
                                type="{{ $EmailType }}"
                                value="{{ old($EmailName) }}"
                                placeholder="{{ $EmailPlaceholder }}"
                                class="LoginInput"
                                required
                                autofocus
                            >

                            @error($EmailName)
                                <p class="LoginError">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="LoginField">
                            <label for="{{ $PasswordName }}" class="LoginLabel">
                                {{ $PasswordLabel }}
                            </label>

                            <input
                                id="{{ $PasswordName }}"
                                name="{{ $PasswordName }}"
                                type="{{ $PasswordType }}"
                                placeholder="{{ $PasswordPlaceholder }}"
                                class="LoginInput"
                                required
                            >

                            @error($PasswordName)
                                <p class="LoginError">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="LoginButton">
                            {{ $SubmitLabel }}
                        </button>
                    </form>
                </div>
            </main>

            @include('components.guest-footer', ['LogoPath' => $GuestFooterLogo])
        </div>
    </body>
</html>
