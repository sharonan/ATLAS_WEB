$(document).ready(function(){
	$('.select_option').click(function(){
		var id = $(this).attr('id');
		$.ajax({
			url: "/ajax/confirm_single",
			type: 'post',
			data:{
				web_item_user_id: id
			},
			success:function(response){
				window.location.href = window.location.href;
			}
		});
		return false;
	});
	
	$('a.decline').click(function(){
		var id = $(this).attr('id');
		$.ajax({
			url: "/ajax/decline_single",
			type: 'post',
			data:{
				web_item_user_id: id
			},
			success:function(response){
				window.location.href = window.location.href;
			}
		});
		return false;
	});
	
	$('.selectors div').click(function(){
		$(this).parent().find('div').removeClass('selected');
		$(this).addClass('selected');
		$(this).parent().parent().find('input').val($(this).attr('rel'));
	});
	
	var opts = {};
	$('a.vote').click(function(){
		var inputs = $('.multi_option');
		var opts = $.map(inputs, function(x, y) {
			return {
				key: $(x).attr('id'),
				value: $(x).find('input').val()
			};
		});
		for (var key in opts) {
			var obj = opts[key];
			for (var prop in obj) {
				if((prop == 'value') && (obj[prop] == '3')) {
					alert("You must pick a response for every time slot.")
					return false;
				}
			}
		}
		$.ajax({
			url: "/ajax/vote_multi",
			type: 'post',
			data:{
				opts: opts
			},
			success:function(response){
				window.location.href = window.location.href;
			}
		});
		return false;
		
	});
	
	$('#icalDL').click(function(){
		$('#icalDLform').submit();
		return false;
	});
	$('#outlookDL').click(function(){
		$('#icalDLform').submit();
		return false;
	});
	
	$.ajax({
		url: "/site/downloadgoogle",
		type: 'post',
		data:{
			title: $('#titles').val(),
			location: $('#locations').val(),
			start: $('#starts').val(),
			duration: $('#durations').val(),
			desc: $('#descs').val(),
			webitem: $('#webitems').val()
		},
		success:function(response){
			$('#googleDL').attr('href', response);
		}
	});
});