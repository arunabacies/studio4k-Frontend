<!-- Add User Modal --- Start -->
<div class="modal fade primary" id="addUserModal" tabindex="-1" role="dialog" 
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">

          <form method="post" action="{{route('addUser')}}" id="addDomain">
          @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add New User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="bx bx-x"></i></button>
              </div>
              <div class="modal-body">
                
                <div class="form-group">
                  <label>Name</label>  
                  <input class="form-control" type="text" placeholder="Full Name" name="name" 
                    value="" required>
                </div>
                
                <div class="form-group">
                  <label>Email</label>  
                  <input class="form-control" type="email" placeholder="Email Address" 
                    name="email" value="" required>
                </div>  

                <div class="form-group">
                  <label class="mb-50">User Role</label> 
                  <ul class="list-unstyled mb-0">

                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                        <div class="radio radio-primary radio-glow">
                            <input type="radio" id="admin" name="userrole" value="admin">
                            <label for="admin">Admin</label>
                        </div>
                      </fieldset>
                    </li>
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                        <div class="radio radio-primary radio-glow">
                            <input type="radio" id="pm" name="userrole"value="project manager" >
                            <label for="pm">Manager</label>
                        </div>
                      </fieldset>
                    </li>
                    
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                          <div class="radio radio-primary radio-glow">
                              <input type="radio" id="eng" name="userrole" value="engineer" checked="">
                              <label for="eng">Engineer</label>
                          </div>
                      </fieldset>
                    </li>
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                        <div class="radio radio-primary radio-glow">
                            <input type="radio" id="crew" name="userrole" value="crew">
                            <label for="crew">Crew</label>
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
      <!-- Add User Modal End -->

      <!-- Edit User Modal --- Start -->
      <div class="modal fade primary" id="editUser" tabindex="-1" role="dialog" 
        aria-labelledby="editUserLabel">
        
        <div class="modal-dialog modal-dialog-centered" role="document">
          
          <form method="post" action="{{route('edit-user')}}" id="addDomain">

            @csrf
            <input id="editUserId" type="text" value="" name="id" hidden>
            <input id="editUserName" type="text" value="" name="editUserName" hidden>
            <input id="status" type="text" value="" name="status" hidden>
            
            <div class="modal-content">
              
              <div class="modal-header bg-warning">
                <h4 class="modal-title text-white" id="myModalLabel">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="bx bx-x"></i></button>
              </div>

              <div class="modal-body">
                
                <div class="form-group">
                  <label>Name</label>  
                  <input class="form-control" type="text" placeholder="Full Name" name="name" 
                    value="" id="editName" required>
                </div>
                
                <div class="form-group">
                  <label>Email</label>  
                  <input class="form-control" disabled type="text" placeholder="Email Address" 
                    name="email" value="" id="editEmail" required>
                </div>  

                <div class="form-group">
                  
                  <label class="mb-50">User Role</label> 
                  <ul class="list-unstyled mb-0">
                    
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                        <div class="radio radio-primary radio-glow">
                            <input type="radio" id="eadmin" name="euserrole" value="1">
                            <label for="eadmin">Admin</label>
                        </div>
                      </fieldset>
                    </li>
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                        <div class="radio radio-primary radio-glow">
                            <input type="radio" id="epm" name="euserrole" value="2">
                            <label for="epm">Manager</label>
                        </div>
                      </fieldset>
                    </li>
                    
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                          <div class="radio radio-primary radio-glow">
                              <input type="radio" id="eeng" name="euserrole" value="3">
                              <label for="eeng">Engineer</label>
                          </div>
                      </fieldset>
                    </li>
                    <li class="d-inline-block mr-2 mb-1">
                      <fieldset>
                        <div class="radio radio-primary radio-glow">
                            <input type="radio" id="ecrew" name="euserrole" value="4">
                            <label for="ecrew">Crew</label>
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
      <!-- Edit User Modal End -->