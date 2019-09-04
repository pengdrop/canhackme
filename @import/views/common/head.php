<!--
	 _______  _______  _______  _______  ___      _______  _     _  _______  ______   
	|       ||   _   ||       ||       ||   |    |       || | _ | ||       ||    _ |  
	|  _____||  |_|  ||    ___||    ___||   |    |   _   || || || ||    ___||   | ||  
	| |_____ |       ||   |___ |   |___ |   |    |  | |  ||       ||   |___ |   |_||_ 
	|_____  ||       ||    ___||    ___||   |___ |  |_|  ||       ||    ___||    __  |
	 _____| ||   _   ||   |    |   |    |       ||       ||   _   ||   |___ |   |  | |
	|_______||__| |__||___|    |___|    |_______||_______||__| |__||_______||___|  |_|
-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
		<title><?= htmlentities($args['title']) ?></title>
		<meta name="author" content="<?= htmlentities(__AUTHOR__['name']) ?>">
		<meta name="publisher" content="<?= htmlentities(__AUTHOR__['name']) ?>">
		<meta name="copyright" content="<?= htmlentities(__AUTHOR__['name']) ?>">
		<meta name="keywords" content="<?= htmlentities(__SITE__['keyword']) ?>">
		<meta property="description" content="<?= htmlentities(__SITE__['description']) ?>">
		<link rel="shortcut icon" type="image/x-icon" href="<?= Data::resource('/assets/images/favicon.ico') ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?= Data::resource('/assets/images/favicon.png') ?>">
		<meta name="theme-color" content="#343a40">
		<meta name="apple-mobile-web-app-title" content="<?= htmlentities($args['title']) ?>">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="#343a40">
		<link rel="apple-touch-icon-precomposed" href="<?= Data::resource('/assets/images/favicon.png') ?>">
		<meta name="application-name" content="<?= htmlentities($args['title']) ?>">
		<meta name="msapplication-tooltip" content="<?= htmlentities($args['title']) ?>">
		<meta name="msapplication-TileColor" content="#343a40">
		<meta name="msapplication-TileImage" content="<?= Data::resource('/assets/images/favicon.png') ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?= __SITE__['url'] ?>">
		<meta property="og:description" content="<?= htmlentities(__SITE__['description']) ?>">
		<meta property="og:site_name" content="<?= htmlentities(__SITE__['title']) ?>">
		<meta property="og:image" content="<?= Data::resource('/assets/images/thumbnail.png', true) ?>">
		<meta property="og:locale" content="en_US">
		<meta property="fb:app_id" content="<?= htmlentities(__SITE__['facebook_app_id']) ?>">
		<meta name="twitter:card" content="summary">
		<meta name="twitter:site" content="@<?= htmlentities(__SITE__['twitter_account']) ?>">
		<meta name="twitter:url" content="<?= __SITE__['url'] ?>">
		<meta name="twitter:title" content="<?= htmlentities($args['title']) ?>">
		<meta name="twitter:description" content="<?= htmlentities(__SITE__['description']) ?>">
		<meta name="twitter:image" content="<?= Data::resource('/assets/images/thumbnail.png', true) ?>">
		<!--[if lt IE 9]>
			<script src="<?= Data::resource('/assets/scripts/html5shiv.min.js') ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
			<script src="<?= Data::resource('/assets/scripts/respond.min.js') ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?= Data::resource('/assets/styles/bootstrap.min.css') ?>">
		<link rel="stylesheet" href="<?= Data::resource('/assets/styles/font-awesome.min.css') ?>">
		<link rel="stylesheet" href="<?= Data::resource('/assets/styles/common.css') ?>">
<?php for($i = 0; isset($args['styles'][$i]); ++$i): ?>
		<link rel="stylesheet" href="<?= Data::resource($args['styles'][$i]) ?>">
<?php endfor; ?>
		<script src="<?= Data::resource('/assets/scripts/jquery.min.js') ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
		<script src="<?= Data::resource('/assets/scripts/popper.min.js') ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
		<script src="<?= Data::resource('/assets/scripts/bootstrap.min.js') ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
		<script src="<?= Data::resource('/assets/scripts/common.js') ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
<?php for($i = 0; isset($args['scripts'][$i]); ++$i): ?>
		<script src="<?= Data::resource($args['scripts'][$i]) ?>" nonce="<?= htmlentities(__CSP_NONCE__) ?>"></script>
