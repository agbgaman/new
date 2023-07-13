<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Image;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\User;
use League\Csv\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ImagesFolderController extends Controller
{
    public function index($name)
    {
        $project = Project::where('name',$name)->first();
        if ($project) {
            $data = Folder::where('user_id', auth()->user()->id)->where('project_id',$project->id)->withCount('images')->latest()->get();
            return view('user.Images.folder.index', compact('data','project'));
        } else {
            return redirect()->back();
        }
    }
    public function smsIndex()
    {
        $project = Project::where('id', 20)->first();
        $projectApplication = ProjectApplication::where('user_id', auth()->user()->id)->where('status','Approved')->where('project_id',$project->id)->first();

        if ($projectApplication && $projectApplication->appliedForm == null && $project->consent_form != null){
            $appliedForm = 1;
        } else {
            $appliedForm = 0;
        }
        $data = Folder::where('user_id', auth()->user()->id)->withCount('images')->latest()->get();
        return view('user.Images.folder.indexSMS', compact('data','appliedForm','project','projectApplication'));
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

            $data = Folder::where('user_id', auth()->user()->id)->where('project_id',$request->project_id)->withCount('images')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="folder-checkbox " data-folder-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    $actionBtn = '';
                    if ($row['status'] != 'paid') {
                        $actionBtn = '<div>
                        <a class="deleteUserButton" id="' . $row['id'] . '" href="#"><i class="fa-solid fa-trash table-action-buttons delete-action-button" title="Delete Folder"></i></a>
                     </div>';
                    }
                    return $actionBtn;
                })
                ->addColumn('name', function ($row) {
                    $name = '<a href="' . route("user.images.folder.edit", $row["id"]) . '"><span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span></a>';
                    return $name;
                })
                ->addColumn('images_count', function ($row) {
                    $images_count = '<a href="' . route("user.images.folder.edit", $row["id"]) . '" class="folder-link all-images"><i class="fas fa-folder folder-icon-list "></i><span class="font-weight-bold-' . $row["images_count"] . '"">' . ucfirst($row["images_count"]) . '</span></a>';
                    return $images_count;
                })
                ->addColumn('accepted_image', function ($row) {
                    $acceptedImage = Image::where('folder_id', $row['id'])->where('status', 'active')->count();
                    $accepted_image = '<a href="' . route("user.images.folder.edit", $row["id"]) . '" class="folder-link accepted"><i class="fas fa-folder folder-icon-list "></i><span class="font-weight-bold-' . $acceptedImage . '"">' . ucfirst($acceptedImage) . '</span></a>';
                    return $accepted_image;
                })
                ->addColumn('rejected_image', function ($row) {
                    $rejectedImage = Image::where('folder_id', $row['id'])->where('status', 'inactive')->count();
                    $rejected_image = '<a href="' . route("user.images.folder.rejected-images", $row["id"]) . '" class="folder-link rejected"><i class="fas fa-folder folder-icon-list "></i><span class="font-weight-bold-' . $rejectedImage . '"">' . ucfirst($rejectedImage) . '</span></a>';
                    return $rejected_image;
                })
                ->addColumn('status', function ($row) {
                    $status = '<span class="cell-box user-' . $row["status"] . '">' . ucfirst($row["status"]) . '</span>';
                    return $status;
                })
                ->addColumn('created-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['created_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })

                ->rawColumns(['actions', 'name', 'assign_user', 'status', 'assignUser', 'images_count', 'language', 'text_count', 'accepted_image', 'rejected_image', 'checkbox','created-on'])
                ->make(true);
        }

        return view('user.Images.folder.index');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {
            request()->validate([
                'name' => 'required'
            ]);

            if (strtolower(request('name') == 'name')) {
                return response()->json(['status' => 'error', 'message' => __('Folder Name is reserved and is already created, please create another one')]);
            }

            $check = Folder::where('name', request('name'))->where('user_id', auth()->user()->id)->first();

            if (!isset($check)) {
                $folder = Folder::create([
                    'name'          => auth()->user()->name.'-'.$request->name,
                    'user_id'       => auth()->user()->id,
                    'project_id'    => $request->project_id,
                    'status'        => 'in_progress'
                ]);

                return response()->json([
                    'status'        => 'success',
                    'message'       => 'Folder name updated successfully.',
                    'folder_id'     => $folder->id,
                    'folder_name'   => ucfirst($folder->name)
                ]);

            } else {
                return response()->json(['status' => 'error', 'message' => __('Folder name already exists')]);
            }
        }
    }

    public function updateFolderName(Request $request)
    {
        $folderId = $request->input('id');
        $newName = $request->input('name');

        if (strtolower(request('name') == 'name')) {
            return response()->json(['status' => 'error', 'message' => __('Folder Name is reserved and is already created, please create another one')]);
        }
        $check = Folder::where('name', request('name'))->first();

        if (!isset($check)) {
            $folder = Folder::find($folderId);

            if ($folder) {
                $folder->name = $newName;
                $folder->save();

                return response()->json(['status' => 'success', 'message' => 'Folder name updated successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Folder not found.']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => __('Folder name already exists')]);
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
        $folder  = Folder::where('id', $id)->first();
        $project = Project::where('id', $folder->project_id)->first();
        $image   = Image::where('folder_id', $id)->where('status', 'inactive')->count();
        return view('user.Images.folder.edit', compact('folder', 'image','project'));
    }

    public function update(Request $request, $id)
    {
        $folder = Folder::where('id', $id)->first();

        Folder::where('id', $id)->update([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', __('Folder was successfully updated'));
    }

    public function rejectedImages($id)
    {
        $folder  = Folder::where('id', $id)->first();
        $project = Project::where('id', $folder->project_id)->first();

        return view('user.Images.folder.rejected_images', compact('folder','project'));
    }


    public function deleteMultipleFolders(Request $request)
    {
        $folderIds = $request->input('folder_ids');

        if (is_array($folderIds)) {
            // Delete folders with the given IDs
            Folder::whereIn('id', $folderIds)->delete();
        }

        return response()->json(['status' => 'success']);
    }

    public function exportMultipleFolders(Request $request)
    {
        $folderIds = json_decode($request->input('folder_ids'), true);

        if (is_array($folderIds)) {
            $folders = Folder::whereIn('id', $folderIds)->with('images')->get();

            // Create a new CSV writer instance
            $csv = Writer::createFromString('');

            // Set the header for the CSV
            $csv->insertOne([ 'Folder Name', 'Image Name', 'Image URL', 'Image Status', 'Image Comment','Date']);

            // Loop through each folder and its images and insert into the CSV
            foreach ($folders as $folder) {
                $folderName = $folder->name;

                foreach ($folder->images as $image) {
                    $imageName      = $image->name.$image->id;
                    $imageUrl       = $image->image;
                    $imageStatus    = $image->status;
                    $imageComment   = $image->comment;
                    $imageDate   = $image->created_at->format('Y-m-d');;

                    $csv->insertOne([
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
                'Content-type'          => 'text/csv',
                'Content-Disposition'   => 'attachment; filename=' . $csvFilename,
                'Pragma'                => 'no-cache',
                'Cache-Control'         => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'               => '0',
            ];

            // Return the CSV as a response
            return response((string)$csv, 200, $headers);
        }

        // If folderIds is not an array, return an error response or redirect
        return redirect()->back()->withErrors(['error' => 'No valid folder IDs provided']);
    }
}
