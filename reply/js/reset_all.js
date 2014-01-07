$(function() {

  Parse.$ = jQuery;

  // Initialize Parse with your Parse application javascript keys
  Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX",
		  "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67"); 

 
  
  
  var resetView = Parse.View.extend({
		events: {
	    },
		 el: ".content",
		initialize: function() {
	    	
		      _.bindAll(this);
		      this.render();
		    },

		   
		      
		      
		      render: function() {
		    	  
		    	  
			      this.delegateEvents();
			      
			      
			      ////////// Reset Atlas Invite
//			      $("#resetAtlasInviteClicked").click(function() {  
//				  		
//				  		
//				  		var parseId = $("#atlasInviteObjectId").val().toString();
//				  		
//				  		 var NoteShare = Parse.Object.extend("inviteToAtlas");
//				    	  var query = new Parse.Query(NoteShare);
//				    	  query.get(parseId, {
//				    		  success: function(note) {
////					    			  var NoteReset = Parse.Object.extend("NotesShare");
////					    			  	 var noteReset = new NoteReset();
//					    			  
//					    			  note.set("isRead",false);
//					    			  	
//					    			  note.save(null, {
//				 			  		  success: function(eventRespond) {
//				 			  			  
//				 			  			alert(" atlas invite : "+parseId+ " isRead field is now set to : "+eventRespond.get("isRead"));
//				 			  		  }
//					    			  });
//					    			  
//					    		  }
//		    			  	 });
//		    				
//				  		
//				  		
//				  	});
//				  	
			      ////////// HREF TO SITES REF...
			      
			      //Notes share ref
			      $("#noteShareClicked").click(function() {  
			    	  
			    	  var parseId = $("#noteShareObjectId").val().toString();
			    	  var href = "http://getatlastest.com/reply/share_note_mobile.html?id="+parseId;
			    	 // alert(href);
			    	  $("#noteShareUrl").attr('href',href);
			      });
			      
			      ///// Task assign ref
			      
			      $("#taskAssignClicked").click(function() {  
			    	  
			    	  var parseId = $("#taskAssignObjectId").val().toString();
			    	  var href = "http://getatlastest.com/reply/assign_to_task_mobile.html?id="+parseId;
			    	 // alert(href);
			    	  $("#taskAssignUrl").attr('href',href);
			      });
			      
			      
			      
			      
			      
			      ////Invite ref
			      $("#inviteEventClicked").click(function() {  
			    	  
			    	  var parseId = $("#inviteEventObjectId").val().toString();
			    	  var href = "http://getatlastest.com/reply/invite_to_event_mobile.html?id="+parseId;
			    	 // alert(href);
			    	  $("#inviteEventUrl").attr('href',href);
			      });
			      
			      
			  ////Invite to Atlas ref
			      $("#inviteToAtlasClicked").click(function() {  
			    	  
			    	  var parseId = $("#inviteToAtlasObjectId").val().toString();
			    	  var href = "http://getatlastest.com/reply/invite_to_atlas_mobile.html?id="+parseId;
			    	 // alert(href);
			    	  $("#inviteToAtlasUrl").attr('href',href);
			      });
			      
			      
			      
			      
			      ///////// Reset Note
			      
			  	$("#resetNoteClicked").click(function() {  
			  		
			  		
			  		var parseId = $("#noteObjectId").val().toString();
			  		
			  		 var NoteShare = Parse.Object.extend("NotesShare");
			    	  var query = new Parse.Query(NoteShare);
			    	  query.get(parseId, {
			    		  success: function(note) {
//				    			  var NoteReset = Parse.Object.extend("NotesShare");
//				    			  	 var noteReset = new NoteReset();
				    			  
				    			  note.set("isRead",false);
				    			  	
				    			  note.save(null, {
			 			  		  success: function(eventRespond) {
			 			  			  
			 			  			alert(" note : "+parseId+ " isRead field is now set to : "+eventRespond.get("isRead"));
			 			  		  }
				    			  });
				    			  
				    		  }
	    			  	 });
	    				
			  		
			  		
			  	});
			  	
			  	
			  	///// Reset Tasks
			  	
			  	
			  	
			  	$("#resetTaskClicked").click(function() {  
			  		
			  		
			  		var parseId = $("#taskObjectId").val().toString();
			  		
			  		 var TaskAssignNew = Parse.Object.extend("TaskAssignNew");
			    	  var queryTaskAssignNew = new Parse.Query(TaskAssignNew);
			    	  queryTaskAssignNew.get(parseId, {
			    		  success: function(task) {
//				    			  var NoteReset = Parse.Object.extend("NotesShare");
//				    			  	 var noteReset = new NoteReset();
				    			  
				    			  task.set("isRead",false);
				    			  	
				    			  task.save(null, {
			 			  		  success: function(eventRespond) {
			 			  			  
			 			  			alert(" task : "+parseId+ " isRead field is now set to : "+eventRespond.get("isRead"));
			 			  		  
			 			  		  
			 			  			var taskId = eventRespond.get("taskID");
			 			  		 var TaskAcceptNew = Parse.Object.extend("TaskAcceptNew");
			 			  	
  	    	    			  	 var taskAcceptNew = new Parse.Query(TaskAcceptNew);
  	    	    			  	taskAcceptNew.equalTo("taskID",parseId);
  	    	    			  //	taskAcceptNew.limit(1);
  	    	    			  	taskAcceptNew.find( {
  	   			    		  success: function(taskAcceptResponse) {
  	   			    			//  alert(taskAcceptResponse.length);
  	   			    			  
  	   			    			taskAcceptResponse[0].destroy({
  	   			    			  success: function(myObject) {
  	   			    			    // The object was deleted from the Parse Cloud.
  	   			    				  alert(myObject+" record was deleted from TaskAcceptNew ");
  	   			    			  },
  	   			    			  error: function(myObject, error) {
  	   			    			    // The delete failed.
  	   			    			    // error is a Parse.Error with an error code and description.
  	   			    			  }
  	   			    			});
  	   			    			  
	    	    			  			  
	    	    			  		  }
  	    	    			  });
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  }
				    			  });
				    			  
				    		  }
	    			  	 });
	    				
			  		
			  		
			  	});
			  	
			  	
			  	
			  	
			  	//////// Reset Invite
			  	
			  	$("#resetInviteClicked").click(function() {  
			  		
			  		
			  		var parseId = $("#inviteObjectId").val().toString();
			  		
			  		 var EventInviteNew = Parse.Object.extend("EventInviteNew");
			    	  var queryEventInviteNew = new Parse.Query(EventInviteNew);
			    	  queryEventInviteNew.get(parseId, {
			    		  success: function(invite) {
//				    			  var NoteReset = Parse.Object.extend("NotesShare");
//				    			  	 var noteReset = new NoteReset();
				    			  
				    			  invite.set("isRead",false);
				    			  	
				    			  invite.save(null, {
			 			  		  success: function(eventRespond) {
			 			  			  
			 			  			alert(" invite : "+parseId+ " isRead field is now set to : "+eventRespond.get("isRead"));
			 			  		  
			 			  		  
			 			  		 var EventAcceptNew = Parse.Object.extend("EventAcceptNew");
			 			  	
  	    	    			  	 var eventAcceptNew = new Parse.Query(EventAcceptNew);
  	    	    			  	eventAcceptNew.equalTo("inviteID",parseId);
  	    	    			  //	taskAcceptNew.limit(1);
  	    	    			  	eventAcceptNew.find( {
  	   			    		  success: function(eventAcceptResponse) {
  	   			    			//  alert(taskAcceptResponse.length);
  	   			    			  
  	   			    			eventAcceptResponse[0].destroy({
  	   			    			  success: function(myObject) {
  	   			    			    // The object was deleted from the Parse Cloud.
  	   			    				  alert(myObject+" record was deleted from EventAcceptNew ");
  	   			    			  },
  	   			    			  error: function(myObject, error) {
  	   			    			    // The delete failed.
  	   			    			    // error is a Parse.Error with an error code and description.
  	   			    			  }
  	   			    			});
  	   			    			  
	    	    			  			  
	    	    			  		  }
  	    	    			  });
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  
			 			  		  }
				    			  });
				    			  
				    		  }
	    			  	 });
	    				
			  		
			  		
			  	});
			  	
			  	
			  	
			  	//////////////
			        
			      
			  		
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
    

      	new resetView();
      	
      
    	
    	
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