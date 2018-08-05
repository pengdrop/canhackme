<?php
	if(isset($_POST['name'])){
		if(!Users::is_valid_user_name($_POST['name'])){
			$res = ['result' => 'invalid'];
		}else if(Users::is_exists_user_name($_POST['name'])){
			$res = ['result' => 'exists'];
		}else{
			$res = ['result' => 'valid'];
		}
	}else{
		$res = ['result' => 'error'];
	}
	Templater::json($res);