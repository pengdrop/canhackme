					<div class="text-right">
						<a href="#" class="text-secondary" data-scroll-top><i class="fa fa-arrow-circle-o-up mr-1" aria-hidden="true"></i>Back to top</a>
					</div>
				</div>
			</div>
			<footer>
				<div class="bg-dark border-top">
					<nav class="navbar navbar-expand-md navbar-dark col-md-11 col-lg-10 col-xl-9 mx-auto text-uppercase">
						<div class="navbar-text mr-auto">
							<span class="text-light">© <a class="text-light" href="<?= htmlentities(__AUTHOR__['website']) ?>" target="_blank"><?= htmlentities(__AUTHOR__['name']) ?></a>. All rights reserved.</span>
						</div>
						<div class="collapse navbar-collapse">
							<ul class="navbar-nav ml-auto">
<?php if(Users::is_signed()): ?>
								<li class="nav-item">
									<a class="nav-link<?php if($args['active'] === 'profile'): ?> active<?php endif; ?>" href="<?= get_user_profile_page_url(Users::get_my_user('user_name')) ?>">My profile</a></a>
								</li>
								<li class="nav-item">
									<a class="nav-link<?php if($args['active'] === 'settings'): ?> active<?php endif; ?>" href="/users/settings">Settings</a></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/users/sign-out?token=<?= urlencode(Users::get_signed_token()) ?>&url=<?= urlencode(Templater::get_url_path()) ?>">Sign out</a>
								</li>
<?php else: ?>
								<li class="nav-item<?php if($args['active'] === 'sign-in'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/users/sign-in">Sign in</a>
								</li>
								<li class="nav-item<?php if($args['active'] === 'sign-up'): ?> active<?php endif; ?>">
									<a class="nav-link" href="/users/sign-up">Sign up</a>
								</li>
<?php endif; ?>
							</ul>
						</div>
					</nav>
				</div>
			</footer>
		</div>
	</body>
<?php if(isset($args['script']{0})): ?>
	<script nonce="<?= htmlentities(__CSP_NONCE__) ?>">
		$(function(){ <?= $args['script'] ?> });
	</script>
<?php endif; ?>
</html>
