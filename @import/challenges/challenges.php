<?php
	if(isset($args['chal_tag'])){
		foreach(Challenges::get_chal_tags() as $tag){
			if(!strcasecmp($tag, $args['chal_tag'])){
				$selected_tag = $tag;
				break;
			}
		}
		if(!isset($selected_tag)){
			Templater::error();
		}
	}else{
		$selected_tag = 'All';
	}

	if(!Users::is_signed()){
		Templater::render('users/sign-in');
		die;
	}

	$args_head = [
		'title' => 'Challenges - '.__SITE__['title'],
		'active' => 'challenges',
		'js' => ['/assets/js/challenges.js'],
	];
	$args_foot = [
		'active' => 'challenges',
	];

	# auth flag
	if(isset($_POST['auth-flag'], $_POST['flag'])){
		if(Challenges::is_valid_chal_flag($_POST['flag']) && ($chal = Challenges::get_chal_by_chal_flag($_POST['flag'])) !== false){
			if(Challenges::is_solved_chal($chal['chal_no'])){
				$args_foot['script'] = '$.show_alert("info", "<b>Correct!</b> You already solved the <a class="alert-link" href="/challenges/name/'.$chal['chal_name'].'">'.$chal['chal_title'].'</a> challenge.");';
			}else if(Challenges::do_solve_chal($chal['chal_no'], $chal['chal_score'])){
				$args_foot['script'] = '$.show_alert("success", "<b>Congratulations!</b> You solved the <a class="alert-link" href="/challenges/name/'.$chal['chal_name'].'">'.$chal['chal_title'].'</a> challenge, and you got a '.$chal['chal_score'].'pt.");';
			}else{
				$args_foot['script'] = '$.show_alert("danger", "<b>Error!</b> Try again.");';
			}
			unset($_POST['flag']);
		}else{
			$args_foot['script'] = '$.show_alert("danger", "<b>Failed!</b> The flag is incorrect.");'
				.'$("#flag").focus();';
		}
		unset($chal);
	}

	$chals = Challenges::get_chals($selected_tag);
	if(isset($args['chal_name'])){
		foreach($chals as $chal){
			if(!strcasecmp($chal['chal_name'], $args['chal_name'])){
				$selected_name = $chal['chal_name'];
				$args_foot['script'] = (isset($args_foot['script']) ? $args_foot['script'] : '') . '$("#chals-accordion").find("#chal-body-'.$selected_name.'").collapse({ toggle: true });';
				break;
			}
		}
		if(!isset($selected_name)){
			Templater::error();
		}
	}
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
<?php if(count($chals) < 1): ?>
							<h2>Challenges</h2>
							<div class="card bg-light mt-3 p-3 mb-3">
								Nothing opened yet.
							</div>
<?php else: ?>
							<div class="clearfix">
								<h2 class="float-left">Challenges</h2>
								<div class="float-right py-2">
									<div class="dropdown float-left ml-2">
										<button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="fa fa-tags" aria-hidden="true"></i> Tags: <span class="text-dark"><?php Data::text($selected_tag); ?></span>
										</button>
										<div class="dropdown-menu">
											<a class="dropdown-item <?php if('All' === $selected_tag) Data::text('active'); ?>" href="/challenges">All</a>
<?php 	foreach(Challenges::get_chal_tags() as $tag): ?>
											<a class="dropdown-item <?php if($tag === $selected_tag) Data::text('active'); ?>" href="/challenges/tag/<?php Data::url(strtolower($tag)); ?>"><?php Data::text($tag); ?></a>
