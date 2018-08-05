					<div class="text-right">
						<a href="#">Back to top</a>
					</div>
				</div>
			</div>
			<footer>
				<div class="bg-dark border-top">
					<nav class="navbar navbar-expand-lg navbar-dark col-md-7 mx-auto">
						<div class="navbar-text mr-auto">
							<span class="text-light">© <a class="text-light" href="<?php Data::text(__AUTHOR__['website']); ?>" target="_blank"><?php Data::text(__AUTHOR__['name']); ?></a>. All rights reserved.</span>
						</div>
						<div class="collapse navbar-collapse">
							<ul class="navbar-nav ml-auto">
<?php if(Users::is_signed()): ?>
								<li class="nav-item">
									<a class="nav-link<?php if($args['active'] === 'profile'): ?> active<?php endif; ?>" href="/users/profile/<?php Data::url(strtolower(Users::get_my_user('user_name'))); ?>">My profile</a></a>
								</li>
								<li class="nav-item">
									<a class="nav-link<?php if($args['active'] === 'settings'): ?> active<?php endif; ?>" href="/users/settings">Settings</a></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="/users/sign-out?token=<?php Data::url(Users::get_signed_token()); ?>&url=<?php Data::url(Templater::get_url_path()); ?>">Sign out</a>
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
	<script>
		$(function(){ <?php echo $args['script']; ?> });
	</script>
<?php endif; ?>
</html>
