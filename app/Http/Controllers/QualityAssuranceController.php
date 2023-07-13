<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Image;
use App\Models\Project;
use App\Models\ProjectRemark;
use App\Models\TranscribeResult;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class QualityAssuranceController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Folder::withCount('images', 'text')->where('quality_assurance_id', auth()->user()->id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="folder-checkbox" data-folder-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    if ($row['status'] == 'paid') {
                        $actionBtn = '<div>
                                           <a class="downloadButton" href="'. route("admin.coco.userFolderDownloasadasdad", $row['id'] ). '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                      </div>';
                        return $actionBtn;
                    } else {
                        $actionBtn = '<div>

                                           <a class="downloadButton" href="'. route("admin.coco.userFolderDownloasadasdad", $row['id'] ). '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                           <a class="agreeStatus" id="' . $row["id"] . '" href="#"><i class="fa fa-check table-action-buttons request-action-button" title="Approve Status"></i></a>

                                      </div>';
                        return $actionBtn;
                    }
                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('images_count', function ($row) {
                    $images_count = '<a href="' . route("qa.coco-images", $row["id"]) . '"><span class="font-weight-bold-' . $row["images_count"] . '"">' . ucfirst($row["images_count"]) . '</span></a>';
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
                ->addColumn('Status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->addColumn('updated-on', function ($row) {
                    $last_seen = '<span class="font-weight-bold">' . \Carbon\Carbon::parse($row['updated_at'])->format('d M Y h:i:s A') . '</span>';
                    return $last_seen;
                })
                ->rawColumns(['actions', 'name', 'checkbox', 'assign_user', 'updated-on','status', 'assignUser', 'images_count', 'language', 'text_count', 'accepted_image', 'rejected_image'])
                ->make(true);
        }
        return view('quality_assurance.coco.folder');
    }
    public function images($id){
        $folder = Folder::where('id', $id)->first();
        $project = Project::where('id', $folder->project_id)->first();
        if ($project){
            $projectRemarks = ProjectRemark::where('project_id', $project->id)->get();
        } else {
            $projectRemarks = null;
        }
        return view('quality_assurance.coco.image',compact('id','projectRemarks'));
    }
    public function imagesList(Request $request)
    {
        if ($request->ajax()) {

            $data = Image::where('folder_id', $request->folder_id)
                ->orderBy('id', 'desc')->get();

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
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y H:i:s') . '</span>';
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
                ->rawColumns(['actions', 'name', 'assign_user', 'custom-status', 'image', 'folderName', 'created-on', 'username', 'image_link', 'imageUserName'])
                ->make(true);
        }
        return view('admin.Images.coco.index');
    }
    public function approveStatus(Request $request){
        if ($request->ajax()) {

            $folder = Folder::where('id', request('id'))->firstOrFail();

            if ($folder->status == 'complete') {
                return  response()->json('COMPLETED');
            }

            $folder->update(['status' => 'complete']);

            return  response()->json('success');
        }
    }
}
