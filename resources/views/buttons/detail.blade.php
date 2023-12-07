@extends('base', ['title' => 'Projects'])
@section('main')
<div class="container-fluid panel active" id="listing">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header card-header-rose card-header-icon">
               <div class="card-icon">
                  <i class="material-icons"> category </i>
               </div>
               <h4 class="card-title">Details for button {{ $buttons[0]->button_name }}</h4>
            </div>
            <div class="card-body">
               <div class="text-right top-button-panel">
                  <a href="<?= url('buttons'); ?>/{{ $buttons[0]->project_id }}"  class="btn btn-success mr-2">
                  Back to Buttons 
                  </a>
               </div>
               <div class="material-datatables button-details-datatable">
                  <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                     <thead>
                        <tr>
                           <th>BUTTON ID</th>
                           <th>NAME</th>
                           <th>SEEN</th>
                           <th>COMPLETIONS</th>
                            
                           <th>REGISTERED</th>
                           <th>UPDATED</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($buttons as $project)
                        <tr>
                           <td>{{ $project->button_code }}</td>
                           <td>{{ $project->button_name }}</td>
                           <td> {{ $project->seen }}</td>
                           <td>{{ $project->completed }}</td>
						   
                           <td>	 <?php $formate =  Config::get('site_setting.date_formate'); ?>
                              {{ \Carbon\Carbon::parse($project->created_at)->format($formate)}}
                           </td>
                           <td>	 {{ \Carbon\Carbon::parse($project->updated_at)->format($formate)}}
                           </td>
                        </tr>
                        @endforeach	
                     </tbody>
                  </table>
                  <div class="response">
                     <h3>API Response</h3>
                     <?php 
                        $id = $buttons[0]->button_code;
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
                        
                        echo "<pre>";
                        echo json_encode($response, JSON_PRETTY_PRINT);
                        echo "</pre>";
                        
                         ?>
                  </div>
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
@endsection