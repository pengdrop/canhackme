<?php

	$solv_count = Challenges::get_solv_count();

	$limit = 20;
	$first_page = 1;
	$last_page = (int)ceil($solv_count / $limit);
	$pagination_count = 9;

	if(isset($args['page'])){
		$page = (int)$args['page'];

		if($page < $first_page){
			Templater::redirect('/solves/page/'.urlencode($first_page));
		}else if($last_page < $page){
			Templater::redirect('/solves/page/'.urlencode($last_page));
		}

	}else{
		$page = 1;
	}

	$solvs = Challenges::get_solvs((int)(($page - 1) * $limit), (int)$limit);


	$args_head = [
		'title' => 'Solves - '.__SITE__['title'],
		'active' => 'solves',
		'scripts' => ['/assets/scripts/chart.min.js'],
	];
	$args_foot = [
		'active' => 'solves',
	];

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-bar-chart mr-2" aria-hidden="true"></i>Solves</h3>
<?php if(count($solvs) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nobody solved yet.
							</div>
<?php else: ?>
							<div class="table-responsive mt-3">
							<table class="table table-hover table-striped">
								<colgroup>
									<col style="width:10%">
									<col style="width:20%">
									<col style="width:33%">
									<col style="width:12%">
									<col style="width:25%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center" scope="col">#</th>
										<th>User</th>
										<th>Challenge</th>
										<th class="text-center">Score</th>
										<th>Solved at</th>
									</tr>
								</thead>
								<tbody>
<?php 	foreach($solvs as $solv): ?>
<?php 		if($solv['solv_user_name'] === Users::get_my_user('user_name')): ?>
									<tr class="table-info">
<?php 		else: ?>
									<tr>
<?php 		endif; ?>
										<td class="text-center" scope="row"><?= htmlentities($solv['solv_no']) ?></td>
										<td><a class="text-dark" href="<?= get_user_profile_page_url($solv['solv_user_name']) ?>"><?= htmlentities($solv['solv_user_name']) ?></a></td>
										<td><a class="text-dark" href="<?= get_challenge_shortcut_page_url($solv['solv_chal_name']) ?>"><?= htmlentities($solv['solv_chal_title']) ?></a></td>
										<td class="text-center"><?= htmlentities($solv['solv_chal_score']) ?>pt</td>
										<td><time data-timestamp="<?= strtotime($solv['solv_solved_at']) ?>"><?= htmlentities($solv['solv_solved_at']) ?></time></td>
									</tr>
<?php 	endforeach; ?>
								</tbody>
							</table>
							</div>
<?php endif; ?>
							<nav aria-label="Pagination">
								<ul class="pagination">
<?php if($first_page < $page): ?>
									<li class="page-item">
										<a class="page-link" href="/solves/page/<?= urlencode($first_page) ?>" aria-label="First page">&laquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/solves/page/<?= urlencode($page - 1) ?>" aria-label="Previous page">&lsaquo;</a>
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
										<a class="page-link" href="/solves/page/<?= urlencode($now_page) ?>" aria-label="Page <?= htmlentities($now_page) ?>"><?= htmlentities($now_page) ?></a>
									</li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);
?>
<?php if($page < $last_page): ?>
									<li class="page-item">
										<a class="page-link" href="/solves/page/<?= urlencode($page + 1) ?>" aria-label="Next page">&rsaquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/solves/page/<?= urlencode($last_page) ?>" aria-label="Last page">&raquo;</a>
									</li>
<?php endif; ?>
								</ul>
							</nav>
							<div class="text-muted px-2">
								<i class="fa fa-user mr-1" aria-hidden="true"></i>Currently solved <?= htmlentities(Challenges::get_solv_count()) ?> times.
							</div>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>
