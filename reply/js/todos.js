	 // An example Parse.js Backbone application based on the todo app by
// [Jérôme Gravel-Niquet](http://jgn.me/). This demo uses Parse to persist
// the todo items and provide user authentication and sessions.

$(function() {

  Parse.$ = jQuery;

  // Initialize Parse with your Parse application javascript keys
  Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX",
		  "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67"); 
  
  // Todo Model
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

    // Toggle the `done` state of this todo item.
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

  
  setDateString= function(message,option) {
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
 	};
 	 var prefDate = (option=="1")? INVITE_MESSAGE.PreferredDate :(option=="2")? 
 			INVITE_MESSAGE.AlternativeDate1 :INVITE_MESSAGE.AlternativeDate2;
 	 var date1 = message.split(",")[prefDate];
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
	     
	     var duration1 = parseInt(message.split(",")[INVITE_MESSAGE.Duration]);
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
	      $("#option"+option).click(function () {
	    	  
	    	  var option2 = (option=="1")? "2" :(option=="2")? "1" :"1";
	    	  var option3 = (option=="1")? "3" :(option=="2")? "3" :"2";
	    	  
	          $("#choose"+option).css('visibility','visible');
	          $("#choose"+option2).css('visibility','hidden');
	          $("#choose"+option3).css('visibility','hidden');
	           
	        });
	      
  }
  
  
  // Todo Collection
  // ---------------

  var TodoList = Parse.Collection.extend({

    // Reference to this collection's model.
    model: Todo,

    // Filter down the list of all todo items that are finished.
    done: function() {
      return this.filter(function(todo){ return todo.get('done'); });
    },

    // Filter down the list to only todo items that are still not finished.
    remaining: function() {
      return this.without.apply(this, this.done());
    },

    // We keep the Todos in sequential order, despite being saved by unordered
    // GUID in the database. This generates the next order number for new items.
    nextOrder: function() {
      if (!this.length) return 1;
      return this.last().get('order') + 1;
    },

    // Todos are sorted by their original insertion order.
    comparator: function(todo) {
      return todo.get('order');
    }

  });

  // Todo Item View
  // --------------

  // The DOM element for a todo item...
  var TodoView = Parse.View.extend({

    //... is a list tag.
    tagName:  "li",

    // Cache the template function for a single item.
    template: _.template($('#item-template').html()),

    // The DOM events specific to an item.
    events: {
      "click .toggle"              : "toggleDone",
      "dblclick label.todo-content" : "edit",
      "click .todo-destroy"   : "clear",
      "keypress .edit"      : "updateOnEnter",
      "blur .edit"          : "close"
    },

    // The TodoView listens for changes to its model, re-rendering. Since there's
    // a one-to-one correspondence between a Todo and a TodoView in this
    // app, we set a direct reference on the model for convenience.
    initialize: function() {
      _.bindAll(this, 'render', 'close', 'remove');
      this.model.bind('change', this.render);
      this.model.bind('destroy', this.remove);
    },

    // Re-render the contents of the todo item.
    render: function() {
      $(this.el).html(this.template(this.model.toJSON()));
      this.input = this.$('.edit');
      return this;
    },

    // Toggle the `"done"` state of the model.
    toggleDone: function() {
      this.model.toggle();
    },

    // Switch this view into `"editing"` mode, displaying the input field.
    edit: function() {
      $(this.el).addClass("editing");
      this.input.focus();
    },

    // Close the `"editing"` mode, saving changes to the todo.
    close: function() {
      this.model.save({content: this.input.val()});
      $(this.el).removeClass("editing");
    },

    // If you hit `enter`, we're through editing the item.
    updateOnEnter: function(e) {
      if (e.keyCode == 13) this.close();
    },

    // Remove the item, destroy the model.
    clear: function() {
      this.model.destroy();
    }

  });

  // The Application
  // ---------------

  // The main view that lets a user manage their todo items
  var ManageTodosView = Parse.View.extend({

    // Our template for the line of statistics at the bottom of the app.
    statsTemplate: _.template($('#stats-template').html()),

    // Delegated events for creating new items, and clearing completed ones.
    events: {
      "keypress #new-todo":  "createOnEnter",
      "click #clear-completed": "clearCompleted",
      "click #toggle-all": "toggleAllComplete",
      "click .log-out": "logOut",
      "click ul#filters a": "selectFilter"
    },

    el: ".content",

    // At initialization we bind to the relevant events on the `Todos`
    // collection, when items are added or changed. Kick things off by
    // loading any preexisting todos that might be saved to Parse.
    initialize: function() {
      var self = this;

      _.bindAll(this, 'addOne', 'addAll', 'addSome', 'render', 'toggleAllComplete', 'logOut', 'createOnEnter');

      // Main todo management template
      this.$el.html(_.template($("#manage-todos-template").html()));
      
      this.input = this.$("#new-todo");
      this.allCheckbox = this.$("#toggle-all")[0];

      // Create our collection of Todos
      this.todos = new TodoList;

      // Setup the query for the collection to look for todos from the current user
      this.todos.query = new Parse.Query(Todo);
      this.todos.query.equalTo("user", Parse.User.current());
        
      this.todos.bind('add',     this.addOne);
      this.todos.bind('reset',   this.addAll);
      this.todos.bind('all',     this.render);

      // Fetch all the todo items for this user
      this.todos.fetch();

      state.on("change", this.filter, this);
    },

    // Logs out the user and shows the login view
    logOut: function(e) {
      Parse.User.logOut();
      new LogInView();
      this.undelegateEvents();
      delete this;
    },

    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {
      var done = this.todos.done().length;
      var remaining = this.todos.remaining().length;

      this.$('#todo-stats').html(this.statsTemplate({
        total:      this.todos.length,
        done:       done,
        remaining:  remaining
      }));

      this.delegateEvents();

      this.allCheckbox.checked = !remaining;
    },

    // Filters the list based on which type of filter is selected
    selectFilter: function(e) {
      var el = $(e.target);
      var filterValue = el.attr("id");
      state.set({filter: filterValue});
      Parse.history.navigate(filterValue);
    },

    filter: function() {
      var filterValue = state.get("filter");
      this.$("ul#filters a").removeClass("selected");
      this.$("ul#filters a#" + filterValue).addClass("selected");
      if (filterValue === "all") {
        this.addAll();
      } else if (filterValue === "completed") {
        this.addSome(function(item) { return item.get('done') });
      } else {
        this.addSome(function(item) { return !item.get('done') });
      }
    },

    // Resets the filters to display all todos
    resetFilters: function() {
      this.$("ul#filters a").removeClass("selected");
      this.$("ul#filters a#all").addClass("selected");
      this.addAll();
    },

    // Add a single todo item to the list by creating a view for it, and
    // appending its element to the `<ul>`.
    addOne: function(todo) {
      var view = new TodoView({model: todo});
      this.$("#todo-list").append(view.render().el);
    },

    // Add all items in the Todos collection at once.
    addAll: function(collection, filter) {
      this.$("#todo-list").html("");
      this.todos.each(this.addOne);
    },

    // Only adds some todos, based on a filtering function that is passed in
    addSome: function(filter) {
      var self = this;
      this.$("#todo-list").html("");
      this.todos.chain().filter(filter).each(function(item) { self.addOne(item) });
    },

    // If you hit return in the main input field, create new Todo model
    createOnEnter: function(e) {
      var self = this;
      if (e.keyCode != 13) return;

      this.todos.create({
        content: this.input.val(),
        order:   this.todos.nextOrder(),
        done:    false,
        user:    Parse.User.current(),
        ACL:     new Parse.ACL(Parse.User.current())
      });

      this.input.val('');
      this.resetFilters();
    },

    // Clear all done todo items, destroying their models.
    clearCompleted: function() {
      _.each(this.todos.done(), function(todo){ todo.destroy(); });
      return false;
    },

    toggleAllComplete: function () {
      var done = this.allCheckbox.checked;
      this.todos.each(function (todo) { todo.save({'done': done}); });
    }
  });
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
  //////// Invite to Atlas
  
  var inviteView = Parse.View.extend({
	    events: {
	      "submit form.atlasinvite-form": "inviteToAtlas",
	    },

	    el: ".content",
	    
	    initialize: function() {
	      _.bindAll(this, "inviteToAtlas");
	      this.render();
	    },

	    inviteToAtlas: function(e) {
	        var self = this;
	       
	          
//	        var InviteToAtlas = Parse.Object.extend("inviteToAtlas");
//	        var query = new Parse.Query(InviteToAtlas);
//	        query.get("SPjDFlzY7c", {
//	          success: function(invite) {
//	            // The object was retrieved successfully.
//	          	
//	          	var email = invite.get("email");
//	          	var name = invite.get("name");
//	          	var inviterID = invite.get("inviterID")
//	          	
//	          	
//	          	var query = new Parse.Query(Parse.User);
//	          	query.equalTo(objectId, inviterID);  
//	          	query.find({
//	          	  success: function(inviter) {
//	          	    var inviter_name = inviter.get("first_name");
//	          	    self.$(".atlasinvite-form h2").html("Welcome !").show();
//
//
//	          	    
//	          	  }
//	          	});
//	          //	var u = " hgjhgjh "; 
//	          	//var c = parseUri(uri).file;
//	          	
//	          	
//	          	
//	          	
//	          	
//	          },
//	          error: function(object, error) {
//	        	  
//	          	self.$(".atlasinvite-form h2").html("Error ").show();
//
//	            // The object was not retrieved successfully.
//	            // error is a Parse.Error with an error code and description.
//	          }
//	        });
		        	
		        	
		    

	        return false;
	      },

	

	    render: function() {
	      this.$el.html(_.template($("#invite-template").html()));
	      this.delegateEvents();
	         
	      var pic1 = document.getElementById("header_img"); 
	      pic1.src = "images/get_atlas_img.png";
	      $("#header_img").attr('src','images/get_atlas_img.png');

	      
	      
//	      $("#description #event").html("Get Atlas").show();  
//	         
//	      $("#description #event").attr('align','center'); 
//	      $("#description #event").attr('font-size','50px');
        	//self.$(".atlasinvite-form h2").html("Welcome ").show();
	  	var uri = location.href; 
      	var c = parseUri(uri).file;
      	
      	//var parseId = parseUri(uri).queryKey["id"];
      	
      	var parseId = window.location.search;
      	parseId = parseId.replace("?id=","");
	        
	      //SPjDFlzY7c
	      
	      if (parseId!=null && parseId!="" && parseId!="undefined")
	      {
	    	  var InviteToAtlas = Parse.Object.extend("inviteToAtlas");
	    	  var query = new Parse.Query(InviteToAtlas);
	    	  query.get(parseId, {
	    		  success: function(invite) {
	    			  // The object was retrieved successfully.
 	          	
	    			  var email = invite.get("email");
	    			  var name = invite.get("name");
 	          	
	    			  var inviterID = invite.get("inviterID");
	          	
	    			  var inviter_name;
	    			  var date;
	    			  
	    			  date = invite.get("recieved_date");
	    			  
	    			  
	    			  if (date==null || date=="" || date=="undefined")
	    			{
	    			   date = new Date();
	    			  invite.set("recieved_date",date);
	    			  invite.save();
	    			}
	    			  
	    			//  $("#description #event").html(inviterID).show();  
	    			  //self.$(".atlasinvite-form h2").html("Welcome "+naTBqne60lDYTBqne60lDYTBqne60lDYme+"! "+inviterID+" invite you to ").show();

	    			  	//   $("#description #inviteeh1").html("Welcome "+name +"! "+inviterID+" invite you to ").show();

	    			  var query2 = new Parse.Query(Parse.User);
	    			  query2.get(inviterID, { 
	          
	    				  success: function(inviter) {
	    					  var inviter_name = inviter.get("displayname");

	    					  ///   self.$(".atlasinvite-form h2").html("Welcome "+name +"! "+inviter_name+" invite you to ").show();
	    					  $("#description #inviteeh1").html("Welcome "+name +"!\n"+inviter_name+"invite you to Atlas").show();
	    					  $("#description #inviteeh1").attr('align','center');  
	    				  }
	    			  });
 	          
 	          
 	          	


 	          	
 	          	
	    		  	},
	    		  		error: function(object, error) {
 	        	  

	    		  				// The object was not retrieved successfully.
	    		  				// error is a Parse.Error with an error code and description.
	    		  		}
 	        });
	      }
	      
	    }
	      
	     
	      
	      
  
	    
	  });
  
  
  
  
  
  
  
  //////// end-section-Invite to Atlas
  
  
 /////// CAL INVITE VIEW SECTION
  var calInviteView = Parse.View.extend({
	    events: {
	      "submit form.cal-form": "inviteToEvent",
	    },

	    el: ".content",
	    
	    initialize: function() {
	    	
	      _.bindAll(this, "inviteToEvent");
	      this.render();
	    },

	    


	    inviteToEvent: function(e) {
	        var self = this;
	       
	        
	        
	        
	       
	    	
	        
	        
	        
	        
	       //  self.$(".cal-form .container.header.h2").html("Welcome ").show();

	        var eventInvite = Parse.Object.extend("inviteToAtlas");
	        var query = new Parse.Query(eventInvite);
	        query.get("SPjDFlzY7c", {
	          success: function(invite) {
	            // The object was retrieved successfully.
	          	
	          	var email = invite.get("email");
	          	var name = invite.get("name");
	          	var inviterID = invite.get("inviterID")
	          	
	          	
	          	var query = new Parse.Query(Parse.User);
	          	query.equalTo(objectId, inviterID);  
	          	query.find({
	          	  success: function(inviter) {
	          	    var inviter_name = inviter.get("displayname");
	          //	    self.$(".cal-form invite_section.h2").html("Welcome "+inviter_name).show();


	          	    
	          	  }
	          	});
	          //	var u = " hgjhgjh "; 
	          	//var c = parseUri(uri).file;
	          	
	          	
	          	
	          	
	          	
	          },
	          error: function(object, error) {
	        	  
	          //	self.$(".cal-form h2").html("Error ").show();

	            // The object was not retrieved successfully.
	            // error is a Parse.Error with an error code and description.
	          }
	        });
		        	
		        	
		    

	        return false;
	      },

	    
	      
	    render: function() {
	    	//alert(this.$("#cal-template ").html());
	    	 
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
	    	var WEEK_DAY = {1:"Sunday",2:"Monday",3:"Tuesday",4:"Wednesday",5:"Thursday",6:"Friday",7:"Saturday"};
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
	      
	    			  var inviterId = invite.get("fromID");
	    			  var inviteeId = invite.get("toID");
	    			  
	    			  if (inviterId!=null && inviterId!="" && inviterId!="undefined" &&
	    				  inviteeId!=null && inviteeId!="" && inviteeId!="undefined")
	    				  {
	    				  		var query1 = new Parse.Query(Parse.User);
	    				  		query1.get(inviterId, { 
		          
	    				  			success: function(inviter) {
	    				  				
	    				  				var query2 = new Parse.Query(Parse.User);
	    	    				  		query2.get(inviteeId, { 
	    		          
	    	    				  			success: function(invitee) {
	    	    				  				
	    	    				  				var inviter_name = inviter.get("displayname");
	    	    				  				var invitee_name = invitee.get("displayname");
	    	    				  				var message = invite.get("message");
	    	    				  				 //// Header settings
	    	    				  		      
	    	    				  		      $("#description #inviteeh1").html("Hi "+invitee_name+"! "+inviter_name+" has invited you to:").show();
	    	    				  	  
	    	    				  		      $("#description #event").html(message.split(",")[INVITE_MESSAGE.Title]).show();
	    	    				  		      $("#header_img").attr('visibility','images/header_img.png');
	    	    				  			
	    	    				  		      
	    	    				  		      
	    	    				  		   // var prefDate = (option=="1")? INVITE_MESSAGE.PreferredDate :(option=="2")? 
	    	    				  		 //			INVITE_MESSAGE.AlternativeDate1 :INVITE_MESSAGE.AlternativeDate2;
	    	    				  		 	 var date1 = message.split(",")[INVITE_MESSAGE.PreferredDate];
	    	    				  		 	 if (date1!=null && date1!="" && date1!="undefined")
	    	    				  		   {
	    	    				  		 		 $("#choose1").css('visibility','visible');
	    	    				  		 		 this.setDateString(message,1);
	    	    				  		 }else
	    	    				  			 $("#dateImg1").css('visibility','hidden');
	    	    				  		 		 var date2 =  message.split(",")[INVITE_MESSAGE.AlternativeDate1];
	    	    				  		 		if (date2!=null && date2!="" && date2!="undefined")
	 	    	    				  		   {

	    	    				  		 		  this.setDateString(message,2);
		    	    				  		 	  
	 	    	    				  		   }else
	 	    	    				  			   
	 		    	    				  			 $("#dateImg2").css('visibility','hidden');
	    	    				  		 		var date3 =  message.split(",")[INVITE_MESSAGE.AlternativeDate2];

	    	    				  		 		  if (date3!=null && date3!="" && date3!="undefined")
		 	    	    				  		   {
	    	    	    				  		    this.setDateString(message,3);

		 	    	    				  		   }else
		 		    	    				  			 $("#dateImg3").css('visibility','hidden');


	    	    				  		  
	    	    				  		    
	    	    				  		    
	    	    				  		    
	    	    				  		  //Work,,0,PREF,2012:28:11 17:00:00,ALT1,2012:28:11 18:00:00,ALT2,2012:28:11 19:00:00,Richard Grossman,11OirI79iE

//	    	    				  		      var date1 = message.split(",")[INVITE_MESSAGE.PreferredDate];
//	    	    				  		      var date1day = date1.split(" ")[0];
//	    	    				  		      var hour1 = date1.split(" ")[1];
//	    	    				  		      
//	    	    				  		     var splitDate =  date1day.split(":");
//	    	    				  		     var date1dayObject  = new Date(splitDate[0], splitDate[2], splitDate[1]);
//	    	    				  		     var day = WEEK_DAY[date1dayObject.getDay()-1];
//	    	    				  		     var month = MONTH[parseInt(splitDate[2])];
//	    	    				  		     
//	    	    				  		     var dateTitle = day+", "+month+" "+splitDate[1]+", "+splitDate[0];
//	    	    				  		     
//	    	    				  		     
//	    	    				  		     var time = hour1.split(":");
//	    	    				  		     var timeHourFrom = parseInt(time[0]) % 12;
//	    	    				  		     var timeMinutesFrom = time[1];
//	    	    				  		     var amTimeFrom = parseInt(time[0]) < 12;
//	    	    				  		     
//	    	    				  		     var fromTimeTitle = timeHourFrom+":"+timeMinutesFrom;
//	    	    				  		     var ampmTitleFrom = (amTimeFrom)? "AM" :"PM";
//	    	    				  		     
//	    	    				  		     var duration1 = message.split(",")[INVITE_MESSAGE.Duration];
//	    	    				  		     var durHour = parseInt(duration1) / 60;
//	    	    				  		     durHour = durHour.toFixed(0);
//	    	    				  		     var durMin =  parseInt(duration1) % 60;
//	    	    				  		     
//	    	    				  		     
//	    	    				  		     var toMinutes = durMin + parseInt(timeMinutesFrom);
//	    	    				  		     if (toMinutes>59)
//	    	    				  		    	 {
//	    	    				  		    	 	durHour +=  toMinutes / 60;
//	    	    				  		    	 	toMinutes = toMinutes % 60;
//	    	    				  		    	 	
//	    	    				  		    	 }
//	    	    				  		   var toHour = timeHourFrom + durHour;
//	    	    				  		   var ampmTitleTo = (toHour<12)? "AM" :"PM";
//	    	    				  		   toHour = toHour % 12;
//	    	    				  		   var toMinutesTitle = (toMinutes<10)? "0"+toMinutes : toMinutes;
//	    	    				  		   
//	    	    				  		   var toTimeTitle = toHour+":"+toMinutesTitle;
//
//	    	    				  		   
//	    	    				  		   
//	    	    			//Test1,Starbucks,135,PREF,2012:25:11 20:00:00,ALT1,2012:26:11 15:00:00,ALT2,2012:30:11 00:00:00,Hunter Atlas,dAOXqk6MTh	  		      
//	    	    				  		  /// Time chooser options settings
//	    	    				  		      $("#day_date_1").html(dateTitle).show();
//	    	    				  		      $("#time1from").html(fromTimeTitle).show();
//	    	    				  		      $("#time1to").html(toTimeTitle).show();
//	    	    				  		      $("#am_pm_from1").html(ampmTitleFrom).show();
//	    	    				  		      $("#am_pm_to1").html(ampmTitleTo).show();
//	    	    				  		      $("#option1").click(function () {
//	    	    				  		          $("#choose1").css('visibility','visible');
//	    	    				  		          $("#choose2").css('visibility','hidden');
//	    	    				  		          $("#choose3").css('visibility','hidden');
//	    	    				  		          
//	    	    				  		        });
	    	    				  		      
	    	    				  		      
	    	    				  		      
	    	    				  		      
	    	    				  		      
//	    	    				  		      $("#day_date_2").html("Thursday, November 29, 2013").show();
//	    	    				  		      $("#time2from").html("2:15").show();
//	    	    				  		      $("#time2to").html("4:45").show();
//	    	    				  		      $("#am_pm_from2").html("AM").show();
//	    	    				  		      $("#am_pm_to2").html("AM").show();
//	    	    				  		      $("#option2").click(function () {
//	    	    				  		          $("#choose2").css('visibility','visible');
//	    	    				  		          $("#choose1").css('visibility','hidden');
//	    	    				  		          $("#choose3").css('visibility','hidden');
//	    	    				  		          
//	    	    				  		        });
//	    	    				  		      
//	    	    				  		      
//	    	    				  		      $("#day_date_3").html("Friday, November 30, 2013").show();
//	    	    				  		      $("#time3from").html("3:15").show();
//	    	    				  		      $("#time3to").html("5:15").show();
//	    	    				  		      $("#am_pm_from3").html("AM").show();
//	    	    				  		      $("#am_pm_to3").html("AM").show();
//	    	    				  		      $("#option3").click(function () {
//	    	    				  		          $("#choose3").css('visibility','visible');
//	    	    				  		          $("#choose2").css('visibility','hidden');
//	    	    				  		          $("#choose1").css('visibility','hidden');
//	    	    				  		          
//	    	    				  		        });
	    	    				  				
	    	    				  				
	    	    				  			}
	    	    				  		});

	    				  			}
		    			  });
	    				  
	    				  
	    				  }
	    		  }
	    				  });
	    	  }
	    			 
	    			  
	     
	      
	      
	      
	     // Work,,0,PREF,2012:28:11 17:00:00,ALT1,2012:28:11 18:00:00,ALT2,2012:28:11 19:00:00,Richard Grossman,11OirI79iE
	     // he parse event invite string is formatted as follows:

	    //  Title, Location, Duration in Minutes, "PREF", Preferred Date, "ALT1", Alternative Date 1, "ALT2", 
	    //  Alternative Date 2, from Display Name, from Atlas ID
	      
	      
	      
	     

	     

	    

	   //  document.getElementById("timeid").innerHTML = "WELCOME";
	     var message = "Please touch to confirm the best time from the choices ";
	     
      	
	  	var uri = location.href; 
    	var c = parseUri(uri).file;
    	
    	var parseId = parseUri(uri).queryKey["id"];
       // self.$(".cal-form container.header.h2").html("Welcome ").show();

	      //SPjDFlzY7c
	      
	      
