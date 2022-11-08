<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Resources\PostCollection;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function index(Request $request) 
    {
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $result = json_decode($res->getBody());
        if($result->message == 'Success') 
        {
            return view('settings.time_settings', ['data' => $result->data]);
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        }
    }

    public function editTimezone(Request $request) 
    {
        $timezone = explode(',(', $request->timezone);
        $zone = $timezone[0];
        
        $timezone_value = $timezone[1];
        $value =  explode(')', $timezone_value)[0]; 
        
        $token = $request->session()->get('token');
        
        $client = new \GuzzleHttp\Client();
        
        $res = $client->put(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => ["zone" => $zone, "value" => $value] // or 'json' => [...]

        ]);
        
        $result = json_decode($res->getBody());
        
        if($result->message == 'Success') {
            session()->put('time_zone', $zone );
            return back()->withSuccess('success', "Timezone Updated");
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        }
        
    }

    

}