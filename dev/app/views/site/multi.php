<!-- 
<div class="container">
<h4 class="blue-gradient" title="<?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to..."><span><?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to...</span></h4>
	
	
			<h3 class="right blue-gradient" title='"<?php echo $event->title; ?>"'><span>"<?php echo $event->title; ?>"</span></h3>
</div>
 -->
 
 
 <?php if($multi_picked){	?>
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
                        <tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >Thanks, <?php echo $invitee->first_name; ?>. We got your vote!</h4>
									<!-- <h4>Check your email to download the details, or choose your calendar below!</h4> -->
								<!-- </div> -->
                                </center>
                            </td>
                        </tr>
 					</table>
                       <tr>
                       <tr>
            				<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/divider.png"> --> </div>
            				
        			   </tr>
						<tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >This is a Group event. </h4>
                                    <h4  >You can re-vote anytime before <?php echo $user->first_name; ?> books the event. </h4>
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
                    <tr >
                        <td width="20%" align="right" style=" color: #7d7d7d;  margin-top: 53px;">Time:</td>
                        

                        <td>
                            <table class="options" >
                            
                         <!--  <div class="divider"> -->
		<!-- <section class="options"> -->
			<?php
				foreach($times as $i=>$object){
				?>
				 <tr >
				 <td align="left">
					<div class="left multi">
						<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
							<!-- <input type="hidden" name="option<?=$i?>" value="3" /> -->
							<!-- <h2>Option <?=$i+1?></h2> -->
						
						
							<p1 style="font-size: 18px;  margin-top: 53px;" align="top"><?=$object['time']['date']?></p1>
							<p align="top"><?=$object['time']['time']?></p>
						</div>
					</div>
				</td>
				<td align="right" >
					<div class="right multi">
						<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
							<input type="hidden" name="option<?=$i?>" value="3" />
						
							<div class="selectors" >
								<div rel="0"  <?php if($multi_picked_array[$i]==0){ ?>class="ideal selected"<?php } ?> ><img width="66" height="59" src="<?=PATH?>/img/button-ideal.png" /></div>
								<div rel="1"  <?php if($multi_picked_array[$i]==1){ ?>class="okay selected"<?php } ?> ><img width="66" height="59" src="<?=PATH?>/img/button-okay.png" /></div>
								<div rel="2"   <?php if($multi_picked_array[$i]==2){ ?>class="cant selected"<?php } ?>><img width="66" height="59" src="<?=PATH?>/img/button-cant.png" /></div>
							</div>
						
						</div>
					</div>
				</td>
				</tr>
				<?php
				}
			?>
			
			</table>
	<!-- 	</section> -->
	<!-- </div> -->
                             
					<?php if($event->location!=""){	?>
                    <tr class="table_options_location">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Location:</td>

                        <td  ><a href="http://maps.google.com/?q=<?php echo $event->location; ?>" target="_blank"><?php echo $event->location; ?></a></td>
                    </tr>
					<?php } ?>
					
                    <tr class="table_options">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Attendees:</td>

                        <td ><p><?php echo $otherInvitee; ?></p></td>
                    </tr>
					<?php if($event->notes!=""){	?>
                    <tr class="table_options">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Note:</td>

                        <td  ><p><?php echo $event->notes; ?></p></td>
                    </tr>
                     <?php } ?>
                      
                  	
                  	
               <!--  </table> -->
 					
                    </table>
                    
				
                </center>
                
 
                
 </table>
 <!-- 
<div class="divider">
		<a class="vote" href="#" title="Re Vote!">Re Vote!</a>
	</div>
 -->
  	</center>
  	
		<tr  align="center" class="revote">
			<a class="revote" href="" align="middle" ><!-- <img src="<?=PATH?>/img/new/btn_change_preferences.png" alt="Submit Preferences"> --></a>
		</tr>
		<!-- 
<div>
		<img src="<?=PATH?>/img/new/ajax_loader.gif" > 
		</div>
 -->

	<!-- 
	<div  align="center" class="pre_loader">
<!~~ 
			<img class="pre_loader" src="<?=PATH?>/img/new/pre_loader.gif" /> 
 ~~>
		</div>
 -->

 		<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/divider.png"> --></div>
		<div class="divider">
   			<h5><?php echo $user->first_name; ?> uses Atlas to schedule meetings.</h5>
			<h5>And you can too! It's free!</h5>
		</div>
<!-- 
<div class="divider">
<?php if($multi_picked){	?>
	<h3 class="blue-gradient" title="We got it!"><span>We got it!</span></h3>
<?php } else { ?>
	<h4 class="blue-gradient" title="Choose the best time for you."></h4>
	<h4 class="blue-gradient" title="Once you choose, we'll notify <?php echo $user->first_name; ?> and you'll be all book."></h4>
<?php } ?>
</div>
 -->
 <table>
<?php } else { ?>
   <center>
  <table>
  <center>
                    <table>
                        <tr>
                            <td style="font-size: 30px; color: #3c76c3;">
                                <center>
                                <div class="container">
                                    <h4 class="blue-gradient" title="<?php echo $invitee->first_name; ?>, <?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to..."><span><?php echo $invitee->first_name; ?>, <?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to...</span></h4>
									<h3 class="right blue-gradient" style="padding-top: 10px;" >"<?php echo $event->title; ?>"</h3>
								</div>
                                </center>
                            </td>
                        </tr>
					</table>
                       <tr>
            				<div class="border_divider"><img src="http://getatlas.com/emails/img/divider.png"></div>
        			   </tr>
						<tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >This is a Group Event.</h4>
									<h4><?php echo $user->first_name; ?> needs your vote for each time.</h4>
								<!-- </div> -->
                                </center>
                            </td>
                        </tr>

 					<table>
 					<?php if($event->message!=""){	?>
                    <tr class="table_options_message">
                    
                        <td width="20%" align="right" style=" color: #7d7d7d; padding-top: 53px;""  > Message:</td>

                        <td   ><p> "<?php echo $event->message; ?>" - <?php echo $user->first_name; ?></p> </td>
                    </tr>
					<?php } ?>
                    <tr>
                        <td width="20%" align="right" style=" color: #7d7d7d;  margin-top: 53px;">Time:</td>
                        

                        <td>
                            <table class="options" >
                            
                         <!--  <div class="divider"> -->
		<!-- <section class="options"> -->
			<?php
				foreach($times as $i=>$object){
				?>
				 <tr >
				 <td align="left">
					<div class="left multi">
						<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
							<!-- <input type="hidden" name="option<?=$i?>" value="3" /> -->
							<!-- <h2>Option <?=$i+1?></h2> -->
						
						
							<p1><?=$object['time']['date']?></p1>
							<p align="top"><?=$object['time']['time']?></p>
						</div>
					</div>
				</td>
				<td align="right" >
					<div class="right multi">
						<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
							<input type="hidden" name="option<?=$i?>" value="3" />
					
							<div class="selectors" >
								<div rel="0" class="ideal" ><img width="66" height="59" src="<?=PATH?>/img/button-ideal.png" /></div>
								<div rel="1" class="okay"><img width="66" height="59" src="<?=PATH?>/img/button-okay.png" /></div>
								<div rel="2" class="cant"><img width="66" height="59" src="<?=PATH?>/img/button-cant.png" /></div>
							</div>
						
						</div>
					</div>
				</td>
				</tr>
				<?php
				}
			?>
			
			</table>
	<!-- 	</section> -->
	<!-- </div> -->
                             
					<?php if($event->location!=""){	?>
                    <tr class="table_options_location">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Location:</td>

                        <td  ><a href="http://maps.google.com/?q=<?php echo $event->location; ?>" target="_blank"><?php echo $event->location; ?></a></td>
                    </tr>
					<?php } ?>
                    <tr class="table_options">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Attendees:</td>
						<td ><p><?php echo $otherInvitee; ?></p></td>
<!-- 
                        <td style=" padding-left: 53px;">You and <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></td>
 -->
                    </tr>
					<?php if($event->notes!=""){	?>
                    <tr class="table_options">
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Note:</td>

                        <td  ><p><?php echo $event->notes; ?></p></td>
                    </tr>
                     <?php } ?>
                      
                  	
                  	
                </table>
 					
                   <!--  </table> -->
                    
				
                </center>
                
 
                
 </table>
 <!-- 
<div class="divider">
		<a class="vote" href="#" title="Re Vote!">Re Vote!</a>
	</div>
 -->
  	</center>
  	
		<tr  align="center" >
			<a class="vote" href="" align="middle"><img src="<?=PATH?>/img/new/btn_submit.png" alt="Submit Preferences"></a>
		</tr>
	<!-- 
	<div  align="center" class="pre_loader">
<!~~ 
			<img class="pre_loader" src="<?=PATH?>/img/new/pre_loader.gif" /> 
 ~~>
		</div>
 -->
 		<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/divider.png"> --></div>
		<div class="divider">
   			<h5><?php echo $user->first_name; ?> uses Atlas to schedule meetings.</h5>
			<h5>And you can too! It's free!</h5>
		</div>
<!-- 
<div class="divider">
<?php if($multi_picked){	?>
	<h3 class="blue-gradient" title="We got it!"><span>We got it!</span></h3>
<?php } else { ?>
	<h4 class="blue-gradient" title="Choose the best time for you."></h4>
	<h4 class="blue-gradient" title="Once you choose, we'll notify <?php echo $user->first_name; ?> and you'll be all book."></h4>
<?php } ?>
</div>
 -->
 <table>
 <?php } ?>
