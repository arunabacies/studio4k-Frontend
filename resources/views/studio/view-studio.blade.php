@extends('layouts.app')

@section('content')

@php 
  $pm = array();
  $crew_list = array();
	$engineer = array();

  $creator = $data->creator;
	$assigned_users = $data->assigned_users;
  $sessions = $data->sessions; 

  $presenters_count = 0;
  if($sessions) {
    foreach($sessions as $session) {
      $presenters_count = $presenters_count + $session->presenters;
    }
  }

  foreach($assigned_users as $assigned_user) {
                        
    if($assigned_user->user_role == 2) {
      array_push($pm,$assigned_user->name);
    }
		if($assigned_user->user_role == 4) {
		  array_push($crew_list, $assigned_user->name);
		} else if($assigned_user->user_role == 3) {
		  array_push($engineer, $assigned_user->name);
		}        

  }
  //dd($storage_cred);
  $storage_source = '';
  $storage_email = '';
  if( $storage_cred) {
    $storage_credential = $storage_cred->storage_credential;
    $storage_email = $storage_credential->email;
    $storage_source = $storage_cred->storage_source;
  }
							
@endphp

  
<!-- Basic card section start -->
<section id="content-types">
  
  <div class="row">

    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h1 class="text-bold-400">{{ $data->name }}</h1>
        <div>
        <button class="btn btn-warning" data-toggle="modal" 
            data-target="#uploadSettings">
          <i class="bx bx-cloud-upload">&nbsp;</i>Upload Settings
        </button>
        <button class="btn btn-success" onClick="window.location.reload();">
          <i class="bx bx-refresh">&nbsp;</i>Refresh
        </button>
      </div>
    </div>

    <div class="card-body">
      <hr class="m-0"/>
      <div class="row justify-content-between">
        <div class="col-md-5 col-sm-6">
          <div class="d-flex mb-2 pt-2" style="flex-wrap: wrap;">
            
            <p class="w-50 mb-50 text-primary">Created By: {{ $creator->name }}</p>
            <p class="w-50 mb-50 text-primary">Date: {{ date('d-m-Y', strtotime($data->created_at)) }}</p>
            <p class="w-50 mb-50 text-primary">Project Manager: {{ implode(", ", $pm) }}</p>
            <p class="w-50 mb-50 text-primary">Job: {{$data->job_number}}</p>
            <p class="w-100 mt-1">
              <span class="d-inline-block text-success mr-1">
                <i class="bx bx-globe" style="font-size: 45px;"></i>
              </span>
              <span id="gttz" class="d-inline-block text-success">{{ $timezone->zone }}
                  <small class="d-block text-success"></small>
              </span>
            </p>
            
          </div>
        </div>

        <div class="col-md-3 col-sm-5">
          <div class="d-flex mb-2 pt-2" style="flex-wrap: wrap;">
            <h4 class="w-50 mb-50 text-white">Sessions:</h4>
            <h4 class="w-50 text-right mb-50 text-white">{{ count($sessions) }}</h4>
            <p class="w-50 mb-50">Crews:</p>
            <p class="w-50 text-right mb-50">{{ count($crew_list) }}</p>
            <p class="w-50 mb-50">Presenters:</p>
            <p class="w-50 text-right mb-50">{{ $presenters_count }}</p>
          </div>
        </div>

      </div>

    </div>
    </div>
    </div>

  </div>
</section>
<!-- Basic Card types section end -->
<div class="alert alert-danger alert-block alert-fade go-link-alert">

  <button type="button" class="close" data-dismiss="alert">×</button>    

  <strong></strong>

</div>
        
<!-- Data table -->
<section id="session-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-white">Sessions</h3>
          @if (Session::get('role') == 1)
          <button class="btn btn-warning create-session" data-toggle="modal" 
            data-target="#newStudioSession" assigned-users="{{ json_encode($assigned_users) }}">
            <i class="bx bx-plus-circle">&nbsp;</i>New Session
          </button>
          
          @endif
        </div>
        
        <div class="card-body card-dashboard" id="sessionTable" >
          @include('studio/list')
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ Data table -->

<!-- Data table -->
<section id="project-user-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-white">Users</h3>
        </div>
        <div class="card-body card-dashboard">
          <div class="table-responsive">
            <table class="table zero-configuration">
              <thead>
                <tr>
                  <!-- <th>ID</th> -->
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

                @foreach ($assigned_users as $assigned_user)
                  @php 
                    if($assigned_user->user_role == 1) {
                      $role = "Admin";
                    } else if($assigned_user->user_role == 2) {
                      $role = "Manager";
                    } else if($assigned_user->user_role == 3) {
                      $role = "Engineer";
                    } else if($assigned_user->user_role == 4) {
                      $role = "Crew";
                    }
                    
                  @endphp
              
                  <tr>
                    <td>{{ $assigned_user->name }}</td>
                    <td>{{ $assigned_user->email }}</td>
                    <td>{{ $role }}</td>
                    <td>
                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to delete User" 
                        class="removeUser" 
                        data-id="{{ $assigned_user->user_id }}" 
                        project-id="{{$data->id}}" 
                        data-name="{{ $assigned_user->name }}" 
                        data-url="{{route('remove-user')}}">
                        <i class="bx bx-trash text-danger "></i>
                      </span>
                    </td>
                  </tr>

                @endforeach
                          
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ Data table -->
		
@endsection

@section('pagespecificscripts')
    <!-- flot charts scripts-->
    <script src="{{ asset('assets/js/studio.js') }}"></script>
@stop
