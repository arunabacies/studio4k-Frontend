@extends('layouts.app')

@section('content')
<!-- avatart -->
<section id="content-types" class="project_list">
  <div class="row">
    <div class="col-12 mb-1">
      <div class="d-flex justify-content-between">
        <h1>User Profile</h1>
                  
      </div>
      <hr/>
      <form method="POST" action="{{ route('edit-profile') }}" enctype="multipart/form-data">
      @csrf
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
          <input class="form-control avatar-file-name" type="text"  name="avatarFileName" 
            value="{{$data->avatar_file_name}}" hidden>
          <input class="form-control delete" type="text" name="deleteAvatar" 
            value="" hidden>

            <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="id" value="{{$data->id}}" hidden>
                  <input class="form-control" type="text"  name="name" value="{{$data->name}}" >

            </div>

            <div class="form-group">
                <label>Avatar</label>
                <input class="form-control" type="file" name="avatar">
              </div>

              <button type="submit" class="btn btn-success" style="margin-top:23px;">Submit</button>

          </div>
          <div class="col-md-3">
            @if ($data->avatart)
            <div style="position: relative;">
              <img src="{{$data->avatart}}" loading="lazy" height="200px" width="200px" alt>
            
              <button type="button" class="close profile-image-remove" style="position: absolute;z-index: 1;right: 33px;">
                <span style="color: red;font-size: 2rem;">&times;</span>
              </button>
            </div>
            @endif
          </div>
        </div>
      </div>
      
        
      </form>
    </div>
  </div>
</section>


@endsection