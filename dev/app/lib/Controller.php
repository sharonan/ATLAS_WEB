<?php
class Controller {

	protected $_model;
	protected $_controller;
	protected $_action;
	protected $_template;
	protected $user_id;
	protected $ajax;
	protected $querystring;
	public $data;
	public $name;
	
	public $eventInvitees;
	public $eventInviter;
	public $eventInvitee;

	function ICS($start,$duration,$name,$description,$location) {
		$return = explode('.', str_replace("T", " ", $start), -1);
		$stamp = strtotime($return[0]);
		$this->name = $name;
		$this->data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Get Atlas//EN\r\nMETHOD:PUBLISH\r\nBEGIN:VEVENT\r\nDTSTART:".date("Ymd\THis\Z",$stamp)."\r\nDTEND:".date("Ymd\THis\Z",($stamp + (60*$duration)))."\r\nLOCATION:".$location."\r\nTRANSP:OPAQUE\r\nSEQUENCE:0\r\nUID:".md5($name)."\r\nDTSTAMP:".date("Ymd\THis\Z")."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".($description?$description:$name)."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\nBEGIN:VALARM\r\nTRIGGER:-PT10080M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
	}
	function showical() {
		header("Content-type:text/calendar");
		header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
		Header('Content-Length: '.strlen($this->data));
		Header('Connection: close');
// 		print_r($this->data);
		echo $this->data;
	}

	function googleCal($start,$duration,$name,$description,$location,$webitem){
		$return = explode('.', str_replace("T", " ", $start), -1);
		$stamp = strtotime($return[0]);

		$url = "http://www.google.com/calendar/event?action=TEMPLATE";
		$url .= "&text=" . urlencode($name);
		$url .= "&dates=" . date("Ymd\THis\Z",$stamp)."/".date("Ymd\THis\Z",($stamp + (60*$duration)));
		$url .= "&details=" . urlencode($description);
		$url .= "&location=" . urlencode($location);
		$url .= "&trp=true";
		$url .= "&sprop=" . urlencode('http://'.(DIRECTORY=='web'?'www':DIRECTORY).'.getatlas.com/respond/'.$webitem);

		echo $url;
	}

	function __construct($model, $controller, $action, $queryString='') {

		$this->_controller = $controller;
		$this->_action = $action;
		$this->_model = $model;

		$this->$model = new $model;
		$this->_template = new Template($controller,$action);

		$this->querystring = $queryString;

		/*
		if(isset($_SESSION['user_id'])){
			$this->user_id = $_SESSION['user_id'];
		} else {
			$this->user_id = 0;
		}
		*/

		$this->user_id = 0;

	}


	/*
	* Makes a variable avaliable to the view
	*/
	public function set($name,$value) {
		$this->_template->set($name,$value);
	}


	public function __destruct() {
		$this->_template->render();
	}


	/*
	* Redirects to given page
	*/
	public function redirect($page) {
		header("Location: ".$page);
		exit();
	}


	/*
	* Checks that a value is set, and not blank
	*
	* @var string
	*/
	public function checkSet($var){
		if((isset($var)) && ($var!=''))
			return true;
		else
			return false;
	}

	/*
	* Checks and returns a get, post, session, request, or global variable in that order.
	*/
	public function checkAll($name){
		if(isset($_GET[$name]))		return $_GET[$name];
		if(isset($_POST[$name]))		return $_POST[$name];
		if(isset($_SESSION[$name]))	return $_SESSION[$name];
		if(isset($_REQUEST[$name]))	return $_REQUEST[$name];
		if(isset($GLOBALS[$name]))	return $GLOBALS[$name];
	}


	/*
	* Empties and resets a session variable
	*/
	public function session($name, $value){
		if(isset($_SESSION[$name]))	unset($_SESSION[$name]);
		$_SESSION[$name] = $value;
	}


	/*
	* Unsets a variable of any type
	*/
	public function destroy($name){
		if(isset($_SESSION[$name]))	unset($_SESSION[$name]);
		if(isset($_GET[$name]))		unset($_GET[$name]);
		if(isset($_POST[$name]))		unset($_POST[$name]);
		if(isset($_REQUEST[$name]))	unset($_REQUEST[$name]);
		if(isset($GLOBALS[$name]))	unset($GLOBALS[$name]);
	}

	/*
	* Checks for a set variable of POST,
	* GET (in that order)  -
	* then makes it availble to the view for use,
	* AND the controller for manipulation.
	* (used mostly for form variables)
	* Used like so:
	* $this->submitted('username');
	* or
	* $this->username = $this->submitted('username');
	*/
	public function submitted($var){
		if		(isset($_POST[$var]))	$temp = $_POST[$var];
		elseif	(isset($_GET[$var]))	$temp = $_GET[$var];
		else	$temp = '';

		$this->set($var, $temp);
		return $temp;
	}

