<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UploadImageOnAWSBucket;
use App\Models\Folder;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;
use Aws\S3\S3Client;

class ImagesController extends Controller
{
    public function index(){
        return view('admin.Images.image.index');
    }

    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Image::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn ='<div>
                                     <a href="'. route("admin.image.edit", $row["id"] ). '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
                                     <a class="deleteUserButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                 </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function($row){
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('name', function($row){
                    $name = '<span class="font-weight-bold-'.$row["name"].'"">'.ucfirst($row["name"]).'</span>';
                    return $name;
                })
                ->addColumn('status', function($row){
                    $status = '<span class="cell-box user-'.$row["status"].'">'.ucfirst($row["status"]).'</span>';
                    return $status;
                })
                ->addColumn('folderName', function($row){
                    if ($row["folder_id"]) {
                        $folder = Folder::where('id',$row["folder_id"])->first();
                        if ($folder){
                            $folderName = '<span>'.Folder::find($row["folder_id"])->name.'</span>';
                            return $folderName;
                        } else {
                            return $row["folder_id"];
                        }
                    } else {
                        return $row["folder_id"];
                    }
                })
                ->addColumn('image', function($row){
                    if ($row['image']) {
                        $path = $row['image'];
                        $image = '<div class="d-flex">
                                    <div class="widget-user-image-sm overflow-hidden mr-4"><img alt="Avatar" src="' . $path . '"></div>
                                    <div class="widget-user-name"><span class="font-weight-bold">'. $row["name"] .'</span></div>
                                  </div>';
                    }

                    return $image;
                })
                ->rawColumns(['actions', 'name', 'assign_user', 'status','image','folderName','created-on'])
                ->make(true);
        }

        return view('admin.Images.image.index');
    }
    public function create(){
        $folders = Folder::where('status','active')->get();
        return view('admin.Images.image.create',compact('folders'));
    }
    public function store(Request $request) {

        $this->validate($request,[
            'imageName'   => 'required',
            'image'       => 'required',
            'status'      => 'required',
            'folder'      => 'required',
        ]);

        $imagePaths = [];
        foreach ($request->file('image') as $image) {
            $filename   = $image->getClientOriginalName();
            $path       = $image->storeAs('temp', $filename);

            $imagePaths[] = $path;
        }

        dispatch(new UploadImageOnAWSBucket($request->imageName,auth()->id(), $request->status,$request->folder, $imagePaths ))->onQueue('default');

        return response()->json('success');

    }
    public function delete(Request $request){
        $image = Image::where('id',$request->id)->first();
        if($image) {
            $image->delete();

            return response()->json('success');

        } else {
            return response()->json('error');
        }
    }

    public function edit($id) {
        $image   = Image::where('id',$id)->first();
        $folders = Folder::where('status','active')->get();
        return view('admin.Images.image.edit',compact('folders','image'));
    }
    public function update(Request $request, $id) {
        $this->validate($request,[
            'name'        => 'required',
            'image'       => 'required',
            'status'      => 'required',
            'folder_id'   => 'required',

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

        Image::where('id',$id)->update([
            'name'          => $request->name,
            'image'         => $url,
            'user_id'       => auth()->id(),
            'status'        => $request->status,
            'folder_id'     => $request->folder_id
        ]);

        return redirect()->back()->with('success',__('Image was successfully updated'));
    }
}
