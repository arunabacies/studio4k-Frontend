<?php use App\Http\Controllers\UserController; $UserController= new UserController(); ?>

@extends('layouts.app')

@section('content')
<!-- Basic card section start -->
<section id="content-types mb-1">
  <div class="row">
    <div class="col-12 mb-1">
      <div class="d-flex justify-content-between">
        <h1>Users</h1>
        @if (Session::get('role') == 1)
        <button class="btn btn-warning" data-toggle="modal" data-target="#addUserModal"><i class="bx bx-plus-circle">&nbsp;</i>New User</button>
        @endif     
      </div>
      <hr/>
    </div>
  </div>
</section>
<!-- Basic Card types section end -->

<!-- Data table -->
<section id="user-datatable">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="text-white">Users</h3>
        </div>
        <div class="card-body card-dashboard">
          <input id="userUrl" type="text" 
            value="{{ route('user-settings',['page'=>1])}}" hidden>
          <div class="table-responsive">
            @include('user/user_table')

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/ Data table -->

@endsection
