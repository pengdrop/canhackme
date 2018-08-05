<?php
	if(isset($_POST['email'])){
		if(!Users::is_valid_user_email($_POST['email'])){
			$res = ['result' => 'invalid'];
		}else if(Users::is_exists_user_email($_POST['email'])){
			$res = ['result' => 'exists'];
		}else{
			$res = ['result' => 'valid'];
		}
	}else{
		$res = ['result' => 'error'];
	}
	Templater::json($res);