<!--   cellspacing="0" cellpadding="0" style="position: relative; width: 649px; table-layout: fixed; background-repeat: repeat; background-image: url(http://getatlas.com/emails/img/New/pattern.png); overflow: hidden; border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; -webkit-box-shadow: rgba(0,0,0,.2) 0 2px 4px; margin: 20px auto;"> -->
       <!-- 
 <tr>
            <td><img src="http://getatlas.com/emails/img/New/border_pattern.png"></td>
        </tr>
 -->

       <!-- 
 <tr>
            <td>
<!~~                 <img src="http://getatlas.com/emails/img/New/hunter.png" style="position: absolute; margin: 10px;"> ~~>

                <center>
                    <table>
                        <tr>
                            <td style="font-size: 30px; color: #3c76c3;">
                                <center>
                                <div class="container">
                                    <h4 class="blue-gradient" title="<?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to..."><span><?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to...</span></h4>
	
	
									<h3 class="right blue-gradient" title='"<?php echo $event->title; ?>"'><span>"<?php echo $event->title; ?>"</span></h3>
								</div>
                                </center>
                            </td>
                        </tr>
 -->

                        <!-- 
<tr>
                            <td style="font-size: 40px; color: #3c76c3;">
                                <center>
                                    <b>"Coffee Meeting"</b>
                                </center>
                            </td>
                        </tr>
 -->
                    <!-- 
