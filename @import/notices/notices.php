<?php
	$args_head = [
		'title' => 'Notices - '.__SITE__['title'],
		'active' => 'notices',
	];
	$args_foot = [
		'active' => 'notices',
	];

	$notis = Notices::get_new_notis(30);
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<h2>Notices</h2>

<?php if(count($notis) < 1): ?>
							<div class="card bg-light p-3 my-3">
								Nothing notified yet.
							</div>
<?php else: ?>
							<ul class="list-group my-3">
<?php 	foreach($notis as $noti): ?>
								<li class="list-group-item d-flex justify-content-between align-items-center bg-light">
									<span class="mr-2"><i class="fa fa-bullhorn mr-1" aria-hidden="true"></i> <?php Data::markbb($noti['noti_contents']); ?></span>
									<time data-timestamp="<?php Data::timestamp($noti['noti_uploaded_at']); ?>"><?php Data::text($noti['noti_uploaded_at']); ?></time>
								</li>
<?php 	endforeach; ?>
							</ul>
<?php endif; ?>
							<div class="text-muted px-2">
								<i class="fa fa-comment mr-1" aria-hidden="true"></i> Currently notified <?php Data::text(Notices::get_noti_count()); ?> messages.
							</div>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>