<?php

use App\Livewire\AboutDeveloper;
use App\Livewire\UserProfile;
use App\Livewire\AssistanceIndex;
use App\Livewire\Auth\Login;
use App\Livewire\ContactIndex;
use App\Livewire\DashboardComponent;
use App\Livewire\MedicalIndex;
use App\Livewire\MembershipIndex;
use App\Livewire\OfficialMailIndex;
use App\Livewire\PatientForm;
use App\Livewire\PatientIndex;
use App\Livewire\PatientShow;
use App\Livewire\ReportIndex;
use App\Livewire\UserIndex;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::match(['get', 'post'], '/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
    Route::get('/patients', PatientIndex::class)->name('patients.index');
    Route::get('/patients/create', PatientForm::class)->name('patients.create');
    Route::get('/patients/{patient}', PatientShow::class)->name('patients.show');
    Route::get('/patients/{patient}/edit', PatientForm::class)->name('patients.edit');
    Route::get('/assistances', AssistanceIndex::class)->name('assistances.index');
    Route::get('/memberships', MembershipIndex::class)->name('memberships.index');
    Route::get('/contacts', ContactIndex::class)->name('contacts.index');
    Route::get('/medical', MedicalIndex::class)->name('medical.index');
    Route::get('/mails', OfficialMailIndex::class)->name('mails.index');
    Route::get('/reports', ReportIndex::class)->name('reports.index');
    Route::get('/about-dev', AboutDeveloper::class)->name('about.developer');
    Route::get('/profile', UserProfile::class)->name('profile');

    // Printable Views
    Route::get('/patients/{patient}/id-card', function (Patient $patient) {
        return view('print.id-card', compact('patient'));
    })->name('patients.id-card');

    Route::get('/patients/{patient}/support-letter', function (Patient $patient) {
        return view('print.support-letter', compact('patient'));
    })->name('patients.support-letter');

    Route::get('/patients/{patient}/summary-report', function (Patient $patient) {
        $patient->load(['assistances', 'medicalLogs', 'documents', 'contacts', 'membershipPayments']);
        return view('print.summary-report', compact('patient'));
    })->name('patients.summary-report');

    // Admin Only
    Route::get('/users', UserIndex::class)->name('users.index');
});
