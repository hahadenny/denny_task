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
		//print_r($request->all()); exit;
		
		$rules = [
			'Description' => array('required', 'string', 'max:200'),
			'Priority' => array('required', 'in:Low,Medium,High,Critical'),
			'Assignee' => array('required', 'string', 'max:100'),
			'DueDate' => array('required', 'regex:/^\d{4}\-\d{2}\-\d{2}$/'),
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
	
	public function editTask($Id, Request $request) {
	}
	
	public function delTask($Id, Request $request) {
	}
}
