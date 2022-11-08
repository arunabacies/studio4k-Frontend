<!-- Add Studio Modal --- start -->
<div class="modal fade primary" id="addStudioModal" tabindex="-1" role="dialog" 
    aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="{{route('add-studio')}}" style="width: 100%;">
        @csrf
        
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add New Studio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
          
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>  
                    <input class="form-control" type="text" placeholder="Name" name="name" value="" required>
                </div>
            
                <div class="form-group">
                    <label>Client Name</label>  
                    <input class="form-control" type="text" placeholder="Name" name="client" value="" required>
                </div>  

                <div class="form-group">
                    <label>Job Number</label>  
                    <input class="form-control" type="text" placeholder="Job Number" name="jobNumber" value="" required>
                </div>

                <div class="form-group">
                    <label>Add Users</label>  
                    <select class="select2 form-control" name="users[]" multiple="multiple">
                        @if ($users)  
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" >{{ $user->name }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
					<label class="mb-50">Recording</label> 
						<ul class="list-unstyled mb-0">

							<li class="d-inline-block mr-2 mb-1">
							  <fieldset>
								<div class="radio radio-primary radio-glow">
									<input type="radio" id="recording1" name="recording" value="1" checked="">
									<label for="recording1">Audio & Video</label>
								</div>
							  </fieldset>
							</li>
							<li class="d-inline-block mr-2 mb-1">
							  <fieldset>
								<div class="radio radio-primary radio-glow">
									<input type="radio" id="recording2" name="recording" value="2" >
									<label for="recording2">Audio Only</label>
								</div>
							  </fieldset>
							</li>
							<li class="d-inline-block mr-2 mb-1">
							  <fieldset>
								<div class="radio radio-primary radio-glow">
									<input type="radio" id="recording3" name="recording" value="3">
									<label for="recording3">Video Only</label>
								</div>
							  </fieldset>
							</li>
						</ul>
                    
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
<!-- Add Project Modal End -->

<!-- Edit Project Modal --- start -->
<div class="modal fade primary" id="editStudioModal" tabindex="-1" role="dialog" 
    aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="{{route('edit-studio')}}" style="width: 100%;">
        @csrf

            <input type="text" id="projectId" name="projectId" value="" hidden/>
            <input type="text" id="assignedMembers" name="assignedMembers" value="" hidden/>
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title text-white" id="myModalLabel">Edit Studio</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
            
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>  
                        <input class="form-control" type="text" id="projectName" placeholder="Name" 
                        name="name" value="" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Client Name</label>  
                        <input class="form-control" type="text" id="clientID" placeholder="Name" 
                        name="client" value="" required>
                    </div>  

                    <div class="form-group">
                        <label>Job Number</label>  
                        <input class="form-control" id="jobNumber" type="text" placeholder="Job Number" 
                        name="jobNumber" value="" required>
                    </div>

                    <div class="form-group">
                        <label>Add Users</label>  
                        <select class="select2 form-control" name="users[]" multiple="multiple" 
                        id="members">
                        @if ($users)  
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" >{{ $user->name }}</option>
                            @endforeach
                        @endif
                        </select>
                    </div> 
                    
                    <div class="form-group">
                        <label class="mb-50">Recording</label> 
						<ul class="list-unstyled mb-0">

							<li class="d-inline-block mr-2 mb-1">
							  <fieldset>
								<div class="radio radio-primary radio-glow">
									<input type="radio" id="erecording1" name="recording" value="1" >
									<label for="erecording1">Audio & Video</label>
								</div>
							  </fieldset>
							</li>
							<li class="d-inline-block mr-2 mb-1">
							  <fieldset>
								<div class="radio radio-primary radio-glow">
									<input type="radio" id="erecording2" name="recording" value="2" >
									<label for="erecording2">Audio Only</label>
								</div>
							  </fieldset>
							</li>
							<li class="d-inline-block mr-2 mb-1">
							  <fieldset>
								<div class="radio radio-primary radio-glow">
									<input type="radio" id="erecording3" name="recording" value="3">
									<label for="erecording3">Video Only</label>
								</div>
							  </fieldset>
							</li>
						</ul>
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
<!-- Edit Studio Modal End -->

