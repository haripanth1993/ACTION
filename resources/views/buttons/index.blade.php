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
               <div class="text-right top-button-panel">
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
                           <th>BUTTON ID</th>
                           <th>NAME</th>
                           <th>SEEN</th>
                           <th>COMPLETIONS</th>
                           <th>COUNTERS</th>
                           <th>REGISTERED</th>
                           <th>UPDATED</th>
                           <th>STATUS</th>
                           <th class="disabled-sorting text-center">ACTIONS</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($buttons->data as $project)
                        <tr id="cus-{{ str_replace('==', '',$project->button_code) }}">
                           <td class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">{{ $project->button_code }}</td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}"> {{ $project->button_name }}</td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}"> {{ $project->seen }}</td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">{{ $project->completed }}</td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">{{ $project->counters }}</td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">	 <?php $formate =  Config::get('site_setting.date_formate'); ?>
                              {{ \Carbon\Carbon::parse($project->created_at)->format($formate)}}
                           </td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/detail/{{ $project->button_code }}">	 {{ \Carbon\Carbon::parse($project->updated_at)->format($formate)}}
                           </td>
                           <td >
                              <div class="togglebutton">
                                 <label>
                                 <input type="checkbox" class="status" <?php if($project->api_running_status == 1) { ?>checked=""<?php } ?> dataid="{{ $project->button_code }}">
                                 <span class="toggle"></span>
                                 <br>
                                 <span class="status-{{str_replace('==', '',$project->button_code)}}"><?php if($project->api_running_status == 1) { ?>Active<?php } else { ?>Pause<?php } ?></span>
                                 </label>
                              </div>
                           </td>
                           <td class="text-right">
                              <a href="<?= url('buttons'); ?>/widgets/{{ $project->button_code }}" class="btn btn-success btn-sm">Get Counter</a>
                              <a  href="<?= url('buttons'); ?>/existing-counters/{{ $project->button_code }}"  class="btn btn-success btn-sm">Existing Counters</a>
                              <a href="<?= url('buttons'); ?>/detail/{{ $project->button_code }}" class="btn btn-lg btn-link btn-success btn-just-icon like"><i class="material-icons">settings</i></a>
                              <a href="#" data-trid="cus-{{str_replace('==', '',$project->button_code)}}"  data-name="{{ $project->button_name }}"data-text="Button" data-url="{{route('buttons.destroy', $project->button_code)}}" class="btn btn-lg btn-link btn-danger btn-just-icon remove"><i class="material-icons">delete_forever</i></a>
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
                     <div class="ripple-container"></div>
                  </button>
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
                           <button type="submit" class="btn btn-fill btn-rose" id="disabledbtn">Register Button</button>
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
	$(".error-msg").remove();
    $("#disabledbtn").addClass('disabled');
	var ButtonCode = $(this).val();
	ButtonCode = ButtonCode.replace(/ /g, "")
   $(this).val(ButtonCode)
   $.ajax({     
   	url: '{{Config::get("site_setting.site_url")}}api/buttons/validation/code?buttoncode='+ButtonCode,
   	dataType: "json",
   	success: function (data) {
   	 if(data.status == undefined){
   		 $("#disabledbtn").removeClass('disabled');
   		  $("#bname").val(data.name);
   	}
   	 else {
		  $(".error-msg").remove();
   		 $("#buttoncode").after("<p class='error-msg text-danger'>"+data.status+"</p>");
   		 $("#bname").val("");
   	}
    },
   });
   });
   
   $(".status").change(function(){
   var status = $(this)[0].checked;
   var id = $(this).attr('dataid');
   var result = id.replace("==", "");
    if(status == false){
   	$(".status-"+result).text("Pause");
   }
   else {
   	$(".status-"+result).text("Active");
   }
   var baseUrl = 'https://new-actionbutton.voxara.net/public/api/button/status';
   var token = $("meta[name='csrf-token']").attr("content");
   $.ajax({
                url: baseUrl,
   	type: 'post',
   	data: {
   			"_token": token,
   			"status":status,
   			"type":"button_code",
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