<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'Login'])->name('login.authenticate');

    Route::get('/register', [AuthController::class, 'ShowRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'Register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/projects', [ProjectsController::class, 'ViewProjects'])->name('projects');
    Route::post('/projects/add', [ProjectsController::class, 'AddProject'])->name('projects.add');
    Route::post('/projects/{ProjectId}/edit', [ProjectsController::class, 'EditProject'])->name('projects.edit');
    Route::post('/projects/{ProjectId}/delete', [ProjectsController::class, 'DeleteProject'])->name('projects.delete');

    Route::get('/categories', [CategoriesController::class, 'ViewCategories'])->name('categories');
    Route::post('/categories/add', [CategoriesController::class, 'AddCategory'])->name('categories.add');
    Route::post('/categories/{CategoryId}/delete', [CategoriesController::class, 'DeleteCategory'])->name('categories.delete');

    Route::get('/timesheet', [TimesheetController::class, 'ViewTimesheet'])->name('timesheet');
    Route::post('/timesheet/add', [TimesheetController::class, 'AddTimesheetEntry'])->name('timesheet.add');
    Route::post('/timesheet/{TimesheetId}/edit', [TimesheetController::class, 'EditTimesheetEntry'])->name('timesheet.edit');
    Route::post('/timesheet/{TimesheetId}/delete', [TimesheetController::class, 'DeleteTimesheetEntry'])->name('timesheet.delete');

    Route::get('/reports', [ReportsController::class, 'ViewReports'])->name('reports');

    Route::get('/settings', [SettingsController::class, 'ViewSettings'])->name('settings');
    Route::post('/settings/update', [SettingsController::class, 'UpdateSettings'])->name('settings.update');

    Route::post('/logout', [AuthController::class, 'Logout'])->name('logout');
});
