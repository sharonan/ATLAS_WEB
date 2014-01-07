$(function() {

  Parse.$ = jQuery;

  // Initialize Parse with your Parse application javascript keys
  Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX",
		  "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67"); 

  var NOTE_MESSAGE = {
	         Title : 0,
	         Content: 1,
	         Catagory :2,
	         Date :3,
	         Star :4,
	         FromName:5,
	         FromId:6,
	         To:7
	       
	         
	}
  
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
var timeString="";

setDate = function(date1)
	{
	 // alert(date1);
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
	     
	    
	     timeString = " "+fromTimeTitle+" "+ampmTitleFrom+" on "+dateTitle;
	   
//Test1,Starbucks,135,PREF,2012:25:11 20:00:00,ALT1,2012:26:11 15:00:00,ALT2,2012:30:11 00:00:00,Hunter Atlas,dAOXqk6MTh	  		      
	  /// Time chooser options settings
	     
	}
  
  
  
  /// SHARE NOTE VIEW
  
  
  var shareNoteView = Parse.View.extend({
		events: {
	    },
		 el: ".content",
		initialize: function() {
	    	
		      _.bindAll(this);
		      this.render();
		    },

		   
		      
		      
		      render: function() {
		    	  
		    	  
			      this.delegateEvents();
			      
			  		var parseId = window.location.search;
			  		parseId = parseId.replace("?id=","");
			        
			      
			      if (parseId!=null && parseId!="" && parseId!=undefined)
			      {
			    	  var NoteShare = Parse.Object.extend("NotesShare");
			    	  var query = new Parse.Query(NoteShare);
			    	  query.get(parseId, {
			    		  success: function(note) {
			    			  
			    			  
			    			  var senderId = note.get("fromID");
			    			  var recieverId = note.get("toID");
			    			  
			    			  var messageNote = note.get("message");
			    			  
			    			  
			    			  
	    				  		var query1 = new Parse.Query(Parse.User);
	    				  		query1.get(senderId, { 
		          
	    				  			success: function(sender) {
	    				  				
//	    				  					var query2 = new Parse.Query(Parse.User);
//	    	    				  			query2.get(recieverId, { 
	    		          
//	    	    				  				success: function(reciever) {
	    	    				  				
	    	    				  					var messageTitle = messageNote.split(",")[NOTE_MESSAGE.Title];
	    	    				  					var messageContent = messageNote.split(",")[NOTE_MESSAGE.Content];
	    	    				  				
	    	    				  					var senderName = sender.get("displayname");
	    	    				  				//	var reciever = reciever.get("displayname");
	    	    				  					var reciever = messageNote.split(",")[NOTE_MESSAGE.To];

	    	    				  					
	    	    				  					var senderPic = sender.get("picture").url.toString();
	    	    									
	    	    									
	    	    									
	    	    									
	    	    									$("#avatar").attr('src', senderPic);
	    	    				  					
	    	    				  					
	    	    				  					
	    	    				  					setDate(messageNote.split(",")[NOTE_MESSAGE.Date]);

	    	    				  					
	    	    				  					$("#readIt").css('visibility','visible');
	    	    				  					$("#description").css('visibility','visible');
	    	    				  					$("#inviteSection").css('visibility','visible');
	    	    				  					$("#inviter").css('visibility','visible');
	    	    				  					$("#noteTitle").css('visibility','visible');
	    	    				  					$("#noteContent").css('visibility','visible');
	    	    				  					$("#notifyMessage").css('visibility','visible');
	    	    				  					
	    	    				  					
	    	    				  					$("#inviter").html(senderName).show();
	    	    				  					$("#noteTitle").html(messageTitle).show();
	    	    				  					$("#noteContent").html(messageContent).show();
	    	    				  					$("#notifyMessage").html("We'll let "+ senderName+" know you've read this note.").show();

	    	    				  					
	    	    				  					//Note Share: Organizer's Name has shared "Note Title" with you. Read note?

	    	    				  					if (!note.get("isRead"))
	    	    				  					{
	    	    				  						$("#readIt").click(function () {
	    	    				  						
	    	    				  							note.set("isRead",true);
	    	    				  							note.save(null, {
	    	    				  								success: function(eventRespond) {
	    	    				  									
	    	    				  									
	    	    				  									
	    	    				  									var	pushMessage =  senderName +" has read "+messageTitle;
		    	    				  	    	     					
  		    	    				  	    	     				// PUSH NOTIFICATION MESSAGE
  		    	    				  	    	     				//Event Response (2): Responder's Name has declined "Event Title".	
  		    	    				  	    	     				Parse.Push.send({
  		    						  									//	where: pushQuery,
  		    						  										 channels:["ID"+senderId],
  		    						  									  data: {
  		    						  									    alert: pushMessage,
  		    						  									    sound: "Incoming_Atlas_Push.mp3"
  		    						  										
  		    						  									  }
  		    						  									}, {
  		    						  									  success: function() {
  		    						  									    //alert(pushMessage);
  		    						  									  },
  		    						  									  error: function(error) {
  		    						  									    // Handle error
  		    						  										// alert(" failed to push!");
  		    						  									  }
  		    						  									});
	    	    				  									
	    	    				  									
	    	    				  									
	    	    				  									jAlert(senderName+ ' Has been informed, you read his note',reciever, function(r) {
	    	    				  								//	alert(senderName+", Has been informed, you read his note");
	    	    				  										$("#readIt").css('visibility','hidden');
	    		    	    				  							$("#notifyMessage").html(senderName+" was informed you read this note.").show();
	    	    				  									});
	    	    				  								},
	    	    				  							});
	    	    				  	
	    	    				  						});
	    	    				  					}
	    	    				  					else
	    	    				  						{
	    	    				  							$("#readIt").css('visibility','hidden');
	    	    				  							$("#notifyMessage").html(senderName+" was informed you read this note.").show();
 
	    	    				  						}
	    	    				  			//	}
	    	    				  		//	});
			      
	    				  			}
	    				  		});
			    		  }
	
			    	  });
		    
			      }
		      }
  
  });
  
  
  
  
  
  
  
  
  // The main view for the app
  var AppView = Parse.View.extend({
    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    el: $("#container"),

    initialize: function() {
      this.render();
    },

    render: function() {

    	//var uri = location.href; 
      	//var c = parseUri(uri).file;
    	$("#inviter").css('visibility','hidden');
			$("#noteTitle").css('visibility','hidden');
			$("#noteContent").css('visibility','hidden');
			$("#notifyMessage").css('visibility','hidden');
			$("#inviteSection").css('visibility','hidden');
			$("#readIt").css('visibility','hidden');
			$("#description").css('visibility','hidden');

      	new shareNoteView();
      	
      
    	
    	
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


  new AppRouter;
  new AppView;
  Parse.history.start();
});