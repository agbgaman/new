<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Admin\StudioDashboardController;
use App\Http\Controllers\Admin\VoiceoverStudioResultController;
use App\Http\Controllers\Admin\TranscribeStudioResultController;
use App\Http\Controllers\Admin\VoiceCustomizationController;
use App\Http\Controllers\Admin\LanguageCustomizationController;
use App\Http\Controllers\Admin\SoundStudioSettingsController;
use App\Http\Controllers\Admin\VoiceoverStudioSettingsController;
use App\Http\Controllers\Admin\TranscribeStudioSettingsController;
use App\Http\Controllers\Admin\LiveTrancriptionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\FinanceSubscriptionPlanController;
use App\Http\Controllers\Admin\FinancePrepaidPlanController;
use App\Http\Controllers\Admin\ReferralSystemController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\FinanceSettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\InstallController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\ImagesFolderController;
use App\Http\Controllers\Admin\ImagesController;
use App\Http\Controllers\Adnin\CSVController;
use App\Http\Controllers\Admin\TextController;
use App\Http\Controllers\Admin\Frontend\AppearanceController;
use App\Http\Controllers\Admin\Frontend\FrontendController;
use App\Http\Controllers\Admin\Frontend\BlogController;
use App\Http\Controllers\Admin\Frontend\PageController;
use App\Http\Controllers\Admin\Frontend\UseCaseController;
use App\Http\Controllers\Admin\Frontend\FAQController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\Frontend\ReviewController;
use App\Http\Controllers\Admin\Settings\GlobalController;
use App\Http\Controllers\Admin\Settings\BackupController;
use App\Http\Controllers\Admin\Settings\OAuthController;
use App\Http\Controllers\Admin\Settings\ActivationController;
use App\Http\Controllers\Admin\PriceController;
use App\Http\Controllers\Admin\Settings\SMTPController;
use App\Http\Controllers\Admin\Settings\RegistrationController;
use App\Http\Controllers\Admin\Settings\UpgradeController;
use App\Http\Controllers\Admin\Webhooks\PaypalWebhookController;
use App\Http\Controllers\Admin\Webhooks\StripeWebhookController;
use App\Http\Controllers\Admin\Webhooks\PaystackWebhookController;
use App\Http\Controllers\Admin\Webhooks\RazorpayWebhookController;
use App\Http\Controllers\Admin\Webhooks\MollieWebhookController;
use \App\Http\Controllers\Admin\COCOController;
use App\Http\Controllers\Admin\Webhooks\CoinbaseWebhookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\UserDashboardController;
use \App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\UserPasswordController;
use App\Http\Controllers\User\PurchaseHistoryController;
use App\Http\Controllers\User\PricingPlanController;
use App\Http\Controllers\QualityAssuranceController;
use App\Http\Controllers\QualityAssuranceTextController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\PhoneNumberVerificationController;
use App\Http\Controllers\User\UserSupportController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\STT\TranscribeStudioController;
use App\Http\Controllers\User\STT\TranscribeResultController;
use App\Http\Controllers\User\STT\TranscribeProjectController;
use App\Http\Controllers\User\TTS\VoiceoverStudioController;
use App\Http\Controllers\MailingSystemController;
use App\Http\Controllers\User\TTS\VoiceoverResultController;
use App\Http\Controllers\User\TTS\VoiceoverProjectController;
use App\Http\Controllers\User\TTS\SoundStudioController;
use App\Http\Controllers\User\TTS\VoiceController;
use \App\Http\Controllers\Admin\ProjectInstructionController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Middleware\CheckProfileCompletion;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now reate something great!
|
*/

// AUTH ROUTES
Route::middleware(['middleware' => 'PreventBackHistory'])->group(function () {
    require __DIR__ . '/auth.php';
});

Route::controller(LiveTrancriptionController::class)->group(function () {
    Route::get('/live/transcription/results/download-all-audio', 'liveTranscriptionResultsDownloadAllAudio')->name('admin.liveTranscription.liveTranscriptionResultsDownloadAllAudio');
});
Route::controller(MailingSystemController::class)->group(function () {
    Route::get('/mailing-system-campaign-unsubscribe/{email}/{campaign}', 'unSubscribe')->name('admin.mailing.system.campaign.unSubscribe');
    Route::get('/track-open', 'trackOpen')->name('track-open');
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
// FRONTEND ROUTES
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::post('/', 'listen')->name('tts.voiceover');
    Route::get('/blog/{slug}', 'blogShow')->name('blogs.show');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/gts', 'gts')->name('gts');
    Route::get('/legal', 'legal')->name('legal');
//    Route::get('/admin-login', 'adminLogin')->name('adminLogin');
    Route::get('/service', 'service')->name('service');
    Route::get('/contact', 'contactPage')->name('contact');
    Route::get('/contact', 'contactPage')->name('contact');
    Route::post('/contact', 'contact')->name('contact');
    Route::get('/terms-and-conditions', 'termsAndConditions')->name('terms');
//    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy');
    Route::get('/anti-corruption-policy', 'antiCorruptionPolicy')->name('antiCorruptionPolicy');
    Route::get('/cookie-policy', 'cookiePolicy')->name('cookiePolicy');
    Route::get('/global-ethical-sourcing-modern-slavery-policy', 'slaveryPolicy')->name('slaveryPolicy');
    Route::get('/group-whistleblower', 'groupWhistleblower')->name('groupWhistleBlower');
    Route::get('/privacy-policy', 'privacyPolicies')->name('privacyPolicies');
    Route::get('/privacy-statement', 'privacyStatement')->name('privacyStatement');
    Route::get('/engine-relevance', 'engineRelevance')->name('engineRelevance');
    Route::get('/surveys', 'surveys')->name('surveys');
    Route::get('/transcription', 'transcription')->name('transcription');
    Route::get('/translations', 'translations')->name('translations');
});


// INSTAL ROUTES
Route::group(['prefix' => 'install', 'middleware' => 'install'], function () {
    Route::controller(InstallController::class)->group(function () {
        Route::get('/', 'index')->name('install');
        Route::get('/requirements', 'requirements')->name('install.requirements');
        Route::get('/permissions', 'permissions')->name('install.permissions');
        Route::get('/database', 'database')->name('install.database');
        Route::post('/database', 'storeDatabaseCredentials')->name('install.database.store');
        Route::get('/activation', 'activation')->name('install.activation');
        Route::post('/activation', 'activateApplication')->name('install.activation.activate');
    });
});

// PAYMENT GATEWAY WEBHOOKS ROUTES
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handleStripe'])->name('stripe.webhook');
Route::post('/webhooks/paypal', [PaypalWebhookController::class, 'handlePaypal']);
Route::post('/webhooks/paystack', [PaystackWebhookController::class, 'handlePaystack']);
Route::post('/webhooks/razorpay', [RazorpayWebhookController::class, 'handleRazorpay']);
Route::post('/webhooks/mollie', [MollieWebhookController::class, 'handleMollie'])->name('mollie.webhook');
Route::post('/webhooks/coinbase', [CoinbaseWebhookController::class, 'handleCoinbase']);

