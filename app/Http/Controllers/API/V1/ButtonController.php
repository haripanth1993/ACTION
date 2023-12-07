<?php
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Button;
USE DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ButtonController extends Controller
{ 


    //Store a newly created button.
    public function added(Request $request)
    {  
		$error = 0;
		$validator = Validator::make($request->all(), [
            "button_code" => "required|unique:buttons,button_code",
			]);
		if($validator->fails())
		{
			$error = array("error" => "Button code already registered.");
		} 
		if($error == 0){  
			$newButton = new Button([
			'project_id' => $request->project_id,'button_name' => $request->button_name,'button_code' => $request->button_code,'customer_id' => $request->customer_id
			]);
			$newButton->save();
			return response()->json($newButton);
		}
		else {
			 return response()->json($error);
		}
    }

    //Buttons listing.
    public function show(string $id)
    {
        $perPage = 10000;
		if(isset($_GET['per_page'])){
			$perPage = $_GET['per_page'];
		}
		
		$buttons = DB::table("buttons")->select("buttons.*",DB::raw("(SELECT  sum(button_meta_values.completed)  FROM button_meta_values WHERE button_meta_values.button_code = buttons.button_code ) as completed"), DB::raw("(SELECT  sum(button_meta_values.seen)  FROM button_meta_values WHERE button_meta_values.button_code = buttons.button_code ) as seen "), DB::raw("(SELECT COUNT(existing_counters.button_code) FROM existing_counters WHERE existing_counters.button_code = buttons.button_code ) as counters"))->where('project_id', $id)->paginate($perPage);
		
		
		$customerName = DB::table("projects")->where('id', $id)->first();
		$projectName = $customerName->project_name;
		$customerId =  $customerName->customer_id;
		$projectid =  $id;
		return response()->json(['buttons'=>$buttons,'customerId'=>$customerId, 'projectid'=>$id,'projectName'=>$projectName]);
    }
	
	// Check button is exsit in API Or DB
	public function checkbutton()
    {
		if(isset($_GET['buttoncode'])){
			$id = $_GET['buttoncode'];
			$data = array('widgetId' => $id); 
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
				'accept: text/plain',
				'Origin: https://actionbutton.voxara.net',
			  ),
			   CURLOPT_POSTFIELDS => $payload,
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$response = json_decode($response);
			if(isset($response->message)){
				$result = array('status' => "Button is not found");
				return  $result;
			}
			else {
				if(isset($response->widgetRequestId)){
					if(DB::table('buttons')->where('button_code',$response->widgetRequestId)->exists()){
						$result = array('status' => "Button code already registered.");
						return  $result;
					}
					else {
						$result = array('name' => $response->widgetName); 
						return  $result;
					}
				}
			}
		}
    }
	
	// 	Update button
	public function updated(Request $request, string $id)
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
				 return response()->json("hh");
			}
		$values = array('button_name' => $request->name, 'button_code' => $request->button_code,'updated_at' => $updated_at);
		try {
			DB::table('buttons')->where('button_code', $id)->update($values); 
				return response()->json("hhh");
			} 
				catch (\Illuminate\Database\QueryException $e){
				$errorCode = $e->getCode();
				return response()->json($errorCode);
		}
	}
	 
	// Remove button by Id.
    public function destroy(string $id)
    {
        $response = DB::table('buttons')->where('button_code', $id)->delete();
		return response()->json($response);
    }
}
