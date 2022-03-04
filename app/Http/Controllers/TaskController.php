<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;

class TaskController extends Controller
{
    public function showTasks(Request $request) {		
		$tasks = Task::orderBy('Id', 'desc')->get();
		return view('tasks', ['tasks' => $tasks, 'user' => env('TEST_USERNAME'), 'admin' => env('TEST_IS_ADMIN')]);
	}
	
	public function addTask(Request $request) {	
		$rules = [
			'Description' => array('required', 'string', 'max:200'),
			'Priority' => array('required', 'in:Low,Medium,High,Critical'),
			'Assignee' => array('required', 'string', 'max:100'),
			'DueDate' => array('required', 'date_format:Y-m-d'),
			'Status' => array('required', 'in:Pending,In Progress,Complete')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		$validator->after(function($validator) use ($request) {	
			if ($request->DueDate < date('Y-m-d'))
				$validator->errors()->add('DueDate', 'Due Date cannot be a past date.');
			
			$otask = Task::where('Description', trim($request->Description))->get();
			if (count($otask))
				$validator->errors()->add('Description', 'Task exists already.');			
		});
		
		if ($validator->fails()) {			
			//print_r($validator->errors()->all()); exit;			
			return back()->withErrors($validator)->withInput()->with(['adderror'=>1]);
		}
		
		$data = new Task();
		$data->Creator = env('TEST_USERNAME');
		$data->Description = $request->Description;
		$data->Priority = $request->Priority;
		$data->Assignee = $request->Assignee;
		$data->DueDate = $request->DueDate;
		$data->Status = $request->Status;
		$data->DateAdded = date('Y-m-d H:i:s');
		$data->DateUpdated = date('Y-m-d H:i:s');
		$data->save();
		
		return redirect("/showTasks")->withSuccess('Task added successfully.');
	}
	
	public function editTask(Request $request) {
		//print_r($request->all()); exit;
		
		$rules = [
			'eId' => array('required', 'numeric'),
			'eDescription' => array('required', 'string', 'max:200'),
			'ePriority' => array('required', 'in:Low,Medium,High,Critical'),
			'eAssignee' => array('string', 'max:100'),
			'eDueDate' => array('required', 'date_format:Y-m-d'),
			'eStatus' => array('required', 'in:Pending,In Progress,Complete')
		];
				
		$messages = [
		];
		
		$validator = Validator::make($request->all(), $rules, $messages);
		
		$validator->after(function($validator) use ($request) {	
			if ($request->eDueDate < date('Y-m-d'))
				$validator->errors()->add('eDueDate', 'Due Date cannot be a past date.');
			
			$otask = Task::where([['Description', trim($request->eDescription)], ['Id', '<>', $request->eId]])->get();
			if (count($otask))
				$validator->errors()->add('eDescription', 'Task exists already.');			
		});
		
		if ($validator->fails()) {			
			//print_r($validator->errors()->all()); exit;			
			return back()->withErrors($validator)->withInput()->with(['editerror'=>1, 'taskid'=>$request->eId]);
		}
		
		$data = array();
		$data['Description'] = $request->eDescription;
		$data['Priority'] = $request->ePriority;
		if (env('TEST_IS_ADMIN') && $request->eAssignee)
			$data['Assignee'] = $request->eAssignee;
		$data['DueDate'] = $request->eDueDate;
		$data['Status'] = $request->eStatus;
		Task::where('Id', $request->eId)->update($data);
		
		return redirect("/showTasks")->withSuccess("Task ID: $request->eId updated successfully.");
	}
	
	public function delTask($Id, Request $request) {		
		$task = Task::find($Id);
		
		if ($task->Creator != env('TEST_USERNAME'))
			return back()->with('error', "Error: You cannot delete Task ID: $Id as you are not the creator of this task.");
		
		if ($task)
			$task->delete();
		
		return redirect("/showTasks")->withSuccess("Task ID: $Id deleted successfully.");
	}
}
