<?php
	if(($user = Users::get_user_by_name($args['user_name'])) === false){
		Templater::error(404);
	}

	$solved_chals = Challenges::get_solved_chals($user['user_no'], true);

	$chal_progress = Challenges::get_chal_count_and_score();
	$percentage = sprintf('%0.2f', 100 / $chal_progress['score'] * $user['user_score']);

	$args_head = [
		'title' => $user['user_name'].'\'s Profile - '.__SITE__['title'],
		'active' => 'users',
	];
	$args_foot = [
		'active' => 'users',
	];
?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-id-card mr-2" aria-hidden="true"></i>Profile</h3>
							<div class="row mt-3">
								<div class="col-md-3 d-none d-md-block">
									<img class="img-fluid rounded img-thumbnail" src="<?= get_user_profile_image_url($user['user_email'], 512) ?>" alt="<?= htmlentities($user['user_name']) ?>">
								</div>
								<div class="col-sm-12 col-md-9">
									<div class="clearfix mb-2">
										<a href="<?= get_user_profile_page_url($user['user_name']) ?>" class="text-dark">
											<h2 class="float-left mb-0"><?= htmlentities($user['user_name']) ?></h2>
										</a>
										<h5 class="float-left mb-0 ml-2 py-2"><span class="badge badge-pill badge-success"># <?= htmlentities(Challenges::get_rank_by_user_no($user['user_no'])) ?></span></h5>
										<h5 class="float-left mb-0 ml-2 py-2"><span class="badge badge-pill badge-secondary"><?= htmlentities($user['user_score']) ?>pt</span></h5>
									</div>
<?php if(Users::get_my_user('user_no') === $user['user_no']): ?>
									<div class="my-1">
										<i class="fa fa-envelope-o mr-1" aria-hidden="true"></i><a href="mailto:<?= email_encode($user['user_email']) ?>" class="text-muted"><?= email_encode($user['user_email']) ?></a>
									</div>
<?php endif; ?>
									<div class="my-1">
										<i class="fa fa-clock-o mr-1" aria-hidden="true"></i><span class="text-muted">Signed up at <time data-timestamp="<?= strtotime($user['user_signed_up_at']) ?>"><?= htmlentities($user['user_signed_up_at']) ?></time>.</span>
									</div>
									<blockquote class="card bg-light p-3 my-3"><?= htmlentities($user['user_comment']) ?></blockquote>
								</div>
							</div>
						</div>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-bar-chart mr-2" aria-hidden="true"></i>Solve Progress</h3>
							<div class="progress mt-3">
								<div class="progress-bar bg-info" role="progressbar" style="width:<?= htmlentities($percentage) ?>%" aria-valuenow="<?= htmlentities($user['user_score']) ?>" aria-valuemin="0" aria-valuemax="<?= htmlentities($chal_progress['score']) ?>"><?= htmlentities($percentage) ?>%</div>
							</div>
<?php if(count($solved_chals) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nothing solved yet.
							</div>
<?php else: ?>
							<ul class="list-group my-3">
<?php 	foreach($solved_chals as $chal): ?>
								<li class="list-group-item d-flex justify-content-between align-items-center bg-light">
									<span>
										<i class="fa fa-bug mr-1" aria-hidden="true"></i>
										<a class="text-dark" href="<?= get_challenge_shortcut_page_url($chal['chal_name']) ?>"><?= htmlentities($chal['chal_title']) ?></a>
										<span class="ml-1">(<?= htmlentities($chal['chal_score']) ?>pt)</span>
<?php 		if($user['user_no'] === $chal['chal_first_solver']): ?>
										<small class="badge badge-danger ml-1">First Solved</small>
<?php 		endif; ?>
									</span>
									<time data-timestamp="<?= strtotime($chal['chal_solved_at']) ?>"><?= htmlentities($chal['chal_solved_at']) ?></time>
								</li>
<?php 	endforeach; ?>
							</ul>
<?php endif; ?>
						</div>
<?php if($user['user_name'] === Users::get_my_user('user_name')): ?>
						<div class="mb-3 text-right">
							<a href="/users/settings" class="btn btn-outline-secondary mr-1"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
						</div>
<?php endif; ?>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>