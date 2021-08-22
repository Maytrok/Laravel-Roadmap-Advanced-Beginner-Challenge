<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\System\Roles;
use Illuminate\Http\Response;

class ProjectController extends Controller
{

    public function __construct()
    {
        $this->middleware("role:" . Roles::SUPER_ADMIN)->only("destroy");
    }
    public function index()
    {
        return Project::with(["user", "client"])->get();
    }

    public function store(CreateProjectRequest $request)
    {
        $project = Project::create(
            array_merge($request->validated(), ["status" => "open"])
        );

        return new ProjectResource($project);
    }
    public function show($id)
    {
        $project = Project::with(["user", "client"])->findOrFail($id);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        $project->refresh();
        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
