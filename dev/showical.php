<?php

//     print "Hello world!"; 
// print_r($_GET);
 $data = $_GET["data"];
 
 $pos = strpos($data,'SUMMARY:');
//   print_r($pos );
//   $pos = strpos($data,":",$pos);
  $pos = $pos+8;
  $fname = "Atlas Event";
//    print_r(" and ".$pos );
 if ($pos !== false)
  {
  $pos2 = strpos($data, 'DESCRIPTION:', $pos);
//   print_r(" pos2 ".$pos2 );
  if ($pos2 !== false && $pos2-$pos-1>0)
    {
    	$fname = substr($data, $pos, $pos2-$pos-2);	
//      print_r(" name ".$name );
    }
    else
    {
    $fname = "Atlas Event";
//     print_r(" name1 ".$name );
    }
} else {
 $fname = "Atlas Event";
//     print_r(" name2 ".$name );
}
 
//     "%0D%0ADESCRIPTION:".($desc?$desc:$name).
//		"%0D%0ALOCATION:".$location.
//    
//     $data = str_replace('%3', ':', $data);
//     $data = str_replace('%0', '\n', $data);
//     
//     header("Content-type:text/calendar");
//         header('Content-Disposition: attachment; filename="calendar.ics"');
//         echo $data;
//     
//     
 //    function showical($data) {
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
//      }
    // function downloadical(){
// 		// return the ics file
// 		$start    = $_POST['start'];
// 		$duration	= $_POST['duration'];
// 		$title    = $_POST['title'];
// 		$desc     = $_POST['desc']." Booked with Atlas. http://getatlas.com";
// 		$location = $_POST['location'];
// 		$this->ICS($start,$duration,$title,$desc,$location);
// 		$this->showical();
// 		$this->template('ajax');
// 		exit;
// 	}
// 	function ICS($start,$duration,$name,$description,$location) {
// 		$return = explode('.', str_replace("T", " ", $start), -1);
// 		$stamp = strtotime($return[0]);
// 		$this->name = $name;
// 		$this->data = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Get Atlas//EN\r\nMETHOD:PUBLISH\r\nBEGIN:VEVENT\r\nDTSTART:".date("Ymd\THis\Z",$stamp)."\r\nDTEND:".date("Ymd\THis\Z",($stamp + (60*$duration)))."\r\nLOCATION:".$location."\r\nTRANSP:OPAQUE\r\nSEQUENCE:0\r\nUID:".md5($name)."\r\nDTSTAMP:".date("Ymd\THis\Z")."\r\nSUMMARY:".$name."\r\nDESCRIPTION:".($description?$description:$name)."\r\nPRIORITY:1\r\nCLASS:PUBLIC\r\nBEGIN:VALARM\r\nTRIGGER:-PT10080M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR\r\n";
// 	}
	// function showical($data) {
// 	echo 'hello';
// 	 $this->data =$data;
	 // file_put_contents("calendar.ics",$data);
// 		header("Content-type:text/calendar");
// 				header('Content-Disposition: attachment; filename="calendar.ics"');
// 
//header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
// // 		Header('Content-Length: '.strlen($this->data));
// 		Header('Connection: close');
// 		echo $data;


// file_put_contents("calendar.ics",$data);
//  print_r(" name1 ".$fname );
// print_r('filename="'.$fname.'.ics"');
 // $name = "test";//$fname.".ics";
// //  print_r('filename="'.$name);
header("Content-type:text/calendar");
// // // $fname = " test with sharon ";
header('Content-Disposition: attachment; filename="'.$fname.'.ics"');
// //  				header('Content-Disposition: attachment; filename="calendar.ics"');
// // 
         Header('Content-Length: '.strlen($data));
         Header('Connection: close');
         echo $data;

// 	}

?>