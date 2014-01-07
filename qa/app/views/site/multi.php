<div class="container">
	<h2>Invitation from <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
</div>
<div class="divider">
<?php if($multi_picked){	?>
	<h3 class="blue-gradient" title="We got it!"><span>We got it!</span></h3>
<?php } else { ?>
	<h3 class="blue-gradient" title="Please RSVP!"><span>Please RSVP!</span></h3>
<?php } ?>
</div>
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