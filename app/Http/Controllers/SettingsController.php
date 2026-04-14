<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $SettingsCardText = 'Set your start date and country below.';

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

        // Field Variables
        $StartDateLabel = 'Start Date';
        $StartDateName = 'start_date';
        $CountryLabel = 'Country';
        $CountryName = 'country';
        $CountryOptions = [
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

        // Settings Variables
        $ExistingSettings = DB::table('settings')
            ->where('user_id', $Request->user()->id)
            ->orderBy('id')
            ->first(['id', 'start_date', 'country']);

        $StartDateValue = old($StartDateName, $ExistingSettings->start_date ?? '');
        $CountryValue = old($CountryName, $ExistingSettings->country ?? '');

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
            'StartDateLabel' => $StartDateLabel,
            'StartDateName' => $StartDateName,
            'StartDateValue' => $StartDateValue,
            'CountryLabel' => $CountryLabel,
            'CountryName' => $CountryName,
            'CountryValue' => $CountryValue,
            'CountryOptions' => $CountryOptions,
        ]);
    }

    public function UpdateSettings(Request $Request): RedirectResponse
    {
        // User Variables
        $User = $Request->user();

        // Validation Variables
        $ValidatedData = $Request->validate([
            'start_date' => ['required', 'date'],
            'country' => ['required', 'in:South Africa,United Kingdom,Australia'],
        ]);

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
                ]);
        } else {
            DB::table('settings')->insert([
                'user_id' => $User->id,
                'start_date' => $ValidatedData['start_date'],
                'country' => $ValidatedData['country'],
            ]);
        }

        return redirect()
            ->route('settings')
            ->with('SuccessMessage', 'Settings saved successfully.');
    }
}
