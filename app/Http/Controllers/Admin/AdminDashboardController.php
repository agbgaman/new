<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Services\Statistics\CostsService;
use App\Services\Statistics\PaymentsService;
use App\Services\Statistics\RegistrationService;
use App\Services\Statistics\UserRegistrationMonthlyService;
use App\Services\Statistics\VoiceoverService;
use App\Services\Statistics\TranscribeService;
use Illuminate\Http\Request;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $cost = new CostsService($year, $month);
        $payment = new PaymentsService($year, $month);
        $registration = new RegistrationService($year, $month);
        $user_registration = new UserRegistrationMonthlyService($year, $month);
        $tts = new VoiceoverService($year, $month);
        $stt = new TranscribeService($year, $month);

        $total_data_monthly = [
            'new_users_current_month' => $registration->getNewUsersCurrentMonth(),
            'new_users_past_month' => $registration->getNewUsersPastMonth(),
            'new_subscribers_current_month' => $registration->getNewSubscribersCurrentMonth(),
            'new_subscribers_past_month' => $registration->getNewSubscribersPastMonth(),
            'income_current_month' => $payment->getTotalPaymentsCurrentMonth(),
            'income_past_month' => $payment->getTotalPaymentsPastMonth(),
            'spending_current_month' => $cost->getTotalCostForTextCurrentMonth() + $cost->getTotalCostForSecondsCurrentMonth(),
            'spending_past_month' => $cost->getTotalCostForTextPastMonth() + $cost->getTotalCostForSecondsPastMonth(),
            'free_chars' => $tts->getTotalFreeCharsUsageMonthly(),
            'paid_chars' => $tts->getTotalPaidCharsUsageMonthly(),
            'purchased_chars' => $payment->getTotalPurchasedCharactersCurrentMonth(),
            'audio_files' => $tts->getTotalAudioFilesMonthly(),
            'free_minutes' => $stt->getTotalFreeMinutesUsageMonthly(),
            'paid_minutes' => $stt->getTotalPaidMinutesUsageMonthly(),
            'purchased_minutes' => $payment->getTotalPurchasedMinutesCurrentMonth(),
            'transcribe_tasks' => $stt->getTotalTasksMonthly(),
        ];

        $total_data_yearly = [
            'total_new_users' => $registration->getNewUsersCurrentYear(),
            'total_new_subscribers' => $registration->getNewSubscribersCurrentYear(),
            'total_income' => $payment->getTotalPaymentsCurrentYear(),
            'total_spending' => $cost->getTotalCostForSecondsCurrentYear() + $cost->getTotalCostForTextCurrentYear(),
        ];

        $chart_data['total_new_users'] = json_encode($registration->getAllUsers());
        $chart_data['monthly_new_users'] = json_encode($user_registration->getRegisteredUsers());
        $chart_data['total_income'] = json_encode($payment->getPayments());
//        $chart_data['remarks_data'] = json_encode($payment->getRejectionReason());
        $percentage['users_current'] = json_encode($registration->getNewUsersCurrentMonth());
        $percentage['users_past'] = json_encode($registration->getNewUsersPastMonth());
        $percentage['subscribers_current'] = json_encode($registration->getNewSubscribersCurrentMonth());
        $percentage['subscribers_past'] = json_encode($registration->getNewSubscribersPastMonth());
        $percentage['income_current'] = json_encode($payment->getTotalPaymentsCurrentMonth());
        $percentage['income_past'] = json_encode($payment->getTotalPaymentsPastMonth());
        $percentage['spending_current'] = json_encode($cost->getTotalCostForTextCurrentMonth() + $cost->getTotalCostForSecondsCurrentMonth());
        $percentage['spending_past'] = json_encode($cost->getTotalCostForTextPastMonth() + $cost->getTotalCostForSecondsPastMonth());
        $percentage['free_current'] = json_encode($tts->getTotalFreeCharsUsageMonthly());
        $percentage['free_past'] = json_encode($tts->getTotalFreeCharsUsagePastMonth());
        $percentage['paid_current'] = json_encode($tts->getTotalPaidCharsUsageMonthly());
        $percentage['paid_past'] = json_encode($tts->getTotalPaidCharsUsagePastMonth());
        $percentage['purchased_current'] = json_encode($payment->getTotalPurchasedCharactersCurrentMonth());
        $percentage['purchased_past'] = json_encode($payment->getTotalPurchasedCharactersPastMonth());
        $percentage['audio_current'] = json_encode($tts->getTotalAudioFilesMonthly());
        $percentage['audio_past'] = json_encode($tts->getTotalAudioFilesPastMonth());
        $percentage['free_current_minutes'] = json_encode($stt->getTotalFreeMinutesUsageMonthly());
        $percentage['free_past_minutes'] = json_encode($stt->getTotalFreeMinutesUsagePastMonth());
        $percentage['paid_current_minutes'] = json_encode($stt->getTotalPaidMinutesUsageMonthly());
        $percentage['paid_past_minutes'] = json_encode($stt->getTotalPaidMinutesUsagePastMonth());
        $percentage['purchased_current_minutes'] = json_encode($payment->getTotalPurchasedMinutesCurrentMonth());
        $percentage['purchased_past_minutes'] = json_encode($payment->getTotalPurchasedMinutesPastMonth());
        $percentage['task_current'] = json_encode($stt->getTotalTasksMonthly());
        $percentage['task_past'] = json_encode($stt->getTotalTasksPastMonth());


        $result = User::latest()->take(5)->get();
        $transaction = User::select('users.id', 'users.email', 'users.name', 'users.profile_photo_path', 'payments.*')->join('payments', 'payments.user_id', '=', 'users.id')->orderBy('payments.created_at', 'DESC')->take(5)->get();
        $transaction_count = User::select('users.id', 'users.email', 'users.name', 'users.profile_photo_path', 'payments.*')->join('payments', 'payments.user_id', '=', 'users.id')->count();
        $projects = Project::all();
        return view('admin.dashboard.index', compact('total_data_monthly', 'total_data_yearly', 'chart_data', 'percentage', 'result', 'transaction', 'transaction_count', 'projects'));
    }

    public function remarks(Request $request)
    {

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $payment = new PaymentsService($year, $month);

        return json_encode($payment->getRejectionReason($request->project_id));

    }

    public function performer(Request $request)
    {

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $payment = new PaymentsService($year, $month);

        return json_encode($payment->getPerformerRejectionReason($request->project_id));

    }

    public function qaData(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $payment = new PaymentsService($year, $month);

        return json_encode($payment->getQAPerformance($request->project_id));

    }

    public function permission()
    {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->project_permission) {
                $permissions = json_decode($user->project_permission, true);

                foreach ($permissions as $key => $permission) {
                    $project = Project::where('name', $key)->first();
                    if ($project) {

                        // Remove the old entry from permissions
                        unset($permissions[$key]);

                        // Add a new entry to permissions, using the project ID as the key
                        $permissions[$project->id] = $permission;

                        $user->update(['project_permission' => json_encode($permissions)]);
                    }
                }
            }
        }
        dd("done");
    }

}
