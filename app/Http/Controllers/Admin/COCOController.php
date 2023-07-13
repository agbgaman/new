<?php

namespace App\Http\Controllers\Admin;

use App\Models\Folder;
use App\Models\Image;
use App\Models\Invoice;
use App\Models\Price;
use App\Models\Project;
use App\Models\ProjectRemark;
use App\Models\Referral;
use App\Models\User;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use League\Csv\Writer;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use ZipArchive;
use GuzzleHttp\Client;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use function Aws\clear_compiled_json;

class COCOController extends Controller
{
    public function index(Request $request)
    {

        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('admin')) {
                $userIds[] = $user->id;
            }
        }

        if ($request->ajax()) {

            if ($request->folder_id) {
                $data = Image::where('folder_id', $request->folder_id)
                    ->orderBy('id', 'desc')->get();
            } else {
                $data = Image::whereNotIn('user_id', $userIds)
                    ->orderBy('id', 'desc')->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                       <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                 </div>';
                    return $actionBtn;
                })
                ->addColumn('username', function ($row) {
                    if ($row["user_id"]) {
                        $user = User::find($row["user_id"]);
                        if ($user) {
                            $username = '<span>' . User::find($row["user_id"])->name . '</span>';
                            return $username;
                        } else {
                            return $row["user_id"];
                        }
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('imageUserName', function ($row) {
                    if ($row["user_id"]) {
                        $user = User::find($row["user_id"]);
                        if ($user) {
                            $imageUserName = User::find($row["user_id"])->name;
                        } else {
                            return $row["user_id"];
                        }
                        return $imageUserName;
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'Pending':
                            $value = 'In Progress';
                            break;
                        case 'inactive':
                            $value = 'Failed';
                            break;
                        case 'active':
                            $value = 'Completed';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    if ($value == 'In Progress') {
                        $case = 'IN_PROGRESS';
                    } elseif ($value == 'Failed') {
                        $case = 'FAILED';
                    } else {
                        $case = 'COMPLETED';
                    }
                    $status = '<span class="cell-box transcribe-' . strtolower($case) . '">' . $value . '</span>';
                    return $status;
                })
                ->addColumn('folderName', function ($row) {
                    if ($row["folder_id"]) {
                        $folder = Folder::where('id', $row["folder_id"])->first();
                        if ($folder) {
                            $folderName = '<span>' . $folder->name . '</span>';
                            return $folderName;
                        } else {
                            return $row["folder_id"];
                        }
                    } else {
                        return $row["folder_id"];
                    }
                })
                ->addColumn('quality_assurance', function ($row) {
                    if ($row["folder_id"]) {
                        $folder = Folder::where('id', $row["folder_id"])->first();
                        if ($folder->quality_assurance_id) {
                            $user = User::where('id', $folder->quality_assurance_id)->first();
                            $user = '<span>' . $user->name . '</span>';
                            return $user;
                        } else {
                            return '<span>' . "Not Assigned Yet" . '</span>';
                        }
                    } else {
                        return '<span>' . "Not Assigned Yet" . '</span>';
                    }
                })
                ->addColumn('image', function ($row) {
                    if ($row['image']) {
                        $path = $row['image'];
                        $image = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">' . $row["name"] . $row["id"] . '</span></div>
                                  </div>';
                    }

                    return $image;
                })
                ->addColumn('image_link', function ($row) {
                    $image_link = $row['image'];
                    return $image_link;
                })
                ->addColumn('comment', function ($row) {
                    $comment = $row['comment'];
                    return $comment;
                })
                ->rawColumns(['actions', 'name', 'comment', 'quality_assurance', 'assign_user', 'custom-status', 'image', 'folderName', 'created-on', 'username', 'image_link', 'imageUserName'])
                ->make(true);
        }
        return view('admin.Images.coco.index');
    }

    public function user(Request $request, $name)
    {
        if ($request->ajax()) {

            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');
            $projectID = $request->input('project_id');
            $columns = $request->input('database_columns');

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

            $data = $data->select('users.*')
                ->selectSub(function ($query) use ($projectID) {
                    $query->from('folders')
                        ->join('images', 'images.folder_id', '=', 'folders.id')
                        ->whereRaw('folders.user_id = users.id')
                        ->whereNull('images.read_at')
                        ->where('folders.project_id', $projectID)
                        ->selectRaw('COUNT(DISTINCT folders.id)');
                }, 'unread_folders_count')
                ->withCount(['folders' => function ($query) use ($projectID) { // Adjusted this line
                    $query->where('project_id', $projectID);
                }])
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'user');
                })
                ->having('folders_count', '>', 0)
                ->orderBy('unread_folders_count', 'desc')
                ->get();

            $data = $data->map(function ($user) use ($projectID) {
                $user->projectId = $projectID;
                return $user;
            });

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                        <a href="' . route("admin.user.storage", $row["id"]) . '"><i class="fa-solid fa-puzzle table-action-buttons request-action-button" title="Add Credits"></i></a>
                                        <a href="' . route("admin.user.show", $row["id"]) . '"><i class="fa-solid fa-clipboard-user table-action-buttons view-action-button" title="View User"></i></a>
                                        <a href="' . route("admin.user.edit", $row["id"]) . '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
                                        <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('user', function ($row) {
                    $unreadFoldersCount = $row->folders()->whereHas('images', function ($query) {
                        $query->whereNull('read_at');
                    })->count();

                    $badge = $unreadFoldersCount > 0 ? '<span class="badge badge-success"><i class="fas fa-bell"></i>  ' . $unreadFoldersCount . '  new</span>' : '';

                    if ($row['profile_photo_path']) {
                        $path = asset($row['profile_photo_path']);
                        $user = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">' . $row['name'] . '</span><br><span class="text-muted">' . $row["email"] . '</span>' . $badge . '</div>
                                </div>';
                    } else {
                        $path = URL::asset('img/users/avatar.png');
                        $user = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" class="rounded-circle" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">' . $row['name'] . '</span><br><span class="text-muted">' . $row["email"] . '</span>' . $badge . '</div>
                                </div>';
                    }
                    return $user;
                })
                ->addColumn('assigned_folders', function ($row) {
                    $folder = Folder::where('user_id', $row["id"])->whereNotNull('quality_assurance_id')->count();
                    $folderCount = '<span class="font-weight-bold">' . $folder . '</span>';
                    return $folderCount;
                })
                ->addColumn('complete', function ($row) {
                    $folder = Folder::where('user_id', $row["id"])->where('status', 'complete')->count();
                    $complete = '<span class="font-weight-bold">' . $folder . '</span>';
                    return $complete;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span class="font-weight-bold">' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('last-seen-on', function ($row) {
                    $last_seen = '<span class="font-weight-bold">' . \Carbon\Carbon::parse($row['last_seen'])->format('d M Y h:i:s A') . '</span>';
                    return $last_seen;
                })
                ->addColumn('custom-group', function ($row) {
                    $custom_group = '<span class="cell-box user-group-' . $row["group"] . '">' . ucfirst($row["group"]) . '</span>';
                    return $custom_group;
                })
                ->addColumn('custom-status', function ($row) {
                    $custom_status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $custom_status;
                })
                ->addColumn('folder_count', function ($row) {
                    $images_count = '<a href="' . route("admin.coco.userFolderList", ['projectID' => $row['projectId'], 'userID' => $row->id]) . '" class="folder-link all-images"><i class="fas fa-folder folder-icon-list "></i><span class="font-weight-bold-' . $row["folders_count"] . '"">' . ucfirst($row["folders_count"]) . '</span></a>';
                    return $images_count;
                })
                ->addColumn('custom-country', function ($row) {
                    $custom_country = '<span class="font-weight-bold">' . $row["country"] . '</span>';
                    return $custom_country;
                })
                ->addColumn('custom-currency', function ($row) {
                    $custom_country = '<span class="font-weight-bold">' . $row["currency"] . '</span>';
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
                ->rawColumns(['actions', 'custom-status', 'folderCount', 'assigned_folders', 'complete', 'custom-group', 'custom-currency', 'created-on', 'user', 'custom-country', 'folder_count', 'custom-characters', 'custom-minutes', 'last-seen-on'])
                ->make(true);
        }
        $project = Project::where('name', $name)->first();

        return view('admin.Images.coco.user', compact('project'));
    }

    public function userFolderList(Request $request, $projectID, $userID)
    {
        if ($request->ajax()) {

            $data = Folder::withCount('images', 'text')->where('project_id', $projectID)->where('user_id', $userID)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="folder-checkbox" data-folder-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    if ($row['status'] == 'paid') {
                        $actionBtn = '<div>
                                         <a class="" href="' . route("admin.coco.userFolderDownloasadasdad", $row['id']) . '" ><i class="fa fa-download table-action-buttons " title="Download"></i></a>
                                         <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                      </div>';
                        return $actionBtn;
                    } else {
                        $actionBtn = '<div>
                                           <a class="" href="' . route("admin.coco.userFolderDownloasadasdad", $row['id']) . '" ><i class="fa fa-download table-action-buttons " title="Download"></i></a>
                                           <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                           <a class="agreePayment" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Payment"></i></a>

                                      </div>';
                        return $actionBtn;
                    }
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->addColumn('name', function ($row) {
                    $hasUnreadImages = $row->images()->whereNull('read_at')->exists();
                    $badge = $hasUnreadImages ? '<span class="badge badge-success"><i class="fas fa-bell"></i> new</span>' : '';
                    $name = '<div class="d-flex align-items-center"><span class="mr-2 font-weight-bold-' . $row["name"] . '">' . ucfirst($row["name"]) . '</span>' . $badge . '</div>';
                    return $name;
                })
                ->addColumn('qualityAssurance', function ($row) {
                    if ($row["quality_assurance_id"]) {
                        $user = User::find($row["quality_assurance_id"]);
                        if ($user) {
                            $qualityAssurance = '<span>' . $user->name . '</span>';
                            return $qualityAssurance;
                        } else {
                            return $row["quality_assurance_id"];
                        }
                    } else {
                        return $row["quality_assurance_id"];
                    }
                })
                ->addColumn('images_count', function ($row) {
                    $images_count = '<a href="' . route("admin.coco.userImageList", $row["id"]) . '"><span class="font-weight-bold-' . $row["images_count"] . '"">' . ucfirst($row["images_count"]) . '</span></a>';
                    return $images_count;
                })
                ->addColumn('accepted_image', function ($row) {
                    $acceptedImage = Image::where('folder_id', $row['id'])->where('status', 'active')->count();
                    $accepted_image = '<span class="font-weight-bold-' . $acceptedImage . '"">' . ucfirst($acceptedImage) . '</span>';
                    return $accepted_image;
                })
                ->addColumn('rejected_image', function ($row) {
                    $rejectedImage = Image::where('folder_id', $row['id'])->where('status', 'inactive')->count();
                    $rejected_image = '<span class="font-weight-bold-' . $rejectedImage . '"">' . ucfirst($rejectedImage) . '</span>';
                    return $rejected_image;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'active':
                            $value = 'Active';
                            break;
                        case 'in_progress':
                            $value = 'Not Assigned';
                            break;
                        case 'inactive':
                            $value = 'Inactive';
                            break;
                        case 'complete':
                            $value = 'Completed';
                            break;
                        case 'In QC':
                            $value = 'In QC';
                            break;
                        case 'paid':
                            $value = 'Paid';
                            break;
                        default:
                            $value = '';
                            break;
                    }

                    $status = '<span class="cell-box transcribe-' . strtolower(str_replace(' ', '_', $value)) . '">' . $value . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name', 'checkbox', 'assign_user', 'custom-status', 'qualityAssurance', 'status', 'assignUser', 'images_count', 'created-on', 'text_count', 'accepted_image', 'rejected_image'])
                ->make(true);
        }
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'quality_assurance');
        })->withCount('folders')->latest()->get();

        return view('admin.Images.coco.userFolder', compact('projectID', 'userID', 'users'));
    }

    public function userImageList($id)
    {
        $folder = Folder::where('id', $id)->first();
        $images = Image::where('folder_id', $id)->with('folder')->get();
        foreach ($images as $image) {
            $image->read_at = now();
            $image->save();
        }
        $project = Project::where('id', $folder->project_id)->first();
        if ($project) {
            $projectRemarks = ProjectRemark::where('project_id', $project->id)->get();
        } else {
            $projectRemarks = null;
        }
        return view('admin.Images.coco.userImageList', compact('id', 'projectRemarks'));
    }

    public function nextImage(Request $request)
    {
        $direction = $request->direction === 'next' ? 'desc' : 'asc';

        $image = Image::where('id', $direction === 'desc' ? '<' : '>', $request->image_id)
            ->where('folder_id', $request->folder_id)
            ->with('folder.qualityAssurance', 'user')
            ->orderBy('id', $direction)
            ->firstOrFail();

        $cacheKey = 'image_metadata_' . $image->id;

        // Check if the metadata exists in the cache
        $metadata = Cache::get($cacheKey);

        if (!$metadata) {
            try {
                $client = new S3Client([
                    'credentials' => [
                        'key' => env('AWS_ACCESS_KEY_ID'),
                        'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    ],
                    'region' => env('AWS_DEFAULT_REGION'),
                    'version' => 'latest',
                ]);

                $bucket = 'gtsdashbucket';
                $parsed_url = parse_url(urldecode($image->image));

                // Remove the leading slash from the path
                $key_name = ltrim($parsed_url['path'], '/');

                $result = $client->getObject([
                    'Bucket' => $bucket,
                    'Key' => $key_name,
                ]);

                $stream = $result['Body'];
                $image_data = $stream->__toString();

                // Save image data to a temporary file
                $temp_file = tempnam(sys_get_temp_dir(), 'image_');
                file_put_contents($temp_file, $image_data);

                // Get the metadata of the image
                $metadata = exif_read_data($temp_file);

                // Get the original date and time from the metadata
                $dateTimeOriginal = $metadata['DateTimeOriginal'] ?? null;
                $dateTimeDigitized = $metadata['DateTimeDigitized'] ?? null;
                $fileDateTime = $metadata['FileDateTime'] ?? null;

                // Choose the appropriate timestamp to use
                $timestamp = $dateTimeOriginal ? strtotime($dateTimeOriginal) : ($dateTimeDigitized ? strtotime($dateTimeDigitized) : $fileDateTime);
                unlink($temp_file);
                // Convert the timestamp to a human-readable date and time format
                $image->date = $timestamp ? date('Y-m-d H:i:s', $timestamp) : $image->created_at;

                Cache::put($cacheKey, $metadata, now()->addHours(24));
            } catch (\Exception $exception) {
                $image->date = $image->created_at;
            }

        }
        return response()->json($image);
    }

    public function checkImageDuplicacy($id)
    {

        $image = Image::where('id', $id)
            ->with('user')
            ->firstOrFail();


        $client = new Client(['verify' => false]);
        $response = $client->request('POST', 'https://api.gts.ai', [
            'form_params' => [
                'email' => $image->user->email ?? '',
                'img_name' => $image->name,
                'img_url' => $image->image,
                'unique_id' => $image->id,
            ]
        ]);


        $body = $response->getBody()->getContents();

        $results = json_decode($body);
        // Remove element with $image->id
        $filteredResults = array_filter($results, function ($item) use ($image) {
            return $item->unique_id != $image->id;
        });

        // Count the remaining elements
        $count = count($filteredResults);
        if ($count > 1) {
            return response()->json(['status' => 'duplicate', 'count' => $count]);
        } else {
            return response()->json(['status' => 'unique', 'count' => $count]);
        }
    }

    public function duplicateImages($id)
    {
        $image = Image::where('id', $id)
            ->with('user')
            ->firstOrFail();
        $url = 'https://api.gts.ai';
        $data = array(
            'email' => $image->user->email ?? '',
            'img_name' => $image->name,
            'img_url' => $image->image,
            'unique_id' => $image->id,
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 30000); // Increase timeout to 5 seconds
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose mode


        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        $results = json_decode($response);

//        $client = new Client(['verify' => false]);
//        $response = $client->request('POST', 'http://api.gts.ai:5000', [
//            'form_params' => [
//                'email' => $image->user->email,
//                'img_name' => $image->name,
//                'img_url' => $image->image,
//                'unique_id' => $image->id,
//            ]
//        ]);
//
//        $body = $response->getBody()->getContents();
//
//        $results = json_decode($body);
        $unique_ids = array_map(function ($item) {
            return $item->unique_id;
        }, $results);

        $images = Image::whereIn('id', $unique_ids)
            ->with('user', 'folder.project')
            ->get();

        return view('admin.Images.coco.duplicateImages', compact('images'));

    }

    public function saveImageDetails(Request $request)
    {
        if ($request->feedback == 'correct') {
            $status = 'active';
        } else {
            $status = 'inactive';
        }
        $image = Image::where('id', $request->image_id)->update([
            'comment' => $request->comment,
            'remark_id' => $request->remark,
            'status' => $status
        ]);

    }

    public function payment(Request $request)
    {
        $folder = Folder::with('project','user.prices')->where('id', $request->id)->first();
        $user       = $folder->user;
        $project    = $folder->project;

        $price = optional($project->price);

        if ($price === null) {
            return response()->json(['error' => 'price_not_set'], 400);
        }
        if ($request->type == 'coco') {
            $folder->load('images');
            $acceptedData = $folder->images->where('status', 'active')->count();
            $rejectedData = $folder->images->where('status', 'inactive')->count();
            $projectPrice = optional($price)->value;
            $projectName = 'coco';
        } elseif ($request->type == 'text') {
            $folder->load('text');
            $acceptedData = $folder->text->whereNull('type')->where('status', 'active')->count();

             $projectPrice = optional($price)->value;

            $projectName = 'text';
        }

        $commissionPrice = null;
        if ($user->referred_by) {
            $commissionPrice = optional($price)->value * 0.20;
            $referredUser = User::where('id', $user->referred_by)->first();
            $referral = Referral::create([
                'referrer_id'       => $referredUser->id,
                'referrer_email'    => $referredUser->email ?? null,
                'referred_id'       => $user->id,
                'referred_email'    => $user->email,
                'order_id'          => $projectName,
                'payment'           => $projectPrice,
                'commission'        => $commissionPrice,
                'rate'              => $commissionPrice,
                'status'            => 'Complete',
                'gateway'           => null,
                'purchase_date'     => now(),
            ]);

            $referredUser->balance += $commissionPrice;
            $referredUser->save();
        }


        $created_invoice = Invoice::create([
            'user_id' => $user->id,
            'project_name'      => $projectName . ' ' . $folder->name,
            'accepted_data'     => $acceptedData,
            'rejected_data'     => $rejectedData,
            'referral_email'    => $referredUser->email ?? null,
            'earning'           => $projectPrice,
            'commission'        => $commissionPrice,
        ]);

        $user->balance += $projectPrice;
        $user->save();

        $folder->status = 'paid';
        $folder->save();

        return response()->json('success');
    }

    public function readMultipleFolders(Request $request)
    {
        $folderIds = json_decode($request->input('folder_ids'), true);
        if (is_array($folderIds)) {
            $images = Image::whereIn('folder_id', $folderIds)->get();
            foreach ($images as $image) {
                $image->read_at = now();
                $image->save();
            }
            return redirect()->back()->with('success', __('Congratulation! Folder is marked as read.'));
        }
        return redirect()->back()->with('error', __('Please select folder first.'));

    }

    public function userFolderDownlodsadasdad($id)
    {
        $folder = Folder::findOrFail($id);
        $images = Image::where('folder_id', $id)
            ->pluck('image')
            ->toArray();

        // Create a new S3 client
        $s3client = new S3Client([
            'credentials' => [
                'key' => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
            'region' => config('filesystems.disks.s3.region'),
            'version' => 'latest',
        ]);

        // Configure the S3 adapter with the Laravel's built-in S3 storage driver
        $adapter = new AwsS3V3Adapter($s3client, config('filesystems.disks.s3.bucket'));
        $s3filesystem = new Filesystem($adapter);

        // Set the paths for the temporary ZIP file
        $tempZipPath = tempnam(sys_get_temp_dir(), 'images_' . $folder->name) . '.zip';

        // Create a new ZIP archive
        $zip = new ZipArchive();
        $zip->open($tempZipPath, ZipArchive::CREATE);

        // Loop through the array of image URLs and add each image to the ZIP file
        foreach ($images as $index => $imageUrl) {
            $imagePath = urldecode(parse_url($imageUrl, PHP_URL_PATH));
            $imageContent = $s3filesystem->read($imagePath);
            $zip->addFromString(basename($imagePath), $imageContent);
        }

        // Close the ZIP archive
        $zip->close();

        // Return the ZIP file as a streamed response
        return new StreamedResponse(function () use ($tempZipPath) {
            readfile($tempZipPath);
            unlink($tempZipPath);
        }, 200, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename=images.zip',
            'Content-Length' => filesize($tempZipPath),
        ]);
    }

    public function exportMultipleFolders(Request $request)
    {
        $folderIds = json_decode($request->input('folder_ids'), true);

        if (is_array($folderIds)) {
            $folders = Folder::whereIn('id', $folderIds)->with('images', 'user')->get();

            // Create a new CSV writer instance
            $csv = Writer::createFromString('');

            // Set the header for the CSV
            $csv->insertOne(['User name', 'Folder Name', 'Image Name', 'Image URL', 'Image Status', 'Image Comment', 'Date']);

            // Loop through each folder and its images and insert into the CSV
            foreach ($folders as $folder) {
                $folderName = $folder->name;
                $folderUserName = $folder->user->name ?? 'no name';

                foreach ($folder->images as $image) {
                    $imageName = $image->name . $image->id;
                    $imageUrl = $image->image;
                    $imageStatus = $image->status;
                    $imageComment = $image->comment;
                    $imageDate = $image->created_at->format('Y-m-d');;

                    $csv->insertOne([
                        $folderUserName,
                        $folderName,
                        $imageName,
                        $imageUrl,
                        $imageStatus,
                        $imageComment,
                        $imageDate,
                    ]);
                }
            }

            // Set the proper headers to return the CSV file as a download
            $csvFilename = 'images_data_' . date('Y_m_d_His') . '.csv';
            $headers = [
                'Content-type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $csvFilename,
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            // Return the CSV as a response
            return response((string)$csv, 200, $headers);
        }

        // If folderIds is not an array, return an error response or redirect
        return redirect()->back()->withErrors(['error' => 'No valid folder IDs provided']);
    }

    public function downloadMultipleFolders(Request $request)
    {
        $folderIds = json_decode($request->input('folder_ids'), true);

        if (is_array($folderIds)) {
            $images = Image::whereIn('folder_id', $folderIds)
                ->pluck('image')
                ->toArray();
            // Create a new S3 client
            $s3client = new S3Client([
                'credentials' => [
                    'key' => config('filesystems.disks.s3.key'),
                    'secret' => config('filesystems.disks.s3.secret'),
                ],
                'region' => config('filesystems.disks.s3.region'),
                'version' => 'latest',
            ]);

            // Configure the S3 adapter with the Laravel's built-in S3 storage driver
            $adapter = new AwsS3V3Adapter($s3client, config('filesystems.disks.s3.bucket'));
            $s3filesystem = new Filesystem($adapter);

            // Set the paths for the temporary ZIP file
            $tempZipPath = tempnam(sys_get_temp_dir(), 'images_' . auth()->user()->name) . '.zip';

            // Create a new ZIP archive
            $zip = new ZipArchive();
            $zip->open($tempZipPath, ZipArchive::CREATE);

            // Loop through the array of image URLs and add each image to the ZIP file
            foreach ($images as $index => $imageUrl) {
                $imagePath = urldecode(parse_url($imageUrl, PHP_URL_PATH));
                $imageContent = $s3filesystem->read($imagePath);
                $zip->addFromString(basename($imagePath), $imageContent);
            }

            // Close the ZIP archive
            $zip->close();

            // Return the ZIP file as a streamed response
            return new StreamedResponse(function () use ($tempZipPath) {
                readfile($tempZipPath);
                unlink($tempZipPath);
            }, 200, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename=images.zip',
                'Content-Length' => filesize($tempZipPath),
            ]);
        }
    }

    public function assignQuantityAssurance(Request $request)
    {

        $folderIds = json_decode($request->input('folder_ids'), true);

        if (is_array($folderIds)) {
            foreach ($folderIds as $folderId) {
                $folder = Folder::where('id', $folderId)->first();
                $folder->quality_assurance_id = $request->user_id;
                $folder->status = 'In QC';
                $folder->save();

            }
            return redirect()->back()->with('success', __('Congratulation! Folder are assigned for Quality Assurance'));

        }
        return redirect()->back()->with('success', __('Congratulation! Folder are assigned for Quality Assurance'));

    }
}
