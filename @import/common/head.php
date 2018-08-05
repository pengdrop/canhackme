<?php
	$user = Users::get_my_user();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
		<title><?php Data::text($args['title']); ?></title>
		<meta name="author" content="<?php Data::text(__AUTHOR__['name']); ?>">
		<meta name="keywords" content="<?php Data::text(__SITE__['keyword']); ?>">
		<meta property="description" content="<?php Data::text(__SITE__['description']); ?>">
		<meta property="og:title" content="<?php Data::text($args['title']); ?>">
		<meta property="og:site_name" content="<?php Data::text(__SITE__['title']); ?>">
		<meta property="og:image" content="<?php Data::resource('/assets/img/thumbnail.png'); ?>">
		<meta property="og:description" content="<?php Data::text(__SITE__['description']); ?>">
		<meta property="og:url" content="<?php echo __SITE__['url']; ?>">
		<meta name="twitter:card" content="summary">
		<meta name="twitter:title" content="<?php Data::text($args['title']); ?>">
		<meta name="twitter:site" content="<?php Data::text(__SITE__['title']); ?>">
		<meta name="twitter:image" content="<?php Data::resource('/assets/img/thumbnail.png'); ?>">
		<meta name="twitter:description" content="<?php Data::text(__SITE__['description']); ?>">
		<meta name="twitter:url" content="<?php echo __SITE__['url']; ?>">
		<link rel="icon" href="<?php Data::resource('/assets/img/favicon.png'); ?>">
		<link rel="apple-touch-icon" href="<?php Data::resource('/assets/img/favicon.png'); ?>">
		<!--[if lt IE 9]>
			<script src="<?php Data::resource('/assets/js/html5shiv.min.js'); ?>"></script>
			<script src="<?php Data::resource('/assets/js/respond.min.js'); ?>"></script>
		<![endif]-->
		<link rel="stylesheet" href="<?php Data::resource('/assets/css/bootstrap.min.css'); ?>">
		<link rel="stylesheet" href="<?php Data::resource('/assets/css/font-awesome.min.css'); ?>">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Play|Exo|Ubuntu">
		<link rel="stylesheet" href="<?php Data::resource('/assets/css/common.css'); ?>">
<?php for($i = 0; isset($args['css'][$i]); ++$i): ?>
		<link rel="stylesheet" href="<?php Data::resource($args['css'][$i]); ?>">
<?php endfor; ?>
		<script src="<?php Data::resource('/assets/js/jquery.min.js'); ?>"></script>
		<script src="<?php Data::resource('/assets/js/popper.min.js'); ?>"></script>
		<script src="<?php Data::resource('/assets/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php Data::resource('/assets/js/common.js'); ?>"></script>
<?php for($i = 0; isset($args['js'][$i]); ++$i): ?>
		<script src="<?php Data::resource($args['js'][$i]); ?>"></script>
<?php endfor; ?>
	</head>
	<body class="bg-dark">
		<div class="container-fluid px-0">
			<header>
				<div class="d-none d-lg-block p-3 bg-light border-top border-dark">
					<div class="col-lg-9 mx-auto text-center">
						<h1 class="m-0 font-weight-bold"><a class="text-dark" href="/"><i class="fa fa-bug mr-2" aria-hidden="true"></i><?php Data::text(__SITE__['title']); ?></a></h1>
					</div>
				</div>
				<div class="bg-dark">
					<nav class="navbar navbar-expand-lg navbar-dark col-lg-9 mx-auto">
						<a class="navbar-brand d-block d-lg-none" href="/"><i class="fa fa-bug mr-2" aria-hidden="true"></i><?php Data::text(__SITE__['title']); ?></a>
						<button class="navbar-toggler btn btn-link px-2 py-0 text-light border-dark" type="button" data-toggle="collapse" data-target="#navbar-dropdown" aria-controls="navbar-dropdown" aria-expanded="false" aria-label="Toggle navigation">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 27" width="30" height="27" focusable="false">
								<title>Menu</title>
								<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h25M4 15h25M4 23h25"></path>
							</svg>
						</button>
						<div class="collapse navbar-collapse" id="navbar-dropdown">
							<ul class="navbar-nav mr-auto">
								<li class="nav-item<?php if($args['active'] === 'home'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/">Home</a>
								</li>
								<li class="nav-item<?php if($args['active'] === 'notices'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/notices">Notices</a>
								</li>
								<li class="nav-item<?php if($args['active'] === 'activities'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/activities">Activities</a>
								</li>
								<li class="nav-item<?php if($args['active'] === 'challenges'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/challenges">Challenges</a>
								</li>
								<li class="nav-item<?php if($args['active'] === 'scoreboard'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/scoreboard">Scoreboard</a>
								</li>
							</ul>
							<ul class="navbar-nav ml-auto">
								<li class="nav-item active dropdown">
									<button type="button" class="btn btn-link nav-link dropdown-toggle" id="dropdown-right-menu-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?php if(Users::is_signed()): ?>
										<img class="rounded" src="<?php Data::profile_image(Users::get_my_user('user_email'), 24); ?>" alt="profile" style="width: 24px; height: 24px;"><span class="ml-2"><?php Data::text(Users::get_my_user('user_name')); ?></span>
<?php else: ?>
										<img class="rounded" src="<?php Data::profile_image(Users::get_guest_token(), 24); ?>" alt="profile" style="width: 24px; height: 24px;"><span class="ml-2">Guest</span>
<?php endif; ?>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdown-right-menu-link">
<?php if(Users::is_signed()): ?>
										<span class="dropdown-header">Signed in as <b><?php Data::text(Users::get_my_user('user_name')); ?></b></span>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item<?php if($args['active'] === 'profile'): ?> active<?php endif; ?>" href="/users/profile/<?php Data::url(strtolower(Users::get_my_user('user_name'))); ?>">My profile</a>
										<a class="dropdown-item<?php if($args['active'] === 'settings'): ?> active<?php endif; ?>" href="/users/settings">Settings</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="/users/sign-out?token=<?php Data::url(Users::get_signed_token()); ?>&url=<?php Data::url(Templater::get_url_path()); ?>">Sign out</a>
<?php else: ?>
										<span class="dropdown-header">Not signed yet</span>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item<?php if($args['active'] === 'sign-in'): ?> active<?php endif; ?>" href="/users/sign-in">Sign in</a>
										<a class="dropdown-item<?php if($args['active'] === 'sign-up'): ?> active<?php endif; ?>" href="/users/sign-up">Sign up</a>
<?php endif; ?>
									</div>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</header>
			<div class="row mx-0 bg-white">
				<div class="col-lg-8 mx-auto px-4 py-4">
					<noscript>
						<div class="alert alert-warning alert-dismissible" role="alert">
							<span><b>Warning!</b> Please enable javascript in your web browser.</span>
						</div>
					</noscript>
					<div id="alert-area"></div>
