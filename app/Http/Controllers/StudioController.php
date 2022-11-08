<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Pagination\LengthAwarePaginator;
use Session;

class StudioController extends Controller
{
    public function index(Request $request, $page) {
        $token = $request->session()->get('token');

        /* Get timezone ---start */
        $timezone_client = new \GuzzleHttp\Client();
        $timezone_res = $timezone_client->get(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $timezone_result = json_decode($timezone_res->getBody());
        if($timezone_result->message == 'Success') 
        {
            $timezone = $timezone_result->data->zone;
        } else if($timezone_result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $timezone_result->message]);
        }
        /* Get timezone ---end */

        /* Get Project List ---start */
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'studio/list?page='. $page. '&per_page=6&time_zone='.$timezone, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);

        $result = json_decode($res->getBody());
		//  dd($result);
        if($result->message == 'Success') 
        {
            $pagination = $result->pagination ?? '';
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
        /* Get Project List ---end */

        /*Get User List -- start */
        $client_user = new \GuzzleHttp\Client();
        $res_user = $client_user->get(config('app.base_url').'user/list_users', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $result_user = json_decode($res_user->getBody());

        if($result_user->message == 'Success') 
        {
            $users= $result_user->data;
        }
        
        // dd($data);
        /*Get User List -- end */
        return view('studio.studio')->with(['data' => $data ?? '',
                                                'users' => $users ?? '']);
    }

    public function paginate_with_custom_path($data) {
        $links = $data->links();
        $patterns = '#\?page=#';
        $replacements = '/';
        $one = preg_replace($patterns, $replacements, $links);
        $pattern2 = '#/([1-9]+[0-9]*)/([1-9]+[0-9]*)#';
        $replacements2 = '/$2';
        $paginate_links = preg_replace($pattern2, $replacements2, $one);
        return $paginate_links;
    }

    /* Add Studio --- start */
    // STUDIO_RECORDING_TYPE = {1: "Both Audio & Video", 2: "Audio Only", 3: "Video Only"}
    public function add(Request $request)
    {
        $name = $request->name;
        $client = $request->client;
        $jobNumber = $request->jobNumber;
        $users = $request->users ?? [];
        
		//dd($request->recording);
        $token = $request->session()->get('token');

        $res = new \GuzzleHttp\Client();
        $result = $res->post(config('app.base_url').'studio/add', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ "name"=> $name, 
                        "client_name" => $client,
                        "job_number" => $jobNumber,
                        "add_users" => $users,
						"recording" => $request->recording] // or 'json' => [...]
        ]);
        
        $response = json_decode($result->getBody());
                
        if($response->message == "Success") {
            return Redirect::back()->with('success', "Project Successfully Added");
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }
    /* Add Studio --- end */

    /* edit Studio --- start */
    public function edit(Request $request)
    {
        $id = $request->projectId;
        $name = $request->name;
        
        $client = $request->client;
        $jobNumber = $request->jobNumber;
        $users = $request->users ?? [];
        $remove = [];
        $assignedMembers = json_decode($request->assignedMembers, true);

        foreach($assignedMembers as $member) {
            if(!in_array($member,$users)){
                array_push($remove,$member);
            }
        }

        $token = $request->session()->get('token');
        $res = new \GuzzleHttp\Client();
        $result = $res->put(config('app.base_url').'studio/edit/'. $id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => ["name"=> $name, 
                        "client_name" => $client,
                        "job_number" => $jobNumber,
                        "add_users" => $users,
                        "remove_users" => $remove,
                        "recording" => $request->recording] // or 'json' => [...]
        ]);
        
        $response = json_decode($result->getBody());
            
        if($response->message == "Success") {
            return Redirect::back()->with('success', "Project Updated");
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }
    /* edit Studio --- end */

    /* Delete Studio --- start */
    public function delete(Request $request) 
    {
        $id = $request->get('id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'studio/delete/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        return $res->getBody();
    }
    /* Delete Studio --- end */

    /***Studio View */
    public function view(Request $request,$id) 
    {
        $token = $request->session()->get('token');
        
        /* Get tmezone ---start */ 
        $settings_client = new \GuzzleHttp\Client();
        $settings_res = $settings_client->get(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
            
        $settings_result = json_decode($settings_res->getBody());
        
        if($settings_result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $settings_result->message]);
        }
        $timezone = $settings_result->data->zone;
        /* Get tmezone ---end */

        /* Get single project details ---start */
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'studio/get_single_studio/'. $id .'?time_zone='.$timezone, [
            // $res = $client->get(config('app.base_url').'siji/project/get_single_project/'. $id .'?time_zone='.$timezone, [
            'headers' => 
                [
                    'cache-control' => 'no-cache', 
                    'authorization' => 'Token ' .$token, 
                    'content-type'  => 'application/json'
                ],
            ]);

        $result = json_decode($res->getBody());
        // dd($result);

        /*Get User List -- start */
        $client_user = new \GuzzleHttp\Client();
        $res_user = $client_user->get(config('app.base_url').'admin/list_users', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $result_user = json_decode($res_user->getBody());
        $crew = array();
        $engineers = array();
        if($result_user->message == 'Success') 
        {
            $users= $result_user->data;
            $collection = collect($users);
            $filtered_eng = $collection->where('user_role', 3);

            $engineers = $filtered_eng->all();
            $filtered_crew = $collection->where('user_role', 4);

            $crew = $filtered_crew->all();
        }
        /*Get User List -- end */

        
        if($result->message == "Success") {
            $data = $result->data ?? '';
            // dd($data);

            // /studio/storage_cred/<int:studio_id>
            $storage_cred_client = new \GuzzleHttp\Client();
            $storage_cred_res = $storage_cred_client->get(config('app.base_url').'studio/storage_cred/'. $id , [
                'headers' => 
                    [
                        'cache-control' => 'no-cache', 
                        'authorization' => 'Token ' .$token, 
                        'content-type'  => 'application/json'
                    ],
                ]);
    
            $storage_cred_result = json_decode($storage_cred_res->getBody());
            // dd($storage_cred_result);

            return view('studio.view-studio')->with(['data' => $data ?? '',
                                    'timezone' => $timezone,
                                    'engineers' => $engineers ?? [],
                                    'crew' => $crew ?? [],
                                    'all_users' => $users ?? [] ,
                                    'timezone' => $settings_result->data ?? '',
                                    'storage_cred' => $storage_cred_result->data ?? ''
                                        ]);
            
        
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        } else {
            // Redirect::back();
            return redirect()->route('project', 1);
        }
    }

    /* Create Session --- start */
    public function createSession(Request $request)
    {
        $projectId = $request->projectId;
        $name = $request->name;
        $token = $request->session()->get('token');
        $add_users = array();
        $assignedUsers = json_decode($request->assignedUsers, true);
        $users = $request->users;
        if ($users) {
            foreach($users as $id) {
                foreach($assignedUsers as $assignedUser) {
                    $add_user = array();
                    if($assignedUser['user_id'] == $id) {
                        $add_user['studio_user_id'] = $assignedUser['id'];
                        $add_user['user_id'] = (int)$id;
                        array_push($add_users,$add_user);
    
                    }
    
                }
            }
        }
        // dd($add_users);
 
        /* Timezone Api --- start */
        $timezone_client = new \GuzzleHttp\Client();
         
        $res_timezone = $timezone_client->get(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
 
        $result_timezone = json_decode($res_timezone->getBody());
 
        if($result_timezone->message == 'Success') 
        {
            $timezone = $result_timezone->data;
            $zone = $timezone->zone;
        } else if($result_timezone->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result_timezone->message]);
        }
         
        /* Timezone Api --- end*/
 
        $eventTimeInLocal = new \Carbon\Carbon($request->session_time, $zone);
        $session_time = $eventTimeInLocal->tz('utc')->format('Y-m-d\TH:i:s');
         
         
        // Create Event Api
        $res = new \GuzzleHttp\Client();
        $result = $res->post(config('app.base_url').'session/create/'.$projectId, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ 
                "name" => $name, 
                "session_time" => $session_time, 
                "add_members" => $add_users,
            ] 
        ]);
         
         $response = json_decode($result->getBody());
                //  dd($response);
         if($response->message == "Success") {
             return Redirect::back()->with('success', "Session created Successfully");
         } else if($response->status == 401) { 
             return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
         } else {
             return Redirect::back()->with('failure', $response->message);
         }
    }
    /* Create Session --- end */


    /**Get session for Edit */
    public function getSessionForEdit(Request $request)
    {
        $id = $request->get('id');
        $project_id = $request->get('project_id');
        $timezone = $request->get('time_zone');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'session/get_for_edit/'.$project_id .
                                        '/'.$id.'?time_zone='.$timezone, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        return $res->getBody();
    }
    
    /* Edit Session --- start */
    public function editSession(Request $request)
    {
        $event_id = $request->eventId;
        $token = $request->session()->get('token');
        $assignedUsers = json_decode($request->assignedUsers, true);
        $members = json_decode($request->members,true);
        $name = $request->name;
        $users = $request->users ?  $request->users : [];
        $assignedUserIds = array();

        foreach ($members as $member) {
            array_push($assignedUserIds,$member['user_id']);
        }
        $remove_users = array();
        $add_users = array();
        if(count($members) > count($users)) {
            foreach($members as $member) {
                if(!in_array($member['user_id'],$users)) {
                    array_push($remove_users,$member['user_id']);
                }
            }
        }
        if(count($users) > count($members)) {
            foreach($users as $user) {
                if(!in_array($user,$assignedUserIds)) {
                    foreach($assignedUsers as $assignedUser) {
                        $add_user = array();
                        if($assignedUser['user_id'] == $user) {
                            $add_user['studio_user_id'] = $assignedUser['id'];
                            $add_user['user_id'] = (int)$user;
                            array_push($add_users,$add_user);
        
                        }
        
                    }

                }
            }
        }

        /* Timezone Api --- start */
        $timezone_client = new \GuzzleHttp\Client();
        
        $res_timezone = $timezone_client->get(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);

        $result_timezone = json_decode($res_timezone->getBody());

        if ($result_timezone->message == 'Success') 
        {
            $timezone = $result_timezone->data;
            $zone = $timezone->zone;
        } else if($result_timezone->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result_timezone->message]);
        }
        /* Timezone Api --- end*/

        

        
        $eventTimeInLocal = new \Carbon\Carbon($request->session_time, $zone);
        $session_time = $eventTimeInLocal->tz('utc')->format('Y-m-d\TH:i:s');

        // dd($old_rooms,$new_rooms,$add_rooms,$edit_rooms1,$remove_rooms);
        // Edit Event Api
        $res = new \GuzzleHttp\Client();
        $result = $res->put(config('app.base_url').'session/edit/'.$event_id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ 
                "name" => $name, 
                "session_time" => $session_time, 
                "remove_members" => $remove_users,
                "add_members" => $add_users,
            ] 
        ]);
        
        $response = json_decode($result->getBody());
                // dd($response);
        if ($response->message == "Success") {
            return Redirect::back()->with('success', "Session Updated Successfully");
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }
    /* Edit Session --- end */

    /* delete Session --- start */
    public function deleteSession(Request $request) 
    {
        $id = $request->get('id');
        $projectId = $request->get('projectId');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'session/delete/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
         return $res->getBody();
    }
    /* delete Session --- end */

    /**Get presenter for Edit */
    public function getPresenterForEdit(Request $request) 
    {
        $id = $request->get('id');
        $project_id = $request->get('project_id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'session/presenter_data_for_edit/'.$project_id .
                                        '/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        return $res->getBody();
    }

    /**Add presenters ------ start*/
    public function createUpdatePresenter(Request $request) {
        $token = $request->session()->get('token');
        $eventId = $request->eventId;
        $old_presenters = json_decode($request->presenters, true);
        $new_presenters = $request->presenter;
        $old_collection = collect($old_presenters);

        $add_presenter = array();
        $edit_presenter = array();
        $presenter = array();
        $presenter1 = array();
        $remove_presenters = array();
        $new_presenter_ids = array();
        $old_presenter_ids = array();
        $files = array();
        
        // foreach ($_FILES as $k => $_FILE) {
            
        //     $temp = array();
        //     $tmp_name = $_FILE['tmp_name'];
        //     $name = $_FILES['presenter']['name'];
        //     $type = $_FILES['presenter']['type'];
            
        //     foreach ($tmp_name as $key => $tmp) {
            
        //         $tmpfile = $tmp_name[$key]['avatar'];
        //         $filename = basename($name[$key]['avatar']);
        //         $t = $type[$key]['avatar'];
        //         $temp[$key] = array(["tmpfile"=>$tmpfile,"filename"=>$filename,"type"=>$t]);
        //     }
            
        // }
        
        if ($new_presenters) {
            foreach ($new_presenters as $key => $_presenter) {
                $user = array();
                $str = $_presenter['uuid'];
				$str = str_replace("?","&",$str);
                parse_str($str, $uuid);

                $user["id"] =  $_presenter['id'];
                $user["name"] =  $_presenter['name'];
                $user["email"] =  $_presenter['email'];
                $user["external_user_id"] = $uuid['ExternalUserId'];
                $user["password"] =  $_presenter['password'];

                // if(array_key_exists('avatar',$_presenter) && $_presenter['avatar'] != '') {
                //     $f = $_presenter['avatar'];
                //     $files['add_'.$key] = base64_encode(file_get_contents($f->getRealPath()));
                    
                //     $image_type = $f->getMimeType();
                    
                //     $user["image_type"] = $image_type;
                //     $user["file"] = $files['add_'.$key];
                // }
                // if ($_presenter['avatar_file_name'] != '' && 
                //     array_key_exists('avatar',$_presenter) && 
                //     $_presenter['avatar'] !== "") {
                    
                //     $user["avatar_file_name"] = $_presenter['avatar_file_name'];
                // }
                // if($_presenter['remove_avatar_file_name'] != '') {
                //     $user["avatar_file_name"] = $_presenter['avatar_file_name'];
                // }
                array_push($presenter1,$user);
            }
        }

        foreach ($presenter1 as $present) {
            array_push($presenter, $present);
            if (array_key_exists('id',$present) && $present['id'] !== null)
                array_push($new_presenter_ids,(int)$present['id']);
        }
        // dd($presenter);

        if($old_presenters) {
            foreach ($old_presenters as $old) {
                array_push($old_presenter_ids,$old['id']);
            }
        }

        foreach ($presenter as $_new_presenter) {
            if ($_new_presenter['id'] !== null) {
                if (in_array($_new_presenter['id'], $old_presenter_ids)) {
                    /**Edit Presenter */
                    $filtered = $old_collection->where('id',$_new_presenter['id'])->all();
                    // dd();
                    foreach ($filtered as $_filtered) {
                        if($_new_presenter['email'] != $_filtered['email'] || 
                        $_new_presenter['name'] != $_filtered['name']  ) {
                            array_push($remove_presenters,(int)$_new_presenter['id']);
                            unset($_new_presenter['id']);
                            array_push($add_presenter, $_new_presenter);
                        }
                    }

                }
            } else {
                /**add Presenter */
                unset($_new_presenter['id']);
                array_push($add_presenter, $_new_presenter);
            }
        }

        if($old_presenters) {
            foreach ($old_presenters as $_old_presenter) {
                if (!in_array($_old_presenter['id'], $new_presenter_ids)) {
                    array_push($remove_presenters,$_old_presenter['id']);
                }
            }
        }
        $d = array(
            'add_presenters' => $add_presenter,
                'remove_presenters' => $remove_presenters,
                // 'edit_presenters' => $edit_presenter,
                
            );
        //  dd($d);   
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('app.base_url').'session/create_update_presenter/'.$eventId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($d),
            CURLOPT_HTTPHEADER => array(
              'Authorization: Token '.$token,
              'Content-Type: application/json'
            ),
        ));


        $res = curl_exec($curl);

        curl_close($curl);
        
        $response = json_decode($res);

        // dd($response);
        if($response->message == "Success") {
           
            return Redirect::back()->with('success', "Presenters Updated");
            
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
        /** end ------------ Create/Update presenter api */

    }

    // public function generateVideoCallLink(Request $request)
    // {
    //     $count = $request->count;
    //     $event_id = $request->eventId;
    //     $token = $request->session()->get('token');

    //     $res = new \GuzzleHttp\Client();
    //     $result = $res->get(config('app.base_url').'event/generate_video_call_protection_url', [
    //         'headers' => 
    //         [
    //             'cache-control' => 'no-cache', 
    //             'authorization' => 'Token ' .$token, 
    //             'content-type'  => 'application/json'
    //         ],
    //         'json' => [ "count"=>(int)$count] // or 'json' => [...]
    //     ]);
        
    //     $response = json_decode($result->getBody());

    //     if($response->status == 200) {
    //         return Redirect::back()->with('success', $response->message);
    //     } else if($response->status == 401) { 
    //         return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
    //     } else {
    //         return Redirect::back()->with('failure', $response->message);
    //     }
    // }

    

    

    

    
    
    /* Terminate session --- start */
    public function terminateSession(Request $request) 
    {
        $id = $request->get('id');
        $state = $request->get('state');
        // $projectId = $request->get('projectId');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        
        // $res = $client->get(config('app.base_url').'presenter/terminate_session/'.$id, [
        $res = $client->put(config('app.base_url').'session/session_management/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ 
                "state" => $state
            ]
        ]);
         return $res->getBody();
    }
    /* Terminate session --- end */


    /* Assign user for project --- start */
    public function assignUser(Request $request) {
        $userArray = array();
        $id = $request->projectId;
        $users = $request->users;
        foreach($users as $user) {
            $user_decode = json_decode($user);
            array_push($userArray, $user_decode);
        }

        $token = $request->session()->get('token');
        $res = new \GuzzleHttp\Client();
        $result = $res->put(config('app.base_url').'user/project/assign_users_to_project/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ "add_users"=> $userArray ] // or 'json' => [...]
        ]);

        $response = json_decode($result->getBody());
                
        if($response->message == "Success") {
            return back();
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }
    /* Assign user for project --- end */

    /* Remove user from project --- start */
    public function removeProjectUser(Request $request) 
    {
        $id = $request->get('id');
        $projectId = $request->get('projectId');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'project/assigned_user_del/'.$projectId.'?user_id='.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        return $res->getBody();
    }
    /* Remove user from project --- end */

    public function presenterEmailSent(Request $request) 
    {
        $id = $request->get('id');
        $presenter = $request->get('presenter');
        // dd($presenter);
        
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->post(config('app.base_url').'session/presenter_email_sent/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ 
                // "presenters" => $presenter,                
            ] 
        ]);
        
        return $res->getBody();
    }

    /**
     * View session Recordings
    */
    public function recordings(Request $request ,$id, $session_id)
    {
        $per_page = 10;
        $token = $request->session()->get('token');

        /* Get timezone ---start */
        $timezone_client = new \GuzzleHttp\Client();
        $timezone_res = $timezone_client->get(config('app.base_url').'settings/time_zone', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        
        $timezone_result = json_decode($timezone_res->getBody());
        if($timezone_result->message == 'Success') 
        {
            $timezone = $timezone_result->data->zone;
        } else if($timezone_result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $timezone_result->message]);
        }
        /* Get timezone ---end */

        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'studio/sesison/recordings_dashboard/'.$id.
                '/'.$session_id.'?time_zone='.$timezone, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);

        $result = json_decode($res->getBody());
        // dd($result);

        if($result->message == 'Success') 
        {
            // $pagination = $result->pagination ?? '';
            // $collection = collect($result->data);
            // $paginator = new LengthAwarePaginator(
            //     $collection, 
            //     $pagination->total, 
            //     $pagination->per_page, 
            //     $pagination->current_page,
            //     ['path' => $request->url()]
            // );
            
            // $data = PostCollection::make($paginator);
            $data =$result->data;
            return view('studio.recording')->with(['data' => $data ?? '']);
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        } else {
            return redirect()->back()->withErrors(['error_msg' => $result->message]);
        }

        
    }

    /**Delete Image/Audio */
    public function multimediaDelete(Request $request)
    {
        $id = $request->get('id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'project/multimedia/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        return $res->getBody();
    }

    /* Terminate session --- start */
    public function recordSession(Request $request) 
    {
        $id = $request->get('id');
        $studio_id = $request->get('studio_id');
        $state = $request->get('state');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        
        $res = $client->post(config('app.base_url').'session/recording/'.$studio_id.'/'.$id.'?state='.$state, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            
        ]);
        return $res->getBody();
    }
    /* Terminate session --- end */
    
    public function goLink(Request $request)
    {
        $id = $request->get('id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        
        $res = $client->get(config('app.base_url').'session/meeting_redirect/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json',
                 'Access-Control-Allow-Origin'=> '*',
            ],
            
        ]);
        return $res->getBody();
    }

    public function studioSessions(Request $request)
    {
        return "test";
        $token = $request->session()->get('token');
        return $id = $request->get('id');
       
    }

    public function storageCredentials(Request $request) {
        // /studio/storage_cred/<int:studio_id>
        $id = $request->get('id');
        $storage_source = $request->get('storage_source');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        
        $res = $client->put(config('app.base_url').'studio/storage_cred/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json',
            ],
            'json' => ["storage_source" => $storage_source],
            
        ]);
        return $res->getBody();
    }
}
