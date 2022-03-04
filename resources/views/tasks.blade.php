@extends('layouts.default')

@section('title', 'Denny Tasks')

@section('content')
  @if (Session::has('success'))
  <div class="alert-success p-2">{{ Session::get('success') }}</div>
  @endif
  
  @if (Session::has('error'))
  <div class="alert-danger p-2">{{ Session::get('error') }}</div>
  @endif

  <!-- Add New Task Modal Start -->
  <div class="modal fade" tabindex="-1" id="addNewTaskModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
		  @if(Session::get('adderror'))
		  <div class="alert-danger p-2">Please review the invalid fields below.</div>
	      @endif

          <form name="add-task-form" method="post" action="{{ asset('/addTask') }}" class="p-2">
			@csrf
		    <div class="mb-3">
              <textarea name="Description" class="form-control form-control-lg" placeholder="Enter Description" maxlength=200 required>{{old('Description')}}</textarea>
              @if($errors->has('Description'))
			  <div class="text-danger">{{$errors->first('Description')}}</div>
			  @endif
            </div>
			
			<div class="mb-3">
              <select name="Priority" class="form-select form-select-lg">
			    <option value="Low" @if(old('Priority')=='Low') selected @endif>Low</option>
				<option value="Medium" @if(old('Priority')=='Medium') selected @endif>Medium</option>
				<option value="High" @if(old('Priority')=='High') selected @endif>High</option>
				<option value="Critical" @if(old('Priority')=='Critical') selected @endif>Critical</option>
			  </select>
            </div>

            <div class="mb-3">
              <input type="text" name="Assignee" class="form-control form-control-lg" placeholder="Enter Assignee Name" maxlength=100 value="{{old('Assignee')}}" required>
              @if($errors->has('Assignee'))
			  <div class="text-danger">{{$errors->first('Assignee')}}</div>
			  @endif
            </div>

            <div class="mb-3">
              <input type="text" name="DueDate" class="form-control form-control-lg" placeholder="Enter Due Date (YYYY-MM-DD)" maxlength=10 value="{{old('DueDate')}}" required>
              @if($errors->has('DueDate'))
			  <div class="text-danger">{{$errors->first('DueDate')}}</div>
			  @endif
            </div>
			
			<div class="mb-3">
              <select name="Status" class="form-select form-select-lg">
			    <option value="Pending" @if(old('Status')=='Pending') selected @endif>Pending</option>
				<option value="In Progress" @if(old('Status')=='In Progress') selected @endif>In Progress</option>
				<option value="Complete" @if(old('Status')=='Complete') selected @endif>Complete</option>
			  </select>
            </div>

            <div class="mb-3">
              <input type="submit" value="Add Task" class="btn btn-primary btn-block btn-lg">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Add New Task Modal End -->

  <!-- Edit Task Modal Start -->
  <div class="modal fade" tabindex="-1" id="editTaskModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit This Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if(Session::get('editerror'))
		  <div class="eerr alert-danger p-2">Please review the invalid fields below.</div>
	      @endif

          <form name="edit-task-form" method="post" action="{{ asset('/editTask') }}" class="p-2">
			@csrf
			<input type="hidden" id="eId" name="eId" value="{{old('eId')}}" />
		    <div class="mb-3">
              <textarea id="eDescription" name="eDescription" class="form-control form-control-lg" maxlength=200 required>{{old('eDescription')}}</textarea>
              @if($errors->has('eDescription'))
			  <div class="eerr text-danger">{{$errors->first('eDescription')}}</div>
			  @endif
            </div>
			
			<div class="mb-3">
              <select id="ePriority" name="ePriority" class="form-select form-select-lg">
			    <option value="Low" @if(old('ePriority')=='Low') selected @endif>Low</option>
				<option value="Medium" @if(old('ePriority')=='Medium') selected @endif>Medium</option>
				<option value="High" @if(old('ePriority')=='High') selected @endif>High</option>
				<option value="Critical" @if(old('ePriority')=='Critical') selected @endif>Critical</option>
			  </select>
            </div>

			@if(env('TEST_IS_ADMIN'))
            <div class="mb-3">
              <input id="eAssignee" type="text" name="eAssignee" class="form-control form-control-lg" maxlength=100 value="{{old('eAssignee')}}" required>
              @if($errors->has('eAssignee'))
			  <div class="eerr text-danger">{{$errors->first('eAssignee')}}</div>
			  @endif
            </div>
			@endif

            <div class="mb-3">
              <input id="eDueDate" type="text" name="eDueDate" class="form-control form-control-lg" maxlength=10 value="{{old('eDueDate')}}" required>
              @if($errors->has('eDueDate'))
			  <div class="eerr text-danger">{{$errors->first('eDueDate')}}</div>
			  @endif
            </div>
			
			<div class="mb-3">
              <select id="eStatus" name="eStatus" class="form-select form-select-lg">
			    <option value="Pending" @if(old('eStatus')=='Pending') selected @endif>Pending</option>
				<option value="In Progress" @if(old('eStatus')=='In Progress') selected @endif>In Progress</option>
				<option value="Complete" @if(old('eStatus')=='Complete') selected @endif>Complete</option>
			  </select>
            </div>

            <div class="mb-3">
              <input type="submit" value="Update Task" class="btn btn-primary btn-block btn-lg">
            </div>
		  </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Edit Task Modal End -->
  <div class="container">
    <div class="row mt-4">
      <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <div>
          <h5 class="text-primary">User: {{$user}} | Admin: {{$admin ? 'Y': 'N'}}</h5>
        </div>
        <div>
          <button id="addTaskBtn" class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addNewTaskModal">Add New Task</button>
        </div>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-lg-12">
        <div id="showAlert"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="table-responsive">
          <table class="table table-striped table-bordered text-center">
            <thead>
              <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Creator</th>
                <th>Priority</th>
				<th>Due Date</th>
				<th>Assignee</th>
                <th>Add Date</th>
                <th>Last Update</th>
				<th>Status</th>
				<th>Action</th>
              </tr>
			</thead>
            <tbody>
			  @foreach ($tasks as $task) 
			  <tr>
                <td id="Id{{$task->Id}}">{{$task->Id}}</td>
				<td id="Description{{$task->Id}}">{{$task->Description}}</td>
                <td id="Creator{{$task->Id}}">{{$task->Creator}}</td>
                <td id="Priority{{$task->Id}}">{{$task->Priority}}</td>
                <td id="DueDate{{$task->Id}}">{{$task->DueDate}}</td>
				<td id="Assignee{{$task->Id}}">{{$task->Assignee}}</td>
				<td id="DateAdded{{$task->Id}}">{{$task->DateAdded}}</td>
				<td id="DateUpdated{{$task->Id}}">{{$task->DateUpdated}}</td>
				<td id="Status{{$task->Id}}">{{$task->Status}}</td>
				<td>
				  <button id="edit{{$task->Id}}" class="btn btn-success btn-sm py-0 editLink" onclick="fillEdit({{$task->Id}});" data-bs-toggle="modal" data-bs-target="#editTaskModal">Edit</button>
				  
				  <button onclick="delTask({{$task->Id}});" class="btn btn-danger btn-sm py-0">Delete</button>
				</td>
              </tr>
			  @endforeach            
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
function delTask(Id) {
	if (confirm('Are you sure to delete Task ID: '+Id+'?')) {
		window.location = 'delTask/'+Id;
	}
}

function fillEdit(Id) {	
  const eerrs = document.getElementsByClassName('eerr');
  if (Id != "{{old('eId')}}" || eerrs[0].style.display == 'none') {	
	for (const eerr of eerrs) {
      eerr.style.display = 'none';
	}
    document.getElementById('eId').value=Id;
    document.getElementById('eDueDate').value=document.getElementById('DueDate'+Id).innerHTML;
    document.getElementById('eDescription').value=document.getElementById('Description'+Id).innerHTML;
    document.getElementById('ePriority').value=document.getElementById('Priority'+Id).innerHTML;
    document.getElementById('eStatus').value=document.getElementById('Status'+Id).innerHTML;
	if (document.getElementById('eAssignee'))
      document.getElementById('eAssignee').value=document.getElementById('Assignee'+Id).innerHTML; 
  }  
}

@if(Session::get('adderror'))
document.getElementById('addTaskBtn').click();
@endif

@if(Session::get('editerror'))
document.getElementById('edit'+{{Session::get('taskid')}}).click();
@endif
</script>
@endsection
