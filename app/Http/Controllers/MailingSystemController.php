<?php

namespace App\Http\Controllers;

use App\Events\ProjectPermissionEvent;
use App\Http\Middleware\Unsubscribed;
use App\Mail\CompaignMailingNotification;
use App\Mail\MailingNotifications;
use App\Models\CompaignMail;
use App\Models\EmailCampaignReport;
use App\Models\unSubscribeMail;
use App\Models\User;
use App\Models\UserMailList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class MailingSystemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UserMailList::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>';

//                    $actionBtn .= '<a href="' . route("admin.images.folder.edit", $row["id"]) . '">
//                                            <i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group">
//                                            </i>
//                                        </a>';
//
//                    $actionBtn .= '<a class="deleteUserButton" id="' . $row["id"] . '" href="#">
//                            <i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User">
//                            </i>
//                        </a>';

                    $actionBtn .= '</div>';

                    return $actionBtn;

                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('description', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["id"] . '"">' . ucfirst($row["description"]) . '</span>';
                    return $name;
                })
                ->addColumn('users', function ($row) {
                    if (isset($row['user_ids'])) {
                        $userNames = [];

                        // Decode the JSON string to an array
                        $userIds = json_decode($row['user_ids']);

                        // Iterate over all user IDs and get the corresponding user names
                        foreach ($userIds as $userId) {
                            $user = User::find((int)$userId);
                            if ($user) {
                                $userNames[] = '<span class="font-weight-bold-' . $user->name . '">' . ucfirst($user->name) . '</span>';
                            } else {
                                $userNames[] = 'N/A';
                            }
                        }

                        // Join all usernames into a single string with commas
                        return implode(', ', $userNames);
                    }

                    return 'N/A'; // return Not Available if the key 'users' does not exist or the user was not found
                })
                ->addColumn('created-by', function ($row) {
                    if ($row["created_by"]) {

                        $user = User::find($row["created_by"]);
                        if ($user) {
                            $assignUser = '<span>' . $user->name . '</span>';
                            return $assignUser;
                        } else {
                            return $row["created_by"];
                        }
                    } else {
                        return $row["created_by"];
                    }
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->rawColumns(['actions', 'name', 'status', 'description', 'users', 'created-on', 'created-by'])
                ->make(true);
        }

        return view('admin.mailing-system.index');
    }

    public function create()
    {
        $languages = \Illuminate\Support\Facades\DB::table('transcribe_languages')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.mailing-system.create', compact('languages'));
    }

    public function fetchUsers(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $columns = $request->input('columns');
        $age = $request->input('age');
        $family = $request->input('family');
        $country = $request->input('country');
        $group = $request->input('group');
        $languages = $request->input('languages');
        $search = $request->input('q');

        $data = User::query();

        if ($search) {
            $data = $data->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

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

        if (!empty($age)) {
            $data = $data->whereHas('userInformation', function ($query) use ($age) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date, CURDATE()) = ?', [$age]);
            });
        }

        if (!empty($family)) {
            $data = $data->whereHas('userInformation', function ($query) use ($family) {
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


        $users = $data->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'users' => 'required'
        ]);

        $userMailList = new UserMailList();
        $userMailList->name = $request->name;
        $userMailList->description = $request->description;
        $userMailList->user_ids = json_encode($request->users);
        $userMailList->created_by = auth()->id();
        $userMailList->save();

        return redirect()->route('admin.mailing.system.index')->with('success', 'Mail list created successfully');
    }

    public function storeUserList(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'users' => 'required'
        ]);

        $userMailList = new UserMailList();
        $userMailList->name = $request->name;
        $userMailList->description = $request->description;
        $userMailList->user_ids = json_encode($request->users);
        $userMailList->created_by = auth()->id();
        $userMailList->save();

        return redirect()->route('admin.mailing.system.index')->with('success', 'Mail list created successfully');
    }

    public function indexCampaign(Request $request)
    {
        if ($request->ajax()) {
            $data = CompaignMail::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>';

//                    $actionBtn .= '<a href="' . route("admin.images.folder.edit", $row["id"]) . '">
//                                            <i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group">
//                                            </i>
//                                        </a>';
//
//                    $actionBtn .= '<a class="deleteUserButton" id="' . $row["id"] . '" href="#">
//                            <i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User">
//                            </i>
//                        </a>';

                    $actionBtn .= '</div>';

                    return $actionBtn;

                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('user_mail', function ($row) {
                    if (isset($row['user_mail_list_id'])) {
                        $userMail = UserMailList::where('id', $row['user_mail_list_id'])->first();
                        if ($userMail) {
                            $userNames = '<span class="font-weight-bold-' . $userMail->name . '">' . ucfirst($userMail->name) . '</span>';
                        } else {
                            $userNames = 'N/A';
                        }
                        return $userNames;
                    }
                    return 'N/A'; // return Not Available if the key 'users' does not exist or the user was not found
                })
                ->addColumn('created-by', function ($row) {
                    if ($row["created_by"]) {

                        $user = User::find($row["created_by"]);
                        if ($user) {
                            $assignUser = '<span>' . $user->name . '</span>';
                            return $assignUser;
                        } else {
                            return $row["created_by"];
                        }
                    } else {
                        return $row["created_by"];
                    }
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->rawColumns(['actions', 'name', 'status', 'users', 'created-on', 'created-by', 'user_mail'])
                ->make(true);
        }

        return view('admin..mailing-system.campaign.index');
    }

    public function createCampaign(Request $request)
    {
        $userLists = UserMailList::all();
        return view('admin..mailing-system.campaign.create', compact('userLists'));

    }

    public function previewEmailCampaign(Request $request)
    {
        Mail::to($request->preview_email)->send(new MailingNotifications($request->all()));

        return response()->json(['status' => 'success', 'message' => __('Preview mail send Successfully')]);
    }

    public function storeCampaign(Request $request)
    {
        $userList = UserMailList::where('id', $request->userList)->first();

        $compaign = CompaignMail::create([
            'name' => $request->name,
            'user_mail_list_id' => $request->userList,
            'mail_body' => $request->description,
            'created_by' => auth()->id()
        ]);
        if ($userList->user_ids) {
            foreach (json_decode($userList->user_ids) as $userId) {
                $user = User::where('id', $userId)->first();
                $unsubscribe = unSubscribeMail::whereIn('user_id', [$user->id])->first();
                if (!$unsubscribe) {
                    if ($user) {
                        Mail::to($user->email)->send(new CompaignMailingNotification($request->all(), $compaign->id, $user->email));
                    }
                }
            }
        }


        return response()->json(['status' => 'success', 'message' => __('Campaign mail send Successfully')]);
    }

    public function unSubscribe($email, $id)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            unSubscribeMail::create([
                'user_id' => $user->id,
                'campaign' => $id
            ]);
        }
        return redirect()->route('login')->with('success', __('Congratulation! You have successfully unsubscribed from our mailing list.'));
    }

    public function unsubscribeList(Request $request)
    {
        if ($request->ajax()) {
            $data = unSubscribeMail::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>';

//                    $actionBtn .= '<a href="' . route("admin.images.folder.edit", $row["id"]) . '">
//                                            <i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group">
//                                            </i>
//                                        </a>';
//
//                    $actionBtn .= '<a class="deleteUserButton" id="' . $row["id"] . '" href="#">
//                            <i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User">
//                            </i>
//                        </a>';

                    $actionBtn .= '</div>';

                    return $actionBtn;

                })
                ->addColumn('name', function ($row) {
                    if ($row["user_id"]) {
                        $user = User::where('id', $row["user_id"])->first();
                        if ($user) {
                            $name = '<span class="font-weight-bold-' . $user->name . '"">' . ucfirst($user->name) . '</span>';
                            return $name;
                        } else {
                            return $row["user_id"];
                        }
                        $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                        return $name;
                    } else {
                        return 'N/A';
                    }

                })
                ->addColumn('campaign', function ($row) {
                    if (isset($row['campaign'])) {
                        $userMail = CompaignMail::where('id', $row['campaign'])->first();
                        if ($userMail) {
                            $userNames = '<span class="font-weight-bold-' . $userMail->name . '">' . ucfirst($userMail->name) . '</span>';
                            return $userNames;
                        } else {
                            return 'N/A';
                        }
                    }
                    return 'N/A'; // return Not Available if the key 'users' does not exist or the user was not found
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->rawColumns(['actions', 'name', 'status', 'users', 'created-on', 'campaign', 'user_mail'])
                ->make(true);
        }
        return view('admin.mailing-system.unsubscribe.index');
    }

    public function trackOpen(Request $request)
    {
        $campaignId = $request->get('email_campaign_id');
        $userEmail = $request->get('user_email');
        $user = User::where('email', $userEmail)->first();

        // Check if an email open report already exists for the user and campaign
        $existingReport = EmailCampaignReport::where('email_campaign_id', $campaignId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingReport) {
            // Report for the user and campaign already exists, handle accordingly
            return response()->json(['status' => 'error', 'message' => 'Email open report already exists for this user and campaign.']);
        }

        // Save the email open report
        $report = new EmailCampaignReport();
        $report->email_campaign_id = $campaignId;
        $report->user_id = $user->id;
        $report->save();

        return response()->json(['status' => 'success', 'message' => __('Campaign mail send Successfully')]);
    }


    public function report(Request $request)
    {

        if ($request->ajax()) {
            $data = CompaignMail::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('user_mail', function ($row) {
                    if (isset($row['user_mail_list_id'])) {
                        $userMail = UserMailList::where('id', $row['user_mail_list_id'])->first();
                        if ($userMail) {
                            $userNames = '<span class="font-weight-bold-' . $userMail->name . '">' . ucfirst($userMail->name) . '</span>';
                        } else {
                            $userNames = 'N/A';
                        }
                        return $userNames;
                    }
                    return 'N/A'; // return Not Available if the key 'users' does not exist or the user was not found
                })
                ->addColumn('created-by', function ($row) {
                    if ($row["created_by"]) {

                        $user = User::find($row["created_by"]);
                        if ($user) {
                            $assignUser = '<span>' . $user->name . '</span>';
                            return $assignUser;
                        } else {
                            return $row["created_by"];
                        }
                    } else {
                        return $row["created_by"];
                    }
                })
                ->addColumn('total_user', function ($row) {
                    $userMailList = UserMailList::where('id', $row["user_mail_list_id"])->first();
                    if ($userMailList) {
                        $userIds = $userMailList->user_ids;
                        $idArray = explode(',', $userIds);
                        $userCount = count($idArray);

                        $userMailList = '<span>' . $userCount . '</span>';
                        return $userMailList;
                    } else {
                        return 'N/A';
                    }
                    $report = '<span>' . $report . '</span>';
                    return $report;
                })
                ->addColumn('ready_users', function ($row) {
                    $report = EmailCampaignReport::where('email_campaign_id', $row["id"])->count();
                    $report = '<span>' . $report . '</span>';
                    return $report;
                })
                ->addColumn('unsubscribeUser', function ($row) {
                    $unsubscribeUser = unSubscribeMail::where('campaign', $row["id"])->count();
                    $unsubscribeUser = '<span>' . $unsubscribeUser   . '</span>';
                    return $unsubscribeUser;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->rawColumns(['actions', 'name', 'status', 'users', 'created-on', 'created-by','total_user','ready_users','unsubscribeUser', 'user_mail'])
                ->make(true);
        }
        return view('admin.mailing-system.report.index');
    }
}
