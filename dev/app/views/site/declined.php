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
                                    <h4  >You have declined this event.</h4>
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
                                  <!--   <h4  >This is a One-to-One event. </h4> -->
                                    <h5  >No further action is necessary.  </h5>
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
