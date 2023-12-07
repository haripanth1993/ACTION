<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Support\Facades\Validator;
use Config;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;

class ButtonController extends Controller
{
    
	// Start Get button details by Id 
	public function detail($id)
    { 
		$buttons = DB::table("buttons")->select("buttons.*",DB::raw("(SELECT  sum(button_meta_values.completed)  FROM button_meta_values WHERE button_meta_values.button_code = buttons.button_code ) as completed"), DB::raw("(SELECT  sum(button_meta_values.seen)  FROM button_meta_values WHERE button_meta_values.button_code = buttons.button_code ) as seen "), DB::raw("(SELECT COUNT(existing_counters.button_code) FROM existing_counters WHERE existing_counters.button_code = buttons.button_code ) as counters"))->where('buttons.button_code', $id)->get();
		
		 
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.button_code',$id)->get();
		
		$projectName = DB::table('projects')->select('project_name')->where('customer_id',$buttons[0]->customer_id)->first();
		$projectName = $projectName->project_name;
		
		$buttonsName = $buttons[0]->button_name;
		
		$customerName = DB::table('customers')->select('customer_name')->where('admin_customer_id',$buttons[0]->customer_id)->first();
		$customerName = $customerName->customer_name;
		
		$pageTitle = $customerName." / ".$projectName." / ".$buttonsName." / BUTTON";
		return view('buttons.detail', compact('buttons','allbuttons','pageTitle')); 
	}
	// End Get button details by Id 
	
	// Start Get widgets details by Id 
	public function widgets($id)
    { 
		$buttons = DB::table("buttons")->where('button_code', $id)->get();
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.button_code',$id)->get();
		$metaValue = DB::table('button_meta_values')->where('button_code',$id)->get();
		
		$customerName = DB::table('customers')->select('customer_name')->where('admin_customer_id',$buttons[0]->customer_id)->first();
		$customerName = $customerName->customer_name;
		$pageTitle = $customerName." / ".$buttons[0]->button_name." / COUNTER";
		return view('buttons.widgets', compact('metaValue','buttons','allbuttons','pageTitle')); 
	} 
	// End Get widgets details by Id 
	

