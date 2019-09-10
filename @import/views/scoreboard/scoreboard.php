<?php

	# args
	$keyword = isset($_GET['q']) && is_string($_GET['q']) ? $_GET['q'] : '';
	$page = isset($_GET['p']) && is_string($_GET['p']) ? (int)$_GET['p'] : 1;

	$user_count = Users::get_user_count($keyword);

	$limit = 20;
	$first_page = 1;
	$last_page = $user_count === 0 ? 1 : (int)ceil($user_count / $limit);
	$pagination_count = 9;

	if($page < $first_page){
		Templater::redirect('/scoreboard?'.http_build_query([
			'q' => $keyword,
			'p' => $first_page,
		]));
	}else if($last_page < $page){
		Templater::redirect('/scoreboard?'.http_build_query([
			'q' => $keyword,
			'p' => $last_page,
		]));
	}

	$ranks = Challenges::get_ranks($keyword, (int)(($page - 1) * $limit), (int)$limit);


	$args_head = [
		'title' => 'Scoreboard - '.__SITE__['title'],
		'active' => 'scoreboard',
		'scripts' => ['/assets/scripts/chart.min.js'],
	];
	$args_foot = [
		'active' => 'scoreboard',
	];

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<div class="clearfix">
								<h3 class="float-left text-uppercase"><i class="fa fa-trophy mr-2" aria-hidden="true"></i>Scoreboard</h3>
								<form class="float-right" id="search-scoreboard-form" action="/scoreboard" method="get">
									<div class="form-group m-0">
										<label class="sr-only" for="keyword">Keyword</label>
										<div class="input-group">
											<input type="text" id="keyword" name="q" class="form-control" 
												aria-label="Keyword" placeholder="Search here..." 
												data-toggle="tooltip" data-placement="top" title="Enter the keyword to search." value="<?= htmlentities($keyword) ?>">
											<div class="input-group-append">
												<button type="submit" class="btn btn-secondary"><i class="fa fa-search" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
								</form>
							</div>
<?php if(count($ranks) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nobody signed up yet.
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
										<th class="d-none d-md-table-cell">Comment</th>
										<th class="text-center">Score</th>
										<th class="d-none d-md-table-cell">Last solved at</th>
									</tr>
								</thead>
								<tbody>
<?php 	$cnt = (int)(($page - 1) * $limit); foreach($ranks as $rank): ?>
<?php 		if($rank['user_name'] === Users::get_my_user('user_name')): ?>
									<tr class="table-info">
<?php 		else: ?>
									<tr>
<?php 		endif; ?>
										<td class="text-center" scope="row"><?= htmlentities(++$cnt) ?></td>
										<td><a class="text-dark" href="<?= get_user_profile_page_url($rank['user_name']) ?>"><?= highlight_keyword($rank['user_name'], $keyword) ?></a></td>
										<td class="d-none d-md-table-cell">
<?php if(Users::is_valid_user_url($rank['user_comment'])): ?>
											<a class="text-dark" href="<?= htmlentities($rank['user_comment']) ?>" target="_blank">
												<?= highlight_keyword(htmlentities($rank['user_comment']), $keyword) ?>
											</a>
<?php else: ?>
											<?= highlight_keyword(htmlentities($rank['user_comment']), $keyword) ?>
<?php endif; ?>
										</td>
										<td class="text-center"><?= htmlentities($rank['user_score']) ?>pt</td>
<?php 		if($rank['user_last_solved_at'] === null): ?>
										<td>Nothing solved yet</td>
<?php 		else: ?>
										<td class="d-none d-md-table-cell"><time data-timestamp="<?= strtotime($rank['user_last_solved_at']) ?>"><?= htmlentities($rank['user_last_solved_at']) ?> (UTC)</time></td>
<?php 		endif; ?>
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
										<a class="page-link" href="/scoreboard?<?= http_build_query([ 'q' => $keyword, 'p' => $first_page ]) ?>" aria-label="First page">&laquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/scoreboard?<?= http_build_query([ 'q' => $keyword, 'p' => $page - 1 ]) ?>" aria-label="Previous page">&lsaquo;</a>
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
										<a class="page-link" href="/scoreboard?<?= http_build_query([ 'q' => $keyword, 'p' => $now_page ]) ?>" aria-label="Page <?= htmlentities($now_page) ?>"><?= htmlentities($now_page) ?></a>
									</li>
<?php
		}
		unset($now_page);
	}
	unset($i, $min, $max);
?>
<?php if($page < $last_page): ?>
									<li class="page-item">
										<a class="page-link" href="/scoreboard?<?= http_build_query([ 'q' => $keyword, 'p' => $page + 1 ]) ?>" aria-label="Next page">&rsaquo;</a>
									</li>
									<li class="page-item">
										<a class="page-link" href="/scoreboard?<?= http_build_query([ 'q' => $keyword, 'p' => $last_page ]) ?>" aria-label="Last page">&raquo;</a>
									</li>
<?php endif; ?>
								</ul>
							</nav>
							<div class="text-muted px-2">
								<i class="fa fa-user mr-1" aria-hidden="true"></i>Currently signed up <?= htmlentities(Users::get_user_count()) ?> peoples.
							</div>
						</div>
					</main>
					
<?php Templater::import('views/common/foot', $args_foot) ?>
