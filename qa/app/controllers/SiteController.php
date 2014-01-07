<?php
class SiteController extends Controller {

	function homepage(){
		$this->view('homepage');
		$this->set('header', 2);
		$this->set('hidepro', true);
		$this->set('nofooter', true);
		$this->set('nospace', true);
	}

	function index($input = false){
		$this->homepage();
		return false;
	}

	function verify($input = false){
		if(!$input) {
			$this->homepage();
			return false;
		}
		$email = $this->curlobject(array('email_address_id' => $input), VERIFYEMAIL, array('limit'=>1), false);
		$this->putcurlobject($email->objectId, array('is_verified'=>true), VERIFYEMAIL);
		$this->pushNotificationVerifyEmail($email->atlas_id);

		$user	= $this->curlobject(array('objectId'=>$email->atlas_id),	USER);

		$vars = array(
			'env'			=> DIRECTORY,
			'user_picture'	=> $user->picture,
			'user_firstname'=> $user->first_name,
			'datetime_created'=> date("F d, Y"),
		);

		if($email->email_address === $user->email){
			$vars = array(
			    'env'			=> DIRECTORY,
			    'user_picture'	=> $user->picture->url,
			    'user_firstname'=> $user->first_name,
			    'datetime_created'=> date("F d, Y"),
			);
			$message = $this->mailitem($vars);
			$insert = array(
			    'body'		=> $message,
			    'from'		=> 'team@getatlas.com',
			    'from_name'	=> 'Team Atlas',
			    'is_sent'	=> false,
			    'is_pending'=> false,
			    'has_error'	=> false,
			    'subject'	=> 'Welcome to Atlas',
			    'to'		=> $email->email_address
			);
			$this->postcurlobject($insert, 'https://api.parse.com/1/classes/outbound_email');
		}

		$this->set('user', $user);
	}

	function pro($input = false){
		$user	= $this->curlobject(array('site_name' => $input),	USER, array('limit'=>1), false);
		if(!$user) {
			$this->homepage();
			return false;
		}

		$timezones = '<select name="userTimeZone" id="userTimeZone">';
		$timezones .= '
			<option value="America/Los_Angeles">(GMT-08:00) Pacific Time</option>
			<option value="Pacific/Honolulu">(GMT-10:00) Hawaii Time</option>
			<option value="America/Anchorage">(GMT-09:00) Alaska Time</option>
			<option value="America/Denver">(GMT-07:00) Mountain Time</option>
			<option value="America/Phoenix">(GMT-07:00) Mountain Time - Arizona</option>
			<option value="America/Chicago">(GMT-06:00) Central Time</option>
			<option value="America/New_York">(GMT-05:00) Eastern Time</option>';
		$timezones .= '</select>';

		$this->view('index');
		$this->set('timezones', $timezones);
		$this->set('atlas_id', $input);
		$this->set('user', $user);
		$this->set('nofooter', true);
	}
	function free($input = false){
		$user	= $this->curlobject(array('objectId' => $input),	USER, array('limit'=>1), false);
		if(!$user) {
			$this->homepage();
			return false;
		}


		$timezones = '<select name="userTimeZone" id="userTimeZone">';
		$timezones .= '
			<option value="America/Los_Angeles">(GMT-08:00) Pacific Time</option>
			<option value="Pacific/Honolulu">(GMT-10:00) Hawaii Time</option>
			<option value="America/Anchorage">(GMT-09:00) Alaska Time</option>
			<option value="America/Denver">(GMT-07:00) Mountain Time</option>
			<option value="America/Phoenix">(GMT-07:00) Mountain Time - Arizona</option>
			<option value="America/Chicago">(GMT-06:00) Central Time</option>
			<option value="America/New_York">(GMT-05:00) Eastern Time</option>';
		$timezones .= '</select>';

		$this->view('index');
		$this->set('timezones', $timezones);
		$this->set('atlas_id', $input);
		$this->set('user', $user);
		$this->set('nofooter', true);
	}

