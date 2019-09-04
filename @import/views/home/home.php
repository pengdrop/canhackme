<?php
	$args_head = [
		'title' => __SITE__['title'],
		'active' => 'home',
	];
	$args_foot = [
		'active' => 'home',
	];
?>
<?php Templater::import('views/common/head', $args_head) ?>
					<main>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-globe mr-2" aria-hidden="true"></i>Introduction</h3>
							<div class="lead">
								This service offers <a href="/challenges">challenges</a> to improve your hacking skills.<br>
								Capture the flag to resolve the challenge.
								The default format for all flags is <code>CanHackMe{...}</code>.<br>
								And authenticate the flag you captured.
								If you've done it, you get points for the challenge.<br>
								You can see the ranking on the <a href="/scoreboard">scoreboard</a> and compete with other <a href="/users">users</a>.<br>
								If you don't give up, you can hack me. Have fun challenges!<br>
							</div>
						</div>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-gavel mr-2" aria-hidden="true"></i>Rules</h3>
							<ul class="list-unstyled m-0 lead">
								<li>DO NOT sign up more than once. If you forget your account, please contact to admin.</li>
								<li>DO NOT play as a team. Only solo play is allowed.</li>
								<li>DO NOT bruteforce challenges authentication. It's meaningless attempt.</li>
								<li>DO NOT share publicly with the flag and the solution of the challenge.</li>
								<li>If you find an unintended bug, please contact to admin directly.</li>
							</ul>
						</div>
						<div class="py-3">
							<h3 class="text-uppercase"><i class="fa fa-envelope mr-2" aria-hidden="true"></i>Contact</h3>
							<div class="lead">
								If you have any questions or problems, please feel free to contact at &lt;<a href="mailto:<?= email_encode(__AUTHOR__['email']) ?>"><?= email_encode(__AUTHOR__['email']) ?></a>&gt;.<br>
								Admin may not be able to reply immediately.<br>
							</div>
						</div>
					</main>
<?php Templater::import('views/common/foot', $args_foot) ?>