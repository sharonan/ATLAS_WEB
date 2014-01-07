<div class="container">
	<h2><?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h2>
</div>
<div class="divider">
	<img src="<?=PATH?>/img/divider.png" alt="divider" width="600" height="12" />
</div>
<div class="divider">
	<h3 class="blue-gradient smaller" title="Schedule a meeting with me in 60 seconds!"><span>Schedule a meeting with me in 60 seconds!</span></h3>
</div>

<div class="divider">
	<img class="thirdspace" src="<?=PATH?>/img/meeting-details.png" width="377" height="55" alt="Enter Meeting Details" />
	<form class="schedule">
		<input type="text" name="name" placeholder="Your Name" id="name" />
		<input type="text" name="email" placeholder="Your Email" id="email" />
		<input type="text" name="title" placeholder="Meeting Title" id="title" />
		<input type="text" name="message" placeholder="Message" id="message" />
		<div class="callmeet">
			<input type="text" name="phone" placeholder="Dial In Number" id="phone" />
			<input type="text" name="pin" placeholder="Pin / Ext" id="pin" />
			<input type="text" name="location" placeholder="Meeting Location" id="location" />
		</div>
		<fieldset>
			<input type="hidden" name="call" id="call" value="1" />
			<input type="hidden" name="meet" id="meet" value="0" />
			<img src="<?=PATH?>/img/input-slider.png" width="35" height="35" alt="Call or Meet" />
		</fieldset>
		<input type="hidden" name="atlasid" id="atlasid" value="<?php echo $user->objectId; ?>" />
	</form>
	<p class="info small">Atlas User? Sign in <a href="#">here</a>.</p>
</div>

<div class="divider">
	<img class="thirdspace" src="<?=PATH?>/img/select-dates.png" width="358" height="55" alt="Enter Meeting Details" />
</div>


<div class="divider">
	<p class="info">Select a date then select a start time.  You can select 3 options.</p>
	<section class="main">
		<div class="custom-calendar-wrap">
			<div id="custom-inner" class="custom-inner">
				<div class="leftbox">
					<div class="custom-header clearfix">
						<nav>
							<span id="custom-prev" class="custom-prev"></span>
								<h2 id="custom-month" class="custom-month"></h2>
								<h3 id="custom-year" class="custom-year"></h3>
							<span id="custom-next" class="custom-next"></span>
						</nav>
					</div>
					<div id="calendar" class="fc-calendar-container"></div>
				</div>
				<div class="rightbox">
					<div id="timepicker">
						<input type="hidden" id="select_date" name="select_date" value="" />
						<input type="hidden" id="day_1" name="day_1" value="" />
						<input type="hidden" id="time_1" name="time_1" value="" />
						<input type="hidden" id="day_2" name="day_2" value="" />
						<input type="hidden" id="time_2" name="time_2" value="" />
						<input type="hidden" id="day_3" name="day_3" value="" />
						<input type="hidden" id="time_3" name="time_3" value="" />
						<input type="hidden" id="year" name="year" value="<?php echo date("Y"); ?>" />
						<div class="header">
							<h2 class="custom-day">Pick a Day</h2>
							<h3 class="select-info">
								Time Zone: <?php echo $timezones; ?>
							</h3>
						</div>
						<div class="timevalue" id="timevalue_0900" data-time="0900" data-hour="9" data-hours="09" data-m="am">9am</div>
						<div class="timevalue" id="timevalue_1000" data-time="1000" data-hour="10" data-hours="10" data-m="am">10am</div>
						<div class="timevalue" id="timevalue_1100" data-time="1100" data-hour="11" data-hours="11" data-m="am">11am</div>
						<div class="timevalue" id="timevalue_1200" data-time="1200" data-hour="12" data-hours="12" data-m="pm">Noon</div>
						<div class="timevalue" id="timevalue_1300" data-time="1300" data-hour="1" data-hours="13" data-m="pm">1pm</div>
						<div class="timevalue" id="timevalue_1400" data-time="1400" data-hour="2" data-hours="14" data-m="pm">2pm</div>
						<div class="timevalue" id="timevalue_1500" data-time="1500" data-hour="3" data-hours="15" data-m="pm">3pm</div>
						<div class="timevalue" id="timevalue_1600" data-time="1600" data-hour="4" data-hours="16" data-m="pm">4pm</div>
						<div class="timevalue" id="timevalue_1700" data-time="1700" data-hour="5" data-hours="17" data-m="pm">5pm</div>
						<div class="timevalue" id="timevalue_1800" data-time="1800" data-hour="6" data-hours="18" data-m="pm">6pm</div>
						<div class="timevalue" id="timevalue_1900" data-time="1900" data-hour="7" data-hours="19" data-m="pm">7pm</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


<div class="divider">
	<p class="info">How long will the event be?</p>
	<input type="hidden" name="duration" id="duration" value="60" />
	<input type="hidden" name="durationtext" id="durationtext" value="1 hour" />
	<div class="containduration">
		<div class="bluebox"></div>
		<ul id="durationpicker">
			<li><a href="#" data-time="30">30 min</a></li>
			<li><a href="#" data-time="60" class="picked">1 hour</a></li>
			<li><a href="#" data-time="90">1.5 hours</a></li>
			<li><a href="#" data-time="120">2 hours</a></li>
			<li><a href="#" data-time="180">3 hours</a></li>
		</ul>
	</div>
</div>

<div class="divider">
	<p class="info">Here are the dates/times you’re suggesting.</p>
</div>
<div class="divider">
	<section class="options additems">
		<form method="post">
			<input type="hidden" name="option" id="option" value="0" />
			<input type="hidden" name="objectId" id="objectId" value="" />
			<input type="hidden" name="atlas_id" id="atlas_id" value="" />
			<input type="hidden" name="web_event_id" id="web_item_user_id" value="<?=$web_item_user_id?>" />
			<input type="hidden" name="web_item_id" id="web_item_id" value="<?=$web_item_id?>" />
		</form>
		<div class="centerpro">
			<div id="opt1">
				<div>
					<h2 class="active">Option 1</h2>
				</div>
				<div class="containitem"></div>
			</div>
		</div>
		<div class="centerpro mid">
			<div id="opt2">
				<div>
					<h2 class="active">Option 2</h2>
				</div>
				<div class="containitem"></div>
			</div>
		</div>
		<div class="centerpro">
			<div id="opt3">
				<div>
					<h2 class="active">Option 3</h2>
				</div>
				<div class="containitem"></div>
			</div>
		</div>
	</section>
</div>
<div class="divider">
	<a title="book" href="#" id="bookbutton"><img src="/img/button-book.png" width="316" height="63" alt="book" /></a>
	<p class="info small"><?php echo $user->first_name; ?> will be notified via Atlas Mobile.</p>
</div>