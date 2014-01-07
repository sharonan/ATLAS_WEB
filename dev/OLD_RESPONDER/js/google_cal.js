GoogleCalendar = function() {
  return {
	/**
	  * Returns link to add to google calendar
	  * @param event = {
		  what, //title
		  dates : {
			  start: {
				  year,month,day,hour,minute
			  },
			  end: {
				  year,month,day,hour,minute
			  }
		  },
		  location,
		  trp, //show busy true/false
		  details,
		  url : window.location.href
	  * }
	*/
	  getGoogleCalendarEventLink : function(event) {
		  var url = "http://www.google.com/calendar/event?action=TEMPLATE";
		  url += "&text=" + urlFormat(event.what);
		  url += "&dates=" + getDateString(event.dates);
		  url += "&details=" + urlFormat(event.details);
		  url += "&location=" + urlFormat(event.location);
		  url += "&trp=" + event.trp;
		  url += "&sprop=" + urlFormat(event.url);
		  
		  return url;
	  }
  };
  
  function urlFormat(str) {
	  var newstr = str.replace(/^\s+/g, "");
	  return encodeURIComponent(newstr.replace(/\s+$/g, ""));
  }
  
  // Determines the CGI argument for dates parameter based on user input
  function getDateString( dates ) {
	  var dateString = "";
	  
	  dateString += getUTCDateString(dates.start.year,
	  dates.start.month,
	  dates.start.day,
	  dates.start.hour,
	  dates.start.minute);
	  dateString += "/";
	  dateString += getUTCDateString(dates.end.year,
	  dates.end.month,
	  dates.end.day,
	  dates.end.hour,
	  dates.end.minute);
	  return dateString;
  }
  
  // Converts the given time into UTC, returns this in a string
  function getUTCDateString(y,m,d,h,min) {
	  var timeObj = new Date(y,m,d,h,min);
	  var dateStr = "" + timeObj.getUTCFullYear();
	  dateStr += stringPad(timeObj.getUTCMonth()+1);
	  dateStr += stringPad(timeObj.getUTCDate());
	  dateStr += "T" + stringPad(timeObj.getUTCHours());
	  dateStr += stringPad(timeObj.getUTCMinutes()) + "00Z";
	  return dateStr;
  }
  
  // Add a leading '0' if string is only 1 char
  function stringPad(str) {
	  var newStr = "" + str;
	  if (newStr.length == 1) {
		  newStr = "0" + newStr;
	  }
	  return newStr;
  }
}();