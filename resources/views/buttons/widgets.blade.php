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
               <h4 class="card-title">Get counter for button  </h4>
            </div>
            <div class="card-body">
               <div class="text-right top-button-panel">
                  <a  href="<?= url('buttons'); ?>/{{ $buttons[0]->project_id }}"  class="btn btn-primary back">
                     Back to buttons
                     <div class="ripple-container"></div>
                  </a>
               </div>
				

               <form method="post" action="<?= url('buttons'); ?>/widgetspost" enctype='multipart/form-data'>
                  @csrf
                  <div class="row">
                     <label class="col-md-3 col-form-label">Button Code:</label>
                     <div class="col-md-9">
                        <div class="form-group has-default">
                           <input type="text" name="buttoncode" value="{{ $buttons[0]->button_code }}" class="pointer-none form-control"> 
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <label class="col-md-3 col-form-label">Counter Data Point:</label>
					 <div class="col-md-9">
                        <div class="form-group">
						   
                           <select class="selectpicker mw-100" name="point" data-style="select-with-transition" data-size="7">
                             <?php   foreach($metaValue as $Value){ if($Value->type == "Poll") {?>
							  <option value="seenPoll"  selected="">Poll Action Seen</option>
							  <option value="completedPoll">Poll Action Completed</option>
							 <?php } 
							  if($Value->type == "Speedometer") {?>
							  <option value="seenSpeedometer"  selected="">Speedometer Action Seen</option>
							  <option value="completedSpeedometer">Speedometer Completed</option>
							 <?php }
							 
							  if($Value->type == "Quiz") {?>
							  <option value="seenQuiz"  selected="">Quiz Action Seen</option>
							  <option value="completedQuiz">Quiz Action Completed</option>
							 <?php }
							 }?>
							  <option value="seenAll"  selected="">All Actions Seen</option>
							  <option value="completedAll">All Actions Completed</option>
							  
							  <option value="initiated">Interactions</option> 
							  <option value="optedIn">Opt-Ins</option>
							  
							  <?php 
							  foreach($metaValue as $Value){  
							 if($Value->type == "Donation") { ?>
								  <option value="DollarsRaised">Dollars Raised</option>
								  <option value="DonationAverage">Average Donation</option>
							  <?php } ?>
							  <?php if($Value->type == "SentimentPoll") { ?>
								  <option value="Sentiment0">Quartile 1 as Number</option> 
								  <option value="SentimentPercent0">Quartile 1 as Percent</option> 
								  <option value="Sentiment1">Quartile 2 as Number</option>
								  <option value="SentimentPercent1">Quartile 2 as Percent</option>
								  <option value="Sentiment2">Quartile 3 as Number</option>
								  <option value="SentimentPercent2">Quartile 3 as Percent</option>
								  <option value="Sentiment3">Quartile 4 as Number</option>
								  <option value="SentimentPercent3">Quartile 4 as Percent</option>
								  <option value="SentimentTop">Top half (Quartile 1&2) as number</option>
								  <option value="SentimentTopPercent">Top half (Quartile 1&2) as percent</option>
								  <option value="SentimentBottom">Bottom half (Quartile 3&4) as number</option>
								  <option value="SentimentBottomPercent">Bottom half (Quartile 3&4) as percent</option>
							  
							   <?php } ?>
							   <?php if($Value->type == "Speedometer") { ?>
								  <option value="Speedometer0">Quartile 1 as Number</option> 
								  <option value="SpeedometerPercent0">Quartile 1 as Percent</option> 
								  <option value="Speedometer1">Quartile 2 as Number</option>
								  <option value="SpeedometerPercent1">Quartile 2 as Percent</option>
								  <option value="Speedometer2">Quartile 3 as Number</option>
								  <option value="SpeedometerPercent2">Quartile 3 as Percent</option>
								  <option value="Speedometer3">Quartile 4 as Number</option>
								  <option value="SpeedometerPercent3">Quartile 4 as Percent</option>
								  <option value="SpeedometerTop">Top half (Quartile 1&2) as number</option>
								  <option value="SpeedometerTopPercent">Top half (Quartile 1&2) as percent</option>
								  <option value="SpeedometerBottom">Bottom half (Quartile 3&4) as number</option>
								  <option value="SpeedometerBottomPercent">Bottom half (Quartile 3&4) as percent</option>
							  
							   <?php }
									if($Value->type == "Quiz") { 
										$Answers = json_decode($Value->answers_array);
										$temp = 0;
										foreach($Answers as $Ans){
										?>
									  <option value="Quiz{{ $temp }}">Answer  {{ $Ans->text }} as #</option> 
									  <option value="QuizPercent{{ $temp }}">Answer  {{ $Ans->text }} as %</option> 
										 <?php $temp++; } ?>
									  <option value="QuizRight">Right Answer as number</option>
									  <option value="QuizRightPercent">Right Answer as % of all answers</option>
									  <option value="QuizWrong">Wrong Answer as number</option>
									  <option value="QuizWrongPercent">Wrong Answer as % of all answers</option>
							  
								<?php   } ?> 
								<?php if($Value->type == "Poll") { 
									$Answers = json_decode($Value->answers_array);
									$temp = 0;
									foreach($Answers as $Ans){
									?>
										<option value="Poll{{ $temp }}">Answer  {{ $Ans->text }} as #</option> 
										<option value="PollPercent{{ $temp }}">Answer  {{ $Ans->text }} as %</option> 
								 <?php $temp++; }

							   
							  } } ?>
							   
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
                                 <img src="{{Config::get('site_setting.site_url')}}images/placeholder-img.jpg" alt="...">
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
<img src="{{Config::get('site_setting.site_url')}}icons/<?php echo $icon; ?>">
<?php } $buttonName = str_replace("==", "",$buttoncode);
 $buttonName = str_replace("=", "",$buttonName);  ?>
<h2><span id="{{$buttonName}}{{ $point }}" dataid="{{$buttoncode}}" datapoint="{{ $point }}"></span></h2>
<?php if($headline != "") { ?>
<h3><?php echo $headline; ?></h3>
<?php } 
?>
<?php if($text != "") { ?>
<p><?php echo $text; ?></p>
<?php } ?>
<link rel="stylesheet" href="{{Config::get('site_setting.site_url')}}css/counter-widget.css">
 <script src="https://new-actionbutton.voxara.net/js/counter-widgets.js?{{$buttonName}}{{ $point }}"></script>
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
                     <?php } $buttonName = str_replace("==", "",$buttoncode);
 $buttonName = str_replace("=", "",$buttonName); 					 ?>
					 <h2><span id="{{$buttonName}}{{ $point }}" dataid="{{$buttoncode}}" datapoint="{{ $point }}"></span></h2>
                     <?php if($headline != "") { ?>
                     <h3><?php echo $headline; ?></h3>
                     <?php }  
                        
                        ?>
                     
                     <?php if($text != "") { ?>
                     <p><?php echo $text; ?></p>
                     <?php } ?>
                     <script src="https://new-actionbutton.voxara.net/js/admin-counter-widgets.js?{{$buttonName}}{{ $point }}"></script>
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