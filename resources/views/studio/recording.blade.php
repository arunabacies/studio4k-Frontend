<?php use App\Http\Controllers\ProjectController; $ProjectController= new ProjectController(); ?>

@extends('layouts.app')

@section('content')
@php 
$creator = $data->created_by;
$crew_list = array();
$recording_type = $data->recording_type;
if ($recording_type == 2 ) {
  $recording_text = "audio/";
} else {
  $recording_text = "video/";
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
            <p class="w-100 mb-50 text-primary">Date: {{ date('d-m-Y', strtotime($data->created_at)) }}</p>
            
          </div>
        </div>

        <div class="col-md-3 col-sm-5">
          <div class="d-flex mb-2 pt-2" style="flex-wrap: wrap;">
            <p class="w-50 mb-50">Crews:</p>
            <p class="w-50 text-right mb-50">{{ count($crew_list) }}</p>
            <p class="w-50 mb-50">Presenters:</p>
            <p class="w-50 text-right mb-50">{{ $data->presenters }}</p>
          </div>
        </div>

      </div>

    </div>
    </div>
    </div>

  </div>
</section>


<!-- Data table -->
<section id="recording-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-white">Recordings</h3>
        </div>
        <div class="card-body card-dashboard">
          <div class="table-responsive">
            <table class="table zero-configuration">
              <thead>
                <tr>
                  <!-- <th>ID</th> -->
                  <th>Name</th>
                  <th>Format</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $recordings = $data->recordings; @endphp
                @if($recordings)
                @foreach($recordings as $recording)
                  @php $urls = $recording->urls; @endphp
                  @foreach ($urls as $url)
                  <tr>
                    <td>{{$recording->name}}</td>
                    <td>{{ $recording_text }}webm</td>
                    
                    <td>
                      <a href="{{$url}}" target="_blank">
                        <i class="bx bx-play-circle" style="color: #5a8dee !important;" ></i>
                      </a>
                    </td>
                    
                  </tr>
                  @endforeach

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

@endsection
