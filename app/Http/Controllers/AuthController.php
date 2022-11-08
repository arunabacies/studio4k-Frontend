<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;

class AuthController extends Controller
{
    /** Login */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $client = new \GuzzleHttp\Client();
        $res = $client->post(config('app.base_url').'user/login', [
            'json' => ['email' => $email,'password'=> $password] // or 'json' => [...]
        ]);
        $data = json_decode($res->getBody());
        if($data->status == 200) {
            session(['token' => $data->auth_token]);
            session(['role' => $data->data->user_role]);
            session(['name' => $data->data->name]);
            session(['avatar' => $data->data->avatart]);
            return Redirect::route('project',1);
        } else if($data->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $data->message]);
        } else {
            
            return redirect()->back()->withErrors(['error_msg' => $data->message]);
        }
        
    }
    
    public function register($token){
        return view('auth.register',['token'=> $token]);
    }

    /** Register User */
    public function signup(Request $request)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $password_confirmation = $request->input('confirmPassword');
        $array = array();

        if ($_FILES['avatar']['name'] !== "" ) {
            $tmpfile = $_FILES['avatar']['tmp_name'];
            $filename = basename($_FILES['avatar']['name']);
            $file = curl_file_create($tmpfile, $_FILES['avatar']['type'], $filename);
            $array['file'] = $file;
        }

        if ($password != $password_confirmation) {
            return redirect()->back()->withErrors(['pwmatch'=>'Passwords do not match']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'confirmPassword' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $array['name'] = $name;
        $array['password'] = $password;
        $array['confirm_password'] = $password_confirmation;

        $token =$request->input('token');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config('app.base_url').'user/registration_from_invitation',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $array,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' .$token, 
        )));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $data = json_decode($response);

        if($data->status == 200) {
            return view('auth.login');
        } else if($data->status == 401) { 
            return redirect()->back()->withErrors(['error_msg' => $data->message]);
        } else {
            
            return redirect()->back()->withErrors(['error_msg' => $data->message]);
        }
        
    }

    public function forgotPassword(){
        return view('auth.email');
    }

    public function resetPassword($token){
        return view('auth.passwords.reset',['token'=> $token]);
    }

    /** Reset password */
    public function passwordResetLink(Request $request) 
    {
        $email = $request->input('email');

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $client = new \GuzzleHttp\Client();
        $res = $client->post(config('app.base_url').'user/forgot_password', [
       
            'json' => ['email'=> $email] // or 'json' => [...]
        ]);
        
        $data = json_decode($res->getBody());

        if($data->status == 200) {
            return view('auth.login');
        } else if($data->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $data->message]);
        } else {
            return redirect()->back()->withErrors(['error_msg'=> $data->message]);
        }

    }

    public function updatePassword(Request $request) 
    {
        $password = $request->input('password');
        $password_confirmation = $request->input('confirmPassword');
        $token =$request->input('token');

        if ($password != $password_confirmation) {
            return redirect()->back()->withErrors(['pwmatch'=>'Passwords do not match']);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirmPassword' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $client = new \GuzzleHttp\Client();
        $res = $client->patch(config('app.base_url').'user/reset_password', [
            'json' => ['new_password' => $password, 'confirm_password' => $password_confirmation],
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        $data = json_decode($res->getBody());
       
        if($data->status == 200) {
            return view('auth.login');
        }  else if($data->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $data->message]);
        } else {
            return redirect()->back()->withErrors(['error_msg'=> $data->message]);
        }

    }

    public function logout(Request $request) 
    {
        $request->session()->flush();
        // $token = $request->session()->get('token');
        // $client = new \GuzzleHttp\Client();
        // $res = $client->get(config('app.base_url').'user/logout',[
        //     'headers' => 
        //     [
        //         'cache-control' => 'no-cache', 
        //         'authorization' => 'Token ' .$token, 
        //         'content-type'  => 'application/json'
        //     ],
        // ]);
        // $data = json_decode($res->getBody());
        // if($data->status == 200) {
            
        //     // Session::flush();
            
        //     session()->forget('token');
        //     session()->forget('role');
        //     session()->forget('name');
        //     session()->forget('avatar');
        //     $request->session()->flush();
            
        // }
        return Redirect::to('/');
    }
}