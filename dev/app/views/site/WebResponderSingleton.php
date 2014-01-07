<?php
class WebResponderSingleton extends Singleton
{
    protected static $instance = null;
    
    private static $atlasInviterUser = null;
    private static $webItemUserId = null;
    private static $webItemUserIdItemUser = null;
    private static $nonAtlasUser = null;
    private static $allEvents = null;
    private static $primaryEvent = null;
    
    private static $allItemUserEvents = null;
    private static $eventPicked;
    private static $nonAtlasUserItemUsers = null;
    private static $allAttendeesNames = null;
    
    private static $webItemUserIdSession;
    
    private static $currentView;
    protected function __construct()
    {
        
    }
    protected function __clone()
    {
       
    }

    public static function getInstance($webItemUserId)
    {
     static::$webItemUserIdSession = Session::get("webItemUserIdSession");
     if (!static::$webItemUserIdSession.isSessionSet($webItemUserId))
    
        // if (!isset(static::$instance)) {
        {
            static::$instance = new static;
            session_start();
			// include the class
			//require_once("Session.php"); (included in base.inc.php file)
			
			
			static::$webItemUserIdSession = array (
							'webItemUserId' => static::$webItemUserId,
							'nonAtlasUser' => static::$nonAtlasUser,
							'atlasInviterUser' => static::$atlasInviterUser,
							'allEvents' =>static::$allEvents,
							'allItemUserEvents' =>static::$allItemUserEvents,
						    'allAttendees' => static::$allAttendeesNames,
						    'currentView' => static::$currentView );

		
			Session::set("webItemUserIdSession", static::$webItemUserIdSession);
        }
        return static::$instance;
    }
    public function setAllItemUserEvent()
    {
    	static::$allEvents = 	Session::get("webItemUserIdSession.allEvents");
    	static::$webItemUserIdItemUser = 	Session::get("webItemUserIdSession.webItemUserIdItemUser");
    	if (static::$allEvents != null && static::$webItemUserIdItemUser != null) 
    	{
    		$eventIdArray[] = $primaryEvent->web_event_id;
		
			//for alt events - note, it adds the info for the itemUser for each alt event (selected and web_item_user_id) down below
			foreach($allEvents as $altEvent)
			{	
				$eventIdArray[] = $altEvent->web_event_id;
			}
			$inputs = array('web_item_id'=>array('$in'=>$eventIdArray),'atlas_id'=>$webItemUserIdItemUser->atlas_id);
		
			static::$allItemUserEvents	= $this->curlobject($inputs,	ITEM_USER,	false, true);
			static::$webItemUserIdSession['allItemUserEvents'] = static::$allItemUserEvents;
			Session::set("webItemUserIdSession", static::$webItemUserIdSession);
    	}
    }
    public function getAllItemUserEvent()
    {
//     	return static::$allItemUserEvents;
    	return Session::get("webItemUserIdSession.allItemUserEvents");

    }
    
    public function setCurrentEvent()
    {
        static::$webItemUserId = 	Session::get("webItemUserIdSession.webItemUserId");

    	if (static::$webItemUserId != null)
    	{
    		$itemUser	= $this->curlobject(array('web_item_user_id'=>$webItemUserId), ITEM_USER, array('limit'=>1), false);
			//get the event for that itemUser
			static::$webItemUserIdItemUser = $itemUser;
			$event	= $this->curlobject(array('web_event_id'=>$itemUser->web_item_id), EVENT, array('limit'=>1), false);
			static::$primaryEvent = $event;
			//get all the alternate events
			$altEventArray = $this->curlobject(array('primary_web_event_id'=>$itemUser->web_item_id), EVENT, array('order'=>'display_order'), true);
			array_push($altEventArray, $event);
			static::$allEvents = $altEventArray;
			
			static::$webItemUserIdSession['allEvents'] = static::$allEvents;
			Session::set("webItemUserIdSession", static::$webItemUserIdSession);
    	}
    }
     public function getCurrentView()
    {
//     	return static::$allEventRecords;
    	return Session::get("webItemUserIdSession.currentView");
    }
     public function setCurrentView($currentView)
    {
    	
    		static::$currentView = $currentView;
    		static::$webItemUserIdSession['currentView'] = static::$currentView;
			Session::set("webItemUserIdSession", static::$webItemUserIdSession);

    	
    }
    public function getCurrentEvent()
    {
//     	return static::$allEventRecords;
    	return Session::get("webItemUserIdSession.allEvents");
    }
     public function setWebItemUserId($webItemUserId)
    {
    	if ($webItemUserId != null)
    	{
    		static::$webItemUserId = $webItemUserId;
    		static::$webItemUserIdSession['webItemUserId'] = static::$webItemUserId;
			Session::set("webItemUserIdSession", static::$webItemUserIdSession);

    	}
    }
    public function getWebItemUserId()
    {
//     	return static::$webItemUserId;
    	return Session::get("webItemUserIdSession.webItemUserId");
    }
    public function setNonAtlasUser($objectId)
    {
    	if ($objectId != null)
    	{
    		static::$nonAtlasUser = $this->curlobject(array('objectId' => $objectId),	USER, array('limit'=>1), false);
    		static::$webItemUserIdSession['nonAtlasUser'] = static::$nonAtlasUser;
			Session::set("webItemUserIdSession", $webItemUserIdSession);

    	}
    }
    public function getNonAtlasUser()
    {
//     	return static::$nonAtlasUser;
    	return Session::get("webItemUserIdSession.nonAtlasUser");
    }
     public function setCurrentInviter($objectId)
    {
    	if ($objectId != null)
    	{
    		static::$atlasInviterUser = $this->curlobject(array('objectId' => $objectId),	USER, array('limit'=>1), false);
			static::$webItemUserIdSession['atlasInviterUser'] = static::$atlasInviterUser;
			Session::set("webItemUserIdSession", static::$webItemUserIdSession);

    	}
    }
    public function getCurrentAtlasInviter()
    {
//     	return static::$atlasInviterUser;
    	return Session::get("webItemUserIdSession.atlasInviterUser");

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
}