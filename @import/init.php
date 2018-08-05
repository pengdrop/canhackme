<?php
	ini_set('display_errors', 'off');

	ini_set('session.name', 'session');
	ini_set('session.cookie_httponly', '1');
	ini_set('session.sid_length', '50');
	ini_set('session.sid_bits_per_character', '6');
	session_start();

	header('X-XSS-Protection: 1; mode=block');

	require __DIR__.'/config.php';

	######################################################################################################################

	if(__IS_DEBUG__){
		error_reporting(E_ALL);
		ini_set('display_errors', 'on');
	}

	date_default_timezone_set('UTC');

	$do_init = !is_file(__DB_FILE__);
	$db = new SQLite3(__DB_FILE__);
	$db->createFunction('HASH', function($value){
		return hash('sha256', $value.__HASH_SALT__);
	});
	if($do_init){
		$db->query(file_get_contents(__DIR__.'/init.sql'));
	}
	unset($do_init);

	Templater::init();
	Users::init();
	Challenges::init();

	######################################################################################################################

	class Templater{
		private static $url_path;
		public static function init(){
			self::$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		}
		public static function get_url_path(){
			return self::$url_path;
		}
		public static function route(string $regex, array $methods, &$args = null){
			return in_array($_SERVER['REQUEST_METHOD'], $methods, true) && preg_match($regex, self::get_url_path(), $args);
		}
		public static function render(string $file, $args = null){
			include __DIR__.'/'.$file.'.php';
		}
		public static function error(string $status = '404'){
			$_SERVER['REDIRECT_STATUS'] = $status;
			include __DIR__.'/common/error.php';
			die;
		}
		public static function redirect(string $url){
			if(headers_sent()){
				echo '<meta http-equiv="refresh" content="0;url=', $url, '"></meta>';
			}else{
				header('Location: '.$url);
			}
			die;
		}
		public static function json(array $data){
			if(!headers_sent()){
				header('Content-Type: application/json; charset=utf-8');
			}
			die(json_encode($data));
		}
	}

	######################################################################################################################

	class Data{
		public static function text($value){
			echo htmlentities($value, ENT_QUOTES, 'UTF-8');
		}
		public static function url($value){
			echo urlencode($value);
		}
		public static function timestamp($value){
			echo strtotime($value);
		}
		public static function email($value){
			echo strtr($value, ['@' => '&#64;', '.' => '&#46;']);
		}
		public static function resource($link){
			$url_path = parse_url($link, PHP_URL_PATH);
			$file_path = realpath($_SERVER['DOCUMENT_ROOT'].'/'.$url_path);
			$url_query = $file_path !== false ? '?v='.filemtime($file_path) : '';
			echo $url_path.$url_query;
		}
		public static function markbb($value){
			$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
			$value = strtr($value, ["\r" => '', "\n" => '<br>']);

			$value = preg_replace('#\[b\](.*?)\[/b\]#s', '<b>$1</b>', $value);
			$value = preg_replace('#__(.*?)__#s', '<b>$1</b>', $value);
			$value = preg_replace('#\*\*(.*?)\*\*#s', '<b>$1</b>', $value);

			$value = preg_replace('#\[u\](.*?)\[/u\]#s', '<u>$1</u>', $value);
			$value = preg_replace('#\+\+(.*?)\+\+#s', '<u>$1</u>', $value);

			$value = preg_replace('#\[i\](.*?)\[/i\]#s', '<i>$1</i>', $value);
			$value = preg_replace('#\*(.*?)\*#s', '<i>$1</i>', $value);

			$value = preg_replace('#\[s\](.*?)\[/s\]#s', '<s>$1</s>', $value);
			$value = preg_replace('#~~(.*?)~~#s', '<s>$1</s>', $value);

			$value = preg_replace('#\[quote\](.*?)\[/quote\]#s', '<blockquote>$1</blockquote>', $value);
			$value = preg_replace('#\>(.*?)(\r?\n)#s', '<blockquote>$1</blockquote>$2', $value);

			$value = preg_replace('#\[mark\](.*?)\[/mark\]#s', '<mark>$1</mark>', $value);
			$value = preg_replace('#==(.*?)==#s', '<mark>$1</mark>', $value);

			$value = preg_replace('#\[code\](.*?)\[/code\]#s', '<code>$1</code>', $value);
			$value = preg_replace('#`(.*?)`#s', '<code>$1</code>', $value);

			$value = preg_replace('#\[pre\](.*?)\[/pre\]#s', '<pre>$1</pre>', $value);
			$value = preg_replace('#```(.*?)```#s', '<pre><code>$1</code></pre>', $value);

			$value = preg_replace('#\[file\](.*?)\[/file\]#s', '<a href="$1" download>$1</a>', $value);
			$value = preg_replace('#\[file=(.*?)\](.*?)\[/file\]#s', '<a href="$1" download>$2</a>', $value);

			$value = preg_replace('#\[img\](.*?)\[/img\]#s', '<img src="$1" alt>', $value);
			$value = preg_replace('#\!\[(.*?)\]\((.*?)\)#s', '<img src="$2" alt="$1">', $value);
			$value = preg_replace('#\[img=(.*?)\](.*?)\[/img\]#s', '<img src="$2" alt="$1">', $value);

			$value = preg_replace('#\[(.*?)\]\((\/.*?)\)#s', '<a href="$2">$1</a>', $value);
			$value = preg_replace('#\[(.*?)\]\((.*?)\)#s', '<a href="$2" target="_blank">$1</a>', $value);
			$value = preg_replace('#\[url=(\/.*?)\](.*?)\[/url\]#s', '<a href="$1">$2</a>', $value);
			$value = preg_replace('#\[url=(.*?)\](.*?)\[/url\]#s', '<a href="$1" target="_blank">$2</a>', $value);

			$value = preg_replace('#\&lt;(\/.*?)\&gt;#s', '<a href="$1">$1</a>', $value);
			$value = preg_replace('#\&lt;(.*?)\&gt;#s', '<a href="$1" target="_blank">$1</a>', $value);
			$value = preg_replace('#\[url\](\/.*?)\[/url\]#s', '<a href="$1">$1</a>', $value);
			$value = preg_replace('#\[url\](.*?)\[/url\]#s', '<a href="$1" target="_blank">$1</a>', $value);
			echo $value;
		}
		public static function profile_image($user_email, $size = 48){
			$token = md5($user_email);
			echo 'https://www.gravatar.com/avatar/'.$token.'?s='.$size.'&d=https://github.com/identicons/'.$token.'.png';
		}
	}

	######################################################################################################################

	class Users{
		private static $is_signed;
		private static $my_user;
		public static function init(){
			self::$is_signed = isset($_SESSION['user_no'], $_SESSION['signed_token']) && $_SESSION['signed_token'] === self::get_signed_token();
			if(self::$is_signed){
				if((self::$my_user = self::get_user_by_user_no($_SESSION['user_no'])) === false){
					self::$is_signed = self::$my_user = false;
				}
			}else{
				self::$my_user = false;
			}
		}
		public static function is_signed(){
			return self::$is_signed;
		}
		public static function get_my_user(string $column = '*'){
			if(self::$is_signed === true){
				if($column === '*'){
					return self::$my_user;
				}else if(isset(self::$my_user[$column])){
					return self::$my_user[$column];
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		public static function get_guest_token(){
			return sha1($_SERVER['REMOTE_ADDR'].'|'.$_SERVER['HTTP_USER_AGENT'].'|'.__HASH_SALT__);
		}
		public static function get_signed_token(){
			return isset($_SESSION['user_no']) ? sha1($_SESSION['user_no'].'|'.$_SERVER['REMOTE_ADDR'].'|'.$_SERVER['HTTP_USER_AGENT'].'|'.__HASH_SALT__) : false;
		}
		public static function get_user(string $user_name, string $column = '*', bool $is_case_sensitive = false){
			$where_option = $is_case_sensitive ? '' : 'COLLATE NOCASE';
			global $db;
			$stmt = $db->prepare("
				SELECT
					*
				FROM
					`users`
				WHERE
					`user_name`=:user_name {$where_option}
				LIMIT
					1
			");
			$stmt->bindValue(':user_name', $user_name, SQLITE3_TEXT);
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;

			if($column === '*'){
				return $user;
			}else if(isset($user[$column])){
				return $user[$column];
			}else{
				return false;
			}
		}
		public static function get_user_by_user_no(int $user_no, string $column = '*'){
			global $db;
			$stmt = $db->prepare('
				SELECT
					*
				FROM
					`users`
				WHERE
					`user_no`=:user_no
				LIMIT
					1
			');
			$stmt->bindValue(':user_no', $user_no, SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;

			if($column === '*'){
				return $user;
			}else if(isset($user[$column])){
				return $user[$column];
			}else{
				return false;
			}
		}
		public static function get_new_users(int $count = 30){
			global $db;
			$stmt = $db->prepare("
				SELECT
					*
				FROM
					`users`
				ORDER BY
					`user_no` DESC
				LIMIT
					{$count}
			");
			$res = $stmt->execute();
			if($res === false) return false;
			$users = [];
			while($user = $res->fetchArray(SQLITE3_ASSOC)) $users[] = $user;
			return $users;
		}
		public static function is_valid_user_name($user_name){
			return is_string($user_name) && preg_match('/\A[a-zA-Z0-9_-]{5,20}\z/', $user_name);
		}
		public static function is_valid_user_email($user_email){
			return is_string($user_email) && filter_var($user_email, FILTER_VALIDATE_EMAIL);
		}
		public static function is_valid_user_comment($user_comment){
			return is_string($user_comment) && ($len = mb_strlen($user_comment)) !== false && 0 <= $len && $len <= 50;
		}
		public static function is_valid_user_password($user_password){
			return is_string($user_password) && ($len = mb_strlen($user_password)) !== false && 6 <= $len && $len <= 50;
		}
		public static function is_exists_user_name(string $user_name){
			global $db;
			$stmt = $db->prepare('
				SELECT
					1
				FROM
					`users`
				WHERE
					`user_name`=:user_name COLLATE NOCASE AND `user_no`!=:user_no
				LIMIT
					1
			');
			$stmt->bindValue(':user_name', $user_name, SQLITE3_TEXT);
			$stmt->bindValue(':user_no', self::get_my_user('user_no'), SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			return true;
		}
		public static function is_exists_user_email(string $user_email){
			global $db;
			$stmt = $db->prepare('
				SELECT
					1
				FROM
					`users`
				WHERE
					`user_email`=:user_email COLLATE NOCASE AND `user_no`!=:user_no
				LIMIT
					1
			');
			$stmt->bindValue(':user_email', $user_email, SQLITE3_TEXT);
			$stmt->bindValue(':user_no', self::get_my_user('user_no'), SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			return true;
		}
		public static function do_sign_in(string $user_name, string $user_password){
			global $db;
			$stmt = $db->prepare('
				SELECT
					`user_no`
				FROM
					`users`
				WHERE
					`user_name`=:user_name COLLATE NOCASE AND `user_password`=HASH(:user_password)
				LIMIT
					1
			');
			$stmt->bindValue(':user_name', $user_name, SQLITE3_TEXT);
			$stmt->bindValue(':user_password', $user_password, SQLITE3_TEXT);
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			if(session_regenerate_id(true) === false) return false;
			$_SESSION['user_no'] = $user['user_no'];
			$_SESSION['signed_token'] = self::get_signed_token();
			self::init();
			return true;
		}
		public static function do_sign_up(string $user_name, string $user_email, string $user_password, string $user_comment){
			global $db;
			$stmt = $db->prepare('
				INSERT INTO
					`users` 
					(
						`user_name`, `user_email`, `user_password`, `user_comment`, `user_score`
					) 
				VALUES
					(
						:user_name, :user_email, HASH(:user_password), :user_comment, 0
					)
			');
			$stmt->bindValue(':user_name', $user_name, SQLITE3_TEXT);
			$stmt->bindValue(':user_email', $user_email, SQLITE3_TEXT);
			$stmt->bindValue(':user_password', $user_password, SQLITE3_TEXT);
			$stmt->bindValue(':user_comment', $user_comment, SQLITE3_TEXT);
			$res = $stmt->execute();
			if($res === false) return false;
			return true;
		}
		public static function do_sign_out(){
			unset($_SESSION['user_no'], $_SESSION['signed_token']);
		}
		public static function get_user_count(){
			global $db;
			$stmt = $db->prepare('
				SELECT
					COUNT(*) AS `count`
				FROM
					`users`
			');
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			return $user['count'];
		}
		public static function update_my_user(string $user_name, string $user_email, string $user_password, string $user_comment){
			global $db;
			$stmt = $db->prepare('
				UPDATE
					`users`
				SET
					`user_name`=:user_name,
					`user_email`=:user_email,
					`user_password`=HASH(:user_password),
					`user_comment`=:user_comment
				WHERE
					`user_no`=:user_no
			');
			$stmt->bindValue(':user_name', $user_name, SQLITE3_TEXT);
			$stmt->bindValue(':user_email', $user_email, SQLITE3_TEXT);
			$stmt->bindValue(':user_password', $user_password, SQLITE3_TEXT);
			$stmt->bindValue(':user_comment', $user_comment, SQLITE3_TEXT);
			$stmt->bindValue(':user_no', Users::get_my_user('user_no'), SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			self::init();
			return true;
		}
	}

	######################################################################################################################

	class Challenges{
		private static $chal_tags;
		public static function init(){
			global $db;
			$stmt = $db->prepare('
				SELECT
					GROUP_CONCAT(`chal_tags`, ",") AS `chal_tags`
				FROM
					`chals`
			');
			$res = $stmt->execute();
			if($res !== false){
				$chal = $res->fetchArray(SQLITE3_ASSOC);
				if($chal !== false){
					self::$chal_tags = array_unique(explode(',', $chal['chal_tags']));
				}else{
					self::$chal_tags = false;
				}
			}else{
				self::$chal_tags = false;
			}
		}
		public static function get_chal_tags(){
			return self::$chal_tags;
		}
		public static function is_valid_chal_flag($chal_flag){
			return is_string($chal_flag) && preg_match('/\ACanHackMe\{[a-zA-Z0-9_]{10,50}\}\z/', $chal_flag);
		}
		public static function get_chal_by_chal_flag(string $chal_flag){
			global $db;
			$stmt = $db->prepare('
				SELECT
					*
				FROM
					`chals`
				WHERE
					`chal_flag`=:chal_flag
				LIMIT
					1
			');
			$stmt->bindValue(':chal_flag', $chal_flag, SQLITE3_TEXT);
			$res = $stmt->execute();
			if($res === false) return false;
			$chal = $res->fetchArray(SQLITE3_ASSOC);
			if($chal === false) return false;
			return $chal;
		}
		public static function get_solved_chals(int $user_no){
			global $db;
			$stmt = $db->prepare("
				SELECT
					`chal_name`,
					`chal_title`,
					`chal_score`,
					`solv_solved_at` AS `chal_solved_at`
				FROM
					`solvs`,
					`chals`
				WHERE
					`solv_chal_no`=`chal_no` AND `solv_user_no`=:solv_user_no
				ORDER BY
					`solv_solved_at` ASC,
					`chal_score` ASC,
					`chal_no` ASC
			");
			$stmt->bindValue(':solv_user_no', $user_no, SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			$chals = [];
			while($chal = $res->fetchArray(SQLITE3_ASSOC)) $chals[] = $chal;
			return $chals;
		}
		public static function is_solved_chal(int $chal_no){
			global $db;
			$stmt = $db->prepare('
				SELECT
					1
				FROM
					`solvs`
				WHERE
					`solv_user_no`=:solv_user_no AND `solv_chal_no`=:solv_chal_no
				LIMIT
					1
			');
			$stmt->bindValue(':solv_user_no', Users::get_my_user('user_no'), SQLITE3_INTEGER);
			$stmt->bindValue(':solv_chal_no', $chal_no, SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			$chal = $res->fetchArray(SQLITE3_ASSOC);
			if($chal === false) return false;
			return true;
		}
		public static function do_solve_chal(int $chal_no, int $chal_score){
			global $db;
			$stmt = $db->prepare('
				INSERT INTO `solvs`
				(
					`solv_user_no`, `solv_chal_no`
				)
				VALUES
				(
					:solv_user_no, :solv_chal_no
				)
			');
			$stmt->bindValue(':solv_user_no', Users::get_my_user('user_no'), SQLITE3_INTEGER);
			$stmt->bindValue(':solv_chal_no', $chal_no, SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;

			$stmt = $db->prepare('
				UPDATE
					`users`
				SET
					`user_score`=`user_score`+:score
				WHERE
					`user_no`=:user_no
			');
			$stmt->bindValue(':user_no', Users::get_my_user('user_no'), SQLITE3_INTEGER);
			$stmt->bindValue(':score', $chal_score, SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			return true;
		}
		public static function get_chals(string $chal_tag = 'all'){
			$query_where = strcasecmp($chal_tag, 'all') ? 'AND INSTR(","||`chal_tags`||",", ",'.$chal_tag.',")' : '';
			global $db;
			$stmt = $db->prepare("
				SELECT
					`chal_name`,
					`chal_title`,
					`chal_contents`,
					`chal_score`,
					`chal_tags`,
					`chal_uploaded_at`,
					`user_name` AS `chal_author`,
					EXISTS(SELECT 1 FROM `solvs` WHERE `solv_chal_no`=`chal_no` AND `solv_user_no`=:solv_user_no LIMIT 1) AS `chal_is_solved`,
					(SELECT COUNT(*) FROM `solvs` WHERE `solv_chal_no`=`chal_no`) AS `chal_solvers`,
					(SELECT `u`.`user_name` FROM `solvs`, `users` AS `u` WHERE `solv_chal_no`=`chal_no` AND `solv_user_no`=`u`.`user_no` ORDER BY `solv_no` ASC LIMIT 1) AS `chal_first_solver`
				FROM
					`chals`,
					`users`
				WHERE
					`chal_user_no`=`user_no`
					{$query_where}
				ORDER BY
					`chal_score` ASC,
					`chal_no` ASC
			");
			$stmt->bindValue(':solv_user_no', Users::get_my_user('user_no'), SQLITE3_INTEGER);
			$res = $stmt->execute();
			if($res === false) return false;
			$chals = [];
			while($chal = $res->fetchArray(SQLITE3_ASSOC)) $chals[] = $chal;
			return $chals;
		}
		public static function get_rank_by_user_no(int $user_no){
			global $db;
			$stmt = $db->prepare('
				SELECT
					`user_no`
				FROM
					`users`
				ORDER BY
					`user_score` DESC,
					(SELECT `solv_solved_at` FROM `solvs` WHERE `solv_user_no`=`user_no` ORDER BY `solv_solved_at` DESC LIMIT 1) ASC,
					`user_signed_up_at` ASC
			');
			$res = $stmt->execute();
			if($res === false) return false;
			$i = 0;
			$ranks = [];
			while($rank = $res->fetchArray(SQLITE3_ASSOC)){
				++$i;
				if($rank['user_no'] === $user_no) return $i;
			}
			return false;
		}
		public static function get_ranks(int $count = 30){
			global $db;
			$stmt = $db->prepare("
				SELECT
					`user_name`,
					`user_comment`,
					`user_score`,
					(SELECT `solv_solved_at` FROM `solvs` WHERE `solv_user_no`=`user_no` ORDER BY `solv_solved_at` DESC LIMIT 1) AS `user_last_solved_at`
				FROM
					`users`
				ORDER BY
					`user_score` DESC,
					`user_last_solved_at` ASC,
					`user_signed_up_at` ASC
				LIMIT
					{$count}
			");
			$res = $stmt->execute();
			if($res === false) return false;
			$ranks = [];
			while($rank = $res->fetchArray(SQLITE3_ASSOC)) $ranks[] = $rank;
			return $ranks;
		}
		public static function get_new_solves(int $count = 30){
			global $db;
			$stmt = $db->prepare("
				SELECT
					`solv_no`,
					`user_name` AS `solv_user_name`,
					`chal_name` AS `solv_chal_name`,
					`chal_title` AS `solv_chal_title`,
					`chal_score` AS `solv_chal_score`,
					`solv_solved_at`
				FROM
					`solvs`,
					`chals`,
					`users`
				WHERE
					`user_no`=`solv_user_no` AND `chal_no`=`solv_chal_no`
				ORDER BY
					`solv_no` DESC
				LIMIT
					{$count}
			");
			$res = $stmt->execute();
			if($res === false) return false;
			$solvs = [];
			while($solv = $res->fetchArray(SQLITE3_ASSOC)) $solvs[] = $solv;
			return $solvs;
		}
		public static function get_chal_count(){
			global $db;
			$stmt = $db->prepare('
				SELECT
					COUNT(*) AS `chal_count`
				FROM
					`chals`
			');
			$res = $stmt->execute();
			if($res === false) return false;
			$chal = $res->fetchArray(SQLITE3_ASSOC);
			if($chal === false) return false;
			return $chal['chal_count'];
		}
		public static function get_solv_count(int $user_no = 0){
			$query_where = $user_no > 0 ? 'WHERE `solv_user_no`="'.$user_no.'"' : '';
			global $db;
			$stmt = $db->prepare("
				SELECT
					COUNT(*) AS `solv_count`
				FROM
					`solvs`
				{$query_where}
			");
			$res = $stmt->execute();
			if($res === false) return false;
			$solv = $res->fetchArray(SQLITE3_ASSOC);
			if($solv === false) return false;
			return $solv['solv_count'];
		}
	}

	class Notices{
		public static function get_new_notis(int $count = 30){
			global $db;
			$stmt = $db->prepare("
				SELECT
					*
				FROM
					`notis`
				ORDER BY
					`noti_no` DESC
				LIMIT
					{$count}
			");
			$res = $stmt->execute();
			if($res === false) return false;
			$notis = [];
			while($noti = $res->fetchArray(SQLITE3_ASSOC)) $notis[] = $noti;
			return $notis;
		}
		public static function get_noti_count(){
			global $db;
			$stmt = $db->prepare('
				SELECT
					COUNT(*) AS `noti_count`
				FROM
					`notis`
			');
			$res = $stmt->execute();
			if($res === false) return false;
			$noti = $res->fetchArray(SQLITE3_ASSOC);
			if($noti === false) return false;
			return $noti['noti_count'];
		}
	}