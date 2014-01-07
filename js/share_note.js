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
	    				  				
	    				  					var query2 = new Parse.Query(Parse.User);
	    	    				  			query2.get(recieverId, { 
	    		          
	    	    				  				success: function(reciever) {
	    	    				  				
	    	    				  					var messageTitle = messageNote.split(",")[NOTE_MESSAGE.Title];
	    	    				  					var messageContent = messageNote.split(",")[NOTE_MESSAGE.Content];
	    	    				  				
	    	    				  					var senderName = sender.get("displayname");
	    	    				  					var reciever = reciever.get("displayname");
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

	    	    				  					
	    	    				  					
	    	    				  					if (!note.get("isRead"))
	    	    				  					{
	    	    				  						$("#readIt").click(function () {
	    	    				  						
	    	    				  							note.set("isRead",true);
	    	    				  							note.save(null, {
	    	    				  								success: function(eventRespond) {
	    	    				  									
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
	    	    				  				}
	    	    				  			});
			      
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