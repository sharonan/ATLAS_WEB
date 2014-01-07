<?php
class AjaxController extends Controller {
	public $web_item_user_id;
	
	function confirm_single(){
		$this->web_item_user_id = $_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEMUSER, array('limit'=>1), false);
		if($request->status != 0) { echo "You have already responded to this event."; exit; }
		$event	= $this->curlobject(array('web_event_id' =>$request->web_item_id),		EVENT, array('limit'=>1), false);
		if($event->primary_web_event_id){
			$event	= $this->curlobject(array('web_event_id' =>$event->primary_web_event_id),EVENT, array('limit'=>1), false);
			$extra	= $this->curlobject(array('primary_web_event_id' =>$event->web_event_id), EVENT, false, true);
		} else {
			$extra	= $this->curlobject(array('primary_web_event_id' =>$request->web_item_id), EVENT, false, true);
		}
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
		$invitee_items	= $this->curlobject($inputs,	ITEMUSER,	false, true);
		foreach($invitee_items as $key=>$obj){
			if($this->web_item_user_id == $obj->web_item_user_id) {
				$user = $this->curlobject(array('objectId' => $request->atlas_id), USER, array('limit'=>1), false);
				$this->putcurlobject($obj->objectId, array('status'=>1,'was_received'=>false,
						'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEMUSER);
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>1), EVENT);
				$this->pushNotification($event->atlas_id, $user->first_name, $eventtimes[$obj->web_item_id],  $event->title, $event->web_event_id);
			} else {
				$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
						'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEMUSER);
				$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
			}
		}
	}
	
	function decline_single(){
		$this->web_item_user_id = $_POST['web_item_user_id'];
		if(!$this->web_item_user_id) return false;
		$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEMUSER, array('limit'=>1), false);
		if($request->status != 0) { echo "You have already responded to this event."; exit; }
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
		$invitee_items	= $this->curlobject($inputs,	ITEMUSER,	false, true);
		foreach($invitee_items as $key=>$obj){
			$this->putcurlobject($obj->objectId, array('status'=>2,'was_received'=>false,
			    'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEMUSER);
			$this->putcurlobject($eventlist[$obj->web_item_id], array('status'=>2), EVENT);
		}
	}
	
	function vote_multi(){
		$data = $_POST['opts'];
		if(!$data) return false;
		foreach($data as $object){
			if((int)$object['value'] > 2) continue;
			$this->web_item_user_id = $object['key'];
			if(!$this->web_item_user_id) return false;
			
			$request	= $this->curlobject(array('web_item_user_id' =>$this->web_item_user_id),	ITEMUSER, array('limit'=>1), false);
			
			//if($request->status != 0) { echo "You have already responded to this event."; exit; }
			$status = ((int)$object['value']<2?1:2);
			
			$this->putcurlobject($request->objectId, array('priority_order'=>(int)$object['value'],'status'=>$status,'was_received'=>false,
			    'status_datetime'=>array('__type'=>'Date', 'iso'=>date('Y-m-d\TH:i:s.000\Z') )), ITEMUSER);
			
		}
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