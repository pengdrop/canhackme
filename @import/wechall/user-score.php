<?php
	if(!isset($_GET['name'])){
		die('0'); 
	}

	if(!Users::is_valid_user_name($_GET['name'])){
		die('0'); 
	}

	if(($user = Users::get_user($_GET['name'], '*', true)) === false){
		die('0');
	}
	$name = $user['user_name'];
	$score = $user['user_score'];

	if(($rank = Challenges::get_rank_by_user_no($user['user_no'])) === false){
		die('0');
	}

	if(($chals = Challenges::get_chals()) === false){
		die('0');
	}
	$maxscore = array_sum(array_column($chals, 'chal_score'));
	$challcount = count($chals);

	if(($challsolved = Challenges::get_solv_count($user['user_no'])) === false){
		die('0');
	}

	if(($usercount = Users::get_user_count()) === false){
		die('0');
	}

	die(sprintf('%s:%d:%d:%d:%d:%d:%d', $name, $rank, $score, $maxscore, $challsolved, $challcount, $usercount));