//	      var InviteToAtlas = Parse.Object.extend("inviteToAtlas");
//      	 var query = new Parse.Query(InviteToAtlas);
//	        query.get("SPjDFlzY7c", {
//	          success: function(invite) {
//	            // The object was retrieved successfully.
//	          	
//	          	 var email = invite.get("email");
//	          	 var name = invite.get("name");
//	          	
//	          	 var inviterID = invite.get("inviterID")
//	          	
//	          	var inviter_name;
//	          	 
//	        //  	self.$(".cal-form .header").html(message).show();
//	          	 
//	          	var query2 = new Parse.Query(Parse.User);
//	          	 query2.get(inviterID, { 
//	          	  success: function(inviter) {
//	          	     var inviter_name = inviter.get("userNameDisplay");
//	 	          	
//		          	 message = message + inviter_name+" has given you below:";
//
//	          	     
//	          	   //  self.$(".cal-form .h2").html(message).show();
//	          	  }
//	          	});
//	          	
//	          },
//	          error: function(object, error) {
//	        	  
//
//	            // The object was not retrieved successfully.
//	            // error is a Parse.Error with an error code and description.
//	          }
//	        });
//	        
	        
	    }
	    
	  });
 ///// END CAL INVITE VIEW
  
  
  
  
  
  
  
  
  
  
  
  

  var LogInView = Parse.View.extend({
    events: {
      "submit form.login-form": "logIn",
      "submit form.signup-form": "signUp"
    },

    el: ".content",
    
    initialize: function() {
      _.bindAll(this, "logIn", "signUp");
      this.render();
    },

    logIn: function(e) {
      var self = this;
      var username = this.$("#login-username").val();
      var password = this.$("#login-password").val();
     
        
      
      Parse.User.logIn(username, password, {
        success: function(user) {
          new ManageTodosView();
          self.undelegateEvents();
          delete self;
        },

        error: function(user, error) {
          self.$(".login-form .error").html("Invalid username or password. Please try again.").show();
          this.$(".login-form button").removeAttr("disabled");
        }
      });

      this.$(".login-form button").attr("disabled", "disabled");

      return false;
    },

    signUp: function(e) {
      var self = this;
      var username = this.$("#signup-username").val();
      var password = this.$("#signup-password").val();
      
      Parse.User.signUp(username, password, { ACL: new Parse.ACL() }, {
        success: function(user) {
          new ManageTodosView();
          self.undelegateEvents();
          delete self;
        },

        error: function(user, error) {
          self.$(".signup-form .error").html(error.message).show();
          this.$(".signup-form button").removeAttr("disabled");
        }
      });

      this.$(".signup-form button").attr("disabled", "disabled");

      return false;
    },

    render: function() {
      this.$el.html(_.template($("#login-template").html()));
      this.delegateEvents();
      
      
      
      var InviteToAtlas = Parse.Object.extend("inviteToAtlas");
      var query = new Parse.Query(InviteToAtlas);
      query.get("SPjDFlzY7c", {
        success: function(invite) {
          // The object was retrieved successfully.
        	
        	var email = invite.get("email");
        	var name = invite.get("name");
        	
        	self.$(".login-form h2").html("Welcome "+name+"!").show();


        	
        	
        },
        error: function(object, error) {
          // The object was not retrieved successfully.
          // error is a Parse.Error with an error code and description.
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
//      if (Parse.User.current()) {
//        new ManageTodosView();
//      } else {
//      //  new LogInView();
//        new inviteView();
//      }
    	var uri = location.href; 
      	var c = parseUri(uri).file;
      	
      	var type = parseUri(uri).queryKey["type"];
      	switch(type)
      	{
      	case "inviteToApp":
      		new inviteView();
      	  break;
      	case "inviteToEvent":
      	  new calInviteView();
      	  break;
      	default:
      		new inviteView();
      	  break;
      	}
      	
    	
    	
    	
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
