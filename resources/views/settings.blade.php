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

                            <div class="{{ $SettingsFieldClass }}">
                                <label for="{{ $CountryName }}" class="{{ $SettingsLabelClass }}">
                                    {{ $CountryLabel }}
                                </label>

                                <select
                                    id="{{ $CountryName }}"
                                    name="{{ $CountryName }}"
                                    class="{{ $SettingsInputClass }}"
                                    required
                                >
                                    <option value="">{{ $CountryPlaceholderLabel }}</option>

                                    @foreach ($CountryOptions as $CountryOption)
                                        <option
                                            value="{{ $CountryOption['Value'] }}"
                                            @selected($CountryValue === $CountryOption['Value'])
                                        >
                                            {{ $CountryOption['Label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error($CountryName)
                                    <p class="{{ $SettingsErrorClass }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="{{ $SettingsFieldClass }}">
                                <input type="hidden" name="{{ $UseWorldClocksName }}" value="0">

                                <label for="{{ $UseWorldClocksName }}" class="{{ $SettingsCheckboxRowClass }}">
                                    <input
                                        id="{{ $UseWorldClocksName }}"
                                        name="{{ $UseWorldClocksName }}"
                                        type="checkbox"
                                        value="1"
                                        class="{{ $SettingsCheckboxClass }}"
                                        @checked($UseWorldClocksValue === 1)
                                    >

                                    <span class="{{ $SettingsLabelClass }}">
                                        {{ $UseWorldClocksLabel }}
                                    </span>
                                </label>

                                @error($UseWorldClocksName)
                                    <p class="{{ $SettingsErrorClass }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <div
                                id="{{ $WorldClockCountWrapperId }}"
                                class="{{ $SettingsFieldClass }} {{ $UseWorldClocksValue === 1 ? '' : $SettingsDisabledFieldClass }}"
                            >
                                <label for="{{ $WorldClockCountName }}" class="{{ $SettingsLabelClass }}">
                                    {{ $WorldClockCountLabel }}
                                </label>

                                <select
                                    id="{{ $WorldClockCountName }}"
                                    name="{{ $WorldClockCountName }}"
                                    class="{{ $SettingsInputClass }}"
                                    @disabled($UseWorldClocksValue !== 1)
                                >
                                    <option value="">{{ $WorldClockCountPlaceholderLabel }}</option>

                                    @foreach ($WorldClockCountOptions as $WorldClockCountOption)
                                        <option
                                            value="{{ $WorldClockCountOption['Value'] }}"
                                            @selected((string) $WorldClockCountValue === $WorldClockCountOption['Value'])
                                        >
                                            {{ $WorldClockCountOption['Label'] }}
                                        </option>
                                    @endforeach
                                </select>

                                @error($WorldClockCountName)
                                    <p class="{{ $SettingsErrorClass }}">{{ $message }}</p>
                                @enderror
                            </div>

                            @foreach ($WorldClockFields as $WorldClockField)
                                @php
                                    $WorldClockIsEnabled = $UseWorldClocksValue === 1
                                        && (int) $WorldClockCountValue >= $WorldClockField['MinimumCount'];
                                @endphp

                                <div
                                    class="{{ $SettingsFieldClass }} {{ $WorldClockIsEnabled ? '' : $SettingsDisabledFieldClass }}"
                                    data-world-clock-field="true"
                                    data-minimum-count="{{ $WorldClockField['MinimumCount'] }}"
                                >
                                    <label for="{{ $WorldClockField['Name'] }}" class="{{ $SettingsLabelClass }}">
                                        {{ $WorldClockField['Label'] }}
                                    </label>

                                    <select
                                        id="{{ $WorldClockField['Name'] }}"
                                        name="{{ $WorldClockField['Name'] }}"
                                        class="{{ $SettingsInputClass }}"
                                        @disabled(! $WorldClockIsEnabled)
                                    >
                                        <option value="">{{ $WorldClockPlaceholderLabel }}</option>

                                        @foreach ($WorldClockOptions as $WorldClockOption)
                                            <option
                                                value="{{ $WorldClockOption['Value'] }}"
                                                @selected($WorldClockField['Value'] === $WorldClockOption['Value'])
                                            >
                                                {{ $WorldClockOption['Label'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error($WorldClockField['Name'])
                                        <p class="{{ $SettingsErrorClass }}">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach

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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const UseWorldClocksCheckbox = document.getElementById(@json($UseWorldClocksName));
                const WorldClockCountWrapper = document.getElementById(@json($WorldClockCountWrapperId));
                const WorldClockCountSelect = document.getElementById(@json($WorldClockCountName));
                const WorldClockFields = document.querySelectorAll('[data-world-clock-field="true"]');

                function UpdateWorldClockDisplay() {
                    const UseWorldClocksEnabled = UseWorldClocksCheckbox.checked;
                    const SelectedWorldClockCount = parseInt(WorldClockCountSelect.value || '0', 10);

                    if (UseWorldClocksEnabled) {
                        WorldClockCountWrapper.classList.remove('opacity-50');
                        WorldClockCountSelect.disabled = false;
                    } else {
                        WorldClockCountWrapper.classList.add('opacity-50');
                        WorldClockCountSelect.disabled = true;
                    }

                    WorldClockFields.forEach(function (WorldClockField) {
                        const MinimumCount = parseInt(WorldClockField.dataset.minimumCount, 10);
                        const WorldClockSelect = WorldClockField.querySelector('select');
                        const ShouldEnableField = UseWorldClocksEnabled && SelectedWorldClockCount >= MinimumCount;

                        WorldClockSelect.disabled = !ShouldEnableField;

                        if (ShouldEnableField) {
                            WorldClockField.classList.remove('opacity-50');
                        } else {
                            WorldClockField.classList.add('opacity-50');
                        }
                    });
                }

                UseWorldClocksCheckbox.addEventListener('change', UpdateWorldClockDisplay);
                WorldClockCountSelect.addEventListener('change', UpdateWorldClockDisplay);

                UpdateWorldClockDisplay();
            });
        </script>
    </body>
</html>
