$(function(){
	$('[data-toggle="tooltip"]').tooltip();

	$.show_alert = function(type, contents){
		let alert_area = $('#alert-area'),
				alert = alert_area.find('.alert'),
				html = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' + 
				'<span>' + contents+ '</span>' + 
				'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">⨯</span></button>' +
				'</div>';
		if(alert.length == 3){
			alert[0].remove();
		}
		if(alert_area.find('.alert').length == 0){
			alert_area.prepend(html);
		}else{
			alert_area.find('.alert:last').after(html);
		}
	};
	$('.view-password').click(function(){
		let password = $($(this).toggleClass('active').data('target'));
		if(password.attr('type') == 'text'){
			password.attr('type', 'password');
		}else{
			password.attr('type', 'text');
		}
	});

	$('.go-back').click(function() {
		let href = $(this).data('href'),
			link = location.protocol+'//'+location.host + href;
		if(document.referrer.indexOf(link) == 0){
			history.back();
		}else{
			location.href=href;
		}
	});

	function localize_time(timestamp){
		let t = new Date(timestamp * 1000);
		if(t.toString() === 'Invalid Date') return;
		let year = t.getFullYear();
		let month = t.getMonth() + 1;
			month = month < 10 ? '0' + month : month;
		let date = t.getDate();
			date = date < 10 ? '0' + date : date;
		let hour = t.getHours();
			hour = hour < 10 ? '0' + hour : hour;
		let min = t.getMinutes();
			min = min < 10 ? '0' + min : min;
		let sec = t.getSeconds();
			sec = sec < 10 ? '0' + sec : sec;
		return year + '-' + month + '-' + date + ' ' + hour + ':' + min + ':' + sec;
	}
	$('time').each(function(){
		let timestamp = $(this).data('timestamp');
		if(timestamp != undefined){
			let time = localize_time(timestamp);
			if(time != undefined){
				$(this).html(time);
			}
		}
	});

	$('.verify-name').click(function() {
		$.ajax({
			method: 'POST',
			url: '/users/verify/name',
			data: {'name': $($(this).data('target')).val()},
			dataType: 'json',
			success: function(res) {
				switch (res.result) {
					case 'valid':
						$.show_alert('success', '<b>Succeed!</b> The name can be used.');
						break;
					case 'invalid':
						$.show_alert('danger', '<b>Failed!</b> The name format is invalid.');
						name.focus();
						break;
					case 'exists':
						$.show_alert('danger', '<b>Failed!</b> The name already exists.');
						name.focus();
						break;
					default:
						$.show_alert('danger', '<b>Error!</b> Try again.')
				}
			},
			error: function() {
				$.show_alert('danger', '<b>Error!</b> Try again.')
			}
		});
		return false
	});
	$('.verify-email').click(function() {
		$.ajax({
			method: 'POST',
			url: '/users/verify/email',
			data: {'email': $($(this).data('target')).val()},
			dataType: 'json',
			success: function(res) {
				switch (res.result) {
					case 'valid':
						$.show_alert('success', '<b>Succeed!</b> The email can be used.');
						break;
					case 'invalid':
						$.show_alert('danger', '<b>Failed!</b> The email format is invalid.');
						email.focus();
						break;
					case 'exists':
						$.show_alert('danger', '<b>Failed!</b> The email already exists.');
						email.focus();
						break;
					default:
						$.show_alert('danger', '<b>Error!</b> Try again.')
				}
			},
			error: function() {
				$.show_alert('danger', '<b>Error!</b> Try again.')
			}
		});
		return false
	});

});