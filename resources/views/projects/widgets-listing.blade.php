@extends('base', ['title' => 'Projects'])
@section('main')
<div class="customer-listing">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
            <div class="card ">
               <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                     <i class="material-icons"> category </i>
                  </div>
                  <h4 class="card-title text-capitalize">Get Existing counters for Project {{ $customerName }}</h4>
               </div>
               <div class="card-body">
                  <div class="text-right top-button-panel">
                     <a  href="<?= url('projects'); ?>/{{$customerId}}" class="btn btn-primary mr-0 ml-auto">
                        Back to Project
                        <div class="ripple-container"></div>
                     </a>
                  </div>
                  <?php  if(count($counters) == 0){ ?>
                  <h4>No Existing Counter found.</h4>
                  <?php } 
                     else {
                     	
                     ?>
                  <h4>Widget Code</h4>
                  <?php 
                     }
                     	$temp = 0;
                     	foreach($counters as $counter){
                     	$temp++;	
                     	?>
                  <div class="sample-code bg-light position-relative mb-4" id="counter{{ $counter->existing_counters_id }}">
                     <div class="copy-code div{{ $temp }}" id="accbtn{{ $temp }}">
<xmp id="myInput">
<!--  Counter Widget --> 
<?php 
$data = str_replace("<br />", "\n", $counter->widget_code);
print_r($data); ?>	
<!-- end counter widget -->
</xmp>
                     </div>
                     <div class="widget-code-button-panel">
                        <button type="button" onclick="copyStringToClipboard(this.getAttribute('data-target'))"  data-target="accbtn{{ $temp }}" class="btn btn-success btn-sm text-uppercase m-1">Copy to clipboard</button>
                        <button type="button" codeid="{{ $counter->existing_counters_id }}" class="btn btn-danger btn-sm m-1 deletebtn"><i class="material-icons"> delete </i></button>
                     </div>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- Start delete widget popup for counters by id-->
<script>
   $(document).ready(function(){
   	$(".deletebtn").click(function(){
   		var codeid = $(this).attr('codeid'); 
   		demo.showSwal(
   			swal({
   				title:  "Delete this Widget Code?",
   				type: 'warning',
   				showCancelButton: true,
   				confirmButtonClass: 'btn btn-success deleted',
   				cancelButtonClass: 'btn btn-danger closed',
   				confirmButtonText: 'Yes, delete it!',
   				buttonsStyling: false
   			}).then(function(result) {
   				if(result.value == true){
   					$("#counter"+codeid).remove();
   					swal({
   						title: 'Widget Code has been deleted.!',
   						type: 'success',
   						confirmButtonClass: "btn btn-success",
   						buttonsStyling: false
   					})
   			  
   					$.ajax({
   						url: 'delete/'+codeid,
   						type: 'GET',
   						success: function(data) {
   						},
   					}); 
   				}
   			}).catch(swal.noop)
   		);
   	});
   });
</script>
<!--End Start delete widget popup for counters by id-->	
<!-- Start counter-->
<script>
   function copyStringToClipboard (target) {
   	var str = document.getElementById(target).innerText;
   	// Create new element
   	var el = document.createElement('textarea');
   	// Set value (string to be copied)
   	el.value = str;
   	// Set non-editable to avoid focus and move outside of view
   	el.setAttribute('readonly', '');
   	el.style = {position: 'absolute', left: '-9999px'};
   	document.body.appendChild(el);
   	// Select text inside element
   	el.select();
   	// Copy text to clipboard
   	document.execCommand('copy');
   	// Remove temporary element
   	document.body.removeChild(el);
   }
</script>
<!-- End counter-->
@endsection