<?php
	$chal_tags = Challenges::get_chal_tags();

	if(isset($args['chal_tag'])){
		foreach($chal_tags as $tag){
			if(!strcasecmp($tag, $args['chal_tag'])){
				$selected_tag = $tag;
				break;
			}
		}
		if(!isset($selected_tag)){
			Templater::error(404);
		}
	}else{
		$selected_tag = 'All';
	}
	$chals = Challenges::get_chals($selected_tag);

	$args_head = [
		'title' => 'Challenges - '.__SITE__['title'],
		'active' => 'challenges',
		'scripts' => [
			'/assets/scripts/challenges.js',
			'https://www.google.com/recaptcha/api.js?render='.urlencode(__SITE__['recaptcha_sitekey']),
		],
	];
	$args_foot = [
		'active' => 'challenges',
	];

	if(isset($args['chal_name'])){
		foreach($chals as $chal){
			if(!strcasecmp($chal['chal_name'], $args['chal_name'])){
				$selected_name = $chal['chal_name'];
				break;
			}
		}
		if(!isset($selected_name)){
			Templater::error(404);
		}
	}else{
		$selected_name = null;
	}

?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
<?php if(count($chals) < 1): ?>
							<h3 class="text-uppercase"><i class="fa fa-bug mr-2" aria-hidden="true"></i>Challenges</h3>
							<div class="card bg-light mt-3 p-3 mb-3">
								Nothing opened yet.
							</div>
<?php else: ?>
							<div class="clearfix">
								<h3 class="float-left text-uppercase"><i class="fa fa-bug mr-2" aria-hidden="true"></i>Challenges</h3>
								<div class="float-right pt-1">
									<div class="dropdown float-left">
										<button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fa fa-tags" aria-hidden="true"></i><span class="ml-1">Tag: <?= htmlentities($selected_tag) ?></span>
										</button>
										<div class="dropdown-menu">
											<a class="dropdown-item<?= 'All' === $selected_tag ? ' active' : '' ?>" href="/challenges">All</a>
<?php 	foreach($chal_tags as $tag): ?>
											<a class="dropdown-item<?= $tag === $selected_tag ? ' active' : '' ?>" href="<?= get_challenge_tag_page_url($tag) ?>"><?= htmlentities($tag) ?></a>
<?php 	endforeach; ?>
										</div>
									</div>
								</div>
							</div>
							<form id="auth-flag-form" class="mt-2" action="/challenges/authentication" method="post" data-recaptcha-sitekey="<?= htmlentities(__SITE__['recaptcha_sitekey']) ?>">
								<input type="hidden" name="recaptcha-token">
								<div class="form-group m-0">
									<label class="sr-only" for="flag">Flag</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text" id="addon-flag"><i class="fa fa-flag" aria-hidden="true"></i></span>
										</div>
										<input type="text" id="flag" name="flag" class="form-control" 
											aria-label="Flag" aria-describedby="addon-flag" placeholder="CanHackMe{ ... }" 
											data-toggle="tooltip" data-placement="top" title="Enter the flag you captured.">
										<div class="input-group-append">
											<button type="submit" class="btn btn-secondary" disabled>
												<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Waiting...
											</button>
										</div>
									</div>
								</div>
							</form>
							<div class="accordion my-3" id="chals-accordion">
<?php 	foreach($chals as $chal): ?>
								<div class="card">
									<div class="card-header clearfix" id="chal-head-<?= htmlentities($chal['chal_name']) ?>">
										<a href="<?= get_challenge_shortcut_page_url($chal['chal_name']) ?>" class="btn btn-link text-<?= $chal['chal_is_solved'] ? 'success' : 'dark' ?> p-0 float-left" 
											data-toggle="collapse" data-target="#chal-body-<?= htmlentities($chal['chal_name']) ?>" 
											aria-expanded="false" aria-controls="chal-body-<?= htmlentities($chal['chal_name']) ?>">
											<i class="fa fa-<?= $chal['chal_is_solved'] ? 'unlock-alt' : 'lock' ?>" aria-hidden="true"></i><span class="px-2"><?= htmlentities($chal['chal_title']) ?></span>
										</a>
<?php 		if(strtotime($chal['chal_uploaded_at']) >= time() - 3600 * 24 * 3): ?>
										<small><span class="badge badge-<?= $chal['chal_is_solved'] ? 'success' : 'secondary' ?>">New</span></small>
<?php 		endif; ?>
										<span class="float-right text-<?= $chal['chal_is_solved'] ? 'success' : 'dark' ?>"><?= htmlentities($chal['chal_score']) ?>pt</span>
									</div>
									<div id="chal-body-<?= htmlentities($chal['chal_name']) ?>" class="collapse<?= $selected_name === $chal['chal_name'] ? ' show': '' ?>" aria-labelledby="chal-head-<?= htmlentities($chal['chal_name']) ?>" data-parent="#chals-accordion">
										<div class="card-body">
											<div class="text-muted text-right2">
												<ul class="list-inline mb-0">
													<li class="list-inline-item mx-0">Author: <a class="text-dark" href="<?= get_user_profile_page_url($chal['chal_author']) ?>"><?= htmlentities($chal['chal_author']) ?></a></li>
													<li class="list-inline-item mx-1">|</li>
													<li class="list-inline-item mx-0">Solvers: <span class="text-dark solvers"><?= htmlentities($chal['chal_solvers']) ?></span></li>
<?php 		if($chal['chal_first_solver'] !== null): ?>
													<li class="list-inline-item mx-1">|</li>
													<li class="list-inline-item mx-0">First Solver: <a class="text-dark" href="<?= get_user_profile_page_url($chal['chal_first_solver']) ?>"><?= htmlentities($chal['chal_first_solver']) ?></a></li>
<?php 		endif; ?>
												</ul>
											</div>
											<div class="my-4"><?= Data::markbb($chal['chal_contents']) ?></div>
											<div class="text-right">
												<i class="fa fa-tags" aria-hidden="true"></i>
<?php 		foreach(explode(',', $chal['chal_tags']) as $tag): ?>
												<a href="<?= get_challenge_tag_page_url($tag) ?>" class="text-muted ml-1">#<?= htmlentities($tag) ?></a>
<?php 		endforeach; ?>
											</div>
										</div>
									</div>
								</div>
<?php 	endforeach; ?>
							</div>
<?php endif; ?>
							<div class="text-muted px-2">
								<i class="fa fa-bug mr-1" aria-hidden="true"></i>Currently opened <?= htmlentities(Challenges::get_chal_count()) ?> challenges.
							</div>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>