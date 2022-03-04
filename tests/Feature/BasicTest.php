<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use DB;
use Session;

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
		$response->assertSee('Denny Tasks'); //page title
    }
	
	public function testAddTaskRequireDescription()
	{
		$data = [
            'DateAdded' => date('Y-m-d H:i:s'),
			'DateUpdated' => date('Y-m-d H:i:s'),
			'Creator' => 'David',
			'Assignee' => 'John',
			'Description' => '',
            'Priority' => 'Low',
			'DueDate' => '2022-05-01',
			'Status' => 'Pending'			
	    ];
		$task = Task::make($data);
        $response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => env('TEST_IS_ADMIN')])->post('/addTask', $task->toArray());
		$response->assertStatus(302);
		$response->assertSessionHasErrors('Description');
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
		$task = Task::make($data);
        $response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => env('TEST_IS_ADMIN')])->post('/addTask', $task->toArray());
        $response->assertStatus(302);
		$response->assertRedirect('/showTasks');
		
		global $taskId;
		$taskId = DB::getPdo()->lastInsertId();
		
		$this->assertNotEquals(0, $taskId);
		
		$this->assertDatabaseHas('Task',['id'=> $taskId , 'Description' => 'Test Test Test']);

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
        $response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => env('TEST_IS_ADMIN')])->post('/addTask', $data);
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
        $response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => env('TEST_IS_ADMIN')])->post('/editTask', $data);
        $response->assertStatus(302);
		$response->assertRedirect('/showTasks');
		
		$task = Task::where(['Id' => $taskId])->get()->first();
		if ($task)
			$this->assertEquals('Complete', $task->Status);
	}
	
	public function testNonAdminCannotEditName() {
		global $taskId;
		
		$data = [
			'eId' => $taskId,
			'eDescription' => 'Test Test Test',
			'eAssignee' => 'Denny Test',
            'ePriority' => 'Low',
			'eDueDate' => '2022-05-01',
			'eStatus' => 'Complete'			
	    ];
        $response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => 0])->post('/editTask', $data);
        $response->assertStatus(302);
		$response->assertRedirect('/showTasks');
		
		$task = Task::where(['Id' => $taskId])->get()->first();
		if ($task)
			$this->assertEquals('John', $task->Assignee);
	}
	
	public function testAdminCanEditName() {
		global $taskId;
		
		$data = [
			'eId' => $taskId,
			'eDescription' => 'Test Test Test',
			'eAssignee' => 'Denny Test',
            'ePriority' => 'Low',
			'eDueDate' => '2022-05-01',
			'eStatus' => 'Complete'			
	    ];
        $response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => 1])->post('/editTask', $data);
        $response->assertStatus(302);
		$response->assertRedirect('/showTasks');
		
		$task = Task::where(['Id' => $taskId])->get()->first();
		if ($task)
			$this->assertEquals('Denny Test', $task->Assignee);
	}
	
	public function testNonCreatorCannotDelTask() 
	{
		global $taskId;
		
		$response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME').' Test', 'TEST_IS_ADMIN' => env('TEST_IS_ADMIN')])->get("/delTask/$taskId");
		$response->assertStatus(302);
		
		$this->assertDatabaseHas('Task',['id'=> $taskId , 'Description' => 'Test Test Test']);
	}
	
	public function testDelTask() 
	{
		global $taskId;
		
		$response = $this->withSession(['TEST_USERNAME' => env('TEST_USERNAME'), 'TEST_IS_ADMIN' => env('TEST_IS_ADMIN')])->get("/delTask/$taskId");
		$response->assertStatus(302);
		$response->assertRedirect('/showTasks');
		
		$this->assertDatabaseMissing('Task',['id'=> $taskId]);		
	}
}
