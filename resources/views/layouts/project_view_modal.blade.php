<!-- Add Session Modal --- start -->
<div class="modal fade primary" id="newSchedule" tabindex="-1" role="dialog" 
  aria-labelledby="newScheduleLabel">
      
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

    <form method="post" action="{{route('create-event')}}" style="width: 100%;">
    @csrf

      <input type="text" name="projectId" value="{{$data->id}}" hidden>
			<input type="text" id="assignedUsers" name="assignedUsers" 
            value="{{ json_encode($assigned_users) }}" hidden>
				
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Add New Session</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
            
        <div class="modal-body">
              
          <div class="form-group">
            <label>Name</label>  
            <input class="form-control" type="text" placeholder="Name" name="name" value="" required >
          </div>
              
          <div class="form-group">
            <label>Event Time</label>  
            <input type="text" name="event_time" class="form-control "  
              placeholder="Pick a Date &amp; Time" id='demo' required>
          </div>

          <div class="form-group multi-field">
            <div class="row mt-1" style="height: 0;opacity: 0;">
              <div class="col-md-6"> 
                <label>Room</label> 
                <input class="form-control r-name" required type="text"  name="rooms[0][name]" value="Waiting Room" readonly>
              </div>		
              <div class="col-md-6">
                <label>Users</label>
                <select class="select2 form-control assign-users" multiple data-live-search="true" name="rooms[0][id][]">
                  @if($assigned_users)
                    @foreach($assigned_users as $user)
                      <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
                  
            </div>
                
          </div>

          <div class="row">
            <div class="col-sm-12 text-right" style="margin-top: 25px;">
              <a href="javascript:void(0);" class="add_button btn btn-icon btn-light-success" title="Add field"><i style="top:3px;" class="bx bx-plus"></i> Add Room</a>
            </div>
          </div>

        </div>
              
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary button-submit">Submit</button>
        </div>

      </div>

    </form>

  </div>
</div>
<!-- Add Session Modal End -->
    
<!-- Edit Session Modal --- start -->
<div class="modal fade primary" id="editEvent" tabindex="-1" role="dialog" 
    aria-labelledby="editEventLabel">
      
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        
    <form method="post" action="{{route('edit-event')}}" style="width:100%">
    @csrf

      <input type="text" name="projectId" value="{{$data->id}}" hidden>
      <input type="text" id="eventId" name="eventId" value="" hidden>
      <input type="text" id="assignedUsers" name="assignedUsers" value="" hidden>
      <input type="text" id="room" name="room" value="" hidden>

      <div class="modal-content">

        <div class="modal-header bg-warning">
          <h4 class="modal-title text-white" id="myModalLabel">Edit Session</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
            
        <div class="modal-body">
          <div class="loader-overlay"><div class="loader" id="loader-4"><span></span><span></span><span></span></div></div>
              
          <div class="form-group">
            <label>Name</label>  
            <input class="form-control" type="text" placeholder="Name" name="name" value="" id="eventName" required>
          </div>
              
          <div class="form-group">
            <label>Event Time</label>  
            <input type="text" name="event_time" class="form-control "  
                  placeholder="Pick a Date &amp; Time" id='editEventTime' required>
          </div>

          <div class="form-group multi-field">
                
            <div class="row" style="height: 0;opacity: 0;">
              <input id="roomFirstId" class="form-control" required type="text"  name="rooms[0][roomId]" hidden>

              <div class="col-md-6"> 
                <label>Room</label> 
                <input id="roomFirstName" class="form-control r-name" required type="text" 
                      name="rooms[0][name]" value="Waiting Room" readonly>
              </div>		
              <div class="col-md-6">
                <label>Users</label>
                <select id="roomFirstUsers" class="select2 form-control assign-users" 
                      multiple data-live-search="true" name="rooms[0][id][]">

                  @if($assigned_users)
                    @foreach($assigned_users as $user)
                      <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                    @endforeach
                  @endif

                </select>
              </div>
          
            </div>

            <div class="edit-assign-rooms">
            </div>

          </div>

          <div class="row">
            <div class="col-md-12 text-right" style="margin-top: 25px;">
              <a href="javascript:void(0);" class="add_button btn btn-icon btn-light-success"
                title="Add field">
                <i style="top:3px;" class="bx bx-plus"></i> Add Room
              </a>
            </div>
          </div>

        </div>
            
        <div class="modal-footer">
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning button-submit">Submit</button>
        </div>

      </div>

    </form>
  </div>
</div>
<!-- Edit Session Modal End -->

