@extends('layouts.default')

@section('title', 'Denny Tasks')

@section('content')
  @if (Session::has('success'))
  <div class="alert-success p-2">{{ Session::get('success') }}</div>
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
              <input type="submit" value="Add Task" class="btn btn-primary btn-block btn-lg" id="add-user-btn">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Add New Task Modal End -->

  <!-- Edit User Modal Start -->
  <div class="modal fade" tabindex="-1" id="editUserModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit This User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="edit-user-form" class="p-2" novalidate>
            <input type="hidden" name="id" id="id">
            <div class="row mb-3 gx-3">
              <div class="col">
                <input type="text" name="fname" id="fname" class="form-control form-control-lg" placeholder="Enter First Name" required>
                <div class="invalid-feedback">First name is required!</div>
              </div>

              <div class="col">
                <input type="text" name="lname" id="lname" class="form-control form-control-lg" placeholder="Enter Last Name" required>
                <div class="invalid-feedback">Last name is required!</div>
              </div>
            </div>

            <div class="mb-3">
              <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Enter E-mail" required>
              <div class="invalid-feedback">E-mail is required!</div>
            </div>

            <div class="mb-3">
              <input type="tel" name="phone" id="phone" class="form-control form-control-lg" placeholder="Enter Phone" required>
              <div class="invalid-feedback">Phone is required!</div>
            </div>

            <div class="mb-3">
              <input type="submit" value="Update User" class="btn btn-success btn-block btn-lg" id="edit-user-btn">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Edit User Modal End -->
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
			  @foreach ($tasks as $task) 
			  <tr>
                <td>{{$task->Id}}</td>
				<td>{{$task->Description}}</td>
                <td>{{$task->Creator}}</td>
                <td>{{$task->Priority}}</td>
                <td>{{$task->DueDate}}</td>
				<td>{{$task->Assignee}}</td>
				<td>{{$task->DateAdded}}</td>
				<td>{{$task->DateUpdated}}</td>
				<td>{{$task->Status}}</td>
				<td>
				  <a href="javascript:void(0);" class="btn btn-success btn-sm py-0 editLink" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</a>
				  
				  <a href="javascript:void(0);" class="btn btn-danger btn-sm py-0 deleteLink">Delete</a>
				</td>
              </tr>
			  @endforeach
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')

<script type="module">
@if(Session::get('adderror'))
document.getElementById('addTaskBtn').click();
@endif
</script>
@endsection
