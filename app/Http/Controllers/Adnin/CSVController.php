<?php

namespace App\Http\Controllers\Adnin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Image;
use App\Models\TextModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use function GuzzleHttp\Promise\all;

class CSVController extends Controller
{
    public function index() {
        return view('admin.Images.csv.index');
    }
    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = TextModel::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn ='<div>
                                     <a href="'. route("admin.csv.edit", $row["id"] ). '"><i class="fa-solid fa-user-pen table-action-buttons edit-action-button" title="Edit User Group"></i></a>
                                     <a class="deleteUserButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-user-slash table-action-buttons delete-action-button" title="Delete User"></i></a>
                                 </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function($row){
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('text', function($row){
                    $text = '<span class="font-weight-bold-'.$row["text"].'"">'.ucfirst($row["text"]).'</span>';
                    return $text;
                })
                ->addColumn('name', function($row){
                    $text = '<span class="font-weight-bold-'.$row["name"].'"">'.ucfirst($row["name"]).'</span>';
                    return $text;
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
                ->rawColumns(['actions', 'name', 'status','text','folderName','created-on'])
                ->make(true);
        }

        return view('admin.Images.csv.index');
    }
    public function create(){
        $folders = Folder::where('status','active')->get();
        return view('admin.Images.csv.create',compact('folders'));
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
        try {
            DB::beginTransaction();

            foreach ($data as $text) {
                TextModel::create([
                    'text'      => $text['text'],
                    'name'      => $text['name'],
                    'folder_id' => $request->folder_id,
                    'user_id'   => auth()->id(),
                    'status'    => $request->status,
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', __('CSV Text was successfully created'));
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error message or return it to the user
            return redirect()->back()->with('error', __('An error occurred while creating the CSV Text: ' . $e->getMessage()));
        }
    }


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

                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
    public function delete(Request $request){
        $text = TextModel::where('id',$request->id)->first();
        if($text) {
            $text->delete();
            return response()->json('success');
        } else {
            return response()->json('error');
        }
    }
    public function edit($id) {
        $folders = Folder::where('status','active')->get();
        $text    = TextModel::where('id',$id)->first();
        return view('admin.Images.csv.edit',compact('folders','text'));
    }
    public function update(Request $request,$id){

        $this->validate($request,[
            'text'      => 'required',
            'name'      => 'required',
            'status'    => 'required',
            'folder_id' => 'required',
        ]);

        TextModel::where('id',$id)->update([
            'text'          => $request->text,
            'name'          => $request->name,
            'folder_id'     => $request->folder_id,
            'status'        => $request->status,
        ]);

        return redirect()->back()->with('success',__('CSV Text was successfully updated'));
    }
}
