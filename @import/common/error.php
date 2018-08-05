<?php
	$args_head = [
		'title' => 'Error - '.__SITE__['title'],
		'active' => 'error',
	];
	$args_foot = [
		'active' => 'error',
	];

	# error
	isset($_SERVER['REDIRECT_STATUS']) or $_SERVER['REDIRECT_STATUS'] = '404';
	switch($_SERVER['REDIRECT_STATUS']){
	case '403':
		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
		$err_title = 'Forbidden';
		$err_contents = 'You don\'t have permission to access this page.';
		break;
	case '404':
	default:
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		$err_title = 'Page Not Found';
		$err_contents = 'Please check that the URL is spelled correctly.';
		break;
	}

?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3 text-center">
							<h2><?php Data::text($err_title); ?></h2>
							<img class="m-4" src="<?php Data::resource('/assets/img/forbidden.png'); ?>" alt="🚫">
							<div class="lead">
								<?php Data::text($err_contents); ?><br>
								Return to <a href="/">home page</a>.
							</div>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>