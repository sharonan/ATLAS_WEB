$(function() {

  Parse.$ = jQuery;

  // Initialize Parse with your Parse application javascript keys
  Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX",
		  "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67"); 

  //  Model
  // ----------

  // Our basic Todo model has `content`, `order`, and `done` attributes.
  var Todo = Parse.Object.extend("Todo", {
    // Default attributes for the todo.
    defaults: {
      content: "empty todo...",
      done: false
    },

    // Ensure that each todo created has `content`.
    initialize: function() {
      if (!this.get("content")) {
        this.set({"content": this.defaults.content});
      }
    },

    // Toggle the `done` state of this  item.
    toggle: function() {
      this.save({done: !this.get("done")});
    }
  });

  // This is the transient application state, not persisted on Parse
  var AppState = Parse.Object.extend("AppState", {
    defaults: {
      filter: "all"
    }
  });

  var inviter_name;
  var invitee_name;
  var message;
  var location="";
  var title="";
  var finalDate;
  
  
  var eventPicked = 1;
  var INVITE_MESSAGE = {
	         Title : 0,
	         Location: 1,
	         Duration :2,
	         PREF :3,
	         PreferredDate :4,
	         ALT1:5 ,
	         AlternativeDate1 :6,
	         ALT2: 7,
	         AlternativeDate2:8,
	         fromDisplayName:9,
	         fromAtlasID:10
	         
	};
  var WEEK_DAY = {0:"Sunday",1:"Monday",2:"Tuesday",3:"Wednesday",4:"Thursday",5:"Friday",6:"Saturday"};
	var MONTH = 
	{
	    0 :"NotSet",
	    1: "January",
	    2: "February",
	    3: "March",
	    4: "April",
	    5: "May",
	    6: "June",
	    7: "July",
	    8: "August",
	    9: "September",
	    10:"October",
	    11: "November",
	    12: "December"
	}
  sendMessageBack = function(accept, message, invite, inviterId, inviteeId)
  {
	  var prefDatePicked = (parseInt(eventPicked)-1)*2 + 4;
	//  var prefOptionPicked = (parseInt(eventPicked)-1)*2 + 3;
	  
//	  var messageAcceptBack = (accept)? "ACCEPT,":"REJECT,";
//  	   messageAcceptBack +=    message.split(",")[prefOptionPicked]+","+
//  		   					   message.split(",")[prefDatePicked]+","+
//  	  						   message.split(",")[INVITE_MESSAGE.Title]+","+
//  	  						   message.split(",")[INVITE_MESSAGE.Location];
	 	 
	  
	  var duration = parseInt(message.split(",")[INVITE_MESSAGE.Duration]);

  	  var EventAcceptNew = Parse.Object.extend("EventAcceptNew");
  	  var eventAcceptNew = new EventAcceptNew();
  	  eventAcceptNew.set("inviteID",invite.id);
  	  eventAcceptNew.set("inviter",inviterId);
  //	  eventAcceptNew.set("message",messageAcceptBack);
  	  eventAcceptNew.set("invitee",inviteeId);
  	  
  	  
  	  eventAcceptNew.set("respond",accept);
  	  eventAcceptNew.set("time_choosen", message.split(",")[prefDatePicked]);
  	  eventAcceptNew.set("time_label",eventPicked);
  	  eventAcceptNew.set("event_title",message.split(",")[INVITE_MESSAGE.Title]);
  	eventAcceptNew.set("event_location", message.split(",")[INVITE_MESSAGE.Location]);
  	  
  	eventAcceptNew.set("event_duration", duration);
  	  
  	  eventAcceptNew.save(null, {
  		  success: function(eventRespond) {
  			  // The object was saved successfully.
  			finalDate = $("#day_date_"+eventPicked).html()+" @ "+
  						$("#time"+eventPicked+"from").html()+ " "+
  						$("#am_pm_from"+eventPicked).html();
  			location = message.split(",")[INVITE_MESSAGE.Location];
  			title = message.split(",")[INVITE_MESSAGE.Title];
  			//  alert("Youre event respond was succefully updated at inviter calander"+title+" "+location);
  			new calInviteSuccessView();
  		  },
  		  error: function(gameScore, error) {
  			  // The save failed.
  			  // error is a Parse.Error with an error code and description.
  			  
  			alert("Youre event respond has failed to updated at inviter calander! Error: " + error.code + " " + error.message);
  		  }
  	  });
    
  }
  
	setDate = function(date1,duration1,option)
	{
		//("date "+date1+" dur "+duration1+" option "+option);
		 var date1day = date1.split(" ")[0];
	      var hour1 = date1.split(" ")[1];
	      
	     var splitDate =  date1day.split(":");
	     var date1dayObject  = new Date(splitDate[0], parseInt(splitDate[2])-1, splitDate[1]);
	     var day = WEEK_DAY[(date1dayObject.getDay())];
	     var month = MONTH[parseInt(splitDate[2])];
	     
	     var dateTitle = day+", "+month+" "+splitDate[1]+", "+splitDate[0];
	     
	     
	     var time = hour1.split(":");
	     var timeHourFrom = parseInt(time[0]) % 12;
	     var timeMinutesFrom = time[1];
	     var amTimeFrom = parseInt(time[0]) < 12;
	     
	     var fromTimeTitle = timeHourFrom+":"+timeMinutesFrom;
	     var ampmTitleFrom = (amTimeFrom)? "AM" :"PM";
	     
	    
	     var durHour =(duration1>=60)? duration1/ 60: 0;
	     durHour = (durHour>0) ? durHour.toFixed(0):durHour;
	     var durMin =  duration1 % 60;
	     
	     
	     var toMinutes = durMin + parseInt(timeMinutesFrom);
	     if (toMinutes>59)
	    	 {
	    	// 	durHour +=  toMinutes / 60;
	    	 	toMinutes = toMinutes % 60;
	    	 	
	    	 }
	   var toHour = parseInt(timeHourFrom) + parseInt(durHour);
	   var ampmTitleTo = (toHour<12)? ((amTimeFrom)? "AM":"PM") :((amTimeFrom)? "PM":"AM");
	   toHour = toHour % 12;
	   var toMinutesTitle = (toMinutes<10)? "0"+toMinutes : toMinutes;
	   
	   var toTimeTitle = toHour+":"+toMinutesTitle;

	   
	   
//Test1,Starbucks,135,PREF,2012:25:11 20:00:00,ALT1,2012:26:11 15:00:00,ALT2,2012:30:11 00:00:00,Hunter Atlas,dAOXqk6MTh	  		      
	  /// Time chooser options settings
	      $("#day_date_"+option).html(dateTitle).show();
	      $("#time"+option+"from").html(fromTimeTitle).show();
	      $("#time"+option+"to").html(toTimeTitle).show();
	      $("#am_pm_from"+option).html(ampmTitleFrom).show();
	      $("#am_pm_to"+option).html(ampmTitleTo).show();
	     
	}
  
  setDateString= function(message,option) {
 	 
 	
 	 var prefDate = (option=="1")? INVITE_MESSAGE.PreferredDate :(option=="2")? 
 			INVITE_MESSAGE.AlternativeDate1 :INVITE_MESSAGE.AlternativeDate2;
 	 var date1 = message.split(",")[prefDate];
	 
 	 var duration1 = parseInt(message.split(",")[INVITE_MESSAGE.Duration]);
 	 
 	 
 	setDate(date1,duration1,option);
 	
 	
 	
 	 $("#option"+option).click(function () {
   	  eventPicked = option;
   	  var option2 = (option=="1")? "2" :(option=="2")? "1" :"1";
   	  var option3 = (option=="1")? "3" :(option=="2")? "3" :"2";
   	  
         $("#choose"+option).css('visibility','visible');
         $("#choose"+option2).css('visibility','hidden');
         $("#choose"+option3).css('visibility','hidden');
          
       });
	      
  }
  
  
 

  

  function parseUri (str) {
		var	o   = parseUri.options,
			m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
			uri = {},
			i   = 14;

		while (i--) uri[o.key[i]] = m[i] || "";

		uri[o.q.name] = {};
		uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
			if ($1) uri[o.q.name][$1] = $2;
		});

		return uri;
	};

	parseUri.options = {
		strictMode: false,
		key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
		q:   {
			name:   "queryKey",
			parser: /(?:^|&)([^&=]*)=?([^&]*)/g
		},
		parser: {
			strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
			loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
		}
	};
