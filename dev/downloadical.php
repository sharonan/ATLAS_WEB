<?php

    
    // $data = $_GET["content"];
//     
//    
//     $data = str_replace('%3', ':', $data);
//     $data = str_replace('%0', '\n', $data);
//     
//     header("Content-type:text/calendar");
//         header('Content-Disposition: attachment; filename="calendar.ics"');
//         echo $data;
//     
//     
//     function ICS($data) {
//         
//         $this->data =$data;
//     }
//     function save() {
//         file_put_contents("calendar.ics",$data);
//     }
//     function show() {
//         header("Content-type:text/calendar");
//         header('Content-Disposition: attachment; filename="calendar.ics"');
//         echo $data;
//     }
    function downloadical(){
		// return the ics file
		$start    = $_POST['start'];
		$duration	= $_POST['duration'];
		$title    = $_POST['title'];
		$desc     = $_POST['desc']." Booked with Atlas. http://getatlas.com";
		$location = $_POST['location'];
		$this->ICS($start,$duration,$title,$desc,$location);
		$this->showical();
		// $this->template('ajax');
		exit;
	}
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
		echo $this->data;
	}

?>