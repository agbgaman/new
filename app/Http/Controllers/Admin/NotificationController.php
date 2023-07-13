<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NotificationEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Notifications\GeneralNotification;
use App\Notifications\WhatsappNotification;
use App\Models\User;
use GuzzleHttp\Client;
use DataTables;

class NotificationController extends Controller
{
    /**
     * Display all general notifications
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $query = User::with(['notifications' => function ($query) use ($start_date, $end_date) {
                $query->where('type', 'App\Notifications\GeneralNotification')->orderBy('created_at', 'desc');

                if (!empty($start_date)) {
                    $start_date = Carbon::createFromFormat('Y-m-d\TH:i', $start_date)->format('Y-m-d H:i:s');
                    $query->where('created_at', '>=', $start_date);
                }

                if (!empty($end_date)) {
                    $end_date = Carbon::createFromFormat('Y-m-d\TH:i', $end_date)->format('Y-m-d H:i:s');
                    $query->where('created_at', '<=', $end_date);
                }
                $query->orderBy('created_at', 'desc');

            }]);

            $data = $query->get()->flatMap(function ($user) {
                return $user->notifications;
            })->all();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                    <a href="' . route("admin.notifications.show", $row["id"]) . '">
                                        <i class="fa-solid fa-bell-exclamation table-action-buttons view-action-button" title="View Notification"></i>
                                    </a>
                                    <a class="deleteNotificationButton" id="' . $row["id"] . '" href="#">
                                        <i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Notification"></i>
                                    </a>
                                </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s') . '</span>';
                    return $created_on;
                })
                ->addColumn('subject', function ($row) {
                    $created_on = $row["data"]["subject"];
                    return $created_on;
                })
                ->addColumn('user-action', function ($row) {
                    $user_action = '<span class="font-weight-bold">' . $row["data"]["action"] . '</span>';
                    return $user_action;
                })
                ->addColumn('notification-type', function ($row) {
                    $notification = '<span class="cell-box notification-' . strtolower($row["data"]["type"]) . '">' . $row["data"]["type"] . '</span>';
                    return $notification;
                })
                ->addColumn('users', function ($row) {
                    $users = '';
                    if (isset($row['data']['user'])) {
                        if (!($row['data']['user'] == 'all')) {
                            $userCount = 0;
                            foreach ($row['data']['user'] as $userId) {
                                if ($userCount == 3) break; // exit loop after 3 users
                                $user = User::find($userId);
                                if ($user) { // only add user to the string if user is found
                                    $users .= $user->name . ', ';
                                    $userCount++;
                                }
                            }
                            return rtrim($users, ', '); // remove trailing comma and space
                        } else {
                            $users .= "All users";
                            return $users;
                        }
                    } else {
                        $users .= "No User";
                        return $users;
                    }
                })
                ->addColumn('user_id', function ($row) {
                    $userId = '';
                    if (isset($row['data']['user_id'])) {
                        $userId = $row['data']['user_id'];
                        $user = User::find($userId);
                        if ($user) { // only add user to the string if user is found
                            return $user->name;
                        }
                    } else {
                        $userId .= "No User";
                        return $userId;
                    }
                })
                ->rawColumns(['actions', 'notification-type', 'created-on', 'subject', 'user-action', 'users','user_id'])
                ->make(true);

        }

        return view('admin.notification.index');
    }


    /**
     * Display all system notifications
     */
    public function system(Request $request)
    {
        if ($request->ajax()) {
            $data = Auth::user()->notifications()->where('type', '<>', 'App\Notifications\GeneralNotification')->orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a href="' . route("admin.notifications.systemShow", $row["id"]) . '"><i class="fa-solid fa-bell table-action-buttons view-action-button" title="View Result"></i></a>
                                        <a class="deleteNotificationButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Result"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s') . '</span>';
                    return $created_on;
                })
                ->addColumn('read-on', function ($row) {
                    if (!is_null($row["read_at"])) {
                        $read_on = '<span>' . date_format($row["read_at"], 'd M Y H:i:s') . '</span>';
                        return $read_on;
                    } else {
                        return '<span>' . $row["read_at"] . '</span>';
                    }

                })
                ->addColumn('subject', function ($row) {
                    $created_on = '<span>' . $row["data"]["subject"] . '</span>';
                    return $created_on;
                })
                ->addColumn('email', function ($row) {
                    $email = '<span class="font-weight-bold">' . $row["data"]["email"] . '</span>';
                    return $email;
                })
                ->addColumn('country', function ($row) {
                    $country = '<span>' . $row["data"]["country"] . '</span>';
                    return $country;
                })
                ->addColumn('notification-type', function ($row) {
                    if ($row["data"]["type"] == "new-user") {
                        $type = "New User";
                    } elseif ($row["data"]["type"] == "new-payment") {
                        $type = "New Payment";
                    } elseif ($row["data"]["type"] == "payout-request") {
                        $type = "New Payout Request";
                    } elseif ($row["data"]["type"] == "project-updates") {
                        $type = "Project Updates";
                    }
                    $notification = '<span class="cell-box notification-' . strtolower($row["data"]["type"]) . '">' . $type . '</span>';
                    return $notification;
                })
                ->rawColumns(['actions', 'notification-type', 'created-on', 'subject', 'read-on', 'email', 'country'])
                ->make(true);

        }

        return view('admin.notification.system');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        return view('admin.notification.create', compact('users'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'notification-type' => 'required|string',
            'notification-action' => 'required|string',
            'notification-subject' => 'required|string',
            'notification-message' => 'required|string',
            'notification-to' => 'required',
        ]);

        $users = array();
        $usersId = array();
        if (isset($request->user) && count($request->user) > 0) {
            $users = User::whereIn('id', $request->user)->get();
            $usersId = $request->user;
        } else {
            $users = User::all();
            $usersId = 'all';
        }
        $notification = [
            'type'      => htmlspecialchars(request('notification-type')),
            'action'    => htmlspecialchars(request('notification-action')),
            'subject'   => htmlspecialchars(request('notification-subject')),
            'message'   => nl2br(htmlspecialchars(request('notification-message'))),
            'user'      => $usersId,
            'user_id'   => \auth()->user()->id,
        ];
        if (request('notification-to')) {
            foreach (request('notification-to') as $notify) {


                // Notification to WhatsApp
                if ($notify == "WhatsApp") {
                    $this->sendWhatsAppMessage($users, $notification);
                }

                // Notification to Panel
                if ($notify == "Panel") {
                    Notification::send($users, new GeneralNotification($notification));
                }

                // notification emails
                if ($notify == "email") {
                    $this->email($users, $notification);
                }

            }
        }

        return redirect()->route('admin.notifications')->with("success", __("New notification has been created successfully"));
    }

    public function sendWhatsAppMessage($users, $notification)
    {

        $client = new Client();

        $url = 'https://graph.facebook.com/v16.0/102296272843449/messages';
        $headers = [
            'Authorization' => 'Bearer EAAPIgr9W8DUBAKQSqfRgnvRUgYUEJFMZAeECsi7YiNIkogV3FEMCVb5NkhLdZCdP2S2iAEoWt1QqzABKQWek49cycscdiyAB3JZABAKvtKVrOCLtMwASO5BBZAvOyFDtMllHnADkgZCSPrpVmzMixrGL0trgdILJuv7ISUVCM59CbdsE9JJGS17WMLSUjzaDhGYQgqphMIqnYg4W3ZBxOnc17FFmNDpUYZD',
            'Content-Type' => 'application/json',
        ];
        $data = [
            'messaging_product' => 'whatsapp',
            'to' => '923086000611',
            'type' => 'template',
            'template' => [
                'name' => 'dashapp',
                'language' => [
                    'policy' => 'deterministic',
                    'code' => 'en_US',
                ],
                'components' => [
                    [
                        'type' => 'header',
                        'parameters' => [
                            ['type' => 'text', 'text' => 'John Doe'],
                        ],
                    ],
                ],
            ],
        ];


//        try {
        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);
        dd($response);
        // Handle the response
        if ($response->getStatusCode() == 200) {
            // The message was sent successfully
        } else {
            // The message sending failed
        }
