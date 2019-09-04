$(function(){
	$('[data-toggle="tooltip"]').tooltip();

	$('[data-scroll-top]').click(function() {
		// scroll to top
		$('html,body').animate({
			scrollTop: 0,
		}, 300);
		return false;
	});
	$('[data-scroll-bottom]').click(function() {
		// scroll to bottom
		$('html,body').animate({
			scrollTop: $(document).height(),
		}, 300);
		return false;
	});

	// refresh recaptcha token in forms
	$('form[data-recaptcha-sitekey]').each(function(index){
		var form = $(this);
		form.on('refresh-recaptcha', function(){
			var sitekey = form.data('recaptcha-sitekey');

			var submit = form.find('[type=submit]');
			if(!submit.attr('disabled')){
				submit.attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Waiting...');
			}
			grecaptcha.ready(function(){
				grecaptcha.execute(sitekey, { action: 'homepage' }).then(function(token){
					form.find('input[name=recaptcha-token]').val(token);
					submit.attr('disabled', false).html('<i class="fa fa-check" aria-hidden="true"></i> Submit');
				});
			});
			return;
		});
		form.trigger('refresh-recaptcha');
		return;
	});

	$.alert = function(level, message){
		var alert_area = $('#alert-area'),
			alert = alert_area.find('.alert'),
			alert_class, icon_class;
		// select html tag class
		switch(level){
			case 'success':
				alert_class = 'alert-success';
				icon_class = 'fa-check';
				break;
			case 'warning':
				alert_class = 'alert-warning';
				icon_class = 'fa-exclamation-circle';
				break;
			case 'info':
				alert_class = 'alert-info';
				icon_class = 'fa-info-circle';
				break;
			case 'danger':
			default:
				alert_class = 'alert-danger';
				icon_class = 'fa-exclamation-triangle';
				break;
		}
		// generate html tag
		var html = 	
		'<div class="alert ' + alert_class + ' alert-dismissible fade show" role="alert">' + 
			'<i class="fa ' + icon_class + '"></i><span class="ml-1">' + message + '</span>' + 
			'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
				'<span aria-hidden="true">&times;</span>' + 
			'</button>' +
		'</div>';
		// pop alert (fifo)
		if(alert.length == 3){
			alert[0].remove();
		}
		// push alert (fifo)
		if(alert_area.find('.alert').length === 0){
			alert_area.prepend(html);
		}else{
			alert_area.find('.alert:last').after(html);
		}
		// scroll to top
		$('html,body').animate({
			scrollTop: 0,
		}, 300);
	};

	$('.view-password').click(function(){
		// convert input type
		var input = $($(this).toggleClass('active').data('target'));
		input.attr('type', input.attr('type') === 'text' ? 'password' : 'text');
	});

	$('.go-back').click(function() {
		var href = $(this).data('href');
		if(document.referrer.indexOf(location.protocol + '//' + location.host + href) == 0){
			history.back();
		}else{
			location.href = href;
		}
	});

	var localize_time = function(timestamp){
		var t = new Date(timestamp * 1000);
		if(t.toString() === 'Invalid Date') return;

		var zerofill = function(num, zero_count){
			return num < (10 ** (zero_count - 1)) ? '0' + num.toString() : num.toString();
		}
		var y = zerofill(t.getFullYear(), 4);
		var m = zerofill(t.getMonth() + 1, 2);
		var d = zerofill(t.getDate(), 2);
		var h = zerofill(t.getHours(), 2);
		var i = zerofill(t.getMinutes(), 2);
		var s = zerofill(t.getSeconds(), 2);
		return y + '-' + m + '-' + d + ' ' + h + ':' + i + ':' + s;
	}
	$('[data-timestamp]').each(function(){
		var timestamp = $(this).data('timestamp');
		var time = localize_time(timestamp);
		if(time != undefined){
			$(this).html(time);
		}
	});

});