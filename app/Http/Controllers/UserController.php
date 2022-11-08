<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use Session;

class UserController extends Controller
{
    public function index(Request $request,$page) {
        $per_page = $request->has('per_page') ? $request->per_page : 10;
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        if(Session::get('role') == 1) {
            $url = 'admin/list_users?page='.$page.'&per_page='.$per_page;
        } else {
            $url = 'user/list_users';
        }
        try {
            $res = $client->get(config('app.base_url').$url, [
                'headers' => 
                [
                    'cache-control' => 'no-cache', 
                    'authorization' => 'Token ' .$token, 
                    'content-type'  => 'application/json'
                ],
            ]);
        } catch (ServerException $e) {
            // dd($e->getCode());
            return view('errors.503');
        } catch (ClientException $e) {
            return view('errors.503');
        }
        
        $result = json_decode($res->getBody());
        if($request->has('per_page')) {
            return $res->getBody();
        }
        if($result->message == 'Success') 
        {
            if(Session::get('role') == 1) {
                $pagination = $result->pagination;
                $collection = collect($result->data);
                $paginator = new LengthAwarePaginator(
                    $collection, 
                    $pagination->total, 
                    $pagination->per_page, 
                    $pagination->current_page,
                    ['path' => $request->url()]
                );

                $data = PostCollection::make($paginator);
            } else {
                $data = $result->data;
            }
            // dd($data);
            
            // dd($data);
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        }
        if(Session::get('role') == 1) {
        return view('user.user_settings')->with(['data' => $data ?? '']);
        } else {
            return view('user.user_list')->with(['data' => $data ?? '']);
        }
    }

    public function paginate_with_custom_path($data) {
        if ($data->hasPages()) {
            $links = $data->links();
            // dd($links);
            // $match = preg_match('#user-settings/1/#',$links);
            // dd($match);
            $pattern2 = '#/([1-9]+[0-9]*)/([1-9]+[0-9]*)#';
            $replacements2 = '/$2';
            $paginate_links = preg_replace($pattern2, $replacements2, $links);
            $p = '#\?page=([1-9]+[0-9]*)#';
            preg_match($p, $paginate_links, $matches);
            // dd($paginate_links,$matches);
            $pg = $matches[1];
            $p1 = '#user-settings/([1-9]+[0-9]*)#';
            preg_match($p1, $paginate_links, $matches1);
            $per_pg = $matches1[1];
            // dd($paginate_links,$p,$matches,$matches1);
            $patterns = '#/([1-9]+[0-9]*)#';
            $replacements = '/';
            $one = preg_replace($patterns, $replacements, $paginate_links);
            $pattern1 = '#\?page=#';
            // $replacements1 = $pg.'/'.$per_pg;
            $replacements1 = '';
            return $two = preg_replace($pattern1, $replacements1, $one);
            // dd($two);
            $pattern3 = '#user-settings/([1-9]+[0-9]*)#';
            // dd($pattern3,$pattern3.'/'.$per_pg);
            // $replacements3 = '#/([1-9]+[0-9]*)/#'.$per_pg;
            $replacements3 = '/'.$per_pg;
            $three = str_replace($pattern3, $replacements3, $two);
            // dd($three);
            // $pattern3 = '#user-settings/([1-9]+[0-9]*)#';
            // $replacements3 = 'user-settings/([1-9]+[0-9]*)/$2';
            // $three = preg_replace($pattern3, $replacements3, $two);
            dd($three);
            // return $paginate_links;
        }
        
    }