//        } catch (Exception $e) {
//            // Handle any errors here
//        }
    }

    /**
     * Send a inviation email
     */
    public function email($users, $notification)
    {
        try {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NotificationEmail($user, $notification));
            }


            if (Mail::flushMacros()) {
                return redirect()->back()->with('error', __('Sending email failed, please try again.'));
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', __('SMTP settings are not configured correctly yet. ') . $e->getMessage());
        }


        return redirect()->back()->with('success', __('Email was sent successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = \App\Models\Notification::where('id', $id)->first();

        return view('admin.notification.show', compact('notification'));
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function systemShow($id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return view('admin.notification.systemShow', compact('notification'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {

            $notification = auth()->user()->notifications()->find(request('id'));

            if ($notification) {

                $notification->delete();

                return response()->json('success');

            } else {
                return response()->json('error');
            }
        }
    }


    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        $notifications = auth()->user()->unreadNotifications->where('type', '<>', 'App\Notifications\GeneralNotification');

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return redirect()->route('admin.notifications.system')->with('success', __('All system notifications are marked as read'));
    }


    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        $notifications = auth()->user()->notifications->where('type', '<>', 'App\Notifications\GeneralNotification');

        foreach ($notifications as $notification) {
            $notification->delete();
        }

        return redirect()->route('admin.notifications.system')->with('success', __('All system notifications are deleted'));
    }

    /**
     * Get country wise city
     */
    public function getCity(Request $request)
    {
        if ($request->ajax()) {
            $cities = User::where('country', request('country'))->get();
            $result = [];
            if (count($cities) > 0) {
                $html_view = view("admin.notification.city_options", compact('cities'))->render();
                $result['options'] = $html_view;
                $result['result'] = "success";
            } else {
                $result['result'] = "error";
            }
            return $result;
        }
    }

    /**
     * Get city wise users
     */
    public function getCityUsers(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select('id', 'name', 'email', 'phone_number')->where('country', request('country'));
            if (request('search')) {
                $keyword = request('search');
                $users = $users->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('phone_number', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
                });
                dd($users->all());

            }
            $users = $users->get();
            $result = [];

            if (count($users) > 0) {
                $html_view = view("admin.notification.user_options", compact('users'))->render();
                $result['options'] = $html_view;
                if (request('search')) {
                    $opt = '';
                    foreach ($users as $usr) {
                        $opt .= '<option value="' . $usr['id'] . '">' . $usr['name'] . '</option>';
                    }
                    $result['opt'] = $opt;
                }
                $result['result'] = "success";
            } else {
                $result['result'] = "error";
            }
            return $result;
        }
    }
}
