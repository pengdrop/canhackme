<?php

	if(!isset($_POST['recaptcha-token'], $_POST['flag'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	if(!is_valid_recaptcha_token($_POST['recaptcha-token'])){
		$res = ['result' => 'invalid_token'];
		goto tail;
	}

	if(!Users::is_signed()){
		$res = ['result' => 'unsigned'];
		goto tail;
	}

	if(!Challenges::is_valid_chal_flag($_POST['flag']) || ($chal = Challenges::get_chal_by_chal_flag($_POST['flag'])) === false){
		$res = ['result' => 'invalid_flag'];
		goto tail;
	}

	if(Challenges::is_solved_chal($chal['chal_no'])){
		$res = ['result' => 'already_solved', 'chal_name' => $chal['chal_name'], 'chal_title' => $chal['chal_title']];
		goto tail;
	}

	if(!Challenges::do_solve_chal($chal['chal_no'], $chal['chal_score'])){
		$res = ['result' => 'error'];
		goto tail;
	}

	$res = ['result' => 'solved', 'chal_name' => $chal['chal_name'], 'chal_title' => $chal['chal_title'], 'chal_score' => $chal['chal_score']];

	tail:
	Templater::json($res);