	/*
	* Makes a page only accessible by logged in users.
	*/
	public function isAuthorized(){
		if($this->user_id == 0) $this->redirect("/user/login");
	}
	/*
	public function curl ($id) {
		$input['objectId']	= $id;
		foreach($input as $key=>$value) $input[$key] = strval($value);
		$input = json_encode($input);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);
		$ch = curl_init(USEROBJECT);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		return json_decode($result);
	}
	*/
	public function timeduration($time, $duration){
		date_default_timezone_set('America/Los_Angeles');
		$return = explode('.', str_replace("T", " ", $time), -1);
		$stamp = strtotime($return[0]." UTC");
		$returnarray = array(	'date'=>date("D, F j", $stamp),
					'time'=>date("g:ia", $stamp)." - ".date("g:ia", ($stamp + (60*$duration))." UTC")
		    );
		date_default_timezone_set('UTC');
		return $returnarray;
	}

	public function timestr($time, $duration){
		$return = explode('.', str_replace("T", " ", $time), -1);
		$stamp = strtotime($return[0]." UTC");
		return date("D, F j", $stamp)." from ".date("g:ia", $stamp)." to ".date("g:ia", ($stamp + (60*$duration))." UTC");
	}


	public function postcurlobject($insert, $type = EVENT, $ops=false)  {
		$array = $insert + array(
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
		if($ops) $array += $ops;
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);
		$ch = curl_init($type);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		return json_decode($return->result);
	}

	public function putcurlobject($objectid, $insert, $type = EVENT, $ops=false)  {
		$array = $insert + array(
		    '_method'		=>'PUT',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
		if($ops) $array += $ops;
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);
		$ch = curl_init($type."/$objectid");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		return $return->updatedAt;

	}

