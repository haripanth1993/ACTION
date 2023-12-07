<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Customer_url;
USE DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
class CustomerController extends Controller
{ 
    public function index()
    {
		$perPage = 10000;
		if(isset($_GET['per_page'])){
			$perPage = $_GET['per_page'];
		}
		$Customer = DB::table("customers")->select("customers.*", DB::raw("(SELECT COUNT(existing_counters.customer_id) FROM existing_counters WHERE existing_counters.customer_id = customers.admin_customer_id ) as counters"), DB::raw("(SELECT GROUP_CONCAT(url_link) FROM customer_urls WHERE customer_urls.customer_id = customers.admin_customer_id GROUP BY customers.admin_customer_id) as urls"), DB::raw("(SELECT COUNT(projects.customer_id) FROM projects WHERE projects.customer_id = customers.admin_customer_id GROUP BY customers.admin_customer_id) as projects"), DB::raw("(SELECT COUNT(buttons.customer_id) FROM buttons WHERE buttons.customer_id = customers.admin_customer_id GROUP BY customers.admin_customer_id) as buttons"))->paginate($perPage);
		return response()->json($Customer);
    }
	
	public function store(Request $request)
    {  
	    $urls  = $request->get('urls');
        $request->validate([
			'admin_customer_id' => 'required|unique:customers,admin_customer_id',
			'name' => 'required', 
			]);

		  $newCustomer = new Customer([
			'customer_name' => $request->get('name'),
			'admin_customer_id' => $request->get('admin_customer_id'),
			'customer_url' => $request->get('url'),
			]);
		$newCustomer->save();
		foreach($urls as $url){
			$newUrls = new Customer_url([
			'customer_id' => $request->get('admin_customer_id'),
			'url_link' => $url,
			]);
			$newUrls->save();
		}
		return response()->json($newCustomer);
    }

	public function updated(Request $request)
    {
		$updated_at = DB::raw('NOW()');
		$urls = $request->urls;
		$values = array('customer_name' => $request->customer_name,'updated_at'=> $updated_at);
		$id	 = $request->admin_customer_id;	
        $response = DB::table('customers')->where('admin_customer_id', $id)->update($values);
		DB::table('customer_urls')->where('customer_id', $id)->delete();
		foreach($urls as $url){
			$newUrls = new Customer_url([
			'customer_id' => $id,
			'url_link' => $url,
			]);
			$newUrls->save();
		}
		return response()->json($response);
	}
	 
    public function update(Request $request, string $id)
    {
		$customer = Customer::findOrFail($id);
		$request->validate([
			'customer_name' => 'required',
		]);
		$customer->customer_name = $request->get('customer_name');
		$response = $customer->save();
		return response()->json($response);
    }

    public function destroy(string $id)
    {
        $response = DB::table('customers')->where('admin_customer_id', $id)->delete();
		DB::table('projects')->where('customer_id', $id)->delete();
		DB::table('buttons')->where('customer_id', $id)->delete();
		return response()->json($response);
    }
}
