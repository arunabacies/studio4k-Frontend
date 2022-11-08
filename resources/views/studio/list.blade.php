
          <div class="table-responsive">
            <table class="table zero-configuration">
              <thead>
                <tr>
                  <th>Session</th>
                  <th>Date</th>
                  <th>Presenters</th>
                  @if (Session::get('role') == 1)
                  <th>Go to Session</th>
                  @endif
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
						
                @if($sessions)
                  @foreach($sessions as $session)
                  @php 
                    
                    $presenters = $session->presenters;
                    
                   
                    $state_to_be = "";
                    $title ="";
                    $style_pointer = '';
                    if($session->state == 'upcoming') {
                      $terminate_class = 'bx-play-circle';
                      $state_to_be = "running";
                      $title = "Start";
                    } else if($session->state == 'running'){
                      $terminate_class = 'bx-x-circle';
                      $state_to_be = "closed";
                      $title = "Terminate";
                    } else if($session->state == 'closed') {
                      $terminate_class = 'bx-no-entry';
                      $style_pointer = 'style=pointer-events:none';
                    }
                    if($session->start_recording == true) {
                      $recording_icon = 'bx-stop-circle text-danger' ;
                      $recording_title = 'Stop';
                    } else {
                      $recording_icon = 'bx-cloud-upload text-warning';
                      $recording_title = 'Start';
                    }
                  @endphp

                  <tr>
                    <td>{{ $session->name }} </td>
                    <td>{{ date('d-m-Y', strtotime($session->session_time)) }}</td>
                    <td>{{ $presenters }}</td>
                    
                    @if (Session::get('role') == 1)
                    <td>
                      <button class="btn btn-danger go-link" 
                              data-id="{{ $session->id }}"
                              data-url="{{ route('go-link') }}" >Go</button>
                    </td>
                    @endif
                    
                    <td>
                      @if ($session->recordings > 0)
                      
                        <a data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-container="body" data-content="Click Here to View Recordings" 
                          href="{{route('studio-recordings', ['id'=>$data->id,'session_id'=>$session->id])}}">
                          <i class="bx bx-folder-open text-primary" 
                              title="Recordings" >
                          </i>
                        </a>

                      @endif

                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to {{ $recording_title }} Recording" >
                        <i class="bx {{ $recording_icon }} studio-session-record" data-toggle="modal" 
                            data-url="{{ route('studio-record-session') }}" 
                            data-id="{{ $session->id}}" 
                            data-name="{{ $session->name}}" 
                            studio-id="{{ $data->id }}"
                            start-recording="{{ $session->start_recording }}" 
                            title="{{ $recording_title }}" ></i>
                      </span>
                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to add Presenters" >
                        <i class="bx bx-user-plus text-success session-add-presenter" 
                          data-toggle="modal" 
                          data-target="#addSessionPresenter"
                          session-id="{{ $session->id}}" 
                          project-id="{{ $data->id }}"
                          url="{{ route('get-studio-presenter') }}"
                          session-state="{{ $session->state }}"
                          style="font-size: 1.4em;">
                        </i>
                      </span>
                      
                      @if($presenters > 0)
                        <span data-toggle="popover" data-placement="top" data-trigger="hover"
                          data-container="body" data-content="Click Here to send mail" 
                          {{$style_pointer}}>
                          <i class="bx bx-mail-send text-primary send-mail-button" 
                            session-id="{{ $session->id}}"
                            presenters="{{ json_encode($presenters) }}" 
                            url="{{ route('studio-presenter-email-sent') }}"></i>
                        </span>
                      @endif

                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to {{ $title }} Session" 
                        {{$style_pointer}}>
                        <i class="bx {{$terminate_class}} text-danger terminate-session" 
                          data-id="{{ $session->id}}" 
                          data-name="{{$session->name}}" 
                          data-url="{{route('terminate-studio-session')}}" 
                          title="{{ $title }}" 
                          state_to_be="{{ $state_to_be }}"></i>
                      </span>
                      
                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to edit Session" >
                        <i class="bx bx-edit-alt text-warning sessionEdit" data-toggle="modal" 
                            data-target="#editstudioSession"
                            data-url="{{ route('get-session') }}" 
                            data-id="{{ $session->id}}" 
                            project-id="{{ $data->id }}"
                            assigned-users="{{ json_encode($assigned_users) }}" 
                            timezone="{{ $timezone->zone }}"
                            session-state="{{ $session->state }}"
                            title="Edit" ></i>
                      </span>
                      

                      
                      <span data-toggle="popover" data-placement="top" data-trigger="hover"
                        data-container="body" data-content="Click Here to delete Session">
                        <i class="bx bx-trash text-danger deleteEvent" 
                          data-id="{{$session->id}}" 
                          data-name="{{$session->name}}" 
                          data-url="{{route('delete-studio-session')}}"
                          project-id="{{$data->id}}"></i>
                      </span>
                    </td>
                  </tr>

                @endforeach
                @endif   
              </tbody>
            </table>
          </div>