<?php 	endforeach; ?>
										</div>
									</div>
								</div>
							</div>
							<form id="auth-flag-form" class="mt-2" method="post">
								<input type="hidden" name="auth-flag">
								<div class="form-group m-0">
									<label class="sr-only" for="flag">Flag</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text" id="addon-flag"><i class="fa fa-flag" aria-hidden="true"></i></span>
										</div>
										<input type="text" id="flag" name="flag" class="form-control" 
											aria-label="Flag" aria-describedby="addon-flag" placeholder="CanHackMe{ ... }" 
											data-toggle="tooltip" data-placement="top" title="Input the flag you captured." 
											value="<?php if(isset($_POST['auth-flag'], $_POST['flag']) && is_string($_POST['flag'])) Data::text($_POST['flag']); ?>">
										<div class="input-group-append">
											<button class="btn btn-secondary" type="submit">Submit</button>
										</div>
									</div>
								</div>
							</form>

							<div class="accordion my-3" id="chals-accordion">
<?php 	foreach($chals as $chal): ?>
								<div class="card">
									<div class="card-header clearfix" id="chal-head-<?php Data::text($chal['chal_name']); ?>">
										<a href="/challenges/name/<?php Data::url($chal['chal_name']); ?>" class="btn btn-link text-<?php echo $chal['chal_is_solved'] ? 'success' : 'dark'; ?> p-0 float-left" 
											data-toggle="collapse" data-target="#chal-body-<?php Data::text($chal['chal_name']); ?>" 
											aria-expanded="false" aria-controls="chal-body-<?php Data::text($chal['chal_name']); ?>">
											<i class="fa fa-<?php echo $chal['chal_is_solved'] ? 'unlock-alt' : 'lock'; ?>" aria-hidden="true"></i><span class="px-2"><?php Data::text($chal['chal_title']); ?></span>
										</a>
<?php 		if(strtotime($chal['chal_uploaded_at']) >= time() - 3600 * 24 * 3): ?>
										<small><span class="badge badge-<?php echo $chal['chal_is_solved'] ? 'success' : 'secondary'; ?>">New</span></small>
<?php 		endif; ?>
										<span class="float-right text-<?php echo $chal['chal_is_solved'] ? 'success' : 'dark'; ?>"><?php Data::text($chal['chal_score']); ?>pt</span>
									</div>
									<div id="chal-body-<?php Data::text($chal['chal_name']); ?>" class="collapse" aria-labelledby="chal-head-<?php Data::text($chal['chal_name']); ?>" data-parent="#chals-accordion">
										<div class="card-body">
											<div class="text-muted text-right2">
												<ul class="list-inline mb-0">
													<li class="list-inline-item mx-0">Author: <a class="text-dark" href="/users/profile/<?php Data::url(strtolower($chal['chal_author'])); ?>"><?php Data::text($chal['chal_author']); ?></a></li>
													<li class="list-inline-item mx-1">|</li>
													<li class="list-inline-item mx-0">Solvers: <span class="text-dark solvers"><?php Data::text($chal['chal_solvers']); ?></span></li>
<?php 		if($chal['chal_first_solver'] !== null): ?>
													<li class="list-inline-item mx-1">|</li>
													<li class="list-inline-item mx-0">First Solver: <a class="text-dark" href="/users/profile/<?php Data::url(strtolower($chal['chal_first_solver'])); ?>"><?php Data::text($chal['chal_first_solver']); ?></a></li>
<?php 		endif; ?>
												</ul>
											</div>
											<div class="my-4"><?php Data::markbb($chal['chal_contents']); ?></div>
											<div class="text-right">
												<i class="fa fa-tags" aria-hidden="true"></i>
<?php 		foreach(explode(',', $chal['chal_tags']) as $tag): ?>
												<a href="/challenges/tag/<?php Data::url($tag); ?>" class="text-muted ml-1">#<?php Data::text($tag); ?></a>
<?php 		endforeach; ?>
											</div>
										</div>
									</div>
								</div>
<?php 	endforeach; ?>
							</div>
<?php endif; ?>
							<div class="text-muted px-2">
								<i class="fa fa-bug mr-1" aria-hidden="true"></i> Currently opened <?php Data::text(Challenges::get_chal_count()); ?> challenges.
							</div>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>