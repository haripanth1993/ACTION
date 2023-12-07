<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use Config;
use GuzzleHttp\Client;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
		$name = $request->name; 
		$id = $request->customerid;
		$url = $request->url;
		$description = $request->description;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => Config::get('site_setting.site_url').'api/projects',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('name' => $name,'customer_id' => $id, 'description' => $description, 'project_url' => $url), 
			CURLOPT_HTTPHEADER => array('Accept: application/json'),));
		$addResponse = curl_exec($curl);
		curl_close($curl);
		$addResponse = json_decode($addResponse, true);
		return redirect('/projects/'.$id)->with('success', 'Project is added!');
	}
	
	public function show($id)
    {
		$client = new Client();
		$page = 1;
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}
		$pageLimit = 10000; 
		if(isset($_GET['per_page'])){
			$pageLimit = $_GET['per_page'];
		}
		$response = $client->get(Config::get('site_setting.site_url').'api/projects/'.$id.'?page='.$page.'&per_page='.$pageLimit);
		$body = $response->getBody()->getContents();
        $response = json_decode($body);
		$projects = $response->projects;
		$customerId = $response->customerId;
		$customerName = $response->customerName;
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.customer_id',$customerId)->get();
		$pageTitle = $customerName." / PROJECTS";
		return view('projects.index', compact('projects','customerId','customerName','allbuttons','pageTitle'));  
	}

    public function update(Request $request, $id)
    {
		$redirect = DB::table('projects')->where('id', $id)->first();
		$redirect = $redirect->customer_id;
		$name = $request->name; 
		$description = $request->description;
		$url = $request->url;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => Config::get('site_setting.site_url').'api/projects/update',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('name' => $name,'description' => $description, 'url' => $url, 'id'=>$id ),
			CURLOPT_HTTPHEADER => array('Accept: application/json'),));
		$addResponse = curl_exec($curl);
		curl_close($curl);
		$addResponse = json_decode($addResponse, true); 
		return redirect('/projects/'.$redirect)->with('success', 'Project is updated!');	
    }

    public function destroy($id)
    {
		$redirect = DB::table('projects')->where('id', $id)->first();
		$redirect = $redirect->customer_id;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => Config::get('site_setting.site_url').'api/projects/'.$id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			CURLOPT_HTTPHEADER => array(
				'Accept: application/json'),
			));
		$response = curl_exec($curl);
		curl_close($curl);
		return true;	
    }
	
	 public function widgets($id)
    {
		$client = new Client();
		$page = 1;
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}
		$pageLimit = 10; 
		if(isset($_GET['per_page'])){
			$pageLimit = $_GET['per_page'];
		}
		$response = $client->get(Config::get('site_setting.site_url').'api/buttons/'.$id.'?page='.$page.'&per_page='.$pageLimit);
		$body = $response->getBody()->getContents();
        $response = json_decode($body);
		$buttons = $response->buttons;
		$projectName = $response->projectName;
		$customerId = $response->customerId;
		$projectid = $response->projectid;
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.project_id',$projectid)->get();
		$customerName = DB::table('customers')->select('customer_name')->where('admin_customer_id',$customerId)->first();
		$customerName = $customerName->customer_name;
		$pageTitle = $customerName." / ".$projectName." / COUNTER";
		return view('projects.widgets', compact('buttons','customerId','projectName', 'projectid','allbuttons','pageTitle')); 
    }
	
	 public function widgetspost(Request $request)
	{ 
		$headline = $request->headline;
		$text = $request->text;
		$icon = $request->icon;
		$point = $request->point;
		$buttoncode = $request->buttoncode;
		if($icon == ""){
			$imageName = "";
		}
		else {
			$imageName = $buttoncode.time().'.'.$icon->extension();  
			$icon->move(public_path('icons'), $imageName);
		}
		$data = array('headline' => $headline, 'text' => $text,'point'=> $point,'buttoncode' => $buttoncode,'imageName' => $imageName);
		$code = '<div class="counter-widget"><br />';
		if($icon != ""){
			$code .= '<img src="https://actionbutton.voxara.net/public/icons/'.$imageName.'"><br />';
		}
		$code .= '<h2><span id="'.$buttoncode.$point.'" dataid="'.$buttoncode.'" datapoint="'.$point.'"></span></h2><br />';
		if($headline != ""){
			$code .= '<h3>'.$headline.'</h3><br />';
		}
		
		if($text != ""){
			$code .= '<p>'.$text.'</p><br />';  
		}
		$code .= '<link rel="stylesheet" href="https://new-actionbutton.voxara.net/public/css/counter-widget.css"><br />';
		$code .= '<script src="https://new-actionbutton.voxara.net/js/project-counter-widgets.js?'.$buttoncode.$point.'"></script><br />'; 		
		$code .= "</div>";
		$values = array('type' => $point,'project_id' => $buttoncode, 'widget_code'=> $code);
		DB::table('existing_counters')->insert($values);
		return redirect('/projects/widget/'.$buttoncode.'#widget')->with('getresponse', $data);
	}
	
		public function widgetsdata($id)
	{ 
		if(isset($_GET['datapoints'])){
			$datapoints = $_GET['datapoints'];
			$url = $_GET['CustomerUrl'];
			if($url == "admin")
			{	
				 $matchUrl = 1;
				
			} 
			else {
				$customer = DB::table("buttons")->select('customer_id')->where('project_id', $id)->first();
				
				$customerUrl = DB::table("customer_urls")->select('url_link')->where('customer_id', $customer->customer_id)->get();
				$matchUrl = 0;
				foreach($customerUrl as $links){
					if(str_contains($url,$links->url_link)){ 
					 $matchUrl = 1;
					}
				}
			}	
				
				if($matchUrl == 1){
					if($datapoints == "AverageDonation" || $datapoints == "DollarsRaised"  ){
						$buttonAmount = DB::table("buttons")->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->where('buttons.project_id', $id)->sum('button_meta_values.amount');
						$buttonCompleted = DB::table("buttons")->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->where('buttons.project_id', $id)->sum('button_meta_values.completed');
						if($datapoints == "DollarsRaised"){
							$buttonResult = $buttonAmount;
						}
						else {
							$buttonResult = $buttonAmount/$buttonCompleted;
						}
					}
					else{
						$buttonResult = DB::table("buttons")->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->where('buttons.project_id', $id)->sum("button_meta_values.".$datapoints);
					}	
					$id = $_GET['buttonId']; 
					$data = array("data" => $id , "number" => $buttonResult);
					echo $_GET['callback'] . '('.json_encode($data).')';
			}
				else{ 
					$id = $_GET['buttonId'];
					$data = array("data" => $id , "number" => "false");
					echo $_GET['callback'] . '('.json_encode($data).')';
				}	
			 
		}
	}
	
	 public function widgetslisting($id)
    {
		$client = new Client();
		$page = 1;
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}
		$pageLimit = 10; 
		if(isset($_GET['per_page'])){
			$pageLimit = $_GET['per_page'];
		}
		$response = $client->get(Config::get('site_setting.site_url').'api/buttons/'.$id.'?page='.$page.'&per_page='.$pageLimit);
		$body = $response->getBody()->getContents();
        $response = json_decode($body);
		$projects = $response->buttons;
		$customerName = $response->projectName;
		$customerId = $response->customerId;
		$projectid = $response->projectid;
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.project_id',$projectid)->get();
		$counters = DB::table('existing_counters')->where('project_id',$id)->orderBy('created_at', 'asc')->get();
		
		$projectName = DB::table('customers')->select('customer_name')->where('admin_customer_id',$customerId)->first();
		$projectName = $projectName->customer_name;
		$pageTitle = $projectName." / ".$customerName." / Existing COUNTERS";
		return view('projects.widgets-listing', compact('projects','customerId','projectid','customerName','allbuttons','counters','pageTitle'));     
    }
	
	public function widgetDelete($id)
    {
		$counters = DB::table('existing_counters')->where('existing_counters_id',$id)->delete();
		$redirect = "/projects/existing-counters/".$_GET['redirect'];
		return redirect($redirect)->with('success', 'Widget Code is deleted!');	
	}
}
