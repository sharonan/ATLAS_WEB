$(function() {

  Parse.$ = jQuery;

  // Initialize Parse with your Parse application javascript keys
  Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX",
		  "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67"); 

  var TASK_MESSAGE = {
	         Title : 0,
	         Content: 1,
	         Catagory :2,
	         Deadline:3,
	         Date :4,
	         To:5
	       
	         
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
  
  
  var accept = true;
  /// SHARE NOTE VIEW
  
  
  var taskView = Parse.View.extend({
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
			    	  var NoteShare = Parse.Object.extend("TaskAssignNew");
			    	  var query = new Parse.Query(NoteShare);
			    	  query.get(parseId, {
			    		  success: function(task) {
			    			  
			    			 
			    			
			    			  
			    			  //ted,Hy,02Medium,DEADLINE,2012:27:11 08:13:22,Hunter Atlas,dAOXqk6MTh
			    			  var senderId = task.get("fromID");
			    			  var recieverId = task.get("toID");
			    			  
			    			  var messageTask = task.get("message");
			    			  
			    			  
			    			  
	    				  		var query1 = new Parse.Query(Parse.User);
	    				  		query1.get(senderId, { 
		          
	    				  			success: function(sender) {
	    				  				
//	    				  					var query2 = new Parse.Query(Parse.User);
//	    	    				  			query2.get(recieverId, { 
//	    		          
//	    	    				  				success: function(reciever) {
	    	    				  					var messageTitle = messageTask.split(",")[TASK_MESSAGE.Title];
	    	    				  					var messageContent = messageTask.split(",")[TASK_MESSAGE.Content];
	    	    				  				
	    	    				  					var senderName = sender.get("displayname");
	    	    				  					
	    	    				  					
	    	    				  					var reciever = messageTask.split(",")[TASK_MESSAGE.To];
	    	    				  				//	var reciever = reciever.get("displayname");
	    	    				  				
	    	    				  					
	    	    				  					setDate(messageTask.split(",")[TASK_MESSAGE.Date]);
	    	    				  					
	    	    				  					
	    	    				  				  $("#confirm_subtext").css('visibility','visible');
	    	    				    				$("#confirm_task").css('visibility','visible');
	    	    				    				$("#reject").css('visibility','visible');
	    	    				    				$("#accept").css('visibility','visible');
	    	    				    				$("#acceptRejectTitle").css('visibility','visible');
	    	    				    				$("#invite_section").css('visibility','visible');
	    	    				    				$("#header").css('visibility','visible');

	    	    				    				$("#choose1").css('visibility','visible');
	    	    				    			  
	    	    				    			  
	    	    				  					
	    	    				  					
	    	    				  					
	    	    				  					//$("#readIt").css('visibility','visible');
	    	    				  					$("#description").css('visibility','visible');
	    	    				  					$("#inviteSection").css('visibility','visible');
	    	    				  					$("#inviter").css('visibility','visible');
	    	    				  					$("#taskTitle").css('visibility','visible');
	    	    				  					//$("#noteContent").css('visibility','visible');
	    	    				  					$("#confirm_subtext").css('visibility','visible');
	    	    				  					
	    	    				  					
	    	    				  					$("#inviter").html(senderName+" has assigned you to:").show();
	    	    				  					$("#taskTitle").html(messageTitle+" "+messageContent+" at "+timeString).show();
	    	    				  					$("#acceptRejectTitle").html("Please accept or decline  "+senderName+"'s request:").show();
	    	    				  					$("#confirm_subtext").html("Keep in mind: whichever option you choose will instantly populate into "+ senderName+"'s task list.").show();

	    	    				  					
	    	    				  					
	    	    				  					var CheckAcceptedNew = Parse.Object.extend("TaskAcceptNew");
		    	    				  	    	    var checkQuery =  new Parse.Query(CheckAcceptedNew);
		    	    				  	    	    checkQuery.equalTo("taskID", parseId);

		    	    				  	    	    checkQuery.find({
		    	    				  	    	    	success: function(inviteConfimedCheckup) {
		    	    				  	    	    		if (inviteConfimedCheckup.length!=0)
		    	    				  	    		    	{/// Already responded to the event
		    	    				  	    	    			$("#confirm_task").css('visibility','hidden');	
		    	    				  	    	    			$("#confirm_task").css('visibility','hidden');
		    	    				  	    	    			$("#reject").css('visibility','hidden');
		    	    				  	    	    			$("#choose1").css('visibility','hidden');
		    	    				  	    	    			$("#choose2").css('visibility','hidden');
		    	    				  							$("#accept").css('visibility','hidden');
		    	    				  							$("#confirm_subtext").css('visibility','hidden');
		    	    				  							$("#acceptRejectTitle").css('visibility','hidden');

		    	    				  							$("#inviter").html(senderName+" has received your response for:").show();
	    				  	    	     						$("#taskTitle").html(messageTitle+" "+messageContent).show();
	 	    	    				  	    	    			
	 	    	    				  	    	    			
	 	    	    				  	    		    	}else
		    	    				  	    	    		//if (!inviteConfimedCheckup[0].get("isRead"))
		    	    				  	    	    		{
		    	    				  	    	    			$("#accept").click(function () {
	    	    				  							
		    	    				  	    	    				accept = true;
		    	    				  	    	    				
		    	    				  	    	    				
			    	    				  	    	    			$("#choose1").css('visibility','visible');
			    	    				  	    	    			$("#choose2").css('visibility','hidden');

//		    	    				  	    	    				inviteConfimedCheckup[0].set("isRead",true);
//		    	    				  	    	    				inviteConfimedCheckup[0].save(null, {
//		    	    				  	    	    					success: function(eventRespond) {
//	    	    				  									
//		    	    				  	    	    						jAlert(senderName+ ' Has been informed, you accepted his task',reciever, function(r) {
//		    	    				  	    	    							//	alert(senderName+", Has been informed, you read his note");
//		    	    				  	    	    							//$("#readIt").css('visibility','hidden');
//		    	    				  	    	    							$("#confirm_subtext").html(senderName+" was informed you accepted his task.").show();
//		    	    				  	    	    						});
//		    	    				  	    	    					},
//		    	    				  	    	    				});
	    	    				  	
		    	    				  	    	    			});
		    	    				  	    	    			
		    	    				  	    	    			$("#reject").click(function () {
	    	    				  							
		    	    				  	    	    				accept = false;
		    	    				  	    	    				
		    	    				  	    	    				
		    	    				  	    	    				
			    	    				  	    	    			$("#choose2").css('visibility','visible');
			    	    				  	    	    			$("#choose1").css('visibility','hidden');
		    	    				  	    	    				
		    	    				  	    	    				
//		    	    				  	    	    				inviteConfimedCheckup[0].set("isRead",true);
//		    	    				  	    	    				inviteConfimedCheckup[0].save(null, {
//		    	    				  	    	    				success: function(eventRespond) {
//	    	    				  									
//		    	    				  	    	    					jAlert(senderName+ ' Has been informed, you rejected his task',reciever, function(r) {
//		    	    				  	    	    						//	alert(senderName+", Has been informed, you read his note");
//		    	    				  	    	    						//	$("#readIt").css('visibility','hidden');
//		    	    				  	    	    						$("#confirm_subtext").html(senderName+" was informed you accepted his task.").show();
//		    	    				  	    	    					});
//		    	    				  	    	    				},
//		    	    				  	    	    				});
//	    	    				  	
		    	    				  	    	    			});
	    	    				  						
		    	    				  	    	    			$("#confirm_task").click(function () {
		    	    				  	    	    				
		    	    				  	    	    				
		    	    				  	    	    				 var TaskAcceptNew = Parse.Object.extend("TaskAcceptNew");
		    	    				  	    	    			  	 var taskAcceptNew = new TaskAcceptNew();
		    	    				  	    	    				
		    	    				  	    	    				
		    	    				  	    	    			  	task.set("isRead",true);
		    	    				  	    	    			  	
		    	    				  	    	    			  task.save(null, {
	    	    				  	    	    			  		  success: function(eventRespond) {
	    	    				  	    	    			  		  	
	  		    	    				  	    	    			    taskAcceptNew.set("taskID",task.id);
	  		    	    				  	    	    			    taskAcceptNew.set("inviterID",senderId);
	  		    	    				  	    	    			  	taskAcceptNew.set("inviteeID",recieverId);
	  		    	    				  	    	    			  	taskAcceptNew.set("accept",accept);
	  		    	    				  	    	    			
	  		    	    				  	    	    				var respond = (accept)? "confirmed" : "declined";
	  		    	    				  	    	    				
	  		    	    				  	    	    				
	  		    	    				  	    	    				taskAcceptNew.save(null, {
	  		    	    				  	    	    			  		  success: function(eventRespond) {
	  	    	    				  									
	  		    	    				  	    	     					jAlert(senderName+ ' Has been informed, you '+ respond+' his task',reciever, function(r) {
	  		    	    				  	    	    						//	alert(senderName+", Has been informed, you read his note");
	  		    	    				  	    	    						//	$("#readIt").css('visibility','hidden');
	  		    	    				  	    	     						
	  		    	    				  	    	     						
	  		    	    				  	    	     						
	  		    	    				  	    	     						
	  		    	    				  	    	    						$("#confirm_subtext").html(senderName+" was informed you confirmed his task.").show();
	  		    	    				  	    	    						
//	  		    	    				  	    	    				$("#confirm_task").css('visibility','hidden');	
	  		  		    	    				  	    	    			$("#confirm_task").css('visibility','hidden');
	  				    	    				  	    	    			$("#reject").css('visibility','hidden');
	  				    	    				  	    	    			$("#choose1").css('visibility','hidden');
	  				    	    				  	    	    			$("#choose2").css('visibility','hidden');
	  				    	    				  							$("#accept").css('visibility','hidden');
	  				    	    				  							$("#confirm_subtext").css('visibility','hidden');
	  				    	    				  							$("#acceptRejectTitle").css('visibility','hidden');

	  				    	    				  							$("#inviter").html(" You "+respond+"  :").show();
	  			    				  	    	     						$("#taskTitle").html(messageTitle+" "+messageContent+" with "+senderName).show();
	  			 	    	    				  	    	    			
	  		    	    				  	    	    						
	  		    	    				  	    	    						
	  		    	    				  	    	    					});
	  		    	    				  	    	     					
	  		    	    				  	    	     					//Responder's Name has confirmed "Event Title" at time on day. 
	  		    	    				  	    	     					
	  		    	    				  	    	     				var	pushMessage =  reciever +" has "+respond+" "+messageTitle+" at "+timeString;
	  		    	    				  	    	     					
	  		    	    				  	    	     				// PUSH NOTIFICATION MESSAGE
	  		    	    				  	    	     				//Event Response (2): Responder's Name has declined "Event Title".	
	  		    	    				  	    	     				Parse.Push.send({
	  		    						  									//	where: pushQuery,
	  		    						  										 channels:[senderId],
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
	  		    	    				  	    	     					
	  		    	    				  	    	     					
	  		    	    				  	    	     					
	  		    	    				  	    	    				},
	  		    	    				  	    	    				});
	  		    	    				  	    	    				
	  		    	    				  	    	    				
	    	    				  	    	    			  		  }}
		    	    				  	    	    			  );
		    	    				  	    	    			
		    	    				  	    	    				
		    	    				  	    	    			});
		    	    				  	    	   
		    	    				  	    	    		}
		    	    				  	    	    	}});
			      
//	    	    				  				}
//	    	    				  			});
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

    	$("#choose1").css('visibility','hidden');
		$("#choose2").css('visibility','hidden');
		
		
		
		
		
		
		$("#confirm_subtext").css('visibility','hidden');
		$("#confirm_task").css('visibility','hidden');
		$("#reject").css('visibility','hidden');
		$("#accept").css('visibility','hidden');
		$("#acceptRejectTitle").css('visibility','hidden');
		$("#invite_section").css('visibility','hidden');
		$("#header").css('visibility','hidden');

      	new taskView();
      	
      
    	
    	
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