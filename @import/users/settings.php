<?php
	if(!Users::is_signed()){
		Templater::render('users/sign-in');
		die;
	}
	$args_head = [
		'title' => 'Settings - '.__SITE__['title'],
		'active' => 'settings',
	];
	$args_foot = [
		'active' => 'settings',
	];

	# update profile
	if(isset($_POST['update-profile'], $_POST['name'], $_POST['email'], $_POST['password'], $_POST['comment'])){

		if(!Users::is_valid_user_name($_POST['name'])){
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The name format is invalid.");'
				.'$("#name").focus();';

		}else if(Users::is_exists_user_name($_POST['name'])){
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The name already exists.");'
				.'$("#name").focus();';

		}else if(!Users::is_valid_user_email($_POST['email'])){
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The email format is invalid.");'
				.'$("#email").focus();';

		}else if(Users::is_exists_user_email($_POST['email'])){
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The email already exists.");'
				.'$("#email").focus();';

		}else if(!Users::is_valid_user_password($_POST['password'])){
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The password format is invalid.");'
				.'$("#password").focus();';

		}else if(!Users::is_valid_user_comment($_POST['comment'])){
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The comment format is invalid.");'
				.'$("#comment").focus();';

		}else if(Users::update_my_user($_POST['name'], $_POST['email'], $_POST['password'], $_POST['comment'])){
			Templater::redirect('/users/profile/'.urlencode(strtolower($_POST['name'])));

		}else{
			$args_foot['script'] = '$.show_alert("danger", "<b>Error!</b> Try again.");';
		}
	}

	$user = Users::get_my_user();

?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<h2>Settings</h2>
							<form id="update-profile-form" class="mt-3" method="post">
								<input type="hidden" name="update-profile">
								<div class="form-group">
									<label for="name">Name <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="text" class="form-control bg-light" id="name" name="name"
											data-toggle="tooltip" data-placement="top" 
											title="Name must be unique and 5 to 20 characters long and just alphanumeric characters(a-z, A-Z, 0-9), underscore(_), hyphen(-)." 
											aria-describedby="name-help"
											value="<?php if(isset($_POST['update-profile'], $_POST['name']) && is_string($_POST['name'])) Data::text($_POST['name']); else Data::text($user['user_name']); ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary verify-name" type="button" data-target="#name"
												data-toggle="tooltip" data-placement="left" title="Verify name."><i class="fa fa-check" aria-hidden="true"></i></button>
										</div>
									</div>
									<small id="name-help" class="form-text text-muted">
										Name is used for sign in or show your information in here.
									</small>
								</div>
								<div class="form-group">
									<label for="email">Email <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="text" class="form-control bg-light" id="email" name="email" 
											data-toggle="tooltip" data-placement="top" 
											title="Email must be unique and valid." 
											aria-describedby="email-help"
											value="<?php if(isset($_POST['update-profile'], $_POST['email']) && is_string($_POST['email'])) Data::text($_POST['email']); else Data::text($user['user_email']); ?>">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary verify-email" type="button" data-target="#email" 
												data-toggle="tooltip" data-placement="left" title="Verify email."><i class="fa fa-check" aria-hidden="true"></i></button>
										</div>
									</div>
									<small id="email-help" class="form-text text-muted">
										Email is used for reset your password or send any notice.
									</small>
								</div>
								<div class="form-group">
									<label for="password">New password <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="password" class="form-control bg-light" id="password" name="password" 
											data-toggle="tooltip" data-placement="top" 
											title="Password must be case sensitive and 6 to 50 characters long." 
											aria-describedby="password-help">
										<div class="input-group-append">
											<button class="btn btn-outline-secondary view-password" type="button" data-target="#password" 
												data-toggle="tooltip" data-placement="left" title="View password."><i class="fa fa-eye" aria-hidden="true"></i></button>
										</div>
									</div>
									<small id="password-help" class="form-text text-muted">
										Password is used for sign in, and all passwords are encrypted with long salt.
									</small>
								</div>
								<div class="form-group">
									<label for="comment">Comment</label>
									<input type="text" class="form-control bg-light" id="comment" name="comment" 
										data-toggle="tooltip" data-placement="top" 
										title="Comment must be 0 to 50 characters long, it's not required." 
										aria-describedby="comment-help"
										value="<?php if(isset($_POST['update-profile'], $_POST['comment']) && is_string($_POST['comment'])) Data::text($_POST['comment']); else Data::text($user['user_comment']); ?>">
									<small id="comment-help" class="form-text text-muted">
										Comment is used for show your information in here.
									</small>
								</div>
								<div class="clearfix mb-3">
									<div class="float-left py-1">
										<a href="/users/profile/<?php Data::url(strtolower($user['user_name'])); ?>">Want to see your profile?</a>
									</div>
									<div class="float-right">
										<button type="submit" class="btn btn-secondary">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>