</table>
                </center>
            </td>
        </tr>

        <tr>
            <td><img src="http://getatlas.com/emails/img/New/divider.png"></td>
        </tr>

        <tr>
            <td>
                <table width="95%" cellspacing="20" style="font-size: 18px;">
                    <tr>
                        <td width="20%" align="right" style="vertical-align: top; color: #7d7d7d;" valign="top">Hunter's Message:</td>

                        <td><i>"We should think about possibly adding another user to the metrics situation..."</i></td>
                    </tr>
<div class="divider">
		

                    <tr>
                        <td width="20%" align="right" style="vertical-align: top; color: #7d7d7d;" valign="top">Location:</td>

                        <td><a href="#74be16979710d4c4e7c6647856088456">820 Broadway, Santa Monica, CA 90401</a></td>
                    </tr>

                    <tr>
                        <td width="20%" align="right" style="vertical-align: top; color: #7d7d7d;" valign="top">Invitees:</td>

                        <td>You and Hunter G</td>
                    </tr>

                    <tr>
                        <td width="20%" align="right" style="vertical-align: top; color: #7d7d7d;" valign="top">Note:</td>

                        <td>We should think about possibly adding another user to the metrics situation...</td>
                    </tr>
                </table>
 -->
<!-- 
<div class="divider">
	<div class="table">
		<div class="gradtop">
			<img src="<?=PATH?>/img/mail.png" alt="RSVP" />
		</div>
		<div class="event">
			<p class="left">Event</p>
			<p class="right blue-gradient" title='"<?php echo $event->title; ?>"'><span>"<?php echo $event->title; ?>"</span></p>
			<img src="<?=PATH?>/img/divider-small.png" width="506" height="4" />
			<p class="left">Location</p>
			<p class="right small blue-gradient" title='<?php echo $event->location; ?>'><span><?php echo $event->location; ?></span></p>
			<div class="block"></div>
		</div>
	</div>
