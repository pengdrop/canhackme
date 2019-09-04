<?php
	# error
	isset($_SERVER['REDIRECT_STATUS']) or $_SERVER['REDIRECT_STATUS'] = 404;
	switch($_SERVER['REDIRECT_STATUS']){
	case 403:
		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
		$res = ['result' => 'access_denied'];
		break;
	case 404:
	default:
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		$res = ['result' => 'page_not_found'];
		break;
	}

	Templater::json($res);
