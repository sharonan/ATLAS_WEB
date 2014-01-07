<div class="container">
	<h2>Event with <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
</div>
<div class="divider">
	<h3 class="blue-gradient" title="Your meeting is all booked!"><span>Your meeting is all booked!</span></h3>
</div>
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
<div class="divider">
	<h2 class="space">Quickly add this meeting to your calendar.</h2>
</div>
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