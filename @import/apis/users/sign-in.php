<?php

	if(!isset($_POST['recaptcha-token'], $_POST['name'], $_POST['password'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	if(!is_valid_recaptcha_token($_POST['recaptcha-token'])){
		$res = ['result' => 'invalid_token'];
		goto tail;
	}

	if(Users::is_signed()){
		$res = ['result' => 'already_signed'];
		goto tail;
	}

	if(!Users::is_valid_user_name($_POST['name']) || 
		!Users::is_valid_user_password($_POST['password']) || 
		!Users::do_sign_in($_POST['name'], $_POST['password'])){
		$res = ['result' => 'invalid_account'];
		goto tail;
	}

	$res = ['result' => 'valid'];

	tail:
	Templater::json($res);