// LOCALE ROUTES
Route::get('/locale/{lang}', [LocaleController::class, 'language'])->name('locale');

// UPDATE ROUTE
Route::get('/update/now', [UpdateController::class, 'updateDatabase']);
// ADMIN ROUTES
Route::group(['prefix' => 'admin', 'middleware' => ['verified', 'role:admin|accounts', 'PreventBackHistory', \App\Http\Middleware\CheckTwoFactorAuthentication::class]], function () {

    // ADMIN DASHBOARD ROUTES
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/remarks-data', [AdminDashboardController::class, 'remarks'])->name('admin.dashboard.remarks');
    Route::get('/dashboard/performer-data', [AdminDashboardController::class, 'performer'])->name('admin.dashboard.performer');
    Route::get('/dashboard/qa-data', [AdminDashboardController::class, 'qaData'])->name('admin.dashboard.performer');
    Route::get('/dashboard/permission', [AdminDashboardController::class, 'permission'])->name('admin.dashboard.performer');

    // ADMIN STUDIO DASHBOARD ROUTES
    Route::controller(StudioDashboardController::class)->group(function () {
        Route::get('/studio/dashboard', 'index')->name('admin.studio.dashboard');
    });


    // ADMIN VOICEOVER RESULT ROUTES
    Route::controller(VoiceoverStudioResultController::class)->group(function () {
        Route::get('/text-to-speech/results/list', 'listResults')->name('admin.voiceover.results');
        Route::get('/text-to-speech/result/{id}/show', 'show')->name('admin.voiceover.result.show');
        Route::post('/text-to-speech/results/result/delete', 'delete');
    });

    // ADMIN TRANSCRIBE RESULT ROUTES
    Route::controller(TranscribeStudioResultController::class)->group(function () {
        Route::get('/speech-to-text/results/list', 'listResults')->name('admin.transcribe.results');
        Route::get('/speech-to-text/result/{id}/show', 'show')->name('admin.transcribe.result.show');
        Route::post('/speech-to-text/results/delete', 'delete');

    });


    // ADMIN VOICEOVER VOICE CUSTOMIZATION ROUTES
    Route::controller(VoiceCustomizationController::class)->group(function () {
        Route::get('/text-to-speech/voices', 'voices')->name('admin.voiceover.voices');
        Route::post('/text-to-speech/voices/avatar/upload', 'changeAvatar');
        Route::post('/text-to-speech/voice/update', 'voiceUpdate');
        Route::post('/text-to-speech/voices/voice/activate', 'voiceActivate');
        Route::post('/text-to-speech/voices/voice/deactivate', 'voiceDeactivate');
        Route::get('/text-to-speech/voices/activate/all', 'voicesActivateAll');
        Route::get('/text-to-speech/voices/deactivate/all', 'voicesDeactivateAll');
    });

    // ADMIN TRANSCRIBE LANGUAGE CUSTOMIZATION ROUTES
    Route::controller(LanguageCustomizationController::class)->group(function () {
        Route::get('/speech-to-text/languages', 'languages')->name('admin.transcribe.languages');
        Route::post('/speech-to-text/language/update', 'languageUpdate');
        Route::post('/speech-to-text/languages/language/activate', 'languageActivate');
        Route::post('/speech-to-text/languages/language/deactivate', 'languageDeactivate');
        Route::get('/speech-to-text/languages/activate/all', 'languagesActivateAll');
        Route::get('/speech-to-text/languages/deactivate/all', 'languagesDeactivateAll');
    });

    // ADMIN SOUND STUDIO SETTINGS ROUTES
    Route::controller(SoundStudioSettingsController::class)->group(function () {
        Route::get('/text-to-speech/sound-studio', 'index')->name('admin.sound.studio');
        Route::post('/text-to-speech/sound-studio', 'store')->name('admin.sound.studio.store');
        Route::get('/text-to-speech/sound-studio/{id}/show', 'show')->name('admin.sound.studio.show');
        Route::get('/text-to-speech/sound-studio/music', 'music')->name('admin.sound.studio.music');
        Route::post('/text-to-speech/sound-studio/music/public', 'public');
        Route::post('/text-to-speech/sound-studio/music/private', 'private');
        Route::post('/text-to-speech/sound-studio/music/upload', 'upload');
        Route::post('/text-to-speech/sound-studio/music/delete', 'deleteMusic');
        Route::post('/text-to-speech/sound-studio/music/result/delete', 'deleteResult');
    });

    // ADMIN VOICEOVER STUDIO SETTINGS ROUTES
    Route::controller(VoiceoverStudioSettingsController::class)->group(function () {
        Route::get('/text-to-speech/settings', 'index')->name('admin.voiceover.settings');
        Route::post('/text-to-speech/settings', 'store')->name('admin.voiceover.settings.store');
    });

    // ADMIN TRANSCRIBE STUDIO SETTINGS ROUTES
    Route::controller(TranscribeStudioSettingsController::class)->group(function () {
        Route::get('/speech-to-text/settings', 'index')->name('admin.transcribe.settings');
        Route::post('/speech-to-text/settings', 'store')->name('admin.transcribe.settings.store');
    });

    // ADMIN USER MANAGEMENT ROUTES
    Route::controller(AdminUserController::class)->group(function () {
        Route::get('/accounts/dashboard', 'index')->name('admin.user.dashboard');
        Route::get('/accounts/activity', 'activity')->name('admin.user.activity');
        Route::get('/user-projects-permission-request', 'projectPermission')->name('admin.user.permission.request');
        Route::get('/user-projects-permission-pdf/{id}', 'projectPermissionPDF')->name('admin.user.permission.request.pdf');
        Route::get('/user-projects-permission-contract-form/{id}', 'projectPermissionContractFormPDF')->name('admin.user.permission.request.contract-form.pdf');
        Route::post('/user-projects-permission-request-approved', 'projectPermissionApproved')->name('admin.user.permission.request.approve');
        Route::post('/user-projects-permission-request-disagree', 'projectPermissionDisagree')->name('admin.user.permission.request.disagree');
        Route::get('/accounts/list', 'listUsers')->name('admin.user.list');
        Route::post('/accounts', 'store')->name('admin.user.store');
        Route::get('/accounts/create', 'create')->name('admin.user.create');
        Route::get('/accounts/{user}/show', 'show')->name('admin.user.show');
        Route::get('/accounts/{user}/edit', 'edit')->name('admin.user.edit');
        Route::get('/accounts/{user}/storage', 'storage')->name('admin.user.storage');
        Route::post('/accounts/{user}/increase', 'increase')->name('admin.user.increase');
        Route::put('/accounts/{user}/update', 'update')->name('admin.user.update');
        Route::get('/admin/user/login/{id}', 'loginAsUser')->name('admin.user.login');
        Route::put('/accounts/{user}', 'change')->name('admin.user.change');
        Route::post('/accounts/delete', 'delete');
        Route::post('/accounts/sms-verification', 'SMS_Verification')->name('admin.user.sms.verification');
    });

    Route::controller(MailingSystemController::class)->group(function () {
        Route::get('/mailing-system', 'index')->name('admin.mailing.system.index');
        Route::get('/mailing-system-create', 'create')->name('admin.mailing.system.user.create');
        Route::get('/mailing-system-fetch-users', 'fetchUsers')->name('admin.mailing.system.user.fetchUsers');
        Route::post('/mailing-system-store', 'store')->name('admin.mailing.system.user.store');
        Route::get('/track-open', 'trackOpen')->name('track-open');
        Route::get('/mailing-system-campaign', 'indexCampaign')->name('admin.mailing.system.campaign.index');
        Route::get('/mailing-system-campaign-create', 'createCampaign')->name('admin.mailing.system.campaign.create');
        Route::post('/mailing-system-campaign-preview-email', 'previewEmailCampaign')->name('admin.mailing.system.campaign.preview.email');
        Route::post('/mailing-system-campaign-store', 'storeCampaign')->name('admin.mailing.system.campaign.store');
        Route::get('/mailing-system/un-subscribe-list', 'unsubscribeList')->name('admin.mailing.system.unsubscribe.list');
        Route::get('/mailing-system/report', 'report')->name('admin.mailing.system.report');
        Route::get('/mailing-system/{id}/edit', 'edit')->name('admin.mailing.system.edit');
        Route::put('/mailing-system/{id}/update', 'update')->name('admin.mailing.system.update');
        Route::post('/mailing-system/delete', 'delete');
    });

    // ADMIN FINANCE - DASHBOARD & TRANSACTIONS & SUBSCRIPTION LIST ROUTES
    Route::controller(FinanceController::class)->group(function () {
        Route::get('/finance/dashboard', 'index')->name('admin.finance.dashboard');
        Route::get('/finance/transactions', 'listTransactions')->name('admin.finance.transactions');
        Route::put('/finance/transaction/{id}/update', 'update')->name('admin.finance.transaction.update');
        Route::get('/finance/transaction/{id}/show', 'show')->name('admin.finance.transaction.show');
        Route::get('/finance/transaction/{id}/edit', 'edit')->name('admin.finance.transaction.edit');
        Route::post('/finance/transaction/delete', 'delete');
        Route::get('/finance/subscribers', 'listSubscriptions')->name('admin.finance.subscriptions');
    });

    // ADMIN FINANCE - CANCEL USER SUBSCRIPTION
    Route::post('/finance/subscriptions/cancel', [PaymentController::class, 'stopSubscription']);

    // ADMIN FINANCE - SUBSCRIPTION PLAN ROUTES
    Route::controller(FinanceSubscriptionPlanController::class)->group(function () {
        Route::get('/finance/subscription', 'index')->name('admin.finance.plans');
        Route::post('/finance/subscription', 'store')->name('admin.finance.plan.store');
        Route::get('/finance/subscription/create', 'create')->name('admin.finance.plan.create');
        Route::get('/finance/subscription/{id}/show', 'show')->name('admin.finance.plan.show');
        Route::get('/finance/subscription/{id}/edit', 'edit')->name('admin.finance.plan.edit');
        Route::put('/finance/subscription/{id}', 'update')->name('admin.finance.plan.update');
        Route::post('/finance/subscription/delete', 'delete');
    });

    // ADMIN FINANCE - PREPAID PLAN ROUTES
    Route::controller(FinancePrepaidPlanController::class)->group(function () {
        Route::get('/finance/prepaid', 'index')->name('admin.finance.prepaid');
        Route::post('/finance/prepaid', 'store')->name('admin.finance.prepaid.store');
        Route::get('/finance/prepaid/create', 'create')->name('admin.finance.prepaid.create');
        Route::get('/finance/prepaid/{id}/show', 'show')->name('admin.finance.prepaid.show');
        Route::get('/finance/prepaid/{id}/edit', 'edit')->name('admin.finance.prepaid.edit');
        Route::put('/finance/prepaid/{id}', 'update')->name('admin.finance.prepaid.update');
        Route::post('/finance/prepaid/delete', 'delete');
    });

    // ADMIN FINANCE - PREPAID PLAN ROUTES
    Route::controller(PriceController::class)->group(function () {
        Route::get('/price', 'index')->name('admin.price');
        Route::post('/price', 'store')->name('admin.price.store');
        Route::get('/price/create', 'create')->name('admin.price.create');
        Route::get('/price/{id}/show', 'show')->name('admin.price.show');
        Route::get('/price/{id}/edit', 'edit')->name('admin.price.edit');
        Route::put('/price/{id}', 'update')->name('admin.price.update');
        Route::post('/price/delete', 'delete');
    });

    // ADMIN FINANCE - REFERRAL ROUTES
    Route::controller(ReferralSystemController::class)->group(function () {
        Route::get('/referral/settings', 'index')->name('admin.referral.settings');
        Route::post('/referral/settings', 'store')->name('admin.referral.settings.store');
        Route::get('/referral/{order_id}/show', 'paymentShow')->name('admin.referral.show');
        Route::get('/referral/payouts', 'payouts')->name('admin.referral.payouts');
        Route::get('/referral/payouts/{id}/show', 'payoutsShow')->name('admin.referral.payouts.show');
        Route::put('/referral/payouts/{id}/store', 'payoutsUpdate')->name('admin.referral.payouts.update');
        Route::get('/referral/payouts/{id}/cancel', 'payoutsCancel')->name('admin.referral.payouts.cancel');
        Route::delete('/referral/payouts/{id}/decline', 'payoutsDecline')->name('admin.referral.payouts.decline');
        Route::get('referral/payouts/{id}/download-invoice', 'downloadInvoice')->name('admin.referral.payouts.downloadInvoice');

        Route::get('/referral/top', 'topReferrers')->name('admin.referral.top');
    });

    // ADMIN FINANCE - INVOICE SETTINGS
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/settings/invoice', 'index')->name('admin.settings.invoice');
        Route::post('/settings/invoice', 'store')->name('admin.settings.invoice.store');
    });

    // ADMIN FINANCE SETTINGS ROUTES
    Route::controller(FinanceSettingController::class)->group(function () {
        Route::get('/finance/settings', 'index')->name('admin.finance.settings');
        Route::post('/finance/settings', 'store')->name('admin.finance.settings.store');

    });

    // ADMIN SUPPORT ROUTES
    Route::controller(SupportController::class)->group(function () {
        Route::get('/support', 'index')->name('admin.support');
        Route::put('/support/{ticked_id}', 'update')->name('admin.support.update');
        Route::get('/support/{ticket_id}/show', 'show')->name('admin.support.show');
        Route::post('/support/delete', 'delete');
    });


    // ADMIN GENERAL SETTINGS - GLOBAL SETTINGS
    Route::controller(GlobalController::class)->group(function () {
        Route::get('/settings/global', 'index')->name('admin.settings.global');
        Route::post('/settings/global', 'store')->name('admin.settings.global.store');
    });

    // ADMIN GENERAL SETTINGS - DATABASE BACKUP
    Route::controller(BackupController::class)->group(function () {
        Route::get('/settings/backup', 'index')->name('admin.settings.backup');
        Route::get('/settings/backup/create', 'create')->name('admin.settings.backup.create');
        Route::get('/settings/backup/{file_name}', 'download')->name('admin.settings.backup.download');
        Route::get('/settings/backup/{file_name}/delete', 'destroy')->name('admin.settings.backup.delete');
    });

    // ADMIN GENERAL SETTINGS - SMTP SETTINGS
    Route::controller(SMTPController::class)->group(function () {
        Route::post('/settings/smtp/test', 'test')->name('admin.settings.smtp.test');
        Route::get('/settings/smtp', 'index')->name('admin.settings.smtp');
        Route::post('/settings/smtp', 'store')->name('admin.settings.smtp.store');
    });

    // ADMIN GENERAL SETTINGS - REGISTRATION SETTINGS
    Route::controller(RegistrationController::class)->group(function () {
        Route::get('/settings/registration', 'index')->name('admin.settings.registration');
        Route::post('/settings/registration', 'store')->name('admin.settings.registration.store');
    });

    // ADMIN GENERAL SETTINGS - OAUTH SETTINGS
    Route::controller(OAuthController::class)->group(function () {
        Route::get('/settings/oauth', 'index')->name('admin.settings.oauth');
        Route::post('/settings/oauth', 'store')->name('admin.settings.oauth.store');
    });

    // ADMIN GENERAL SETTINGS - ACTIVATION SETTINGS
    Route::controller(ActivationController::class)->group(function () {
        Route::get('/settings/activation', 'index')->name('admin.settings.activation');
        Route::post('/settings/activation', 'store')->name('admin.settings.activation.store');
        Route::get('/settings/activation/remove', 'remove')->name('admin.settings.activation.remove');
        Route::delete('/settings/activation/destroy', 'destroy')->name('admin.settings.activation.destroy');
        Route::get('/settings/activation/manual', 'showManualActivation')->name('admin.settings.activation.manual');
        Route::post('/settings/activation/manual', 'storeManualActivation')->name('admin.settings.activation.manual.store');
    });

    // ADMIN FRONTEND SETTINGS - APPEARANCE SETTINGS
    Route::controller(AppearanceController::class)->group(function () {
        Route::get('/settings/appearance', 'index')->name('admin.settings.appearance');
        Route::post('/settings/appearance', 'store')->name('admin.settings.appearance.store');
    });

    // ADMIN FRONTEND SETTINGS - FRONTEND SETTINGS
    Route::controller(FrontendController::class)->group(function () {
        Route::get('/settings/frontend', 'index')->name('admin.settings.frontend');
        Route::post('/settings/frontend', 'store')->name('admin.settings.frontend.store');
    });

    // ADMIN FRONTEND SETTINGS - BLOG MANAGER
    Route::controller(BlogController::class)->group(function () {
        Route::get('/settings/blog', 'index')->name('admin.settings.blog');
        Route::get('/settings/blog/create', 'create')->name('admin.settings.blog.create');
        Route::post('/settings/blog', 'store')->name('admin.settings.blog.store');
        Route::put('/settings/blogs/{id}', 'update')->name('admin.settings.blog.update');
        Route::get('/settings/blogs/{id}/edit', 'edit')->name('admin.settings.blog.edit');
        Route::post('/settings/blog/delete', 'delete');
    });

    // ADMIN GENERAL SETTINGS - USE CASE MANAGER
    Route::controller(UseCaseController::class)->group(function () {
        Route::get('/settings/usecase', 'index')->name('admin.settings.usecase');
        Route::get('/settings/usecase/create', 'create')->name('admin.settings.usecase.create');
        Route::post('/settings/usecase', 'store')->name('admin.settings.usecase.store');
        Route::put('/settings/usecases/{id}', 'update')->name('admin.settings.usecase.update');
        Route::get('/settings/usecases/{id}/edit', 'edit')->name('admin.settings.usecase.edit');
        Route::post('/settings/usecase/delete', 'delete');
    });

    // ADMIN FRONTEND SETTINGS - FAQ MANAGER
    Route::controller(FAQController::class)->group(function () {
        Route::get('/settings/faq', 'index')->name('admin.settings.faq');
        Route::get('/settings/faq/create', 'create')->name('admin.settings.faq.create');
        Route::post('/settings/faq', 'store')->name('admin.settings.faq.store');
        Route::put('/settings/faqs/{id}', 'update')->name('admin.settings.faq.update');
        Route::get('/settings/faqs/{id}/edit', 'edit')->name('admin.settings.faq.edit');
        Route::post('/settings/faq/delete', 'delete');
    });

    // ADMIN FRONTEND SETTINGS - REVIEW MANAGER
    Route::controller(ReviewController::class)->group(function () {
        Route::get('/settings/review', 'index')->name('admin.settings.review');
        Route::get('/settings/review/create', 'create')->name('admin.settings.review.create');
        Route::post('/settings/review', 'store')->name('admin.settings.review.store');
        Route::put('/settings/reviews/{id}', 'update')->name('admin.settings.review.update');
        Route::get('/settings/reviews/{id}/edit', 'edit')->name('admin.settings.review.edit');
        Route::post('/settings/review/delete', 'delete');
    });

    // ADMIN Folder CRUD Images Folder
    Route::controller(ImagesFolderController::class)->group(function () {
        Route::get('/folder', 'index')->name('admin.images.folder');
        Route::get('/folder-list', 'list')->name('admin.images.folder.list');
        Route::post('/folder-store', 'store')->name('admin.images.folder.store');
        Route::get('/folder-edit/{id}', 'edit')->name('admin.images.folder.edit');
        Route::get('/folder-freeze/{id}', 'freeze')->name('admin.images.folder.freeze');
        Route::get('/folder-un-freeze/{id}', 'unFreeze')->name('admin.images.folder.unFreeze');
        Route::post('/folder-update/{id}', 'update')->name('admin.images.folder.update');
        Route::post('/folder-delete', 'delete')->name('admin.images.folder.delete');
        Route::get('/folder-images/{id}', 'folderImages')->name('admin.images.folder.images');
        Route::post('/folder-images-data', 'folderImageData')->name('admin.images.folder.image.data');
    });

    // ADMIN Folder CRUD Images Folder
    Route::controller(ImagesController::class)->group(function () {
        Route::get('/image', 'index')->name('admin.image.index');
        Route::get('/image-list', 'list')->name('admin.image.list');
        Route::get('/image-create', 'create')->name('admin.image.create');
        Route::post('/image-store', 'store')->name('admin.image.store');
        Route::get('/image-edit/{id}', 'edit')->name('admin.image.edit');
        Route::post('/image-update/{id}', 'update')->name('admin.image.update');
        Route::post('/image-delete', 'delete')->name('admin.image.delete');
    });

    // ADMIN Folder CRUD Images Folder
    Route::controller(ReportController::class)->group(function () {
        Route::get('/project', 'project')->name('admin.report.project');

    });

    // ADMIN Folder CRUD CSV Folder
    Route::controller(CSVController::class)->group(function () {
        Route::get('/csv', 'index')->name('admin.csv.index');
        Route::get('/csv-list', 'list')->name('admin.csv.list');
        Route::get('/csv-create', 'create')->name('admin.csv.create');
        Route::post('/csv-store', 'store')->name('admin.csv.store');
        Route::get('/csv-edit/{id}', 'edit')->name('admin.csv.edit');
        Route::post('/csv-update/{id}', 'update')->name('admin.csv.update');
        Route::post('/csv-delete', 'delete')->name('admin.csv.delete');
    });

    // ADMIN Folder CRUD Text Folder
    Route::controller(TextController::class)->group(function () {
        Route::get('/text', 'index')->name('admin.text.index');
        Route::get('/text-list', 'list')->name('admin.text.list');
        Route::get('/text-create', 'create')->name('admin.text.create');
        Route::post('/text-store', 'store')->name('admin.text.store');
        Route::get('/text-edit/{id}', 'edit')->name('admin.text.edit');
        Route::post('/text-update/{id}', 'update')->name('admin.text.update');
        Route::post('/text-delete', 'delete')->name('admin.text.delete');
    });

    // ADMIN FRONTEND SETTINGS - PAGE MANAGER (PRIVACY & TERMS)
    Route::controller(PageController::class)->group(function () {
        Route::get('/settings/terms', 'index')->name('admin.settings.terms');
        Route::post('/settings/terms', 'store')->name('admin.settings.terms.store');
    });

    // ADMIN GENERAL SETTINGS - UPGRADE SOFTWARE
    Route::controller(UpgradeController::class)->group(function () {
        Route::get('/settings/upgrade', 'index')->name('admin.settings.upgrade');
        Route::post('/settings/upgrade', 'upgrade')->name('admin.settings.upgrade.start');
    });

    Route::get('/clear', function () {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('queue:work');
    });

    Route::get('/symlink', function () {
        Artisan::call('storage:link');
    });

    // ADMIN project instructions
    Route::controller(ProjectInstructionController::class)->group(function () {
        Route::get('/project-instruction', 'index')->name('admin.project-instruction');
        Route::post('/instruction-store', 'store');
        Route::post('/upload', 'uploadFile');
        Route::get('/project-instruction-list', 'list')->name('admin.project-instruction.list');
        Route::post('/project-instruction-delete', 'delete')->name('admin.project-instruction.delete');
        Route::get('/project-instruction-create-view', 'getCreateView')->name('admin.project-instruction.create-view');
        Route::get('/project-instruction/{id}', 'edit')->name('admin.project-instruction.edit');
        Route::post('/project-instruction-update/{id}', 'update')->name('admin.project-instruction.update');
        Route::get('/assigned-projects/{id}', 'assignedProjects')->name('admin.assigned.projects');
    });

});


