<!-- Add Project Modal --- start -->
<div class="modal fade primary" id="addProjectModal" tabindex="-1" role="dialog" 
      aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="{{route('add-project')}}" style="width: 100%;">
          @csrf
        
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">Add New Project</h4>
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
                <input type="checkbox" id="recording" name="recording" checked> Record Call
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
    <div class="modal fade primary" id="editProjectModal" tabindex="-1" role="dialog" 
      aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="post" action="{{route('edit-project')}}" style="width: 100%;">
        @csrf

          <input type="text" id="projectId" name="projectId" value="" hidden/>
          <input type="text" id="assignedMembers" name="assignedMembers" value="" hidden/>
          <div class="modal-content">
            <div class="modal-header bg-warning">
              <h4 class="modal-title text-white" id="myModalLabel">Edit Project</h4>
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
                <input type="checkbox" id="recording" name="recording"> Record Call
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
    <!-- Edit Project Modal End -->