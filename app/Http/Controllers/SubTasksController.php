<?php

namespace App\Http\Controllers;

use App\Models\SubTasks;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //get all SubTasks with user and Project
    public function getAllSubTasks()
    {
        try {
            $subtasks = SubTasks::where('status',1)->with('project')->with('employee.role')->get();
            return $subtasks;
        } catch (Exception) {
            return response()->json(array('status' => false, 'message' => "There is no Sub Tasks", 'statuscode' => 400), 400);
        }
    }


    public function statusUpdate(Request $request)
    {
        try {
            $id=$request['task_id'];
            // Check if SubTasks existed OR not 
            $subtask = SubTasks::where('status',1)->findOrFail($id);
            
            $subtask['status'] = 0 ;// that's mean the task was submit and closed
            $subtask['details']=$request['details'];
           
            $subtask->update();

            return response()->json(array('status' => true, 'user' => "The Task Was Submited", 'statuscode' => 200));
        } catch (Exception) {

            return response()->json(array('status' => false, 'message' => "There is no Sub task with This id", 'statuscode' => 400), 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
        //
        public function createSubTask(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $subtasks = SubTasks::create([
                'task_name' => $request['task_name'],
                'status' => 1,
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
                'employee_id'=>$request['employee_id'],
                'project_id'=>$request['project_id'],
                'details'=>$request['details']? $request["details"] : null,

            ]);
            $subtasks->save();

            DB::commit();
            return response()->json(array('status' => true, 'message' => "Sub Task Create", 'statuscode' => 200), 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubTasks  $subTasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        try {
            // Check if Sub Task existed OR not 
            $subtasks = SubTasks::where('status',1)->findOrFail($request['id']);
            
            $subtasks['task_name'] = $request['task_name'];
            $subtasks['status'] = $request['status'];
            $subtasks['employee_id'] = $request['employee_id'];
            $subtasks['start_date'] = $request['start_date'];
            $subtasks['end_date'] = $request['end_date'];
            $subtasks['project_id'] = $request['project_id'];
            $subtasks['details'] = $request['details']? $request["details"] : null;
            $subtasks->update();

            return response()->json(array('status' => true, 'Sub Task' => $subtasks, 'statuscode' => 200));
        } catch (Exception) {

            return response()->json(array('status' => false, 'message' => "No Sub task Info Found for This id", 'statuscode' => 400), 400);
        }
    
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubTasks  $subTasks
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $SubTask = SubTasks::where('id', $id)->first();
            $SubTask->delete();
            return response()->json(array('status' => true, 'message' => "SubTask Deleted", 'statuscode' => 204));
        } catch (Exception) {
            return response()->json(array('status' => false, 'message' => "No SubTasks Info Found for This id", 'statuscode' => 400), 400);
        }
    
    }
}
