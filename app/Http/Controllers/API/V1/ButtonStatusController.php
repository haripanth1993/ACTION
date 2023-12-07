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
class ButtonStatusController extends Controller
{ 
   public function ButtonStatus(Request $request)
    {
		$status =  $request->status;
		if($status == "false")
		{
			$status = 0;
		}
		else {
			$status = 1;
		}
		$type =  $request->type;
		$id = $request->id;
		$values = array('api_running_status' =>$status);
		
		if($type == "project_id"){
			DB::table('projects')->where("id", $id)->update($values); 
		}
		if($type == "customer_id"){
			DB::table('customers')->where("admin_customer_id", $id)->update($values); 
			DB::table('projects')->where("customer_id", $id)->update($values);  
		}
		DB::table('buttons')->where($type, $id)->update($values); 
	}	
}
