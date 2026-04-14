<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function ViewSettings(Request $Request): View
    {
        // Component Variables
        $ComponentController = app(ComponentController::class);
        $AppHeaderLogo = $ComponentController->AppHeaderLogo();
        $AppFooterLogo = $ComponentController->AppFooterLogo();

        // Page Variables
        $PageTitle = 'Settings';
        $SettingsPageClass = 'SettingsPage';
        $SettingsPageMainClass = 'SettingsPageMain';
        $SettingsPageContentClass = 'SettingsPageContent';
        $SettingsPageMessageClass = 'SettingsPageMessage';
        $SettingsPageErrorMessageClass = 'SettingsPageErrorMessage';

        // Card Variables
        $SettingsCardClass = 'SettingsCard';
        $SettingsCardHeaderClass = 'SettingsCardHeader';
        $SettingsCardTitleClass = 'SettingsCardTitle';
        $SettingsCardTextClass = 'SettingsCardText';
        $SettingsCardTitle = 'Settings';
        $SettingsCardText = 'Set your start date, country, and world clock preferences below.';

        // Form Variables
        $SettingsFormClass = 'SettingsForm';
        $SettingsFieldClass = 'SettingsField';
        $SettingsLabelClass = 'SettingsLabel';
        $SettingsInputClass = 'SettingsInput';
        $SettingsErrorClass = 'SettingsError';
        $SettingsActionsClass = 'SettingsActions';
        $SettingsSubmitButtonClass = 'SettingsSubmitButton';
        $SettingsFormAction = route('settings.update');
        $SettingsSubmitButtonLabel = 'Save Settings';

        // Toggle And Display Variables
        $SettingsHiddenClass = 'hidden';
        $SettingsDisabledFieldClass = 'opacity-50';
        $SettingsCheckboxRowClass = 'flex items-center gap-3';
        $SettingsCheckboxClass = 'h-4 w-4 rounded border-gray-300 text-black focus:ring-black';

        // Start Date Field Variables
        $StartDateLabel = 'Start Date';
        $StartDateName = 'start_date';

        // Country Field Variables
        $CountryLabel = 'Country';
        $CountryName = 'country';
        $CountryPlaceholderLabel = 'Select Country';
        $CountryOptions = $this->CountryOptions();

        // World Clock Toggle Variables
        $UseWorldClocksLabel = 'Use World Clocks ?';
        $UseWorldClocksName = 'use_world_clocks';

        // World Clock Count Variables
        $WorldClockCountLabel = 'Number Of World Clocks';
        $WorldClockCountName = 'world_clock_count';
        $WorldClockCountWrapperId = 'WorldClockCountWrapper';
        $WorldClockCountPlaceholderLabel = 'Select Amount';
        $WorldClockCountOptions = $this->WorldClockCountOptions();

        // World Clock Option Variables
        $WorldClockPlaceholderLabel = 'Select Location';
        $WorldClockOptions = $this->WorldClockOptions();

        // Settings Variables
        $ExistingSettings = DB::table('settings')
            ->where('user_id', $Request->user()->id)
            ->orderBy('id')
            ->first([
                'id',
                'start_date',
                'country',
                'use_world_clocks',
                'world_clock_count',
                'world_clock_one',
                'world_clock_two',
                'world_clock_three',
            ]);

        $StartDateValue = old($StartDateName, $ExistingSettings->start_date ?? '');
        $CountryValue = old($CountryName, $ExistingSettings->country ?? '');
        $UseWorldClocksValue = (int) old($UseWorldClocksName, $ExistingSettings->use_world_clocks ?? 0);
        $WorldClockCountValue = old($WorldClockCountName, $ExistingSettings->world_clock_count ?? '');

        // World Clock Field Variables
        $WorldClockFields = [
            [
                'Label' => 'World Clock 1',
                'Name' => 'world_clock_one',
                'Value' => old('world_clock_one', $ExistingSettings->world_clock_one ?? ''),
                'MinimumCount' => 1,
            ],
            [
                'Label' => 'World Clock 2',
                'Name' => 'world_clock_two',
                'Value' => old('world_clock_two', $ExistingSettings->world_clock_two ?? ''),
                'MinimumCount' => 2,
            ],
            [
                'Label' => 'World Clock 3',
                'Name' => 'world_clock_three',
                'Value' => old('world_clock_three', $ExistingSettings->world_clock_three ?? ''),
                'MinimumCount' => 3,
            ],
        ];

        return view('settings', [
            'PageTitle' => $PageTitle,
            'AppHeaderLogo' => $AppHeaderLogo,
            'AppFooterLogo' => $AppFooterLogo,
            'SettingsPageClass' => $SettingsPageClass,
            'SettingsPageMainClass' => $SettingsPageMainClass,
            'SettingsPageContentClass' => $SettingsPageContentClass,
            'SettingsPageMessageClass' => $SettingsPageMessageClass,
            'SettingsPageErrorMessageClass' => $SettingsPageErrorMessageClass,
            'SettingsCardClass' => $SettingsCardClass,
            'SettingsCardHeaderClass' => $SettingsCardHeaderClass,
            'SettingsCardTitleClass' => $SettingsCardTitleClass,
            'SettingsCardTextClass' => $SettingsCardTextClass,
            'SettingsCardTitle' => $SettingsCardTitle,
            'SettingsCardText' => $SettingsCardText,
            'SettingsFormClass' => $SettingsFormClass,
            'SettingsFieldClass' => $SettingsFieldClass,
            'SettingsLabelClass' => $SettingsLabelClass,
            'SettingsInputClass' => $SettingsInputClass,
            'SettingsErrorClass' => $SettingsErrorClass,
            'SettingsActionsClass' => $SettingsActionsClass,
            'SettingsSubmitButtonClass' => $SettingsSubmitButtonClass,
            'SettingsFormAction' => $SettingsFormAction,
            'SettingsSubmitButtonLabel' => $SettingsSubmitButtonLabel,
            'SettingsHiddenClass' => $SettingsHiddenClass,
            'SettingsDisabledFieldClass' => $SettingsDisabledFieldClass,
            'SettingsCheckboxRowClass' => $SettingsCheckboxRowClass,
            'SettingsCheckboxClass' => $SettingsCheckboxClass,
            'StartDateLabel' => $StartDateLabel,
            'StartDateName' => $StartDateName,
            'StartDateValue' => $StartDateValue,
            'CountryLabel' => $CountryLabel,
            'CountryName' => $CountryName,
            'CountryValue' => $CountryValue,
            'CountryOptions' => $CountryOptions,
            'CountryPlaceholderLabel' => $CountryPlaceholderLabel,
            'UseWorldClocksLabel' => $UseWorldClocksLabel,
            'UseWorldClocksName' => $UseWorldClocksName,
            'UseWorldClocksValue' => $UseWorldClocksValue,
            'WorldClockCountLabel' => $WorldClockCountLabel,
            'WorldClockCountName' => $WorldClockCountName,
            'WorldClockCountValue' => $WorldClockCountValue,
            'WorldClockCountWrapperId' => $WorldClockCountWrapperId,
            'WorldClockCountPlaceholderLabel' => $WorldClockCountPlaceholderLabel,
            'WorldClockCountOptions' => $WorldClockCountOptions,
            'WorldClockPlaceholderLabel' => $WorldClockPlaceholderLabel,
            'WorldClockOptions' => $WorldClockOptions,
            'WorldClockFields' => $WorldClockFields,
        ]);
    }

    public function UpdateSettings(Request $Request): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Option Variables
        $CountryOptionValues = collect($this->CountryOptions())
            ->pluck('Value')
            ->implode(',');

        $WorldClockOptionValues = collect($this->WorldClockOptions())
            ->pluck('Value')
            ->implode(',');

        $WorldClockCountOptionValues = collect($this->WorldClockCountOptions())
            ->pluck('Value')
            ->implode(',');

        // Validation Variables
        $Validator = Validator::make($Request->all(), [
            'start_date' => ['required', 'date'],
            'country' => ['required', 'in:' . $CountryOptionValues],
            'use_world_clocks' => ['required', 'boolean'],
            'world_clock_count' => ['nullable', 'in:' . $WorldClockCountOptionValues],
            'world_clock_one' => ['nullable', 'in:' . $WorldClockOptionValues],
            'world_clock_two' => ['nullable', 'in:' . $WorldClockOptionValues],
            'world_clock_three' => ['nullable', 'in:' . $WorldClockOptionValues],
        ]);

        $Validator->after(function ($Validator) use ($Request) {
            $UseWorldClocksValue = (int) $Request->input('use_world_clocks', 0);
            $WorldClockCountValue = (int) $Request->input('world_clock_count', 0);

            if ($UseWorldClocksValue !== 1) {
                return;
            }

            if (! in_array((string) $Request->input('world_clock_count'), ['1', '2', '3'], true)) {
                $Validator->errors()->add('world_clock_count', 'Please select how many world clocks you want.');
                return;
            }

            $SelectedWorldClocks = [];

            if ($WorldClockCountValue >= 1) {
                if (! $Request->filled('world_clock_one')) {
                    $Validator->errors()->add('world_clock_one', 'Please select World Clock 1.');
                } else {
                    $SelectedWorldClocks[] = $Request->input('world_clock_one');
                }
            }

            if ($WorldClockCountValue >= 2) {
                if (! $Request->filled('world_clock_two')) {
                    $Validator->errors()->add('world_clock_two', 'Please select World Clock 2.');
                } else {
                    $SelectedWorldClocks[] = $Request->input('world_clock_two');
                }
            }

            if ($WorldClockCountValue >= 3) {
                if (! $Request->filled('world_clock_three')) {
                    $Validator->errors()->add('world_clock_three', 'Please select World Clock 3.');
                } else {
                    $SelectedWorldClocks[] = $Request->input('world_clock_three');
                }
            }

            if (count($SelectedWorldClocks) !== count(array_unique($SelectedWorldClocks))) {
                $Validator->errors()->add('world_clock_one', 'Please select different world clock locations.');
            }
        });

        $ValidatedData = $Validator->validate();

        // World Clock Storage Variables
        $UseWorldClocksValue = (int) ($ValidatedData['use_world_clocks'] ?? 0);
        $WorldClockCountValue = $UseWorldClocksValue === 1
            ? (int) ($ValidatedData['world_clock_count'] ?? 0)
            : null;

        $WorldClockOneValue = $UseWorldClocksValue === 1 && $WorldClockCountValue >= 1
            ? ($ValidatedData['world_clock_one'] ?? null)
            : null;

        $WorldClockTwoValue = $UseWorldClocksValue === 1 && $WorldClockCountValue >= 2
            ? ($ValidatedData['world_clock_two'] ?? null)
            : null;

        $WorldClockThreeValue = $UseWorldClocksValue === 1 && $WorldClockCountValue >= 3
            ? ($ValidatedData['world_clock_three'] ?? null)
            : null;

        // Settings Variables
        $ExistingSettings = DB::table('settings')
            ->where('user_id', $User->id)
            ->orderBy('id')
            ->first(['id']);

        if ($ExistingSettings) {
            DB::table('settings')
                ->where('id', $ExistingSettings->id)
                ->update([
                    'start_date' => $ValidatedData['start_date'],
                    'country' => $ValidatedData['country'],
                    'use_world_clocks' => $UseWorldClocksValue,
                    'world_clock_count' => $WorldClockCountValue,
                    'world_clock_one' => $WorldClockOneValue,
                    'world_clock_two' => $WorldClockTwoValue,
                    'world_clock_three' => $WorldClockThreeValue,
                ]);
        } else {
            DB::table('settings')->insert([
                'user_id' => $User->id,
                'start_date' => $ValidatedData['start_date'],
                'country' => $ValidatedData['country'],
                'use_world_clocks' => $UseWorldClocksValue,
                'world_clock_count' => $WorldClockCountValue,
                'world_clock_one' => $WorldClockOneValue,
                'world_clock_two' => $WorldClockTwoValue,
                'world_clock_three' => $WorldClockThreeValue,
            ]);
        }

        return redirect()
            ->route('settings')
            ->with('SuccessMessage', 'Settings saved successfully.');
    }

    public function CountryOptions(): array
    {
        return [
            [
                'Value' => 'South Africa',
                'Label' => '🇿🇦 South Africa',
            ],
            [
                'Value' => 'United Kingdom',
                'Label' => '🇬🇧 United Kingdom',
            ],
            [
                'Value' => 'Australia',
                'Label' => '🇦🇺 Australia',
            ],
        ];
    }

    public function WorldClockCountOptions(): array
    {
        return [
            [
                'Value' => '1',
                'Label' => '1',
            ],
            [
                'Value' => '2',
                'Label' => '2',
            ],
            [
                'Value' => '3',
                'Label' => '3',
            ],
        ];
    }

    public function WorldClockOptions(): array
    {
        return [
            [
                'Value' => 'Africa/Johannesburg',
                'Label' => '🇿🇦 Johannesburg',
            ],
            [
                'Value' => 'Europe/London',
                'Label' => '🇬🇧 London',
            ],
            [
                'Value' => 'Europe/Paris',
                'Label' => '🇫🇷 Paris',
            ],
            [
                'Value' => 'Europe/Berlin',
                'Label' => '🇩🇪 Berlin',
            ],
            [
                'Value' => 'America/New_York',
                'Label' => '🇺🇸 New York',
            ],
            [
                'Value' => 'America/Los_Angeles',
                'Label' => '🇺🇸 Los Angeles',
            ],
            [
                'Value' => 'America/Toronto',
                'Label' => '🇨🇦 Toronto',
            ],
            [
                'Value' => 'Asia/Dubai',
                'Label' => '🇦🇪 Dubai',
            ],
            [
                'Value' => 'Asia/Tokyo',
                'Label' => '🇯🇵 Tokyo',
            ],
            [
                'Value' => 'Asia/Singapore',
                'Label' => '🇸🇬 Singapore',
            ],
            [
                'Value' => 'Australia/Sydney',
                'Label' => '🇦🇺 Sydney',
            ],
            [
                'Value' => 'Pacific/Auckland',
                'Label' => '🇳🇿 Auckland',
            ],
        ];
    }
}
