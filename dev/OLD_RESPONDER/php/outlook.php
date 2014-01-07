<?php
  $start   = $_GET['start'];
  $end     = $_GET['end'];
  $title   = $_GET['title'];
  $desc    = $_GET['desc'];
  
  $ical = "BEGIN:VCALENDAR
  VERSION:2.0
  PRODID:-//hacksw/handcal//NONSGML v1.0//EN
  BEGIN:VEVENT
  UID:".md5(uniqid(mt_rand(), true))."example.com
  DTSTAMP:".gmdate('Ymd')."T".gmdate('His')."Z
  DTSTART:".$start."
  DTEND:".$end."
  SUMMARY:".$title."
  DESCRIPTION:".$desc."
  END:VEVENT
  END:VCALENDAR";
  
  //set correct content-type-header
  header('Content-type: text/calendar; charset=utf-8');
  header('Content-Disposition: attachment; filename=calendar.ics'); //attachment if not inline
  echo $ical;
  exit;
?>