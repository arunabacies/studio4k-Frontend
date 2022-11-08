@extends('layouts.app')

@section('content')

@php 
  $pm = array();
  $crew_list = array();
	$engineer = array();
  $client = array();

  $creator = $data->creator;
	$assigned_users = $data->assigned_users;
  $events = $data->events; 

  //get media details
  $image = 0;
  $audio = 0;
  $image_name = '';
  $audio_name = '';
  $image_url = '';
  $audio_url = '';
  $media = $data->media;
  foreach($media as $m) {
    if($m->type_ == 'image'){
      $image = $m->id;
      $image_name = $m->local_name;
      $image_url = $m->url;
    } else if($m->type_ == 'song') {
      $audio = $m->id;
      $audio_name = $m->local_name;
      $audio_url = $m->url;
    }
  }

  foreach($assigned_users as $assigned_user) {
                        
    if($assigned_user->user_role == 2) {
      array_push($pm,$assigned_user->name);
    }
		if($assigned_user->user_role == 5) {
		  array_push($crew_list, $assigned_user->name);
		} else if($assigned_user->user_role == 4) {
		  array_push($engineer, $assigned_user->name);
		} else if ($assigned_user->user_role == 3) {
      array_push ($client, $assigned_user->name);
    }        

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
        <button class="btn btn-warning upload-image" data-toggle="modal" 
          data-target="#multimediaModal" data-id="{{ $data->id}}" 
          image-name="{{$image_name}}" image="{{ $image }}" audio="{{ $audio }}"
          image-url="{{ $image_url }}">
          <i class="bx bx-image">&nbsp;</i>Image
        </button>
        <button class="btn btn-warning upload-audio" data-toggle="modal" 
          data-target="#multimediaModal1" data-id="{{ $data->id}}" 
          audio-name="{{$audio_name}}" image="{{ $image }}" audio="{{ $audio }}"
          audio-url="{{ $audio_url }}">
          <i class="bx bx-music">&nbsp;</i>Audio
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
            <p class="w-50 mb-50 text-primary">Date: {{ date('d-m-Y', strtotime($data->created)) }}</p>
            <p class="w-50 mb-50 text-primary">Project Manager: {{ implode(", ", $pm) }}</p>
            <p class="w-50 mb-50 text-primary">Job: {{$data->job_number}}</p>
            <p class="w-100 mt-1">
              <span class="d-inline-block text-success mr-1">
                <i class="bx bx-globe" style="font-size: 45px;"></i>
              </span>
              <span id="gttz" class="d-inline-block text-success"> {{ $time_zone }}
                  <small class="d-block text-success"></small>
              </span>
            </p>
            
          </div>
        </div>

        <div class="col-md-3 col-sm-5">
          <div class="d-flex mb-2 pt-2" style="flex-wrap: wrap;">
            <h4 class="w-50 mb-50 text-white">Sessions:</h4>
            <h4 class="w-50 text-right mb-50 text-white">{{ count($events) }}</h4>
            <p class="w-50 mb-50">Crew:</p>
            <p class="w-50 text-right mb-50">{{ count($crew_list) }}</p>
            <p class="w-50 mb-50">Eng:</p>
            <p class="w-50 text-right mb-50">{{ count($engineer) }}</p>
            <p class="w-50 mb-50">Client:</p>
            <p class="w-50 text-right mb-50">{{ $data->client_name }}</p>
          </div>
        </div>

      </div>

    </div>
    </div>
    </div>

  </div>
</section>
<!-- Basic Card types section end -->

<!-- Data table -->
<section id="session-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-white">Sessions</h3>
          @if (Session::get('role') == 1)
          <button class="btn btn-warning create-session" data-toggle="modal" 
            data-target="#newSchedule" assigned-users="{{ json_encode($assigned_users) }}">
            <i class="bx bx-plus-circle">&nbsp;</i>New Session
          </button>
          @endif
        </div>
        
        <div class="card-body card-dashboard">
          <div class="table-responsive">
            <table class="table zero-configuration">
              <thead>
                <tr>
                  <th>Session</th>
                  <th>Date</th>
                  <th>Rooms</th>
                  <th>Presenters</th>
                  @if (Session::get('role') == 1)
                  <th>Go to Session</th>
                  @endif
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
						
                @if($events)
                  @foreach($events as $event)
                  @php 
                    $rooms = $event->rooms -1;
                    $presenters = $event->presenters;
                    $presenter_count = 0;
                   
                    $state_to_be = "";
                    $title ="";
                    $style_pointer = '';
                    if($event->state == 'upcoming') {
                      $terminate_class = 'bx-play-circle';
                      $state_to_be = "running";
                      $title = "Start";
                    } else if($event->state == 'running'){
                      $terminate_class = 'bx-x-circle';
                      $state_to_be = "closed";
                      $title = "Terminate";
                    } else if($event->state == 'closed') {
                      $terminate_class = 'bx-no-entry';
                      $style_pointer = 'style=pointer-events:none';
                    }
                  @endphp

                  <tr>
                    <td>{{ $event->name }} </td>
                    <td>{{ date('d-m-Y', strtotime($event->event_time)) }}</td>
                    <td>{{ $rooms }}</td>
                    <td>{{ $presenters }}</td>
                    @php if ($presenters != 0) {
                      $href = "https://pohostaging.club/oasis-connect-rooms/dashboard/".$event->id."/". Session::get('token');
                      $target = 'target="_blank"';
                    } else {
                      $href = "#";
                      $target = '';
                    }
                    @endphp
                    @if (Session::get('role') == 1)
                    <td>
                      <a class="btn btn-danger" href="{{$href}}" {{$target}}>Go</a>
                    </td>
                    @endif
                    
                    <td>

                      @if ($data->recording == true && $event->state == 'closed')
                      <a data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-container="body" data-content="Click Here to View Recordings" 
                          href="{{route('project-recordings', ['id'=>$data->id,'session_id'=>$event->id])}}">
                          <i class="bx bx-folder-open text-primary" 
                              title="Recordings" >
                          </i>
                      </a>
                      @endif

                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to add Presenters" >
                        <i class="bx bx-user-plus text-success generate-video-url-button" 
                          data-toggle="modal" 
                          data-target="#generateLink"
                          session-id="{{ $event->id}}" 
                          project-id="{{ $data->id }}"
                          url="{{ route('get-presenter') }}" 
                          session-state="{{ $event->state }}"
                          style="font-size: 1.4em;">
                        </i>
                      </span>
                      
                      @if($presenters > 0)
                        <span data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-container="body" data-content="Click Here to send mail" 
                          {{$style_pointer}}>
                          <i class="bx bx-mail-send text-primary send-mail-button" 
                            session-id="{{ $event->id}}"
                            presenters="{{ json_encode($presenters) }}" 
                            url="{{ route('presenter-email-sent') }}"></i>
                        </span>
                      @endif

                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to {{ $title }} Session" 
                        {{$style_pointer}}>
                        <i class="bx {{$terminate_class}} text-danger terminate-session" 
                          data-id="{{ $event->id}}" 
                          data-name="{{$event->name}}" 
                          data-url="{{route('terminate-session')}}" 
                          title="{{ $title }}" 
                          state_to_be="{{ $state_to_be }}"></i>
                      </span>
                      
                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to edit Session">
                        <i class="bx bx-edit-alt text-warning eventEdit" data-toggle="modal" 
                            data-target="#editEvent" 
                            data-url="{{ route('get-event') }}"
                            data-id="{{ $event->id}}" 
                            project-id="{{ $data->id }}"
                            assigned-users="{{ json_encode($assigned_users) }}" 
                            session-state="{{ $event->state }}"
                            title="Edit" ></i>
                      </span>
                      
                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to delete Session">
                        <i class="bx bx-trash text-danger deleteEvent" 
                          data-id="{{$event->id}}" 
                          data-name="{{$event->name}}" 
                          data-url="{{route('delete-event')}}"
                          project-id="{{$data->id}}"></i>
                      </span>
                    </td>
                  </tr>

                @endforeach
                @endif   
              </tbody>
            </table>
          </div>
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
    <script src="{{ asset('assets/js/multi-field.js') }}"></script>
@stop
