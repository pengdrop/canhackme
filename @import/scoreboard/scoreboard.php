<?php
	$args_head = [
		'title' => 'Scoreboard - '.__SITE__['title'],
		'active' => 'scoreboard',
	];
	$args_foot = [
		'active' => 'scoreboard',
	];

	$user_name = Users::get_my_user('user_name');
	$ranks = Challenges::get_ranks(30);
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<h2>Scoreboard</h2>
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
									<col style="width:28%">
									<col style="width:12%">
									<col style="width:30%">
								</colgroup>
								<thead>
									<tr>
										<th class="text-center" scope="col">#</th>
										<th>User</th>
										<th>Comment</th>
										<th class="text-center">Score</th>
										<th>Last solved at</th>
									</tr>
								</thead>
								<tbody>
<?php 	$cnt = 0; foreach($ranks as $rank): ?>
<?php 		if($rank['user_name'] === $user_name): ?>
									<tr class="table-info">
<?php 		else: ?>
									<tr>
<?php 		endif; ?>
										<td class="text-center" scope="row"><?php Data::text(++$cnt); ?></td>
										<td><a class="text-dark" href="/users/profile/<?php Data::url(strtolower($rank['user_name'])); ?>"><?php Data::text($rank['user_name']); ?></a></td>
										<td><?php Data::text($rank['user_comment']); ?></td>
										<td class="text-center"><?php Data::text($rank['user_score']); ?>pt</td>
<?php 		if($rank['user_last_solved_at'] === null): ?>
										<td class="text-center">Nothing solved yet</td>
<?php 		else: ?>
										<td><time data-timestamp="<?php Data::timestamp($rank['user_last_solved_at']); ?>"><?php Data::text($rank['user_last_solved_at']); ?></time></td>
<?php 		endif; ?>
									</tr>
<?php 	endforeach; ?>
								</tbody>
							</table>
							</div>
<?php endif; ?>
							<div class="text-muted px-2">
								<i class="fa fa-user mr-1" aria-hidden="true"></i> Currently signed up <?php Data::text(Users::get_user_count()); ?> peoples.
							</div>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>