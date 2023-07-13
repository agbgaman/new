<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function project(Request $request){

        if ($request->ajax()) {
            $data = Folder::withCount('images', 'text')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="folder-checkbox" data-folder-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    if ($row['status'] == 'paid') {
                        $actionBtn = '<div>
                                         <a class="downloadButton" href="' . route("admin.coco.userImageDownload", $row['id']) . '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                         <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                      </div>';
                        return $actionBtn;
                    } else {
                        $actionBtn = '<div>
                                           <a class="downloadButton" href="' . route("admin.coco.userImageDownload", $row['id']) . '" ><i class="fa fa-download table-action-buttons download-action-button" title="Download"></i></a>
                                           <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>

                                      </div>';
                        return $actionBtn;
                    }
                })
                ->addColumn('name', function ($row) {
                    $name = '<a href="' . route("admin.coco.userImageList", $row["id"]) . '"><span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span></a>';
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
                ->addColumn('user-name', function ($row) {
                    if ($row["user_id"]) {
                        $user = User::find($row["user_id"]);
                        if ($user) {
                            $qualityAssurance = '<a href="' . route("admin.coco.userFolderList", $row["id"]) . '"><span>' . $user->name . '</span></a>';
                            return $qualityAssurance;
                        } else {
                            return $row["user_id"];
                        }
                    } else {
                        return $row["user_id"];
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
                ->addColumn('qa-percentage', function ($row) {
                    $InProgressImage = Image::where('folder_id', $row['id'])->where('status', 'Pending')->count();
                    $totalImage      = $row["images_count"];
                    if ($totalImage == 0) {
                        $qaPercentage = 0;
                    } else {
                        $qaPercentage    = round(($InProgressImage * 100) / $totalImage, 2);
                    }
                    $accepted_image = '<span class="font-weight-bold-' . $qaPercentage . '"">' . ucfirst($qaPercentage) .'%'. '</span>';
                    return $accepted_image;
                })
                ->addColumn('rejected_image', function ($row) {
                    $rejectedImage = Image::where('folder_id', $row['id'])->where('status', 'inactive')->count();
                    $rejected_image = '<span class="font-weight-bold-' . $rejectedImage . '"">' . ucfirst($rejectedImage) . '</span>';
                    return $rejected_image;
                })
                ->addColumn('status', function ($row) {
                    $cleanedStatus = str_replace("_", " ", $row["status"]);
                    $capitalizedStatus = ucfirst($cleanedStatus);
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . $capitalizedStatus . '</span>';
                    return $status;
                })
                ->rawColumns(['actions', 'name','user-name','qa-percentage', 'checkbox', 'assign_user', 'qualityAssurance', 'status', 'assignUser', 'images_count', 'language', 'text_count', 'accepted_image', 'rejected_image'])
                ->make(true);
        }

        return view('admin.Report.project');
    }
}
