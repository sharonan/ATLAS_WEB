<div class="container">
	<h2>Invitation from <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
</div>
<div class="divider">
	<h3 class="blue-gradient" title="You have declined this event."><span>You have declined this event.</span></h3>
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