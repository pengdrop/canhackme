<?php

	define('__IS_DEBUG__', false);

	define('__SITE__', [
		'title' => 'CanHackMe',
		'description' => 'Have fun challenges!',
		'keyword' => 'CanHackMe, Jeopardy, CTF, Wargame, Hacking, Security, Flag',
		'url' => 'https://canhack.me/',
		'facebook_app_id' => file_get_contents(__DIR__.'/.facebook_app_id.txt'),
		'twitter_account' => file_get_contents(__DIR__.'/.twitter_account.txt'),

		'use_recaptcha' => false,
		'recaptcha_sitekey' => file_get_contents(__DIR__.'/.recaptcha_sitekey.txt'),
		'recaptcha_secretkey' => file_get_contents(__DIR__.'/.recaptcha_secretkey.txt'),

		'wechall_authkey' => file_get_contents(__DIR__.'/.wechall_authkey.txt'),
	]);

	define('__AUTHOR__', [
		'name' => 'Safflower',
		'email' => 'plzdonotsay@gmail.com',
		'website' => 'https://safflower.pw/',
	]);

	define('__ADMIN__', [
		'admin',
		'safflower',
	]);

	define('__HASH_SALT__', file_get_contents(__DIR__.'/.hash_salt.txt'));
