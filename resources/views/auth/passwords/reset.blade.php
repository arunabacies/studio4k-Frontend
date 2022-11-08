
@extends('auth.layout.layout')

@section('title')
    <title>Reset Password - Oasis Connect</title>
@endsection

@section('content')

<div class="card mb-0 p-2 h-100 d-flex justify-content-center" style="background-color: #02020C;">
    <div class="card-header pb-1" style="background-color: #02020C;">
        <div class="card-title w-100 text-center mb-2">
            <h1><img style="max-width:78%;margin:0 auto;" 
                src="{{ asset('app-assets/images/logo/oasis-logo.png') }}">
            </h1>
            <h4 class="text-center mt-2 text-white text-bold-700">Reset Password</h4>
            <div class="text-center"><small class="mr-25">Already member?</small>
                <a href="{{ route('getlogin') }}"><small>Sign In</small></a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
                            
        <form method="POST" action="{{ route('update-password') }}">
        @csrf
        
            <input name = "token" value = "{{ request()->token }}" hidden>

            <div class="form-group mb-3">
                <label class="text-bold-700" for="exampleInputPassword1">New Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1"
                    placeholder="Password" name="password">

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="text-bold-700" for="exampleInputPassword1">Confirm Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1"
                    placeholder="Confirm Password" name="confirmPassword">
                @error('confirmPassword')
                    <div class="error">{{ $message }}</div>
                @enderror
                @error('pwmatch')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
                                
            <button type="submit" class="btn btn-primary glow w-100 position-relative text-bold-700">Reset Password<i</button>
                                
        </form>
    </div>
</div>

@endsection