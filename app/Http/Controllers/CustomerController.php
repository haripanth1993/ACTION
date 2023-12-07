<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Customer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use GuzzleHttp\Client;
use Config;
class CustomerController extends Controller
{
   
    // Start all customers list
    public function index()
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
		$response = $client->get(Config::get('site_setting.site_url').'api/customers?page='.$page.'&per_page='.$pageLimit);
		$body = $response->getBody()->getContents();
        $customers = json_decode($body);
        $allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->get();
		$pageTitle = "ALL CUSTOMERS";
		return view('customers.index', compact('customers','allbuttons','pageTitle'));
    }
    // End all customers list
  
	// Start create new customer
    public function store(Request $request)
    {
		$name = $request->name;
		$url = $request->url;
        $urls = $request->urls;		
		$admin_customer_id = str_replace(' ', '',$request->admin_customer_id); 
		$data = array('name' => $name,'admin_customer_id' => $admin_customer_id,'url' => $url,'urls' => $urls);
		$data = http_build_query($data);
		$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => Config::get('site_setting.site_url').'api/customers',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array('Accept: application/json'),));
			$addResponse = curl_exec($curl);
			curl_close($curl);
			$addResponse = json_decode($addResponse, true);
			 
			if(isset($addResponse['message'])){
				return redirect('/customers')->with('errorAdd', $addResponse['message']);
			}
			return redirect('/customers')->with('success', 'Customer is added!');	
    }
	 // End create new customer

    // Start Create counter
    public function show($id)
    {
		$customerId = $id;
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.customer_id',$customerId)->get();
		$customerName = DB::table('customers')->select('customer_name')->where('admin_customer_id',$customerId)->first();
		$customerName = $customerName->customer_name;
		$pageTitle = $customerName." / COUNTER";
		return view('customers.widgets', compact('customerId','customerName','allbuttons','pageTitle'));     
    }
	// End Create counter
	
    // Start create customer counter
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
			$code .= '<img src="https://new-actionbutton.voxara.net/public/icons/'.$imageName.'"><br />';
		}
		$code .= '<h2><span id="'.$buttoncode.$point.'" dataid="'.$buttoncode.'" datapoint="'.$point.'"></span></h2><br />';
		if($headline != ""){
			$code .= '<h3>'.$headline.'</h3><br />';
		}
		if($text != ""){
			$code .= '<p>'.$text.'</p><br />';
		}
		$code .= '<link rel="stylesheet" href="https://new-actionbutton.voxara.net/public/css/counter-widget.css"><br />';
		$code .= '<script src="https://new-actionbutton.voxara.net/js/customer-counter-widgets.js?'.$buttoncode.$point.'"></script><br />'; 		
		$code .= "</div>";
		$values = array('type' => $point,'customer_id' => $buttoncode, 'widget_code'=> $code);
		DB::table('existing_counters')->insert($values);
		return redirect('/customers/'.$buttoncode.'#widget')->with('getresponse', $data);
	}
	// Start create customer counter
	
	// Start customer counter api 
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
				$customerUrl = DB::table("customer_urls")->select('url_link')->where('customer_id', $id)->get();
				$matchUrl = 0;
				foreach($customerUrl as $links){
					if(str_contains($url,$links->url_link)){ 
					 $matchUrl = 1;
					}
				}
			}	
			if($matchUrl == 1){
				if($datapoints == "AverageDonation" || $datapoints == "DollarsRaised"  ){
					$buttonAmount = DB::table("buttons")->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->where('buttons.customer_id', $id)->sum('button_meta_values.amount');
					$buttonCompleted = DB::table("buttons")->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->where('buttons.customer_id', $id)->sum('button_meta_values.completed');
					if($datapoints == "DollarsRaised"){
						$buttonResult = $buttonAmount;
					}
					else {
						$buttonResult = $buttonAmount/$buttonCompleted;
					}
				}
				else{
					$buttonResult = DB::table("buttons")->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->where('buttons.customer_id', $id)->sum("button_meta_values.".$datapoints);
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
	// End customer counter api 

    // Start update customer by id 
    public function update(Request $request, $id)
    {
		$name = $request->name; 
		$urls = $request->urls;  		
		$curl = curl_init();
		$data = array('customer_name' => $name,'admin_customer_id' => $id, 'urls' => $urls);
		$data = http_build_query($data);
		curl_setopt_array($curl, array(
			CURLOPT_URL => Config::get('site_setting.site_url').'api/customers/update',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array('Accept: application/json')));
		$addResponse = curl_exec($curl);
		curl_close($curl);
		$addResponse = json_decode($addResponse, true);
		return redirect('/customers')->with('success', 'Customer is updated!');
    }
    // End update customer by id
   
    // Start delete customer by id
    public function destroy($id)
    {
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => Config::get('site_setting.site_url').'api/customers/'.$id,
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
	 // End delete customer by id
	
	// Start customer counters list
	 public function widgetslisting($id)
    {
		$client = new Client();
		$page = 1;
		$pageLimit = 10000; 
		$response = $client->get(Config::get('site_setting.site_url').'api/projects/'.$id.'?page='.$page.'&per_page='.$pageLimit);
		$body = $response->getBody()->getContents();
        $response = json_decode($body);
		$projects = $response->projects;
		$customerId = $response->customerId;
		$customerName = $response->customerName;
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.customer_id',$customerId)->get();
		$counters = DB::table('existing_counters')->where('customer_id',$id)->orderBy('created_at', 'asc')->get();
		$pageTitle = $customerName." / Existing COUNTERS";
		return view('customers.widgets-listing', compact('projects','customerId','customerName','allbuttons','counters','pageTitle'));     
    }
	// End customer counters list
	
	 // Start delete customer counter by id
	 public function widgetDelete($id)
    {
		$counters = DB::table('existing_counters')->where('existing_counters_id',$id)->delete();
		return true;	
	}
	// End delete customer counter by id
}
