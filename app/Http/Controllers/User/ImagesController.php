<?php

namespace App\Http\Controllers\User;

use App\Events\PayoutRequested;
use App\Events\ProjectCompleteEvent;
use App\Http\Controllers\Controller;
use App\Jobs\UploadImageOnAWSBucket;
use App\Models\Folder;
use App\Models\Image;
use App\Models\User;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use Yajra\DataTables\DataTables;

class ImagesController extends Controller
{
    public function index()
    {
        return view('user.Images.image.index');
    }

    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {

        if ($request->rejected) {
            $images = Image::where('user_id', auth()->user()->id)
                ->where('status', 'inactive')
                ->where('folder_id', $request->id)
                ->get();
        } elseif ($request->page != 1) {
            $page = $request->page - 1;
            $skip = $page * $request->per_page;
            $next = $request->per_page;

            $images = Image::where('user_id', auth()->user()->id)
                ->where('folder_id', $request->id)
                ->where('status', '!=', 'inactive')
                ->skip($skip)
                ->take($next)->get();
        } else {

            $images = Image::where('user_id', auth()->user()->id)
                ->where('status', '!=', 'inactive')
                ->where('folder_id', $request->id)
                ->take('8')->get();

        }
        return response()->json([
            'images' => $images,
        ]);

    }

    public function tableList(Request $request)
    {

        if ($request->ajax()) {
            if ($request->rejected) {
                $data = Image::where('user_id', auth()->user()->id)->where('status', 'inactive')->where('folder_id', $request->id)->latest()->get();
            } else {
                $data = Image::where('user_id', auth()->user()->id)->where('status', '!=', 'inactive')->where('folder_id', $request->id)->latest()->get();
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="image-checkbox" data-image-id="' . $row['id'] . '">';
                })
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div>
                                       <a class="deleteUserButton" id="' . $row["id"] . '" href="#"><i class="fa-solid fa-trash table-action-buttons delete-action-button" title="Delete Image"></i></a>
                                 </div>';
                    return $actionBtn;
                })
                ->addColumn('name', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["name"] . '"">' . ucfirst($row["name"]) . '</span>';
                    return $name;
                })
                ->addColumn('comment', function ($row) {
                    $name = '<span class="font-weight-bold-' . $row["coment"] . '"">' . ucfirst($row["comment"]) . '</span>';
                    return $name;
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
                        $folderName = '<span>' . Folder::find($row["folder_id"])->name . '</span>';
                        return $folderName;
                    } else {
                        return $row["folder_id"];
                    }
                })
                ->addColumn('image', function ($row) {
                    if ($row['image']) {
                        $path = $row['image'];
                        $image = '<div class="d-flex">
                    <div class="widget-user-image-sm overflow-hidden mr-4">
                        <img class="image-click" alt="Avatar" src="' . $path . '" width="50" height="50">
                    </div>

                  </div>';
                    } else {
                        $image = '';
                    }
                    return $image;
                })
                ->addColumn('created-on', function ($row) {
                    $timezone = auth()->user()->timezone; // Assuming the user's timezone is stored in the "timezone" column
                    $created_at = $row['created_at']->setTimezone($timezone);
                    $created_on = '<span>' . date_format($created_at, 'd M Y H:i:s A') . '</span>';
                    return $created_on;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'custom-status', 'image', 'folderName', 'checkbox', 'comment','created-on'])
                ->make(true);
        }
    }

    public function create()
    {
        $folders = Folder::where('user_id', auth()->user()->id)->get();
        return view('user.Images.image.create', compact('folders'));
    }

    public function store(Request $request)
    {

        $imagePaths = [];
        foreach ($request->file('image') as $key => $image) {

            $filename = $image[$key]->getClientOriginalName();
            $path = $image[$key]->storeAs('temp', $filename);

            $imagePaths[] = $path;
        }

        dispatch(new UploadImageOnAWSBucket(
            $request->imageName,
            auth()->id(),
            $request->status,
            $request->folder,
            $imagePaths
        ))->onQueue('default');
        $folder = Folder::where('id', $request->folder)->first();
        $user = User::where('id', auth()->user()->id)->firstOrFail();
        $subject = "New Images upload in New COCO Folder " . $folder->name . " Please check";
        event(new ProjectCompleteEvent($user, $subject));

        return response()->json('success');

    }

    public function convertHeicToJpeg(Request $request)
    {
        // Retrieve the uploaded HEIC image from the request
        $heicImage = $request->file('image');

        // Create a new Image instance from the HEIC image
        $image = ImageManagerStatic::make($heicImage);

        // Convert the HEIC image to JPEG format
        $image->encode('jpg');

        // Save the converted JPEG image to a specific location
        $image->save('path/to/save/converted-image.jpg');
    }

    public function delete(Request $request)
    {
        $image = Image::where('id', $request->id)->first();
        if ($image) {
            $image->delete();

            return response()->json('success');

        } else {
            return response()->json('error');
        }
    }

    public function edit($id)
    {
        $folders = Folder::where('user_id', auth()->user()->id)->get();
        $image = Image::where('id', $id)->first();
        return view('user.Images.image.edit', compact('folders', 'image'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'image' => 'required',
            'status' => 'required',
            'folder_id' => 'required',

        ]);
        // Get the image from the request
        $image = $request->file('image');
        // Get the client for the S3 bucket
        $s3 = S3Client::factory([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest'
        ]);
        // Upload the image to the S3 bucket
        $result = $s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $image->getClientOriginalName(),
            'SourceFile' => $image->getRealPath(),
            'ACL' => 'public-read'
        ]);

        $url = $s3->getObjectUrl(env('AWS_BUCKET'), $image->getClientOriginalName());

        Image::where('id', $id)->update([
            'name' => $request->name,
            'image' => $url,

            'user_id' => auth()->id(),
            'status' => $request->status,
            'folder_id' => $request->folder_id
        ]);

        return redirect()->back()->with('success', __('Image was successfully updated'));
    }

    public function comment(Request $request)
    {
        return Image::where('id', $request->id)->with('remark')->first();

    }

    public function deleteMultipleImages(Request $request)
    {
        $imageIds = $request->input('image_ids');

        if (is_array($imageIds)) {
            // Delete folders with the given IDs
            Image::whereIn('id', $imageIds)->delete();
        }

        return response()->json(['status' => 'success']);
    }
    public function nextImage(Request $request){
        $direction = $request->direction === 'next' ? 'desc' : 'asc';

        if ($request->status == 'active'){
            $image =  Image::where('id', $direction === 'desc' ? '<' : '>', $request->image_id)
                ->where('folder_id', $request->folder_id)
//                ->where('status','!=','inactive')
                ->with('folder.qualityAssurance', 'user','remark')
                ->orderBy('id', $direction)
                ->firstOrFail();
        } else {
            $image = Image::where('id', $direction === 'desc' ? '>' : '<', $request->image_id)
                ->where('folder_id', $request->folder_id)
                ->where('status','inactive')
                ->with('folder.qualityAssurance', 'user')
                ->orderBy('id', $direction)
                ->firstOrFail();

        }
        if ($image) {
            return response()->json($image);
        } else {
            return response()->json(['message' => 'No more images found.'], 404);
        }
    }
}
