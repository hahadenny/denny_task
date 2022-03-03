<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function showTasks(Request $request) {
		//print_r($request->all()); exit;
	}
	
	public function addTask(Request $request) {
	}
	
	public function editTask($Id, Request $request) {
	}
	
	public function delTask($Id, Request $request) {
	}
}
