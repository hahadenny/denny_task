<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function showTasks(Request $request) {
		//print_r($request->all()); exit;
		$tasks = Task::orderBy('Id', 'desc')->get();
		return view('tasks', ['tasks' => $tasks, 'user' => env('TEST_USERNAME'), 'admin' => env('TEST_IS_ADMIN')]);
	}
	
	public function addTask(Request $request) {
	}
	
	public function editTask($Id, Request $request) {
	}
	
	public function delTask($Id, Request $request) {
	}
}
