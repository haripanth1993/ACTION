@extends('base', ['title' => 'Projects'])
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
					<h4 class="card-title">All Buttons for {{ $projectName }}</h4>
				
				</div>
                <div class="card-body">
					<div class="text-right">
					<a href="<?= url('projects'); ?>/{{ $customerId }}"  class="btn btn-success mr-2">
                      Back to Projects 
					  </a>
						<button id="addcustomer" type="button" class="btn btn-primary">
							<i class="material-icons"> person_add </i> Register Button
						</button>
					</div>
					
                  <div class="material-datatables">
                    <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                      <thead>
                        <tr>
						  <th>Button Code  </th>
                          <th>Name</th>
                          <th>Buttons Seen</th>
                          <th>Completed Button Actions</th>
                          <th>Registered At 
							<i class="material-icons" rel="tooltip" data-placement="bottom" title="" style="vertical-align: bottom;" data-original-title="This time shows when the button was registered with this app. Time is in UTC."> info </i>
						  </th>
                          <th>Results Updated At
							<i class="material-icons" rel="tooltip" data-placement="bottom" title="" style="vertical-align: bottom;" data-original-title="This shows the last time the results for this button was updated. Time is in UTC."> info </i>
						  </th>
                          <th class="disabled-sorting text-right">Actions</th>
                        </tr>
                      </thead>
                     <tbody>
						  @foreach($buttons->data as $project)
							<tr>
							  <td class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">{{ $project->button_code }}</td>
							  <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}"> {{ $project->button_name }}</td>
							  <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}"> {{ $project->seen }}</td>
                                    
                              
							  <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">{{ $project->completed }}</td>
							 
							  <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">	 <?php $formate =  Config::get('site_setting.date_formate'); ?>
                                    
                                     {{ \Carbon\Carbon::parse($project->created_at)->format($formate)}}
							  </td>
							  <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">	 {{ \Carbon\Carbon::parse($project->updated_at)->format($formate)}}
							  </td>
							  
							  <td class="text-right">
								<a href="<?= url('buttons'); ?>/widgets/{{ $project->button_code }}" class="btn btn-success btn-sm">Get Counter</a>
								<a  href="<?= url('buttons'); ?>/existing-counters/{{ $project->button_code }}"  class="btn btn-success btn-sm">Existing Counters</a>
								<a href="<?= url('buttons'); ?>/detail/{{ $project->button_code }}" class="btn btn-link btn-success btn-just-icon like"><i class="material-icons">settings</i></a>
								<a href="#" data-text="Button" data-url="{{route('buttons.destroy', $project->button_code)}}" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">delete_forever</i></a>
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
	
<div class="container-fluid panel @if(session()->get('errorAdd')) active @else inactive @endif" id="addform">
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
						<h4 class="card-title">Register Button</h4>
						
						<div class="text-right">
							<button  type="button" class="btn btn-primary back">
								  Back to List
							<div class="ripple-container"></div></button>
						</div>
						</div>
						<div class="card-body ">
						 <form method="post" action="{{ route('buttons.store') }}">
						   @csrf	
						   <input type="hidden" value="{{ $customerId }}" name="customerid">
						   <input type="hidden" value="{{ $projectid }}" name="projectid">
							<div class="row">
							<label class="col-md-3 col-form-label">Button Code: <i class="material-icons" rel="tooltip" data-placement="bottom" title="This usually looks like “SPK-324442==”" style="vertical-align: bottom;"> info </i> </label>
							<div class="col-md-9">
								<div class="form-group has-default">
								 
								<input type="text" id="buttoncode" name="button_code" class="form-control" required>
								</div>
							</div>
							</div>
							<div class="row">
							<label class="col-md-3 col-form-label"> Name: </label>
							<div class="col-md-9">
								<div class="form-group">
								<input type="text" id="bname" name="name"  class="form-control disable " required>
								</div>
							</div>
							</div>
							
							<div class="card-footer w-100 ">
						<div class="row w-100">
							<div class="col-md-12 text-right">
							<button type="submit" class="btn btn-fill btn-rose">Register Button</button>
							</div>
						</div>
						</div>
						</form>
						</div>
						
					</div>
				</div>  	
			</div>	
		</div>		
<script>
   $(document).ready(function(){
	   $("#buttoncode").focusout(function(){
		   $("#disabledbtn").addClass('disabled');
	    var ButtonCode = $(this).val();
		$(".error-msg").remove();
	    $.ajax({     
			 
			 url: '{{Config::get("site_setting.site_url")}}api/buttons/validation/code?buttoncode='+ButtonCode,
			 dataType: "json",
			 success: function (data) {
				  console.log(data);
				 if(data.status == undefined){
					 $("#disabledbtn").removeClass('disabled');
					  $("#bname").val(data.name);
					  
				 }
				 else {
					 
					 $("#buttoncode").after("<p class='error-msg'>"+data.status+"</p>");
					 $("#bname").val("");
					
				 }
			 },
			});
		 });
	   
	   
		$("#search").on("keyup", function() {
			var value = $(this).val();
			$("table.table-list tr").each(function(index) {
				if (index !== 0) {
					$row = $(this);
					var id = $row.find("td:eq(1)").text();
					if (id.indexOf(value) !== 0) {
						$row.hide();
					}
					else {
						$row.show();
					}
				}
			});
		});
	   
	   
	   
    $("#addcustomer").click(function(){
    $("#listing").addClass('inactive');
    $("#listing").removeClass('active');
    
    $("#addform").removeClass('inactive');
    $("#addform").addClass('active');
    });
     $("#back").click(function(){
   
    $("#listing").addClass('active');
    $("#listing").removeClass('inactive');
    
    $("#addform").removeClass('active');
    $("#addform").addClass('inactive');
   
   
    });
    $('select').on('change', function() {
     var totalPage = this.value;
   });
    
    $(".edit-customer").click(function(){
     var customerId = $(this).attr("form-id");
     var customerName = $(this).attr("form-name");
	  var customerproject = $(this).attr("form-project-id");
    
     $("#editform #editprojectid").val(customerproject);
     $("#editform #editname").val(customerName);
	$("#editid").val(customerId);
     $("#editform form").attr("action","/public/index.php/buttons/"+customerId);
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
    
    $("#closemsg").click(function(){
     
    $('.closemsg').hide(1000); 
    });
	
	
	 $(document).on('click', '.dropdown', function(e) {
		
		$(this).addClass('showpages');
		 e.stopPropagation();
	  });
	  $(document).on('click', 'body', function(e) {
		$('.dropdown.showpages').removeClass('showpages');
	  });
	  
	  $(".showoption li").click(function(){
		  var perPage = $(this).attr('label');
		 window.location.href = "{{Config::get('site_setting.site_url')}}buttons/{{ $projectid }}?per_page="+perPage; 
	  });
	 
      
   });
</script> 

<!--script>
	$(document).ready(function(){
			var formData = 'SPK-Q0FARw==';
		 $.ajax({
			 headers: { 'Accept': 'application/json',
                        'Content-Type': 'application/json' 
                    },
                url: "https://stage-api.actionbutton.co/api/Widget/GetResultsAsync",
                type: "post",
				data: { widgetId: formData},
                
				 
                success: function(d) {
                    console.log(d);
					console.log("----");
                },
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(jqXHR);
				
              }
            });
        
	
});
</script-->


@endsection