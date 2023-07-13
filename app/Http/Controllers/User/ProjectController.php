<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectApplication;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $country = auth()->user()->country;
            $data = Project::all();

            $country = auth()->user()->country;
            $data = Project::all();

            $data = $data->filter(function ($project) use ($country) {
                if ($project->country) {
                    return str_contains($project->country, $country);
                } else {
                    return true; // Show all projects when $country or $project->country is null
                }
            });


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $application = ProjectApplication::where('user_id', auth()->user()->id)->where('project_id', $row["id"])->first();
                    if ($application) {
                        $status = $application->status;

                        if ($status == "pending") {
                            $statusSpan = '<a class="dropdown-item text-warning" href=""><i class="fas fa-clock"></i> Pending</a>';
                        } elseif ($status == "Approved") {
                            $statusSpan = '<a class="dropdown-item text-success" href=""><i class="fas fa-check"></i> Accepted</a>';
                        } else {
                            $statusSpan = '<a class="dropdown-item text-danger" href=""><i class="fas fa-times"></i> Failed</a>';
                        }


                        $actionBtn = '
                            <div class="dropdown">
                                    <button class="btn table-actions" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #e8e8ec">
                                        Options <i class="fas fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="' . route("user.project.details", $row["id"]) . '">More Info</a></li>
                                        <li>' . $statusSpan . '</li>
                                    </ul>
                                </div>';
                            } else {
                            $actionBtn = '
                            <div class="dropdown">
                                <button class="btn table-actions" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #e8e8ec">
                                    Options <i class="fas fa-angle-down"></i>
                                </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="' . route("user.project.details", $row["id"]) . '">More Info</a></li>';

                        if (!isset($row["term_and_condition"]) && empty($row["term_and_condition"])) {
                            $actionBtn .= '<li><a class="dropdown-item" href="' . route("user.project.applied", $row["id"]) . '">Apply</a></li>';
                        } else {
                            $actionBtn .= '<li><a class="dropdown-item applyButton" data-project-id="' . $row['id'] . '">Apply</a></li>';
                        }

                        $actionBtn .= '
                                        </ul>
                                    </div>';
                    }



                    return $actionBtn;
                })
                ->addColumn('name', function ($row) {
                    $formattedName = str_replace('_', ' ', $row["name"]);
                    $text = '<span class="font-weight-bold-' . $formattedName . '">' . ucfirst($formattedName) . '</span>';
                    return $text;
                })
                ->addColumn('price', function ($row) {
                    if ( $row['price']){
                        $price =  $row['price'] .'$/per task ';
                    } else {
                        $price = null;
                    }
                    $text = '<span class="font-weight-bold-' . $row['price'] . '">' . $price . '</span>';
                    return $text;
                })
                ->addColumn('created-on', function ($row) {
                    $datetime = $row["created_at"];
                    $userTimezone = auth()->user()->timezone;

                    if ($userTimezone) {
                        // Assuming $datetime is in UTC timezone
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $userTimezone);
                        // Now convert the date to user's timezone
                        $date->setTimezone($userTimezone);
                    } else {
                        // If no user timezone is set, use UTC
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $datetime, 'UTC');
                    }

                    // Now you can format the date
                    $formattedDate = $date->format('d M Y H:i:s');
                    $created_on = '<span>' . $formattedDate  . '</span>';
                    return $created_on;
                })
                ->addColumn('description', function ($row) {
                    $description = $row['short_description'];
                    $description = '<div class=""><span class="">' . $description . '</span></div>';
                    return $description;
                })
                ->rawColumns(['actions', 'name', 'created-on', 'description','price'])
                ->make(true);
        }
        $participant_name = auth()->user()->name;
        $current_date = date('d M Y');
        return view('user.project.index', compact('participant_name', 'current_date'));
    }

    /**
     * @return void
     */
    public function details($id)
    {
        $project = Project::where('id', $id)->first();
        return view('user.project.show', compact('project'));
    }

    public function projectApply(Request $request)
    {

        $project = ProjectApplication::create([
            'user_id' => auth()->id(),
            'project_id' => $request->projectId,
            'status' => 'pending',
            'contract_form' => $request->contract_form,
        ]);
        return redirect()->back();
    }

    public function projectApplied(Request $request, $id)
    {
        $project = ProjectApplication::create([
            'user_id' => auth()->id(),
            'project_id' => $id,
            'status' => 'pending',

        ]);
        return redirect()->back();
    }

    public function consentForm(Request $request)
    {

        $project = ProjectApplication::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'project_id' => $request->projectId,
            ],
            [
                'status' => 'Approved',
                'appliedForm' => $request->consentFormContent,
            ]
        );

        return;
    }

    public function assignProjects($id)
    {
        $project = Project::where('id', $id)->first();
        if ($project->type == 'image_to_speech') {
            return redirect()->route('user.transcribe.assign-folder', $project->id);
        } elseif ($project->type == 'text_to_speech') {
            return redirect()->route('user.transcribe.assign-text', $project->id);
        } elseif ($project->type == 'text_to_text') {
            return redirect()->route('user.transcribe.assign-text-to-text', $project->id);
        } elseif ($project->type == 'image') {
//            dd($project->type);
            return redirect()->route('user.images.folder', $project->name);
        }
    }

    public function getTermCondition($id)
    {
        $termAndCondition = Project::where('id', $id)->first();

        return $termAndCondition->term_and_condition;
    }
}