	public function curlobject ($where, $type = ITEM_USER, $ops=false, $allresults=false)  {
		$array = array(
		    'where'		=> $where,
		    '_method'		=>'GET',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
		if($ops) $array += $ops;

		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init($type);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		if($allresults)
			return $return->results;
		else return $return->results[0];

	}
	public function pushMultiNotification($user, $name,  $title, $webitem){
	
	// echo "confirm_single c.";
		$array = array(
		    'where'=> array(
			   'channels' => array('$in'=>array("ID$user"))
			   //,
			   //'deviceType'=>'ios',
		    ),
//		    "expiration_time"=>"1970-01-01T00:00:00.004Z",
		    "data"=> array(
				"alert"=>"$name has voted on '$title'.",
				"I"=> $webitem,
				"badge"=>"Increment",
				"sound"=>"push.aiff",
				"T"=> "event",
			),
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
	
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init("https://api.parse.com/1/push");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		
// 		echo "confirm_single d.";
		
		return $return->results;
	}
	// $users = array(1, 2, 3, 4...);
	public function pushNotification($user, $name, $time, $title, $webitem){
	
	// echo "confirm_single c.";
		$array = array(
		    'where'=> array(
			   'channels' => array('$in'=>array("ID$user"))
			   //,
			   //'deviceType'=>'ios',
		    ),
//		    "expiration_time"=>"1970-01-01T00:00:00.004Z",
		    "data"=> array(
				"alert"=>"$name has chosen a time for '$title'.",
				"I"=> $webitem,
				"badge"=>"Increment",
				"sound"=>"push.aiff",
				"T"=> "event",
			),
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
	
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init("https://api.parse.com/1/push");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		
// 		echo "confirm_single d.";
		
		return $return->results;
	}
public function pushDeclinedEventNotification($user, $name,  $title, $webitem){
	
	// echo "confirm_single c.";
		$array = array(
		    'where'=> array(
			   'channels' => array('$in'=>array("ID$user"))
			    
			   //,
			   //'deviceType'=>'ios',
		    ),
//		    "expiration_time"=>"1970-01-01T00:00:00.004Z",
		    "data"=> array(
				"alert"=>"$name has declined your invitation for '$title'.",
				
				"I"=> $webitem,
				"badge"=>"Increment",
				"sound"=>"push.aiff",
				"T"=> "event",
			),
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
	
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init("https://api.parse.com/1/push");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		
// 		echo "confirm_single d.";
		
		return $return->results;
	}
	public function pushCanceledEventNotification($user, $name,  $title, $webitem){
	
	// echo "confirm_single c.";
		$array = array(
		    'where'=> array(
			   'channels' => array('$in'=>array("ID$user"))
			    
			   //,
			   //'deviceType'=>'ios',
		    ),
//		    "expiration_time"=>"1970-01-01T00:00:00.004Z",
		    "data"=> array(
				"alert"=>"$name has canceled your invitation for '$title'.",
				
				"I"=> $webitem,
				"badge"=>"Increment",
				"sound"=>"push.aiff",
				"T"=> "event",
			),
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
	
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init("https://api.parse.com/1/push");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		
// 		echo "confirm_single d.";
		
		return $return->results;
	}
	public function pushCantMakeItEventNotification($user, $name,  $title, $webitem){
	
	 echo "pushCantMakeItEventNotification c.";
		$array = array(
		    'where'=> array(
			   'channels' => array('$in'=>array("ID$user"))
// 			   'channels' => array('$in'=>array("ID7bZKFSY8CP"))
			   //,
			   //'deviceType'=>'ios',
		    ),
//		    "expiration_time"=>"1970-01-01T00:00:00.004Z",
		    "data"=> array(
				"alert"=>"$name can't make it to '$title'.",
				// "alert"=>"Sharona can't make it to TESTING.",
				"I"=> $webitem,//"79086bbb66c7a46174b9a3e13c5da563",//
				"badge"=>"Increment",
				"sound"=>"push.aiff",
				"T"=> "event",
			),
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);
	
		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init("https://api.parse.com/1/push");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		
// 		echo "confirm_single d.";
		
		return $return->results;
	}
	public function pushNotificationVerifyEmail($userId){
		$array = array(
		    'where'=> array(
			   'channels' => array('$in'=>array("ID$userId"))
			   //,
			   //'deviceType'=>'ios',
		    ),
//		    "expiration_time"=>"1970-01-01T00:00:00.004Z",
		    "data"=> array(
				"alert"=>"You have verified your email! You're all set!",
				"sound"=>"push.aiff",
				"pushType"=> "verify"
			),
		    '_method'		=>'POST',
		    '_ApplicationId'=>APPLICATION_ID,
		    '_JavaScriptKey'=>JAVASCRIPT_KEY,
//		    '_ClientVersion'=>'js1.2.0',
//		    "_InstallationId"=>"0bcff9fe-bc17-b8fb-5ce0-329b0e19e986"
		);

		$input = json_encode($array);
		$headers = array(
			'X-Parse-Application-Id: ' . APPLICATION_ID,
			'X-Parse-REST-API-Key: ' . REST_API_KEY,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($input),
		);

		$ch = curl_init("https://api.parse.com/1/push");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($result);
		return $return->results;
	}
	/*
		https://api.parse.com/1/classes/event/Pmj0oXyMj8 EEqU1HJnsl 8aZCvTMQbR
	{"status":1,"_method":"PUT"
	{"status":2....x2

		https://api.parse.com/1/push
	{"channels":["ID1Xg4djyEAM"],"expiration_time":"1970-01-01T00:00:00.004Z","data":{"alert":"You're all booked! Tommy Test has chosen 7:00PM on Sunday, March 3 for 'Test event'.","badge":"Increment","sound":"push.aiff","title":"Atlas"}
	 */
	 
function emailDeclined($invitee, $inviter, $event)
{
		$times = $this->timeduration($event->start_datetime->iso, $event->duration);
	   $start    = $event->start_datetime->iso;
		$duration	= $event->duration;
		$title    = $event->title;
		$desc     = $event->message." \n\n". $event->notes." ".$event->phone_number." Booked with Atlas. http://getatlas.com";
		$location = $event->location;
	

		$return = explode('.', str_replace("T", " ", $start), -1);
		$stamp = strtotime($return[0]);
// 	
		$name = $event->title." with ".$inviter->first_name." ".$inviter->last_name;
// 		$data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Get Atlas//EN\r\nMETHOD:PUBLISH\r\nBEGIN:VEVENT\r\nDTSTART:".date("Ymd\THis\Z",$stamp)."\r\nDTEND:".date("Ymd\THis\Z",($stamp + (60*$duration)))."\r\nLOCATION:".$location."\r\nTRANSP:OPAQUE\r\nSEQUENCE:0\r\nUID:".md5($name)."\r\nDTSTAMP:".date("Ymd\THis\Z")."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".($desc?$desc:$name)."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\nBEGIN:VALARM\r\nTRIGGER:-PT10080M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";


	$vars = array(
			'env'			=> DIRECTORY,
			'notes'	=> $event->notes,
			'location'	=> $event->location,//The Coffee Bean & Tea Leaf, South Westlake Boulevard, Westlake Village, CA, United States',//$event->location,
			'title'	=> $event->title,
			'time'	=> $times['time'],
			'inviter_name'=> $inviter->first_name,
			'first_name'=> $inviter->first_name,
			'last_name'=> $inviter->last_name,
			 'web_item_user_id' => $event->web_event_id,
			 'web_item_id' => $event->web_event_id,
			 'phone_number' => $inviter->phone_number,
			 'duration' => $event->duration,
			 'message' => $event->message,
			 'data' => $data,
			'other_invitee'=> $inviter->first_name ." and You " ,
			'desc' => $desc,
			'endTime' => date("Ymd\THis\Z",($stamp + (60*$duration))),
			'startTime' => $start,
			'googleUrl' => $url,
			'user_picture' => $inviter->picture->url,
			'date'=> $times['date'],
			// 'datetime_created'=> date("F d, Y"),
		);

// 		if($email->email_address === $user->email){
			// $vars = array(
// 			    'env'			=> DIRECTORY,
// 			    'user_picture'	=> $inviter->picture->url,
// 			    'user_firstname'=> $inviter->first_name,
// 			    'datetime_created'=> date("F d, Y"),
// 			);

		

		



// 			$message ="<html><head> <meta name='viewport' content='width=device-width'> <style type='text/css'>@import url(http://fonts.googleapis.com/css?family=PT+Sans); </style> <title></title></head><body style='background-color: #EEE; font-family: 'PT Sans', sans-serif; margin: 0px;' bgcolor='#EEE'> <table cellspacing='0' cellpadding='0' style='width: 649px; overflow: hidden; margin: 10px auto; background-repeat: repeat; background-image: url(http://getatlas.com/emails/img/New/pattern.png); border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; -webkit-box-shadow: rgba(0,0,0,.2) 0 2px 4px;'> <tr> <td><img src='http://getatlas.com/emails/img/New/border_pattern.png'></td> </tr> <tr style='background:url(http://files.parse.com/5a57d7f6-d602-41d6-9011-ce32454fe355/9ff41023-2a50-4559-b5dc-84d04206de08-image.png) 1% no-repeat; background-size: 88px 88px;'> <td> <table width='100%'> <tr> <td style='font-size: 30px; color: #3c76c3;'> <center> lasha,<br> please verify your email. </center> </td> </tr> </table> </td> </tr> <tr> <td><img src='http://getatlas.com/emails/img/New/divider.png'></td> </tr> <tr> <td> <center style='font-size: 20px;'> <br> <i style='color: #3c76c3; font-size: 24px;'>lasha@getatlas.com</i><br> <br> <span style='font-size: 14px;'>If you didn't initiate this verify process, please ignore this email.</span><br> <br> <a href='http://web.getatlas.com/verify/045c37bf5e3ba72974ab126f77ea5bfe'><img src='http://getatlas.com/emails/img/button-confirm.png'></a><br> <br> Your feedback is very important to us. We want to make<br> scheduling meetings and phone calls as easy as possible.<br> <br> With your help, we can<br> <img src='http://getatlas.com/emails/img/team-atlas.png'><br> <br> </center> </td> </tr> <tr> <td><img src='http://getatlas.com/emails/img/New/border_pattern.png'></td> </tr> </table> <div style='width: 649px; margin: 10px auto; font-size: 12px; color: #7d7d7d; text-align:center;'> Appointment Negotiation is a service provided by Atlas.<br> Copyright &copy; Atlas Powered, LLC - 820 Broadway, Santa Monica, CA 90401<br> <br> <a href='http://support.getatlas.com' style='color: #7d7d7d;'>Need Support? Click here.</a><br> <br> <a href='http://web.getatlas.com/verify/045c37bf5e3ba72974ab126f77ea5bfe'><img src='http://getatlas.com/emails/img/New/atlas_btn.png' alt='Atlas Homepage'></a><br> </div></body></html>";
			$message = $this->mailDeclined($vars);
// 			echo "SEND EMAIL.".$data; 
			$firstName = $inviter->first_name;
			$lastName = $inviter->last_name;
			$subject = "Cancelled '$event->title' event";
			$insert = array(  
			    'body'		=> $message,
			    'from'		=> 'team@getatlas.com',
			    'from_name'	=> 'Team Atlas',
			    'is_sent'	=> false,
			    'is_pending'=> false,
			    'has_error'	=> false,
			    'subject'	=> $subject,
			    'to'		=>  $invitee->email, //'sharon@getatlas.com',
			);
			$this->postcurlobject($insert, 'https://api.parse.com/1/classes/outbound_email');
	 	
}
	 
function mailDeclined($vars=false)
{
$message = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>
<div style="font-family:&#39;PT Sans&#39;,sans-serif;margin:0px;min-width:649px" bgcolor="#EEE">
<table cellspacing="0" cellpadding="0" width="649" style="overflow:hidden;margin:10px auto;border-radius:8px" background="http://getatlas.com/emails/img/New/pattern.png">
<tr>
<td><img src="http://getatlas.com/emails/img/New/border_pattern.png"></td>
</tr>
<tr>
<td>
<table>
<tr>
<td><img width="80" height="80" src="*|user_picture|*"  style="border-radius:40px;border:1px solid #ccc"></td>
<td width="489">
<center style="font-size:30px;color:#3c76c3">
&quot;*|title|*&quot; has been cancelled!
</center>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><img src="http://getatlas.com/emails/img/New/divider.png"></td>
</tr>
<tr>
<td>
<center style="font-size:24px;color:#3c76c3;margin-top:10px">
<i>Here are the original event details:</i>
</center>
</td>
</tr>
<tr>
<td>
<table width="95%" cellspacing="20" style="font-size:18px">
<tr>
<td width="20%" align="right" style="color:#7d7d7d" valign="top">Time:</td>
<td style="font-size:20px">*|date|*<br>
<span style="font-size:16px">*|time|*</span></td>
</tr>
<tr>
<td width="20%" align="right" style="color:#7d7d7d" valign="top">Location:</td>
<td>*|location|*</td>
</tr>
<tr>
<td width="20%" align="right" style="color:#7d7d7d" valign="top">Attendees:</td>
<td>*|other_invitee|*</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><img src="http://getatlas.com/emails/img/New/divider.png"></td>
</tr>
<tr>
<td>
<center>
<img src="http://getatlas.com/emails/img/New/device.png">
</center>
</td>
</tr>
<tr>
<td style="font-size:17px;color:#7d7d7d">
<center>
Get the free Atlas app for iPhone and start scheduling today!
</center>
</td>
</tr>
<tr>
<td>
<center>
<a><img src="http://getatlas.com/emails/img/New/app_store_btn.png" alt="Download Atlas on the App Store" style="margin:15px"></a>
</center>
</td>
</tr>
<tr>
<td><img src="http://getatlas.com/emails/img/New/border_pattern.png"></td>
</tr>
</table>
<div width="649" style="margin:10px auto;font-size:12px;color:#7d7d7d;text-align:center">Appointment Negotiation is a service provided by Atlas.<br>
Copyright ©Atlas Powered, LLC - 820 Broadway, Santa Monica, CA 90401<br>
<br>
<a style="color:#7d7d7d">Need Support? Click here.</a><br>
<br>
<a><img src="http://getatlas.com/emails/img/New/atlas_btn.png" alt="Atlas Homepage"></a><br>
</div>
</div>
</body></html>';

if($vars){
			foreach($vars as $key=>$item){
				$message = str_replace('*|'.$key.'|*', $item, $message);
			}
		}
		if ($message)
		return $message;
}
function emailBooked($invitee, $inviter, $event, $otherInvitee)
	{
	 	
	   $times = $this->timeduration($event->start_datetime->iso, $event->duration);
	   $start    = $event->start_datetime->iso;
		$duration	= $event->duration;
		$title    = $event->title;
		$desc     = $event->message." \n\n". $event->notes." ".$event->phone_number." Booked with Atlas. http://getatlas.com";
		$location = $event->location;
		// $this->ICS($start,$duration,$title,$desc,$location);
// 		$this->showical();
// 		// $this->template('ajax');
// 		exit;
// 	}
// 	function ICS($start,$duration,$name,$description,$location) {
		$return = explode('.', str_replace("T", " ", $start), -1);
		$stamp = strtotime($return[0]);
// 		$this->name = $name;
		$name = $event->title." with ".$inviter->first_name." ".$inviter->last_name;
// 		$data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Get Atlas//EN\r\nMETHOD:PUBLISH\r\nBEGIN:VEVENT\r\nDTSTART:".date("Ymd\THis\Z",$stamp)."\r\nDTEND:".date("Ymd\THis\Z",($stamp + (60*$duration)))."\r\nLOCATION:".$location."\r\nTRANSP:OPAQUE\r\nSEQUENCE:0\r\nUID:".md5($name)."\r\nDTSTAMP:".date("Ymd\THis\Z")."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".($desc?$desc:$name)."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\nBEGIN:VALARM\r\nTRIGGER:-PT10080M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";

// 		$data = 'BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Get Atlas//EN\r\nMETHOD:PUBLISH\r\nBEGIN:VEVENT\r\nDTSTART:".date("Ymd\THis\Z",$stamp)."\r\nDTEND:".date("Ymd\THis\Z",($stamp + (60*$duration)))."\r\nLOCATION:".$location."\r\nTRANSP:OPAQUE\r\nSEQUENCE:0\r\nUID:".md5($name)."\r\nDTSTAMP:".date("Ymd\THis\Z")."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".($desc?$desc:$name)."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\nBEGIN:VALARM\r\nTRIGGER:-PT10080M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n';
// 	}


$url = "http://www.google.com/calendar/event?action=TEMPLATE";
		$url .= "&text=" . urlencode($name);
// 		print_r(" text ".$name );
// 		$url .= "&dates=" . $start."/".$end;

		$url .= "&dates=" . date("Ymd\THis\Z",$stamp)."/".date("Ymd\THis\Z",($stamp + (60*$duration)));

// 		$url .= "&dates=" . date("Ymd\THis\Z",$stamp1)."/".$end;
		$url .= "&details=" . urlencode($desc);
// 		print_r(" details ".$description );
		$url .= "&location=" . $location;
// 		print_r(" location ".$location );
		$url .= "&trp=true";
		$url .= "&sprop=" . urlencode('http://web.getatlas.com/respond/'.$event->web_event_id);





		
$data ="BEGIN:VCALENDAR%0D%0A" .
		"VERSION:2.0%0D%0A" .
		"PRODID:Get Atlas%0D%0A" .
		"METHOD:PUBLISH%0D%0A" .
		"BEGIN:VEVENT%0D%0A".
		"UID:".md5($name).
		"%0D%0ADTSTAMP:".date("Ymd\THis\Z").
		"%0D%0ADTSTART:".date("Ymd\THis\Z",$stamp).
		"%0D%0ADTEND:".date("Ymd\THis\Z",($stamp + (60*$duration))).
		"%0D%0ASUMMARY:".$name.
		"%0D%0ADESCRIPTION:".($desc?$desc:$name).
		"%0D%0ALOCATION:".$location.
		"%0D%0AEND:VEVENT%0D%0A".
		"END:VCALENDAR";
		
		$vars = array(
			'env'			=> DIRECTORY,
			'notes'	=> $event->notes,
			'location'	=> $event->location,//The Coffee Bean & Tea Leaf, South Westlake Boulevard, Westlake Village, CA, United States',//$event->location,
			'title'	=> $event->title,
			'time'	=> $times['time'],
			'inviter_name'=> $inviter->first_name,
			'first_name'=> $inviter->first_name,
			'last_name'=> $inviter->last_name,
			 'web_item_user_id' => $event->web_event_id,
			 'web_item_id' => $event->web_event_id,
			 'phone_number' => $inviter->phone_number,
			 'duration' => $event->duration,
			 'message' => $event->message,
			 'data' => $data,
			'other_invitee'=> $otherInvitee,
			'desc' => $desc,
			'endTime' => date("Ymd\THis\Z",($stamp + (60*$duration))),
			'startTime' => $start,
			'googleUrl' => $url,
			'date'=> $times['date'],
			// 'datetime_created'=> date("F d, Y"),
		);

// 		if($email->email_address === $user->email){
			// $vars = array(
// 			    'env'			=> DIRECTORY,
// 			    'user_picture'	=> $inviter->picture->url,
// 			    'user_firstname'=> $inviter->first_name,
// 			    'datetime_created'=> date("F d, Y"),
// 			);

		

		



// 			$message ="<html><head> <meta name='viewport' content='width=device-width'> <style type='text/css'>@import url(http://fonts.googleapis.com/css?family=PT+Sans); </style> <title></title></head><body style='background-color: #EEE; font-family: 'PT Sans', sans-serif; margin: 0px;' bgcolor='#EEE'> <table cellspacing='0' cellpadding='0' style='width: 649px; overflow: hidden; margin: 10px auto; background-repeat: repeat; background-image: url(http://getatlas.com/emails/img/New/pattern.png); border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; -webkit-box-shadow: rgba(0,0,0,.2) 0 2px 4px;'> <tr> <td><img src='http://getatlas.com/emails/img/New/border_pattern.png'></td> </tr> <tr style='background:url(http://files.parse.com/5a57d7f6-d602-41d6-9011-ce32454fe355/9ff41023-2a50-4559-b5dc-84d04206de08-image.png) 1% no-repeat; background-size: 88px 88px;'> <td> <table width='100%'> <tr> <td style='font-size: 30px; color: #3c76c3;'> <center> lasha,<br> please verify your email. </center> </td> </tr> </table> </td> </tr> <tr> <td><img src='http://getatlas.com/emails/img/New/divider.png'></td> </tr> <tr> <td> <center style='font-size: 20px;'> <br> <i style='color: #3c76c3; font-size: 24px;'>lasha@getatlas.com</i><br> <br> <span style='font-size: 14px;'>If you didn't initiate this verify process, please ignore this email.</span><br> <br> <a href='http://web.getatlas.com/verify/045c37bf5e3ba72974ab126f77ea5bfe'><img src='http://getatlas.com/emails/img/button-confirm.png'></a><br> <br> Your feedback is very important to us. We want to make<br> scheduling meetings and phone calls as easy as possible.<br> <br> With your help, we can<br> <img src='http://getatlas.com/emails/img/team-atlas.png'><br> <br> </center> </td> </tr> <tr> <td><img src='http://getatlas.com/emails/img/New/border_pattern.png'></td> </tr> </table> <div style='width: 649px; margin: 10px auto; font-size: 12px; color: #7d7d7d; text-align:center;'> Appointment Negotiation is a service provided by Atlas.<br> Copyright &copy; Atlas Powered, LLC - 820 Broadway, Santa Monica, CA 90401<br> <br> <a href='http://support.getatlas.com' style='color: #7d7d7d;'>Need Support? Click here.</a><br> <br> <a href='http://web.getatlas.com/verify/045c37bf5e3ba72974ab126f77ea5bfe'><img src='http://getatlas.com/emails/img/New/atlas_btn.png' alt='Atlas Homepage'></a><br> </div></body></html>";
			$message = $this->mailEventBooked($vars);
// 			echo "SEND EMAIL.".$data; 
			$firstName = $inviter->first_name;
			$lastName = $inviter->last_name;
			$subject = "Your appointment with " .$firstName." ". $lastName." is Booked";
			$insert = array(  
			    'body'		=> $message,
			    'from'		=> 'team@getatlas.com',
			    'from_name'	=> 'Team Atlas',
			    'is_sent'	=> false,
			    'is_pending'=> false,
			    'has_error'	=> false,
			    'subject'	=> $subject,
			    'to'		=>  $invitee->email, //'sharon@getatlas.com',
			);
			$this->postcurlobject($insert, 'https://api.parse.com/1/classes/outbound_email');
	 	
	}
	public function mailEventBooked($vars = false)
	{
		
// <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8">
// <script type="text/javascript" src="http://getatlas.com/js/jquery.calendario.js"></script></head><body>

	$message = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><META http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body><div style="background-color:#eee;font-family:&#39;PT Sans&#39;,sans-serif;margin:0px;min-width:649px" bgcolor="#EEE"><table cellspacing="0" cellpadding="0" style="width:649px;overflow:hidden;margin:10px auto;background-repeat:repeat;background-image:url(http://getatlas.com/emails/img/New/pattern.png);border-radius:8px"><tr><td><img src="http://getatlas.com/emails/img/New/border_pattern.png"></td>
</tr><tr style="background:url() 1% no-repeat;font-size:30px;color:#3c76c3">
<td><center>You & *|inviter_name|* are all booked for...<br><b style="font-size:40px">&quot;*|title|*&quot;</b><br>
</center></td></tr><tr><td><img src="http://getatlas.com/emails/img/New/divider.png"></td></tr><tr><td><center style="font-size:24px;color:#3c76c3;margin-top:10px">
<i>Congrats! Here are the details.</i></center></td></tr><tr><td><table width="95%" cellspacing="20" style="font-size:18px"><tr>
<td width="20%" align="right" style="vertical-align:top;color:#7d7d7d" valign="top">Time:</td><td><table width="100%"><tr>
<td width="50%" style="vertical-align:top;font-size:20px" valign="top">*|date|*<br><span style="font-size:16px">*|time|*</span></td>
<td><a><img src="http://getatlas.com/emails/img/New/checkmark.png" alt="Choose this time"></a></td></tr></table></td>
</tr><tr><td width="20%" align="right" style="vertical-align:top;color:#7d7d7d" valign="top">Location:</td><td><a href="http://maps.google.com/?q=*|location|*" target="_blank">*|location|*</a></td></tr>
<tr><td width="20%" align="right" style="vertical-align:top;color:#7d7d7d" valign="top">Invitees:</td><td>*|other_invitee|*</td>
</tr><tr><td width="20%" align="right" style="vertical-align:top;color:#7d7d7d" valign="top">Note:</td><td>*|notes|*</td></tr>
</table></td></tr><tr><td><center style="font-size:24px;color:#3c76c3"><i>Click to add this meeting to your calendar.</i></center></td>
</tr><tr><td><center><table cellspacing="10"><tr><td><a href="*|googleUrl|*"><img src="http://getatlas.com/emails/img/New/google_btn.png" alt="Download Atlas on the App Store"></a>
</td><td><a href="http://web.getatlas.com/showical.php?data=*|data|*"><img src="http://getatlas.com/emails/img/New/ical_btn.png" alt="Download Atlas on the App Store"></a>
</td><td><a href="http://web.getatlas.com/showical.php?data=*|data|*"><img src="http://getatlas.com/emails/img/New/outlook_btn.png" alt="Download Atlas on the App Store"></a>
</td></tr></table></center></td></tr><tr><td><center><a style="color:#7d7d7d;font-size:17px">Can&#39;t make it anymore? Click here.</a>
</center></td></tr><tr><td><img src="http://getatlas.com/emails/img/New/divider.png"></td></tr><tr><td><center><img src="http://getatlas.com/emails/img/New/device.png">
</center></td></tr><tr><td style="font-size:17px;color:#7d7d7d"><center>Get the free Atlas app for iPhone and start scheduling today!
</center></td></tr><tr><td><center><a><img src="http://getatlas.com/emails/img/New/app_store_btn.png" alt="Download Atlas on the App Store" style="margin:15px"></a>
</center></td></tr><tr><td><img src="http://getatlas.com/emails/img/New/border_pattern.png"></td></tr></table><div style="width:649px;margin:10px auto;font-size:12px;color:#7d7d7d;text-align:center">
Appointment Negotiation is a service provided by Atlas.<br>Copyright © Atlas Powered, LLC - 820 Broadway, Santa Monica, CA 90401<br>
<br><a style="color:#7d7d7d">Need Support? Click here.</a><br><br><a><img src="http://getatlas.com/emails/img/New/atlas_btn.png" alt="Atlas Homepage"></a><br></div></div></body></html>';
		
	
	// echo success;
// 	else{
// 	echo fail;
		if($vars){
			foreach($vars as $key=>$item){
				$message = str_replace('*|'.$key.'|*', $item, $message);
			}
		}
		if ($message)
		return $message;
		
	}
	/*
	* Creates an email from an HTML email template, replaces
	* all *|variables|* with $vars (array), then creates
	* a readable text version of the HTML email on the fly
	* automatically.
	*/
	function mailitem($vars = false){
		
		//$message = file_get_contents(ROOT . '/app/emails/' . $template . '.html');
		$message = '
<html>
<head>
<title>Atlas</title>
</head>
<body  style="background-color: #ffffff; font: arial, sans-serif;padding:0;margin:0;">
<center>
<table border="0" cellpadding="0" cellspacing="0" width="640" align="center">
<tr style="background-color: #5d94d6; color: #fff;">
<td width="0px" height="0px"></td>
<td colspan="1" style="padding-left:25px;padding-top:5px;font: 18px \'Helvetica Neue\',\'Arial\',\'sans-serif\';">*|datetime_created|*</td>
</tr>
<tr>
<td width="0px" height="0px"></td>
<td rowspan="2" width="640px" height="130" align="center" valign="bottom" style="padding-bottom: 12px; padding-right: 5px; border-right:1px solid black;"><img src="*|user_picture|*" width="130" height="130" /></td>
</tr>
<tr>
<td height="293" colspan="2"  width="640px"><img src="http://*|env|*.getatlas.com/img/email/top-blue-bg5.png" /></td>
</tr>
<tr>
<td colspan="2" align="center" style="font: bold 26px \'Helvetica Neue\',\'Arial\',\'sans-serif\';color:#3066af;">Welcome to Atlas, *|user_firstname|*</td>
</tr>
<tr>
<td height="15" colspan="2"> </td>
</tr>
<tr>
<td height="34" colspan="2" align="center"><img src="http://*|env|*.getatlas.com/img/email/atlas-icon.png" /></td>
</tr>
<tr>
<td colspan="2" height="25"> </td>
</tr>
<tr>
<td colspan="2" height="13"><img src="http://*|env|*.getatlas.com/img/email/divider.png" /></td>
</tr>
<tr>
<td colspan="2" height="25"> </td>
</tr>
<tr>
<td colspan="2" align="center" style="font: 30px \'Helvetica Neue\',\'Arial\',\'sans-serif\';color:#1b1b1b;line-height: 1.4em;">
Your feedback is very important to us.<br />
We want to make scheduling meetings<br />
and phone calls as easy as possible.
<br /><br />
With your help, we can.
</td>
</tr>
<tr>
<td colspan="2" height="15"> </td>
</tr>
<tr>
<td height="45" colspan="2" align="center" style="font: bold 30px \'Helvetica Neue\',\'Arial\',\'sans-serif\';color:#3066af;">Team Atlas</td>
</tr>
<tr>
<td height="34" colspan="2" align="center"><img src="http://*|env|*.getatlas.com/img/email/atlas-icon.png" /></td>
</tr>
<tr>
<td colspan="2" height="25"> </td>
</tr>
<tr>
<td colspan="2" align="center" style="font: 25px \'Helvetica Neue\',\'Arial\',\'sans-serif\';color:#1b1b1b;">
Check out our YouTube channel for great Atlas tutorials.
</td>
</tr>
<tr>
<td height="100" colspan="2" align="center"><a href="http://youtube.com/getatlas" title="Tutorials on YouTube"><img src="http://*|env|*.getatlas.com/img/email/tutorials.png" width="261" height="63" alt="Tutorials on Youtube" /></a></td>
</tr>
<tr>
<td colspan="2" height="75"> </td>
</tr>
<tr>
<td colspan="2" align="center" width="605" height="31"><img src="http://*|env|*.getatlas.com/img/email/tagline.png" width="605" height="31" /></td>
</tr>
<tr>
<td colspan="2" height="25"> </td>
</tr>
<tr>
<td colspan="2" height="15">
<table width="640">
<tr align="center">
<td width="206" height="99"><a href="http://*|env|*.getatlas.com/"><img width="206" height="60" src="http://*|env|*.getatlas.com/img/email/app-store.png" /></a></td>
<td width="99" height="99"><a href="http://*|env|*.getatlas.com/"><img width="99" height="99" src="http://*|env|*.getatlas.com/img/email/logo.png" /></a></td>
<td width="206" height="99"><a href="http://*|env|*.getatlas.com/"><img width="206" height="60" src="http://*|env|*.getatlas.com/img/email/google-play.png" /></a></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" height="85"> </td>
</tr>
</table>
</center>
</body>';
		if($vars){
			foreach($vars as $key=>$item){
				$message = str_replace('*|'.$key.'|*', $item, $message);
			}
		}

		return $message;
	}




	function saveThumbnail($source_path, $target_path, $filename, $scale=true, $w=800, $h=600){

		//	$source_path	= $_FILES[ 'Image1' ][ 'tmp_name' ];
		//	$target_path	= img/id/4;
		//	$filename		= photo

		$filename = $filename . '.jpg';

		list( $source_width, $source_height, $source_type ) = getimagesize( $source_path );

		switch ( $source_type ) {
			case IMAGETYPE_GIF:
				$source_gdim = imagecreatefromgif( $source_path );
				break;

			case IMAGETYPE_JPEG:
				$source_gdim = imagecreatefromjpeg( $source_path );
				break;

			case IMAGETYPE_PNG:
				$source_gdim = imagecreatefrompng( $source_path );
				break;
		}

		$source_aspect_ratio = $source_width / $source_height;
		$desired_aspect_ratio = $w / $h;

		if ($scale) {
			//Scale the image to fit the area
			if ( $source_aspect_ratio < $desired_aspect_ratio ) {
				//
				// Triggered when source image is wider
				//
				$temp_height = $h;
				$temp_width = ( int ) ( $h * $source_aspect_ratio );
			} else {
				//
				// Triggered otherwise (i.e. source image is similar or taller)
				//
				$temp_width = $w;
				$temp_height = ( int ) ( $w / $source_aspect_ratio );
			}
		} else {
			//Explode the image, cut off extra from border
			if ( $source_aspect_ratio > $desired_aspect_ratio ) {
				//
				// Triggered when source image is wider
				//
				$temp_height = $h;
				$temp_width = ( int ) ( $h * $source_aspect_ratio );
			} else {
				//
				// Triggered otherwise (i.e. source image is similar or taller)
				//
				$temp_width = $w;
				$temp_height = ( int ) ( $w / $source_aspect_ratio );
			}
		}

		//
		// Resize the image into a temporary GD image
		//
		$temp_gdim = imagecreatetruecolor( $temp_width, $temp_height );
		$white = imagecolorallocate($temp_gdim, 255, 255, 255);
		imagefill($temp_gdim,0,0,$white);
		imagecopyresampled(
			$temp_gdim,
			$source_gdim,
			0, 0,
			0, 0,
			$temp_width, $temp_height,
			$source_width, $source_height
		);

		//
		// Copy cropped region from temporary image into the desired GD image
		//
		$x0 = ( $temp_width - $w ) / 2;
		$y0 = ( $temp_height - $h ) / 2;

		$desired_gdim = imagecreatetruecolor( $w, $h );

		imagecopy(
			$desired_gdim,
			$temp_gdim,
			0, 0,
			$x0, $y0,
			$w, $h
		);

		$white2 = imagecolorallocate($desired_gdim, 255, 255, 255);
		imagefill($desired_gdim,0,0,$white2);
		imagefill($desired_gdim,$w-1,$h-1,$white2);

		$image_path = ROOT.'/'.$target_path."/";
		if(!is_dir($image_path)){
			mkdir($image_path, 0777, true);
		}
		if(imagejpeg( $desired_gdim, $image_path.$filename, 100 )){
			return $target_path. '/' . $filename;
		}
		else
			return false;

	}

	function template($option){
		global $set_template;
		$set_template = $option;
	}
	function view($option){
		global $set_view;
		$set_view = $option;
	}
}