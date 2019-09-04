<?php
	$args_head = [
		'title' => 'Error - '.__SITE__['title'],
		'active' => 'error',
	];
	$args_foot = [
		'active' => 'error',
	];

	# error
	isset($_SERVER['REDIRECT_STATUS']) or $_SERVER['REDIRECT_STATUS'] = 404;
	switch($_SERVER['REDIRECT_STATUS']){
	case 403:
		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
		$err_title = 'Access Denied';
		$err_contents = 'You don\'t have permission to access this page.';
		break;
	case 404:
	default:
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		$err_title = 'Page Not Found';
		$err_contents = 'The requested URL was not found on this website,<br>please verify that the URL is spelled correctly.';
		break;
	}

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3 text-center">
							<h3 class="text-uppercase"><i class="fa fa-ban mr-2" aria-hidden="true"></i><?= $err_title ?></h3>
							<img class="m-4" src="<?= Data::resource('/assets/images/forbidden.png') ?>" alt="🚫">
							<div class="lead">
								<?= $err_contents ?><br>
								<a href="/">Go back to the home page.</a>
							</div>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>