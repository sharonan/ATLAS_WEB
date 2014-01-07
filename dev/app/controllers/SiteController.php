<?php
class SiteController extends Controller {

	protected $picked_array ;

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

	function about(){
		$this->set('header', 2);
		$this->set('hidepro', true);
		$this->set('nofooter', true);
		$this->set('nospace', true);
		$this->set('mission', true);
	}
	
	function privacy(){
		$this->set('header', 2);
		$this->set('hidepro', true);
		$this->set('nofooter', true);
		$this->set('nospace', true);
		$this->set('mission', false);
	}
	
	function terms(){
		$this->set('header', 2);
		$this->set('hidepro', true);
		$this->set('nofooter', true);
		$this->set('nospace', true);
		$this->set('mission', false);
	}
	
	function verify($input = false){
		if(!$input) {
			$this->homepage();
			return false;
		}
		
		$email = $this->curlobject(array('email_address_id' => $input), EMAIL_ADDRESS, array('limit'=>1), false);
		if (!$email){
			$this->homepage();
			return false;
		}
		
		$this->putcurlobject($email->objectId, array('is_verified'=>true), EMAIL_ADDRESS);
		$this->postcurlobject(array('email_address'=>$email->email_address), DELETE_UNVERIFIED_EMAIL_ADDRESSES);
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
		$appStoreImg = true;
		$this->set('hidepro', true);
		$this->set('user', $user);
		$this->set('appStoreImg', true);
	}