</div>
 -->
<!-- 
<?php if($multi_picked){	?>
	<div class="divider">
		<p class="confirm">You will be notified when <?php echo $user->first_name; ?> picks a time.</p>
	</div>
	<div class="divider">
		<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
	</div>
	<div class="divider">
		<h4 class="blue-gradient" title="You have picked your preferred times."><span>You have picked your preferred times.</span></h4>
		<p class="confirm">If you change your mind, feel free to re-vote!</p>
		<section class="options">
			<?php
				foreach($times as $i=>$object){
				?>
				<div class="center multi">
					<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
						<input type="hidden" name="option<?=$i?>" value="<?=$object['selected']?>" />
						<h2>Option <?=$i+1?></h2>
						<h3><?=$object['time']['date']?></h3>
						<p><?=$object['time']['time']?></p>
						<div class="selectors">
						<tr>
                                    <td style="vertical-align: top;" valign="top">Thursday, Feb 1<br>
                                    <span style="font-size: 14px;">3:15 pm - 4:30 pm PST</span></td>

                                    <td align="right"><a href="#74be16979710d4c4e7c6647856088456"><img src="http://getatlas.com/emails/img/New/confirm_btn.png" alt="Choose this time"></a></td>
                                </tr>

							<div rel="0" class="ideal <?=($object['selected']==0?"selected":"")?>"><img width="66" height="59" src="<?=PATH?>/img/button-ideal.png" /></div>
							<div rel="1" class="okay <?=($object['selected']==1?"selected":"")?>"><img width="66" height="59" src="<?=PATH?>/img/button-okay.png" /></div>
							<div rel="2" class="cant <?=($object['selected']==2?"selected":"")?>"><img width="66" height="59" src="<?=PATH?>/img/button-cant.png" /></div>
						</div>
					</div>
				</div>
				<?php
				}
			?>
		</section>
	</div>
	<div class="divider">
		<a class="vote" href="#" title="Re Vote!">Re Vote!</a>
	</div>
<?php } else { ?>
	<div class="divider">
		<h4 class="blue-gradient" title="<?php echo $user->first_name; ?> wants to know if you can make the event."><span><?php echo $user->first_name; ?> wants to know if you can make the event.</span></h4>
		<p class="confirm">Please indicate your preference for each time so <?php echo $user->first_name; ?> can book the optimal time.</p>
	</div>
	<div class="divider">
		<section class="options">
			<?php
				foreach($times as $i=>$object){
				?>
				<div class="center multi">
					<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
						<input type="hidden" name="option<?=$i?>" value="3" />
						<h2>Option <?=$i+1?></h2>
						<h3><?=$object['time']['date']?></h3>
						<p><?=$object['time']['time']?></p>
						<div class="selectors">
							<div rel="0" class="ideal"><img width="66" height="59" src="<?=PATH?>/img/button-ideal.png" /></div>
							<div rel="1" class="okay"><img width="66" height="59" src="<?=PATH?>/img/button-okay.png" /></div>
							<div rel="2" class="cant"><img width="66" height="59" src="<?=PATH?>/img/button-cant.png" /></div>
						</div>
					</div>
				</div>
				<?php
				}
			?>
		</section>
	</div>
	<div class="divider">
		<a class="vote" href="#" title="Vote!">Vote!</a>
	</div>
<?php } ?>
 -->