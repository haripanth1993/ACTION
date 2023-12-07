@extends('base', ['title' => 'Customers'])
@section('main')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid panel @if(session()->get('errorAdd')) inactive @else active @endif" id="listing">
   @if(session()->get('success'))
   <div class="alert alert-success col-6 m-auto"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="material-icons">close</i></button><span>{{ session()->get('success') }}</span></div>
   @endif
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header card-header-rose card-header-icon">
               <div class="card-icon">
                  <i class="material-icons"> category </i>
               </div>
               <h4 class="card-title">All Customers</h4>
            </div>
            <div class="card-body">
               <div class="text-right top-button-panel">
                  <button id="addcustomer" type="button" class="btn btn-primary">
                  <i class="material-icons"> person_add </i> Add Customer 
                  </button>
               </div>
               <div class="toolbar"></div>
               <div class="material-datatables">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                     <thead>
                        <tr>
                           <th>ID</th>
                           <th>NAME</th>
                           <th>URL</th>
                           <th>PROJECTS</th>
                           <th>BUTTONS</th>
						   <th>COUNTERS</th>
                           <th>CREATED</th>
                           <th>STATUS</th>
                           <th class="disabled-sorting text-center">ACTIONS</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($customers->data as $customer)
                        <tr id="cus-{{ $customer->admin_customer_id }}">
                           <td class="clicking-td" href-link="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}">{{ $customer->admin_customer_id }}</td>
                           <td class="clicking-td" href-link="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}">{{ $customer->customer_name }}</td>
                           <td class="clicking-td" href-link="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}"><?php echo str_replace(',',"<br/>", $customer->urls); ?></td>
                           <td class="clicking-td" href-link="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}">@if($customer->projects == "") 0 @else {{ $customer->projects }} @endif</td>
                           <td class="clicking-td" href-link="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}">@if($customer->buttons == "") 0 @else {{ $customer->buttons }} @endif</td>
                           <td class="clicking-td" href-link="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}">{{ $customer->counters }}</td>
						   <td>	<?php 
                              $formate =  Config::get('site_setting.date_formate'); 
                              ?>
                              {{ \Carbon\Carbon::parse($customer->created_at)->format($formate)}} 
                           </td>
                           <td >
                              <div class="togglebutton">
                                 <label>
                                 <input type="checkbox" class="status" <?php if($customer->api_running_status == 1) { ?>checked=""<?php } ?> dataid="{{ $customer->admin_customer_id }}">
                                 <span class="toggle"></span>
                                 <br>
                                 <span class="status-{{ $customer->admin_customer_id }}"><?php if($customer->api_running_status == 1) { ?>Active<?php } else { ?>Pause<?php } ?></span>
                                 </label>
                              </div>
                           </td>
                           <td class="text-right">
                              <a href="<?= url('customers'); ?>/{{ $customer->admin_customer_id }}" class="btn btn-success btn-sm">Get Counter</a>
                              <a href="<?= url('customers'); ?>/existing-counters/{{ $customer->admin_customer_id }}" class="btn btn-success btn-sm">Existing Counters</a>
                              <a href="<?= url('projects'); ?>/{{ $customer->admin_customer_id }}" class="btn btn-lg  btn-link btn-success btn-just-icon like"><i class="material-icons">settings</i></a>
                              <a href="#" class="btn btn-lg btn-link btn-info btn-just-icon edit" customer-id="{{ $customer->admin_customer_id }}"  customer-name="{{ $customer->customer_name }}"  customer-urls="{{ $customer->urls }}"><i class="material-icons">edit_note</i></a>
                              <a href="#" data-trid="cus-{{ $customer->admin_customer_id }}" data-name="{{ $customer->customer_name }}"   data-text="Customer" data-url="{{route('customers.destroy', $customer->admin_customer_id)}}" class="btn btn-lg btn-link btn-danger btn-just-icon remove"><i class="material-icons">delete_forever</i></a>
                           </td>
                        </tr>
                        @endforeach	
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- end content-->
         </div>
         <!--  end card  -->
      </div>
      <!-- end col-md-12 -->
   </div>
   <!-- end row -->
