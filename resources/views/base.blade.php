<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <title>ABC</title>
      <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
      <link rel="icon" type="image/x-icon" href="https://actionbutton.voxara.net/public/images/favicon.ico">
      <!--     Fonts and icons     -->
      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
      <!-- CSS Files -->
      <link rel="stylesheet" href="{{ asset('css/material-dashboard.css') }}">
      <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script>
         $(document).ready(function(){
         	$('[data-toggle="tooltip"]').tooltip();
         });
      </script>
   </head>
   <body>
      <div class="brand-panel bg-header text-center p-4"><a href="<?= url('customers'); ?>"><img src="{{ asset('images/brand-logo.png') }}"></a></div>
      <div class="content">
         <div class="container-fluid">
            <div class="row top-button-panel py-4 align-items-center">
			   <div class="col-12 col-md-6">
			        <h4 class="text-uppercase m-0 p-0"><?php if(isset($pageTitle)){
						echo $pageTitle;
					} ?> 
					</h4>
			   </div>
               <div class="col-12 col-md-6 text-right">
                  <a id="refresh" href="{{Config::get('site_setting.site_url')}}update-buttons-summary?redirect={{  Request::url() }}" class="btn btn-success"> Refresh Data <i class="material-icons">refresh</i></a>
                  <?php  $status =  App\Http\Controllers\ActionButtonApiController::CountersStatus();
                     if($status == 0) { ?>
                  <a href="{{Config::get('site_setting.site_url')}}update-counters?redirect={{  Request::url() }}&status=1" class="btn btn-primary ml-2"> Run Counters/API <i class="material-icons">play_arrow</i></a>
                  <?php 
                     }
                     else { ?>
                  <a href="{{Config::get('site_setting.site_url')}}update-counters?redirect={{  Request::url() }}&status=0" class="btn btn-primary ml-2"> Pause Counters/API <i class="material-icons">play_arrow</i></a>
                  <?php } ?>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card card-stats">
                     <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                           <i class="material-icons">tag</i>
                        </div>
                        <p class="card-category"># of Buttons</p>
                        <h3 class="card-title mb-4">
                           @if($allbuttons[0]->total == "") 0 @else {{ $allbuttons[0]->total }} @endif
                        </h3>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card card-stats">
                     <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                           <i class="material-icons">visibility</i>
                        </div>
                        <p class="card-category">Buttons Seen</p>
                        <h3 class="card-title mb-4">@if($allbuttons[0]->seen == "") 0 @else {{ $allbuttons[0]->seen }} @endif</h3>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card card-stats">
                     <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                           <i class="material-icons">group_add</i>
                        </div>
                        <p class="card-category">New Opt-Ins Acquired</p>
                        <h3 class="card-title mb-4">@if($allbuttons[0]->optedIn == "") 0 @else {{ $allbuttons[0]->optedIn }} @endif</h3>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="card card-stats">
                     <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                           <i class="material-icons">fact_check</i>
                        </div>
                        <p class="card-category">Buttons Completed</p>
                        <h3 class="card-title mb-4">@if($allbuttons[0]->completed == "") 0 @else {{ $allbuttons[0]->completed }} @endif</h3>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="main-listing">
         @yield('main')
      </div>
	  
	  <script>
		$(document).ready(function () {
			$(window).on("resize", function (e) {
				checkScreenSize();
			});

			checkScreenSize();
			
			function checkScreenSize(){
				var newWindowWidth = $(window).width();
				if (newWindowWidth > 767) {
					 $(".clicking-td").click(function(){
				window.location = $(this).attr('href-link');
			});
				}
			}
		});
	  </script>
	  
	  
	  <script>
	  $(document).ready(function(){
			 
		 $("#refresh").click(function(){
			 var pageType = $(".container-fluid.panel.active").attr('id');
			 var redirectUrl = $(this).attr('href');
			 console.log(redirectUrl);
			 $(this).attr('href',redirectUrl+'?page='+pageType);
			
		 }); 
		  
	  });
	</script>	
	<?php 
	if(isset($_GET['page'])){
		 $type = $_GET['page'];
		?>
		<script>
		  $(document).ready(function(){
			  $('#listing').removeClass('active');
			  $('#addform').removeClass('active');
			  $('#editform').removeClass('active');
			  $('#listing').addClass('inactive ');
			  $('#addform').addClass('inactive ');
			  $('#editform').addClass('inactive ');
			  
			  
			  $("#<?php echo $type; ?>").removeClass('inactive');
			  $("#<?php echo $type; ?>").addClass('active');
		});
		</script>
		
	<?php }
	?>
	  
      <script>
        $(document).ready(function() {
			$().ready(function() {
				$sidebar = $('.sidebar');
				$sidebar_img_container = $sidebar.find('.sidebar-background');
				$full_page = $('.full-page');
				$sidebar_responsive = $('body > .navbar-collapse');
				window_width = $(window).width();
				fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();
         
				if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
					if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
						$('.fixed-plugin .dropdown').addClass('open');
					}
				}
         
				$('.fixed-plugin a').click(function(event) {
				// Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
					if ($(this).hasClass('switch-trigger')) {
						if (event.stopPropagation) {
							event.stopPropagation();
						} else if (window.event) {
							window.event.cancelBubble = true;
						}
					}
				});
         
				$('.fixed-plugin .active-color span').click(function() {
					$full_page_background = $('.full-page-background');
					$(this).siblings().removeClass('active');
					$(this).addClass('active');
					var new_color = $(this).data('color');
					if ($sidebar.length != 0) {
						$sidebar.attr('data-color', new_color);
					}
         
					if ($full_page.length != 0) {
						$full_page.attr('filter-color', new_color);
					}
         
					if ($sidebar_responsive.length != 0) {
						$sidebar_responsive.attr('data-color', new_color);
					}
				});
         
				$('.fixed-plugin .background-color .badge').click(function() {
					$(this).siblings().removeClass('active');
					$(this).addClass('active');
         
					var new_color = $(this).data('background-color');
         
					if ($sidebar.length != 0) {
						$sidebar.attr('data-background-color', new_color);
					}
				});
         
				$('.fixed-plugin .img-holder').click(function() {
					$full_page_background = $('.full-page-background');
         
					   $(this).parent('li').siblings().removeClass('active');
					   $(this).parent('li').addClass('active');
				 
         
               var new_image = $(this).find("img").attr('src');
         
               if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                 $sidebar_img_container.fadeOut('fast', function() {
                   $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                   $sidebar_img_container.fadeIn('fast');
                 });
               }
         
               if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                 var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
         
                 $full_page_background.fadeOut('fast', function() {
                   $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                   $full_page_background.fadeIn('fast');
                 });
               }
         
               if ($('.switch-sidebar-image input:checked').length == 0) {
                 var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
                 var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
         
                 $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                 $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
               }
         
               if ($sidebar_responsive.length != 0) {
                 $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
               }
             });
         
             $('.switch-sidebar-image input').change(function() {
               $full_page_background = $('.full-page-background');
         
               $input = $(this);
         
               if ($input.is(':checked')) {
                 if ($sidebar_img_container.length != 0) {
                   $sidebar_img_container.fadeIn('fast');
                   $sidebar.attr('data-image', '#');
                 }
         
                 if ($full_page_background.length != 0) {
                   $full_page_background.fadeIn('fast');
                   $full_page.attr('data-image', '#');
                 }
         
                 background_image = true;
               } else {
                 if ($sidebar_img_container.length != 0) {
                   $sidebar.removeAttr('data-image');
                   $sidebar_img_container.fadeOut('fast');
                 }
         
                 if ($full_page_background.length != 0) {
                   $full_page.removeAttr('data-image', '#');
                   $full_page_background.fadeOut('fast');
                 }
         
                 background_image = false;
               }
             });
         
             $('.switch-sidebar-mini input').change(function() {
               $body = $('body');
         
               $input = $(this);
         
               if (md.misc.sidebar_mini_active == true) {
                 $('body').removeClass('sidebar-mini');
                 md.misc.sidebar_mini_active = false;
         
                 if ($(".sidebar").length != 0) {
                   var ps = new PerfectScrollbar('.sidebar');
                 }
                 if ($(".sidebar-wrapper").length != 0) {
                   var ps1 = new PerfectScrollbar('.sidebar-wrapper');
                 }
                 if ($(".main-panel").length != 0) {
                   var ps2 = new PerfectScrollbar('.main-panel');
                 }
                 if ($(".main").length != 0) {
                   var ps3 = new PerfectScrollbar('main');
                 }
         
               } else {
         
                 if ($(".sidebar").length != 0) {
                   var ps = new PerfectScrollbar('.sidebar');
                   ps.destroy();
                 }
                 if ($(".sidebar-wrapper").length != 0) {
                   var ps1 = new PerfectScrollbar('.sidebar-wrapper');
                   ps1.destroy();
                 }
                 if ($(".main-panel").length != 0) {
                   var ps2 = new PerfectScrollbar('.main-panel');
                   ps2.destroy();
                 }
                 if ($(".main").length != 0) {
                   var ps3 = new PerfectScrollbar('main');
                   ps3.destroy();
                 }
         
         
                 setTimeout(function() {
                   $('body').addClass('sidebar-mini');
         
                   md.misc.sidebar_mini_active = true;
                 }, 300);
               }
         
               // we simulate the window Resize so the charts will get updated in realtime.
               var simulateWindowResize = setInterval(function() {
                 window.dispatchEvent(new Event('resize'));
               }, 180);
         
               // we stop the simulation of Window Resize after the animations are completed
               setTimeout(function() {
                 clearInterval(simulateWindowResize);
               }, 1000);
         
             });
           });
         });
      </script>
      <script>
         $(document).ready(function() {
           // Javascript method's body can be found in assets/js/demos.js
           md.initDashboardPageCharts();
         
           md.initVectorMap();
         
         });
      </script>
      <script>
         $(document).ready(function() {
         $("#addcustomer").click(function(){
         $("#listing").addClass('inactive');
         $("#listing").removeClass('active');
         $("#addform").removeClass('inactive');
         $("#addform").addClass('active');
         });
         $(".back").click(function(){
         $("#listing").addClass('active');
         $("#listing").removeClass('inactive');
         $("#addform").removeClass('active');
         $("#addform").addClass('inactive');
         $("#editform").removeClass('active');
         $("#editform").addClass('inactive');
         
         });
         
           $('#datatables').DataTable({
             "pagingType": "full_numbers",
             "lengthMenu": [
               [10, 25,50, -1],
               [10, 25,50, "All"]
             ],
             responsive: true,
             language: {
               search: "",
               searchPlaceholder: "Search records",
             }
           });
         
           var table = $('#datatables').DataTable();
           
         
           // Edit record
         
           table.on('click', '.edit', function() {
             $tr = $(this).closest('tr');
			 
			
			 var data = table.row($tr).data();
			 var customerId =  $(this).attr('customer-id');
			 var customerName =  $(this).attr('customer-name');
			 var customerUrl = $(this).attr('customer-urls');
			 
			 $("#editform #editname").val(customerName);
			 
			 let text = "How are you doing today?";
			 const urlArray = customerUrl.split(",");
			 
			 for(var i =0; i < urlArray.length; i++){
				 
				 if(i == 0){
					 $("#firsturl").val(urlArray[i]);
				 }
				 else {
					  newRowAdd =
					'<div id="row"> <div class="input-group">' +
					'<input type="text"  name="urls[]"  value="'+urlArray[i]+'" class="form-control"> <div class="input-group-prepend">' +
					'<button class="btn btn-danger btn-sm px-3" id="DeleteRow" type="button">' +
					'<i class="material-icons">delete</i></button> </div>' +
					'</div> </div>';
	 
					$('#urlEdit').append(newRowAdd);
				 }
			 }
			 //$("#editform #editurl").val(customerUrl);
			 
			 
			 $("#editform form").attr("action","customers/"+customerId);
			 $("#listing").addClass('inactive');
			 $("#listing").removeClass('active');
			 $("#editform").removeClass('inactive');
			 $("#editform").addClass('active'); 
         });
		 
		 
		  table.on('click', '.editproject', function() {
             $tr = $(this).closest('tr');
			 
			 var projectId =  $(this).attr('project-id');
			 var projectName = $(this).attr('project-name');
			 var description = $(this).attr('project-description');
			 $("#editform #editname").val(projectName);
			 $("#editform #description").val(description);
			 $("#editform form").attr("action","/public/index.php/projects/"+projectId);
			 $("#listing").addClass('inactive');
			 $("#listing").removeClass('active');
			 $("#editform").removeClass('inactive');
			 $("#editform").addClass('active'); 
         });
		 
         table.on('click', '.remove', function(e) {
         var userURL = $(this).data('url');
		 var usertrid = $(this).data('trid');
         var deleteText = $(this).data('text'); 		 
         var dataName = $(this).data('name'); 
         demo.showSwal(
			swal({
				title:  dataName,
				text: 'Delete this '+deleteText+'?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonClass: 'btn btn-success deleted',
				cancelButtonClass: 'btn btn-danger closed',
				confirmButtonText: 'Yes, delete it!',
             buttonsStyling: false
           }).then(function(result) {
			   
         if(result.value == true){
            
            $("#"+usertrid).remove();
			swal({
				title: 'Deleted!',
         		text: deleteText+' has been deleted.',
         		type: 'success',
         		confirmButtonClass: "btn btn-success",
         		buttonsStyling: false
         	})
			var token = $("meta[name='csrf-token']").attr("content");
			$.ajax({
                url: userURL,
                type: 'DELETE',
				data: {
						"_token": token,
				},
                success: function(data) {
                },
         	});
        }
     }).catch(swal.noop)
   );
  });
 });
