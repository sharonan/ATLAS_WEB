<div class="container">
	<h2>Event with <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
</div>
<div class="divider">
	<h3 class="blue-gradient" title="Meeting Invitation Sent to <?php echo $user->first_name; ?>!"><span>Meeting Invitation Sent to <?php echo $user->first_name; ?>!</span></h3>
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
			<p class="right small blue-gradient" title='<?=$count?> choice<?=($count===1?"":"s")?>'><span><?=$count?> choice<?=($count===1?"":"s")?></span></p>
			<div class="block"></div>
		</div>
	</div>
</div>
<div class="divider">
	<h2 class="space twohundredfiftypercent blue-gradient" title="What happens next?"><span>What happens next?</span></h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/1.png" width="47" height="47" title="1" />
</div>
<div class="divider">
	<p class="whatsnext "><?php echo $user->first_name; ?> will receive a mobile alert (iPhone/Android) and then <br />
		<span class="blue">confirm the final meeting time</span> or 
		<span class="blue">counter with additional times</span>
	</p>
	<div class="whatsnextimg">
		<img src="<?=PATH?>/img/whatsnext.png" width="973" height="554" />
		<p class="counteroffer">You'll receive an email with <?=$user->first_name?>'s counter offer times</p>
		<p class="confirmoffer">After you confirm the final meeting time, <?=$user->first_name?> will be alerted, and you'll be given calendar attachments.</p>
	</div>
</div>
