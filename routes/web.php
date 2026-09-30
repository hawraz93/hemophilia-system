<?php

use App\Http\Controllers\SecureFileController;
use App\Livewire\AboutDeveloper;
use App\Livewire\ActivityIndex;
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
use App\Livewire\UserProfile;
use App\Models\Patient;
use Illuminate\Http\Request;
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
    Route::get('/patients/create', PatientForm::class)->name('patients.create')->middleware('can:edit-records');
    Route::get('/patients/{patient}', PatientShow::class)->name('patients.show');
    Route::get('/patients/{patient}/edit', PatientForm::class)->name('patients.edit')->middleware('can:edit-records');
    Route::get('/activities', ActivityIndex::class)->name('activities.index');
    Route::get('/assistances', AssistanceIndex::class)->name('assistances.index');
    Route::get('/memberships', MembershipIndex::class)->name('memberships.index');
    Route::get('/contacts', ContactIndex::class)->name('contacts.index');
    Route::get('/medical', MedicalIndex::class)->name('medical.index');
    Route::get('/mails', OfficialMailIndex::class)->name('mails.index');
    Route::get('/reports', ReportIndex::class)->name('reports.index');
    Route::get('/about-dev', AboutDeveloper::class)->name('about.developer');
    Route::get('/profile', UserProfile::class)->name('profile');

    // Secure file endpoints (files live on the private disk)
    Route::controller(SecureFileController::class)->group(function () {
        Route::get('/patient-documents/{document}/view', 'viewDocument')->name('patient-documents.view');
        Route::get('/patient-documents/{document}/download', 'downloadDocument')->name('patient-documents.download');
        Route::get('/patient-documents/{document}/print', 'printDocument')->name('patient-documents.print');
        Route::get('/mails/{mail}/file', 'viewMail')->name('mails.file');
    });

    // Printable Views
    Route::get('/patients/{patient}/id-card', function (Patient $patient) {
        return view('print.id-card', compact('patient'));
    })->name('patients.id-card');

    Route::get('/patients/{patient}/support-letter', function (Patient $patient, Request $request) {
        $recipient = $request->query('recipient', 'سەرجەم لایەنە پەیوەندیدارەکان');
        $subject = $request->query('subject', 'نوسراوی پشتگیری');
        return view('print.support-letter', compact('patient', 'recipient', 'subject'));
    })->name('patients.support-letter');

    Route::get('/patients/{patient}/summary-report', function (Patient $patient) {
        $patient->load(['assistances', 'medicalLogs', 'documents', 'contacts', 'membershipPayments']);
        return view('print.summary-report', compact('patient'));
    })->name('patients.summary-report');

    // Admin Only
    Route::get('/users', UserIndex::class)->name('users.index')->middleware('can:manage-users');
});