<!-- Add Presenter Modal --- start -->
<div class="modal fade primary" id="generateLink" tabindex="-1" role="dialog" 
  aria-labelledby="generateLinkLabel">

  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

    <form method="post" action="{{route('create-update-presenter')}}" 
      enctype="multipart/form-data" style="width:100%">
    @csrf
        
      <input type="text" id= "presenters" name="presenters" value="" hidden>

      <div class="modal-content">

        <div class="modal-header bg-success">
          <h4 class="modal-title text-white" id="myModalLabel">Add Presenter</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
          </button>
        </div>
            
        <div class="modal-body">
          <div class="loader-overlay"><div class="loader" id="loader-4"><span></span><span></span><span></span></div></div>

          <div class="alert alert-warning alert-block alert-fade">
            <button type="button" class="close" data-dismiss="alert">×</button>    
            <strong>Url Copied</strong>
          </div>
            
          <div class="container-fluid">

            <input type="text" value="" id="presenterEventId" name="eventId" hidden>
                
            <div class="edit-presenter-block">
                 
            </div>
                
          </div>

          <div class="row">

            <div class="col-12 mt-2 text-right">
              <button type="button" class="btn btn-info add_presenter_button" 
                    data-toggle="popover" data-placement="top" data-trigger="hover"
                    data-container="body" data-content="Click Here to add Presenters">
                    <i style="top:3px;" class="bx bx-plus"></i>&nbsp;Add
              </button>
            </div>

          </div>
          <div id="selectTXT" class="select-txt" >
            <table class="select-row" style="height:0;opacity:0" >
            </table>
          </div>
        </div>

        
        <div class="generate-link-error">Please generate url</div>
            
        <div class="modal-footer">
            
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cancel</button>
          <!-- <button type="button" class="btn btn-primary send-mail" 
              url="{{ route('presenter-email-sent') }}">Send Mail</button> -->
          <button type="button" class="btn btn-primary copy-all-url"  onclick="copyDivData(selectTXT)"
              >Copy All</button>
          <button type="submit" class="btn btn-success button-submit">Submit</button>

        </div>

      </div>

    </form>
  </div>
</div>
<!-- Add Presenter Modal End -->
  
<!-- Add Multimedia Modal --- start -->
<div class="modal fade primary" id="multimediaModal" tabindex="-1" role="dialog" 
  aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">

    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Add Image</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
      </div>
          
      <div class="modal-body">
          
        <div class="alert alert-success alert-block alert-fade">

          <button type="button" class="close" data-dismiss="alert" style="color:#54575B !important">×</button>    

          <strong></strong>

        </div>

        <div class="form-group">
          <form method="post" action="{{route('multimedia-upload')}}" enctype="multipart/form-data">
          @csrf
                 
            <input type="text" name="projectId" class="form-control projectId" hidden>
            <input type="text" name="type" class="form-control" value="image" hidden>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-9">
                  <input type="text" name="projectId" class="form-control projectId" hidden>
                  <input type="text" name="type" class="form-control" value="image" hidden>
                  <input type="text" name="uploadedImage" class="form-control uploadedImage" hidden>
                  <div class="row">
                    <label>Image File</label> 
                    <input type="file" name="image" class="form-control" required>
                  </div>
                  <div class="row">
                    <button type="submit" class="btn btn-success image-upload" style="margin-top: 15px;">Upload</button>
                  </div>
                </div>
                <div class="col-md-3" style="position:relative;display:none">
                  <button type="button" class="close remove-image" 
                    url="{{route('multimedia-delete')}}" project-id="{{$data->id}}"
                    style="position: absolute;z-index: 1;right: -18px;">
                    <span style="color: red;font-size: 2rem;">&times;</span>
                  </button>
                  <img src="" width="125px" height="125px">

                </div>
              </div>
            </div>
                
          </form>

        </div>
            

            <!-- <div class="input-group control-group increment" >
          <input type="file" name="filename[]" class="form-control">
          <div class="input-group-btn"> 
            <button class="btn btn-success add-file" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
          </div>
        </div>
        <div class="clone hide">
          <div class="control-group input-group" style="margin-top:10px">
            <input type="file" name="filename[]" class="form-control">
            <div class="input-group-btn"> 
              <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
            </div>
          </div>
        </div> -->
			
			
      </div>

    </div>
  </div>
</div>
<!-- Add Multimedia Modal End -->

<!-- Add Audio Modal --- start -->
<div class="modal fade primary" id="multimediaModal1" tabindex="-1" role="dialog" 
  aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">

  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Add Audio File</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
    </div>
          
    <div class="modal-body">

      <div class="alert alert-success alert-block alert-fade">

        <button type="button" class="close" data-dismiss="alert" style="color:#54575B !important">×</button>    

        <strong></strong>

      </div>
          
      <div class="form-group">

        <form method="post" action="{{route('multimedia-upload')}}" enctype="multipart/form-data">
        @csrf
          <input type="text" name="projectId" class="form-control projectId" hidden>
          <input type="text" name="type" class="form-control" value="song" hidden>
          <input type="text" name="uploadedAudio" class="form-control uploadedAudio"  hidden>

          <div class="col-md-12">
            <div class="row">
              <label>Audio File</label>  
              <input type="file" name="audio" class="form-control" required>
            </div>
            <div class="row mt-1 audio-block" style="margin-left: -30px;display:none">
              <div class="col-md-11">
                <audio controls id="audioPlay" style="width: 100%;">
                  <source id="audioSource" src="" >
                  Your browser does not support the audio element.
                </audio>
              </div>
              <div class="col-md-1" style="margin-left: -11px;">
                <button type="button" class="btn btn-icon btn-danger remove-audio" 
                  url="{{route('multimedia-delete')}}" project-id="{{$data->id}}">
                  <i data-toggle="popover" data-placement="top" data-trigger="hover" 
                    data-container="body" data-content="Click Here to remove this audio" 
                    style="top:3px;" class="bx bx-x">
                  </i>
                </button>
              </div>
            </div>  
            <div class="row mt-1">
              <button type="submit" class="btn btn-success audio-upload">Upload
              </button>
            </div>
          </div>
              
        </form>
      </div>
			
    </div>

  </div>

</div>
<!-- Add Audio Modal End -->