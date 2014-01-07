<?php
/// DATA SENT...
// $data ="BEGIN:VCALENDAR%0D%0A" .
// 		"VERSION:2.0%0D%0A" .
// 		"PRODID:Get Atlas%0D%0A" .
// 		"METHOD:PUBLISH%0D%0A" .
// 		"BEGIN:VEVENT%0D%0A".
// 		"UID:".md5($name).
// 		"%0D%0ADTSTAMP:".date("Ymd\THis\Z").
// 		"%0D%0ADTSTART:".date("Ymd\THis\Z",$stamp).
// 		"%0D%0ADTEND:".date("Ymd\THis\Z",($stamp + (60*$duration))).
// 		"%0D%0ASUMMARY:".$name.
// 		"%0D%0ADESCRIPTION:".($desc?$desc:$name).
// 		"%0D%0ALOCATION:".$location.
// 		"%0D%0AEND:VEVENT%0D%0A".
// 		"END:VCALENDAR";
$data = $_GET;
// print_r(" data ".$data );
// print_r(" data ".count($_GET) );

$duration = $data['duration'];
$start = $data['start'];
$location = $data['location'];
$name = $data['title'];
$description = $data['desc'];
$webitem = $data['webitemid'];

// print_r(" end ".$data['end'] );
// print_r(" start ".$data['start'] );
// print_r(" location ".$data['location'] );
// print_r(" name ".$data['title'] );
// print_r(" description ".$data['desc'] );
// print_r(" webitem ".$data['webitemid'] );


// if(count($_GET) > 0) {
//             if(!empty($args)) {
//                 $lastkey = "";
//                 $pairs = explode("&",$data);
//                 print_r(" data ".count($pairs) );
//                 foreach($pairs as $pair) {
//                     if(strpos($pair,":") !== false) {
//                         list($key,$value) = explode(":",$pair);
//                         unset($_GET[$key]);
//                         $lastkey = "&$key$value";
//                     } elseif(strpos($pair,"=") === false)
//                         unset($_GET[$pair]);
// 
//                     else {
//                         list($key, $value) = explode("=",$pair);
//                         $_GET[$key] = $value;
//                     }
//                 }
//             } 
//             return "?".((count($_GET) > 0)?http_build_query($_GET).$lastkey:"");
//         }


// $data2 = $_GET["duration"]
// // print_r(" data ".$data );
// // print_r(" data2 ".$data2 );
// $dataArray = explode('&', $data);
// 
// $start = $dataArray[0];
// print_r(" start ".$start );
// $duration = $dataArray[1];
// print_r(" end ".$end );
// $name = $dataArray[2];
// print_r(" name ".$name );
// 
// $description = $dataArray[3];
// print_r(" description ".$description );
// 
// $location = $dataArray[4];
// print_r(" location ".$location );
// 
// $webitem = $dataArray[5];
// print_r(" webitem ".$webitem );

// 
//  $pos = strpos($data, 'DTSTART:');
//  $pos = $pos+9;
//  $pos2 = strpos($data, 'DTEND:');
//  
//  $start = substr($data, $pos, $pos2-$pos-1);
//  $pos2 = $pos2 + 7;
//  $pos = strpos($data, 'SUMMARY:');
//  
//  $end = substr($data, $pos2, $pos-$pos2-1);
// 
//  $pos = $pos + 8;
//  $pos2  = strpos($data, 'DESCRIPTION:');
//  
//  $name = substr($data, $pos, $pos2-$pos-1);
//  
//  $pos2  = $pos2 + 13;
//  $pos = strpos($data, 'LOCATION:');
//  $description = substr($data, $pos2, $pos-$pos2-1);
//  
//  $pos = $pos + 10;
//  $pos2  = strpos($data, 'END:VEVENT');
//  $location = substr($data, $pos, $pos2-$pos-1);
//  
//   print_r(" pos2 ".$pos2 );
  // if ($pos2 !== false && $pos2-$pos-1>0)
//     {
//     		

//  googleCal($start,$duration,$name,$description,$location,$webitem){
	 	
	 	
	 	$return = explode('.', str_replace("T", " ", $start), -1);
		$stamp = strtotime($return[0]);

// 		$url = "http://www.google.com/calendar/event?action=TEMPLATE";
// 		$url .= "&text=" . urlencode($name);
	 	
// 	 	$return1 = explode('.', str_replace("T", " ", $start), -1);
//   		$stamp1 = strtotime($return[0]);
  		
//   		$return2 = explode('.', str_replace("T", " ", $end), -1);
//   		$stamp2 =  strtotime($return[0]);
// // 		
// // 		$return2 = explode('.', str_replace("T", " ", $end), -1);
// // 		$stamp2 = strtotime($return[0]);
// 
		$url = "http://www.google.com/calendar/event?action=TEMPLATE";
		$url .= "&text=" . urlencode($name);
// 		print_r(" text ".$name );
// 		$url .= "&dates=" . $start."/".$end;

		$url .= "&dates=" . date("Ymd\THis\Z",$stamp)."/".date("Ymd\THis\Z",($stamp + (60*$duration)));

// 		$url .= "&dates=" . date("Ymd\THis\Z",$stamp1)."/".$end;
		$url .= "&details=" . urlencode($description);
// 		print_r(" details ".$description );
		$url .= "&location=" . $location;
// 		print_r(" location ".$location );
		$url .= "&trp=true";
		$url .= "&sprop=" . urlencode('http://www.getatlas.com/respond/'.$webitem);
// 
		$data = echo $url;
		echo $data;
// 	}

?>