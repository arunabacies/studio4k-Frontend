@extends('auth.layout.layout')

@section('title')
    <title>Login Page - Oasis Connect</title>
@endsection

@section('content')

<div class="card mb-0 p-2 h-100 d-flex justify-content-center" style="background-color: #02020C;">
  <div class="card-header pb-1" style="background-color: #02020C;">
    
    <div class="card-title w-100 text-center mb-2">
      <h1>
        <img style="max-width:78%;margin:0 auto;" 
          src="{{ asset('app-assets/images/logo/oasis-logo.png') }}">
      </h1>
      <h4 class="text-center mt-2 text-white text-bold-700">Sign In</h4>
                                
    </div>
  </div>
  
  <div class="card-body">
                            
    @error('error_msg')
      <div class="error">{{ $message }}</div>
    @enderror
    
    <form method="POST" action="{{ route('login') }}">
    @csrf
      
      <div class="form-group mb-2">
        <label class="text-bold-700" for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1"
          placeholder="Email address" name="email" >
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group mb-3">
        <label class="text-bold-700" for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1"
          placeholder="Password" name="password">

        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
                                
      <button type="submit" class="btn btn-primary glow w-100 position-relative text-bold-700">Login
        <i id="icon-arrow" class="bx bx-right-arrow-alt"></i>
      </button>
      
      <div class="text-center mt-2">
        <a href="{{ route('forgot-password') }}" class="card-link"><small>Forgot Password?</small>
        </a>
      </div>
      
    </form>
    
  </div>
</div>

@endsection