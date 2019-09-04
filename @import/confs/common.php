<?php

	define('__IS_DEBUG__', false);

	define('__SITE__', [
		'title' => 'CanHackMe',
		'description' => 'Have fun challenges!',
		'keyword' => 'CanHackMe, Jeopardy, CTF, Wargame, Hacking, Security, Flag',
		'url' => 'https://canhack.me/',

		'facebook_app_id' => '343585006287893',
		'twitter_account' => 'plzdonotsay',
		'recaptcha_sitekey' => '6LeTPJ0UAAAAAGxR8vTw0O0rONQjk9MnO_AFtp3F',
		'recaptcha_secretkey' => file_get_contents(__DIR__.'/.recaptcha_secretkey.txt'),
		'wechall_authkey' => file_get_contents(__DIR__.'/.wechall_authkey.txt'),
	]);

	define('__AUTHOR__', [
		'name' => 'Safflower',
		'email' => 'plzdonotsay@gmail.com',
		'website' => 'https://safflower.pw/',
	]);

	define('__HASH_SALT__', file_get_contents(__DIR__.'/.hash_salt.txt'));
