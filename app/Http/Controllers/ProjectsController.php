<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Projects;
use App\Models\User;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //get all Projects with user and subtask
    public function getAllProjects()
    {
        try {
            $projects = Projects::with('user.role')->with('subtask')->get();
            return $projects;
        } catch (Exception) {
            return response()->json(array('status' => false, 'message' => "There is no Projects", 'statuscode' => 400), 400);
        }
    }

    //get all users for Project By Project Id
    public function getAllProjectsById($id)
    {
        //
        try {
            $project = Projects::with('user')->findOrFail($id);
            return $project;
        } catch (Exception) {
            return response()->json(array('status' => false, 'message' => "No Project Info Found for This id", 'statuscode' => 400), 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createProjects(ProjectRequest $request)
    {
        //
        DB::beginTransaction();
        try {
            $project = Projects::create([
                'name' => $request['name'],
                'status' => 1,
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
                'user_id'=>$request['user_id'],

            ]);
            $project->save();

            DB::commit();
            return response()->json(array('status' => true, 'message' => "Project Create", 'statuscode' => 200), 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    
         //
         try {
            // Check if project existed OR not 
            $projects = Projects::findOrFail($request['id']);
            
            $projects['name'] = $request['name'];
            $projects['status'] = $request['status'];
            $projects['user_id'] = $request['user_id'];
            $projects['start_date'] = $request['start_date'];
            $projects['end_date'] = $request['end_date'];
            $projects->update();

            return response()->json(array('status' => true, 'Project' => $projects, 'statuscode' => 200));
        } catch (Exception) {

            return response()->json(array('status' => false, 'message' => "No Project Info Found for This id", 'statuscode' => 400), 400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Projects  $projects
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $project = Projects::where('id', $id)->first();
            $project->delete();
            return response()->json(array('status' => true, 'message' => "Project Deleted", 'statuscode' => 204));
        } catch (Exception) {
            return response()->json(array('status' => false, 'message' => "No Project Info Found for This id", 'statuscode' => 400), 400);
        }
    
    
    }
}
