<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UseCase;
use DataTables;

class UseCaseController extends Controller
{
    /**
     * Show appearance settings page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = UseCase::all()->sortByDesc("created_at");
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div">
                                            <a href="'. route("admin.settings.usecase.edit", $row["id"] ). '"><i class="fa-solid fa-pencil-square table-action-buttons edit-action-button" title="Edit Review"></i></a>
                                            <a class="deleteReviewButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Review"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span>'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->rawColumns(['actions', 'created-on'])
                    ->make(true);
                    
        }

        return view('admin.frontend.usecase.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.frontend.usecase.create');
    }


    /**
     * Store usecase post properly in database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'text' => 'required',
        ]);

        if (request()->has('image')) {

            request()->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,bmp,tiff,gif,svg,webp|max:10048'
            ]);
            
            $image = request()->file('image');
            $name = Str::random(10);         
            $folder = 'img/usecases/';
            
            $this->uploadImage($image, $folder, 'public', $name);

            $path = $folder . $name . '.' . request()->file('image')->getClientOriginalExtension();
        }  
        
        if (request()->has('audio')) {

            request()->validate([
                'audio' => 'required|mimes:mp3|max:10048'
            ]);
            
            $image = request()->file('audio');
            $name = Str::random(10);         
            $folder = 'voices/frontend/';
            
            $this->uploadImage($image, $folder, 'public', $name);

            $audio_path = $folder . $name . '.' . request()->file('audio')->getClientOriginalExtension();
        } 

        $usecase = UseCase::create([
            'title' => $request->title,
            'text' => $request->text,
            'image_url' => $path,
            'audio_url' => $audio_path,
        ]);

        return redirect()->route('admin.settings.usecase')->with('success', __('Use Case successfully created'));
    }


    /**
     * Edit usecase.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(UseCase $id)
    {
        return view('admin.frontend.usecase.edit', compact('id'));
    }


    /**
     * Update usecase post properly in database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'title' => 'required',
            'text' => 'required',
        ]);

        if (request()->has('image')) {

            request()->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,bmp,tiff,gif,svg,webp|max:10048'
            ]);
            
            $image = request()->file('image');
            $name = Str::random(10);         
            $folder = 'img/usecases/';
            
            $this->uploadImage($image, $folder, 'public', $name);

            $path = $folder . $name . '.' . request()->file('image')->getClientOriginalExtension();

        } else {
            $path = '';
        }

        if (request()->has('audio')) {

            request()->validate([
                'audio' => 'required|mimes:mp3|max:10048'
            ]);
            
            $image = request()->file('audio');
            $name = Str::random(10);         
            $folder = 'voices/frontend/';
            
            $this->uploadImage($image, $folder, 'public', $name);

            $audio_path = $folder . $name . '.' . request()->file('audio')->getClientOriginalExtension();

        } else {
            $audio_path = '';
        }


        $usecase = UseCase::where('id', $id)->firstOrFail();
        $usecase->title = request('title');
        $usecase->image_url = ($path != '') ? $path : $usecase->image;
        $usecase->audio_url = ($audio_path != '') ? $audio_path : $usecase->audio;
        $usecase->text = request('text');
        $usecase->save();    

        return redirect()->route('admin.settings.usecase')->with('success', __('Use Case successfully updated'));
    }


    /**
     * Upload logo images
     */
    public function uploadImage(UploadedFile $file, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(5);

        $file->storeAs($folder, $name .'.'. $file->getClientOriginalExtension(), $disk);
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {  
        if ($request->ajax()) {

            $review = UseCase::find(request('id'));

            if($review) {

                $review->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        }    
    }

}