    // USER_ROLE = {'1': "Admin", '2': "Manager", '3': "Engineer", '4': "Crew", 5: "Presenter", 6: "Bot"}
    // Add user 
    public function addUser(Request $request)
    {
        
        if($request->userrole == 'admin')
            $role = 1;
        elseif($request->userrole == 'project manager')
            $role = 2;
        elseif($request->userrole == 'engineer') 
            $role = 3;
        elseif($request->userrole == 'crew') 
            $role = 4;
            
        $name = $request->name;
        $email = $request->email;
        $token = $request->session()->get('token');
        $res = new \GuzzleHttp\Client();
        $result = $res->post(config('app.base_url').'admin/invite_new_user', [
                'headers' => 
                [
                    'cache-control' => 'no-cache', 
                    'authorization' => 'Token ' .$token, 
                    'content-type'  => 'application/json'
                ],
                'json' => [ 'email' => $email,
                            'name' => $name, 
                            'user_role' => $role
                          ] // or 'json' => [...]
            ]);
        
        $user = json_decode($result->getBody());

        if($user->message == 'success') {
            return Redirect::back()->with('success', "User Successfully Added");
        } else if($user->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $user->message]);
        } else {
            return Redirect::back()->with('error', $user->message);
        }
    }

    public function deleteUser(Request $request) {

        $id = $request->get('id');
        $name = $request->get('name');
        $role = $request->get('role');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'admin/delete_user/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $response = json_decode($res->getBody());
        return $res->getBody();
        
    }

    // Edit User
    public function editUser(Request $request) 
    {
        // editUserName
        $data = array();
        if($request->euserrole == "1")
            $role = 1;
        else if($request->euserrole == "2")
            $role = 2;
        else if($request->euserrole == "3")
            $role = 3;
        else if($request->euserrole == "4")
            $role = 4;

        $data['user_role'] = $role;
        
        if($request->name != $request->editUserName) {
            $data['name'] = $request->name;
        }

        // $data['is_active'] = $request->status == 1 ? True : False;
        $token = $request->session()->get('token');
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config('app.base_url').'user/edit_user_details/'.$request->id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' .$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);
            
        if($result->message == 'Success') {
            return Redirect::back()->with('success', "User Updated");
        }  else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        } else {
            return Redirect::back()->with('failure', $result->message);
        }
    }

    // My profile
    public function getProfile(Request $request) {
        
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'user/my_profile', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $result = json_decode($res->getBody());
        // dd($result->data);
        if($result->message == 'Success') {
            $data = $result->data;
            session()->put('avatar', $data->avatart );
            return view('user.user_profile')->with(['data' => $result->data ? $result->data : '']);
            
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        }
            
    }

    // Edit My profile
    public function editProfile(Request $request) {
        
        $token = $request->session()->get('token');
        $data = array();

        $data['name'] = $request->name;
        if ($_FILES['avatar']['name'] !== "" ) {
            $tmpfile = $_FILES['avatar']['tmp_name'];
            $filename = basename($_FILES['avatar']['name']);
            $file = curl_file_create($tmpfile, $_FILES['avatar']['type'], $filename);
            $data['file'] = $file;
        } 
        if ($request->avatarFileName != '' && $_FILES['avatar']['name'] !== "") {
            $data['avatar_file_name'] = $request->avatarFileName;
        }
        if($request->deleteAvatar != '') {
            $data['avatar_file_name'] = $request->deleteAvatar;
        }
        // dd($data,$request->deleteAvatar);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config('app.base_url').'user/edit_user_details/'.$request->id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token '. $token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $result = json_decode($response);
        // dd($result);
        if($result->message == 'Success') {
            session()->put('name', $request->name );
            if($request->deleteAvatar != '') {
                session()->put('avatar', '' );
            }
            return redirect()->back();
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        }
            
    }

    // Dashboard users
    public function users(Request $request, $page) {
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'user/list_users?page='.$page, [
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
            $pagination = $result->pagination;
            $collection = collect($result->data);
            $paginator = new LengthAwarePaginator(
                $collection, 
                $pagination->total, 
                $pagination->per_page, 
                $pagination->current_page,
                ['path' => $request->url()]
            );

            $data = PostCollection::make($paginator);
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        }
            
        return view('user.user')->with(['data' => $data ? $data : '']);
    }

}