</div>
<div class="container-fluid panel inactive @if(session()->get('errorAdd')) active @else inactive @endif" id="addform">
   @if(session()->get('errorAdd'))
   <div class="alert alert-danger col-6 m-auto"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="material-icons">close</i></button><span>{{ session()->get('errorAdd') }}</span></div>
   @endif
   <div class="row">
      <div class="col-md-12">
         <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
               <div class="card-icon">
                  <i class="material-icons"> category </i>
               </div>
               <h4 class="card-title">Add Customer</h4>
            </div>
            <div class="card-body ">
               <div class="text-right top-button-panel">
                  <button  type="button" class="btn btn-primary back">
                     Back to List  
                     <div class="ripple-container"></div>
                  </button>
               </div>
               <form class="form-horizontal" method="post" action="{{ route('customers.store') }}">
                  @csrf	
                  <div class="row">
                     <label class="col-md-3 col-form-label">Admin Cust ID: <i class="material-icons" rel="tooltip" data-placement="bottom" title="You can find this number in ActionButton Admin in the URL bar of the partner." style="vertical-align: bottom;"> info </i> </label>
                     <div class="col-md-9">
                        <div class="form-group has-default">
                           <input type="text" class="form-control"  name="admin_customer_id" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Name: <i class="material-icons" rel="tooltip" data-placement="bottom" title="What is the name of the customer as registered in ActionButton Admin." style="vertical-align: bottom;"> info </i> </label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" class="form-control" name="name" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Url:</label>
                     <div class="col-md-9">
                        <div id="row">
                           <div class="input-group mb-3">
                              <input type="text" name="urls[]" class="form-control" required>
                              <button id="rowAdder" type="button" class="btn btn-success btn-sm px-3 float-right"  rel="tooltip" data-placement="top" title="" style="vertical-align: top;" data-original-title="Add More Url's">
                              <i class="material-icons">add</i>
                              </button>
                           </div>
                        </div>
                        <div id="newinput"></div>
                     </div>
                  </div>
                  <div class="card-footer w-100 ">
                     <div class="row w-100 ">
                        <div class="col-md-12 text-right">
                           <button type="submit" class="btn btn-fill btn-rose">Add Customer</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid panel inactive" id="editform">
   <div class="row">
      <div class="col-md-12">
         <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
               <div class="card-icon">
                  <i class="material-icons"> category </i>
               </div>
               <h4 class="card-title">Update Customer</h4>
            </div>
            <div class="card-body ">
               <div class="text-right top-button-panel">
                  <button type="button" class="btn btn-primary back">
                     Back to List  
                     <div class="ripple-container"></div>
                  </button>
               </div>
               <form method="post" action="#">
                  @method('PATCH') 
                  @csrf 
                  <div class="row">
                     <label class="col-md-3 col-form-label">Name: <i class="material-icons" rel="tooltip" data-placement="bottom" title="What is the name of the customer as registered in ActionButton Admin." style="vertical-align: bottom;"> info </i> </label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" class="form-control" id="editname" name="name" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Url:</label>
                     <div class="col-md-9">
                        <div id="row">
                           <div class="input-group mb-3">
                              <input type="text" name="urls[]" id="firsturl" class="form-control" required>
                              <button id="rowEdit" type="button" class="rowAdder btn btn-success btn-sm px-3 float-right" rel="tooltip" data-placement="top" title="" style="vertical-align: top;" data-original-title="Add More Url's">
                              <i class="material-icons">add</i> 
                              </button>
                           </div>
                        </div>
                        <div id="urlEdit"></div>
                     </div>
                  </div>
                  <div class="card-footer w-100 ">
                     <div class="row w-100">
                        <div class="col-md-12 text-right">
                           <button type="submit" class="btn btn-fill btn-rose">Update Customer</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
   $("#rowAdder").click(function () {
       newRowAdd =
           '<div id="row"> <div class="input-group">' +
           '<input type="text"  name="urls[]"  class="form-control"> <div class="input-group-prepend">' +
           '<button class="btn btn-danger btn-sm px-3" id="DeleteRow" type="button">' +
           '<i class="material-icons">delete</i></button> </div>' +
           '</div> </div>';
   
       $('#newinput').append(newRowAdd);
   });
   
   $("#rowEdit").click(function () {
       newRowAdd =
           '<div id="row"> <div class="input-group">' +
           '<input type="text"  name="urls[]"  class="form-control"> <div class="input-group-prepend">' +
           '<button class="btn btn-danger btn-sm px-3" id="DeleteRow" type="button">' +
           '<i class="material-icons">delete</i></button> </div>' +
           '</div> </div>';
   
       $('#urlEdit').append(newRowAdd);
   });
   
   $("body").on("click", "#DeleteRow", function () {
       $(this).parents("#row").remove();
   })
</script> 
<script>
   $(document).ready(function(){
   	$(".clicking-tr").click(function(){
   		window.location = $(this).attr('href-link');
   	});	
      
   $(".edit-customer").click(function(){
   		var customerId = $(this).attr("form-id");
   		var customerName = $(this).attr("form-name");
   		$("#editform #editname").val(customerName);
   		$("#editform form").attr("action","customers/"+customerId);
   		$("#listing").addClass('inactive');
   		$("#listing").removeClass('active');
   		$("#editform").removeClass('inactive');
   		$("#editform").addClass('active');
   	});
   
   	$("#editback").click(function(){
   		$("#listing").addClass('active');
   		$("#listing").removeClass('inactive');
   		$("#editform").removeClass('active');
   		$("#editform").addClass('inactive');
   	});
   
   });
</script>
<script>
	$(document).ready(function(){
		$(".status").change(function(){
			var status = $(this)[0].checked;
			var id = $(this).attr('dataid');
			if(status == false){
				$(".status-"+id).text("Pause");
			}
			else {
				$(".status-"+id).text("Active");
			}
			var baseUrl = 'https://new-actionbutton.voxara.net/public/api/button/status';
			var token = $("meta[name='csrf-token']").attr("content");
			$.ajax({
                url: baseUrl,
				type: 'post',
				data: {
					"_token": token,
					"status":status,
					"type":"customer_id",
					"id": id,
				},
                success: function(data) {
				},
				error:function(data) {
				},
         	});
		});
   });
</script>
@endsection