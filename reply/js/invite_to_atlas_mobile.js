$(function() {

 // Parse.$ = jQuery;
  
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
  
  
  var inviteView = Parse.View.extend({
		events: {
	    },
		 el: ".content",
		initialize: function() {
	    	
		      _.bindAll(this);
		      this.render();
		    },

		   
		      
		      
		      render: function() {
		    	  
		    	  
			      this.delegateEvents();
			      
			      
			      
			      
			      
			      
			      
			      
			  		var inviteId = window.location.search;
			  		inviteId = inviteId.replace("?id=","");
			        
			  		 var inviteToAtlas = Parse.Object.extend("inviteToAtlas");
			    	  var query = new Parse.Query(inviteToAtlas);
			    	  query.get(inviteId, {
			    		  success: function(invite) {
			    			  
			    			 
			    			  var senderId = invite.get("inviterID");
			    			  var emailReference = invite.get("email");
			    			  var phoneReference = invite.get("phone");
			    			  var inviteeName = invite.get("name");
			    			  
			    			  
			    			  var query1 = new Parse.Query(Parse.User);
	    				  		query1.get(senderId, { 
		          
	    				  			success: function(sender) {
	    				  				
	    				  				var date = new Date();
	    				  				
	    				  				
	    				  				
	    				  				var recieved = "";//invite.get("recieved_date");
	    				  				if (recieved==null || recieved=="")
	    				  				{
	    				  					invite.set("recieved_date", date);
	    				  				
	    				  					invite.save(null, {
			  								success: function(eventRespond) {
			  									
			  								
			  									// Find devices associated with these users
			  									//var pushQuery = new Parse.Query(Parse.Installation);
			  								//	alert("hello");
			  								//	pushQuery.equalTo('atlasID', senderId);
			  									// alert(senderId);
			  									// Send push notification to query
			  								//	Parse.Cloud.afterSave("VonTlk5hUp", function(request) {
			  									 
			  									 
//			  									Parse.Push.send({
//			  									  channels: ["atlasID"],
//			  									  data: {
//			  									    alert: "The Giants won against the Mets 2-3."
//			  									  }
//			  									}, {
//			  									  success: function() {
//			  									    // Push was successful
//			  									  },
//			  									  error: function(error) {
//			  									    // Handle error
//			  									  }
//			  									});
			  									 
			  									 
//			  									 
			  										Parse.Push.send({
					  									//	where: pushQuery,
					  										 channels:["ID"+senderId],
					  									  data: {
					  									    alert: "Your Atlas invitation was sent",
					  									 	sound: "Incoming_Atlas_Push.mp3"
					  										
					  									  }
					  									}, {
					  									  success: function() {
					  									    //alert("pushed!");
					  									  },
					  									  error: function(error) {
					  									    // Handle error
					  										// alert(" failed to push!");
					  									  }
					  									});
//			  									
			  								
			  									},
	    				  					});
	    				  				
	    				  				}
	  									var senderName = sender.get("displayname");
	  									var senderPic = sender.get("picture");
	  									
	  									
	  									var imageUrl = (senderPic==null)? "" : senderPic.url;
	  									
	  									
	  									
	  									
	  									
	  									$("#avatar").attr('src', imageUrl);
	  									
	  									
	    				  				$("#invitername").html(senderName+" has invited you to ").show();
	    				  				
	    				  				$("#inviteText2").html(" This invitation from "+senderName+" will self-destruct in 1 hour ").show();
	    				  				
	    				  			}
	    				  			});
		    
			    			  
			    			  
			    			  
			    			  
			    		  }
			    	 
			    	  
			    	  
			    	  
			    	  
			    	  
			    	  });
			    		  
			    
			    			  
	    				  		
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
    	
      	new inviteView();
      	
      
    	
    	
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