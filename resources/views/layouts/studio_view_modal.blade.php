<!-- Add Session Modal --- start -->
<div class="modal fade primary" id="newStudioSession" tabindex="-1" role="dialog" 
    aria-labelledby="newStudioSessionLabel">
      
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <form method="post" action="{{route('create-studio-session')}}" style="width: 100%;">
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
                        <label>Session Time</label>  
                        <input type="text" name="session_time" class="form-control "  
                        placeholder="Pick a Date &amp; Time" id='demo' required>
                    </div>

                    <div class="form-group">
                        <label>Users</label>
                        <select class="select2 form-control assign-users" multiple data-live-search="true" name="users[]">
                        @if($assigned_users)
                            @foreach($assigned_users as $user)
                            <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                        </select>
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
<div class="modal fade primary" id="editstudioSession" tabindex="-1" role="dialog" 
    aria-labelledby="editStudioSessionLabel">
      
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        
        <form method="post" action="{{route('edit-studio-session')}}" style="width:100%">
        @csrf

            <input type="text" name="projectId" value="{{$data->id}}" hidden>
            <input type="text" id="eventId" name="eventId" value="" hidden>
            <input type="text" id="assignedUsers" name="assignedUsers" value="" hidden>
            <input type="text" id="members" name="members" value="" hidden>

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
                        <label>Session Time</label>  
                        <input type="text" name="session_time" class="form-control "  
                            placeholder="Pick a Date &amp; Time" id='editSessionTime' required>
                    </div>

                    <div class="form-group multi-field">
                        <label>Users</label>
                        <select id="roomFirstUsers" class="select2 form-control assign-users" 
                            multiple data-live-search="true" name="users[]">

                        @if($assigned_users)
                            @foreach($assigned_users as $user)
                            <option value="{{ $user->user_id }}">{{ $user->name }}</option>
                            @endforeach
                        @endif

                        </select>
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
<div class="modal fade primary" id="addSessionPresenter" tabindex="-1" role="dialog" 
  aria-labelledby="addSessionPresenterLabel">

  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

    <form method="post" action="{{route('create-update-studio-presenter')}}" 
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

        </div>

        <div class="generate-link-error">Please generate url</div>
            
        <div class="modal-footer">
            
          <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cancel</button>
          <!-- <button type="button" class="btn btn-primary send-mail" 
              url="{{ route('presenter-email-sent') }}">Send Mail</button> -->
          <button type="submit" class="btn btn-success button-submit">Submit</button>

        </div>

      </div>

    </form>
  </div>
</div>
<!-- Add Presenter Modal End -->

<!-- uploadSettings -->
<div class="modal fade primary" id="uploadSettings" tabindex="-1" role="dialog" 
    aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="" style="width: 100%;">
        @csrf

            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title text-white" id="myModalLabel">Upload Settings</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
            
                <div class="modal-body">
                  <input id="studioId" type="text" value="{{ $data->id }}" hidden>
                    <input id="url" type="text" value="{{ config('app.base_url') }}" hidden>
                    <input id="storageUrl" type="text" value="{{ route('storage-credentials') }}" hidden>
                    <input id="token" type="text" value="{{ Session::get('token') }}" hidden>
                    
                  <div class="radio radio-primary radio-glow">
                    
                    <input type="radio" id="googleDrive" name="uploadSettings" <?php echo $storage_source == 2? 'checked=""' :'' ?> value="google">
                    <label for="googleDrive">Google Drive</label>
                  </div>
                  <div class="google-drive-div">
                    
                    @if($storage_email && $storage_source == 2)
                    <!-- <div class="gmail"> -->
                      <!-- <div class="row"> -->
                        <!-- <div class="col-md-11"> -->
                    <label>Email</label>  
                    <input class="form-control" type="text" placeholder="Email" name="email" value="{{ $storage_email }}" id="googleEmail" disabled>
                    <!-- </div> -->
                    <!-- <div class="col-md-1">
                      <button type="button" class="close remove-storage-email btn btn-danger" style="margin-top: 23px;height: 37px;" type="gmail">
                      <span style="color: red;font-size: 2rem; padding: 10px;">&times;</span>
                      </button>
                    </div> -->
                    <!-- </div> -->
                    @else
                    <button type="button" class="btn btn-success gmail-login" style="margin: 10px;">
                      <strong>Google</strong>
                    </button>
                    @endif
                  </div>
                  <div class="radio radio-primary radio-glow" style="margin-top: 10px;margin: bottom 10px;">
                    <input type="radio" id="dropbox" name="uploadSettings" value="dropbox" <?php echo $storage_source == 3? 'checked=""' :'' ?>>
                    <label for="dropbox">Dropbox</label>
                  </div>
                  <div class="dropbox-div" style="display: none;margin:10px">
                    @if($storage_email && $storage_source == 3)
                    <div class="dropbox-email">
                    <label>Email</label>  
                    <input class="form-control" type="text" placeholder="Email" name="email" value="{{ $storage_email }}" id="dropboxEmail" disabled>
                    </div>
                    @else
                    <button type="button" class="btn btn-success dropbox-login" style="margin: 10px;">
                        <strong>Dropbox</strong></button>
                    @endif
                  </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning button-submit">Submit</button>
                </div> -->

            </div>

        </form>
    </div>
</div>

