<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use DataTables;

class FinanceSubscriptionPlanController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubscriptionPlan::all()->sortByDesc("created_at");          
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        $actionBtn = '<div>                                            
                                            <a href="'. route("admin.finance.plan.show", $row["id"] ). '"><i class="fa-solid fa-file-invoice-dollar table-action-buttons edit-action-button" title="View Plan"></i></a>
                                            <a href="'. route("admin.finance.plan.edit", $row["id"] ). '"><i class="fa-solid fa-file-pen table-action-buttons view-action-button" title="Update Plan"></i></a>
                                            <a class="deletePlanButton" id="'. $row["id"] .'" href="#"><i class="fa-solid fa-trash-xmark table-action-buttons delete-action-button" title="Delete Plan"></i></a>
                                        </div>';
                        return $actionBtn;
                    })
                    ->addColumn('created-on', function($row){
                        $created_on = '<span class="font-weight-bold">'.date_format($row["created_at"], 'd M Y').'</span>';
                        return $created_on;
                    })
                    ->addColumn('custom-status', function($row){
                        $custom_priority = '<span class="cell-box plan-'.strtolower($row["status"]).'">'.ucfirst($row["status"]).'</span>';
                        return $custom_priority;
                    })
                    ->addColumn('frequency', function($row){
                        $custom_status = '<span class="cell-box payment-'.strtolower($row["pricing_plan"]).'">'.ucfirst($row["pricing_plan"]).'</span>';
                        return $custom_status;
                    })
                    ->addColumn('custom-cost', function($row){
                        $custom_cost = '<span class="font-weight-bold">'.$row["price"] . ' ' . $row["currency"].'</span>';
                        return $custom_cost;
                    })
                    ->addColumn('custom-characters', function($row){
                        $custom_storage = '<span class="font-weight-bold">'.number_format($row["characters"], 0, 2).'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-minutes', function($row){
                        $custom_storage = '<span class="font-weight-bold">'.number_format($row["minutes"], 0, 2).'</span>';
                        return $custom_storage;
                    })
                    ->addColumn('custom-name', function($row){
                        $custom_name = '<span class="font-weight-bold">'.$row["plan_name"].'</span>';
                        return $custom_name;
                    })
                    ->addColumn('custom-featured', function($row){
                        $icon = ($row['featured'] == true) ? '<i class="fa-solid fa-circle-check text-success fs-16"></i>' : '<i class="fa-solid fa-circle-xmark fs-16"></i>';
                        $custom_featured = '<span class="font-weight-bold">'.$icon.'</span>';
                        return $custom_featured;
                    })
                    ->addColumn('custom-free', function($row){
                        $icon = ($row['free'] == true) ? '<i class="fa-solid fa-circle-check text-success fs-16"></i>' : '<i class="fa-solid fa-circle-xmark fs-16"></i>';
                        $custom_featured = '<span class="font-weight-bold">'.$icon.'</span>';
                        return $custom_featured;
                    })
                    ->addColumn('custom-tasks', function($row){
                        $tasks = ($row['synthesize_tasks'] == -1) ? '<span class="font-weight-bold">Unlimited</span>' : '<span class="font-weight-bold">' . $row['synthesize_tasks'] . '</span>';
                        return $tasks;
                    })
                    ->addColumn('custom-voices', function($row){
                        $custom_voice = '<span class="cell-box voice-'.strtolower($row["voice_type"]).'">'.ucfirst($row["voice_type"]).'</span>';
                        return $custom_voice;
                    })
                    ->rawColumns(['actions', 'custom-status', 'created-on', 'custom-cost', 'frequency', 'custom-characters', 'custom-minutes', 'custom-name', 'custom-featured', 'custom-free', 'custom-tasks', 'custom-voices'])
                    ->make(true);
                    
        }

        return view('admin.finance.plans.subscription.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.finance.plans.subscription.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'plan-status' => 'required',
            'plan-name' => 'required',
            'cost' => 'required|numeric',
            'currency' => 'required',
            'characters' => 'required|integer',
            'minutes' => 'required|integer',
            'duration' => 'required'
        ]);

        $check = SubscriptionPlan::where('plan_name', request('plan-name'))->first();
    
        if (!isset($check)) {

            if (isset($request->unlimited)) {
                $synthesize_tasks = -1;
            } else {
                $synthesize_tasks = request('synthesize-task');
            }
            
            $plan = new SubscriptionPlan([
                'paypal_gateway_plan_id' => request('paypal_gateway_plan_id'),
                'stripe_gateway_plan_id' => request('stripe_gateway_plan_id'),
                'paystack_gateway_plan_id' => request('paystack_gateway_plan_id'),
                'razorpay_gateway_plan_id' => request('razorpay_gateway_plan_id'),
                'status' => request('plan-status'),
                'plan_name' => request('plan-name'),
                'price' => request('cost'),
                'currency' => request('currency'),
                'characters' => request('characters'),
                'minutes' => request('minutes'),
                'pricing_plan' => request('duration'),
                'primary_heading' => request('primary-heading'),
                'featured' => request('featured'),
                'plan_features' => request('features'),
                'free' => request('free-plan'),
                'voice_type' => request('voice-type'),
                'synthesize_tasks' => $synthesize_tasks,
            ]); 
                   
            $plan->save();            
    
            return redirect()->route('admin.finance.plans')->with("success", __('New subscription plan has been created successfully'));
        
        } else {
            return redirect()->back()->with('error', __('Subscription plan name already exists, use different plan name'));
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionPlan $id)
    {
        return view('admin.finance.plans.subscription.show', compact('id'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SubscriptionPlan $id)
    {
        return view('admin.finance.plans.subscription.edit', compact('id'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubscriptionPlan $id)
    {
        request()->validate([
            'plan-status' => 'required',
            'plan-name' => 'required',
            'cost' => 'required|numeric',
            'currency' => 'required',
            'characters' => 'required|integer|min:0',
            'minutes' => 'required|integer|min:0',
            'duration' => 'required',
        ]);

        if (isset($request->unlimited)) {
            $synthesize_tasks = -1;
        } else {
            $synthesize_tasks = request('synthesize-task');
        }

        $id->update([
            'paypal_gateway_plan_id' => request('paypal_gateway_plan_id'),
            'stripe_gateway_plan_id' => request('stripe_gateway_plan_id'),
            'paystack_gateway_plan_id' => request('paystack_gateway_plan_id'),
            'razorpay_gateway_plan_id' => request('razorpay_gateway_plan_id'),
            'status' => request('plan-status'),
            'plan_name' => request('plan-name'),
            'price' => request('cost'),
            'currency' => request('currency'),
            'characters' => request('characters'),
            'minutes' => request('minutes'),
            'pricing_plan' => request('duration'),
            'primary_heading' => request('primary-heading'),
            'featured' => request('featured'),
            'plan_features' => request('features'),
            'free' => request('free-plan'),
            'voice_type' => request('voice-type'),
            'synthesize_tasks' => $synthesize_tasks,
        ]); 

        return redirect()->route('admin.finance.plans')->with("success", __("Selected plan has been updated successfully"));
        
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

            $plan = SubscriptionPlan::find(request('id'));

            if($plan) {

                $plan->delete();

                return response()->json('success');

            } else{
                return response()->json('error');
            } 
        }
    }

}
