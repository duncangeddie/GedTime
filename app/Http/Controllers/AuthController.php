<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function ShowLogin(): View
    {
        // Header Variables
        $ComponentController = app(ComponentController::class);
        $WelcomeHeaderLogo = $ComponentController->WelcomeHeaderLogo();

        // Page Variables
        $PageTitle = 'Login';
        $LoginPageClass = 'LoginPage';
        $LoginPageMainClass = 'LoginPageMain';
        $LoginCardClass = 'LoginCard';
        $LoginHeading = 'Welcome Back';
        $LoginSubheading = 'Log in to your GedTime account below.';

        // Form Variables
        $FormAction = route('login.authenticate');
        $FormMethod = 'POST';

        $EmailLabel = 'Email';
        $EmailName = 'email';
        $EmailType = 'email';
        $EmailPlaceholder = 'Enter your email address';

        $PasswordLabel = 'Password';
        $PasswordName = 'password';
        $PasswordType = 'password';
        $PasswordPlaceholder = 'Enter your password';

        $SubmitLabel = 'Login';

        return view('auth.login', [
            'PageTitle' => $PageTitle,
            'WelcomeHeaderLogo' => $WelcomeHeaderLogo,
            'LoginPageClass' => $LoginPageClass,
            'LoginPageMainClass' => $LoginPageMainClass,
            'LoginCardClass' => $LoginCardClass,
            'LoginHeading' => $LoginHeading,
            'LoginSubheading' => $LoginSubheading,
            'FormAction' => $FormAction,
            'FormMethod' => $FormMethod,
            'EmailLabel' => $EmailLabel,
            'EmailName' => $EmailName,
            'EmailType' => $EmailType,
            'EmailPlaceholder' => $EmailPlaceholder,
            'PasswordLabel' => $PasswordLabel,
            'PasswordName' => $PasswordName,
            'PasswordType' => $PasswordType,
            'PasswordPlaceholder' => $PasswordPlaceholder,
            'SubmitLabel' => $SubmitLabel,
        ]);
    }

    public function Login(Request $Request): RedirectResponse
    {
        // Validation Variables
        $Credentials = $Request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($Credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $Request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function ShowRegister(): View
    {
        // Header Variables
        $ComponentController = app(ComponentController::class);
        $WelcomeHeaderLogo = $ComponentController->WelcomeHeaderLogo();

        // Page Variables
        $PageTitle = 'Register';
        $RegisterPageClass = 'RegisterPage';
        $RegisterPageMainClass = 'RegisterPageMain';
        $RegisterCardClass = 'RegisterCard';
        $RegisterHeading = 'Create Account';
        $RegisterSubheading = 'Register a new GedTime account below.';

        // Form Variables
        $FormAction = route('register.store');
        $FormMethod = 'POST';

        $NameLabel = 'Name';
        $NameName = 'name';
        $NameType = 'text';
        $NamePlaceholder = 'Enter your name';

        $EmailLabel = 'Email';
        $EmailName = 'email';
        $EmailType = 'email';
        $EmailPlaceholder = 'Enter your email address';

        $PasswordLabel = 'Password';
        $PasswordName = 'password';
        $PasswordType = 'password';
        $PasswordPlaceholder = 'Enter your password';

        $PasswordConfirmationLabel = 'Confirm Password';
        $PasswordConfirmationName = 'password_confirmation';
        $PasswordConfirmationType = 'password';
        $PasswordConfirmationPlaceholder = 'Confirm your password';

        $SubmitLabel = 'Register';

        return view('auth.register', [
            'PageTitle' => $PageTitle,
            'WelcomeHeaderLogo' => $WelcomeHeaderLogo,
            'RegisterPageClass' => $RegisterPageClass,
            'RegisterPageMainClass' => $RegisterPageMainClass,
            'RegisterCardClass' => $RegisterCardClass,
            'RegisterHeading' => $RegisterHeading,
            'RegisterSubheading' => $RegisterSubheading,
            'FormAction' => $FormAction,
            'FormMethod' => $FormMethod,
            'NameLabel' => $NameLabel,
            'NameName' => $NameName,
            'NameType' => $NameType,
            'NamePlaceholder' => $NamePlaceholder,
            'EmailLabel' => $EmailLabel,
            'EmailName' => $EmailName,
            'EmailType' => $EmailType,
            'EmailPlaceholder' => $EmailPlaceholder,
            'PasswordLabel' => $PasswordLabel,
            'PasswordName' => $PasswordName,
            'PasswordType' => $PasswordType,
            'PasswordPlaceholder' => $PasswordPlaceholder,
            'PasswordConfirmationLabel' => $PasswordConfirmationLabel,
            'PasswordConfirmationName' => $PasswordConfirmationName,
            'PasswordConfirmationType' => $PasswordConfirmationType,
            'PasswordConfirmationPlaceholder' => $PasswordConfirmationPlaceholder,
            'SubmitLabel' => $SubmitLabel,
        ]);
    }

    public function Register(Request $Request): RedirectResponse
    {
        $ValidatedData = $Request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // User Variables
        $User = User::create([
            'name' => $ValidatedData['name'],
            'email' => $ValidatedData['email'],
            'password' => Hash::make($ValidatedData['password']),
        ]);

        Auth::login($User);
        $Request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function Logout(Request $Request): RedirectResponse
    {
        Auth::logout();

        $Request->session()->invalidate();
        $Request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
