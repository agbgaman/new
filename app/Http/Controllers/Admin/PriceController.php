<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrepaidPlan;
use App\Models\Price;
use Illuminate\Http\Request;
use DataTables;

class PriceController extends Controller
{
    public function index(Request $request){

        if ($request->ajax()) {
            $data = Price::all()->sortByDesc("created_at");
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function($row){
                    $actionBtn = '<div>
                                        <a href="'. route("admin.price.edit", $row["id"] ). '"><i class="fa-solid fa-file-pen table-action-buttons view-action-button" title="Update Plan"></i></a>
                                        <a class="deletePlanButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Plan"></i></a>
                                    </div>';
                    return $actionBtn;
                })
                ->addColumn('created-on', function($row){
                    $created_on = '<span>'.date_format($row["created_at"], 'd M Y').'</span>';
                    return $created_on;
                })
                ->addColumn('custom-status', function($row){
                    $custom_priority = '<span class="cell-box plan-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                    return $custom_priority;
                })
                ->addColumn('custom-text-price', function($row){
                    $custom_priority = '<span class="font-weight-bold">' . $row["text_price"] . ' ' . $row["currency"].'</span>';
                    return $custom_priority;
                })
                ->addColumn('custom-image-price', function($row){
                    $custom_priority = '<span class="font-weight-bold">' . $row["image_price"] . ' ' . $row["currency"].'</span>';
                    return $custom_priority;
                })
                ->addColumn('custom-coco-price', function($row){
                    $custom_priority = '<span class="font-weight-bold">' . $row["coco_price"] . ' ' . $row["currency"].'</span>';
                    return $custom_priority;
                })
                ->addColumn('custom-commission-price', function($row){
                    $custom_priority = '<span class="font-weight-bold">' . $row["commission"] . ' ' . $row["commission  "].'</span>';
                    return $custom_priority;
                })
                ->addColumn('custom-name', function($row){
                    $custom_name = '<span class="font-weight-bold">'.$row["price_name"].'</span>';
                    return $custom_name;
                })
                ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-coco-price', 'custom-image-price','custom-commission-price', 'custom-text-price', 'custom-name', 'custom-featured', 'custom-frequency'])
                ->make(true);

        }

        return view('admin.finance.price.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.finance.price.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        request()->validate([
            'price_status'  => 'required',
            'price_name'    => 'required',
            'image_price'   => 'required|numeric',
            'text_price'    => 'required|numeric',
            'coco_price'    => 'required|numeric',
            'commission'    => 'required|numeric',
            'currency'      => 'required',
        ]);

        $check = PrepaidPlan::where('plan_name', request('plan-name'))->first();

        if (!isset($check)) {

            $price = new Price([
                'status'        => request('price_status'),
                'price_name'    => request('price_name'),
                'currency'      => request('currency'),
                'text_price'    => request('text_price'),
                'coco_price'    => request('coco_price'),
                'commission'    => request('commission'),
                'image_price'   => request('image_price'),
            ]);

            $price->save();

            return redirect()->route('admin.price')->with("success", __('New Price has been created successfully'));

        } else {
            return redirect()->back()->with('error', __('Price name already exists, use different name'));
        }
    }
    public function edit(Request $request, $id){
        $price = Price::where('id',$id)->first();

        return view('admin.finance.price.edit',compact('price'));
    }
    public function update(Request $request, $id){
        request()->validate([
            'price_status'  => 'required',
            'price_name'    => 'required',
            'image_price'   => 'required|numeric',
            'text_price'    => 'required|numeric',
            'coco_price'    => 'required|numeric',
            'commission'    => 'required|numeric',
            'currency'      => 'required',
        ]);
        $price = Price::where('id',$id)->update([
            'status'        => request('price_status'),
            'price_name'    => request('price_name'),
            'currency'      => request('currency'),
            'text_price'    => request('text_price'),
            'coco_price'    => request('coco_price'),
            'commission'    => request('commission'),
            'image_price'   => request('image_price'),
        ]);

        return redirect()->route('admin.price')->with("success", __('New Price has been updated successfully'));
    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $price = Price::find(request('id'));
            if($price) {
                $price->delete();
                return response()->json('success');
            } else{
                return response()->json('error');
            }
        }
    }
}
