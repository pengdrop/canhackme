<?php
	$args_head = [
		'title' => 'Activities - '.__SITE__['title'],
		'active' => 'activities',
	];
	$args_foot = [
		'active' => 'activities',
	];
	$user_name = Users::get_my_user('user_name');
	$solvs = Challenges::get_new_solves(15);
	$users = Users::get_new_users(15);
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<h2>New Solves</h2>
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
									<col style="width:28%">
									<col style="width:12%">
									<col style="width:30%">
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
<?php 		if($solv['solv_user_name'] === $user_name): ?>
									<tr class="table-info">
<?php 		else: ?>
									<tr>
<?php 		endif; ?>
										<td class="text-center" scope="row"><?php Data::text($solv['solv_no']); ?></td>
										<td><a class="text-dark" href="/users/profile/<?php Data::url(strtolower($solv['solv_user_name'])); ?>"><?php Data::text($solv['solv_user_name']); ?></a></td>
										<td><a class="text-dark" href="/challenges/name/<?php Data::url($solv['solv_chal_name']); ?>"><?php Data::text($solv['solv_chal_title']); ?></a></td>
										<td class="text-center"><?php Data::text($solv['solv_chal_score']); ?>pt</td>
										<td><time data-timestamp="<?php Data::timestamp($solv['solv_solved_at']); ?>"><?php Data::text($solv['solv_solved_at']); ?></time></td>
									</tr>
<?php 	endforeach; ?>
								</tbody>
							</table>
							</div>
<?php endif; ?>
							<div class="text-muted px-2">
								<i class="fa fa-flag mr-1" aria-hidden="true"></i> Currently solved <?php Data::text(Challenges::get_solv_count()); ?> times.
							</div>
						</div>
						<div class="py-3">
							<h2>New Users</h2>

<?php if(count($users) < 1): ?>
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
										<th class="text-center">#</th>
										<th>User</th>
										<th>Comment</th>
										<th class="text-center">Score</th>
										<th>Signed up at</th>
									</tr>
								</thead>
								<tbody>
<?php 	foreach($users as $user): ?>
<?php 		if($user['user_name'] === $user_name): ?>
									<tr class="table-info">
<?php 		else: ?>
									<tr>
<?php 		endif; ?>
										<td class="text-center" scope="row"><?php Data::text($user['user_no']); ?></td>
										<td><a class="text-dark" href="/users/profile/<?php Data::url(strtolower($user['user_name'])); ?>"><?php Data::text($user['user_name']); ?></a></td>
										<td><?php Data::text($user['user_comment']); ?></td>
										<td class="text-center"><?php Data::text($user['user_score']); ?>pt</td>
										<td><time data-timestamp="<?php Data::timestamp($user['user_signed_up_at']); ?>"><?php Data::text($user['user_signed_up_at']); ?></time></td>
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