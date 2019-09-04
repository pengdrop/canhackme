<?php

	$noti_count = Notifications::get_noti_count();

	$limit = 20;
	$first_page = 1;
	$last_page = (int)ceil($noti_count / $limit);
	$pagination_count = 9;

	if(isset($args['page'])){
		$page = (int)$args['page'];

		if($page < $first_page){
			Templater::redirect('/notifications/page/'.urlencode($first_page));
		}else if($last_page < $page){
			Templater::redirect('/notifications/page/'.urlencode($last_page));
		}

	}else{
		$page = 1;
	}

	$notis = Notifications::get_notis((int)(($page - 1) * $limit), (int)$limit);

	$args_head = [
		'title' => 'Notifications - '.__SITE__['title'],
		'active' => 'notifications',
	];
	$args_foot = [
		'active' => 'notifications',
	];

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-bell mr-2" aria-hidden="true"></i>Notifications</h3>

<?php if(count($notis) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nothing notified yet.
							</div>
<?php else: ?>
							<ul class="list-group my-3">
<?php 	foreach($notis as $noti): ?>
								<li class="list-group-item d-flex justify-content-between align-items-center bg-light">
									<span class="mr-2"><i class="fa fa-bullhorn mr-1" aria-hidden="true"></i> <?= Data::markbb($noti['noti_contents']) ?></span>
									<time data-timestamp="<?= strtotime($noti['noti_uploaded_at']) ?>"><?= htmlentities($noti['noti_uploaded_at']) ?></time>
								</li>
<?php 	endforeach; ?>
							</ul>
<?php endif; ?>
							<nav aria-label="Pagination">
								<ul class="pagination">
<?php if($first_page < $page): ?>
									<li class="page-item">
										<a class="page-link" href="/notifications/page/<?= urlencode($first_page) ?>" aria-label="First page">&laquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/notifications/page/<?= urlencode($page - 1) ?>" aria-label="Previous page">&lsaquo;</a>
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
										<a class="page-link" href="/notifications/page/<?= urlencode($now_page) ?>" aria-label="Page <?= htmlentities($now_page) ?>"><?= htmlentities($now_page) ?></a>
									</li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);
?>
<?php if($page < $last_page): ?>
									<li class="page-item">
										<a class="page-link" href="/notifications/page/<?= urlencode($page + 1) ?>" aria-label="Next page">&rsaquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/notifications/page/<?= urlencode($last_page) ?>" aria-label="Last page">&raquo;</a>
									</li>
<?php endif; ?>
								</ul>
							</nav>
							<div class="text-muted px-2">
								<i class="fa fa-bell mr-1" aria-hidden="true"></i>Currently notified <?= htmlentities(Notifications::get_noti_count()) ?> messages.
							</div>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>