<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use DB;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShowTasks()
    {
        $response = $this->get('/showTasks');
        $response->assertStatus(200);
    }
	
	public function testAddTask()
    {
		$data = [
            'DateAdded' => date('Y-m-d H:i:s'),
			'DateUpdated' => date('Y-m-d H:i:s'),
			'Creator' => 'David',
			'Assignee' => 'John',
			'Description' => 'Test Test Test',
            'Priority' => 'Low',
			'DueDate' => '2022-05-01',
			'Status' => 'Pending'			
	    ];
        $response = $this->post('/addTask', $data);
        $response->assertStatus(302);
		
		global $taskId;
		$taskId = DB::getPdo()->lastInsertId();
		
		$this->assertNotEquals(0, $taskId);
    }
	
	public function testAddNoDuplicate()
    {
		$data = [
            'DateAdded' => date('Y-m-d H:i:s'),
			'DateUpdated' => date('Y-m-d H:i:s'),
			'Creator' => 'David',
			'Assignee' => 'John',
			'Description' => 'Test Test Test',
            'Priority' => 'Low',
			'DueDate' => '2022-05-01',
			'Status' => 'Pending'			
	    ];
        $response = $this->post('/addTask', $data);
        $response->assertStatus(302);
		
		$ntaskId = DB::getPdo()->lastInsertId();
		
		$this->assertEquals(0, $ntaskId);
    }
	
	public function testEditTask() 
	{
		global $taskId;
		
		$data = [
			'eId' => $taskId,
			'eDescription' => 'Test Test Test',
            'ePriority' => 'Low',
			'eDueDate' => '2022-05-01',
			'eStatus' => 'Complete'			
	    ];
        $response = $this->post('/editTask', $data);
        $response->assertStatus(302);
		
		$task = Task::where(['Id' => $taskId])->get()->first();
		if ($task)
			$this->assertEquals('Complete', $task->Status);
	}
	
	public function testDelTask() 
	{
		global $taskId;
		
		$response = $this->get("/delTask/$taskId");
		$response->assertStatus(302);
		
		$task = Task::where(['Id' => $taskId])->get();
		$this->assertEquals(0, count($task));
	}
}
