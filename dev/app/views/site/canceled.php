<center>
  <table>
  <center>
                    <table>
                        <tr>
                            <td style="font-size: 30px; color: #3c76c3;">
                                <center>
                                <div class="container">
                                <br></br>
                                 <!--    <h4 class="blue-gradient" title="<?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to..."><span><?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to...</span></h4> -->
									<h3 class="right blue-gradient" title='"<?php echo $event->title; ?>"'><span>"<?php echo $event->title; ?>"</span></h3>
								</div>
                                </center>
                            </td>
                        </tr>
                        </tr>
						<tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >Hello, <?php echo $invitee->first_name; ?>. This event has been cancelled.</h4>
									<!-- <h4>Check your email to download the details, or choose your calendar below!</h4> -->
								<!-- </div> -->
                                </center>
                            </td>
                        </tr>
 					</table>
                       <tr>
                       <div class="border_divider">
                      	<!--  <img src="<?=PATH?>/img/new/divider.png"> -->
                       </div>
            				
        			   </tr>
						<tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >This is a One-to-One event. </h4>
                                    <h4  >No further action is necessary.  </h4>
									<!-- <h4>Check your email to download the details, or choose your calendar below!</h4> -->
								<!-- </div> -->
                                </center>
                            </td>
                        </tr>

 					<table>
 					<?php if($event->message!=""){	?>
                    <tr class="table_options_message">
                        <td width="20%" align="right" style=" color: #7d7d7d; padding-top: 53px;""  >Message:</td>

                        <td ><p> "<?php echo $event->message; ?>" - <?php echo $user->first_name; ?></p> </td>
                    </tr>
					<?php } ?>
                   <!-- 
 <tr  >
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top:53px;">Time:</td>
                        

<!~~                         <td> ~~>
                           <!~~  <table class="options" > ~~>
                            
                         <!~~  <div class="divider"> ~~>
		<!~~ <section class="options"> ~~>
			<?php
				foreach($times as $i=>$object){
				?>
				 
				 <!~~ 
<?php if($pickedOrder==$i){ ?>
				<!~~  <img  src="<?=PATH?>/img/new/booked_rectangle.png" /> ~~>
					 <tr >
					 <!~~ background="<?=PATH?>/img/new/booked_rectangle.png" > ~~>
				 <?php } else { ?>
					  <tr>
				 <?php
					}
					?>
 ~~>
					<?php if($pickedOrder==$i){ ?>
				 <td class="options_booked">
					
						<div class="select_option" id="<?php echo $object['web_item_user_id']; ?>">
							<p1 ><?=$object['time']['date']?></p1>
							<p ><?=$object['time']['time']?></p>
						</div>
					<!~~ </div> ~~>
				</td>
				<?php } ?>
				<?php if($pickedOrder==$i){ ?>
				<td class="status_check">
					<!~~ <div class="single_option" > ~~>
					
							<div   ><!~~ 	<img src="<?=PATH?>/img/new/status_check.png" />  ~~></div>
								
						
						<!~~ </div> ~~>
					<!~~ </div> ~~>
				</td>
				<?php
				}
			?>
				
				<?php
				}
			?>
<!~~ 			</td> ~~>
			</tr>
			<!~~ </table> ~~>
	<!~~ 	</section> ~~>
	<!~~ </div> ~~>
 -->
	
	
	
                             
					<?php if($event->location!=""){	?>
                    <tr class="table_options_location">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Location:</td>

                        <td  ><a href="http://maps.google.com/?q=<?php echo $event->location; ?>" target="_blank"><?php echo $event->location; ?></a></td>
                    </tr>
					<?php } ?>
                    <!-- 
<tr class="table_options">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Attendees:</td>
						<td ><p><?php echo $otherInvitee; ?></p></td>