	// function emailBooked($invitee, $inviter, $event, $otherInvitee)
// 	{
// 	 	
// 	   $times = $this->timeduration($event->start_datetime->iso, $event->duration);
// 		$vars = array(
// 			'env'			=> DIRECTORY,
// 			'notes'	=> $event->notes,
// 			'location'	=> $event->location,//The Coffee Bean & Tea Leaf, South Westlake Boulevard, Westlake Village, CA, United States',//$event->location,
// 			'title'	=> $event->title,
// 			'time'	=> $times['time'],
// 			'inviter_name'=> $inviter->first_name,
// 			'other_invitee'=> $otherInvitee,
// 			'date'=> $times['date'],
// 			// 'datetime_created'=> date("F d, Y"),
// 		);
// 
// // 		if($email->email_address === $user->email){
// 			// $vars = array(
// // 			    'env'			=> DIRECTORY,
// // 			    'user_picture'	=> $inviter->picture->url,
// // 			    'user_firstname'=> $inviter->first_name,
// // 			    'datetime_created'=> date("F d, Y"),
// // 			);
// // 			$message ="<html><head> <meta name='viewport' content='width=device-width'> <style type='text/css'>@import url(http://fonts.googleapis.com/css?family=PT+Sans); </style> <title></title></head><body style='background-color: #EEE; font-family: 'PT Sans', sans-serif; margin: 0px;' bgcolor='#EEE'> <table cellspacing='0' cellpadding='0' style='width: 649px; overflow: hidden; margin: 10px auto; background-repeat: repeat; background-image: url(http://getatlas.com/emails/img/New/pattern.png); border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; -webkit-box-shadow: rgba(0,0,0,.2) 0 2px 4px;'> <tr> <td><img src='http://getatlas.com/emails/img/New/border_pattern.png'></td> </tr> <tr style='background:url(http://files.parse.com/5a57d7f6-d602-41d6-9011-ce32454fe355/9ff41023-2a50-4559-b5dc-84d04206de08-image.png) 1% no-repeat; background-size: 88px 88px;'> <td> <table width='100%'> <tr> <td style='font-size: 30px; color: #3c76c3;'> <center> lasha,<br> please verify your email. </center> </td> </tr> </table> </td> </tr> <tr> <td><img src='http://getatlas.com/emails/img/New/divider.png'></td> </tr> <tr> <td> <center style='font-size: 20px;'> <br> <i style='color: #3c76c3; font-size: 24px;'>lasha@getatlas.com</i><br> <br> <span style='font-size: 14px;'>If you didn't initiate this verify process, please ignore this email.</span><br> <br> <a href='http://dev.getatlas.com/verify/045c37bf5e3ba72974ab126f77ea5bfe'><img src='http://getatlas.com/emails/img/button-confirm.png'></a><br> <br> Your feedback is very important to us. We want to make<br> scheduling meetings and phone calls as easy as possible.<br> <br> With your help, we can<br> <img src='http://getatlas.com/emails/img/team-atlas.png'><br> <br> </center> </td> </tr> <tr> <td><img src='http://getatlas.com/emails/img/New/border_pattern.png'></td> </tr> </table> <div style='width: 649px; margin: 10px auto; font-size: 12px; color: #7d7d7d; text-align:center;'> Appointment Negotiation is a service provided by Atlas.<br> Copyright &copy; Atlas Powered, LLC - 820 Broadway, Santa Monica, CA 90401<br> <br> <a href='http://support.getatlas.com' style='color: #7d7d7d;'>Need Support? Click here.</a><br> <br> <a href='http://dev.getatlas.com/verify/045c37bf5e3ba72974ab126f77ea5bfe'><img src='http://getatlas.com/emails/img/New/atlas_btn.png' alt='Atlas Homepage'></a><br> </div></body></html>";
// 			$message = $this->mailEventBooked($vars);
// 			
// 			$insert = array(
// 			    'body'		=> $message,
// 			    'from'		=> 'team@getatlas.com',
// 			    'from_name'	=> 'Team Atlas',
// 			    'is_sent'	=> false,
// 			    'is_pending'=> false,
// 			    'has_error'	=> false,
// 			    'subject'	=> 'booked',
// 			    'to'		=> 'sharon@getatlas.com',
// 			);
// 			$this->postcurlobject($insert, 'https://api.parse.com/1/classes/outbound_email');
// 	 	
// 	}
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
		$event	= $this->curlobject(array('web_event_id'=>$input), EVENT, array('limit'=>1), false);
		if(!$event) {
			$this->homepage();
			return false;
		}
		$inputs = array('web_item_id'=>array('$in'=>array($input)));
		$invitee_items	= $this->curlobject($inputs, ITEM_USER, false, true);

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
//	function invited($input){
//		$event	= $this->curlobject(array('web_event_id' =>$input),		EVENT, array('limit'=>1), false);
//		if(!$event) {
//			$this->homepage();
//			return false;
//		}
//		$inputs = array('web_item_id'=>array(
//						'$in'=>array($input)));
//		$invitee_items	= $this->curlobject($inputs,	ITEM_USER,	false, true);
//
//		foreach($invitee_items as $item){
//			if($item->atlas_id != $event->atlas_id) $proatlasid = $item->atlas_id;
//		}
//
//		$user	= $this->curlobject(array('objectId' => $proatlasid),	USER, array('limit'=>1), false);
//		$extra	= $this->curlobject(array('primary_web_event_id' =>$input), EVENT, array('order'=>'display_order'), true);
//
//		$count = 1;
//		if($extra) foreach($extra as $k) $count++;
//
//		$this->set('count', $count);
//		$this->set('event', $event);
//		$this->set('user', $user);
//		$this->set('nofooter', true);
//		$this->set('invited_footer', true);
//
//	}


// function getPickedStatus($priority_order,$status)
// {
// 	
// 		if ($status == 2)
// 			return 2;
// 		else
// 			if  ($status == 1 && $priority_order==1)
// 				return = 1;
// 			else
// 				return = 0;
// 			
// }

function email_respond($input)
{

		
	// 	$urlArray = array();
// 			$urlArray = explode('?',$input);
// 			$primary_web_item_user_id = $urlArray[0];
// 			$web_item_user_id_picked = $urlArray[1];
		
		// if ($urlArray[1]!=$urlArray[0])
// 		{
// 			respond($urlArray[0]);
// 		}else
// 		{


		respond($input);



		$this->web_item_user_id = $input;//$_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		if($request->status != 0) { echo "Email - You have already responded to this event."; exit; }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		if($event->primary_web_event_id){
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
// 				echo "confirm_single a.";
		$eventlist[$event->web_event_id]	= $event->objectId;
		$eventtimes[$event->web_event_id]	= $this->timestr($event->start_datetime->iso, $event->duration);
		$ins[]						= $event->web_event_id;
		if($extra) foreach($extra as $obj) {
			$ins[]					= $obj->web_event_id;
			$eventlist[$obj->web_event_id]= $obj->objectId;
			$eventtimes[$obj->web_event_id]= $this->timestr($obj->start_datetime->iso, $obj->duration);
		}
		$inputs = array('web_item_id'=>array(
						'$in'=>$ins),
					  'atlas_id'=>$request->atlas_id);
		$invitee_items	= $this->curlobject($inputs,	ITEM_USER,	false, true);
// 				echo "confirm_single b.";

		foreach($invitee_items as $key=>$obj){
// 					echo "confirm_single c.";

			if($this->web_item_user_id == $obj->web_item_user_id) {
				$user = $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);
				$this->putcurlobject($obj->objectId, array('status'=>1,'was_received'=>false,
						'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>1), EVENT);
				$this->pushNotification($event->atlas_id, $user->first_name, $eventtimes[$obj->web_item_id],  $event->title, $event->web_event_id);
			} else {
				$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
						'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
			}
			
		}
		
		respond($input);
		
// 		}
}

function decline_email($decline,$inviteeNum){
		$this->web_item_user_id = $decline;
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		// if($request->status != 0) { echo "	Declined email - You have already responded to this event."; exit; }
// 		 echo "	Declined email - You have already responded to this event.";
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		
		if($event->primary_web_event_id){
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
		$eventlist[$event->web_event_id]	= $event->objectId;
		$ins[]						= $event->web_event_id;
		if($extra) foreach($extra as $obj) {
			$ins[]					= $obj->web_event_id;
			$eventlist[$obj->web_event_id]= $obj->objectId;
		}
		$inputs = array('web_item_id'=>array(
						'$in'=>$ins),
					  'atlas_id'=>$request->atlas_id);
		$invitee_items	= $this->curlobject($inputs,	ITEM_USER,	false, true);
		
		foreach($invitee_items as $key=>$obj){
			$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
			    'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
			if ($inviteeNum==2)
			$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
		}
	}
	function respond($input){
//	
		
		
		
		//////////SHARON...
		$web_respond = false;
		$web_respond = (strpos($input, '?')===0) ;
		$web_item_user_id_picked = "";
		if (!$web_respond)
		{
// 			echo "confirm_single b.";
			$urlArray = array();
			$urlArray = explode('?',$input);
			$input = $urlArray[0];
			$web_item_user_id_picked = $urlArray[1];
			// if (!strcmp($web_item_user_id_picked, "")===0)
				// $this->view('booked');
// 			$web_item_user_id_picked ="sharona";
			// email_respond($web_item_user_id_picked);
		}
// 	
//  		else
// 		{
		
		
		
		
		//get the itemUser for the webItemUserId passed
		$itemUser	= $this->curlobject(array('web_item_user_id'=>$input), ITEM_USER, array('limit'=>1), false);
		//get the event for that itemUser
		$event	= $this->curlobject(array('web_event_id'=>$itemUser->web_item_id), EVENT, array('limit'=>1), false);
		$itemUserPicked = $this->curlobject(array('web_item_user_id'=>$web_item_user_id_picked), ITEM_USER, array('limit'=>1), false);
		
		$otherInviteeItemUserPrimary = $this->curlobject(array('web_item_id'=>$itemUser->web_item_id), ITEM_USER, false, true);
		
		
		if(!$event) {
// 		echo "NO EVENT";
			$this->homepage();
			return false;
		}

		//get all the alternate events
		$altEventArray = $this->curlobject(array('primary_web_event_id'=>$itemUser->web_item_id), EVENT, array('order'=>'display_order'), true);

//for event
		//some array for something.  event.displayOrder is always 0 here
		$eventInfoArray[$event->display_order] = array(
			'event'						=>$event,
			'time'		 				=>$this->timeduration($event->start_datetime->iso, $event->duration),
			'web_event_id'		=>$event->web_event_id,
			'primary_web_event_id'   =>$event->primary_web_event_id,
			'atlas_id'				=>$event->atlas_id,
			'duration'				=>$event->duration,
			'status'					=>$event->status,
			'title'						=>$event->title,
			'location'				=>$event->location,
			'selected'				=>$itemUser->priority_order,
			'web_item_user_id'		=>$itemUser->web_item_user_id
		);
		$eventIdArray[] = $event->web_event_id;
		//$eventIdArray[]	= $itemUser->web_item_id;		
		//some array with just the time.  array index is eventId rather than displayOrder for some reason.
		$eventTimeArray[$event->web_event_id] = $this->timeduration($event->start_datetime->iso, $event->duration);
		
//for alt events - note, it adds the info for the itemUser for each alt event (selected and web_item_user_id) down below
		if($altEventArray) foreach($altEventArray as $altEvent){
			$eventInfoArray[$altEvent->display_order] = array(
				'event'						=>$altEvent,
				'time'						=>$this->timeduration($altEvent->start_datetime->iso, $altEvent->duration),
				'web_event_id'		=>$altEvent->web_event_id,
				'primary_web_event_id'   =>$altEvent->primary_web_event_id,
				'atlas_id'				=>$altEvent->atlas_id, 
				'duration'				=>$altEvent->duration,
				'status'					=>$altEvent->status,
				'title'						=>$altEvent->title,
				'location'				=>$altEvent->location,
				'display_order'			=>$altEvent->display_order,
			);
			$eventIdArray[] = $altEvent->web_event_id;
			$eventTimeArray[$altEvent->web_event_id] = $this->timeduration($altEvent->start_datetime->iso, $altEvent->duration);
		}
		$primaryWebEventId = "";
		$pickedEmailOrder = -1;
		$pickedOrder = -1;
		$picked = false;
		$selected = false;
		/*
		echo "<!--";
		print_r($eventInfoArray);
		echo "-->";
		 * 
		 */
		 $choosed = false;
		 $choosedDec = false;
		 $canceledView = false;
		foreach($eventInfoArray as $eventInfo){
			$choosed = $choosed || ($eventInfo['status'] >0);
// 			 = $eventInfo['status'] === 2 || $choosedDec;
			if (strcmp($eventInfo['primary_web_event_id'],"")===0)
				$primaryWebEventId = $eventInfo['web_event_id'];
			 if(strcmp($eventInfo['web_event_id'], $itemUserPicked->web_item_id)===0)
			 {
 				$pickedEmailOrder = $eventInfo['display_order'];
 				$picked = $eventInfo['time'];
 				$pickedEvent = $eventInfo['event'];
//  				echo "confirm_single a.";
 			}
			if($eventInfo['status'] === 1){
			$choosedDec = true;
				$picked = $eventInfo['time'];
				$pickedEvent = $eventInfo['event'];
				$pickedOrder = $eventInfo['display_order'];
// 				echo "confirm_single b.";
			}
			$canceledView = $eventInfo['status'] === 3;
			if($eventInfo['status'] > 0){
				$selected = true;
			}
		}
		$multi_picked = false;
		
		if (!$multi_picked_array)
			$multi_picked_array = array(1,1,1);
		// $multi_picked_2 = 0;
// 		$multi_picked_3 = 0;
		
		//some array with web_item_id as an array with a key of '$in'=an array of eventId's
		$inputs = array('web_item_id'=>array('$in'=>$eventIdArray),'atlas_id'=>$itemUser->atlas_id);
		//somehow this returns all this user's itemUsers for these events
		$itemUserArray	= $this->curlobject($inputs,	ITEM_USER,	false, true);
		
		//mark the invite as received.
		$doupdate = true;
		if($itemUserArray) {
			foreach($itemUserArray as $aItemUser){
				if($aItemUser->was_received == true) $doupdate = false;
			}
		}
		if($doupdate) $this->putcurlobject($itemUser->objectId,array(
						'was_received'=>true,
						'received_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )
					), ITEM_USER);
		
		if($altEventArray) foreach($altEventArray as $altEvent){
			foreach($itemUserArray as $aItemUser){
				if($aItemUser->priority_order || $aItemUser->status) $multi_picked = true;
				
				if($altEvent->web_event_id == $aItemUser->web_item_id ){
					$eventInfoArray[$altEvent->display_order]['web_item_user_id'] = $aItemUser->web_item_user_id;
					$eventInfoArray[$altEvent->display_order]['selected'] = $aItemUser->priority_order;
					
// 				
				}
			}
		}
		/*
		echo "<!--";
		print_r($itemUserArray);
		echo "-->";
		
		foreach($itemUserArray as $aItemUser){
			if($aItemUser->status > 0){
				$selected = true;
				if(($aItemUser->status == 1) && (!$picked)){
					$picked = $eventTimeArray[$aItemUser->web_item_id];
				}
			}
		} 
		*/

		$bookedview = false;
		//if($selected && $picked){
		if($picked && !$choosed){
// echo 'booked';
			$bookedview = true;
			$this->view('booked');
		} elseif($selected && !$picked){
// 			echo 'declined';
			$this->view('declined');
		}elseif ($choosed && !$web_respond)
		{
// 		echo 'choodsed';
			$bookedview = true;
			if (!$choosedDec)
			$this->view('declined');
			else
			$this->view('booked');
		}
		// else if ($choosed)
// 		{
// 		
// 		
// 		
// 		}
		
		$inputs = array('web_item_id'=>array('$in'=>$eventIdArray));
		$itemUserArray = $this->curlobject($inputs,	ITEM_USER,	false, true);

		$counter = false;
		foreach($itemUserArray as $aItemUser) {
			$inviteeArray[$aItemUser->atlas_id] = $aItemUser->atlas_id;
			if($aItemUser->status == 4) $counter = true;
			if($aItemUser->atlas_id != $itemUser->atlas_id) $otherInviteeArray[] = $aItemUser->atlas_id;
			
			
			if($altEventArray &&  $aItemUser->atlas_id == $itemUser->atlas_id) foreach($eventInfoArray as $altEvent){
			
				if ($input==$aItemUser->web_item_user_id)
				{
				if ($aItemUser->status === 2)
						$multi_picked_array[0] =  2;
					else
						if  ($aItemUser->status===1 && $aItemUser->priority_order===1)
								$multi_picked_array[ 0] =1;
						else
								$multi_picked_array[0] = 0;
				}else
// 				if ($altEvent['display_order']==0)
				if($altEvent['web_event_id'] == $aItemUser->web_item_id ||  $altEvent['primary_web_event_id'] == $aItemUser->web_item_id)
					
// 				
					if ($aItemUser->status === 2)
						$multi_picked_array[$altEvent['display_order']] =  2;
					else
						if  ($aItemUser->status==1 && $aItemUser->priority_order==1)
								$multi_picked_array[ $altEvent['display_order']] =1;
						else
								$multi_picked_array[$altEvent['display_order']] = 0;
// 					
				
			}
		}
		$multi = count($inviteeArray) > 2;

		$inviter	= $this->curlobject(array('objectId' => $event->atlas_id),	USER, array('limit'=>1), false);
		$invitee	= $this->curlobject(array('objectId' => $itemUser->atlas_id), USER, array('limit'=>1), false);

		
		if ($choosed)
		{
			$declinedEvent = true;
			foreach($multi_picked_array as $vote)
			{
				$declinedEvent = $declinedEvent && ($vote > 1);
			}
			if($declinedEvent)
			{
				$bookedview = true;
			
				$this->view('declined');
			}
		}
		
		
		
		if(!$inviter->is_atlas_user) {
			$tmp = $invitee;
			$invitee = $inviter;
			$inviter = $tmp;
			$tmp = null;
		}
		

//i have no idea why this is here.  but this is what it is doing.
//if the inviter is not an atlas user.  then look up all the other invitees until find one that is an atlas user.
		if(!$inviter->is_atlas_user) {
			foreach($otherInviteeArray as $atlasId){
				//if($atlasId != $itemUser->atlas_id) 
				$inviter	= $this->curlobject(array('objectId' => $atlasId),	USER, array('limit'=>1), false);
				if($inviter->is_atlas_user) break;
			}
		}
// 		echo "confirm_single a.";
		$inviteeNum = 0;
		$otherInvitee ;//$otherInvitee . " " . $moreUsers->first_name;
		foreach($otherInviteeArray as $atlasId){
		// 		//if($atlasId != $itemUser->atlas_id)
 
				$moreUsers	= $this->curlobject(array('objectId' => $atlasId),	USER, array('limit'=>1), false);
// 				if (strcmp($atlasId,$itemUser->atlas_id)!= 0)
					// $otherInvitee = $otherInvitee . "," . $moreUsers->first_name;
// 				$inviteeNum = $inviteeNum +1;
			}
		foreach($otherInviteeItemUserPrimary as $itemUserPrimary){
				$inviteeItem	= $this->curlobject(array('objectId' => $itemUserPrimary->atlas_id),	USER, array('limit'=>1), false);
				if (strcmp($itemUserPrimary->atlas_id,$itemUser->atlas_id)!= 0)
					if ($inviteeNum>1)
						$otherInvitee = $otherInvitee . ", " . $inviteeItem->first_name;
					else
						$otherInvitee = $inviteeItem->first_name;
					
						
				$inviteeNum = $inviteeNum +1;
			}
			
		$otherInvitee = $otherInvitee. " and You ";	
// 		$invitee2	= $this->curlobject(array('objectId' => "MZHQ4qaSVJ"), USER, array('limit'=>1), false);
// echo "confirm_single b.";
		// $this->emailBooked($invitee2,$inviter,$event,$otherInvitee);

		//$this->pushNotification($event->atlas_id, $user->first_name, $eventtimesr['time'], $event->title, $itemUser->web_item_id);
// echo "confirm_single c.";

		// if (!$web_respond && $web_item_user_id_picked!=-1)
// 		{
// 			
// 		}else
// 		{
		if($multi && !$bookedview ) $this->view('multi');
		$this->set('otherInvitee', $otherInvitee);
		$this->set('hidepro', true);
		$this->set('appStoreImg', true);
		$this->set('counter', $counter);
		$this->set('multi_picked', $multi_picked);
		$this->set('multi_picked_array', $multi_picked_array);
		// $this->set('multi_picked_2', $multi_picked_2);
// 		$this->set('multi_picked_3', $multi_picked_3);
		$this->set('web_item_user_id', $itemUser->web_item_user_id);
		$this->set('web_item_id', $itemUser->web_item_id);
		$this->set('event', $event);
		// $this->set('data', $invitee);
		$this->set('invitee', $invitee);
		$this->set('user', $inviter);
		$this->set('times', $eventInfoArray);
		$this->set('picked', $picked);
		
		$this->set('pickedEmailOrder',$pickedEmailOrder);
		$this->set('hideAppStoreImg', false);
		$this->set('pickedEvent', $pickedEvent);
// 		}
// echo "You have already responded to this event."; 
		// $this->set('web_respond', $web_respond);
		$this->set('web_item_user_id_picked', $web_item_user_id_picked);
		$this->set('multi', $multi);

		$this->set('pickedOrder', $pickedOrder);
		if ($canceledView)
		{
			
			$this->view('canceled');
// 			$this->emailDeclined($invitee,$inviter,$event,$otherInvitee);
// 			$this->pushCanceledEventNotification($event->atlas_id, $userInvited->first_name,  $event->title, $primaryWebEventId);

		}else
		if (!$web_respond &&  strcmp($web_item_user_id_picked, "")!=0 && !$choosed )
		{
		if ($pickedEmailOrder!=-1)
		
			$pickedOrder = $pickedEmailOrder;
// 		echo "You have already responded to this event."; 
		// $this->view('booked');
			if ( strcmp($web_item_user_id_picked, "000")===0)
			{
				$this->decline_email($input,$inviteeNum);
				$this->view('declined');
				$userInvited = $this->curlobject(array('objectId' => $itemUser->atlas_id), USER, array('limit'=>1), false);

				// 		$this->pushNotification($event->atlas_id, $userInvited->first_name, $eventtimes[$obj->web_item_id],  $event->title, $primaryWebEventId );
				if (!$picked)
					$this->pushDeclinedEventNotification($event->atlas_id, $userInvited->first_name,  $event->title, $primaryWebEventId);
				else
				{
// 				echo "picked already ";
				    $this->pushCantMakeItEventNotification($event->atlas_id, $userInvited->first_name,  $event->title, $primaryWebEventId);
				}
			}else
			{
			if ($pickedEmailOrder!=-1)
			{
				$pickedOrder = $pickedEmailOrder;//$event->display_order;
				$bookedview = true;
				$this->view('booked');
			}
			$this->web_item_user_id = $web_item_user_id_picked;//$_POST['web_item_user_id'];
			if(!$this->web_item_user_id) return false;
			$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
			if($request->status > 0) 
			{ 
// 				echo "You have already responded to this event."; 
				return false;
			 }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		$eventPicked = $event;
		if($event->primary_web_event_id){
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
// 				echo "confirm_single a.";
		$eventlist[$event->web_event_id]	= $event->objectId;
		$eventtimes[$event->web_event_id]	= $this->timestr($event->start_datetime->iso, $event->duration);
		$ins[]						= $event->web_event_id;
		if($extra) foreach($extra as $obj) {
			$ins[]					= $obj->web_event_id;
			$eventlist[$obj->web_event_id]= $obj->objectId;
			$eventtimes[$obj->web_event_id]= $this->timestr($obj->start_datetime->iso, $obj->duration);
		}
		$inputs = array('web_item_id'=>array(
						'$in'=>$ins),
					  'atlas_id'=>$request->atlas_id);
		$invitee_items	= $this->curlobject($inputs,	ITEM_USER,	false, true);
// 				echo "confirm_single b.";

		foreach($invitee_items as $key=>$obj){
// 					echo "confirm_single c.";

			if(strcmp($this->web_item_user_id, $obj->web_item_user_id)=== 0) {
// 				$pickedEvent = $this->curlobject(array('web_event_id' =>$eventlist[$obj->web_item_id]), EVENT, false, true);
				$userInvited = $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);
				$this->putcurlobject($obj->objectId, array('status'=>1,'was_received'=>false,
						'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>1), EVENT);
				$this->pushNotification($event->atlas_id, $userInvited->first_name, $eventtimes[$obj->web_item_id],  $event->title, $primaryWebEventId );
				$this->emailBooked($invitee,$inviter,$eventPicked,$otherInvitee);
			} else {
// 				echo "confirm_single d.";
				$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
						'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
			}
			
		}
		// $this->emailBooked($userInvited,$inviter,$event,$otherInvitee);
		$this->view('booked');
		
		
		}
		}
// 		}
		$this->set('pickedOrder', $pickedOrder);
		
}


// function email($input)
// {
// 
// }

//function respond($input){
//		//get the 
//		$request	= $this->curlobject(array('web_item_user_id'=>$input), ITEM_USER, array('limit'=>1), false);
//		$event	= $this->curlobject(array('web_event_id'=>$request->web_item_id), EVENT, array('limit'=>1), false);
//
//		if(!$event) {
//			$this->homepage();
//			return false;
//		}
//
//		$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, array('order'=>'display_order'), true);
//
//		$times[$event->display_order] = array(
//			'time'		=>$this->timeduration($event->start_datetime->iso, $event->duration),
//			'web_event_id'	=>$event->web_event_id,
//			'atlas_id'	=>$event->atlas_id,
//			'duration'	=>$event->duration,
//			'web_item_user_id'=>$request->web_item_user_id,
//			'status'		=> $event->status,
//			'title'		=>$event->title,
//			'location'	=>$event->location,
//			'selected'	=>$request->priority_order,
//		);
//		$eventtimes[$event->web_event_id] = $this->timeduration($event->start_datetime->iso, $event->duration);
//		$ins[]	= $request->web_item_id;
//		if($extra) foreach($extra as $obj){
//			$times[$obj->display_order] = array(
//				'time'		=>$this->timeduration($obj->start_datetime->iso, $obj->duration),
//				'web_event_id'	=>$obj->web_event_id,
//				'atlas_id'	=>$obj->atlas_id,
//				'duration'	=>$obj->duration,
//				'status'		=>$obj->status,
//				'title'		=>$obj->title,
//				'location'	=>$obj->location,
//			);
//			$ins[] = $obj->web_event_id;
//			$eventtimes[$obj->web_event_id] = $this->timeduration($obj->start_datetime->iso, $obj->duration);
//	//		$eventtimesr = $this->timeduration($obj->start_datetime->iso, $obj->duration);
//		}
//		$booked = false;
//		$selected = false;
//		foreach($times as $time){
//			if($time['status'] == 1){
//				$picked = $time['time'];
//			}
//			if($time['status'] > 0){
//				$selected = true;
//			}
//		}
//		$multi_picked = false;
//		$inputs = array('web_item_id'=>array(
//						'$in'=>$ins),
//					  'atlas_id'=>$request->atlas_id);
//		$invitee_items	= $this->curlobject($inputs,	ITEM_USER,	false, true);
//		$doupdate = true;
//		if($invitee_items) {
//			foreach($invitee_items as $obj2){
//				if($obj2->was_received == true) $doupdate = false;
//			}
//		}
//		if($doupdate) $this->putcurlobject($request->objectId,array(
//						'was_received'=>true,
//						'received_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )
//					), ITEM_USER);
//		if($extra) foreach($extra as $obj){
//			foreach($invitee_items as $obj2){
//				if($obj2->priority_order || $obj2->status) $multi_picked = true;
//				if($obj->web_event_id == $obj2->web_item_id){
//					$times[$obj->display_order]['web_item_user_id'] = $obj2->web_item_user_id;
//					$times[$obj->display_order]['selected'] = $obj2->priority_order;
//					if($doupdate)	$this->putcurlobject($obj2->objectId,array(
//						'was_received'=>true,
//						'received_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )
//					), ITEM_USER);
//				}
//			}
//
//		}
//
//		$picked = false;
//		foreach($invitee_items as $obj2){
//			if($obj2->status > 0){
//				$selected = true;
//				if(($obj2->status == 1) && (!$picked)){
//					$picked = $eventtimes[$obj2->web_item_id];
//				}
//			}
//		}
//
//		$bookedview = false;
//		if(($selected && $picked)){
//			$bookedview = true;
//			$this->view('booked');
//		} elseif($selected && !$picked){
//			$this->view('declined');
//		}
//
//		$inputs = array('web_item_id'=>array(
//						'$in'=>$ins));
//		$inviter_items		= $this->curlobject($inputs,	ITEM_USER,	false, true);
//
//		$counter = false;
//		foreach($inviter_items as $inviter_item) {
//			$inviters[$inviter_item->atlas_id] = $inviter_item->atlas_id;
//			if($inviter_item->status == 4) $counter = true;
//			if($inviter_item->atlas_id != $request->atlas_id) $otherids[] = $inviter_item->atlas_id;
//		}
//		if(count($inviters) > 2) $multi = true;
//		else					$multi = false;
//
//
//
//		$user	= $this->curlobject(array('objectId' => $event->atlas_id),	USER, array('limit'=>1), false);
//		$data	= $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);
//
//		if(!$user->is_atlas_user) {
//			$tmp = $data;
//			$data = $user;
//			$user = $tmp;
//			$tmp = null;
//		}
//
//		if(!$user->is_atlas_user) {
//			foreach($otherids as $id){
//				if($id != $request->atlas_id)
//				$user	= $this->curlobject(array('objectId' => $id),	USER, array('limit'=>1), false);
//				if($user->is_atlas_user) break;
//			}
//		}
//
//		/*
//		echo "<pre>";
//		echo "REQUEST:\n";
//		print_r($request);
//		echo "EVENT:\n";
//		print_r($event);
//		echo "EXTRA:\n";
//		print_r($extra);
//		echo "INVITEE_ITEMS\n";
//		print_r($invitee_items);
//		echo "INVITER_ITEMS\n";
//		print_r($inviter_items);
//		echo "DATA\n";
//		print_r($data);
//		echo "USER\n";
//		print_r($user);
//		echo "</pre>";
//		*/
//
//		//$this->pushNotification($event->atlas_id, $user->first_name, $eventtimesr['time'], $event->title, $request->web_item_id);
//
//
//		if($multi) $this->view('multi');
//		$this->set('hidepro', true);
//		$this->set('counter', $counter);
//		$this->set('multi_picked', $multi_picked);
//		$this->set('web_item_user_id', $request->web_item_user_id);
//		$this->set('web_item_id', $request->web_item_id);
//		$this->set('event', $event);
//		$this->set('data', $data);
//		$this->set('user', $user);
//		$this->set('times', $times);
//		$this->set('picked', $picked);
//	}
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
		$this->putcurlobject(array('status'=>1, 'was_received'=>false, 'status_datetime'=>date()),array('web_item_user_id' =>$input), ITEM_USER);
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