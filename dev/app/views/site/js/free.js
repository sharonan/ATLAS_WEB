/**
 * jquery.calendario.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2012, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {
	
	'use strict';

	$.Calendario = function( options, element ) {
		
		this.$el = $( element );
		this._init( options );
		
	};

	// the options
	$.Calendario.defaults = {
		/*
		you can also pass:
		month : initialize calendar with this month (1-12). Default is today.
		year : initialize calendar with this year. Default is today.
		caldata : initial data/content for the calendar.
		caldata format:
		{
			'MM-DD-YYYY' : 'HTML Content',
			'MM-DD-YYYY' : 'HTML Content',
			'MM-DD-YYYY' : 'HTML Content'
			...
		}
		*/
		weeks : [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
		weekabbrs : [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],
		months : [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
		monthabbrs : [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
		// choose between values in options.weeks or options.weekabbrs
		displayWeekAbbr : false,
		// choose between values in options.months or options.monthabbrs
		displayMonthAbbr : false,
		// left most day in the calendar
		// 0 - Sunday, 1 - Monday, ... , 6 - Saturday
		startIn : 0,
		onDayClick : function( $el, $content, dateProperties ) { return false; }
	};

	$.Calendario.prototype = {

		_init : function( options ) {
			
			// options
			this.options = $.extend( true, {}, $.Calendario.defaults, options );

			this.today = new Date();
			this.month = ( isNaN( this.options.month ) || this.options.month == null) ? this.today.getMonth() : this.options.month - 1;
			this.year = ( isNaN( this.options.year ) || this.options.year == null) ? this.today.getFullYear() : this.options.year;
			this.caldata = this.options.caldata || {};
			this._generateTemplate();
			this._initEvents();

		},
		_initEvents : function() {

			var self = this;

			this.$el.on( 'click.calendario', 'div.fc-row > div', function() {

				var $cell = $( this ),
					idx = $cell.index(),
					$content = $cell.children( 'div' ),
					dateProp = {
						day : $cell.children( 'span.fc-date' ).text(),
						month : self.month + 1,
						monthname : self.options.displayMonthAbbr ? self.options.monthabbrs[ self.month ] : self.options.months[ self.month ],
						monthabbr : self.options.monthabbrs[ self.month ],
						year : self.year,
						weekday : idx + self.options.startIn,
						weekdayname : self.options.weeks[ idx + self.options.startIn ]
					};

				if( dateProp.day ) {
					self.options.onDayClick( $cell, $content, dateProp );
				}

			} );

		},
		// Calendar logic based on http://jszen.blogspot.pt/2007/03/how-to-build-simple-calendar-with.html
		_generateTemplate : function( callback ) {

			var head = this._getHead(),
				body = this._getBody(),
				rowClass;

			switch( this.rowTotal ) {
				case 4 : rowClass = 'fc-four-rows'; break;
				case 5 : rowClass = 'fc-five-rows'; break;
				case 6 : rowClass = 'fc-six-rows'; break;
			}

			this.$cal = $( '<div class="fc-calendar ' + rowClass + '">' ).append( head, body );

			this.$el.find( 'div.fc-calendar' ).remove().end().append( this.$cal );

			if( callback ) { callback.call(); }

		},
		_getHead : function() {

			var html = '<div class="fc-head">';
		
			for ( var i = 0; i <= 6; i++ ) {

				var pos = i + this.options.startIn,
					j = pos > 6 ? pos - 6 - 1 : pos;

				html += '<div>';
				html += this.options.displayWeekAbbr ? this.options.weekabbrs[ j ] : this.options.weeks[ j ];
				html += '</div>';

			}

			html += '</div>';

			return html;

		},
		_getBody : function() {

			var d = new Date( this.year, this.month + 1, 0 ),
				// number of days in the month
				monthLength = d.getDate(),
				firstDay = new Date( this.year, this.month, 1 );

			// day of the week
			this.startingDay = firstDay.getDay();

			var html = '<div class="fc-body"><div class="fc-row">',
				// fill in the days
				day = 1;

			// this loop is for weeks (rows)
			for ( var i = 0; i < 7; i++ ) {

				// this loop is for weekdays (cells)
				for ( var j = 0; j <= 6; j++ ) {

					var pos = this.startingDay - this.options.startIn,
						p = pos < 0 ? 6 + pos + 1 : pos,
						inner = '',
						today = this.month === this.today.getMonth() && this.year === this.today.getFullYear() && day === this.today.getDate(),
						content = '';
					
					if ( day <= monthLength && ( i > 0 || j >= p ) ) {

						inner += '<span class="fc-date">' + day + '</span><span class="fc-weekday">' + this.options.weekabbrs[ j + this.options.startIn > 6 ? j + this.options.startIn - 6 - 1 : j + this.options.startIn ] + '</span>';

						// this day is:
						var strdate = ( this.month + 1 < 10 ? '0' + ( this.month + 1 ) : this.month + 1 ) + '-' + ( day < 10 ? '0' + day : day ) + '-' + this.year,
							dayData = this.caldata[ strdate ];

						if( dayData ) {
							content = dayData;
						}

						if( content !== '' ) {
							inner += '<div>' + content + '</div>';
						}

						++day;

					}
					else {
						today = false;
					}

					var cellClasses = today ? 'fc-today ' : '';
					if( content !== '' ) {
						cellClasses += 'fc-content';
					}
					if(inner == '') cellClasses = 'disabled';

					html += cellClasses !== '' ? '<div id="cal_day_'+day+'" class="' + cellClasses + '">' : '<div id="cal_day_'+day+'">';
					html += inner;
					html += '</div>';

				}

				// stop making rows if we've run out of days
				if (day > monthLength) {
					this.rowTotal = i + 1;
					break;
				} 
				else {
					html += '</div><div class="fc-row">';
				}

			}
			html += '</div></div>';

			return html;

		},
		// based on http://stackoverflow.com/a/8390325/989439
		_isValidDate : function( date ) {

			date = date.replace(/-/gi,'');
			var month = parseInt( date.substring( 0, 2 ), 10 ),
				day = parseInt( date.substring( 2, 4 ), 10 ),
				year = parseInt( date.substring( 4, 8 ), 10 );

			if( ( month < 1 ) || ( month > 12 ) ) {
				return false;
			}
			else if( ( day < 1 ) || ( day > 31 ) )  {
				return false;
			}
			else if( ( ( month == 4 ) || ( month == 6 ) || ( month == 9 ) || ( month == 11 ) ) && ( day > 30 ) )  {
				return false;
			}
			else if( ( month == 2 ) && ( ( ( year % 400 ) == 0) || ( ( year % 4 ) == 0 ) ) && ( ( year % 100 ) != 0 ) && ( day > 29 ) )  {
				return false;
			}
			else if( ( month == 2 ) && ( ( year % 100 ) == 0 ) && ( day > 29 ) )  {
				return false;
			}

			return {
				day : day,
				month : month,
				year : year
			};

		},
		_move : function( period, dir, callback ) {

			if( dir === 'previous' ) {
				
				if( period === 'month' ) {
					this.year = this.month > 0 ? this.year : --this.year;
					this.month = this.month > 0 ? --this.month : 11;
				}
				else if( period === 'year' ) {
					this.year = --this.year;
				}

			}
			else if( dir === 'next' ) {

				if( period === 'month' ) {
					this.year = this.month < 11 ? this.year : ++this.year;
					this.month = this.month < 11 ? ++this.month : 0;
				}
				else if( period === 'year' ) {
					this.year = ++this.year;
				}

			}

			this._generateTemplate( callback );

		},
		/************************* 
		******PUBLIC METHODS *****
		**************************/
		getYear : function() {
			return this.year;
		},
		getMonth : function() {
			return this.month + 1;
		},
		getMonthName : function() {
			return this.options.displayMonthAbbr ? this.options.monthabbrs[ this.month ] : this.options.months[ this.month ];
		},
		// gets the cell's content div associated to a day of the current displayed month
		// day : 1 - [28||29||30||31]
		getCell : function( day ) {

			var row = Math.floor( ( day + this.startingDay - this.options.startIn ) / 7 ),
				pos = day + this.startingDay - this.options.startIn - ( row * 7 ) - 1;

			return this.$cal.find( 'div.fc-body' ).children( 'div.fc-row' ).eq( row ).children( 'div' ).eq( pos ).children( 'div' );

		},
		setData : function( caldata ) {

			caldata = caldata || {};
			$.extend( this.caldata, caldata );
			this._generateTemplate();

		},
		// goes to today's month/year
		gotoNow : function( callback ) {

			this.month = this.today.getMonth();
			this.year = this.today.getFullYear();
			this._generateTemplate( callback );

		},
		// goes to month/year
		goto : function( month, year, callback ) {

			this.month = month;
			this.year = year;
			this._generateTemplate( callback );

		},
		gotoPreviousMonth : function( callback ) {
			this._move( 'month', 'previous', callback );
		},
		gotoPreviousYear : function( callback ) {
			this._move( 'year', 'previous', callback );
		},
		gotoNextMonth : function( callback ) {
			this._move( 'month', 'next', callback );
		},
		gotoNextYear : function( callback ) {
			this._move( 'year', 'next', callback );
		}

	};
	
	var logError = function( message ) {

		if ( window.console ) {

			window.console.error( message );
		
		}

	};
	
	$.fn.calendario = function( options ) {

		var instance = $.data( this, 'calendario' );
		
		if ( typeof options === 'string' ) {
			
			var args = Array.prototype.slice.call( arguments, 1 );
			
			this.each(function() {
			
				if ( !instance ) {

					logError( "cannot call methods on calendario prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {

					logError( "no such method '" + options + "' for calendario instance" );
					return;
				
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
				
				if ( instance ) {

					instance._init();
				
				}
				else {

					instance = $.data( this, 'calendario', new $.Calendario( options, this ) );
				
				}

			});
		
		}
		
		return instance;
		
	};
	
} )( jQuery, window );

$(document).ready(function(){
		
	if($('#calendar').length){
		var curtime = false;

		function inctime(){
			curtime = false;
			for(var i = 1; i < 4; i++){
				if($('#opt'+i+' .containitem').html() == ""){
					curtime = i;
					return curtime;
				}
			}
			return curtime;
		}

		var d=new Date();
		var weekday=new Array(7);
		weekday[0]="Sunday";
		weekday[1]="Monday";
		weekday[2]="Tuesday";
		weekday[3]="Wednesday";
		weekday[4]="Thursday";
		weekday[5]="Friday";
		weekday[6]="Saturday";

		var n = weekday[d.getDay()];

		var	$wrapper = $( '#custom-inner' ),
			$calendar = $( '#calendar' ),
			cal = $calendar.calendario( {
				onDayClick : function( $el, $contentEl, dateProperties ) {
					addEvent( $el, $contentEl, dateProperties );
				},
				displayWeekAbbr : true
			} ),
			$month = $( '#custom-month' ).html( cal.getMonthName() ),
			$year = $( '#custom-year' ).html( cal.getYear() );

		$( '#custom-next' ).on( 'click', function() {
			cal.gotoNextMonth( updateMonthYear );
			$('#timepicker').css('height', $('#calendar').parent().css('height'));
		} );
		$( '#custom-prev' ).on( 'click', function() {
			cal.gotoPreviousMonth( updateMonthYear );
			$('#timepicker').css('height', $('#calendar').parent().css('height'));
		} );

		function updateMonthYear() {				
			$month.html( cal.getMonthName() );
			$year.html( cal.getYear() );
		}


		function addEvent( $el, $contentEl, dateProperties ) {
			$('.fc-row div').removeClass('active');
			$('.timevalue').removeClass('active check');

			function populatechecks(){
				for(var i=1;i<4;i++){
					if($('#day_'+i).val() == dateProperties.weekdayname + ", " + dateProperties.monthname + " " + dateProperties.day){ 
						$('#timevalue_'+$('#time_'+i).val()).addClass('check');
					}
				}
			}

			populatechecks();

			var $events = $( '<div id="custom-content-reveal" class="custom-content-reveal"><h4>Events for ' + dateProperties.monthname + ' ' + dateProperties.day + ', ' + dateProperties.year + '</h4></div>' ).on( 'click', removeEvent );

			$('h2.custom-day').html(dateProperties.weekdayname + ', ' + dateProperties.monthabbr + ' ' + dateProperties.day);

			$('#select_date').val(dateProperties.weekdayname + ", " + dateProperties.monthname + " " + dateProperties.day);

			$($el).addClass('active');

			//$events.append( $contentEl.html() ).insertAfter( $wrapper );
			/*
			setTimeout( function() {
				$events.css( 'top', '0%' );
			}, 25 );
			*/

			function handletimeclick(){
				var timespot = inctime();
				if(timespot){
					$('.timevalue').removeClass('active');
					$(this).addClass('active check');
					$($el).addClass('check');

					var datetext = dateProperties.weekdayname + ", " + dateProperties.monthname + " " + dateProperties.day;
					var timetext = $(this).data('time');
					var hour1text = $(this).data('hour')+$(this).data('m');

					$('#day_' + timespot).val(datetext);
					$('#time_' + timespot).val(timetext);

					var durationtext = $('#durationtext').val();

					var itembox = $('<div class="item">'
								+	'<h3>'+datetext+'</h3>'
								+	'<p>'+hour1text+' for <span class="timelength">'+durationtext+'</span></p>'
								+'</div>').on('click', removeEvent);
					$(itembox).appendTo('#opt'+timespot+" .containitem").delay(1).queue(function(next){
						$(this).addClass("slid");
						next();
					});
				} else {
					alert("You may only pick 3 times.");
				}
			}

			$('.timevalue').unbind('click');
			$('.timevalue').bind('click', handletimeclick);

		}
		function removeEvent() {
			var id		= $(this).parent().parent().attr('id').split('opt')[1];
			var removeid	= $('#day_'+id).val().split(" ")[2];
			var removetime = $('#time_'+id).val();
			var count		= 0;
			for(var i = 1; i < 4; i++){
				var checkval = $('#day_'+i).val().split(" ")[2];
				if(checkval == removeid) count++;
			}
			if(count == 1){
				$('#cal_day_'+(parseInt(removeid)+1)).removeClass('check');
			}
			$('#timevalue_'+removetime).removeClass('check');
			$('#day_'+id).val("");
			$('#time_'+id).val("");
			$(this).removeClass("slid").delay(200).queue(function(next){
				$(this).remove();
				next();
			});
		}

		$('#durationpicker a').click(function(){
			var width = $(this).width();
			var diff	= (106 - width) / 2;
			var pos	= $(this).position();
			var left	= pos.left;
			$('.bluebox').css('left', (left - diff + 18));
			$('#durationpicker a').removeClass('picked');
			$(this).addClass('picked');

			// Time text to options / input
			$('#duration').val($(this).data('time'));
			$('#durationtext').val($(this).html());
			$('.timelength').html($(this).html())
			return false;
		});

		$('#bookbutton').click(function(){
			var name = $('#name').val();
			var firstname = name.substr(0,name.indexOf(' '));
			var lastname = name.substr(name.indexOf(' ')+1);
			var atlas_id = $('#atlasid').val();
			var email = $('#email').val();
			var phone = $('#phone').val();
			var ext	= $('#pin').val();

			var title = $('#title').val();
			var message = $('#message').val();

			var location = $('#location').val();
			var duration = $('#duration').val();
			var date_time_1 = '';
			var date_time_2 = '';
			var date_time_3 = '';
			
			var tz = $('#userTimeZone').val();

			var datestr1 = $('#time_1').val();
			if(datestr1 != ''){
				datestr1	= $('#timevalue_'+datestr1).data('hours');
				date_time_1 = $('#day_1').val()+" "+ $('#year').val() + " "+datestr1+":00:00";
			}

			var datestr2 = $('#time_2').val();
			if(datestr2 != ''){
				datestr2	= $('#timevalue_'+datestr2).data('hours');
				date_time_2 = $('#day_2').val()+" "+ $('#year').val() + " "+datestr2+":00:00";
			}

			var datestr3 = $('#time_3').val();
			if(datestr3 != ''){
				datestr3	= $('#timevalue_'+datestr3).data('hours');
				date_time_3 = $('#day_3').val()+" "+ $('#year').val() + " "+datestr3+":00:00";
			}

			$.ajax({
				url: '/ajax/invite',
				type: 'post',
				data:{
					atlas_id		: atlas_id,
					firstname		: firstname,
					lastname		: lastname,
					email		: email,
					title		: title,
					message		: message,
					location		: location,
					phone_number	: phone,
					ext			: ext,
					duration		: duration,
					date_time_1	: date_time_1,
					date_time_2	: date_time_2,
					date_time_3	: date_time_3,
					tz			: tz
				},
				success: function(response){
					window.location.href = "/invited/"+response;
				}
			});
			return false;
		});
	}
});