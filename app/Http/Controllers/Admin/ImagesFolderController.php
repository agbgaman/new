<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Image;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;

class ImagesFolderController extends Controller
{
    public function index()
    {

        return view('admin.Images.folder.index');
    }

    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function list(Request $request)
    {

        if ($request->ajax()) {
            $data = Folder::withCount('images', 'text')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>';

                    if($row["is_frozen"] != 1){
                        $actionBtn .= '<a href="' . route("admin.images.folder.edit", $row["id"]) . '">
                                            <i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group">
                                            </i>
                                        </a>';
                      }

                        $actionBtn .= '<a class="deleteUserButton" id="' . $row["id"] . '" href="#">
                            <i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User">
                            </i>
                        </a>';

                        if($row["is_frozen"] == 1) {
                            $actionBtn .= '<a class="unfreezeUserButton" id="' . $row["id"] . '" href="' . route("admin.images.folder.unFreeze", $row["id"]) . '">
                                                <i class="fa-solid fa-temperature-down table-action-buttons unfreeze-action-button" title="Unfreeze User">
                                                </i>
                                            </a>';
                        } else {
                            $actionBtn .= '<a class="freezeUserButton" id="' . $row["id"] . '" href="' . route("admin.images.folder.freeze", $row["id"]) . '">
                                            <i class="fa-solid fa-snowflake table-action-buttons freeze-action-button" title="Freeze User">
                                            </i>
                                        </a>';
                    }

                    $actionBtn .= '</div>';

                    return $actionBtn;

                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('images_count', function ($row) {
                    $images_count = '<a href="' . route("admin.images.folder.images", $row["id"]) . '"><span class="font-weight-bold-' . $row["images_count"] . '"">' . ucfirst($row["images_count"]) . '</span></a>';
                    return $images_count;
                })
                ->addColumn('text_count', function ($row) {
                    $text_count = '<span class="font-weight-bold-' . $row["text_count"] . '"">' . ucfirst($row["text_count"]) . '</span>';
                    return $text_count;
                })
                ->addColumn('text_count', function ($row) {
                    $text_count = '<span class="font-weight-bold-' . $row["text_count"] . '"">' . ucfirst($row["text_count"]) . '</span>';
                    return $text_count;
                })
                ->addColumn('created-by', function ($row) {
                    if ($row["user_id"]) {

                        $user = User::find($row["user_id"]);
                        if ($user) {
                            $assignUser = '<span>' . $user->name . '</span>';
                            return $assignUser;
                        } else {
                            return $row["user_id"];
                        }
                    } else {
                        return $row["user_id"];
                    }
                })
                ->addColumn('project-id', function ($row) {
                    if ($row["project_id"]) {
                        $project = Project::where('id', $row["project_id"])->first();
                        if ($project) {
                            $language = '<span>' . $project->name . '</span>';
                            return $language;
                        } else {
                            return $row["project_id"];
                        }
                    } else {
                        return $row["project_id"];
                    }
                })
                ->addColumn('assignUser', function ($row) {
                    if ($row["assign_user_id"]) {
                        $user = User::find($row["assign_user_id"]);
                        if ($user) {
                            $assignUser = '<span>' . $user->name . '</span>';
                            return $assignUser;
                        } else {
                            return $row["assign_user_id"];
                        }
                    } else {
                        return $row["assign_user_id"];
                    }
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })->addColumn('Status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'status', 'assignUser', 'images_count', 'language', 'text_count', 'project-id', 'created-on', 'created-by'])
                ->make(true);
        }

        return view('admin.Images.folder.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            request()->validate([
                'name' => 'required'
            ]);

            if (strtolower(request('name') == 'all')) {
                return response()->json(['status' => 'error', 'message' => __('Project Name is reserved and is already created, please create another one')]);
            }

            $check = Folder::where('name', request('name'))->first();

            if (!isset($check)) {
                Folder::create([
                    'name' => $request->name,
                    'user_id' => auth()->user()->id,
                    'status' => 'active'
                ]);

                return response()->json(['status' => 'success', 'message' => __('Folder has been successfully created')]);

            } else {
                return response()->json(['status' => 'error', 'message' => __('Folder name already exists')]);
            }
        }
    }

    public function delete(Request $request)
    {
        $folder = Folder::where('id', $request->id)->first();

        if ($folder) {
            $folder->delete();

            return response()->json('success');

        } else {
            return response()->json('error');
        }
    }

    public function edit($id)
    {
        $folder = Folder::where('id', $id)->first();

        $users = User::where('group', 'user')->where('status', 'active')->get();

        $projects = Project::where('status', 'active')->get();

        $quality_assurance_users = User::whereHas('roles', function ($query) {
            $query->where('name', 'quality_assurance');
        })->withCount('folders')->latest()->get();

        # Show Languages
        $languages = DB::table('transcribe_languages')
            ->join('vendors', 'transcribe_languages.vendor', '=', 'vendors.vendor_id')
            ->where('vendors.enabled', 1)
            ->where('transcribe_languages.status', 'active')
            ->where('type', 'both')
            ->select('transcribe_languages.id', 'transcribe_languages.language', 'transcribe_languages.language_code', 'transcribe_languages.language_flag', 'transcribe_languages.vendor_img')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.Images.folder.edit', compact('folder', 'users', 'languages', 'quality_assurance_users', 'projects'));
    }

    public function update(Request $request, $id)
    {

        $folder = Folder::where('id', $id)->first();

        Folder::where('id', $id)->update([
            'name' => $request->name,
            'user_id' => $folder->user_id,
            'assign_user_id' => $request->assign_user_id,
            'status' => $request->status,
            'quality_assurance_id' => $request->quality_assurance_id,
            'language_id' => $request->language,
            'project_id' => $request->project_id,
        ]);

        return redirect()->back()->with('success', __('Folder was successfully updated'));
    }

    public function folderImages($id)
    {
        $folder = Folder::where('id', $id)->first();

        return view('admin.Images.folder.image', compact('folder'));
    }

    public function folderImageData(Request $request)
    {
        if ($request->ajax()) {
            $data = Image::where('folder_id', $request->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                     <a href="' . route("admin.image.edit", $row["id"]) . '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
                                     <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                 </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
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
                ->addColumn('image', function ($row) {
                    if ($row['image']) {
                        $path = $row['image'];
                        $image = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">' . $row["name"] . '</span></div>
                                  </div>';
                    }

                    return $image;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'status', 'image', 'folderName', 'created-on'])
                ->make(true);
        }
    }
    public function freeze(Request $request, $id){
        $folder = Folder::where('id',$id)->first();
        $folder->is_frozen  = 1;
        $folder->save();
        return redirect()->back()->with('success', __('Folder was freeze successfully updated'));

    }
    public function unFreeze(Request $request, $id){
        $folder = Folder::where('id',$id)->first();
        $folder->is_frozen  = 0;
        $folder->save();
        return redirect()->back()->with('success', __('Folder was un freeze successfully updated'));

    }
}
