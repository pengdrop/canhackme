<?php
	# init
	require __DIR__.'/@import/init.php';

	$tpl = new Templater();

	if($tpl->route('/\A\/\z/', ['GET'])){
		$tpl->import('views/home/home');


	}else if($tpl->route('/\A\/notifications\z/i', ['GET'])){
		$tpl->import('views/notifications/notifications');

	}else if($tpl->route('/\A\/notifications\/page\/(?<page>[0-9]{1,10})\z/i', ['GET'], $args)){
		$tpl->import('views/notifications/notifications', $args);


	}else if($tpl->route('/\A\/challenges\z/i', ['GET'])){
		$tpl->import('views/challenges/challenges');

	}else if($tpl->route('/\A\/challenges\/\@(?<chal_name>[a-zA-Z0-9_-]{1,10})\z/i', ['GET'], $args)){
		$tpl->import('views/challenges/challenges', $args);

	}else if($tpl->route('/\A\/challenges\/name\/(?<chal_name>[a-zA-Z0-9_-]{1,10})\z/i', ['GET'], $args)){
		$tpl->redirect('/challenges/@'.$args['chal_name']);

	}else if($tpl->route('/\A\/challenges\/tag\/(?<chal_tag>[a-zA-Z0-9_-]{1,10})\z/i', ['GET'], $args)){
		$tpl->import('views/challenges/challenges', $args);
	
	}else if($tpl->route('/\A\/challenges\/authentication\z/i', ['POST'])){
		$tpl->import('apis/challenges/authentication');


	}else if($tpl->route('/\A\/scoreboard\z/i', ['GET'])){
		$tpl->import('views/scoreboard/scoreboard');

	}else if($tpl->route('/\A\/scoreboard\/page\/(?<page>[0-9]{1,10})\z/i', ['GET'], $args)){
		$tpl->import('views/scoreboard/scoreboard', $args);


	}else if($tpl->route('/\A\/solves\z/i', ['GET'])){
		$tpl->import('views/solves/solves');

	}else if($tpl->route('/\A\/solves\/page\/(?<page>[0-9]{1,10})\z/i', ['GET'], $args)){
		$tpl->import('views/solves/solves', $args);


	}else if($tpl->route('/\A\/users\z/i', ['GET'])){
		$tpl->import('views/users/users');

	}else if($tpl->route('/\A\/users\/page\/(?<page>[0-9]{1,10})\z/i', ['GET'], $args)){
		$tpl->import('views/users/users', $args);


	}else if($tpl->route('/\A\/users\/sign-in\z/i', ['GET'])){
		$tpl->import('views/users/sign-in');

	}else if($tpl->route('/\A\/users\/sign-in\z/i', ['POST'])){
		$tpl->import('apis/users/sign-in');


	}else if($tpl->route('/\A\/users\/sign-up\z/i', ['GET'])){
		$tpl->import('views/users/sign-up');

	}else if($tpl->route('/\A\/users\/sign-up\z/i', ['POST'])){
		$tpl->import('apis/users/sign-up');


	}else if($tpl->route('/\A\/users\/verify-name\z/i', ['POST'])){
		$tpl->import('apis/users/verify-name');

	}else if($tpl->route('/\A\/users\/verify-email\z/i', ['POST'])){
		$tpl->import('apis/users/verify-email');


	}else if($tpl->route('/\A\/users\/sign-out\z/i', ['GET'])){
		$tpl->import('apis/users/sign-out');


	}else if($tpl->route('/\A\/users\/\@(?<user_name>[a-zA-Z0-9_-]{5,20})\z/i', ['GET'], $args)){
		$tpl->import('views/users/profile', $args);

	}else if($tpl->route('/\A\/users\/profile\/(?<user_name>[a-zA-Z0-9_-]{5,20})\z/i', ['GET'], $args)){
		$tpl->redirect('/users/@'.$args['user_name']);


	}else if($tpl->route('/\A\/users\/settings\z/i', ['GET'], $args)){
		$tpl->import('views/users/settings', $args);

	}else if($tpl->route('/\A\/users\/settings\z/i', ['POST'], $args)){
		$tpl->import('apis/users/settings', $args);


	}else if($tpl->route('/\A\/wechall\/user-score\z/i', ['GET'])){
		$tpl->import('apis/wechall/user-score');

	}else if($tpl->route('/\A\/wechall\/validate-mail\z/i', ['GET'])){
		$tpl->import('apis/wechall/validate-mail');


	}else{
		$tpl->error(404);

	}