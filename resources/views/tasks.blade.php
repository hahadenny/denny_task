@extends('layouts.default')

@section('title', 'Denny Tasks')

@section('content')
  <!-- Add New User Modal Start -->
  <div class="modal fade" tabindex="-1" id="addNewUserModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="add-user-form" class="p-2" novalidate>
            <div class="row mb-3 gx-3">
              <div class="col">
                <input type="text" name="fname" class="form-control form-control-lg" placeholder="Enter First Name" required>
                <div class="invalid-feedback">First name is required!</div>
              </div>

              <div class="col">
                <input type="text" name="lname" class="form-control form-control-lg" placeholder="Enter Last Name" required>
                <div class="invalid-feedback">Last name is required!</div>
              </div>
            </div>

            <div class="mb-3">
              <input type="email" name="email" class="form-control form-control-lg" placeholder="Enter E-mail" required>
              <div class="invalid-feedback">E-mail is required!</div>
            </div>

            <div class="mb-3">
              <input type="tel" name="phone" class="form-control form-control-lg" placeholder="Enter Phone" required>
              <div class="invalid-feedback">Phone is required!</div>
            </div>

            <div class="mb-3">
              <input type="submit" value="Add User" class="btn btn-primary btn-block btn-lg" id="add-user-btn">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Add New User Modal End -->

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
          <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addNewUserModal">Add New User</button>
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
