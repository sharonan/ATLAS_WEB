$(function () {
    // Initialize Parse with your Parse application javascript keys

	var parts = window.location.host.split('.')
	var subdomain = parts[0];
    var Environment = subdomain;

	//var Environment = "dev";
    switch (Environment) {
        case "dev":
            Parse.initialize("FvEPBedzfhHchdviDbKOayUm2W9aPAzCrVcIRTT4", "nPgVAfkwjmaSkDqLTjhEzlJjfHH3QjTJPAZOz8JJ");
            break;
        case "qa":
            Parse.initialize("ZfzVtSyJdEemMVz7RVC5DnWNxrvrcJiFSpopUxHg", "sPHpBrIKIUxbI6e0ItAQC7Qx1F4EmjVeXCw4chM1");
            break;
		case "demo":
            Parse.initialize("JRi0WKejt1nd6qy4iry27KAVmEvgexvcShZduaMG", "XqH08NKyno2l7dwbr12BwCejr8ZTj2GPE0nFQ3fm");
            break;
		case "beta":
            Parse.initialize("d8ccXhrq3W1xVQBo36n6HjnC8PznadKXGOndtZcK", "m4S5MxOtfeV87e4hGHdBDvAGKSsfAvS9FRQcdo9w");
            break;
        default:
            Parse.initialize("HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX", "ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67");
    }

    pullItemUser();

    function pullItemUser() {
        // Works only if item user of primary event!!!!!
        var webItemUser = window.webItemUser;
        var parseClass = "item_user";
        var parseClassObject = Parse.Object.extend(parseClass);
        var query = new Parse.Query(parseClassObject);
        query.equalTo("web_item_user_id", webItemUser.webItemUserId);
        query.first({
            success: function (object) {
				if (!object) {
					$('.loader').hide();
					alert("No invite found");
					return;
				}
                webItemUser.objectId = object.id;
                webItemUser.atlasId = object.get("atlas_id");
                webItemUser.itemType = object.get("item_type");
                webItemUser.status = object.get("status");
				webItemUser.priorityOrder = object.get("priority_order");
                webItemUser.wasReceived = object.get("was_received");
                webItemUser.webItemId = object.get("web_item_id");
                window.webItemUser = webItemUser;

                // Start Populating with Object Id
                if (webItemUser.itemType === "event") {
                    pullPrimaryEvent();
                } else if (webItemUser.itemType === "task") {

                }

                object.set("was_received", true);
                object.save();
            },
            error: function (object, error) {
                alert("Error: " + error.code + " " + error.message);
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
                }
            }
        });
    }

    function pullPrimaryEvent() {
        var parseClass = "event";
        var parseClassObject = Parse.Object.extend(parseClass);
        var query = new Parse.Query(parseClassObject);
        query.equalTo("web_event_id", window.webItemUser.webItemId);
        query.first({
            success: function (object) {
                var webEvent = {};
                webEvent.primaryWebEventId = object.get("primary_web_event_id");
                if (webEvent.primaryWebEventId !== "") {
                    //error
                }
				webEvent.webEventId = object.get("web_event_id");
                webEvent.atlasId = object.get("atlas_id");
                webEvent.displayOrder = object.get("display_order");
                webEvent.status = object.get("status");
                webEvent.startDatetime = object.get("start_datetime");
                webEvent.title = object.get("title");
                webEvent.duration = object.get("duration");
                webEvent.location = object.get("location");
                webEvent.notes = object.get("notes");
				webEvent.parseObject = object;
                window.webEvent = webEvent;

                window.webEventArray = [webEvent];

                pullAltEvents();
            },
            error: function (object, error) {
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
                }
            }
        });
    }

    function pullAltEvents() {
        var parseClass = "event";
        var parseClassObject = Parse.Object.extend(parseClass);
        var query = new Parse.Query(parseClassObject);
        query.equalTo("primary_web_event_id", window.webItemUser.webItemId);
		query.ascending("display_order");
        query.find({
            success: function (results) {
                var webAltEvents = [];
                var index;
                for (index in results) {
					var object = {};
					webAltEvents.push(object);
                    webAltEvents[index].webEventId = results[index].get("web_event_id");
                    webAltEvents[index].displayOrder = results[index].get("display_order");
                    webAltEvents[index].status = results[index].get("status");
                    webAltEvents[index].startDatetime = results[index].get("start_datetime");
                    webAltEvents[index].title = results[index].get("title");
                    webAltEvents[index].duration = results[index].get("duration");
                    webAltEvents[index].location = results[index].get("location");
                    webAltEvents[index].notes = results[index].get("notes");
					webAltEvents[index].parseObject = results[index];

                    window.webEventArray.push(webAltEvents[index]);
                }

				pullItemUsers();
            },
            error: function (object, error) {
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
                }
            }
        });
    }

	function pullItemUsers() {
		var webEventIdArray = [];
		var index;
		for (index in window.webEventArray) {
			webEventIdArray.push(window.webEventArray[index].webEventId);
		}

        var parseClass = "item_user";
        var parseClassObject = Parse.Object.extend(parseClass);
        var query = new Parse.Query(parseClassObject);
        query.containedIn("web_item_id", webEventIdArray);
		query.find({
            success: function (results) {
                var allItemUsers = results.length;
				var allEvents = window.webEventArray.length;
				// Total Item Users
				window.webItemUserCount = allItemUsers / allEvents;

				query.equalTo("atlas_id", window.webItemUser.atlasId);
				query.find({
					success: function (results) {
						var webItemUsers = [];
						var index;
						for (index in results) {
							var object = {};
							webItemUsers.push(object);
							webItemUsers[index].webItemId = results[index].get("web_item_id");
							webItemUsers[index].status = results[index].get("status");
							webItemUsers[index].priorityOrder = results[index].get("priority_order");
							webItemUsers[index].parseObject = results[index];
						}
						window.webItemUsersArray = webItemUsers;

						pullInvitee();
                		pullInviter();
					},
					error: function (object, error) {
						alert("Error: " + error.code + " " + error.message);
						if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
							alert("Uh oh, we couldn't find the object!");
						} else if (error.code === Parse.Error.CONNECTION_FAILED) {
							alert("Uh oh, we couldn't even connect to the Parse Cloud!");
						}
					}
				});
            },
            error: function (object, error) {
                alert("Error: " + error.code + " " + error.message);
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
                }
            }
        });
    }

    function pullInvitee() {
        var webInvitee = {};
        webInvitee.atlasId = window.webItemUser.atlasId;
        var query = new Parse.Query(Parse.User);
        query.get(webInvitee.atlasId, {
            success: function (object) {
                webInvitee.firstName = object.get("first_name");
                webInvitee.lastName = object.get("last_name");
                webInvitee.fullName = webInvitee.firstName + " " + webInvitee.lastName;
                webInvitee.isAtlasUser = object.get("is_atlas_user");
                if (webInvitee.isAtlasUser) {
                    //error
                }
                window.webInvitee = webInvitee;

				displayAfterLoading();
            }
        });
    }

    function pullInviter() {
        var webInviter = {};
        webInviter.atlasId = window.webEvent.atlasId;
        var query = new Parse.Query(Parse.User);
        query.get(webInviter.atlasId, {
            success: function (object) {
                webInviter.firstName = object.get("first_name");
                webInviter.lastName = object.get("last_name");
                webInviter.fullName = webInviter.firstName + " " + webInviter.lastName;
                webInviter.picture = object.get("picture").url;
                window.webInviter = webInviter;

                displayAfterLoading();
            }
        });
    }

	function displayAfterLoading() {
		if (window.webInvitee.fullName !== "" && window.webInviter.fullName !== "") {
			// Display HTML
			displayView();
		}
	}

	function displayView() {
        // status = 0 pending
        // status = 1 theOne
        // status = 2 notTheOne

        if (window.webEvent.status !== 0) { //Booked
			var index;
			for (index in window.webEventArray) {
				if (window.webEventArray[index].status == 1) {
					window.webEventBooked = window.webEventArray[index];
				}
			}
			$.ajax({
				type: 'GET',
				url: window.webEventBooked == undefined ? 'PHP/declined.php' : 'PHP/booked.php',
				success: function(data) {
					$('.content').html(data);
					displayEventHTML();
					displayBooked();
				}
			});
        } else { //Pending
			$.ajax({
				type: 'GET',
				url: ((window.webItemUserCount == 2) ? 'PHP/response.php' : 'PHP/response_group.php'),
				success: function(data) {
					$(".content").html(data);
					if (window.webItemUserCount == 2) {
						$(".option").css("cursor", "pointer");
					}
					displayEventHTML();
					displayOptions();
				}
			});
        }
    }

	function displayEventHTML() {
        $(".event_title").html(window.webEvent.title);
        if (window.webEvent.location) {
            $(".event_location").html(" at " + window.webEvent.location);
        }

		$(".invitee_firstname").html(window.webInvitee.firstName);
        $(".invitee_name").html(window.webInvitee.fullName);
        $(".inviter_name").html(window.webInviter.fullName);
		$(".avatar").attr('src', window.webInviter.picture);

        // Show Content
        $('.loader').hide();
        $('.content').show();
    }

	function displayBooked() {
        $('.description').css('font-size', '0.8em');
        $('.event_title, .event_location').css('color', '#323232');

		var startDate = new Date(window.webEventBooked.startDatetime);
        var endDate = new Date(startDate);
        endDate.setMinutes(startDate.getMinutes() + parseInt(window.webEventBooked.duration));
		$('.date').html(formattedDate(startDate));
        $('.time1').html(timeWithAmPm(startDate.getHours(), startDate.getMinutes()));
        $('.time2').html(timeWithAmPm(endDate.getHours(), endDate.getMinutes()));

		displayCalendars();
	}

    function displayOptions() {
        var webEventArray = window.webEventArray;

        // Change Width of Option Base
        var optionBaseWidth = webEventArray.length * 288;
        $('.options').css('width', optionBaseWidth + 'px');

        // Build Time Options in Option Base
        var index;
        for (index in webEventArray) {
            // Create Options if Needed
            var option = index > 0 ? $('.option[value=0]').clone() : $('.option[value=0]');

            // Add Times to Options
            displayOptionInfo(option, webEventArray[index]);

            // Add value attribute
            option.attr('value', index);

            // Change Option Header Name
            var optionVal = parseInt(index) + 1;
            option.find('.option_header').html('<b>Option ' + optionVal+'</b>');

            // Add to Option Base
            option.appendTo('.options');
        }
    }

    // Populate Option Times
    function displayOptionInfo(option, webEvent) {
        var startDate = new Date(webEvent.startDatetime);

        // Create EndDate From startDatetime + duration
        var endDate = new Date(startDate);
        endDate.setMinutes(startDate.getMinutes() + parseInt(webEvent.duration));

        if (option) {
            // Add Date to Option
            option.find('.date').html(formattedDate(startDate));
            option.find('.time1').html(timeWithAmPm(startDate.getHours(), startDate.getMinutes()));
            option.find('.time2').html(timeWithAmPm(endDate.getHours(), endDate.getMinutes()));
        }
    }

	$(document).on("click", ".option_vote img", function() {
		$(this).attr("selected", true);
        var priorityOrder = $(this).attr("priorityorder");
		switch (priorityOrder) {
			case "0":
				$(this).attr('src', 'images/vote_ideal_selected.png');
				break;
			case "1":
				$(this).attr('src', 'images/vote_okay_selected.png');
				break;
			case "-1":
				$(this).attr('src', 'images/vote_cant_selected.png');
				break;
		}

		var notSelected = $(this).siblings("img:not([priorityorder="+priorityOrder+"])");
		notSelected.each(function(index, object) {
			$(object).attr("selected", false);
			var priorityOrder = $(object).attr("priorityorder");
			switch (priorityOrder) {
				case "0":
					$(object).attr('src', 'images/vote_ideal.png');
					break;
				case "1":
					$(object).attr('src', 'images/vote_okay.png');
					break;
				case "-1":
					$(object).attr('src', 'images/vote_cant.png');
					break;
			}
		});
    });

	$(document).on("click", ".select_option", function() {
		var list = [];
		var selectedDatetime;

		var status = $(this).attr("value");
		for (index in window.webEventArray) {
			var Event = Parse.Object.extend("event");
			var webEvent = new Event();
			webEvent = window.webEventArray[index].parseObject;
			if (index === status) {
				window.webEventBooked = window.webEventArray[index];
				selectedDatetime = window.webEventArray[index].startDatetime;
				webEvent.set("status", 1);
			} else {
				webEvent.set("status", 2);
			}
			list.push(webEvent);
		}

		var message = "You're all booked! "+window.webInvitee.fullName+" has chosen "+timeWithAmPm(selectedDatetime.getHours(), selectedDatetime.getMinutes())+" on "+formattedDate(selectedDatetime)+" for '"+window.webEvent.title+"'.";

		bookEvent(list, message);
	});

	$(document).on("click", ".decline", function() {
		var list = [];

		for (index in window.webEventArray) {
			var Event = Parse.Object.extend("event");
			var webEvent = new Event();
			webEvent = window.webEventArray[index].parseObject;
			webEvent.set("status", 2);
			list.push(webEvent);
		}

		var message = window.webInvitee.fullName+" has declined '"+window.webEvent.title+"'.";

		bookEvent(list, message);
	});

	function bookEvent(list, message) {
		Parse.Object.saveAll(list, {
			success: function(list) {
				// Saved
				var channel = "ID"+window.webEvent.atlasId;
				sendPushNotification(channel, message);

				$.ajax({
					type: 'GET',
					url: window.webEventBooked == undefined ? 'PHP/declined.php' : 'PHP/booked.php',
					success: function(data) {
						$('.content').html(data);
						displayEventHTML();
						displayBooked();
					}
				});
			},
			error: function(error) {
				alert("Error: " + error.code + " " + error.message);
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
                }
			}
		});
	}

	$(document).on("click", ".vote", function() {
		var list = [];

		$('.option_vote img[selected=selected]').each(function(eventIndex, object) {
			//var parentValue = $(object).parent().parent().attr("priorityOrder");
			var status = $(this).attr("status");
			var priorityOrder = $(this).attr("priorityorder");

			var itemUserIndex;
			for (itemUserIndex in window.webItemUsersArray) {
				if (window.webItemUsersArray[itemUserIndex].webItemId === window.webEventArray[eventIndex].webEventId) {
					var ItemUser = Parse.Object.extend("item_user");
					var itemUser = new ItemUser();
					itemUser = window.webItemUsersArray[itemUserIndex].parseObject;
					itemUser.set("status", parseInt(status));
					itemUser.set("priority_order", parseInt(priorityOrder));
					list.push(itemUser);
				}
			}
        });

		if (list.length < window.webEventArray.length) {
			alert("Please give your vote for each time");
			return;
		}

		Parse.Object.saveAll(list, {
			success: function(list) {
				// Saved
				var message = window.webInvitee.fullName+" has voted on '"+window.webEvent.title+"'. Check the responses so far?";
				var channel = "ID"+window.webEvent.atlasId;
				sendPushNotification(channel, message);

				$.ajax({
					type: 'GET',
					url: 'PHP/sent.php',
					success: function(data) {
						$('.content').html(data);
						displayEventHTML();
					}
				});
			},
			error: function(error) {
				alert("Error: " + error.code + " " + error.message);
                if (error.code === Parse.Error.OBJECT_NOT_FOUND) {
                    alert("Uh oh, we couldn't find the object!");
                } else if (error.code === Parse.Error.CONNECTION_FAILED) {
                    alert("Uh oh, we couldn't even connect to the Parse Cloud!");
                }
			}
		});
	});





    // Return Time
    function timeWithAmPm(hours, mins) {
		if (hours === 0) {hours = 12;}
        if (mins === 0) {mins = "00";}
        if (hours <= 12) {return hours + ":" + mins + "AM";}
        if (hours > 12) {return parseInt(hours) - 12 + ":" + mins + "PM";}
    }

    // Return Date
    function formattedDate(date) {
        var dayOfWeek = date.getDayName();
        var month = date.getMonthName();
        var dayOfMonth = date.getDate();
        return dayOfWeek + ", " + month + " " + dayOfMonth;
    }

    // Custom Date Functions
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    Date.prototype.getMonthName = function () {
        return months[this.getMonth()];
    }
    Date.prototype.getDayName = function () {
        return days[this.getDay()];
    }


    function sendPushNotification(channel, message) {
        var today = new Date();
        var tomorrow = new Date(today.getDate() + 1);
        Parse.Push.send({
            channels: [channel],
            expiration_time: tomorrow,
            data: {
                alert: message,
                badge: "Increment",
                sound: "push.aiff",
                title: "Atlas"
            }
        }, {
            success: function () {

            },
            error: function (error) {

            }
        });
    }

    // Load Calendar Items
    function displayCalendars() {
        var title = window.webEventBooked.title+" with "+window.webInviter.fullName;
        var location = window.webEventBooked.location;
		var notes = "Booked with Atlas. Get Atlas for free!";
		var start = new Date(window.webEventBooked.startDatetime);
        var end = new Date(start);
        end.setMinutes(start.getMinutes() + parseInt(window.webEventBooked.duration));

        // Google
        event = {
            what: title,
            dates: {
                start: {
                    year: start.getFullYear(),
                    month: start.getMonth(),
                    day: start.getDate(),
                    hour: start.getHours(),
                    minute: start.getMinutes()
                },
                end: {
                    year: end.getFullYear(),
                    month: end.getMonth(),
                    day: end.getDate(),
                    hour: end.getHours(),
                    minute: end.getMinutes()
                }
            },
            location: location,
            trp: false,
            details: notes,
            url: document.URL
        };
        var googleUrl = GoogleCalendar.getGoogleCalendarEventLink(event);
        $('.google').attr('href', googleUrl); // Add to google button


        // iCalendar
        // Outlook
        var params = {
            start: getICSDate(start),
            end: getICSDate(end),
            title: title,
            desc: notes,
            location: location
        };
        var icalUrl = "php/ical.php?" + $.param(params);
        $('.ical').attr('href', icalUrl); // Add to ical button
        $('.outlook').attr('href', icalUrl); // Add to outlook button
    }

    // Get ICS Date
    function getICSDate(date) {
        //Ex: 2009-11-06 09:00
        var dateStr = date.getUTCFullYear();
        dateStr += "-";
        dateStr += addZero(date.getUTCMonth() + 1);
        dateStr += "-";
        dateStr += addZero(date.getUTCDate());
        dateStr += " ";
        dateStr += addZero(date.getUTCHours());
        dateStr += ":";
        dateStr += addZero(date.getUTCMinutes());
        return dateStr;
    }

    // Add a leading '0' if string is only 1 char
    function addZero(str) {
        var newStr = str;
        if (newStr.length == 1) {
            newStr = "0" + newStr;
        }
        return newStr;
    }
});