<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Resources\PostCollection;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Pagination\LengthAwarePaginator;
use Session;

class ProjectController extends Controller
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
        $res = $client->get(config('app.base_url').'project/list?page='. $page. '&per_page=6&time_zone='.$timezone, [
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
        return view('project.project')->with(['data' => $data ?? '',
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

    /***Project View */
    public function viewProject(Request $request,$id) 
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
        $res = $client->get(config('app.base_url').'project/get_single_project/'. $id .'?time_zone='.$timezone, [
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
            // dd($timezone);
            return view('project.view-project')->with(['data' => $data ?? '',
                                    'time_zone' => $timezone,
                                    'engineers' => $engineers ?? [],
                                    'crew' => $crew ?? [],
                                    'all_users' => $users ?? [] ,
                                    'timezone' => $settings_result->data ?? '' 
                                        ]);
            
        
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        } else {
            // Redirect::back();
            return redirect()->route('project', 1);
        }
    }

    public function generateVideoCallLink(Request $request)
    {
        $count = $request->count;
        $event_id = $request->eventId;
        $token = $request->session()->get('token');

        $res = new \GuzzleHttp\Client();
        $result = $res->get(config('app.base_url').'event/generate_video_call_protection_url', [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ "count"=>(int)$count] // or 'json' => [...]
        ]);
        
        $response = json_decode($result->getBody());

        if($response->status == 200) {
            return Redirect::back()->with('success', $response->message);
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }

    /* Add Project --- start */
    public function addProject(Request $request)
    {
        $name = $request->name;
        $client = $request->client;
        $jobNumber = $request->jobNumber;
        $users = $request->users ?? [];
        $recording = $request->recording == "on" ? true : false;
        $token = $request->session()->get('token');

        $res = new \GuzzleHttp\Client();
        $result = $res->post(config('app.base_url').'project/add', [
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
						"recording" => $recording] // or 'json' => [...]
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
    /* Add Project --- end */

    /* edit Project --- start */
    public function editProject(Request $request)
    {
        $id = $request->projectId;
        $name = $request->name;
		$recording = $request->recording == "on" ? true : false;
		
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
        $result = $res->put(config('app.base_url').'project/edit/'. $id, [
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
						"recording" => $recording] // or 'json' => [...]
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
    /* edit Project --- end */

    /* Delete Project --- start */
    public function deleteProject(Request $request) 
    {
        $id = $request->get('id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'project/delete/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        return $res->getBody();
    }
    /* Delete Project --- end */

    /* Set Prestart time --- start */
    public function preTime(Request $request)
    {
        $id = $request->get('id');
        $token = $request->session()->get('token');

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => config('app.base_url') ."user/project/pre_time/". $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PATCH",
        CURLOPT_POSTFIELDS =>  $request->get('data'),
        CURLOPT_HTTPHEADER => array(
            "x-requested-with: XMLHttpRequest",
            "Authorization: Token " . $token,
            "Content-Type: application/json"
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $result = $response;

    }
    /* Set Prestart time --- end */

    /* Create Session --- start */
    public function createEvent(Request $request)
    {
        $projectId = $request->projectId;
        $name = $request->name;
        $token = $request->session()->get('token');
        $rooms = array();
        $assignedUsers = json_decode($request->assignedUsers, true);

        for ($i = 0; $i < count($request->rooms); $i++) {
            $add_members = array();
            $rooms[$i]['name'] = $request->rooms[$i]['name'];
            // broadcasttoNDI
            if(array_key_exists('broadcasttoNDI',$request->rooms[$i])){
                $rooms[$i]['broadcast_ndi'] = $request->rooms[$i]['broadcasttoNDI'] == "on" ? true : false;
            } else {
                $rooms[$i]['broadcast_ndi'] = false;
            }
            
            if(array_key_exists('id',$request->rooms[$i])) { 
                $user_ids = $request->rooms[$i]['id'];
                foreach($user_ids as $id) {
                    foreach($assignedUsers as $user) {
                        $add_member = array();
                        if($user['user_id'] == $id) {
                        $add_member['proj_user_id'] =$user['id'];
                        $add_member['user_id'] = $id;
                            array_push($add_members,$add_member);

                        }

                    }
                }
                $rooms[$i]['add_members'] = $add_members;
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

        if($result_timezone->message == 'Success') 
        {
            $timezone = $result_timezone->data;
            $zone = $timezone->zone;
        } else if($result_timezone->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result_timezone->message]);
        }
        
        /* Timezone Api --- end*/

        $eventTimeInLocal = new \Carbon\Carbon($request->event_time, $zone);
        $event_time = $eventTimeInLocal->tz('utc')->format('Y-m-d\TH:i:s');
        
        
        // Create Event Api
        $res = new \GuzzleHttp\Client();
        $result = $res->post(config('app.base_url').'event/create/'.$projectId, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ 
                "name" => $name, 
                "event_time" => $event_time, 
                "add_rooms" => $rooms,
            ] 
        ]);
        
        $response = json_decode($result->getBody());
                // dd($response);
        if($response->message == "Success") {
            return Redirect::back()->with('success', "Session created Successfully");
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }
    /* Create Session --- end */

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
        
        foreach ($_FILES as $k => $_FILE) {
            
            $temp = array();
            $tmp_name = $_FILE['tmp_name'];
            $name = $_FILES['presenter']['name'];
            $type = $_FILES['presenter']['type'];
            
            foreach ($tmp_name as $key => $tmp) {
            
                $tmpfile = $tmp_name[$key]['avatar'];
                $filename = basename($name[$key]['avatar']);
                $t = $type[$key]['avatar'];
                $temp[$key] = array(["tmpfile"=>$tmpfile,"filename"=>$filename,"type"=>$t]);
            }
            
        }
        
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

                if(array_key_exists('avatar',$_presenter) && $_presenter['avatar'] != '') {
                    $f = $_presenter['avatar'];
                    $files['add_'.$key] = base64_encode(file_get_contents($f->getRealPath()));
                    
                    $image_type = $f->getMimeType();
                    
                    $user["image_type"] = $image_type;
                    $user["file"] = $files['add_'.$key];
                }
                if ($_presenter['avatar_file_name'] != '' && 
                    array_key_exists('avatar',$_presenter) && 
                    $_presenter['avatar'] !== "") {
                    
                    $user["avatar_file_name"] = $_presenter['avatar_file_name'];
                }
                if($_presenter['remove_avatar_file_name'] != '') {
                    $user["avatar_file_name"] = $_presenter['avatar_file_name'];
                }
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
                        $_new_presenter['name'] != $_filtered['name'] || 
                        (array_key_exists('avatar',$_new_presenter) && 
                            $_new_presenter['avatar'] != null) ||
                            (!array_key_exists('avatar',$_new_presenter) && 
                            array_key_exists('avatar_file_name',$_new_presenter) 
                            && $_new_presenter['avatar_file_name'] != null) ) {
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
                'edit_presenters' => $edit_presenter,
                
            );
        //  dd($d);   
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('app.base_url').'event/create_update_presenter/'.$eventId,
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

    public function createUpdatePresenter2(Request $request) {
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

        if ($new_presenters) {
            foreach ($new_presenters as $_presenter) {
                $user = array();
                $str = $_presenter['uuid'];
				$str = str_replace("?","&",$str);
                parse_str($str, $uuid);

                array_push($user,["id" => $_presenter['id'],
                                    "name" => $_presenter['name'],
                                    "email" => $_presenter['email'],
                                    "external_user_id" => $uuid['ExternalUserId'],
                                    // "eventId" => (int)$uuid['EventId'],
                                    "password" => $_presenter['password']]);
                
                array_push($presenter1,$user);
            }
        }
    

        foreach ($presenter1 as $p1) {
            foreach ($p1 as $present) {
                array_push($presenter, $present);
                if ($present['id'] !== null)
                    array_push($new_presenter_ids,(int)$present['id']);
            }
        }
        // dd($presenter);

        foreach ($old_presenters as $old) {
            array_push($old_presenter_ids,$old['id']);
        }

        foreach ($presenter as $_new_presenter) {
            if ($_new_presenter['id'] !== null) {
                if (in_array($_new_presenter['id'], $old_presenter_ids)) {
                    /**Edit Presenter */
                    $filtered = $old_collection->where('id',$_new_presenter['id'])->all();
                    // dd();
                    foreach ($filtered as $_filtered) {
                        if($_new_presenter['email'] != $_filtered['email'] || 
                        $_new_presenter['name'] != $_filtered['name']) {
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
        // dd($presenter,$old_presenters,$old_presenter_ids,$remove_presenters);


        foreach ($old_presenters as $_old_presenter) {
            if (!in_array($_old_presenter['id'], $new_presenter_ids)) {
                array_push($remove_presenters,$_old_presenter['id']);
            }
        }
        /** start ------------ Create/Update presenter api */
        $res = new \GuzzleHttp\Client();
        $result = $res->post(config('app.base_url').'event/create_update_presenter/'.$eventId, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                // 'content-type'  => 'application/json'
            ],
            'json' => [ 
                "add_presenters" => $add_presenter, 
                "remove_presenters" => $remove_presenters, 
                "edit_presenters" => $edit_presenter, 
               
            ] 
        ]);

        $response = json_decode($result->getBody());
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

    /**Add presenters ------ end*/
    
    /* Edit Events --- start */
    public function editEvent(Request $request)
    {
        $event_id = $request->eventId;
        $token = $request->session()->get('token');
        $new_rooms = $request->rooms;
        $old_rooms = json_decode($request->room, true);
        $assignedUsers = json_decode($request->assignedUsers, true);
        $name = $request->name;
        $old_collection = collect($old_rooms);

        $new_room_ids = array();
        $old_room_ids = array();
        $edit_rooms = array();
        $remove_rooms = array();
        $add_rooms = array();

        /** New room Ids */
        foreach ($new_rooms as $room) {
            if($room['roomId'] != null) {
                array_push($new_room_ids,(int)$room['roomId']);
            }
        }

        /** Old room Ids */
        foreach ($old_rooms as $room) {
            array_push($old_room_ids,$room['id']);
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

        /** Check Every New rooms */
        foreach ($new_rooms as $room) {
            $edit_room = array();
            $add_members = array();
            $remove_members = array();
            $old_room_members = array();
            $new_room_members = array();

            /** Collect Old rooms and members in every room */
            $old_assigned_room = $old_collection->whereIn('id', (int)$room['roomId'])
                                   ->all();

            foreach ($old_assigned_room as $r) {
                foreach ($r['members'] as $m) {
                    array_push($old_room_members,$m['user_id']);
                }
            }
            /** */
            
            /**Check for the room Ids */
            if (in_array( (int)$room['roomId'],$old_room_ids)) {
                if(array_key_exists('broadcasttoNDI',$room)){
                    $broadcast_ndi = $room['broadcasttoNDI'] == "on" ? true : false;
                } else {
                    $broadcast_ndi = false;
                }

                if(array_key_exists('id',$room)) {
                    foreach ($room['id'] as $id) {
                        
                        array_push($new_room_members,(int)$id);

                        if (!in_array ((int)$id,$old_room_members)) {
                            foreach($assignedUsers as $user) {
                                $add_member = array();

                                if($user['user_id'] == (int)$id) {
                                $add_member['proj_user_id'] =$user['id'];
                                $add_member['user_id'] = $id;
                                    array_push($add_members,$add_member);

                                }

                            }
                        }
                    }
                }

                foreach ($old_room_members as $old_room_member) {
                    if (!in_array ($old_room_member,$new_room_members)) {
                        array_push($remove_members,$old_room_member);
                    }
                }

                array_push($edit_room,["id" => (int)$room['roomId'],
                             "name" => $room['name'],
                             "broadcast_ndi" => $broadcast_ndi,
                             "add_members" => $add_members,
                             "remove_members" => $remove_members
                            ]);
                array_push($edit_rooms,$edit_room);
                
            } else if ((int)$room['roomId'] == null) {
                //Add Rooms

                $add_members = array();
                $user_ids = array_key_exists('id',$room) ? $room['id'] : array();
                    
                $add_room['name'] = $room['name'];
                    
                if (array_key_exists('broadcasttoNDI',$room)) {
                    $add_room['broadcast_ndi'] = $room['broadcasttoNDI'] == "on" ? true : false;
                } else {
                    $add_room['broadcast_ndi'] = false;
                }
                
                foreach ($user_ids as $id) {
                    foreach($assignedUsers as $user) {
                            
                        $add_member = array();
                        if($user['user_id'] == $id) {
                            $add_member['proj_user_id'] =$user['id'];
                            $add_member['user_id'] = $id;
                            array_push($add_members,$add_member);

                        }

                    }
                }
                $add_room['add_members'] = $add_members;
                    
                array_push($add_rooms,$add_room);

            } 
        }

        /** Check Every Old rooms */
        foreach ($old_rooms as $old_room) {
            if (!in_array ($old_room['id'], $new_room_ids)) {
                array_push($remove_rooms,$old_room['id']);
            }
        }

        $edit_rooms1 = array();
        foreach ($edit_rooms as $edit_room) {
            foreach ($edit_room as $edit) {
                array_push($edit_rooms1, $edit);
            }
        }
        // dd($remove_rooms);

        
        $eventTimeInLocal = new \Carbon\Carbon($request->event_time, $zone);
        $event_time = $eventTimeInLocal->tz('utc')->format('Y-m-d\TH:i:s');

        // dd($old_rooms,$new_rooms,$add_rooms,$edit_rooms1,$remove_rooms);
        // Edit Event Api
        $res = new \GuzzleHttp\Client();
        $result = $res->put(config('app.base_url').'event/edit/'.$event_id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
            'json' => [ 
                "name" => $name, 
                "event_time" => $event_time, 
                "remove_rooms" => $remove_rooms,
                "add_rooms" => $add_rooms,
                "edit_rooms" => $edit_rooms1
            ] 
        ]);
        
        $response = json_decode($result->getBody());
                
        if ($response->message == "Success") {
            return Redirect::back()->with('success', "Event Updated Successfully");
        } else if($response->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $response->message]);
        } else {
            return Redirect::back()->with('failure', $response->message);
        }
    }
    /* Edit Events --- end */

    /* delete Events --- start */
    public function deleteEvent(Request $request) 
    {
        $id = $request->get('id');
        $projectId = $request->get('projectId');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'event/delete/'.$id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
         return $res->getBody();
    }
    /* delete Events --- end */
    
    /* Terminate session --- start */
    public function terminateSession(Request $request) 
    {
        $id = $request->get('id');
        $state = $request->get('state');
        // $projectId = $request->get('projectId');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        
        // $res = $client->get(config('app.base_url').'presenter/terminate_session/'.$id, [
        $res = $client->put(config('app.base_url').'event/session_management/'.$id, [
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
        // return $token = $request->session()->get('token');
        // return config('app.base_url').'project/assigned_user_del/'.$projectId.'?user_id='.$id;
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
        $res = $client->post(config('app.base_url').'event/presenter_email_sent/'.$id, [
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

    public function multimediaUpload(Request $request) {
        $type = $request->get('type');
        $projectId = $request->get('projectId');

        if($type == 'image') {
            $tmpfile = $_FILES['image']['tmp_name'];
            $filename = basename($_FILES['image']['name']);
            $file = curl_file_create($tmpfile, $_FILES['image']['type'], $filename);
            $uploadedImage = $request->get('uploadedImage');
            if($uploadedImage == 0) {
                $url = config('app.base_url').'project/multimedia_uploader/'.$projectId.'?type_='.$type;
            } else {
                $url = config('app.base_url').'project/multimedia_uploader/'.$projectId.'?type_='.$type.'?id_='.$uploadedImage;
            }
        } else {
            $tmpfile = $_FILES['audio']['tmp_name'];
            $filename = basename($_FILES['audio']['name']);
            $file = curl_file_create($tmpfile, $_FILES['audio']['type'], $filename);
            $uploadedAudio = $request->get('uploadedAudio');
            if($uploadedAudio == 0) {
                $url = config('app.base_url').'project/multimedia_uploader/'.$projectId.'?type_='.$type;
            } else {
                $url = config('app.base_url').'project/multimedia_uploader/'.$projectId.'?type_='.$type.'?id_='.$uploadedAudio;
            }
        }
        
        // dd($file);

        $token = $request->session()->get('token');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('file'=> $file),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token ' .$token, 
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);
        // dd($result);
        if($result->status == 200) {
            return Redirect::back()->with('success', $result->message);
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        } else {
            return Redirect::back()->with(['error_msg' => $result->message]);
        }
    }

    // public function recordings(Request $request , $page)
    // {
    //     $per_page = 10;
    //     $token = $request->session()->get('token');

    //     /* Get timezone ---start */
    //     $timezone_client = new \GuzzleHttp\Client();
    //     $timezone_res = $timezone_client->get(config('app.base_url').'settings/time_zone', [
    //         'headers' => 
    //         [
    //             'cache-control' => 'no-cache', 
    //             'authorization' => 'Token ' .$token, 
    //             'content-type'  => 'application/json'
    //         ],
    //     ]);
        
    //     $timezone_result = json_decode($timezone_res->getBody());
    //     if($timezone_result->message == 'Success') 
    //     {
    //         $timezone = $timezone_result->data->zone;
    //     } else if($timezone_result->status == 401) { 
    //         return redirect()->to('/')->withErrors(['error_msg' => $timezone_result->message]);
    //     }
    //     /* Get timezone ---end */

    //     $client = new \GuzzleHttp\Client();
    //     $res = $client->get(config('app.base_url').'recorder/complete_session?page='.$page.
    //         '&per_page='.$per_page.'&time_zone='.$timezone, [
    //         'headers' => 
    //         [
    //             'cache-control' => 'no-cache', 
    //             'authorization' => 'Token ' .$token, 
    //             'content-type'  => 'application/json'
    //         ],
    //     ]);

    //     $result = json_decode($res->getBody());
    //     // dd($result);

    //     if($result->message == 'Success') 
    //     {
    //         $pagination = $result->pagination ?? '';
    //         $collection = collect($result->data);
    //         $paginator = new LengthAwarePaginator(
    //             $collection, 
    //             $pagination->total, 
    //             $pagination->per_page, 
    //             $pagination->current_page,
    //             ['path' => $request->url()]
    //         );
            
    //         $data = PostCollection::make($paginator);

    //     } else if($result->status == 401) { 
    //         return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
    //     }

    //     return view('project.recording')->with(['data' => $data ?? '']);
    // }

    /**Delete Image/Audio */
    public function multimediaDelete(Request $request)
    {
        $id = $request->get('id');
        $project_id = $request->get('project_id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->delete(config('app.base_url').'project/multimedia/'.$id.'?project_id='.$project_id, [
            'headers' => 
            [
                'cache-control' => 'no-cache', 
                'authorization' => 'Token ' .$token, 
                'content-type'  => 'application/json'
            ],
        ]);
        return $res->getBody();
    }

    /**Get event for Edit */
    public function getEventForEdit(Request $request)
    {
        $id = $request->get('id');
        $project_id = $request->get('project_id');
        $timezone = $request->get('time_zone');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'event/get_for_edit/'.$project_id .
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

    /**Get presenter for Edit */
    public function getPresenterForEdit(Request $request) 
    {
        $id = $request->get('id');
        $project_id = $request->get('project_id');
        $token = $request->session()->get('token');
        $client = new \GuzzleHttp\Client();
        $res = $client->get(config('app.base_url').'event/presenter_data_for_edit/'.$project_id .
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
        $res = $client->get(config('app.base_url').'project/event/recordings_dashboard/'.$id.
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
            $data =$result->data;
            // dd($data);
            return view('project.recording')->with(['data' => $data ?? '']);
        } else if($result->status == 401) { 
            return redirect()->to('/')->withErrors(['error_msg' => $result->message]);
        } else {
            return redirect()->back()->withErrors(['error_msg' => $result->message]);
        }

        
    }
}