<?php endfor; ?>
	</head>
	<body class="bg-dark">
		<div class="container-fluid px-0">
			<header>
				<div class="bg-dark">
					<nav class="navbar navbar-expand-md navbar-dark col-md-11 col-lg-10 col-xl-9 mx-auto">
						<a class="navbar-brand" href="/">
							<h3 class="mb-0">
								<img src="<?= Data::resource('/assets/images/brand.svg') ?>" style="width:26px;height:26px;vertical-align:-10%"><span class="ml-2"><?= htmlentities(__SITE__['title']) ?></span>
							</h3>
						</a>
						<button class="navbar-toggler btn btn-link px-2 text-light border-dark" type="button" data-toggle="collapse" data-target="#navbar-dropdown" aria-controls="navbar-dropdown" aria-expanded="false" aria-label="Toggle navigation">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 27" width="33" height="33" focusable="false">
								<path stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h25M4 15h25M4 23h25"></path>
							</svg>
						</button>
						<div class="collapse navbar-collapse text-uppercase" id="navbar-dropdown">
							<ul class="navbar-nav mr-auto">
								<li class="nav-item<?= $args['active'] === 'notifications' ? ' active' : '' ?>">
									<a class="nav-link" href="/notifications">
										<span class="d-none d-sm-none d-md-block d-lg-none d-xl-none">Notis</span>
										<span class="d-block d-sm-block d-md-none d-lg-block d-xl-block">Notifications</span>
									</a>
								</li>
								<li class="nav-item<?= $args['active'] === 'users' ? ' active' : '' ?>">
									<a class="nav-link" href="/users">Users</a>
								</li>
								<li class="nav-item<?= $args['active'] === 'challenges' ? ' active' : '' ?>">
									<a class="nav-link" href="/challenges">
										<span class="d-none d-sm-none d-md-block d-lg-none d-xl-none">Challs</span>
										<span class="d-block d-sm-block d-md-none d-lg-block d-xl-block">Challenges</span>
									</a>
								</li>
								<li class="nav-item<?= $args['active'] === 'scoreboard' ? ' active' : '' ?>">
									<a class="nav-link" href="/scoreboard">
										<span class="d-none d-sm-none d-md-block d-lg-none d-xl-none">Score</span>
										<span class="d-block d-sm-block d-md-none d-lg-block d-xl-block">Scoreboard</span>
									</a>
								</li>
								<li class="nav-item<?= $args['active'] === 'solves' ? ' active' : '' ?>">
									<a class="nav-link" href="/solves">Solves</a>
								</li>
							</ul>
							<ul class="navbar-nav ml-auto">
								<li class="nav-item active dropdown">
									<button type="button" class="btn btn-link nav-link dropdown-toggle" id="dropdown-right-menu-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?php if(Users::is_signed()): ?>
										<img class="rounded float-left" src="<?= get_user_profile_image_url(Users::get_my_user('user_email'), 24) ?>" alt="profile" style="width:24px;height:24px;"><span class="ml-2 d-block d-sm-block d-md-none d-lg-none d-xl-none float-left"><?= htmlentities(Users::get_my_user('user_name')) ?></span>
<?php else: ?>
										<img class="rounded" src="<?= get_user_profile_image_url(Users::get_unsigned_token(), 24) ?>" alt="profile" style="width:24px;height:24px;">
<?php endif; ?>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdown-right-menu-link">
<?php if(Users::is_signed()): ?>
										<span class="dropdown-header">Signed in as <b><?= htmlentities(Users::get_my_user('user_name')) ?></b></span>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item<?= $args['active'] === 'profile' ? ' active' : '' ?>" href="<?= get_user_profile_page_url(Users::get_my_user('user_name')) ?>">My profile</a>
										<a class="dropdown-item<?= $args['active'] === 'settings' ? ' active' : '' ?>" href="/users/settings">Settings</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="/users/sign-out?token=<?= urlencode(Users::get_signed_token()) ?>&url=<?= urlencode(Templater::get_url_path()) ?>">Sign out</a>
<?php else: ?>
										<span class="dropdown-header"><i class="fa fa-exclamation-triangle mr-1" aria-hidden="true"></i> Unsigned yet</span>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item<?= $args['active'] === 'sign-in' ? ' active' : '' ?>" href="/users/sign-in">Sign in</a>
										<a class="dropdown-item<?= $args['active'] === 'sign-up' ? ' active' : '' ?>" href="/users/sign-up">Sign up</a>
<?php endif; ?>
									</div>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</header>
			<div class="row mx-0 bg-white">
				<div class="col-md-10 col-lg-9 col-xl-8 mx-auto px-4 py-4">
					<noscript>
						<div class="alert alert-warning alert-dismissible" role="alert">
							<i class="fa fa-exclamation-circle"></i><span class="ml-1"><b>Warning!</b> JavaScript is disabled in this web browser, please enable it.</span>
						</div>
					</noscript>
					<div id="alert-area"></div>