	// Start show counter results to widget
	public function widgetsdata($id)
	{ 
		$buttons = DB::table("buttons")->where('button_code', $id)->first();
		if(isset($_GET['datapoints'])){
			$datapoints = $_GET['datapoints'];
			$url = $_GET['CustomerUrl'];
			if($url == "admin")
			{
				$matchUrl = 1;
			} 
			else {
				$customer = DB::table("buttons")->select('customer_id')->where('button_code', $id)->first();
				$customerUrl = DB::table("customer_urls")->select('url_link')->where('customer_id',  $customer->customer_id)->get();
				$matchUrl = 0;
				foreach($customerUrl as $links){
					if(str_contains($url,$links->url_link)){ 
					 $matchUrl = 1;
					}
				}	
			} 
				
			if($matchUrl == 1){
				if($datapoints == "seenPoll"){
					$buttons = DB::table("button_meta_values")->select("seen")->where('button_code', $id)->where('type',"Poll")->first();
					$buttonResult = $buttons->seen;
				}
				else if($datapoints == "seenSpeedometer"){
					$buttons = DB::table("button_meta_values")->select("seen")->where('button_code', $id)->where('type',"Speedometer")->first();
					$buttonResult = $buttons->seen;
				}	
				else if($datapoints == "seenQuiz"){
					$buttons = DB::table("button_meta_values")->select("seen")->where('button_code', $id)->where('type',"Quiz")->first();
					$buttonResult = $buttons->seen;
				}	
				else if($datapoints == "seenAll"){
					$buttons = DB::table("button_meta_values")->where('button_code', $id)->sum("seen");
					$buttonResult = $buttons;
				}
				else if($datapoints == "completedPoll"){
					$buttons = DB::table("button_meta_values")->select("completed")->where('button_code', $id)->where('type',"Poll")->first();
					$buttonResult = $buttons->completed;
				}
				else if($datapoints == "completedSpeedometer"){
					$buttons = DB::table("button_meta_values")->select("completed")->where('button_code', $id)->where('type',"Speedometer")->first();
					$buttonResult = $buttons->completed;
				}
				else if($datapoints == "completedQuiz"){
					$buttons = DB::table("button_meta_values")->select("completed")->where('button_code', $id)->where('type',"Quiz")->first();
					$buttonResult = $buttons->completed;
				}
				else if($datapoints == "completedAll"){
					$buttons = DB::table("button_meta_values")->where('button_code', $id)->sum("completed");
					$buttonResult = $buttons;
				}
				else if($datapoints == "initiated"){
					$buttons = DB::table("button_meta_values")->where('button_code', $id)->sum("initiated");
					$buttonResult = $buttons;
				}
				else if($datapoints == "optedIn"){
					$buttons = DB::table("button_meta_values")->where('button_code', $id)->sum("optedIn");
					$buttonResult = $buttons;
				}
				else if($datapoints == "DonationAverage"){
					$buttons = DB::table("button_meta_values")->select('amount','completed')->where('button_code', $id)->first();
					$buttonResult = $buttons->amount/$buttons->completed;
				}
				else if($datapoints == "DollarsRaised"){
					$buttons = DB::table("button_meta_values")->select('amount')->where('button_code', $id)->first();
					$buttonResult = $buttons->amount;
				}
				else if(str_contains($datapoints,"Sentiment")){
					$buttons = DB::table("button_meta_values")->select('distribution_array','completed')->where('type',"SentimentPoll")->where('button_code', $id)->first();
					$distributionArray = json_decode($buttons->distribution_array);
					$completed = array_sum($distributionArray);
					$distribution = array_chunk($distributionArray,3);
					if(str_contains($datapoints,"SentimentBottomPercent")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[1]);
						$buttonResult = $buttonResult/$completed*100;
					}
					else if(str_contains($datapoints,"SentimentBottom")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[1]);
					}
					else if(str_contains($datapoints,"SentimentTopPercent")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[0]);
						$buttonResult = $buttonResult/$completed*100;
					}
					else if(str_contains($datapoints,"SentimentTop")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[0]);
					}
					else if(str_contains($datapoints,"SentimentPercent")){
						$datapoints = str_replace("SentimentPercent","",$datapoints);
						$buttonResult = array_sum($distribution[$datapoints]);
						$buttonResult = $buttonResult/$completed*100;
					}
					else {
						$datapoints = str_replace("Sentiment","",$datapoints); 
						$buttonResult = array_sum($distribution[$datapoints]);
					}
                }
				else if(str_contains($datapoints,"Speedometer")){
					$buttons = DB::table("button_meta_values")->select('distribution_array','completed')->where('type',"Speedometer")->where('button_code', $id)->first();
					$distributionArray = json_decode($buttons->distribution_array);
					$completed = array_sum($distributionArray);
					$distribution = array_chunk($distributionArray,3);
					if(str_contains($datapoints,"SpeedometerBottomPercent")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[1]);
						$buttonResult = $buttonResult/$completed*100;
					}
					else if(str_contains($datapoints,"SpeedometerBottom")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[1]);
					}
					else if(str_contains($datapoints,"SpeedometerTopPercent")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[0]);
						$buttonResult = $buttonResult/$completed*100;
					}
					else if(str_contains($datapoints,"SpeedometerTop")){
						$distribution =  array_chunk($distributionArray,6);
						$buttonResult =  array_sum($distribution[0]);
					}
					else if(str_contains($datapoints,"SpeedometerPercent")){
						$datapoints = str_replace("SpeedometerPercent","",$datapoints);
						$buttonResult = array_sum($distribution[$datapoints]);
						$buttonResult = $buttonResult/$completed*100;
					}
					else {
						$datapoints = str_replace("Speedometer","",$datapoints);
						$buttonResult = array_sum($distribution[$datapoints]);
					}
                }
				else if(str_contains($datapoints,"Quiz")){
					$buttons = DB::table("button_meta_values")->select('answers_array','completed')->where('type',"Quiz")->where('button_code', $id)->first();
					$answersArray = json_decode($buttons->answers_array);
					$completed = $buttons->completed;
					$correct = 0;
					foreach($answersArray as $answer){
						if($answer->isCorrect == 1){
							$correct = $answer->count;
						}
					}
					if(str_contains($datapoints,"QuizWrongPercent")){
						$Wrong = $completed-$correct;
						$buttonResult = $Wrong/$completed*100;
					}
					else if(str_contains($datapoints,"QuizWrong")){
						$Wrong = $completed-$correct;
						$buttonResult =  $Wrong;
					}
					else if(str_contains($datapoints,"QuizRightPercent")){
						$buttonResult = $correct/$completed*100;
					}
					else if(str_contains($datapoints,"QuizRight")){
						$buttonResult = $correct;
					}
					else if(str_contains($datapoints,"QuizPercent")){
						$datapoints = str_replace("QuizPercent","",$datapoints);
						$buttonResult = $answersArray[$datapoints]->count/$completed*100;
					}
					else {
						$datapoints = str_replace("Quiz","",$datapoints);
						$buttonResult = $answersArray[$datapoints]->count;
					}
				}
				else if(str_contains($datapoints,"Poll")){
					$buttons = DB::table("button_meta_values")->select('answers_array','completed')->where('type',"Poll")->where('button_code', $id)->first();
					$answersArray = json_decode($buttons->answers_array);
					$completed = $buttons->completed;
					if(str_contains($datapoints,"PollPercent")){
						$datapoints = str_replace("PollPercent","",$datapoints);
						$buttonResult = $answersArray[$datapoints]->count/$completed*100;
					}
					else {
						$datapoints = str_replace("Poll","",$datapoints);
						$buttonResult = $answersArray[$datapoints]->count;
					}
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
	// End show counter results to widget
	
	// Start Create button
	public function store(Request $request)
    {
		$id = $request->projectid;
		$validator = Validator::make($request->all(), [
           "button_code" =>
               "required|unique:buttons,button_code",
        ]);
		if($validator->fails())
		{
			return redirect('/buttons/'.$id)->with('errorAdd', 'The button is already registered.');
		}
		
		$values = array('project_id' => $request->projectid,'button_name' => $request->name,'button_code' => $request->button_code,'customer_id' => $request->customerid);
		$response = DB::table('buttons')->insert($values);
		$values = array('button_code' => $request->button_code);
		
		
		
		
		
		return redirect('/update-buttons-summary/?redirect=/buttons/'.$id)->with('success', 'Button is added!');
    }
    // End Create button
    
    public function show($id)
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
		
		$pageTitle = $customerName." / ".$projectName." / PROJECT BUTTONS";
		return view('buttons.index', compact('buttons','customerId','projectName', 'projectid','allbuttons','pageTitle')); 
	}

	public function update(Request $request, $id)
    {
		$updated_at = DB::raw('NOW()');
		$redirect = $request->projectid;
		$button_code = $id;
		$validator = Validator::make($request->all(), [
			'buttons' => [
			'unique:buttons',
			Rule::unique('buttons')->ignore($id),
		],
		]);
		if($validator->fails())
		{
			return redirect('/buttons/'.$redirect)->with('errorAdd', 'Button code already registered.');
		}
		$values = array('button_name' => $request->name, 'button_code' => $request->button_code,'updated_at' => $updated_at);
		try {
			DB::table('buttons')->where('button_code', $id)->update($values); 
			return redirect('/buttons/'.$redirect)->with('success', 'Button is updated!');	
			} catch (\Illuminate\Database\QueryException $e){
				$errorCode = $e->getCode();
				return redirect('/buttons/'.$redirect)->with('errorAdd', 'Button code already registered.');
		}
	}

    public function destroy($id)
    {
		$redirect = DB::table('buttons')->where('button_code', $id)->first();
		$redirect = $redirect->project_id;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => Config::get('site_setting.site_url').'api/buttons/'.$id,
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
		return redirect('/buttons/'.$redirect)->with('success', 'Button is deleted!');
    
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
		$buttonName = str_replace("==", "",$buttoncode);
		$buttonName = str_replace("=", "",$buttonName);
		$data = array('headline' => $headline, 'text' => $text,'point'=> $point,'buttoncode' => $buttoncode,'imageName' => $imageName);
		$code = '<div class="counter-widget"><br />';
		if($icon != ""){
			$code .= '<img src="https://new-actionbutton.voxara.net/public/icons/'.$imageName.'"><br />';
		}
		$code .= '<h2><span id="'.$buttonName.$point.'" dataid="'.$buttoncode.'" datapoint="'.$point.'"></span></h2><br />';
		if($headline != ""){
			$code .= '<h3>'.$headline.'</h3><br />';
		}
		if($point == "completed"){
			$Name = "Completions";
		}
		if($point == "optedIn"){
			$Name = "Opt-Ins";
		}
		if($point == "seen"){
			$Name = "Views";
		}
		if($point == "initiated"){
			$Name = "Interactions";
		}
		if($point == "amount"){
			$Name = "Dollars Raised";
		}
		if($point == "ab"){
			$Name = "Average Donation";
		}
		
		
		if($text != ""){
			$code .= '<p>'.$text.'</p><br />';
		}
		$code .= '<link rel="stylesheet" href="https://new-actionbutton.voxara.net/public/css/counter-widget.css"><br />';
		$code .= '<script src="https://new-actionbutton.voxara.net/js/counter-widgets.js?'.$buttonName.$point.'"></script><br />'; 		
		$code .= "</div>";
		$values = array('type' => $point,'button_code' => $buttoncode, 'widget_code'=> $code);
		DB::table('existing_counters')->insert($values);
		return redirect('/buttons/widgets/'.$buttoncode.'#widget')->with('getresponse', $data);
	}
	
	// Start Show widgets listing by button id
	 public function widgetslisting($id)
    {
		$projects = DB::table("buttons")->where('button_code', $id)->get();
		$allbuttons = DB::table('buttons')->join('button_meta_values', 'button_meta_values.button_code', '=', 'buttons.button_code')->selectRaw('count(*) as total, Sum(button_meta_values.completed) as completed, Sum(button_meta_values.optedIn) as optedIn, Sum(button_meta_values.seen) as seen')->where('buttons.button_code',$id)->get();
		
		$counters = DB::table('existing_counters')->where('button_code',$id)->orderBy('created_at', 'asc')->get();
		$customerName = DB::table('customers')->select('customer_name')->where('admin_customer_id',$projects[0]->customer_id)->first();
		$customerName = $customerName->customer_name;
		$pageTitle = $customerName." / ".$projects[0]->button_name." / Existing COUNTERS";
		return view('buttons.widgets-listing', compact('projects','allbuttons','counters','pageTitle'));     
    }
	// End Show widgets listing by button id
	
    // Start Delete button by id	
	public function widgetDelete($id)
    {
		$counters = DB::table('existing_counters')->where('existing_counters_id',$id)->delete();
		$redirect = "/buttons/existing-counters/".$_GET['redirect'];
		return redirect($redirect)->with('success', 'Widget Code is deleted!');	
	}
	// End Delete button by id	
}
