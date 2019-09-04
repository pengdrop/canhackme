<?php
	if(!isset($_POST['email'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	if(!Users::is_valid_user_email($_POST['email'])){
		$res = ['result' => 'invalid'];
		goto tail;
	}

	if(Users::is_exists_user_email($_POST['email'])){
		$res = ['result' => 'exists'];
		goto tail;
	}

	$res = ['result' => 'valid'];

	tail:
	Templater::json($res);
