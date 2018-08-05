<?php
	# init
	require __DIR__.'/@import/init.php';

	$tpl = new Templater(__DIR__.'/@import');

	if($tpl->route('/\A\/\z/', ['GET'])){
		$tpl->render('home/home');


	}else if($tpl->route('/\A\/notices\z/i', ['GET'])){
		$tpl->render('notices/notices');


	}else if($tpl->route('/\A\/activities\z/i', ['GET'])){
		$tpl->render('activities/activities');


	}else if($tpl->route('/\A\/challenges\z/i', ['GET', 'POST'])){
		$tpl->render('challenges/challenges');

	}else if($tpl->route('/\A\/challenges\/name\/(?<chal_name>[a-zA-Z0-9_-]{1,10})\z/i', ['GET', 'POST'], $args)){
		$tpl->render('challenges/challenges', $args);

	}else if($tpl->route('/\A\/challenges\/tag\/(?<chal_tag>[a-zA-Z0-9_-]{1,10})\z/i', ['GET', 'POST'], $args)){
		$tpl->render('challenges/challenges', $args);
	
	}else if($tpl->route('/\A\/challenges\/auth\z/i', ['POST'])){
		$tpl->render('challenges/auth');


	}else if($tpl->route('/\A\/scoreboard\z/i', ['GET'])){
		$tpl->render('scoreboard/scoreboard');


	}else if($tpl->route('/\A\/users\/sign-in\z/i', ['GET', 'POST'])){
		$tpl->render('users/sign-in');

	}else if($tpl->route('/\A\/users\/sign-up\z/i', ['GET', 'POST'])){
		$tpl->render('users/sign-up');

	}else if($tpl->route('/\A\/users\/verify\/name\z/i', ['POST'])){
		$tpl->render('users/verify-name');

	}else if($tpl->route('/\A\/users\/verify\/email\z/i', ['POST'])){
		$tpl->render('users/verify-email');

	}else if($tpl->route('/\A\/users\/sign-out\z/i', ['GET'])){
		$tpl->render('users/sign-out');

	}else if($tpl->route('/\A\/users\/profile\/(?<user_name>[a-zA-Z0-9_-]{5,20})\z/i', ['GET'], $args)){
		$tpl->render('users/profile', $args);

	}else if($tpl->route('/\A\/users\/settings\z/i', ['GET', 'POST'], $args)){
		$tpl->render('users/settings', $args);


	}else if($tpl->route('/\A\/wechall\/user-score\z/i', ['GET'])){
		$tpl->render('wechall/user-score');

	}else if($tpl->route('/\A\/wechall\/validate-mail\z/i', ['GET'])){
		$tpl->render('wechall/validate-mail');


	}else{
		$tpl->error();

	}