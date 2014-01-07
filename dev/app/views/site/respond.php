 <center>
  <table>
  <center>
                    <table>
                        <tr>
                            <td style="font-size: 30px; color: #3c76c3;">
                                <center>
                                <div class="container">
                                    <h4 class="blue-gradient" title="<?php echo $invitee->first_name; ?>, <?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to..."><span><?php echo $invitee->first_name; ?>, <?php echo $user->first_name; ?> <?php echo $user->last_name; ?> has invited you to...</span></h4>
									<h3 style="padding-top: 10px;" class="right blue-gradient" >"<?php echo $event->title; ?>"</h3>
								</div>
                                </center>
                            </td>
                        </tr>
					</table>
                       <tr>
            				<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/new/border_pattern.png"> --></div>
        			   </tr>
						<tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >This is a One-to-One event.<!-- <?php echo $web_item_user_id_picked; ?> --></h4>
									<h4>Book the event by choosing the best time for you.<!--  <?php echo $user->first_name; ?> and you'll be all book. --></h4>
								<!-- </div> -->
                                </center>
                            </td>
                        </tr>

 					<table>
 					<?php if($event->message!=""){	?>
                    <tr class="table_options_message">
                        <td width="20%" align="right" style=" color: #7d7d7d; padding-top: 53px;"  >Message:</td>

                        <td   > <p><?php echo $event->message; ?></p></td>
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
					<!-- 
<div class="left multi">
						<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
 -->
							<!-- <input type="hidden" name="option<?=$i?>" value="3" /> -->
							<!-- <h2>Option <?=$i+1?></h2> -->
						
						<div class="select_option" id="<?php echo $object['web_item_user_id']; ?>">
							<p1 style="font-size: 18px; " align="top"><?=$object['time']['date']?></p1>
							<p ><?=$object['time']['time']?></p>
						</div>
					</div>
				</td>
				<td style=" padding-left: 53px;  "  >
				<div class="right multi">
					<div class="single_option" >
					
						<!-- 
<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
							<input type="hidden" name="option<?=$i?>" value="3" />
 -->
					<div class="selectors_singles" >
							<div  class="select_option" id="<?php echo $object['web_item_user_id']; ?>" >
								<a   href="#" ><img  src="<?=PATH?>/img/new/btn_choose.png" /></a>
								<!-- 
<div rel="1" class="okay"><img width="66" height="59" src="<?=PATH?>/img/button-okay.png" /></div>
								<div rel="2" class="cant"><img width="66" height="59" src="<?=PATH?>/img/button-cant.png" /></div>
 -->
							</div>
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

                        <td><a href="http://maps.google.com/?q=<?php echo $event->location; ?>" target="_blank"><?php echo $event->location; ?></a></td>
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
                      
                  	
                  	
             <!--    </table> -->
 					
                    </table>
                    
				
                </center>
                
 
                
 </table>
 <!-- 
<div class="divider">
		<a class="vote" href="#" title="Re Vote!">Re Vote!</a>
	</div>
 -->
  	</center>
  	
		<tr  align="center" class="decline">
		<a class="decline" href="#" title="I can't make it" id="<?php echo $web_item_user_id; ?>"><!-- <img src="<?=PATH?>/img/new/btn_cant.png" > --></a>
<!-- 
			<a class="vote" href="#" align="middle"><img src="<?=PATH?>/img/new/btn_submit.png" alt="Submit Preferences"></a>
 -->
		</tr>
		
 		<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/new/border_pattern.png"> --></div>
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


<!-- 














<div class="divider">
	<section class="options">
		<?php
		foreach($times as $i=>$object){
			?>
			<div class="center">
				<div class="select_option" id="<?php echo $object['web_item_user_id']; ?>">
					<h2>Option <?=$i+1?></h2>
					<h3><?=$object['time']['date']?></h3>
					<p><?=$object['time']['time']?></p>
				</div>
			</div>
			<?php
		}
		?>
	</section>
</div>
<div class="divider">
	<a class="decline" href="#" title="Decline Event" id="<?php echo $web_item_user_id; ?>">Decline Event</a>
</div>
 -->