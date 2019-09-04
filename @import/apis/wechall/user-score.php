<?php
	if(!isset($_GET['authkey']{0}) || $_GET['authkey'] !== __SITE__['wechall_authkey']){
		Templater::error(403);
	}

	if(!isset($_GET['name']{0}) || !Users::is_valid_user_name($_GET['name'])){
		die('0'); 
	}

	if(($user = Users::get_user_by_name($_GET['name'], '*', true)) === false){
		die('0');
	}
	$name = $user['user_name'];
	$score = $user['user_score'];

	if(($rank = Challenges::get_rank_by_user_no($user['user_no'])) === false){
		die('0');
	}

	if(($chal_progress = Challenges::get_chal_count_and_score()) === false){
		die('0');
	}
	$maxscore = $chal_progress['score'];
	$challcount = $chal_progress['count'];

	if(($challsolved = Users::get_solv_count_by_user_no($user['user_no'])) === false){
		die('0');
	}

	if(($usercount = Users::get_user_count()) === false){
		die('0');
	}

	die(sprintf('%s:%d:%d:%d:%d:%d:%d', $name, $rank, $score, $maxscore, $challsolved, $challcount, $usercount));