////// CAL INVITE SUCCESS SECTION
	
	var calInviteSuccessView = Parse.View.extend({
		events: {
	    },
		 el: ".content",
		initialize: function() {
	    	
		      _.bindAll(this);
		      this.render();
		    },

		   
		      
		      
		      render: function() {
		    	  
		    	  
		    	  this.$el.html(_.template($("#success-template").html()));
			      this.delegateEvents();
			      
			      $("#inviteeh1").attr('class', 'sub_header');

			      $("#invite_section").attr('class', 'container_sub_header');

			      $("#appointment").attr('class', 'appointment');
			      
		  		  $("#inviteeh1").html("Success!").show();
		  		  var eventDetails = title;
		  		  eventDetails = (location!=null && location!="" && location!=undefined)? eventDetails+" at "+location: eventDetails+" ";
		  		  
		  		  var successString = invitee_name+", Your appointment for "+eventDetails;
		  		  					   
		  		  					
		  		  					  
		  		$("#time_picked").html(finalDate).show();
		  		  
		  		$("#successString").html(successString).show();
		  		
		  		$("#withId").html( " with "+ inviter_name+" is confirmed ").show();
			      
	  		    $("#description #event").html("").show();

	  		    $("#avatar").css('visibility','hidden');
		    	  
		      }
	
	
	})
	
	
	
	
	
	
	
	
  
 /////// CAL INVITE VIEW SECTION
  var calInviteView = Parse.View.extend({
	    events: {
	    },

	    el: ".content",
	    
	    initialize: function() {
	    	
	      _.bindAll(this);
	      this.render();
	    },

	    

	    render: function() {
	    	
	    	
	    	
	    	 var INVITE_MESSAGE = {
	    	         Title : 0,
	    	         Location: 1,
	    	         Duration :2,
	    	         PREF :3,
	    	         PreferredDate :4,
	    	         ALT1:5 ,
	    	         AlternativeDate1 :6,
	    	         ALT2: 7,
	    	         AlternativeDate2:8,
	    	         fromDisplayName:9,
	    	         fromAtlasID:10
	    	         
	    	}
	    
	      this.$el.html(_.template($("#cal-template").html()));
	      this.delegateEvents();
	      
	      
	      
	
	  	var parseId = window.location.search;
      	parseId = parseId.replace("?id=","");
	        
	      //SPjDFlzY7c  
	      
	      if (parseId!=null && parseId!="" && parseId!="undefined")
	      {
	    	  
	    	  
	    	  
	    	  
	    	  var EventInviteNew = Parse.Object.extend("EventInviteNew");
	    	  var query = new Parse.Query(EventInviteNew);
	    	  query.get(parseId, {
	    		  success: function(invite) {
	      
	    			  var inviterId = invite.get("inviter");
	    			  var inviteeId = invite.get("invitee");
	    			  var inviterReadRespond =  invite.get("isRead");
	    			  
	    			  
	    			  
	    			  if (inviterId!=null && inviterId!="" && inviterId!=undefined &&
	    				  inviteeId!=null && inviteeId!="" && inviteeId!=undefined)
	    				  {
	    				  		var query1 = new Parse.Query(Parse.User);
	    				  		query1.get(inviterId, { 
		          
	    				  			success: function(inviter) {
	    				  				
	    				  				var query2 = new Parse.Query(Parse.User);
	    	    				  		query2.get(inviteeId, { 
	    		          
	    	    				  			success: function(invitee) {
	    	    				  				
	    	    				  				 inviter_name = inviter.get("displayname");
	    	    				  				 invitee_name = invitee.get("displayname");
	    	    				  				 message = invite.get("message");
	    	    				  				
	    	    				  				
	    	    				  				///// Check if already confirmed event...
	    	    				  				
	    	    				  				
	    	    				  				var CheckAcceptedNew = Parse.Object.extend("EventAcceptNew");
	    	    				  	    	    var checkQuery =  new Parse.Query(CheckAcceptedNew);
	    	    				  	    	    checkQuery.equalTo("inviteID", parseId);

	    	    				  	    	    checkQuery.find({
	    	    				  	    	    	success: function(inviteConfimedCheckup) {
	    	    				  	    	    		if (inviteConfimedCheckup.length!=0)
	    	    				  	    		    	{/// Already responded to the event
	    	    				  	    		    	
		    	  	    	    				  		  
	    	    				  	    	    			

	    	    				  	    	    			location = " ";
		    	  	    	    				  		       location  = inviteConfimedCheckup[0].get("event_location");
		    	  	    	    				  		      
		    	  	    	    				  		      location = ( location!=null && location!="")? " at "+location : " ";
		    	  	    	    				  		      
		    	  	    	    				  		      
		    	  	    	    				  		      title  = inviteConfimedCheckup[0].get("event_title");
		    	  	    	    				  		      var accept  = inviteConfimedCheckup[0].get("respond");
		    	  	    	    				  		      
		    	  	    	    				  		      var acceptText = (accept)? " confirmation " : " rejection ";
		    	  	    	    				  		      
		    	  	    	    				  		      $("#description #inviteeh1").html("Hi "+invitee_name+"! "+inviter_name+" already got your "+acceptText+" to ").show();

		    	  	    	    				  	          $("#description #event").html(title+location).show();
		    	  	    	    				  		     $("#dateImg1").css('visibility','hidden');
		    	  	    	    				  			 $("#dateImg2").css('visibility','hidden');
		    	  	    	    				  			 $("#dateImg3").css('visibility','hidden');
		    	  	    	    				  		      if (accept)
		    	  	    	    				  		      {
		    	  	    	    				  		    	  	var timeChoose  = inviteConfimedCheckup[0].get("time_choosen");
		    	  	    	    				  		    	  	var timeLabel  = inviteConfimedCheckup[0].get("time_label");
		    	  	    	    				  		    	  	var duration  = inviteConfimedCheckup[0].get("event_duration");
				    	  	    	    				  			$("#dateImg"+timeLabel).css('visibility','visible');
				    	  	    	    				  			$("#choose"+timeLabel).css('visibility','visible');
		    	  	    	    				  		    	  	
		    	  	    	    				  		    	  	setDate(timeChoose,duration,timeLabel);
		    	  	    	    				  		      }
		    	  	    	    				  		      
		    	  	    	    				  		      
		    	  	    	    				  		      $("#confirmEvent").css('visibility','hidden');
	    	  	    	    				  			      $("#decline").css('visibility','hidden');
	    	  	    	    				  			      $("#confirmSubtext").html("");
	    	    				  	    	    			
			    	  	    	    				  			

	    	    				  	    		    	}
	    	    				  	    	    		else
	    	    				  	    		    		{
	    	    				  	    		    		
	    	    				  	    	    			 //// Header settings
	    	  	    	    				  		      $("#description #inviteeh1").html("Hi "+invitee_name+"! "+inviter_name+" has invited you to:").show();
	    	  	    	    				  		    
	    	  	    	    				  		      var eventText = message.split(",")[INVITE_MESSAGE.Title];
	    	  	    	    				  		       location = message.split(",")[INVITE_MESSAGE.Location];
	    	  	    	    				  		      eventText += (location!=undefined && location !=null && location!="" )? " at "+location : "";
	    	  	    	    				  		      $("#description #event").html(eventText).show();
	    	  	    	    				  		      $("#header_img").attr('visibility','images/header_img.png');
	    	  	    	    				  			
	    	  	    	    				  		      $("#confirmSubtext").html("Keep in mind whichever time you choose will instantly populate into "+inviter_name+" calander").show();
	    	  	    	    				  		      
	    	  	    	    				  		 	 var date1 = message.split(",")[INVITE_MESSAGE.PreferredDate];
	    	  	    	    				  		 	 if (date1!=null && date1!="" && date1!="undefined")
	    	  	    	    				  		 	 {
	    	  	    	    				  		 		 $("#choose1").css('visibility','visible');
	    	  	    	    				  		 	     $("#dateImg1").css('visibility','visible');
	    	  	    	    				  		 		 this.setDateString(message,1);
	    	  	    	    				  		 	 }
	    	  	    	    				  			 //$("#dateImg1").css('visibility','hidden');
	    	  	    	    				  		 		 var date2 =  message.split(",")[INVITE_MESSAGE.AlternativeDate1];
	    	  	    	    				  		 		if (date2!=null && date2!="" && date2!="undefined")
	    	  	 	    	    				  		   {
		    	  	    	    				  		 	     $("#dateImg2").css('visibility','visible');

	    	  	    	    				  		 		  this.setDateString(message,2);
	    	  		    	    				  		 	  
	    	  	 	    	    				  		   }
	    	  	 	    	    				  			   
	    	  	    	    				  		 		var date3 =  message.split(",")[INVITE_MESSAGE.AlternativeDate2];

	    	  	    	    				  		 		  if (date3!=null && date3!="" && date3!="undefined")
	    	  		 	    	    				  		   {
	 	    	  	    	    				  		 	     $("#dateImg3").css('visibility','visible');

	    	  	    	    	    				  		    this.setDateString(message,3);

	    	  		 	    	    				  		   }

	    	  	    	    				  		 		  
	    	  	    	    				  		 		  // accept message pattern... 
	    	  	    	    				  			      //ACCEPT,2012:30:11 16:00:00,Grab coffee
	    	  	    	    				  			      $("#confirmEvent").click(function() {  
	    	  	    	    				  			    	
	    	  	    	    				  			    	sendMessageBack(true,message, invite,inviterId,inviteeId);
	    	  	    	    				  			      
	    	  	    	    				  			      
	    	  	    	    				  			      });
	    	  	    	    				  			      // reject message pattern... 
	    	  	    	    				  			      //REJECT,2012:29:11 04:41:00,Test CAA
	    	  	    	    				  			      $("#decline").click(function() {
	    	  		    	    				  			    	sendMessageBack(false,message, invite,inviterId,inviteeId);

	    	  	    	    				  			      });
	    	    				  	    		    		
	    	    				  	    		    		
	    	    				  	    		    		
	    	    				  	    		    		
	    	    				  	    		    		
	    	    				  	    		    		}
	    	    				  	    		    
	    	    				  	    		    
	    	    				  	    		    
	    	    				  	    		  },
	    	    				  	    		  error: function(error) {
	    	    				  	    		    alert("Error: " + error.code + " " + error.message);
	    	    				  	    		  }
	    	    				  	    		});
	    	    				  				
	    	    				  				
	    	    				  				
	    	    				  				
	    	    				  				
	    	    				  				
	    	    				  		 		  
	    	    				  		 		  
	    	    				  		 		  
	    	    				  		 		  
	    	    				  			}
	    	    				  		});

	    				  			}
	    				  		});
	    				  	}
	    		  		}
	    			});
	      		}
	      
	      
	      
	      
	      
	    }// end of render function 
	    
	  });
 ///// END CAL INVITE VIEW
  
  
  
  // The main view for the app
  var AppView = Parse.View.extend({
    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    //el: $("#container"),

    initialize: function() {
      this.render();
    },

    render: function() {

    	//var uri = location.href; 
      	//var c = parseUri(uri).file;
      	
      	
      	new calInviteView();
      	
      
    	
    	
    }
  });
  

  var AppRouter = Parse.Router.extend({
    routes: {
      "all": "all",
      "active": "active",
      "completed": "completed"
    },

    initialize: function(options) {
    },

    all: function() {
      state.set({ filter: "all" });
    },
  
    active: function() {
      state.set({ filter: "active" });
    },

    completed: function() {
      state.set({ filter: "completed" });
    }
  });

  var state = new AppState;

  new AppRouter;
  new AppView;
  Parse.history.start();
});