</script>
      <!--   Core JS Files   -->
      <script src="{{ asset('js/jquery.min.js') }}"></script> 
      <script src="{{ asset('js/popper.min.js') }}"></script> 
      <script src="{{ asset('js/bootstrap-material-design.min.js') }}"></script>
      <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script> 
      <!-- Plugin for the momentJs  -->
      <script src="{{ asset('js/moment.min.js') }}"></script>  
      <!--  Plugin for Sweet Alert -->
      <script src="{{ asset('js/sweetalert2.js') }}"></script> 
      <!-- Forms Validations Plugin -->
      <script src="{{ asset('js/jquery.validate.min.js') }}"></script>  
      <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
      <script src="{{ asset('js/jquery.bootstrap-wizard.js') }}"></script> 
      <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
      <script src="{{ asset('js/bootstrap-selectpicker.js') }}"></script> 
      <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
      <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script> 
      <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
      <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 
      <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
      <script src="{{ asset('js/bootstrap-tagsinput.js') }}"></script> 
      <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
      <script src="{{ asset('js/jasny-bootstrap.min.js') }}"></script> 
      <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
      <script src="{{ asset('js/fullcalendar.min.js') }}"></script> 
      <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
      <script src="{{ asset('js/jquery-jvectormap.js') }}"></script> 
      <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
      <script src="{{ asset('js/nouislider.min.js') }}"></script> 
      <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
      <!-- Library for adding dinamically elements -->
      <script src="{{ asset('js/arrive.min.js') }}"></script> 
      <!--  Google Maps Plugin    -->
      <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
      <!-- Chartist JS -->
      <script src="{{ asset('js/chartist.min.js') }}"></script> 
      <!--  Notifications Plugin    -->
      <script src="{{ asset('js/bootstrap-notify.js') }}"></script> 
      <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
      <script src="{{ asset('js/material-dashboard.js') }}" type="text/javascript"></script> 
      <!-- Material Dashboard DEMO methods, don't include it in your project! -->
      <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
   </body>
</html>