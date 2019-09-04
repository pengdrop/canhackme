$(function(){

	$('#auth-flag-form').submit(function(){
		var form = $(this);
		$.ajax({
			method: 'POST',
			url: form.attr('action'),
			data: form.serialize(),
			dataType: 'json',
		}).done(function(res) {
			switch (res['result']) {
				case 'solved':
					$.alert('success', '<b>Congratulations!</b> You solved the <a class="alert-link" href="/challenges/@'+res['chal_name']+'">'+res['chal_title']+'</a> challenge, and you got a '+res['chal_score']+'pt.');
					$('#flag').val('');
					var chal_head = $('#chal-head-'+res['chal_name']);
					chal_head.find('a.text-dark')
						.removeClass('text-dark')
						.addClass('text-success')
						.find('i.fa-lock')
						.removeClass('fa-lock')
						.addClass('fa-unlock-alt');
					chal_head.find('span.text-dark')
						.removeClass('text-dark')
						.addClass('text-success');
					chal_head.find('span.badge-secondary')
						.removeClass('badge-secondary')
						.addClass('badge-success');
					var chal_solvers = $('#chal-body-'+res['chal_name']).find('span.solvers');
					chal_solvers.html(Number(chal_solvers.html()) + 1);
					break;
				case 'already_solved':
					$.alert('info', '<b>Correct!</b> You already solved the <a class="alert-link" href="/challenges/@'+res['chal_name']+'">'+res['chal_title']+'</a> challenge.');
					$('#flag').val('');
					break;
				case 'invalid_flag':
					$.alert('danger', '<b>Incorrect!</b> You have entered an invalid flag, please make sure you have entered it correctly.');
					$('#flag').focus();
					break;
				case 'unsigned':
					$.alert('warning', '<b>Failed!</b> You have not signed this website, please sign in through <a href="/users/sign-in">here</a>.');
					break;
				case 'invalid_token':
					$.alert("danger", "<b>Error!</b> The captcha token is invalid, please try again.");
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