<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
USE DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
class ProjectController extends Controller
{ 
  
    public function store(Request $request)
    {  
		$request->validate([
			'name' => 'required',
		]);
		$newProject = new Project([
			'project_name' => $request->get('name'),
			'description' => $request->get('description'),
			'project_url' => $request->get('project_url'),
			'customer_id' => $request->get('customer_id')
			]);
		$newProject->save();
		return response()->json($newProject);
    }

    public function show(string $id)
    {
        $perPage = 10000;
		if(isset($_GET['per_page'])){
			$perPage = $_GET['per_page'];
		}
		$projects = DB::table("projects")->select("projects.*", DB::raw("(SELECT COUNT(existing_counters.project_id) FROM existing_counters WHERE existing_counters.project_id = projects.id ) as counters"), DB::raw("(SELECT COUNT(buttons.project_id) FROM buttons WHERE projects.id = buttons.project_id GROUP BY projects.id) as button"))->where('projects.customer_id', $id)->paginate($perPage);
		$customerId = $id;
		$customerName = DB::table("customers")->where('admin_customer_id', $customerId)->first();
		$customerName = $customerName->customer_name;
		return response()->json(['projects'=>$projects,'customerId'=>$customerId,'customerName'=>$customerName]);
    }

    public function updated(Request $request)
    {
		$updated_at = DB::raw('NOW()');
		$name = $request->name; 
		$description = $request->description;
		$url = $request->url;
		$values = array('project_name' =>$name,'updated_at'=> $updated_at,'description' => $description,'project_url' => $url);
		$response = DB::table('projects')->where('id', $request->id)->update($values);
	    return response()->json($response);
	}
	 
	public function destroy(string $id)
    {
        $response = DB::table('projects')->where('id', $id)->delete();
		DB::table('buttons')->where('project_id', $id)->delete();
		return response()->json($response);
    }
}
