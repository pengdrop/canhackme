<?php
	if(!isset($_POST['name'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	if(!Users::is_valid_user_name($_POST['name'])){
		$res = ['result' => 'invalid'];
		goto tail;
	}

	if(Users::is_exists_user_name($_POST['name'])){
		$res = ['result' => 'exists'];
		goto tail;
	}

	$res = ['result' => 'valid'];

	tail:
	Templater::json($res);
