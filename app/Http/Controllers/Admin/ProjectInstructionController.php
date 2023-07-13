<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProjectInstructionRequest;
use App\Models\Project;
use App\Models\ProjectRemark;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectInstructionController extends Controller
{
    /**
     * This function is get index page
     *
     * @return void
     */
    public function index()
    {
        return view('admin.project-instruction.index');
    }

    /**
     * This function is used for get create instruction page
     *
     * @return void
     */
    public function getCreateView()
    {
        return view('admin.project-instruction.create');
    }

    /**
     * This function is for create project instructions.
     *
     * @param AddProjectInstructionRequest $request
     * @return void
     */
    public function store(AddProjectInstructionRequest $request)
    {

        try {
            return DB::transaction(function () use ($request) {
                if ($request->ajax()) {
                    $project = Project::create([
                        'name' => htmlspecialchars(request('name')),
                        'status' => $request->status,
                        'type' => $request->type,
                        'country' => $request->country,
                        'short_description' => $request->short_description,
                        'description' => $request->description,
                        'user_id' => auth()->id(),
                        'term_and_condition' => $request->term_and_condition,
                        'price' => $request->price,
                        'consent_form' => $request->consent_form
                    ]);
                    foreach ($request->remarks as $remark) {
                        ProjectRemark::create([
                            'project_id' => $project->id,
                            'remark' => $remark,
                        ]);
                    }
//                    foreach ($request->rejectionReason as $reason) {
//                        ProjectRejection::create([
//                            'project_id' => $project->id,
//                            'reason' => $reason,
//                        ]);
//                    }
                    return response()->json(['status' => 'success', 'message' => __('Project  has been successfully created')]);
                }
            });
        } catch (\Exception|\Throwable $e) {
            return response()->json($e->getMessage(),
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * This function is used for list project instruction data
     *
     * @param Request $request
     * @return void
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Project::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    $actionBtn =
                        '<div>
                                    <a href="' .
                        route('admin.project-instruction.edit', $row['id']) .
                        '"><i class="fa-solid fa fa-edit table-action-buttons edit-action-button" title="Edit"></i></a>
                                    <a class="deleteprojectInstructionButton" id="' .
                        $row['id'] .
                        '" href="#"><i class="fa-solid fa fa-trash table-action-buttons delete-action-button" title="Delete"></i></a>
                                </div>';
                    return $actionBtn;
                })
                ->addColumn('name', function ($row) {
                    $formattedName = str_replace('_', ' ', $row["name"]);
                    $text = '<span class="font-weight-bold-' . $formattedName . '">' . ucfirst($formattedName) . '</span>';
                    return $text;
                })
                ->addColumn('price', function ($row) {
                    if ($row['price']) {
                        $price = $row['price'] . '$/per task ';
                    } else {
                        $price = null;
                    }
                    $text = '<span class="font-weight-bold-' . $row['price'] . '">' . $price . '</span>';
                    return $text;
                })
                ->addColumn('created-on', function ($row) {
                    $created_on = '<span>' . date_format($row["created_at"], 'd M Y') . '</span>';
                    return $created_on;
                })
                ->addColumn('description', function ($row) {
                    $description = $row['short_description'];
                    $description = '<div class=""> ' . $description . '</div>';
                    return $description;
                })
                ->rawColumns(['actions', 'name', 'price', 'created-on', 'description'])
                ->make(true);
        }
    }

    /**
     * This function is used for upload image.
     *
     * @param Request $request
     * @return void
     */
    public function uploadFile(Request $request)
    {
        if($request->hasFile('upload')) {
            $file = $request->file('upload');
            if(!$file->isValid()) {
                return response()->json(['message' => 'File is not valid'], 400);
            }
            $filename = $file->getClientOriginalName();
            $destinationPath = 'cskeditor';
            $file->move($destinationPath,$filename);
            $url = asset($destinationPath.'/'.$filename);

            // Return JSON response to CKEditor
            return response()->json([
                'uploaded' => true,
                'url' => $url,
            ]);
        } else {
            return response()->json(['message' => 'No file uploaded'], 400);
        }
    }


    /**
     * This function is used for delete project instruction data
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        $projectInstruction = Project::where('id', $request->id)->first();
        if ($projectInstruction) {
            $projectInstruction->delete();
            return response()->json('success');
        } else {
            return response()->json('error');
        }

    }

    /**
     * This function is used for get data for edit
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function edit(Request $request, $id)
    {
        $project = Project::where('id', $id)->first();
        $projectRemarks = ProjectRemark::where('project_id', $id)->get();

        return view('admin.project-instruction.edit', compact('project', 'projectRemarks'));
    }

    /**
     * This function is used for update project instruction data
     *
     * @param AddProjectInstructionRequest $request
     * @param [type] $id
     * @return void
     */
    public function update(AddProjectInstructionRequest $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {
                if ($request->ajax()) {
                    Project::updateOrCreate(
                        ['id' => $id],
                        [
                            'name' => htmlspecialchars(request('name')),
                            'status' => $request->status,
                            'country' => $request->country,
                            'description' => $request->description,
                            'short_description' => $request->short_description,
                            'type' => $request->type,
                            'term_and_condition' => $request->term_and_condition,
                            'user_id' => auth()->id(),
                            'consent_form' => $request->consent_form,
                            'price' => $request->price,

                        ],
                    );
                    $remarks = $request->remarks;
                    $remark_ids = $request->remark_ids;

                    foreach ($remarks as $index => $remark) {
                        if (isset($remark_ids[$index])) {
                            // Update or create based on remark_id
                            ProjectRemark::updateOrCreate(
                                ['id' => $remark_ids[$index], 'project_id' => $id],
                                ['remark' => $remark]
                            );
                        } else {
                            // Create new remark
                            ProjectRemark::create([
                                'project_id' => $id,
                                'remark' => $remark
                            ]);
                        }
                    }


                    return response()->json(['status' => 'success', 'message' => __('Project Instructions has been successfully updated')]);
                }
            });
        } catch (\Exception|\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function assignedProjects($id)
    {
        $project = Project::where('id', $id)->first();
        if ($project->type == 'image_to_speech') {
            return redirect()->route('admin.liveTranscription.index', $project->id);
        } elseif ($project->type == 'text_to_speech') {
            return redirect()->route('admin.text.list.user', $project->id);
        } elseif ($project->type == 'text_to_text') {
            return redirect()->route('admin.text.user', $project->id);
        } elseif ($project->type == 'image') {
            return redirect()->route('admin.coco.user', $project->name);
        }
    }
}
