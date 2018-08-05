<?php
	if(Users::is_signed()){
		Templater::redirect('/');
	}

	$args_head = [
		'title' => 'Sign in - '.__SITE__['title'],
		'active' => 'sign-in',
	];
	$args_foot = [
		'active' => 'sign-in',
	];

	# sign in
	if(isset($_POST['sign-in'], $_POST['name'], $_POST['password'])){
		if(Users::is_valid_user_name($_POST['name']) && Users::is_valid_user_password($_POST['password']) && Users::do_sign_in($_POST['name'], $_POST['password'])){
			$url_path = Templater::get_url_path();
			Templater::redirect(strcasecmp($url_path, '/users/sign-in') ? $url_path : '/');
		}else{
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The name or password is incorrect.");'
				.'$("#password").focus();';
		}
	}
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<h2>Sign in</h2>
							<form id="sign-in-form" class="mt-3" method="post">
								<input type="hidden" name="sign-in">
								<div class="form-group">
									<label for="name">Name <span class="text-danger">*</span></label>
									<input type="text" class="form-control bg-light" id="name" name="name" 
										data-toggle="tooltip" data-placement="top" title="Input your name." tabindex="1"
										value="<?php if(isset($_POST['sign-in'], $_POST['name']) && is_string($_POST['name'])) Data::text($_POST['name']); ?>">
								</div>
								<div class="form-group clearfix">
									<label for="password">Password <span class="text-danger">*</span></label>
									<small class="form-text float-right text-muted">
										<a href="mailto:<?php echo Data::email(__AUTHOR__['email']); ?>" tabindex="4">Forgot password?</a>
									</small>
									<input type="password" class="form-control bg-light" id="password" name="password" tabindex="2"
										data-toggle="tooltip" data-placement="top" title="Input your password.">
								</div>
								<div class="clearfix mb-3">
									<div class="float-left py-1">
										<a href="/users/sign-up" tabindex="5">Don't have an account yet?</a>
									</div>
									<div class="float-right">
										<button type="submit" class="btn btn-secondary" tabindex="3">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>