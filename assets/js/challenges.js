$(function(){
	$('#auth-flag-form').submit(function(){
		$.ajax({
			method: 'POST',
			url: '/challenges/auth',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(res) {
				switch (res.result) {
					case 'solved':
						$.show_alert('success', '<b>Congratulations!</b> You solved the <a class="alert-link" href="/challenges/name/'+res['chal_name']+'">'+res['chal_title']+'</a> challenge, and you got a '+res['chal_score']+'pt.');
						$('#flag').val('');
						var chal_head = $('#chal-head-'+res['chal_name']);
						chal_head.find('a.text-dark').removeClass('text-dark').addClass('text-success').find('i.fa-lock').removeClass('fa-lock').addClass('fa-unlock-alt');
						chal_head.find('span.text-dark').removeClass('text-dark').addClass('text-success');
						chal_head.find('span.badge-secondary').removeClass('badge-secondary').addClass('badge-success');
						var chal_body = $('#chal-body-'+res['chal_name']);
						var chal_solvers = chal_body.find('span.solvers');
						chal_solvers.html(Number(chal_solvers.html()) + 1);
						break;
					case 'already_solved':
						$.show_alert('info', '<b>Correct!</b> You already solved the <a class="alert-link" href="/challenges/name/'+res['chal_name']+'">'+res['chal_title']+'</a> challenge.');
						$('#flag').val('');
						break;
					case 'incorrect':
						$.show_alert('danger', '<b>Failed!</b> The flag is incorrect.');
						$('#flag').focus();
						break;
					default:
						$.show_alert('danger', '<b>Error!</b> Try again.');
				}
			},
			error: function() {
				$.show_alert('danger', '<b>Error!</b> Try again.');
			}
		});
		return false
	})
});