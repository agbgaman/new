<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProjectPermissionEvent;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\Referral;
use App\Models\TranscribeResult;
use App\Models\UserInformation;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\Statistics\UserPaymentsService;
use App\Services\Statistics\UserUsageYearlyService;
use App\Services\Statistics\UserRegistrationYearlyService;
use App\Services\Statistics\UserRegistrationMonthlyService;
use App\Models\Subscriber;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use DataTables;
use Cache;


class AdminUserController extends Controller
{
    /**
     * Display user management dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $registration_yearly = new UserRegistrationYearlyService($year);
        $registration_monthly = new UserRegistrationMonthlyService($year, $month);

        $user_data_year = [
            'total_free_tier' => $registration_yearly->getTotalFreeRegistrations(),
            'total_paid_tier' => $registration_yearly->getTotalPaidRegistrations(),
            'total_users' => $registration_yearly->getTotalUsers(),
            'total_paid_users' => $registration_yearly->getTotalPaidUsers(),
            'top_countries' => $this->getTopCountries(),
        ];

        $chart_data['free_registration_yearly'] = json_encode($registration_yearly->getFreeRegistrations());
        $chart_data['paid_registration_yearly'] = json_encode($registration_yearly->getPaidRegistrations());
        $chart_data['current_registered_users'] = json_encode($registration_monthly->getRegisteredUsers());
        $chart_data['user_countries'] = json_encode($this->getAllCountries());


        $cachedUsers = json_decode(Cache::get('isOnline', []), true);
        $users_online = count($cachedUsers);

        $users_today = User::whereNotNull('last_seen')->whereDate('last_seen', Carbon::today())->count();

        return view('admin.users.dashboard.index', compact('chart_data', 'user_data_year', 'users_online', 'users_today'));
    }


    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function listUsers(Request $request)
    {
        if ($request->ajax()) {
            $start_date     = $request->input('created_on_from');
            $end_date       = $request->input('created_on_to');
            $columns        = $request->input('database_columns');
            $family         = $request->input('family');
            $country        = $request->input('country');
            $group          = $request->input('group');
            $languages      = $request->input('languages');
            $city           = $request->input('city');
            $ageL           = $request->input('ageL');
            $ageG           = $request->input('ageG');


            $data = User::query();

            if (!empty($start_date) && !empty($columns)) {
                $data = $data->whereDate($columns, '>=', $start_date);
            } elseif (!empty($start_date)) {
                $data = $data->whereDate('last_seen', '>=', $start_date);
            }

            if (!empty($end_date) && !empty($columns)) {
                $data = $data->whereDate($columns, '<=', $end_date);
            } elseif (!empty($end_date) && !empty($columns)) {
                $data = $data->whereDate('last_seen', '<=', $end_date);
            }


            if (!empty($ageL) && !empty($ageG)) {
                $data = $data->whereHas('userInformation', function($query) use ($ageL, $ageG) {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, date, CURDATE()) BETWEEN ? AND ?', [$ageL, $ageG]);
                });
            }


            if (!empty($family)) {
                $data = $data->whereHas('userInformation', function($query) use ($family) {
                    $query->where('familyParticipation', $family);
                });
            }


            if (!empty($country)) {
                $data = $data->where('country', $country);
            }

            if (!empty($group)) {
                $data = $data->where('group', $group);
            }
            if (!empty($languages)) {
                // If $languages is a string, convert it to an array
                if (is_string($languages)) {
                    // Assuming it's a comma-separated string of ids
                    $languages = explode(',', $languages);
                }

                $data = $data->whereNotNull('language')->where(function ($query) use ($languages) {
                    foreach ($languages as $language) {
                        $query->orWhereJsonContains('language', $language);
                    }
                });
            }


            $data = $data->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<div class="form-check">
                                <input class="form-check-input" type="checkbox" id="' . $row["id"] . '">
                            </div>';
                })
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a href="' . route("admin.user.storage", $row["id"]) . '"><i class="fa-solid fa-puzzle table-action-buttons request-action-button" title="Add Credits"></i></a>
                                        <a href="' . route("admin.user.login", $row["id"]) . '"><i class="fa-solid fa-sign-in-alt table-action-buttons login-action-button" title="Login as User"></i></a>
                                        <a href="' . route("admin.user.show", $row["id"]) . '"><i class="fa-solid fa-clipboard-user table-action-buttons view-action-button" title="View User"></i></a>
                                        <a href="' . route("admin.user.edit", $row["id"]) . '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
                                        <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('user', function ($row) {
                    if ($row['profile_photo_path']) {
                        $path = asset($row['profile_photo_path']);
                        $user = '<a href="' . route("admin.user.show", $row["id"]) . '">
                                     <div class="d-flex">
                                        <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                        <div class="widget-user-name"><span class="font-weight-bold">' . $row['name'] . '</span><br><span class="text-muted">' . $row["email"] . '</span></div>
                                     </div>
                                 </a>';
                    } else {
                        $path = URL::asset('img/users/avatar.png');
                        $user = '<a href="' . route("admin.user.show", $row["id"]) . '">
                                <div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">' . $row['name'] . '</span><br><span class="text-muted">' . $row["email"] . '</span></div>
                                </div>
                             </a>';
                    }
                    return $user;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span class="font-weight-bold">' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('last-seen-on', function ($row) {
                    $last_seen = '<span class="font-weight-bold">' . \Carbon\Carbon::parse($row['last_seen'])->format('Y-m-d H:i:s') . '</span>';
                    return $last_seen;
                })
                ->addColumn('custom-status', function ($row) {
                    $custom_status = '<span class="cell-box user-' . $row["status"] . '">' . str_replace('_', ' ', ucfirst($row["status"])) . '</span>';
                    return $custom_status;
                })
                ->addColumn('custom-group', function ($row) {
                    $custom_group = '<span class="cell-box user-group-' . $row["group"] . '">' . str_replace('_', ' ', ucfirst($row["group"])) . '</span>';
                    return $custom_group;
                })
                ->addColumn('custom-country', function ($row) {
                    $custom_country = '<span class="font-weight-bold">' . $row["country"] . '</span>';
                    return $custom_country;
                })
                ->addColumn('custom-characters', function ($row) {
                    $custom_characters = '<span class="font-weight-bold">' . number_format($row["available_chars"] + $row['available_chars_prepaid'], 0, 2) . '</span>';
                    return $custom_characters;
                })
                ->addColumn('custom-minutes', function ($row) {
                    $custom_minutes = '<span class="font-weight-bold">' . number_format($row["available_minutes"] + $row['available_minutes_prepaid'], 0, 2) . '</span>';
                    return $custom_minutes;
                })
                ->rawColumns(['actions', 'custom-status','checkbox', 'custom-group', 'created-on', 'user', 'custom-country', 'custom-characters', 'custom-minutes', 'last-seen-on'])
                ->make(true);
        }
        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.users.list.index',compact('languages'));
    }


    /**
     * Display user activity
     *
     * @return \Illuminate\Http\Response
     */
    public function activity(Request $request)
    {
        $result = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->whereNotNull('sessions.user_id')
            ->select('sessions.ip_address', 'sessions.user_agent', 'sessions.last_activity', 'users.email', 'users.group')
            ->orderBy('sessions.last_activity', 'desc')
            ->get()->toArray();

        return view('admin.users.activity.index', compact('result'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.list.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::min(8)],
            'role' => 'required',
            'currency' => 'required',
            'country' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country,
            'job_role' => $request->job_role,
            'phone_number' => $request->phone_number,
            'company' => $request->company,
            'website' => $request->website,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'currency' => $request->currency,
            'language' => config('tts.default_language'),
            'voice' => config('tts.default_voice'),
            'language_file' => config('stt.language.file'),
            'language_live' => config('stt.language.live'),
        ]);

