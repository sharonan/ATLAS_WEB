<?php
  $start    = $_GET['start'];
  $end      = $_GET['end'];
  $title    = $_GET['title'];
  $desc     = $_GET['desc'];
  $location = $_GET['location'];
  
  $ical = 'BEGIN:VCALENDAR
  
  VERSION:2.0
  
  PRODID:-//Get Atlas//EN
  
  CALSCALE:GREGORIAN
  
  BEGIN:VEVENT
  
  UID:'.md5($title).'
  
  DTSTAMP:'.gmdate("Ymd").'T'.gmdate("His").'Z
  
  DTSTART:'.$start.'
  
  DTEND:'.$end.'
  
  SUMMARY:'.addslashes($title).'
  
  LOCATION:'.addslashes($location).'
  
  DESCRIPTION:'.addslashes($desc).'
  
  URL;VALUE=URI:http://getAtlas.com/'.$title.'
  
  END:VEVENT
  
  END:VCALENDAR';
  
  //set correct content-type-header
  header('Content-type: text/calendar; charset=utf-8');
  header('Content-Disposition: attachment; filename=calendar.ics');
  echo $ical;
  exit;
?>