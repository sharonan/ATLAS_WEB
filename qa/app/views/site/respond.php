<div class="container">
	<h2><?php echo $user->first_name; ?> <?php echo $user->last_name; ?>has invited you to...</h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" /> 
</div>
<div class="divider">
	<h3 class="blue-gradient" title="Please RSVP!"><span>Please RSVP!</span></h3>
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
<div class="divider">
	<?php if($counter){ ?>
		<h4 class="blue-gradient" title="<?php echo $user->first_name; ?> has sent you a counter offer."><span><?php echo $user->first_name; ?>  has sent you a counter offer.</span></h4>
	<?php } else { ?>
		<h4 class="blue-gradient" title="<?php echo $user->first_name; ?> has left the time &amp; date to you."><span><?php echo $user->first_name; ?> has left the time &amp; date to you.</span></h4>
	<?php } ?>
	<p class="confirm">Please confirm one of the following options.</p>
</div>
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