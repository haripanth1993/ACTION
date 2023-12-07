@extends('base', ['title' => 'Projects'])
@section('main')
<div class="container-fluid">
   @if(session()->get('success'))
   <div class="alert alert-success col-6 m-auto"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="material-icons">close</i></button><span> {{ session()->get('success') }} </span></div>
   @endif
   <div class="row">
      <div class="col-md-12">
         <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
               <div class="card-icon">
                  <i class="material-icons"> category </i>
               </div>
               <h4 class="card-title">Get counter for customer {{ $customerName }}</h4>
            </div>
            <div class="card-body ">
               <div class="text-right top-button-panel">
                  <a  href="<?= url('customers'); ?>" class="btn btn-primary back">
                     Back to all customers 
                     <div class="ripple-container"></div>
                  </a>
               </div>
               <form method="post" action="<?= url('customers'); ?>/widgetspost" enctype='multipart/form-data'>
                  @csrf
                  <div class="row">
                     <label class="col-md-3 col-form-label">Customer ID: </label>
                     <div class="col-md-9">
                        <div class="form-group has-default">
                           <input type="text" name="buttoncode" value="{{ $customerId }}" class="pointer-none form-control"> 
                        </div>
                     </div>
                  </div>
                  <div class="row">
						<label class="col-md-3 col-form-label">Counter Data Point: </label>
						<div class="col-md-9">
							<div class="form-group">
								<select class="selectpicker mw-100" name="point" data-style="select-with-transition" data-size="7">
									<option value="seen"  selected="">Views</option>
									<option value="initiated">Interactions</option>
									<option value="optedIn">Opt-Ins</option>
									<option value="completed">Completions</option>
									<option value="DollarsRaised">Customer Dollars Raised</option>
									<option value="AverageDonation">Customer Average Donation</option>
								</select>
							</div>
						</div>
                  </div>
                  <div class="row">
                    <label class="col-md-3 col-form-label">Headline:</label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" maxlength="20" name="headline" class="form-control">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Text: </label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <input type="text" maxlength="100" name="text" class="form-control">
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Icon: </label>
                     <div class="col-md-9">
                        <div class="form-group">
                           <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                              <div class="fileinput-new thumbnail">
                                 <img src="https://new-actionbutton.voxara.net/public/images/placeholder-img.jpg" alt="...">
                              </div>
                              <div class="fileinput-preview fileinput-exists thumbnail"></div>
                              <div>
                                 <span class="btn btn-rose btn-round btn-file">
                                 <span class="fileinput-new">Select image</span>
                                 <span class="fileinput-exists">Change</span>
                                 <input type="file" name="icon" />
                                 </span>
                                 <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="card-footer w-100 ">
                     <div class="row w-100">
                        <div class="col-md-12 text-right">
                           <button type="submit" class="btn btn-fill btn-rose">Generate Counter</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <div class="response">
      <?php 
         if(session()->get('getresponse')){
         	$res = session()->get('getresponse');
         	$headline = $res['headline'];
         	$text = $res['text'];
         	$icon = $res['imageName'];
         	$point = $res['point'];
         	$buttoncode = $res['buttoncode']; 
         ?>
      <?php 
         if($point == "completed"){
         	$Name = "Completions";
         }
         if($point == "optedIn"){
         	$Name = "Opt-Ins";
         }
         if($point == "seen"){
         	$Name = "Views";
         }
         if($point == "initiated"){
         	$Name = "Interactions";
         }
		 if($point == "amount"){
			$Name = "Customer Dollars Raised";
		}
		if($point == "ab"){
			$Name = "Customer Average Donation";
		}
         ?>
      <div class="row m-0" id="widget">
         <div class="col-md-6 mb-4">
            <div class="response-panel">
               <h3>Widget Code</h3>
               <div class="sample-code  bg-light position-relative">
                  <button type="button" onclick="CopyToClipboard('div1')" data-target="accbtn1"  id="button1" class="float-right btn btn-success btn-sm text-uppercase m-1">
                     Copy to clipboard
                     <div class="ripple-container"></div>
                  </button>
                  <div class="copy-code" id="div1">
<xmp id="myInput">
<!--  Counter Widget --> 
<div class="counter-widget"> 
<?php if($icon != "") { ?> 
<img src="https://new-actionbutton.voxara.net/public/icons/<?php echo $icon; ?>">
<?php } ?>
<h2><span id="{{$buttoncode}}{{ $point }}" dataid="{{$buttoncode}}" datapoint="{{ $point }}"></span></h2>
<?php if($headline != "") { ?>
<h3><?php echo $headline; ?></h3>
<?php } ?>
<?php if($text != "") { ?>
<p><?php echo $text; ?></p>
<?php } ?>
<link rel="stylesheet" href="https://new-actionbutton.voxara.net/public/css/counter-widget.css">
<script src="https://new-actionbutton.voxara.net/js/customer-counter-widgets.js?{{$buttoncode}}{{ $point }}"></script>
</div>
<!-- end counter widget -->
</xmp>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-6 mb-4">
            <div class="response-panel">
               <h3>Preview </h3>
               <div class="sample-code  bg-light">
                  <div class="counter-widget">
                     <?php if($icon != "") { ?>
                     <img src="{{Config::get('site_setting.site_url')}}icons/<?php echo $icon; ?>">
                     <?php } ?>
					 <h2><span id="counterid{{ $point }}" dataid="{{$buttoncode}}" datapoint="{{ $point }}"></span></h2>
                     <?php if($headline != "") { ?>
                     <h3><?php echo $headline; ?></h3>
                     <?php } ?>
                     
                     <?php if($text != "") { ?>
                     <p><?php echo $text; ?></p>
                     <?php } ?>
                     <script src="https://new-actionbutton.voxara.net/js/admin-customer-counter-widgets.js?counterid{{ $point }}"></script>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
   </div>
</div>
<link rel="stylesheet" href="{{Config::get('site_setting.site_url')}}css/counter-widget.css">
<script>
	function CopyToClipboard(containerid) {
		if (document.selection) {
			var range = document.body.createTextRange();
			range.moveToElementText(document.getElementById(containerid));
			range.select().createTextRange();
			document.execCommand("copy");
		} else if (window.getSelection) {
			var range = document.createRange();
			range.selectNode(document.getElementById(containerid));
			window.getSelection().addRange(range);
			document.execCommand("copy");
		}
    }
</script>
@endsection