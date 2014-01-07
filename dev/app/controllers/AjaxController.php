<?php
class AjaxController extends Controller {
	public $web_item_user_id;
	
	function confirm_single(){
		echo "confirm_single.";
		$this->web_item_user_id = $_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		if($request->status != 0) { echo "Conform - You have already responded to this event."; exit; }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		if($event->primary_web_event_id){
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
				echo "confirm_single a.";
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
				echo "confirm_single b.";

		foreach($invitee_items as $key=>$obj){
					echo "confirm_single c.";

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
	}
	
	
	function confirm_email_respond($input){
		echo "confirm_single.";
		$this->web_item_user_id = $input;//$_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		if($request->status != 0) { echo "Confirm respond - You have already responded to this event."; exit; }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		if($event->primary_web_event_id){
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
				echo "confirm_single a.";
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
	}
	
	
	function decline_single(){
		$this->web_item_user_id = $_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		if($request->status != 0) { echo "Decline single - You have already responded to this event."; exit; }
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
			$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
		}
	}
	function cant_make_it_single(){
// 	echo "decline";
		$this->web_item_user_id = $_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		// if($request->status != 0) { echo "You have already responded to this event."; exit; }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		$items =  $this->curlobject(array('web_item_id' =>$event->web_event_id),		ITEM_USER, false, true);
		$multi = count($items) > 2;

		$primaryEventId ;
		$title ;
		$atlasId;
		if($event->primary_web_event_id){
		$primaryEventId = $event->primary_web_event_id;
		
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
		$primaryEventId = $event->web_event_id;
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
		$eventChoosen = $event;
		if ($event-> status === 1)
			$eventChoosen = $event;
		else
		{
			if ($extra)
			{
				foreach($extra as $altEvent){
					if ($altEvent -> status === 1)
						$eventChoosen = $altEvent;
				}
			}
		}
		$title = $event->title;
		$atlasId = $event->atlas_id;
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
		// echo "decline";
		
		
		
		
		
		foreach($invitee_items as $key=>$obj){
			$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
			    'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
			  if (!$multi)  
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>3), EVENT);
		}
 		$userInvited = $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);
		 $inviter = $this->curlobject(array('objectId' => $event->atlas_id), USER, array('limit'=>1), false);

		$this->emailDeclined($userInvited,$inviter,$eventChoosen);
 		$this->pushCantMakeItEventNotification($atlasId, $userInvited->first_name,  $title, $primaryEventId);
	}
	function cant_make_it_multi(){
// 	echo "decline";
		$this->web_item_user_id = $_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
		// if($request->status != 0) { echo "You have already responded to this event."; exit; }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		$items =  $this->curlobject(array('web_item_id' =>$event->web_event_id),		ITEM_USER, false, true);
		$multi = count($items) > 2;

		$primaryEventId ;
		$title ;
		$atlasId;
		if($event->primary_web_event_id){
		$primaryEventId = $event->primary_web_event_id;
		
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
		$primaryEventId = $event->web_event_id;
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
		$title = $event->title;
		$atlasId = $event->atlas_id;
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
		// echo "decline";
		
		
		
		
		
		foreach($invitee_items as $key=>$obj){
			$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
			    'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
// 			  if (!$multi)  
// 				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
		}
 		$userInvited = $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);

 		$this->pushCantMakeItEventNotification($atlasId, $userInvited->first_name,  $title, $primaryEventId);
	}
	function vote_multi(){
		$data = $_POST['opts'];
		$itemEvent;
		$userInvited ;

		$found=false;
		if(!$data) return false;
		foreach($data as $object){
			if((int)$object['value'] > 2) continue;
			$this->web_item_user_id = $object['key'];
			
			if(!$this->web_item_user_id) return false;
			
			$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEM_USER, array('limit'=>1), false);
			if (!$userInvited)
				$userInvited = $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);
			if (!$found)
				$itemEvent	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
			$found = (!$itemEvent->primary_web_event_id);
			//if($request->status != 0) { echo "You have already responded to this event."; exit; }
			$status = ((int)$object['value']<2?1:2);
			
			$this->putcurlobject($request->objectId, array('priority_order'=>(int)$object['value'],'status'=>$status,'was_received'=>false,
			    'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEM_USER);
			
		}
// 		echo "TRY PUSH";
		$this->pushMultiNotification($itemEvent->atlas_id, $userInvited->first_name,  $itemEvent->title, $itemEvent->web_event_id);

		// if ($itemEvent && $userInvited)
// // 		{
// 		echo " PUSH SENDING";
// // 					pushMultiNotification("7bZKFSY8CP", "SHARONA",  "TITLE", "2b8c09f565987aa126d1495a3793c679");
// 
// 			$this->pushNotification($itemEvent->atlas_id, $userInvited->first_name,  $itemEvent->title, $itemEvent->web_event_id);
// 		}
	}
	
	function invite(){
		
		$utc = new DateTimeZone('UTC');
		$dt = new DateTime('now', $utc);
		foreach(DateTimeZone::listIdentifiers() as $tz) {
			$current_tz	= new DateTimeZone($tz);
			$offset		=  $current_tz->getOffset($dt);
			$transition	=  $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
			$abbr		= $transition[0]['abbr'];
			$opts[$tz]	= $abbr;
		}
		ksort($opts);
		//foreach($opts as $abbr=>$time){
		//	$timezones .= '<option value="' .$abbr.'"'. ($abbr=="PST"?' selected="selected"':'').'">' .$abbr. ' ['. $time . ']</option>';
		//}
		
		if(!$opts[$_POST['tz']]) $tz = $opts['America/Los_Angeles'];
		else					$tz = $opts[$_POST['tz']];
		
		$request = array(
		    'atlas_id'=>$_POST['atlas_id'],
		    'visitor'=>array(
			   'firstname'	=>$_POST['firstname'],
			   'lastname'	=>$_POST['lastname'],
			   'email'	=>$_POST['email'],
		    ),
		    'event'=>array(
			   'title'=>$_POST['title'],
			   'message'=>$_POST['message'],
			   'location'=>$_POST['location'],
			   'phone_number'=>$_POST['phone_number'].".".$_POST['ext'],
			   'duration'=>$_POST['duration'],
			   'date_time_1'=>array('__type'=>'Date', 'iso'=>date("Y-m-d\TH:i:s.000\Z", strtotime($_POST['date_time_1']." ".$tz))),
			   'date_time_2'=>array('__type'=>'Date', 'iso'=>date("Y-m-d\TH:i:s.000\Z", strtotime($_POST['date_time_2']." ".$tz))),
			   'date_time_3'=>array('__type'=>'Date', 'iso'=>date("Y-m-d\TH:i:s.000\Z", strtotime($_POST['date_time_3']." ".$tz))),
		    ),
		);
		
		$result = $this->postcurlobject($request, INVITETOEVENT);
		echo $result->primaryEventId;
	}
}