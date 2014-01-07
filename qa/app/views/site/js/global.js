$(document).ready(function(){
	$('form.schedule fieldset').click(function(){
		if($('#call').val() == 1){
			$('#call').val(0);
			$('#meet').val(1);
			$(this).find('img').addClass('meet');
		} else {
			$('#call').val(1);
			$('#meet').val(0);
			$(this).find('img').removeClass();
		}
		$('#phone, #pin, #location').toggleClass('meet');
	});
	$('img').on('dragstart', function(event) { event.preventDefault(); });
});