        $user->syncRoles($request->role);
        $user->status = 'active';
        $user->group = $request->role;
        $user->available_chars = config('tts.free_chars');
        $user->available_minutes = config('stt.free_minutes');
        $user->referral_id = strtoupper(Str::random(15));
        $user->save();

        return redirect()->back()->with('success', __('Congratulation! New user has been created'));
    }


    /**
     * Display the details of selected user
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $year = $request->input('year', date('Y'));

        $payments_yearly = new UserPaymentsService($year);
        $usage_yearly = new UserUsageYearlyService($year);

        $user_data_year = [
            'total_payments' => $payments_yearly->getTotalPayments($user->id),
            'total_standard_chars' => $usage_yearly->getTotalStandardCharsUsage($user->id),
            'total_neural_chars' => $usage_yearly->getTotalNeuralCharsUsage($user->id),
            'total_audio_files' => $usage_yearly->getTotalAudioFiles($user->id),
            'total_listen_modes' => $usage_yearly->getTotalListenModes($user->id),
            'total_minutes' => $usage_yearly->getTotalMinutes($user->id),
            'total_words' => $usage_yearly->getTotalWords($user->id),
            'total_file_transcribe' => $usage_yearly->getTotalFileTranscribe($user->id),
            'total_recording_transcribe' => $usage_yearly->getTotalRecordingTranscribe($user->id),
            'total_live_transcribe' => $usage_yearly->getTotalLiveTranscribe($user->id),
        ];

        $chart_data['payments'] = json_encode($payments_yearly->getPayments($user->id));
        $chart_data['standard_chars'] = json_encode($usage_yearly->getStandardCharsUsage($user->id));
        $chart_data['neural_chars'] = json_encode($usage_yearly->getNeuralCharsUsage($user->id));
        $chart_data['file_minutes'] = json_encode($usage_yearly->getFileMinutesUsage($user->id));
        $chart_data['record_minutes'] = json_encode($usage_yearly->getRecordMinutesUsage($user->id));
        $chart_data['live_minutes'] = json_encode($usage_yearly->getLiveMinutesUsage($user->id));

        if (auth()->user()->hasActiveSubscription()) {
            $subscription = Subscriber::where('user_id', $user->id)->where('status', 'Active')->first();
        } else {
            $subscription = false;
        }

        $user_subscription = ($subscription) ? SubscriptionPlan::where('id', $user->plan_id)->first() : '';

        $characters = $user->available_chars;

        if ($subscription) {
            $plan = SubscriptionPlan::where('id', $user->plan_id)->first();
            $total_characters = $plan->characters;
        } else {
            $total_characters = config('tts.free_chars');
        }

        $progress = [
            'subscription' => ($user->available_chars / $total_characters) * 100,
        ];
        // Applied Projects
        $applied_projects = ProjectApplication::where('user_id', $user->id)->with('projects')->get();
        // refferals
        $referrals = Referral::where('referrer_id', $user->id)->with('referred')->latest()->get();

        // payment
        $invoices = Invoice::where('user_id', $user->id)->latest()->get();
        // languages
        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();
        // INFORMATION
        $information = UserInformation::where('user_id', $user->id)->first();

        return view('admin.users.list.show', compact('user', 'chart_data', 'user_data_year', 'user_subscription', 'characters', 'total_characters', 'progress','languages','applied_projects','invoices','referrals','information'));
    }


    /**
     * Show the form for editing the specified user
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.users.list.edit', compact('user', 'languages'));
    }


    /**
     * Show users storage capacity
     */
    public function storage(User $user)
    {
        return view('admin.users.list.increase', compact('user'));
    }


    /**
     * Change user storage capacity
     */
    public function increase(Request $request, User $user)
    {
        $request->validate([
            'characters' => 'integer',
            'minutes' => 'integer',
        ]);

        $user->available_chars_prepaid = $user->available_chars_prepaid + request('characters');
        $user->available_minutes_prepaid = $user->available_minutes_prepaid + request('minutes');
        $user->save();

        return redirect()->back()->with('success', __('Prepaid credits has been increased successfully'));
    }


    /**
     * Update selected user data
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $user->update(request()->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'job_role' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'phone_number' => 'nullable|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'project_permission' => 'nullable|string',
            'currency' => 'required|string'
        ]));

        return redirect()->back()->with('success', __('User profile was successfully updated'));
    }

    /**
     * Change user group/status/password
     */
    public function change(Request $request, User $user)
    {
        $request->validate([
            'password' => ['nullable', 'confirmed', Rules\Password::min(8)],
            'status' => 'required',
            'group' => 'required'
        ]);

        $user->removeRole($user->group);
        $user->assignRole($request->group);
        $user->status = $request->status;
        $user->group = $request->group;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->back()->with('success', __('User data was successfully updated'));
    }


    /**
     * Delete selected user.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $user = User::find(request('id'));

            if ($user) {

                $user->delete();

                return response()->json('success');

            } else {
                return response()->json('error');
            }
        }
    }


    /**
     * Show list of all countries
     */
    public function getAllCountries()
    {
        $countries = User::select(DB::raw("count(id) as data, country"))
            ->groupBy('country')
            ->orderBy('data')
            ->pluck('data', 'country');

        return $countries;
    }


    /**
     * Show top 30 countries
     */
    public function getTopCountries()
    {
        $countries = User::select(DB::raw("count(id) as data, country"))
            ->groupBy('country')
            ->orderByDesc('data')
            ->pluck('data', 'country')
            ->take(30)
            ->toArray();

        return $countries;
    }

    public function projectPermission(Request $request)
    {
        if ($request->ajax()) {
            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $data = ProjectApplication::query();


            if (!empty($end_date)) {
                $data->whereDate('created_at', '<=', $end_date);
            }
            if (!empty($start_date)) {
                $data->whereDate('created_at', '>=', $start_date);
            }
            if (!empty($request->project_id)) {
                $data->where('project_id', $request->project_id);
            }
            if (!empty($request->status)) {
                $data->where('status', $request->status);
            }

            $data = $data->get();

            return \Yajra\DataTables\Facades\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn =
                        '<div>
                        <a class="agreeTranscriptionButton" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Activate Permission"></i></a>
                        <a class="disagreeTranscriptionButton" id="' . $row["id"] . '" href="#"><i class="fa fa-close table-action-buttons delete-action-button" title="Deactivate Permission"></i></a>';

                    if (!empty($row["contract_form"])) {
                        $actionBtn .= '<a class="downloadButton" href="' . route('admin.user.permission.request.contract-form.pdf', $row["id"]) . '"><i class="fa fa-download table-action-buttons download-action-button" title="Download Contract Form PDF"></i></a>';
                    }

                    if (!empty($row["appliedForm"])) {
                        $actionBtn .= '<a class="downloadButton" href="' . route('admin.user.permission.request.pdf', $row["id"]) . '"><i class="fa fa-download table-action-buttons download-action-button" title="Download Consent Form PDF"></i></a>';
                    }

                    $actionBtn .= '</div>';

                    return $actionBtn;

                })
                ->addColumn('name', function ($row) {
                    if ($row['user_id']) {
                        $user = User::where('id', $row['user_id'])->first();
                        if ($user) {
                            $user_name = '<span class="font-weight-bold-' . $user->name . '">' . ucfirst($user->name) . '</span>';
                            return $user_name;
                        } else {

                        }
                        return $row['user_id'];
                    }
                    return $row['user_id'];
                })
                ->addColumn('project', function ($row) {
                    if ($row['project_id']) {
                        $project = Project::where('id', $row['project_id'])->first();
                        if ($project) {
                            $project_name = '<span class="font-weight-bold-' . $project->name . '">' . ucfirst($project->name) . '</span>';
                            return $project_name;
                        } else {
                            return $row['user_id'];
                        }
                    }
                    return $row['user_id'];
                })
                ->addColumn('status', function ($row) {
                    $project_name = '<span class="cell-box transcribe-' . strtolower(str_replace(' ', '_', $row['status'])) . '">' . ucfirst($row['status']) . '</span>';
                    return $project_name;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'Y-m-d H:i:s') . '</span>';
                    return $created_on;
                })
                ->addColumn('approved-on', function ($row) {
                    if ($row['read_at']) {
                        $datetime = new \DateTime($row['read_at']);
                        $created_on = '<span>' . $datetime->format('Y-m-d H:i:s A') . '</span>';
                        return $created_on;
                    } else {
                        return null;
                    }
                })
                ->rawColumns(['actions', 'name', 'created-on', 'project', 'status', 'approved-on'])
                ->make(true);
        }
        $projects = Project::all();
        return view('admin.users.permission.index', compact('projects'));
    }

    public function projectPermissionApproved(Request $request)
    {
        if ($request->ajax()) {

            $projectApplication = ProjectApplication::where('id', request('id'))->firstOrFail();

//            if ($projectApplication->status == 'Approved') {
//                return response()->json('Applied');
//            }
            $project = Project::where('id', $projectApplication->project_id)->first();
            $user = User::where('id', $projectApplication->user_id)->first();

            if (is_null($user->project_permission) || $user->project_permission == '') {
                $permission = array();
            } else {
                $permission = json_decode($user->project_permission, true);
            }

            if (!array_key_exists($project->id, $permission) || $permission[$project->id] !== true) {

                    $permission[$project->id] = true;

                    $user->update(['project_permission' => json_encode($permission)]);
            }
            $projectApplication->update([
                'status' => 'Approved',
                'read_at' => Carbon::now()
            ]);
            $subject = "Your Project Permission ". $project->name ." is Approved Please check";

            event(new ProjectPermissionEvent($user, $subject));



            return response()->json('success');
        }
    }

    /**
     * Disagree Project Permission Request by Admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function projectPermissionDisagree(Request $request)
    {
        if ($request->ajax()) {

            $projectApplication = ProjectApplication::where('id', request('id'))->firstOrFail();

            if ($projectApplication->status == 'Failed') {
                return response()->json('Failed');
            }
            $project = Project::where('id', $projectApplication->project_id)->first();
            $user = User::where('id', $projectApplication->user_id)->first();

            if (is_null($user->project_permission)) {
                $permission = array();
            } else {
                $permission = json_decode($user->project_permission, true);
            }
            if (array_key_exists($project->id, $permission) && $permission[$project->id] !== false) {

                $permission[$project->id] = false;

                $user->update(['project_permission' => json_encode($permission)]);
            }
            $projectApplication->update([
                'status' => 'Failed',
                'read_at' => Carbon::now()
            ]);
            $subject = "Your Project Permission ". $project->name ." is Failed.";

            event(new ProjectPermissionEvent($user, $subject));

            return response()->json('success');
        }
    }

    /**
     * Admin Login as User
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAsUser($id)
    {
        // Perform any necessary checks here like ensuring the current user is an admin
        // Or any other permissions checks you may need

        // Then, log in as the user
        Auth::loginUsingId($id);

        // Redirect to user's dashboard or wherever is appropriate
        return redirect()->route('user.dashboard');
    }

    /**
     * Admin can download consent form of user for Project Permission
     *
     * @param $id
     * @return null
     */
    public function projectPermissionPDF($id)
    {
        $projectApplication = ProjectApplication::where('id', $id)->firstOrFail();


        // Generate the PDF content
        $pdfContent = view('admin.users.permission.project_permission', compact('projectApplication'))->render();

        // Create a new Dompdf instance
        $dompdf = new Dompdf();


        // Load the PDF content
        $dompdf->loadHtml($pdfContent);

        // (Optional) Set any additional options
        $options = new Options();
        $options->setIsRemoteEnabled(true); // Enable loading images or CSS from remote URLs
        $dompdf->setOptions($options);

        // Render the PDF
        $dompdf->render();

        // Set the response headers for downloading the PDF
        $response = $dompdf->stream('project_permission.pdf', ['Attachment' => true]);

        return $response;
    }

    public function projectPermissionContractFormPDF($id)
    {
        $projectApplication = ProjectApplication::where('id', $id)->firstOrFail();


        // Generate the PDF content
        $pdfContent = view('admin.users.permission.project_permission_contract_form', compact('projectApplication'))->render();

        // Create a new Dompdf instance
        $dompdf = new Dompdf();


        // Load the PDF content
        $dompdf->loadHtml($pdfContent);

        // (Optional) Set any additional options
        $options = new Options();
        $options->setIsRemoteEnabled(true); // Enable loading images or CSS from remote URLs
        $dompdf->setOptions($options);

        // Render the PDF
        $dompdf->render();

        // Set the response headers for downloading the PDF
        $response = $dompdf->stream('project_permission.pdf', ['Attachment' => true]);

        return $response;
    }
    public function SMS_Verification(Request $request){
        $user = User::where('id', $request->user_id)->first();
        $user->update([
            'phone_number_verified_at' => now()
        ]);
        return response()->json('success');
    }
}
