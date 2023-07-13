<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\TranslateTextJob;
use App\Models\Folder;
use App\Models\Project;
use App\Models\TextModel;
use App\Models\TranscribeResult;
use App\Models\User;
use Aws\Comprehend\ComprehendClient;
use Aws\Translate\TranslateClient;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use League\Csv\Writer;
use Yajra\DataTables\DataTables;

class TextController extends Controller
{
    public function index()
    {
        return view('admin.Images.text.index');
    }

    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = TextModel::where('type', 'text_translation')->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                     <a href="' . route("admin.text.edit", $row["id"]) . '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
                                     <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                 </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('text', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["text"]) . '</span>';
                    return $text;
                })
                ->addColumn('translated_text', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["translated_text"]) . '</span>';
                    return $text;
                })
                ->addColumn('name', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $text;
                })
                ->addColumn('status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->addColumn('folderName', function ($row) {
                    if ($row["folder_id"]) {
                        $folder = Folder::where('id', $row["folder_id"])->first();
                        if ($folder) {
                            $folderName = '<span>' . Folder::find($row["folder_id"])->name . '</span>';
                            return $folderName;
                        } else {
                            return $row["folder_id"];
                        }
                    } else {
                        return $row["folder_id"];
                    }
                })
                ->rawColumns(['actions', 'name', 'status', 'text', 'folderName', 'created-on', 'translated_text'])
                ->make(true);
        }

        return view('admin.Images.text.index');
    }

    public function create()
    {
        $folders = Folder::where('status', 'active')->get();
        return view('admin.Images.text.create', compact('folders'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required',
            'name' => 'required',
            'status' => 'required',
            'folder_id' => 'required',
        ]);

        $file = $request->file('csv');
        $data = $this->csvToArray($file);
        TranslateTextJob::dispatch($data, $request->name, $request->folder_id, $request->status);


        return redirect()->back()->with('success', __('CSV Text translation jobs have been queued'));
    }


    public function delete(Request $request)
    {
        $text = TextModel::where('id', $request->id)->first();
        if ($text) {
            $text->delete();
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }

    public function edit($id)
    {
        $folders = Folder::where('status', 'active')->get();
        $text = TextModel::where('id', $id)->first();
        return view('admin.Images.text.edit', compact('folders', 'text'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     *
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'text' => 'required',
            'name' => 'required',
            'status' => 'required',
            'folder_id' => 'required',
        ]);
        $textData = $this->autoTranslateToEnglish($request->text);
        $text = TextModel::create([
            'text' => $request->text,
            'name' => $request->name,
            'folder_id' => $request->folder_id,
            'user_id' => auth()->id(),
            'status' => $request->status,
            'type' => ' ',
            'translated_text' => $textData,
        ]);

        return redirect()->back()->with('success', __('CSV Text was successfully updated'));
    }

    /**
     * @param $filename
     * @param $delimiter
     * @return array|false
     * change cs into array
     */
    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            // Read the first line and remove the BOM character if present
            $firstLine = fgets($handle);
            $cleanFirstLine = str_replace("\xEF\xBB\xBF", '', $firstLine);

            // Convert the cleaned first line to an array and remove extra spaces
            $header = array_map(function ($header) {
                return strtolower(trim($header)); // convert to lowercase
            }, str_getcsv($cleanFirstLine, $delimiter));

            // Read the remaining lines
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Remove extra spaces from the row values
                $row = array_map(function ($value) {
                    return trim($value);
                }, $row);

                // Check if header and row have the same number of elements
                if (count($header) == count($row)) {
                    $data[] = array_combine($header, $row);
                } else {
                    // Handle the error here. You might want to log it or throw an exception.
                    // For now, let's just skip the row.
                    continue;
                }
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * @param $sourceText
     * @param $sourceLanguage
     * @param $targetLanguage
     * @return mixed|null
     * Translate text into english
     */
    public static function translate($sourceText, $sourceLanguage, $targetLanguage)
    {
        $client = new TranslateClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
        ]);

        $result = $client->translateText([
            'SourceLanguageCode' => $sourceLanguage,
            'TargetLanguageCode' => $targetLanguage,
            'Text' => $sourceText,
        ]);
        return $result['TranslatedText'];
    }

    public static function autoTranslateToEnglish($sourceText)
    {
        $sourceLanguage = self::detectLanguage($sourceText);
        if ($sourceLanguage === 'en') {
            return $sourceText;
        } else {
            return self::translate($sourceText, $sourceLanguage, 'en');
        }
    }

    private static function detectLanguage($text)
    {
        $client = new ComprehendClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
        ]);
        $result = $client->detectDominantLanguage([
            'Text' => $text,
        ]);

        $languages = $result['Languages'];
        return $languages[0]['LanguageCode'];
    }

    public function text_user(Request $request, $id)
    {
        if ($request->ajax()) {
            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $data = Folder::query();

            if (!empty($start_date)) {
                $data->whereHas('assignUser', function ($query) use ($start_date) {
                    $query->where('last_seen', '>=', $start_date);
                });
            }

            if (!empty($end_date)) {
                $data->whereHas('assignUser', function ($query) use ($end_date) {
                    $query->where('last_seen', '<=', $end_date);
                });
            }

            $data = $data->whereNotNull('assign_user_id')
                ->where('project_id', $request->project_id)
                ->whereHas('text', function ($query) {
                    $query->where('type', 'text_translation');
                })
                ->get();

            // Group folders by assign_user_id
            $groupedFolders = $data->groupBy('assign_user_id');
            return Datatables::of($groupedFolders)
                ->addIndexColumn()
                ->addColumn('user', function ($group) {
                    // Use the first folder in the group to get user information
                    $user = $group->first()->assignUser;

                    if (!$user) {
                        return 'User not found';
                    }

                    $hasUnreadText = $group->filter(function ($folder) {
                        return $folder->text->whereNull('read_at')->isNotEmpty();
                    })->isNotEmpty();
                    $badge = $hasUnreadText ? '<span class="badge badge-success"><i class="fas fa-bell"></i> new</span>' : '';

                    if ($user->profile_photo_path) {
                        $path = asset($user->profile_photo_path);
                    } else {
                        $path = URL::asset('img/users/avatar.png');
                    }

                    return '<div class="d-flex">
                                <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                <div class="widget-user-name"><span class="font-weight-bold">' . $user->name . '</span><br><span class="text-muted">' . $user->email . '</span>' . $badge . '</div>
                            </div>';
                })
                ->addColumn('assigned_folders', function ($group) {
                    return '<span class="font-weight-bold">' . $group->count() . '</span>';
                })
                ->addColumn('complete', function ($group) {
                    return '<span class="font-weight-bold">' . $group->where('status', 'complete')->count() . '</span>';
                })
                ->addColumn('last-seen-on', function ($group) {
                    $user = $group->first()->assignUser;
                    if (!$user) {
                        return '';
                    }

                    return '<span class="font-weight-bold">' . \Carbon\Carbon::parse($user->last_seen)->format('d M Y h:i:s A') . '</span>';
                })
                ->addColumn('created-on', function ($group) {
                    return '<span class="font-weight-bold">' . date_format($group->first()->created_at, 'd M Y') . '</span>';
                })
                ->addColumn('custom-status', function ($group) {
                    $status = $group->first()->status;

                    return '<span class="cell-box user-' . $status . '">' . ucfirst($status) . '</span>';
                })
                ->addColumn('custom-country', function ($group) {
                    $user = $group->first()->assignUser;
                    if (!$user) {
                        return '';
                    }
                    return '<span class="font-weight-bold">' . $user->country . '</span>';
                })
                ->addColumn('folder_count', function ($group) use ($request) {
                    $assignUserId = $group->first()->assign_user_id;
                    if (!$assignUserId) {
                        return '';
                    }
                    $textCountFolder = Folder::where('assign_user_id', $assignUserId)
                        ->where('project_id', $request->project_id)
                        ->whereHas('text', function ($query) {
                            $query->where('type', 'text_translation');
                        })
                        ->count();
                    $images_count = '<a href="' . route("admin.text.user.folder", ['projectID' => $request->project_id, 'userID' => $assignUserId]) . '" class="folder-link all-images"><i class="fas fa-folder folder-icon-list "></i><span class="font-weight-bold-' . $textCountFolder . '"">' . $textCountFolder . '</span></a>';
                    return $images_count;
                })
                ->rawColumns(['actions', 'custom-status', 'folderCount', 'assigned_folders', 'complete', 'custom-group', 'custom-currency', 'created-on', 'user', 'custom-country', 'folder_count', 'custom-characters', 'custom-minutes', 'last-seen-on'])
                ->make(true);

        }
        $project = Project::find($id);
        return view('admin.Images.text.user', compact('id', 'project'));
    }

    public function text_user_folder(Request $request, $projectID, $userID)
    {

        if ($request->ajax()) {
            $data = Folder::withCount(['text' => function ($query) {
                $query->where('type', '=', 'text_translation');
            }])
                ->whereHas('text', function ($query) {
                    $query->where('type', 'text_translation');
                })
                ->where('assign_user_id', $userID)
                ->where('project_id', $projectID)
                ->having('text_count', '>', 0)
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="folder-checkbox" data-folder-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    if ($row['status'] == 'paid') {
                        $actionBtn = '<div>
                                         <a class="downloadButton" href="' . route("admin.text.csv", $row['id']) . '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                         <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                      </div>';
                        return $actionBtn;
                    } else {
                        $actionBtn = '<div>
                                           <a class="downloadButton" href="' . route("admin.text.csv", $row['id']) . '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                           <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                           <a class="agreePayment" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Payment"></i></a>

                                      </div>';
                        return $actionBtn;
                    }
                })
                ->addColumn('name', function ($row) {
                    $hasUnreadImages = $row->text()->whereNull('read_at')->exists();
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
                ->addColumn('text_count', function ($row) {
                    $textCount = TextModel::where('folder_id', $row["id"])->where('type', 'text_translation')->count();
                    $text_count = '<a href="' . route("admin.text.user.folder.text", $row["id"]) . '"><span class="font-weight-bold-' . $textCount . '"">' . $textCount . '</span></a>';
                    return $text_count;
                })
                ->addColumn('accepted_text', function ($row) {
                    $total_count = TextModel::where('folder_id', $row["id"])
                        ->where('type', 'text_translation')
                        ->where('status', 'complete')
                        ->count();
                    $accepted_image = '<span class="font-weight-bold-' . $total_count . '"">' . ucfirst($total_count) . '</span>';
                    return $accepted_image;
                })
                ->addColumn('rejected_text', function ($row) {
                    $rejected = TextModel::where('folder_id', $row["id"])
                        ->where('type', 'text_translation')
                        ->where('status', 'failed')
                        ->count();

                    $rejected_image = '<span class="font-weight-bold-' . $rejected . '"">' . $rejected . '</span>';
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
                ->rawColumns(['actions', 'name', 'checkbox', 'assign_user', 'custom-status', 'qualityAssurance', 'status', 'assignUser', 'images_count', 'language', 'text_count', 'accepted_text', 'rejected_text'])
                ->make(true);
        }
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'quality_assurance');
        })->withCount('folders')->latest()->get();

        return view('admin.Images.text.folder', compact('projectID', 'userID', 'users'));
    }

    public function text_user_folder_text(Request $request, $id)
    {
        if ($request->ajax()) {
            $start_date = $request->input('created_on_from');
            $end_date = $request->input('created_on_to');

            $data = TextModel::where('type', 'text_translation')
                ->with('folder.qualityAssurance')
                ->where('status', '!=', 'active')
                ->where('folder_id', $id);

            // Apply the created_at filter
            if (!empty($start_date) && !empty($end_date)) {
                $data = $data->whereBetween('updated_at', [$start_date, $end_date]);
            } elseif (!empty($start_date)) {
                $data = $data->where('updated_at', '>=', $start_date);
            } elseif (!empty($end_date)) {
                $data = $data->where('updated_at', '<=', $end_date);
            }

            $data = $data->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('username', function ($row) {
                    if ($row["folder_id"]) {
                        $folder = Folder::find($row["folder_id"]);
                        $username = '<span>' . User::find($folder->assign_user_id)->email . '</span>';
                        return $username;
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('text', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["text"]) . '</span>';
                    return $text;
                })
                ->addColumn('translated_text', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["text"] . '"">' . ucfirst($row["translated_text"]) . '</span>';
                    return $text;
                })
                ->addColumn('name', function ($row) {
                    $text = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $text;
                })
                ->addColumn('custom-status', function ($row) {
                    switch ($row['status']) {
                        case 'IN_PROGRESS':
                            $value = 'In Progress';
                            break;
                        case 'failed':
                            $value = 'Failed';
                            break;
                        case 'complete':
                            $value = 'Complete';
                            break;
                        default:
                            $value = '';
                            break;
                    }
                    $custom_status = '<span class="cell-box transcribe-' . strtolower($row["status"]) . '">' . $value . '</span>';
                    return $custom_status;

                })
                ->addColumn('folderName', function ($row) {
                    if ($row["folder_id"]) {
                        $folder = Folder::where('id', $row["folder_id"])->first();
                        if ($folder) {
                            $folderName = '<span>' . Folder::find($row["folder_id"])->name . '</span>';
                            return $folderName;
                        } else {
                            return $row["folder_id"];
                        }
                    } else {
                        return $row["folder_id"];
                    }
                })
                ->rawColumns(['actions', 'username', 'name', 'status', 'text', 'folderName', 'created-on', 'translated_text', 'custom-status'])
                ->make(true);
        }
        $folder = Folder::where('id', $id)->first();
        if ($folder->project_id) {
            $project = Project::where('id', $folder->project_id)->with('remarks')->fpushirst();

            $remarks = $project->remarks;
        } else {
            $remarks = null;
        }
        $texts = TextModel::where('folder_id', $folder->id)->get();
        foreach ($texts as $text) {
            $text->read_at = now();
            $text->save();
        }
        return view('admin.Images.text.text', compact('id', 'remarks', 'folder'));
    }

    public function nextText(Request $request)
    {
        $direction = $request->direction === 'next' ? 'desc' : 'asc';
        $operator = $direction === 'desc' ? '>' : '<';

        $data = TextModel::where('id', $operator, $request->text_id)
            ->with('folder', 'folder.qualityAssurance')->with('folder.user')
            ->where('status', '!=', 'active')
            ->where('folder_id', $request->folder_id)
            ->first();


        return response()->json($data);

    }

    public function saveFeedbackResult(Request $request)
    {
        if ($request->feedback == 'correct') {
            $status = 'complete';
        } else {
            $status = 'failed';
        }
        $text = TextModel::where('id', $request->text_id)->update([
            'comment' => $request->comment,
            'remark_id' => $request->remark,
            'status' => $status,
//            'translated_text' => $request->editedText,
        ]);
        return $text;
    }

    public function csvText($id)
    {
        $folders = Folder::where('id', $id)->whereHas('text', function ($query) {
            $query->where('type', 'text_translation');
        })->with('text', 'user')->get();

        // Create a new CSV writer instance
        $csv = Writer::createFromString('');

        // Set the header for the CSV
        $csv->insertOne(['Folder Name', 'Name', 'Text', 'Translated_text', 'Status', ' Comment', 'Date']);

        // Loop through each folder and its images and insert into the CSV
        foreach ($folders as $folder) {
            $folderName = $folder->name;
            foreach ($folder->text as $text) {
                if ($text->status == 'complete') {
                    $status = "Correct";
                } elseif ($text->status == 'failed') {
                    $status = "InCorrect";
                } else {
                    $status = $text->status;
                }
                $textName = $text->name;
                $SimpleText = $text->text . $text->id;
                $translatedText = $text->translated_text;
                $textStatus = $status;
                $textComment = $text->comment;
                $textDate = $text->created_at->format('Y-m-d');

                $csv->insertOne([
                    $folderName,
                    $textName,
                    $SimpleText,
                    $translatedText,
                    $textStatus,
                    $textComment,
                    $textDate,
                ]);
            }
        }
        $csv->output('file.csv');
    }
}