// REGISTERED USER ROUTES
Route::group([
    'prefix' => 'account',
    'middleware' => [
        CheckProfileCompletion::class,
        'verified',
        'role:user|admin|subscriber|quality_assurance|accounts',
        'PreventBackHistory',
    ],
], function () {


    // CHANGE USER PASSWORD ROUTES
    Route::controller(UserPasswordController::class)->group(function () {
        Route::get('/dashboard/security', 'index')->name('user.security');
        Route::post('/dashboard/security/password/{id}', 'update')->name('user.security.password');
        Route::post('/dashboard/security/google/{id}', 'security')->name('user.security.google');
    });


    // USER PURCHASE HISTORY ROUTES
    Route::controller(PurchaseHistoryController::class)->group(function () {
        Route::get('/purchases', 'index')->name('user.purchases');
        Route::get('/purchases/show/{id}', 'show')->name('user.purchases.show');
        Route::get('/purchases/subscriptions', 'subscriptions')->name('user.purchases.subscriptions');
    });

    // USER PRICING PLAN ROUTES
    Route::controller(PricingPlanController::class)->group(function () {
        Route::get('/pricing/plans', 'index')->name('user.plans');
        Route::get('/pricing/plan/subscription/{id}', 'subscribe')->name('user.plan.subscribe')->middleware('unsubscribed');
        Route::get('/pricing/plan/prepaid/{id}', 'checkout')->name('user.prepaid.checkout');
    });

    // USER PAYMENT ROUTES
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoices', 'invoices')->name('admin.user.invoices');
        Route::get('/invoices-create', 'create')->name('admin.invoices.create');
        Route::post('/invoices-store', 'invoices_store')->name('admin.invoices_store');
        Route::get('/invoices-list', 'invoicesUserList')->name('user.invoices.index');
    });

    // USER PAYMENT ROUTES
    Route::controller(PaymentController::class)->group(function () {
        Route::post('/payments/pay/{id}', 'pay')->name('user.payments.pay')->middleware('unsubscribed');
        Route::post('/payments/pay/prepaid/{id}', 'payPrePaid')->name('user.payments.pay.prepaid');
        Route::post('/payments/approved/razorpay', 'approvedRazorpayPrepaid')->name('user.payments.approved.razorpay');
        Route::get('/payments/success/braintree', 'braintreeSuccess')->name('user.payments.approved.braintree');
        Route::get('/payments/approved', 'approved')->name('user.payments.approved');
        Route::get('/payments/cancelled', 'cancelled')->name('user.payments.cancelled');
        Route::post('/payments/subscription/razorpay', 'approvedRazorpaySubscription')->name('user.payments.subscription.razorpay');
        Route::get('/payments/subscription/approved', 'approvedSubscription')->name('user.payments.subscription.approved');
        Route::get('/payments/subscription/cancelled', 'cancelledSubscription')->name('user.payments.subscription.cancelled')->middleware('unsubscribed');
        Route::post('/subscriptions/cancel', 'stopSubscription');
        Route::post('/csv/payment', 'CSVPayment')->name('admin.finance.csv.payment');
    });

    // USER REFERRAL ROUTES
    Route::controller(ReferralController::class)->group(function () {

        Route::get('/referral', 'index')->name('user.referral');
        Route::post('/referral/settings', 'store')->name('user.referral.store');
        Route::get('/referral/gateway', 'gateway')->name('user.referral.gateway');
        Route::post('/referral/gateway', 'gatewayStore')->name('user.referral.gateway.store');
        Route::get('/referral/payouts', 'payouts')->name('user.referral.payout');
        Route::post('/referral/email', 'email')->name('user.referral.email');
        Route::get('/referral/payouts/create', 'payoutsCreate')->name('user.referral.payout.create');
        Route::post('/referral/payouts/store', 'payoutsStore')->name('user.referral.payout.store');
        Route::get('/referral/all', 'referrals')->name('user.referral.referrals');
        Route::get('/referral/payouts/{id}/show', 'payoutsShow')->name('user.referral.payout.show');
        Route::get('/referral/payouts/{id}/cancel', 'payoutsCancel')->name('user.referral.payout.cancel');
        Route::delete('/referral/payouts/{id}/decline', 'payoutsDecline')->name('user.referral.payout.decline');
    });

    // USER INVOICE ROUTES
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/payments/invoice/{order_id}/generate', 'generatePaymentInvoice')->name('user.payments.invoice');
        Route::get('/payments/invoice/{id}/show', 'showPaymentInvoice')->name('user.payments.invoice.show');
        Route::get('/payments/invoice/{order_id}/transfer', 'bankTransferPaymentInvoice')->name('user.payments.invoice.transfer');
    });

    // User Folder CRUD Images   Folder
    Route::controller(\App\Http\Controllers\User\ImagesFolderController::class)->group(function () {
        Route::get('/folder/{project}', 'index')->name('user.images.folder');
        Route::get('/sms-folders', 'smsIndex')->name('user.images.folder.sms');
        Route::get('/folder-list', 'list')->name('user.images.folder.list');
        Route::post('folder/folder-store', 'store')->name('user.images.folder.store');
        Route::post('/user-folder-update', 'updateFolderName')->name('user.folder.update.name');
        Route::get('/folder-edit/{id}', 'edit')->name('user.images.folder.edit');
        Route::get('/rejected-images/{id}', 'rejectedImages')->name('user.images.folder.rejected-images');
        Route::post('/folder-update/{id}', 'update')->name('user.images.folder.update');
        Route::post('/user-folder-delete', 'delete')->name('user.images.folder.delete');
        Route::post('/images/folder/delete-multiple', 'deleteMultipleFolders')->name('user.images.folder.delete-multiple');
        Route::post('/images/folder/export-multiple', 'exportMultipleFolders')->name('user.images.folder.export-multiple');
    });

    // User Folder CRUD Images Folder
    Route::controller(\App\Http\Controllers\User\ImagesController::class)->group(function () {
        Route::get('/image', 'index')->name('user.image.index');
        Route::get('/image-list', 'list')->name('user.image.list');
        Route::get('/image-list-table', 'tableList')->name('user.table.image.list');
        Route::get('/image-create', 'create')->name('user.image.create');
        Route::get('/user-image-comment', 'comment')->name('user.image.comment');
        Route::get('/user-image-next', 'nextImage')->name('user.image.nextImage');
        Route::post('/image-store', 'store')->name('user.image.store');
        Route::get('/image-edit/{id}', 'edit')->name('user.image.edit');
        Route::post('/image-update/{id}', 'update')->name('user.image.update');
        Route::post('/image-delete', 'delete')->name('user.image.delete');
        Route::post('/user/images/delete-multiple', 'deleteMultipleImages')->name('user.images.delete-multiple');

    });

    Route::controller(ProjectController::class)->group(function () {
        Route::get('/projects', 'index')->name('user.project.index');
        Route::get('/project-details/{id}', 'details')->name('user.project.details');
        Route::post('/consent-form-apply', 'consentForm')->name('user.project.apply.consent.form');
        Route::post('/project-apply', 'projectApply')->name('user.project.apply');
        Route::get('/project-applied/{id}', 'projectApplied')->name('user.project.applied');
        Route::get('/assign-project/{id}', 'assignProjects')->name('user.project.apply.project');
        Route::get('/get-term-and-condition/{id}', 'getTermCondition')->name('user.project.term.condition');
    });

    // USER SUPPORT REQUEST ROUTES
    Route::controller(UserSupportController::class)->group(function () {
        Route::get('/support', 'index')->name('user.support');
        Route::post('/support', 'store')->name('user.support.store');
        Route::get('/support/create', 'create')->name('user.support.create');
        Route::get('/support/{ticket_id}/show', 'show')->name('user.support.show');
        Route::post('/support/delete', 'delete');
    });

    // USER NOTIFICATION ROUTES
    Route::controller(UserNotificationController::class)->group(function () {
        Route::get('/notification', 'index')->name('user.notifications');
        Route::get('/notification/{id}/show', 'show')->name('user.notifications.show');
        Route::post('/notification/delete', 'delete');
        Route::get('/notifications/mark-all', 'markAllRead')->name('user.notifications.markAllRead');
        Route::get('/notifications/delete-all', 'deleteAll')->name('user.notifications.deleteAll');
        Route::post('/notifications/mark-as-read', 'markNotification')->name('user.notifications.mark');
    });

    // USER SEARCH ROUTES
    Route::any('/search', [SearchController::class, 'index'])->name('search');

    // ALL TEXT TO SPEECH ROUTES
    Route::group(['prefix' => 'text-to-speech'], function () {

        // VOICEOVER STUDIO ROUTES
        Route::controller(VoiceoverStudioController::class)->group(function () {
            Route::get('/', 'index')->name('user.voiceover');
            Route::post('/', 'synthesize')->name('user.voiceover.synthesize');
            Route::post('/listen', 'listen')->name('user.voiceover.listen');
            Route::post('/listen-row', 'listenRow');
            Route::get('/{id}/show', 'show')->name('user.voiceover.show');
            Route::post('/audio', 'audio');
            Route::post('/delete', 'delete');
            Route::post('/config', 'config');
        });

        // VOICEOVER RESULT ROUTES
        Route::controller(VoiceoverResultController::class)->group(function () {
            Route::get('/result', 'index')->name('user.voiceover.results');
            Route::get('/result/{id}/show', 'show')->name('user.voiceover.results.show');
            Route::post('/result/delete', 'delete');
        });

        // VOICEOVER PROJECT ROUTES
        Route::controller(VoiceoverProjectController::class)->group(function () {
            Route::get('/project', 'index')->name('user.voiceover.projects');
            Route::post('/project', 'store');
            Route::post('/project/result/delete', 'delete');
            Route::get('/project/change', 'change')->name('user.voiceover.projects.change');
            Route::get('/project/change/stats', 'changeStatus')->name('user.voiceover.projects.change.stats');
            Route::get('/project/result/{id}/show', 'show')->name('user.voiceover.projects.show');
            Route::put('/project', 'update')->name('user.voiceover.project.update');
            Route::delete('/project', 'destroy')->name('user.voiceover.project.delete');
        });

        // SOUND STUDIO ROUTES
        Route::controller(SoundStudioController::class)->group(function () {
            Route::get('/sound-studio', 'index')->name('user.sound.studio');
            Route::get('/sound-studio/results', 'results')->name('user.sound.studio.results');
            Route::get('/sound-studio/result/{id}/show', 'show')->name('user.sound.studio.show');
            Route::get('/sound-studio/result/{id}/show-studio/', 'showStudio')->name('user.sound.studio.show.studio');
            Route::post('/sound-studio/result/delete', 'delete');
            Route::post('/sound-studio/final/result/delete', 'deleteStudioResult');
            Route::get('/sound-studio/settings', 'settings');
            Route::post('/sound-studio/music/merge', 'merge');
            Route::post('/sound-studio/music/upload', 'upload');
            Route::post('/sound-studio/music/delete', 'deleteMusic');
            Route::get('/sound-studio/music/list', 'list')->name('user.sound.studio.music.list');
        });

        // ALL VOICES ROUTES
        Route::controller(VoiceController::class)->group(function () {
            Route::get('/voices', 'index')->name('user.voiceover.voices');
            Route::post('/voices/change', 'change');
        });
    });

    // ALL SPEECH TO TEXT ROUTES
    Route::group(['prefix' => 'speech-to-text'], function () {

        // TRANSCRIBE RESULT ROUTES
        Route::controller(TranscribeResultController::class)->group(function () {
            Route::get('/result', 'index')->name('user.transcribe.results');
            Route::get('/result/{id}/show', 'show')->name('user.transcribe.results.show');
            Route::post('/result/delete', 'delete');
            Route::post('/result/transcript', 'transcript');
            Route::post('/result/transcript/save', 'transcriptSave');
            Route::post('/results/transcript/live', 'transcript');
            Route::post('/results/transcript/save', 'transcriptSave');
        });

        // TRANSCRIBE PROJECT ROUTES
        Route::controller(TranscribeProjectController::class)->group(function () {
            Route::get('/project', 'index')->name('user.transcribe.projects');
            Route::post('/project', 'store');
            Route::put('/project', 'update')->name('user.transcribe.project.update');
            Route::delete('/project', 'destroy')->name('user.transcribe.project.delete');
            Route::post('/project/result/delete', 'delete');
            Route::get('/project/change', 'change')->name('user.transcribe.projects.change');
            Route::get('/project/change/stats', 'changeStatus')->name('user.transcribe.projects.change.stats');
            Route::get('/project/result/{id}/show', 'show')->name('user.transcribe.projects.show');
        });

        // TRANSCRIBE STUDIO ROUTES
        Route::controller(TranscribeStudioController::class)->group(function () {
            Route::get('/', 'fileTranscribe')->name('user.transcribe.file');
            Route::get('/assign-images-folders/{id}', 'assignImagesFolder')->name('user.transcribe.assign-folder');
            Route::get('/assign-images-text/{id}', 'assignTextFolder')->name('user.transcribe.assign-text');
            Route::get('/assign-text-to-text/{id}', 'assignTextToTextFolder')->name('user.transcribe.assign-text-to-text');
            Route::get('/record', 'recordTranscribe')->name('user.transcribe.record');
            Route::post('/save-text-to-text', 'saveTextToText')->name('user.transcribe.textToText');
            if (config('stt.enable.aws_live') == 'on') {
                Route::get('/live', 'liveTranscribe')->name('user.transcribe.live');
                Route::get('/live-image/{id}', 'liveTranscribeImage')->name('user.transcribe.live-image');
                Route::post('/live', 'transcribeLive')->name('user.transcribe.transcribe.live');
                Route::get('/get-images/{currentSlide}/{direction}/{folderId}', 'getImages')->name('user.transcribe.live.slider-images');
                Route::get('/live-text', 'liveTranscribeText')->name('user.transcribe.live.Text');
                Route::get('/live-text-to-text', 'liveTranscribeTextToText')->name('user.transcribe.live.TextToText');
                Route::get('/live-folder-text/{id}', 'liveTranscribeTextFolder')->name('user.transcribe.live.Text-folder');
                Route::get('/live-folder-text-to-text/{id}', 'liveTranscribeTextToTextFolder')->name('user.transcribe.live.Text-text-to-text');
                Route::post('/live-text-save', 'transcribeLiveText')->name('transcribeLiveText.transcribe.save');
                Route::get('/get-text/{currentSlide}/{direction}/{folderId}/{filter}', 'getText')->name('user.transcribe.live.slider-images');
                Route::get('/get-text-to-text/{currentSlide}/{direction}/{folderId}/{filter}', 'getTextToText')->name('user.transcribe.live.getTextToText');
                Route::get('/get-text-by-id/{id}/{folderId}', 'getTextById')->name('user.transcribe.live.slider-images-by-id');
                Route::get('/settings/live', 'settingsLive');
                Route::get('/settings/live/limits', 'settingsLiveLimits');
            }
            Route::post('/', 'transcribe')->name('user.transcribe.transcribe');
            Route::get('/settings', 'settings');
            Route::get('/{id}/show/file', 'showFile')->name('user.transcribe.show.file');
            Route::get('/{id}/show/record', 'showRecord')->name('user.transcribe.show.record');
        });
    });

});
Route::group([
    'prefix' => 'account',
    'middleware' => [
        'verified',
        \App\Http\Middleware\CheckTwoFactorAuthentication::class,
        'role:user|admin|subscriber|quality_assurance',
        'PreventBackHistory',
    ],
], function () {
    // USER DASHBOARD ROUTES
    Route::controller(UserDashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('user.dashboard');
        Route::get('/complete-profile', 'completeProfile')->name('user.dashboard.complete.profile');
        Route::post('/complete-profile-store', 'completeProfileStore')->name('user.dashboard.complete-profile.store');
        Route::get('/dashboard/edit', 'edit')->name('user.dashboard.edit');
//        Route::get('/dashboard/edit/defaults', 'editDefaults')->name('user.dashboard.edit.defaults');
        Route::post('/dashboard/edit/defaults/project', 'project');
        Route::put('/dashboard/update/{user}', 'update')->name('user.dashboard.update');
        Route::put('/dashboard/update/defaults/{user}', 'updateDefaults')->name('user.dashboard.update.defaults');

    });

    Route::controller(PhoneNumberVerificationController::class)->group(function () {
        Route::get('/verify-phone-number-page', 'verify')->name('verify-phone-number');
        Route::get('/verify-phone-number', 'verifyNumber')->name('verify-phone-number-page');
        Route::post('/verify-phone-number-code', 'verifyNumberCode')->name('verify-phone-number-code');
    });
});
Route::group(['prefix' => 'admin', 'middleware' => ['verified', 'role:admin|quality_assurance', 'PreventBackHistory']], function () {


    // ADMIN TRANSCRIBE RESULT ROUTES
    Route::controller(COCOController::class)->group(function () {
        Route::get('/user/{name}', 'user')->name('admin.coco.user');
        Route::get('/coco', 'index')->name('admin.coco.index');
        Route::get('/user-folder/{projectID}/{userID}', 'userFolderList')->name('admin.coco.userFolderList');
        Route::get('/user-images/{id}', 'userImageList')->name('admin.coco.userImageList');
        Route::get('/user-folder/download/{id}', 'userFolderDownload')->name('admin.coco.userFolderDownload');
        Route::get('/user-dasdasdasd/download/{id}', 'userFolderDownlodsadasdad')->name('admin.coco.userFolderDownloasadasdad');
        Route::get('/next-image', 'nextImage')->name('admin.coco.nextImage');
        Route::get('/check-image-duplicacy/{id}', 'checkImageDuplicacy')->name('admin.coco.duplicacy');
        Route::get('/duplicate-images/{id}', 'duplicateImages')->name('admin.coco.duplicateImages');
        Route::post('/save-feedback', 'saveImageDetails')->name('admin.coco.saveImageDetails imageDetails');
        Route::post('/coco-payment', 'payment')->name('admin.coco.cocoPayment');
        Route::post('/coco/export-multiple', 'exportMultipleFolders')->name('admin.coco.folder.export-multiple');
        Route::post('/coco/download-multiple', 'downloadMultipleFolders')->name('admin.coco.folder.download-multiple');
        Route::post('/coco/read-multiple', 'readMultipleFolders')->name('admin.coco.folder.read-multiple');
        Route::post('/coco/assign-quality-assurance', 'assignQuantityAssurance')->name('admin.coco.folder.assignQuantityAssurance');

    });
    // ADMIN TRANSCRIBE RESULT ROUTES
    Route::controller(LiveTrancriptionController::class)->group(function () {
        Route::get('/live/transcription/results/list', 'liveTranscriptionResults')->name('admin.liveTranscription.index');
        Route::get('/live/transcription/results/list-text', 'liveTranscriptionResultsText')->name('admin.liveTranscriptionText.index');
        Route::get('/text-list-user/{id}', 'textListUser')->name('admin.text.list.user');
        Route::get('/text-list-user-folder/{projectID}/{userID}', 'textListUserFolder')->name('admin.text.list.user.folder');
        Route::get('/text-list-user-folder-text/{id}', 'textListUserFolderText')->name('admin.text.list.user.folder.text');
        Route::get('/live/transcription/results/list-text-to-text', 'liveTranscriptionResultsTextToText')->name('admin.liveTranscriptionText.text_to_text');
        Route::get('/live/transcription/results/list/{id}', 'liveTranscriptionResultsByDates')->name('admin.liveTranscription.liveTranscriptionResultsByDates');
        Route::get('/live/transcription/results/list/user/{id}', 'liveTranscriptionResultsByUser')->name('admin.liveTranscription.liveTranscriptionResultsByUser');
        Route::get('/live/transcription/results/list-text/{id}', 'liveTranscriptionResultsByDatesText')->name('admin.liveTranscription.liveTranscriptionResultsByDatesText');
        Route::get('/live/transcription/results/list/user-text/{id}', 'liveTranscriptionResultsByUserText')->name('admin.liveTranscription.liveTranscriptionResultsByUserText');
        Route::post('/live/transcription/results/agree', 'liveTranscriptionResultsAgree')->name('admin.liveTranscription.liveTranscriptionResultsAgree');
        Route::post('/live/transcription/results/disagree', 'liveTranscriptionResultsDisAgree')->name('admin.liveTranscription.liveTranscriptionResultsDisAgree');
        Route::get('/live/transcription/results/download-audio/{id}', 'liveTranscriptionResultsDownloadAudio')->name('admin.liveTranscription.liveTranscriptionResultsDownloadAudio');
        Route::get('/next-text', 'nextText')->name('admin.coco.nextText');
        Route::post('/save-feedback-result', 'saveFeedbackResult')->name('admin.liveTranscription.saveFeedbackResult');

    });
    // ADMIN Text RESULT ROUTES
    Route::controller(TextController::class)->group(function () {
        Route::get('/text-user/{id}', 'text_user')->name('admin.text.user');
        Route::get('/text-user-folder/{projectID}/{userID}', 'text_user_folder')->name('admin.text.user.folder');
        Route::get('/text-user-folder-text/{id}', 'text_user_folder_text')->name('admin.text.user.folder.text');
        Route::get('/user-text-csv/{id}', 'csvText')->name('admin.text.csv');
        Route::get('/user-next-text', 'nextText')->name('admin.text.next');
        Route::post('/save-text-feedback', 'saveFeedbackResult')->name('admin.text.feedback.save');

    });
    // ADMIN NOTIFICATION ROUTES
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index')->name('admin.notifications');
        Route::get('/notifications/sytem', 'system')->name('admin.notifications.system');
        Route::get('/notifications/create', 'create')->name('admin.notifications.create');
        Route::post('/notifications', 'store')->name('admin.notifications.store');
        Route::get('/notifications/{id}/show', 'show')->name('admin.notifications.show');
        Route::get('/notifications/system/{id}/show', 'systemShow')->name('admin.notifications.systemShow');
        Route::get('/notifications/mark-all', 'markAllRead')->name('admin.notifications.markAllRead');
        Route::get('/notifications/delete-all', 'deleteAll')->name('admin.notifications.deleteAll');
        Route::post('/notifications/delete', 'delete');
        Route::post('/notifications/get-city', 'getCity');
        Route::post('/notifications/get-city-users', 'getCityUsers');
    });

});
Route::group([
    'prefix' => 'quality-assurance',
    'middleware' => [
        'verified',
        'role:quality_assurance',
        'PreventBackHistory',
    ],
], function () {
    // USER DASHBOARD ROUTES
    Route::controller(QualityAssuranceController::class)->group(function () {
        Route::get('/coco-folders', 'index')->name('qa.coco-folder');
        Route::get('/coco-images/{id}', 'images')->name('qa.coco-images');
        Route::get('/imagesList', 'imagesList')->name('qa.coco-imagesList');
        Route::post('/approve-status', 'approveStatus')->name('qa.coco-approveStatus');
    });
    // USER DASHBOARD ROUTES
    Route::controller(QualityAssuranceTextController::class)->group(function () {
        Route::get('/text-folders', 'index')->name('qa.text-folder');
        Route::get('/text-folder/{id}', 'textFolder')->name('qa.text-folder-by-id');
        Route::get('/text', 'text')->name('qa.text');

    });
    // USER DASHBOARD ROUTES
    Route::controller(\App\Http\Controllers\QualityAssuranceTextToTextController::class)->group(function () {
        Route::get('/text-to-text-folders', 'index')->name('qa.text-to-text-folder');
        Route::get('/text-to-text-folder/{id}', 'textFolder')->name('qa.text-to-text-folder-by-id');
        Route::get('/text-to-text', 'text')->name('qa.text_to_text');

    });
});

Route::get('/verify-code', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'verify_2fa_code'])
    ->name('complete.registration');
Route::post('/2fa', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'twoFactorAuthentication'])
    ->name('twoFactorAuthentication');


