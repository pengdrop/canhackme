<?php

	if(!isset($_POST['recaptcha-token'], $_POST['name'], $_POST['email'], $_POST['password'], $_POST['comment'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	if(!is_valid_recaptcha_token($_POST['recaptcha-token'])){
		$res = ['result' => 'invalid_token'];
		goto tail;
	}

	if(!Users::is_signed()){
		$res = ['result' => 'not_signed'];
		goto tail;
	}

	if(!Users::is_valid_user_name($_POST['name'])){
		$res = ['result' => 'invalid_name'];
		goto tail;
	}

	if(Users::is_exists_user_name($_POST['name'])){
		$res = ['result' => 'already_exists_name'];
		goto tail;
	}

	if(!Users::is_valid_user_email($_POST['email'])){
		$res = ['result' => 'invalid_email'];
		goto tail;
	}

	if(Users::is_exists_user_email($_POST['email'])){
		$res = ['result' => 'already_exists_email'];
		goto tail;
	}

	if(!Users::is_valid_user_password($_POST['password'])){
		$res = ['result' => 'invalid_password'];
		goto tail;
	}

	if(!Users::is_valid_user_comment($_POST['comment'])){
		$res = ['result' => 'invalid_comment'];
		goto tail;
	}

	if(!Users::update_my_user($_POST['name'], $_POST['email'], $_POST['password'], $_POST['comment'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	$res = ['result' => 'valid', 'redirect' => get_user_profile_page_url($_POST['name'])];

	tail:
	Templater::json($res);
