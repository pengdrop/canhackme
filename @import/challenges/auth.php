<?php
	if(isset($_POST['auth-flag'], $_POST['flag'])){
		if(Challenges::is_valid_chal_flag($_POST['flag']) && ($chal = Challenges::get_chal_by_chal_flag($_POST['flag'])) !== false){
			if(Challenges::is_solved_chal($chal['chal_no'])){
				$res = ['result' => 'already_solved', 'chal_name' => $chal['chal_name'], 'chal_title' => $chal['chal_title']];
			}else if(Challenges::do_solve_chal($chal['chal_no'], $chal['chal_score'])){
				$res = ['result' => 'solved', 'chal_name' => $chal['chal_name'], 'chal_title' => $chal['chal_title'], 'chal_score' => $chal['chal_score']];
			}else{
				$res = ['result' => 'error'];
			}
		}else{
			$res = ['result' => 'incorrect'];
		}
	}else{
		$res = ['result' => 'error'];
	}
	Templater::json($res);