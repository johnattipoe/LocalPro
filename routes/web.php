<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\ServiceRequestController;
use App\Http\Controllers\Provider\ProviderBookingController;
use App\Http\Controllers\Provider\ProviderDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProviderApprovalController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Provider\ProviderProfileController;
use App\Http\Controllers\Provider\ProviderServiceController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Provider\ProviderVerificationController;
use App\Http\Controllers\Provider\EarningsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProviderSearchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Provider\ProviderPayoutController;
use App\Http\Controllers\Admin\PayoutManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/professionals', [ProviderSearchController::class, 'index'])->name('professionals.index');
Route::get('/services', [HomeController::class, 'services'])->name('services.index');
Route::get('/services/search', [ProviderSearchController::class, 'index'])->name('services.search');
Route::get('/providers/register', fn () => redirect()->route('register', ['role'=>'provider']))->name('providers.register');
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::post('/contact', [SupportController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::view('/how-it-works', 'pages.how-it-works')->name('how-it-works');
Route::get('/services/{category:slug}', [HomeController::class, 'category'])->name('services.show');
Route::get('/providers/{provider}', [HomeController::class, 'provider'])->name('providers.show');
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->middleware('throttle:10,1')->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->middleware('throttle:10,1')->name('google.callback');
    Route::get('/forgot-password', [PasswordResetController::class,'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class,'sendLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class,'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class,'reset'])->name('password.update');
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/requests/{serviceRequest}/messages', [MessageController::class, 'show'])->name('messages.show');
    Route::get('/requests/{serviceRequest}/messages/updates', [MessageController::class, 'updates'])->name('messages.updates');
    Route::post('/requests/{serviceRequest}/messages', [MessageController::class, 'store'])->name('messages.store');
});

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/requests/{serviceRequest}/pay', [PaymentController::class,'checkout'])->name('payments.initialize');
    Route::get('/payments/paystack/return', [PaymentController::class,'callback'])->name('payments.callback');
    Route::get('/requests', [ServiceRequestController::class, 'index'])->name('requests.index');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/requests/{serviceRequest}', [ServiceRequestController::class, 'show'])->name('requests.show');
    Route::patch('/requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('requests.cancel');
    Route::get('/request-to/{provider}', [ServiceRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [ServiceRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{serviceRequest}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/requests/{serviceRequest}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::middleware(['auth', 'provider'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [ProviderBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{serviceRequest}', [ProviderBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{serviceRequest}', [ProviderBookingController::class, 'update'])->name('bookings.update');
    Route::get('/profile', [ProviderProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProviderProfileController::class, 'update'])->name('profile.update');
    Route::get('/verification', [ProviderVerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification', [ProviderVerificationController::class, 'store'])->name('verification.store');
    Route::get('/earnings', [EarningsController::class, 'index'])->name('earnings.index');
    Route::post('/payouts', [ProviderPayoutController::class, 'store'])->name('payouts.store');
    Route::resource('/services', ProviderServiceController::class)->except(['show'])->names('services');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/providers', [ProviderApprovalController::class, 'index'])->name('providers.index');
    Route::patch('/providers/{provider}', [ProviderApprovalController::class, 'update'])->name('providers.update');
    Route::get('/verification-documents/{verification}', [ProviderApprovalController::class, 'document'])->name('verification.download');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [UserManagementController::class, 'toggle'])->name('users.toggle');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'toggle'])->name('categories.toggle');
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::patch('/reviews/{review}', [ReportController::class, 'toggleReview'])->name('reviews.toggle');
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'index'])->name('payments.index');
    Route::get('/payouts', [PayoutManagementController::class, 'index'])->name('payouts.index');
    Route::patch('/payouts/{payout}', [PayoutManagementController::class, 'update'])->name('payouts.update');
    Route::get('/support', [SupportTicketController::class, 'index'])->name('support.index');
    Route::patch('/support/{ticket}', [SupportTicketController::class, 'update'])->name('support.update');
});
