 <center>
  <table>
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

                       <tr>
            				<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/new/border_pattern.png"> --></div>
        			   </tr>
						<tr>
                            <td style="color: #3c76c3;">
                                <center>
                                <!-- <div class="container"> -->
                                    <h4  >Choose the best time for you.</h4>
									<h4>Once you choose,we'll notify <?php echo $user->first_name; ?> and you'll be all book.</h4>
								<!-- </div> -->
                                </center>
                            </td>
                        </tr>

 					<table>
                    <tr>
                        <td width="20%" align="right" style=" color: #7d7d7d; padding-top: 53px;""  ><?php echo $user->first_name; ?>'s Message:</td>

                        <td   style=" padding-left: 53px; "> <?php echo $event->message; ?></td>
                    </tr>

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
				 <td style=" padding-left: 53px; padding-bottom: 53px;">
					<!-- 
<div class="left multi">
						<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
 -->
							<!-- <input type="hidden" name="option<?=$i?>" value="3" /> -->
							<!-- <h2>Option <?=$i+1?></h2> -->
						
						<div class="select_option" id="<?php echo $object['web_item_user_id']; ?>">
							<h3 align="top"><?=$object['time']['date']?></h3>
							<p align="top"><?=$object['time']['time']?></p>
						</div>
					</div>
				</td>
				<td style=" padding-left: 53px;  vertical-align:middle;"  >
					<div class="single_option" >
						<!-- 
<div class="multi_option" id="<?php echo $object['web_item_user_id']; ?>">
							<input type="hidden" name="option<?=$i?>" value="3" />
 -->
					
							<div class="select_option" id="<?php echo $object['web_item_user_id']; ?>" style=" vertical-align:middle;">
								<a  href="#"><img  src="<?=PATH?>/img/new/btn_choose.png" /></a>
								<!-- 
<div rel="1" class="okay"><img width="66" height="59" src="<?=PATH?>/img/button-okay.png" /></div>
								<div rel="2" class="cant"><img width="66" height="59" src="<?=PATH?>/img/button-cant.png" /></div>
 -->
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
                             

                    <tr>
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Location:</td>

                        <td  style=" padding-left: 53px;"><a href="#"><?php echo $event->location; ?></a></td>
                    </tr>

                    <tr>
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Invitees:</td>

                        <td style=" padding-left: 53px;">You and <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></td>
                    </tr>

                    <tr>
                        <td width="20%" align="right" style=" color: #7d7d7d;  padding-top: 53px;">Note:</td>

                        <td style=" padding-left: 53px; " ><?php echo $event->notes; ?></td>
                    </tr>
                     
                      
                  	
                  	
                </table>
 					
                    </table>
                    
				
                </center>
                
 
                
 </table>
 <!-- 
<div class="divider">
		<a class="vote" href="#" title="Re Vote!">Re Vote!</a>
	</div>
 -->
  	</center>
  	
		<tr  align="center" >
		<a class="decline" href="#" title="I can't make it" id="<?php echo $web_item_user_id; ?>">I cant make it</a>
<!-- 
			<a class="vote" href="#" align="middle"><img src="<?=PATH?>/img/new/btn_submit.png" alt="Submit Preferences"></a>
 -->
		</tr>
		
 		<div class="border_divider"><!-- <img src="http://getatlas.com/emails/img/new/border_pattern.png"> --></div>
		<div class="divider">
   			<h4><?php echo $user->first_name; ?> uses Atlas to schedule meetings</h4>
			<h4>And you can too! it's free!</h4>
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