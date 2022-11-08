
@extends('auth.layout.layout')

@section('title')
    <title>Forgot Password - Oasis Connect</title>
@endsection

@section('content')

<div class="card mb-0 p-2 h-100 d-flex justify-content-center" style="background-color: #02020C;">
    <div class="card-header pb-1" style="background-color: #02020C;">
        <div class="card-title w-100 text-center mb-2">
            <h1>
                <img style="max-width:78%;margin:0 auto;" 
                    src="{{ asset('app-assets/images/logo/oasis-logo.png') }}">
            </h1>
            <h4 class="text-center mt-2 text-white text-bold-700">Reset Password </h4>
            
            <div class="text-center"><small class="mr-25">Already member? </small>
                <a href="{{ route('getlogin') }}"><small>Sign In</small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
             
        @error('error_msg')
        <div class="error">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('password-reset-link') }}">
        @csrf
            <div class="form-group mb-2">
                <label class="text-bold-700" for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1"
                    placeholder="Email address" name="email">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary glow w-100 position-relative 
                text-bold-700">{{ __('Send Password Reset Link') }}
            </button>
                                
        </form>
    </div>
</div>

@endsection