	function invited($input){
		$event	= $this->curlobject(array('web_event_id' =>$input),		EVENT, array('limit'=>1), false);
		if(!$event) {
			$this->homepage();
			return false;
		}
		$inputs = array('web_item_id'=>array(
						'$in'=>array($input)));
		$invitee_items	= $this->curlobject($inputs,	ITEMUSER,	false, true);

		foreach($invitee_items as $item){
			if($item->atlas_id != $event->atlas_id) $proatlasid = $item->atlas_id;
		}

		$user	= $this->curlobject(array('objectId' => $proatlasid),	USER, array('limit'=>1), false);
		$extra	= $this->curlobject(array('primary_web_event_id' =>$input), EVENT, array('order'=>'display_order'), true);

		$count = 1;
		if($extra) foreach($extra as $k) $count++;

		$this->set('count', $count);
		$this->set('event', $event);
		$this->set('user', $user);
		$this->set('nofooter', true);
		$this->set('invited_footer', true);

	}

	function respond($input){
		$request	= $this->curlobject(array('web_item_user_id' =>$input),				ITEMUSER, array('limit'=>1), false);
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);

		if(!$event) {
			$this->homepage();
			return false;
		}

		$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, array('order'=>'display_order'), true);

		$times[$event->display_order] = array(
			'time'		=>$this->timeduration($event->start_datetime->iso, $event->duration),
			'web_event_id'	=>$event->web_event_id,
			'atlas_id'	=>$event->atlas_id,
			'duration'	=>$event->duration,
			'web_item_user_id'=>$request->web_item_user_id,
			'status'		=> $event->status,
			'title'		=>$event->title,
			'location'	=>$event->location,
			'selected'	=>$request->priority_order,
		);
		$eventtimes[$event->web_event_id] = $this->timeduration($event->start_datetime->iso, $event->duration);
		$ins[]	= $request->web_item_id;
		if($extra) foreach($extra as $obj){
			$times[$obj->display_order] = array(
				'time'		=>$this->timeduration($obj->start_datetime->iso, $obj->duration),
				'web_event_id'	=>$obj->web_event_id,
				'atlas_id'	=>$obj->atlas_id,
				'duration'	=>$obj->duration,
				'status'		=>$obj->status,
				'title'		=>$obj->title,
				'location'	=>$obj->location,
			);
			$ins[] = $obj->web_event_id;
			$eventtimes[$obj->web_event_id] = $this->timeduration($obj->start_datetime->iso, $obj->duration);
	//		$eventtimesr = $this->timeduration($obj->start_datetime->iso, $obj->duration);
		}
		$booked = false;
		$selected = false;
		foreach($times as $time){
			if($time['status'] == 1){
				$picked = $time['time'];
			}
			if($time['status'] > 0){
				$selected = true;
			}
		}
		$multi_picked = false;
		$inputs = array('web_item_id'=>array(
						'$in'=>$ins),
					  'atlas_id'=>$request->atlas_id);
		$invitee_items	= $this->curlobject($inputs,	ITEMUSER,	false, true);
		$doupdate = true;
		if($invitee_items) {
			foreach($invitee_items as $obj2){
				if($obj2->was_received == true) $doupdate = false;
			}
		}
		if($doupdate) $this->putcurlobject($request->objectId,array(
						'was_received'=>true,
						'received_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )
					), ITEMUSER);
		if($extra) foreach($extra as $obj){
			foreach($invitee_items as $obj2){
				if($obj2->priority_order || $obj2->status) $multi_picked = true;
				if($obj->web_event_id == $obj2->web_item_id){
					$times[$obj->display_order]['web_item_user_id'] = $obj2->web_item_user_id;
					$times[$obj->display_order]['selected'] = $obj2->priority_order;
					if($doupdate)	$this->putcurlobject($obj2->objectId,array(
						'was_received'=>true,
						'received_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )
					), ITEMUSER);
				}
			}

		}

		$picked = false;
		foreach($invitee_items as $obj2){
			if($obj2->status > 0){
				$selected = true;
				if(($obj2->status == 1) && (!$picked)){
					$picked = $eventtimes[$obj2->web_item_id];
				}
			}
		}

		$bookedview = false;
		if(($selected && $picked)){
			$bookedview = true;
			$this->view('booked');
		} elseif($selected && !$picked){
			$this->view('declined');
		}

		$inputs = array('web_item_id'=>array(
						'$in'=>$ins));
		$inviter_items		= $this->curlobject($inputs,	ITEMUSER,	false, true);

		$counter = false;
		foreach($inviter_items as $inviter_item) {
			$inviters[$inviter_item->atlas_id] = $inviter_item->atlas_id;
			if($inviter_item->status == 4) $counter = true;
			if($inviter_item->atlas_id != $request->atlas_id) $otherids[] = $inviter_item->atlas_id;
		}
		if(count($inviters) > 2) $multi = true;
		else					$multi = false;



		$user	= $this->curlobject(array('objectId' => $event->atlas_id),	USER, array('limit'=>1), false);
		$data	= $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);

		if(!$user->is_atlas_user) {
			$tmp = $data;
			$data = $user;
			$user = $tmp;
			$tmp = null;
		}

		if(!$user->is_atlas_user) {
			foreach($otherids as $id){
				if($id != $request->atlas_id)
				$user	= $this->curlobject(array('objectId' => $id),	USER, array('limit'=>1), false);
				if($user->is_atlas_user) break;
			}
		}

		/*
		echo "<pre>";
		echo "REQUEST:\n";
		print_r($request);
		echo "EVENT:\n";
		print_r($event);
		echo "EXTRA:\n";
		print_r($extra);
		echo "INVITEE_ITEMS\n";
		print_r($invitee_items);
		echo "INVITER_ITEMS\n";
		print_r($inviter_items);
		echo "DATA\n";
		print_r($data);
		echo "USER\n";
		print_r($user);
		echo "</pre>";
		*/

		//$this->pushNotification($event->atlas_id, $user->first_name, $eventtimesr['time'], $event->title, $request->web_item_id);


		if($multi) $this->view('multi');
		$this->set('hidepro', true);
		$this->set('counter', $counter);
		$this->set('multi_picked', $multi_picked);
		$this->set('web_item_user_id', $request->web_item_user_id);
		$this->set('web_item_id', $request->web_item_id);
		$this->set('event', $event);
		$this->set('data', $data);
		$this->set('user', $user);
		$this->set('times', $times);
		$this->set('picked', $picked);
	}

	function booked($input){
		if(!$input) {
			$this->homepage();
			return false;
		}
		$user	= $this->curlobject(array('objectId' => $input),	USER, array('limit'=>1), false);

		$this->set('atlas_id', $input);
		$this->set('user', $user);
		$this->set('nofooter', true);
	}

	function booksingle($input){
		list($input, $web_event_id) = explode('-', $input);
		$this->template('ajax');
		$this->putcurlobject(array('status'=>1, 'was_received'=>false, 'status_datetime'=>date()),array('web_item_user_id' =>$input), ITEMUSER);
		$this->putcurlobject(array('status'=>1),array('web_event_id' =>$web_event_id), EVENT);
	}

	function downloadical(){
		// return the ics file
		$start    = $_POST['start'];
		$duration	= $_POST['duration'];
		$title    = $_POST['title'];
		$desc     = $_POST['desc']." Booked with Atlas. http://getatlas.com";
		$location = $_POST['location'];
		$this->ICS($start,$duration,$title,$desc,$location);
		$this->showical();
		$this->template('ajax');
		exit;
	}

	function downloadgoogle(){
		// return the ics file
		$start    = $_POST['start'];
		$duration	= $_POST['duration'];
		$title    = $_POST['title'];
		$desc     = $_POST['desc']."
Booked with Atlas. http://getatlas.com";
		$location = $_POST['location'];
		$webitem	= $_POST['webitem'];
		echo $this->googleCal($start,$duration,$title,$desc,$location,$webitem);
		$this->template('ajax');
		exit;
	}
}