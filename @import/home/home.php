<?php
	$args_head = [
		'title' => __SITE__['title'],
		'active' => 'home',
	];
	$args_foot = [
		'active' => 'home',
	];
?>
<?php Templater::render('common/head', $args_head); ?>
					<main>
						<div class="py-3">
							<h2>General</h2>
							<div class="lead">
								This website offers the opportunity for to test your hack skills.
								To solve the challenge, capture the flag.
								Default format of all flag is <code>CanHackMe{...}</code>.
								If you solve the challenge, you can get scores.
								Have fun challenges!
							</div>
						</div>
						<div class="py-3">
							<h2>Rules</h2>
							<ul class="list-unstyled m-0 lead">
								<li>Don't sign up more than once.</li>
								<li>Don't play as a team. Only solo play is allowed.</li>
								<li>Don't bruteforce authentication the flag. It's meaningless attempt.</li>
								<li>Don't share the flag and solutions publicly. Though small hints are allowed.</li>
								<li>If you find any unintended bug, please contact to admin personally.</li>
							</ul>
						</div>
						<div class="py-3">
							<h2>Contact</h2>
							<div class="lead">
								If you have any questions or problems, please feel free to contact at <a href="mailto:<?php echo Data::email(__AUTHOR__['email']); ?>"><?php echo Data::email(__AUTHOR__['email']); ?></a>.
								Admin might not be able to reply immediately.
							</div>
						</div>
					</main>
<?php Templater::render('common/foot', $args_foot); ?>