<?php
	if(isset($_GET['token']) && $_GET['token'] === Users::get_signed_token()){
		Users::do_sign_out();
	}

	$redirect_url = isset($_GET['url']) && preg_match('/\A(\/[a-zA-Z0-9_-]+)+\z/', $_GET['url']) ? $_GET['url'] : '/';
	Templater::redirect($redirect_url);