<!~~ 
                        <td style=" padding-left: 53px;">You and <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></td>
 ~~>
                    </tr>
 -->
					<?php if($event->notes!=""){	?>
                    <tr class="table_options">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Note:</td>

                        <td  ><p><?php echo $event->notes; ?></p></td>
                    </tr>
                     <?php } ?>
                       
                  	
                  	
               <!--  </table> -->
 					
                    </table>
                   
				
                </center>
               
		<!-- <tr  align="center" > -->
		<div class="divider">
		<a href="http://bit.ly/itunesatlasweb" target="_blank"><img src="<?=PATH?>/img/new/screenshot.png" align="center"></a>
		</div>
		</tr>
		 </table>
 
               <!-- 
 <tr style=" margin-top: 53px; ">
						<div class="container" style="font-size: 16px; color: #3c76c3; margin-top: 53px;">
							<h3 class="blue-gradient" >Click to add this to your calendar.</h3>
						</div>

 				</tr>

 -->

























<!-- 
<div class="divider">
	<div class="table">
		<div class="gradtop">
			Event Details
		</div>
		<div class="event">
			<p class="left">Event</p>
			<p class="right blue-gradient" title='"<?php echo $event->title; ?>"'><span>"<?php echo $event->title; ?>"</span></p>
			<img src="<?=PATH?>/img/divider-small.png" width="506" height="4" />
			<p class="left">Location</p>
			<p class="right small blue-gradient" title='<?php echo $event->location; ?>'><span><?php echo $event->location; ?></span></p>
			<img src="<?=PATH?>/img/divider-small.png" width="506" height="4" />
			<p class="left">When</p>
			<p class="right small blue-gradient" title='<?=$picked['time']." ".$picked['date']?>'><span><?=$picked['time']." ".$picked['date']?></span></p>
			<div class="block"></div>
		</div>
	</div>
</div>
 -->

<!-- 
<div class="divider">
	<section class="options add">
		<form name="icalDLform" id="icalDLform" method="post" action="<?=PATH?>/site/downloadical">
			<input type="hidden" id="title" name="title" value="<?php echo $event->title; ?> with <?php echo $user->first_name; ?> <?php echo $user->last_name; ?>" />
			<input type="hidden" id="location" name="location" value="<?php echo $event->location; ?>" />
			<input type="hidden" id="start" name="start" value="<?php echo $pickedEvent->start_datetime->iso; ?>" />
			<input type="hidden" id="duration" name="duration" value="<?php echo $event->duration; ?>" />
			<input type="hidden" id="desc" name="desc" value="<?php echo $event->message; ?>\n\n<?php echo $event->notes; ?>\n\n<?php echo $event->phone_number; ?>" />
			<input type="hidden" id="webitemid" name="webitemid" value="<?=$web_item_user_id?>" />
		</form>
		<form name="googleDLform" id="googleDLform" method="post" action="<?=PATH?>/site/downloadgoogle">
			<input type="hidden" id="titles" name="title" value="<?php echo $event->title; ?> with <?php echo $user->first_name; ?> <?php echo $user->last_name; ?>" />
			<input type="hidden" id="locations" name="location" value="<?php echo $event->location; ?>" />
			<input type="hidden" id="starts" name="start" value="<?php echo $pickedEvent->start_datetime->iso; ?>" />
			<input type="hidden" id="durations" name="duration" value="<?php echo $event->duration; ?>" />
			<input type="hidden" id="descs" name="desc" value="Message: <?php echo $event->message; ?>
 
Notes: <?php echo $event->notes; ?>
 
<?php echo $event->phone_number; ?>" />
			<input type="hidden" id="webitems" name="webitem" value="<?=$web_item_user_id?>" />
		</form>
		<a href="#" id="googleDL" target="_blank" class="addto google">Add to Google</a>
		<a href="#" id="icalDL" class="addto ical">Add to iCal</a>
		<a href="#" id="outlookDL" class="addto outlook">Add to Outlook</a>
	</section>
</div>
<tr  align="center" >
		<a class="cantMakeIt" href="#" title="I can't make it" id="<?php echo $web_item_user_id; ?>">I cant make it</a>
<!~~ 
			<a class="vote" href="#" align="middle"><img src="<?=PATH?>/img/new/btn_submit.png" alt="Submit Preferences"></a>
 ~~>
		</tr>
 -->
		