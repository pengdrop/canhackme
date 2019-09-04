<?php

	$user_count = Users::get_user_count();

	$limit = 24;
	$first_page = 1;
	$last_page = (int)ceil($user_count / $limit);
	$pagination_count = 9;

	if(isset($args['page'])){
		$page = (int)$args['page'];

		if($page < $first_page){
			Templater::redirect('/users/page/'.urlencode($first_page));
		}else if($last_page < $page){
			Templater::redirect('/users/page/'.urlencode($last_page));
		}

	}else{
		$page = 1;
	}

	$users = Users::get_users((int)(($page - 1) * $limit), (int)$limit);

	$args_head = [
		'title' => 'Users - '.__SITE__['title'],
		'active' => 'users',
	];
	$args_foot = [
		'active' => 'users',
	];

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-user mr-2" aria-hidden="true"></i>Users</h3>

<?php if(count($users) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nobody signed up yet.
							</div>
<?php else: ?>
							<div class="row mt-3">
<?php 	foreach($users as $user): ?>
								<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12s mb-4">
									<a class="text-dark" href="<?= get_user_profile_page_url($user['user_name']) ?>">
										<div class="card">
											<img src="<?= get_user_profile_image_url($user['user_email'], 512) ?>" class="card-img" alt="<?= htmlentities($user['user_name']) ?>">
											<div class="card-body">
												<h5 class="card-title"><?= htmlentities($user['user_name']) ?></h5>
												<p class="card-text"><span class="badge badge-pill badge-secondary"><?= htmlentities($user['user_score']) ?>pt</span></p>
											</div>
										</div>
									</a>
								</div>
<?php 	endforeach; ?>
							</div>
<?php endif; ?>
							<nav aria-label="Pagination">
								<ul class="pagination">
<?php if($first_page < $page): ?>
									<li class="page-item">
										<a class="page-link" href="/users/page/<?= urlencode($first_page) ?>" aria-label="First page">&laquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/users/page/<?= urlencode($page - 1) ?>" aria-label="Previous page">&lsaquo;</a>
									</li>
<?php endif; ?>
<?php
	// middle page
	if($page < ceil($pagination_count / 2)){
		$min = 1 - $page;
		$max = $pagination_count - $page;
	}else if(($last_page - $page + 1) < ceil($pagination_count / 2)){
		$min = -$pagination_count + ($last_page - $page + 1);
		$max = -1 + ($last_page - $page + 1);
	}else{
		$min = -floor($pagination_count / 2);
		$max = floor($pagination_count / 2);
	}
	for($i = (int)$min; $i <= (int)$max; ++$i){
		$now_page = (int)($page + $i);
		if($first_page <= $now_page && $now_page <= $last_page){
?>
									<li class="page-item<?= $now_page === $page ? ' active' : '' ?>">
										<a class="page-link" href="/users/page/<?= urlencode($now_page) ?>" aria-label="Page <?= htmlentities($now_page) ?>"><?= htmlentities($now_page) ?></a>
									</li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);
?>
<?php if($page < $last_page): ?>
									<li class="page-item">
										<a class="page-link" href="/users/page/<?= urlencode($page + 1) ?>" aria-label="Next page">&rsaquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/users/page/<?= urlencode($last_page) ?>" aria-label="Last page">&raquo;</a>
									</li>
<?php endif; ?>
								</ul>
							</nav>
							<div class="text-muted px-2">
								<i class="fa fa-user mr-1" aria-hidden="true"></i> Currently signed up <?= htmlentities(Users::get_user_count()) ?> peoples.
							</div>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>