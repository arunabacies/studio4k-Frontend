
@extends('auth.layout.layout')

@section('title')
    <title>Sign Up Page - Oasis Connect</title>
@endsection

@section('content')

    <div class="card mb-0 p-2 h-100 d-flex justify-content-center" style="background-color: #02020C;">
        <div class="card-header pb-1" style="background-color: #02020C;">
            <div class="card-title w-100 text-center mb-2">
                <h1><img style="max-width:78%;margin:0 auto;" src="{{ asset('app-assets/images/logo/oasis-logo.png') }}"></h1>
                <h4 class="text-center mt-2 text-white text-bold-700">Sign In</h4>
                <div class="text-center"><small class="mr-25">Don't have an account?</small><a
                    href="{{ route('getlogin') }}"><small>Sign up</small></a></div>
                </div>
            </div>
            
            <div class="card-body">
                        
                <form method="POST" action="{{ route('signup') }}" enctype="multipart/form-data">
                    @csrf
                    
                    @error('error_msg')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <input name = "token" value = "{{ request()->token }}" hidden>
                    <div class="form-group mb-2">
                        <label class="text-bold-700" for="exampleInputEmail1">Name</label>
                        <input type="text" class="form-control" id="exampleInputEmail1"
                            placeholder="Name" name="name" value="{{ request()->name }}" >
                            
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-2">
                        <label class="text-bold-700" for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1"
                                placeholder="Password" name="password" >
                                
                        @error('password')
                            <div class="error">{{ $message }}</div>
                        @enderror
                                    
                    </div>
                    
                    <div class="form-group mb-2">
                        <label class="text-bold-700" for="exampleInputPassword1">Confrm Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1"
                            placeholder="Password" name="confirmPassword" >
                            
                        @error('confirmPassword')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        
                        @error('pwmatch')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label class="text-bold-700" for="exampleInputPassword1">Avatar</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>
                                
                    <button type="submit" class="btn btn-primary glow w-100 position-relative text-bold-700">
                        Sign Up<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
                                
                </form>
                        
            </div>
        </div>
    </div>

@endsection
                