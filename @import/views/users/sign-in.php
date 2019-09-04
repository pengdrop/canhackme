<?php
	if(Users::is_signed()){
		Templater::redirect('/');
	}

	$args_head = [
		'title' => 'Sign in - '.__SITE__['title'],
		'active' => 'users',
		'scripts' => [
			'/assets/scripts/users.js',
			'https://www.google.com/recaptcha/api.js?render='.urlencode(__SITE__['recaptcha_sitekey']),
		],
	];
	$args_foot = [
		'active' => 'users',
	];

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-sign-in mr-2" aria-hidden="true"></i>Sign in</h3>
							<form id="sign-in-form" class="mt-3" action="/users/sign-in" method="post" data-recaptcha-sitekey="<?= htmlentities(__SITE__['recaptcha_sitekey']) ?>">
								<input type="hidden" name="recaptcha-token">
								<div class="form-group">
									<label for="name">Name <span class="text-danger">*</span></label>
									<input type="text" class="form-control bg-light" id="name" name="name" 
										data-toggle="tooltip" data-placement="top" title="Enter your name." tabindex="1">
								</div>
								<div class="form-group clearfix">
									<label for="password">Password <span class="text-danger">*</span></label>
									<small class="form-text float-right text-muted">
										<a href="mailto:<?= email_encode(__AUTHOR__['email']) ?>" tabindex="3">Forgot password?</a>
									</small>
									<input type="password" class="form-control bg-light" id="password" name="password" tabindex="2"
										data-toggle="tooltip" data-placement="top" title="Enter your password.">
								</div>
								<div class="clearfix mb-3">
									<div class="float-left py-2">
										<a href="/users/sign-up" tabindex="6">Don't have an account yet?</a>
									</div>
									<div class="float-right">
										<button type="submit" class="btn btn-secondary" tabindex="5" disabled>
											<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Waiting...
										</button>
									</div>
								</div>
							</form>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>