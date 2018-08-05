<?php
	if(($user = Users::get_user($args['user_name'])) === false){
		Templater::error();
	}

	$chals = Challenges::get_solved_chals($user['user_no']);
	$args_head = [
		'title' => $user['user_name'].'\'s Profile - '.__SITE__['title'],
		'active' => 'profile',
	];
	$args_foot = [
		'active' => 'profile',
	];
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<div class="row">
								<div class="col-md-3 d-none d-md-block">
									<img class="img-fluid rounded" src="<?php Data::profile_image($user['user_email'], 256); ?>" alt="<?php Data::text($user['user_name']); ?>">
								</div>
								<div class="col-sm-12 col-md-9">
									<div class="clearfix">
										<h2 class="float-left mb-0"><?php Data::text($user['user_name']); ?></h2>
										<h5 class="float-left mb-0 ml-2 py-2"><span class="badge badge-pill badge-secondary"><?php Data::text($user['user_score']); ?>pt</span></h5>
										<h5 class="float-left mb-0 ml-2 py-2"><span class="badge badge-pill badge-secondary"># <?php Data::text(Challenges::get_rank_by_user_no($user['user_no'])); ?></span></h5>
									</div>
									<div class="my-1">
										<span class="text-muted">Signed up at <time data-timestamp="<?php Data::timestamp($user['user_signed_up_at']); ?>"><?php Data::text($user['user_signed_up_at']); ?></time>.</span>
									</div>
									<blockquote class="card bg-light p-3 my-2"><?php Data::text($user['user_comment']); ?></blockquote>
								</div>
							</div>
						</div>
						<div class="py-3">
							<h3>Solved Challenges</h3>
<?php if(count($chals) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nothing solved yet.
							</div>
<?php else: ?>
							<ul class="list-group my-3">
<?php 	foreach($chals as $chal): ?>
								<li class="list-group-item d-flex justify-content-between align-items-center bg-light">
									<span>
										<i class="fa fa-bug mr-1" aria-hidden="true"></i>
										<a class="text-dark" href="/challenges/name/<?php Data::url($chal['chal_name']); ?>"><?php Data::text($chal['chal_title']); ?></a>
										<span class="ml-1">(<?php Data::text($chal['chal_score']); ?>pt)</span>
									</span>
									<time data-timestamp="<?php Data::timestamp($chal['chal_solved_at']); ?>"><?php Data::text($chal['chal_solved_at']); ?></time>
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
<?php Templater::render('common/foot', $args_foot); ?>