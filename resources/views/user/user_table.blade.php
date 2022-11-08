<table class="table zero-configuration">
              <thead>
                <tr>
                  <!-- <th>ID</th> -->
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @if($data)
                @foreach($data as $user)
                @php 
                if($user->user_role == 1)
                $role = 'Admin';
                elseif($user->user_role == 2 )
                $role = 'Manager';
                elseif($user->user_role == 3)
                $role = 'Engineer';
                elseif($user->user_role == 4)
                $role = 'Crew';
                
                if($user->is_active == 1 && $user->registered == 1 ) {
                  $status = "Active";
                  $badge = "badge-light-success";
                }
                else if($user->is_active == 1 && $user->registered != 1 ) {
                  $status = "Invited";
                  $badge = "badge-light-warning";
                }
                else {
                  $status = "InActive";
                  $badge = "badge-light-warning";
                }
                @endphp
                <tr>
                  <!-- <td>1</td> -->
                  <td>{{$user->name}}</td>
                  <td>{{$user->email}}</td>
                  <td>{{$role}}</td>
                  <td><span class="badge {{$badge}}">{{$status}}</span></td>
                  <td>
                    <i class="bx bx-edit-alt text-warning userEdit" 
                              data-toggle="modal" data-target="#editUser" data-id="{{$user->id}}" 
                              data-email="{{$user->email}}" data-name="{{$user->name}}" role="{{$user->user_role}}" status="{{$user->is_active}}"></i>
                    <i class="bx bx-trash text-danger delete-user" role="{{$user->user_role}}" data-id="{{$user->id}}" 
                                data-name="{{$user->name}}" data-url="{{route('delete-user')}}"></i>
                  </td>
                </tr>
                @endforeach
                @endif
              </tbody>
            </table>

            @if($data)
                
                <div class="pagination">
                    <p class="result-info">Showing {{$data->firstItem()}}-{{$data->lastItem()}} results of {{$data->total()}}</p>
                    <!-- <button class="an-btn an-btn-transparent rounded uppercase small-font">Show More</button> -->
                    {!!$UserController->paginate_with_custom_path($data)!!} 
                  </div>
               
            @endif