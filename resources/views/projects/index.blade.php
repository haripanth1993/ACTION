@extends('base', ['title' => 'Projects'])
@section('main')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid panel active" id="listing">
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
               <h4 class="card-title">All Projects for {{ $customerName }}</h4>
            </div>
            <div class="card-body">
               <div class="text-right top-button-panel">
                  <a href="<?= url('customers'); ?>"  class="btn btn-success mr-2">
                  Back to all customers 
                  </a>
                  <button id="addcustomer" type="button" class="btn btn-primary">
                  <i class="material-icons"> person_add </i> Add Project
                  </button>
               </div>
               <div class="material-datatables">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                     <thead>
                        <tr>
                           <th>ID</th>
                           <th>NAME</th>
                           <th>DESCRIPTION</th>
                           <th>BUTTONS</th>
						    <th>COUNTERS</th>
                           <th>CREATED</th>
                           <th>CHANGED</th>
                           <th>STATUS</th>
                           <th class="disabled-sorting text-center">ACTIONS</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($projects->data as $project)
                        <tr id="cus-{{ $project->id }}">
                           <td class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">{{ $project->id }}</td>
                           <td class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">{{ $project->project_name }}</td>
                           <td class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">{{ $project->description }}</td>
						   
                           <td class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">@if($project->button == "") 0 @else {{ $project->button }} @endif</td>
                           <td  class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">{{ $project->counters }}</td>
						   <td class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">	 <?php $formate =  Config::get('site_setting.date_formate'); ?>
                              {{ \Carbon\Carbon::parse($project->created_at)->format($formate)}}
                           </td>
                           <td class="clicking-td" href-link="<?= url('buttons'); ?>/{{ $project->id }}">	 <?php $formate =  Config::get('site_setting.date_formate'); ?>
                              {{ \Carbon\Carbon::parse($project->updated_at)->format($formate)}}
                           </td>
                           <td >
                              <div class="togglebutton">
                                 <label>
                                 <input type="checkbox" class="status" <?php if($project->api_running_status == 1) { ?>checked=""<?php } ?> dataid="{{ $project->id }}">
                                 <span class="toggle"></span>
                                 <br>
                                 <span class="status-{{ $project->id }}"><?php if($project->api_running_status == 1) { ?>Active<?php } else { ?>Pause<?php } ?></span>
                                 </label>
                              </div>
                           </td>
                           <td class="text-right">
                              <a href="<?= url('projects'); ?>/widget/{{ $project->id }}" class="btn btn-success btn-sm">Get Counter</a>
                              <a  href="<?= url('projects'); ?>/existing-counters/{{ $project->id }}"  class="btn btn-success btn-sm">Existing Counters</a>
                              <a href="<?= url('buttons'); ?>/{{ $project->id }}" class="btn btn-lg btn-link btn-success btn-just-icon like"><i class="material-icons">settings</i></a>
                              <a href="#" project-id="{{ $project->id }}"  project-name="{{ $project->project_name }}" project-description="{{ $project->description }}" class="btn btn-lg btn-link btn-info btn-just-icon editproject"><i class="material-icons">edit_note</i></a>
                              <a href="#"  data-trid="cus-{{ $project->id }}"  data-name="{{ $project->project_name }}"  data-text="Project" data-url="{{route('projects.destroy', $project->id)}}" class="btn btn-lg btn-link btn-danger btn-just-icon remove"><i class="material-icons">delete_forever</i></a>
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
<div class="container-fluid panel inactive" id="addform">
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
               <h4 class="card-title">Add Project</h4>
            </div>
            <div class="card-body ">
               <div class="text-right top-button-panel">
                  <button  type="button" class="btn btn-primary back">
                     Back to List  
                     <div class="ripple-container"></div>
                  </button>
               </div>
               <form method="post" action="{{ route('projects.store') }}">
                  @csrf	
                  <input type="hidden" value="{{ $customerId }}" name="customerid">
                  <div class="row">
                     <label class="col-md-3 col-form-label">Name: <i class="material-icons" rel="tooltip" data-placement="bottom" title="What is the name of the campaign or project with this customer." style="vertical-align: bottom;"> info </i> </label>
                     <div class="col-md-9">
                        <div class="form-group has-default">
                           <input type="text" name="name" class="form-control" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Description: </label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" name="description" class="form-control" required>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer w-100 ">
                     <div class="row w-100">
                        <div class="col-md-12 text-right">
                           <button type="submit" class="btn btn-fill btn-rose">Add Project</button>
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
               <h4 class="card-title">Update Project</h4>
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
                     <label class="col-md-3 col-form-label">Name: <i class="material-icons" rel="tooltip" data-placement="bottom" title="What is the name of the campaign or project with this customer." style="vertical-align: bottom;"> info </i> </label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" class="form-control" id="editname" name="name" required>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Description:</label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer w-100 ">
                     <div class="row w-100">
                        <div class="col-md-12 text-right">
                           <button type="submit" class="btn btn-fill btn-rose">Update Project</button>
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
   			"type":"project_id",
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