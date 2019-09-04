<?php
	if(!isset($_GET['authkey']{0}) || $_GET['authkey'] !== __SITE__['wechall_authkey']){
		Templater::error(403);
	}

	if(!isset($_GET['name'], $_GET['email'])){
		die('0');
	}

	if(!Users::is_valid_user_name($_GET['name'])){
		die('0');
	}

	if(!Users::is_valid_user_email($_GET['email'])){
		die('0');
	}

	if(($user_email = Users::get_user_by_name($_GET['name'], 'user_email', true)) === false){
		die('0');
	}

	if(strcasecmp($user_email, $_GET['email']) !== 0){
		die('0');
	}

	die('1');