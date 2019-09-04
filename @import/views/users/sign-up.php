<?php
	if(Users::is_signed()){
		Templater::redirect('/');
	}

	$args_head = [
		'title' => 'Sign up - '.__SITE__['title'],
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
							<h3 class="text-uppercase"><i class="fa fa-user-plus mr-2" aria-hidden="true"></i>Sign up</h3>
							<form id="sign-up-form" class="mt-3" action="/users/sign-up" method="post" data-recaptcha-sitekey="<?= htmlentities(__SITE__['recaptcha_sitekey']) ?>">
								<input type="hidden" name="recaptcha-token">
								<div class="form-group">
									<label for="name">Name <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="text" class="form-control bg-light" id="name" name="name" data-toggle="tooltip" data-placement="top" tabindex="1" title="Username must be unique and 5 to 20 characters long and just alphanumeric characters(a-z, A-Z, 0-9), underscore(_), hyphen(-)." aria-describedby="name-help">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary verify-name" type="button" data-target="#name" tabindex="2"
												data-toggle="tooltip" data-placement="left" title="Verify name."><i class="fa fa-check" aria-hidden="true"></i></button>
										</div>
									</div>
									<small id="name-help" class="form-text text-muted">
										Name is used for sign in, or let you know your identity.
									</small>
								</div>
								<div class="form-group">
									<label for="email">Email <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="text" class="form-control bg-light" id="email" name="email" data-toggle="tooltip" data-placement="top" tabindex="3" title="Email must be unique and valid." aria-describedby="email-help">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary verify-email" type="button" data-target="#email" tabindex="4"
												data-toggle="tooltip" data-placement="left" title="Verify email."><i class="fa fa-check" aria-hidden="true"></i></button>
										</div>
									</div>
									<small id="email-help" class="form-text text-muted">
										Email is used for reset your password and generate your profile picture in here.
									</small>
								</div>
								<div class="form-group">
									<label for="password">Password <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="password" class="form-control bg-light" id="password" name="password" data-toggle="tooltip" data-placement="top" tabindex="5" title="Password must be case sensitive and 6 to 50 characters long." aria-describedby="password-help">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary view-password" type="button" data-target="#password" tabindex="6" data-toggle="tooltip" data-placement="left" title="View password."><i class="fa fa-eye" aria-hidden="true"></i></button>
										</div>
									</div>
									<small id="password-help" class="form-text text-muted">
										Password is used for sign in, and all passwords are encrypted with long salt.
									</small>
								</div>
								<div class="form-group">
									<label for="comment">Comment</label>
									<input type="text" class="form-control bg-light" id="comment" name="comment" data-toggle="tooltip" data-placement="top" tabindex="7" title="Comment must be 0 to 50 characters long, it's not required." aria-describedby="comment-help">
									<small id="comment-help" class="form-text text-muted">
										Comment is used to express what you want to say.
									</small>
								</div>
								<div class="clearfix mb-3">
									<div class="float-left py-2">
										<a href="/users/sign-in" tabindex="9">Have an account already?</a>
									</div>
									<div class="float-right">
										<button type="submit" class="btn btn-secondary" tabindex="8" disabled>
											<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Waiting...
										</button>
									</div>
								</div>
							</form>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>