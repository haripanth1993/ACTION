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

class ActionButtonApiController extends Controller
{ 
   public function UpdateButtonSummary() 
    {
		$status = DB::table("counter_api_status")->select('running_status')->first();
		$status =  $status->running_status;
		if($status == 1)
		{
		$buttons = DB::table("buttons")->where('api_running_status',1)->select('button_code')->get();
		
		
		foreach($buttons as $button){
			// same code UpdateButtonSummaryAuto function after all testing
		}
				 
		} 
    }
	
	public function UpdateButtonSummaryAuto()
    {
		$redirect = "/customers/";
		if(isset($_GET['redirect'])){
			$redirect = $_GET['redirect'];
		}
		$baseID =  basename($redirect);
		$baseID =  explode('?',$baseID);
		$baseID =  $baseID[0];
			 
        if (str_contains($redirect, 'projects')) { 
			$buttons = DB::table("buttons")->where('customer_id',$baseID)->where('api_running_status', 1)->select('button_code')->get();
		}		
        else if(str_contains($redirect, 'detail') ) {
            $buttons = DB::table("buttons")->where('api_running_status', 1)->where('button_code',$baseID)->select('button_code')->get();
		}
		else if(str_contains($redirect, 'widgets') ) {
            $buttons = DB::table("buttons")->where('api_running_status', 1)->where('button_code',$baseID)->select('button_code')->get();
		}
		else if(str_contains($redirect, 'buttons')) {
           $buttons = DB::table("buttons")->where('api_running_status', 1)->where('project_id',$baseID)->select('button_code')->get();
			//$buttons = DB::table("buttons")->where('api_running_status', 1)->where('button_code',"SPK-Q0FEQQ==")->select('button_code')->get();
		}
        else{
			$buttons = DB::table("buttons")->where('api_running_status', 1)->select('button_code')->get();	
        }			
				
				
		foreach($buttons as $button){
			
			$buttonId = $button->button_code; 
			$data = array('widgetId' => $buttonId); 
			$curl = curl_init();
			$payload = json_encode($data);
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api.actionbutton.co/api/Widget/GetResultsAsync',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json-patch+json',
					'accept: text/json',
					'Origin: https://actionbutton.voxara.net',
				  ),
			   CURLOPT_POSTFIELDS => $payload,
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$actionbuttonsresult = json_decode($response, true);
			$allActions = $actionbuttonsresult['actions'];
			
			
			$action_array = json_encode($actionbuttonsresult['actions']);
			for($A=0; $A< count($allActions); $A++) {
				$type = null;
				$completed = null;
				$optedIn = null;
				$seen = null;
				$initiated = null;
				$actionOrder = null;
				$amount = null;
				$sent = null;
				$answers_array  = null;
				$distribution_array  = null;	
				$sumcompleted = 0;
				$sumoptedIn = 0;
				$sumseen = 0;
				$suminitiated = 0;
				$sumactionOrder = 0;
				$sumamount = 0;
				$sent = 0;
				
				if(isset($allActions[$A]['type'])) {
					$type = $allActions[$A]['type'];
				}	
				if(isset($allActions[$A]['completed'])) {
					$sumcompleted+= $allActions[$A]['completed'];
					$completed = $sumcompleted;
				}
				if(isset($allActions[$A]['optedIn'])) {
				    $sumoptedIn+= $allActions[$A]['optedIn'];
					$optedIn = $sumoptedIn;
				}
				if(isset($allActions[$A]['seen'])) {
					$sumseen+= $allActions[$A]['seen'];
					$seen = $sumseen;
				}
				if(isset($allActions[$A]['initiated'])) {
					$suminitiated+= $allActions[$A]['initiated'];
					$initiated = $suminitiated;
				}
				if(isset($allActions[$A]['actionOrder'])) {
					$sumactionOrder+= $allActions[$A]['actionOrder'];
					$actionOrder = $sumactionOrder;
				}
				if(isset($allActions[$A]['amount'])) {
					$sumamount+= $allActions[$A]['amount'];
					$amount = $sumamount;
				}
				$sumsent = 0;
				if(isset($allActions[$A]['sent'])) {
					$sumsent+= $allActions[$A]['sent'];
					$sent = $sumsent;
				}
				$concat_answers_array = "";
				if(isset($allActions[$A]['answers'])) {
					$get_answers_array = $allActions[$A]['answers'];
					
					if(is_array($get_answers_array)) {
						$concat_answers_array.= json_encode($get_answers_array);
						$answers_array = $concat_answers_array; 
					}
				}
				$concat_distribution_array = "";
				if(isset($allActions[$A]['distribution'])) {
					$get_distribution_array = $allActions[$A]['distribution'];
					if(is_array($get_distribution_array)) {
						$concat_distribution_array.= json_encode($get_distribution_array);
						$distribution_array = $concat_distribution_array;
					}
				}
			
			 
				
				$buttonExist = DB::table('button_meta_values')->where('button_code',$button->button_code)->where('type',$type)->first();
				 
				if($buttonExist){
					$values = array('type' =>$type,'completed' =>$completed,'seen'=>$seen,'optedIn'=>$optedIn,'initiated'=>$initiated,'actionOrder'=>$actionOrder,'amount'=>$amount,'sent'=>$sent,'distribution_array'=>$distribution_array,'answers_array'=> $answers_array);			
					DB::table('button_meta_values')->where('button_code', $button->button_code)->where('type',$type)->update($values);  
				}
				else {
					$values = array('button_code' => $button->button_code, 'type' =>$type,'completed' =>$completed,'seen'=>$seen,'optedIn'=>$optedIn,'initiated'=>$initiated,'actionOrder'=>$actionOrder,'amount'=>$amount,'sent'=>$sent,'distribution_array'=>$distribution_array,'answers_array'=> $answers_array);			
					DB::table('button_meta_values')->insert($values); 
				}
				
				
				
			   
								
				
			}
				$updated_at = DB::raw('NOW()');
				$values = array('actions'=>$action_array,'updated_at' => $updated_at);
                DB::table('buttons')->where('button_code', $button->button_code)->update($values); 
			
		}
	 
		return redirect($redirect);
	}
	
	
	
	
	public function UpdateCounters()
    { 
	   $status = 1;
	   if(isset($_GET['status'])){
			$status = $_GET['status'];
		}
		$values = array('running_status' => $status);
		DB::table('counter_api_status')->update($values);  
		$redirect = "/customers/";
		if(isset($_GET['redirect'])){
			$redirect = $_GET['redirect'];
		}
		return redirect($redirect);
	}
	
	public static function CountersStatus()
    { 
		$status = DB::table("counter_api_status")->select('running_status')->first();
		$status =  $status->running_status;
		return $status;
	}
	
}
