$(function(){
	$('.verify-name').click(function() {
		var name = $($(this).data('target'));
		$.ajax({
			method: 'POST',
			url: '/users/verify-name',
			data: {
				'name': name.val()
			},
			dataType: 'json',
		}).done(function(res) {
			switch (res['result']) {
				case 'valid':
					$.alert('success', '<b>Succeed!</b> This name can be used.');
					break;
				case 'invalid':
					$.alert('danger', '<b>Failed!</b> This name format is invalid.');
					name.focus();
					break;
				case 'exists':
					$.alert('danger', '<b>Failed!</b> This name already exists, please enter a different name.');
					name.focus();
					break;
				default:
					$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
			}
		}).fail(function() {
			$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
		});
		return false;
	});
	$('.verify-email').click(function() {
		var email = $($(this).data('target'));
		$.ajax({
			method: 'POST',
			url: '/users/verify-email',
			data: {
				'email': email.val()
			},
			dataType: 'json',
		}).done(function(res) {
			switch (res['result']) {
				case 'valid':
					$.alert('success', '<b>Succeed!</b> This email can be used.');
					break;
				case 'invalid':
					$.alert('danger', '<b>Failed!</b> This email format is invalid.');
					email.focus();
					break;
				case 'exists':
					$.alert('danger', '<b>Failed!</b> This email already exists, please enter a different email.');
					email.focus();
					break;
				default:
					$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
			}
		}).fail(function() {
			$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
		});
		return false;
	});
	$('#sign-in-form').submit(function(){
		var form = $(this);
		$.ajax({
			method: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize(),
			dataType: 'json',
		}).done(function(res) {
			switch (res['result']) {
				case 'valid':
				case 'already_signed':
					location.href = '/';
					break;
				case 'invalid_account':
					$.alert('danger', '<b>Failed!</b> You have entered an invalid name or password, please make sure you have entered it correctly.');
					$('#password').focus();
					break;
				case 'invalid_token':
					$.alert("danger", "<b>Error!</b> This captcha token is invalid, please try again.");
					break;
				default:
					$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
			}
		}).fail(function() {
			$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
		}).always(function() {
			form.trigger('refresh-recaptcha');
		});
		return false;
	});
	$('#sign-up-form').submit(function(){
		var form = $(this);
		$.ajax({
			method: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize(),
			dataType: 'json',
		}).done(function(res) {
			switch (res['result']) {
				case 'valid':
					location.href = '/users/sign-in';
					break;
				case 'already_signed':
					location.href = '/';
					break;
				case 'invalid_name':
					$.alert("danger", "<b>Failed!</b> This name format is invalid.");
					form.find('#name').focus();
					break;
				case 'already_exists_name':
					$.alert("danger", "<b>Failed!</b> This name already exists, please enter a different name.");
					form.find('#name').focus();
					break;
				case 'invalid_email':
					$.alert("danger", "<b>Failed!</b> This name format is invalid.");
					form.find('#email').focus();
					break;
				case 'already_exists_email':
					$.alert("danger", "<b>Failed!</b> This email already exists, please enter a different email.");
					form.find('#email').focus();
					break;
				case 'invalid_password':
					$.alert("danger", "<b>Failed!</b> This password format is invalid.");
					form.find('#password').focus();
					break;
				case 'invalid_comment':
					$.alert("danger", "<b>Failed!</b> This comment format is invalid.");
					form.find('#comment').focus();
					break;
				case 'invalid_token':
					$.alert("danger", "<b>Error!</b> This captcha token is invalid, please try again.");
					break;
				default:
					$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
			}
		}).fail(function() {
			$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
		}).always(function() {
			form.trigger('refresh-recaptcha');
		});
		return false;
	});
	$('#settings-form').submit(function(){
		var form = $(this);
		$.ajax({
			method: form.attr('method'),
			url: form.attr('action'),
			data: form.serialize(),
			dataType: 'json',
		}).done(function(res) {
			switch (res['result']) {
				case 'valid':
					location.href = res['redirect'];
					break;
				case 'unsigned':
					location.href = '/users/sign-in';
					break;
				case 'invalid_name':
					$.alert("danger", "<b>Failed!</b> This name format is invalid.");
					form.find('#name').focus();
					break;
				case 'already_exists_name':
					$.alert("danger", "<b>Failed!</b> This name already exists, please enter a different name.");
					form.find('#name').focus();
					break;
				case 'invalid_email':
					$.alert("danger", "<b>Failed!</b> This name format is invalid.");
					form.find('#email').focus();
					break;
				case 'already_exists_email':
					$.alert("danger", "<b>Failed!</b> This email already exists, please enter a different email.");
					form.find('#email').focus();
					break;
				case 'invalid_password':
					$.alert("danger", "<b>Failed!</b> This password format is invalid.");
					form.find('#password').focus();
					break;
				case 'invalid_comment':
					$.alert("danger", "<b>Failed!</b> This comment format is invalid.");
					form.find('#comment').focus();
					break;
				case 'invalid_token':
					$.alert("danger", "<b>Error!</b> This captcha token is invalid, please try again.");
					break;
				default:
					$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
			}
		}).fail(function() {
			$.alert('danger', '<b>Error!</b> An unexpected error occurred, please try again.');
		}).always(function() {
			form.trigger('refresh-recaptcha');
		});
		return false;
	});
});