
<?php use App\Http\Controllers\ProjectController; $ProjectController= new ProjectController(); ?>

@extends('layouts.app')

@section('content')
    <!-- Project listing section start -->
    <section id="content-types" class="project_list">
        <div class="row">
            <div class="col-12 mb-1">
                <div class="d-flex justify-content-between">
                    <h1>Projects</h1>
                    @if (Session::get('role') == 1 ) 
                        <button class="btn btn-warning" data-toggle="modal" 
                            data-target="#addProjectModal">
                            <i class="bx bx-plus-circle">&nbsp;</i>New Project
                        </button>
                    @endif
                </div>
                <hr/>
            </div>

            @if($data)
  
                @foreach($data as $project)
                @php
                    $total_events = $project->events;
                    $members = $project->members;
                    $total_members = count($members);
                    $member_ids = array();
                    $crews = array();
                    $pm = array();
                    $admin = array();
                    foreach($members as $member) {
                        array_push($member_ids,$member->id);
                        
            
                        if($member->user_role == 1) {
                            array_push($admin,$member->name);
                        }
                        if($member->user_role == 2) {
                            array_push($pm,$member->name);
                        }
                        if($member->user_role == 5) {
                            array_push($crews,$member->id);
                        }
                        
                        
                    }
                @endphp

                <div class="col-xl-4 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-header mb-2">
                            <h4 class="card-title text-bold-700">{{ $project->name }}
                                <small class="d-block mt-50 text-bold-400">
                                    {{ date('d-m-Y', strtotime($project->created)) }}
                                </small>
                            </h4>
                            @if (Session::get('role') == 1) 
                                <div class="dropdown">
                                    <div class="dropdown-toggle cursor-pointer" role="button" 
                                        id="dropdownMenuButton" data-toggle="dropdown" 
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </div>
                                    <div class="dropdown-menu dropdown-menu-right" 
                                        aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item projectEdit" href="javascript:void(0);" 
                                            data-toggle="modal" data-target="#editProjectModal" 
                                            data-id="{{ $project->id }}" 
                                            projectName="{{ $project->name }}"
                                            client-name="{{$project->client_name}}" 
                                            members="{{ json_encode($member_ids) }}" 
                                            job-number="{{ $project->job_number }}" 
                                            recording="{{ $project->recording }}">
                                            <i class="bx bx-pencil mr-50"></i>Edit
                                        </a>
                                        <a class="dropdown-item kanban-delete delete" id="kanban-delete" 
                                            href="javascript:void(0);"
                                            data-id="{{$project->id}}" 
                                            data-name="{{$project->name}}" 
                                            data-url="{{route('delete-project')}}">
                                            <i class="bx bx-trash mr-50"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-2" style="flex-wrap: wrap;">
                                <p class="w-50 mb-50 title">PROJECT MANAGER:</p>
                                <p class="w-50 text-right mb-50 desc">{{ $pm[0] ?? '' }} 
                                    @if(count($pm) > 1)
                                        <span data-toggle="popover" data-placement="top" 
                                            data-trigger="hover" data-container="body" 
                                            data-content='{{ implode(", ", $pm) }}' 
                                            style="display:inline-flex;
                                                    margin-left: 7px;
                                                    margin-bottom:0px !important; 
                                                    cursor:pointer;
                                                    width:18px;
                                                    height:18px;" 
                                            class="badge badge-circle badge-circle-sm badge-circle-light-info mb-1">
                                            <i style="font-size:0.8rem;" class="bx bx-dots-vertical-rounded font-size-base"></i>
                                        </span>
                                    @endif
                                </p>
                                <p class="w-50 mb-50 title">ADMIN:</p>
                                <p class="w-50 text-right mb-50 desc">
                                    {{ $admin[0] ?? '' }} 
                                    @if(count($pm) > 1)
                                        <span data-toggle="popover" data-placement="top" 
                                            data-trigger="hover" data-container="body" 
                                            data-content='{{ implode(", ", $admin) }}' 
                                            style="display:inline-flex;
                                                    margin-left: 7px;
                                                    margin-bottom:0px !important; 
                                                    cursor:pointer;
                                                    width:18px;
                                                    height:18px;" 
                                            class="badge badge-circle badge-circle-sm 
                                                badge-circle-light-info mb-1">
                                            <i style="font-size:0.8rem;" 
                                                class="bx bx-dots-vertical-rounded font-size-base"></i>
                                        </span>
                                    @endif
                                </p>
                                <p class="w-50 mb-50 title">JOB:</p>
                                <p class="w-50 text-right mb-50 desc">{{ $project->job_number }}</p>
                            </div>
                            <div class="d-flex justify-content-between text-center">
                                <p class="m-0">Members<small class="d-block">{{ $total_members }}</small></p>
                                <p class="m-0">Crew<small class="d-block">{{ count($crews)}}</small></p>
                                <p class="m-0">Sessions<small class="d-block">{{ $total_events }}</small></p>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center mb-50">
                            <a class="btn btn-danger" href="{{route('view-project', $project->id)}}">Go</a>
                        </div>
                    </div>
                </div>

                @endforeach
            @endif

        </div>
        @if($data)
                
                <div class="pagination">
                    <p class="result-info">Showing {{$data->firstItem()}}-{{$data->lastItem()}} results of {{$data->total()}}</p>
                    <!-- <button class="an-btn an-btn-transparent rounded uppercase small-font">Show More</button> -->
                    {!!$ProjectController->paginate_with_custom_path($data)!!}
                  </div>
               
            @endif

    </section>
    <!-- Project listing section end -->

@endsection      