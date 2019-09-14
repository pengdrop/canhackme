<?php
	#version_compare(PHP_VERSION, '7.3.0', '>=') or die('Not supported PHP version (now: '.PHP_VERSION.'). Please install <code>PHP 7.3</code>.');

	ini_set('display_errors', 'off');

	ini_set('session.name', 'session');
	ini_set('session.cookie_httponly', '1');
	ini_set('session.sid_length', '50');
	ini_set('session.sid_bits_per_character', '6');
	session_start();

	define('__CSP_NONCE__', base64_encode(random_bytes(20)));
	header('X-Content-Type-Options: nosniff');
	header('X-Frame-Options: deny');
	header('X-XSS-Protection: 1; mode=block');
	header('Content-Security-Policy: base-uri \'self\'; script-src \'nonce-'.__CSP_NONCE__.'\';');

	require __DIR__.'/confs/common.php';

	######################################################################################################################

	if(__IS_DEBUG__){
		error_reporting(E_ALL);
		ini_set('display_errors', 'on');
	}

	date_default_timezone_set('UTC');

	$need_init = !is_file(__DIR__.'/confs/.common.db');

	$db = new SQLite3(__DIR__.'/confs/.common.db');
	$db->createFunction('HASH', function(string $value){
		return hash('sha256', $value.__HASH_SALT__);
	});

	if($need_init){
		if(($init_sql = file_get_contents(__DIR__.'/confs/init.sql')) === false){
			die('Can\'t found SQL file for initialize database. (path: <code>/confs/init.sql</code>)');
		}
		$db->query($init_sql);
		unset($init_sql);
	}
	unset($need_init);

	Templater::init();
	Users::init();
	Challenges::init();

	######################################################################################################################

	function is_use_recaptcha(): bool{
		return isset(__SITE__['use_recaptcha'], __SITE__['recaptcha_sitekey'], __SITE__['recaptcha_secretkey']) && 
			__SITE__['use_recaptcha'] === true && 
			is_string(__SITE__['recaptcha_sitekey']) &&
			is_string(__SITE__['recaptcha_secretkey']);
	}
	function is_valid_recaptcha_token($token): bool{
		if(!is_string($token) || !isset($token{0})){
			return false;
		}
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = [
			'secret' => __SITE__['recaptcha_secretkey'], 
			'response' => $token,
		];
		$options = [
			'http' => [
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'method' => 'POST',
				'content' => http_build_query($data),
			]
		];
		$context = stream_context_create($options);
		$response = file_get_contents($url, false, $context);
		$responseKeys = json_decode($response, true);
		return isset($responseKeys['success']) && is_bool($responseKeys['success']) ? $responseKeys['success'] : false;
	}
	function email_encode(string $email): string{
		// html encode some characters (at, dot).
		return strtr(htmlentities($email), ['@' => '&#64;', '.' => '&#46;']);
	}
	function get_challenge_shortcut_page_url(string $chal_name): string{
		return '/challenges/@'.urlencode(strtolower($chal_name));
	}
	function get_challenge_tag_page_url(string $chal_name): string{
		return '/challenges/tag/'.urlencode(strtolower($chal_name));
	}
	function get_user_profile_page_url(string $user_name): string{
		return '/users/@'.urlencode(strtolower($user_name));
	}
	function get_user_profile_image_url(string $user_email, int $size = 64): string{
		$token = md5($user_email);
		return 'https://www.gravatar.com/avatar/'.$token.'?'.
			http_build_query([
				'd' => 'https://github.com/identicons/'.$token.'.png',
				's' => $size,
			]);
	}
	function is_admin_user_name(string $user_name): bool{
		return in_array(strtolower($user_name), __ADMIN__, true);
	}
	function highlight_keyword(string $content, string $keyword): string{
		if(!isset($keyword{0})){
			return $content;
		}
		return preg_replace('/('.preg_quote($keyword, '/').')/i', '<span class="text-info">$1</span>', $content);
	}

	######################################################################################################################

	class Templater{
		private static $url_path;
		public static function init(){
			self::$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		}
		public static function get_url_path(): string{
			return self::$url_path;
		}
		public static function route(string $regex, array $methods, &$args = null): bool{
			return in_array($_SERVER['REQUEST_METHOD'], $methods, true) && preg_match($regex, self::get_url_path(), $args);
		}
		public static function import(string $file, $args = null){
			include __DIR__.'/'.$file.'.php';
		}
		public static function error(int $status = 404){
			$_SERVER['REDIRECT_STATUS'] = $status;
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				self::import('views/error/error');
			}else{
				self::import('apis/error/error');
			}
			die;
		}
		public static function redirect(string $url){
			if(!headers_sent()){
				header('Location: '.$url);
			}
			die('<meta http-equiv="refresh" content="0;url='.htmlentities($url).'"></meta>');
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
		public static function resource(string $link, bool $is_full_url = false): string{
			$parsed_url = parse_url($link);

			// If the link is external URL, return it as it is.
			if(isset($parsed_url['scheme']) || isset($parsed_url['host'])) return $link;

			$url_prefix = $is_full_url === true ? rtrim(__SITE__['url'], '/') : '';
			$url_path = $parsed_url['path'];
			$file_path = realpath($_SERVER['DOCUMENT_ROOT'].'/'.$url_path);
			$url_query = $file_path !== false ? '?v='.filemtime($file_path) : '';
			return $url_prefix.$url_path.$url_query;
		}
		public static function markbb(string $value): string{
			$value = htmlentities($value);
			$value = strtr($value, ["\r" => '', "\n" => '<br>']);

			$value = preg_replace('#\[b\](.*?)\[/b\]#s', '<b>$1</b>', $value);
			$value = preg_replace('#__(.*?)__#s', '<b>$1</b>', $value);
			$value = preg_replace('#\*\*(.*?)\*\*#s', '<b>$1</b>', $value);

			$value = preg_replace('#\[u\](.*?)\[/u\]#s', '<u>$1</u>', $value);
			$value = preg_replace('#\+\+(.*?)\+\+#s', '<u>$1</u>', $value);

			$value = preg_replace('#\[i\](.*?)\[/i\]#s', '<i>$1</i>', $value);
			$value = preg_replace('#\[em\](.*?)\[/em\]#s', '<em>$1</em>', $value);
			$value = preg_replace('#\*(.*?)\*#s', '<em>$1</em>', $value);

			$value = preg_replace('#\[s\](.*?)\[/s\]#s', '<s>$1</s>', $value);
			$value = preg_replace('#~~(.*?)~~#s', '<s>$1</s>', $value);

			$value = preg_replace('#\[quote\](.*?)\[/quote\]#s', '<blockquote>$1</blockquote>', $value);
			$value = preg_replace('#\>(.*?)(\r?\n)#s', '<blockquote>$1</blockquote>$2', $value);

			$value = preg_replace('#\[mark\](.*?)\[/mark\]#s', '<mark>$1</mark>', $value);
			$value = preg_replace('#==(.*?)==#s', '<mark>$1</mark>', $value);

			$value = preg_replace('#```(.*?)```#s', '<pre><code>$1</code></pre>', $value);

			$value = preg_replace('#\[code\](.*?)\[/code\]#s', '<code>$1</code>', $value);
			$value = preg_replace('#`(.*?)`#s', '<code>$1</code>', $value);

			$value = preg_replace('#\[pre\](.*?)\[/pre\]#s', '<pre>$1</pre>', $value);

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
			return $value;
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
		public static function is_signed(): bool{
			return self::$is_signed;
		}
		public static function get_my_user(string $column = '*'){
			if(self::$is_signed !== true){
				return false;
			}
			if($column === '*'){
				return self::$my_user;
			}else if(isset(self::$my_user[$column])){
				return self::$my_user[$column];
			}else{
				return false;
			}
		}
		public static function get_unsigned_token(): string{
			return base64_encode(sha1(json_encode([$_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]), true));
		}
		public static function get_signed_token(){
			return isset($_SESSION['user_no']) ? 
				base64_encode(sha1(json_encode([$_SESSION['user_no'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], __HASH_SALT__]), true)) : 
				false;
		}
		public static function get_user_by_name(string $user_name, string $column = '*', bool $is_case_sensitive = false){
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
			$stmt->bindParam(':user_name', $user_name);
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
			$stmt->bindParam(':user_no', $user_no);
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
		public static function get_solv_count_by_user_no(int $user_no){
			global $db;
			$stmt = $db->prepare("
				SELECT
					COUNT(*) AS `solv_count`
				FROM
					`solvs`
				WHERE
					`solv_user_no`=:user_no
			");
			$stmt->bindParam(':user_no', $user_no);
			$res = $stmt->execute();
			if($res === false) return false;
			$solv = $res->fetchArray(SQLITE3_ASSOC);
			if($solv === false) return false;
			return $solv['solv_count'];
		}
		public static function get_users(int $limit_start = 0, int $limit_end = 24){
			global $db;
			$stmt = $db->prepare('
				SELECT
					*
				FROM
					`users`
				ORDER BY
					`user_no` DESC
				LIMIT
					:limit_start, :limit_end
			');
			$stmt->bindParam(':limit_start', $limit_start);
			$stmt->bindParam(':limit_end', $limit_end);
			$res = $stmt->execute();
			if($res === false) return false;
			$users = [];
			while($user = $res->fetchArray(SQLITE3_ASSOC)) $users[] = $user;
			return $users;
		}
		public static function is_valid_user_name($user_name): bool{
			return is_string($user_name) && preg_match('/\A[a-zA-Z0-9_-]{5,20}\z/', $user_name);
		}
		public static function is_valid_user_url($user_url): bool{
			return is_string($user_url) && preg_match('/\A(https?\:\/\/|\/\/).+\z/i', $user_url) && filter_var($user_url, FILTER_VALIDATE_URL);
		}
		public static function is_valid_user_email($user_email): bool{
			return is_string($user_email) && filter_var($user_email, FILTER_VALIDATE_EMAIL);
		}
		public static function is_valid_user_comment($user_comment): bool{
			return is_string($user_comment) && ($len = mb_strlen($user_comment)) !== false && 0 <= $len && $len <= 50;
		}
		public static function is_valid_user_password($user_password): bool{
			return is_string($user_password) && ($len = mb_strlen($user_password)) !== false && 6 <= $len && $len <= 50;
		}
		public static function is_exists_user_name(string $user_name): bool{
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
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindValue(':user_no', self::get_my_user('user_no'));
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			return true;
		}
		public static function is_exists_user_email(string $user_email): bool{
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
			$stmt->bindParam(':user_email', $user_email);
			$stmt->bindValue(':user_no', self::get_my_user('user_no'));
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			return true;
		}
		public static function do_sign_in(string $user_name, string $user_password): bool{
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
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':user_password', $user_password);
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
		public static function do_sign_up(string $user_name, string $user_email, string $user_password, string $user_comment): bool{
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
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':user_email', $user_email);
			$stmt->bindParam(':user_password', $user_password);
			$stmt->bindParam(':user_comment', $user_comment);
			$res = $stmt->execute();
			if($res === false) return false;
			return true;
		}
		public static function do_sign_out(){
			unset($_SESSION['user_no'], $_SESSION['signed_token']);
		}
		public static function get_user_count(string $keyword = ''){
			global $db;
			$stmt = $db->prepare('
				SELECT
					COUNT(*) AS `count`
				FROM
					`users`
				WHERE
					INSTR(LOWER(`user_name`), LOWER(:keyword)) OR INSTR(LOWER(`user_comment`), LOWER(:keyword))
			');
			$stmt->bindParam(':keyword', $keyword);
			$res = $stmt->execute();
			if($res === false) return false;
			$user = $res->fetchArray(SQLITE3_ASSOC);
			if($user === false) return false;
			return $user['count'];
		}
		public static function update_my_user(string $user_name, string $user_email, string $user_password, string $user_comment): bool{
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
			$stmt->bindParam(':user_name', $user_name);
			$stmt->bindParam(':user_email', $user_email);
			$stmt->bindParam(':user_password', $user_password);
			$stmt->bindParam(':user_comment', $user_comment);
			$stmt->bindValue(':user_no', Users::get_my_user('user_no'));
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
		public static function is_valid_chal_flag($chal_flag): bool{
			return is_string($chal_flag) && preg_match('/\A[a-zA-Z0-9_]+?\{[a-zA-Z0-9_]{10,50}\}\z/', $chal_flag);
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
			$stmt->bindParam(':chal_flag', $chal_flag);
			$res = $stmt->execute();
			if($res === false) return false;
			$chal = $res->fetchArray(SQLITE3_ASSOC);
			if($chal === false) return false;
			return $chal;
		}
		public static function get_solved_chals(int $user_no, bool $get_first_solver = false){
			global $db;
			$select = $get_first_solver ? '(SELECT `u`.`user_no` FROM `solvs`, `users` AS `u` WHERE `solv_chal_no`=`chal_no` AND `solv_user_no`=`u`.`user_no` ORDER BY `solv_no` ASC LIMIT 1) AS `chal_first_solver`,' : '0 AS `chal_first_solver`,';
			$stmt = $db->prepare("
				SELECT
					`chal_name`,
					`chal_title`,
					`chal_score`,
					{$select}
					`solv_solved_at` AS `chal_solved_at`
				FROM
					`solvs`,
					`chals`
				WHERE
					`solv_chal_no`=`chal_no` AND `solv_user_no`=:solv_user_no
				ORDER BY
					`solv_no` ASC,
					`chal_score` ASC,
					`chal_no` ASC
			");
			$stmt->bindParam(':solv_user_no', $user_no);
			$res = $stmt->execute();
			if($res === false) return false;
			$chals = [];
			while($chal = $res->fetchArray(SQLITE3_ASSOC)) $chals[] = $chal;
			return $chals;
		}
		public static function is_solved_chal(int $chal_no): bool{
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
			$stmt->bindValue(':solv_user_no', Users::get_my_user('user_no'));
			$stmt->bindParam(':solv_chal_no', $chal_no);
			$res = $stmt->execute();
			if($res === false) return false;
			$chal = $res->fetchArray(SQLITE3_ASSOC);
			if($chal === false) return false;
			return true;
		}
		public static function do_solve_chal(int $chal_no, int $chal_score): bool{
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
			$stmt->bindValue(':solv_user_no', Users::get_my_user('user_no'));
			$stmt->bindParam(':solv_chal_no', $chal_no);
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
			$stmt->bindValue(':user_no', Users::get_my_user('user_no'));
			$stmt->bindParam(':score', $chal_score);
			$res = $stmt->execute();
			if($res === false) return false;
			return true;
		}
		public static function get_chal_count_and_score(){
			global $db;
			$stmt = $db->prepare("
				SELECT
					COUNT(*) AS `count`,
					SUM(`chal_score`) AS `score`
				FROM
					`chals`
			");
			$res = $stmt->execute();
			if($res === false) return false;
			$solv = $res->fetchArray(SQLITE3_ASSOC);
			if($solv === false) return false;
			return $solv;
		}
		public static function get_chals(string $chal_tag = 'all'){
			$query_column = Users::is_signed() ? 'EXISTS(SELECT 1 FROM `solvs` WHERE `solv_chal_no`=`chal_no` AND `solv_user_no`='.Users::get_my_user('user_no').' LIMIT 1)' : '0';
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
					{$query_column} AS `chal_is_solved`,
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
					(SELECT `solv_solved_at` FROM `solvs` WHERE `solv_user_no`=`user_no` ORDER BY `solv_no` DESC LIMIT 1) ASC
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
		public static function get_ranks(string $keyword = '', int $limit_start = 0, int $limit_end = 30){
			global $db;
			$stmt = $db->prepare('
				SELECT
					`user_no`,
					`user_name`,
					`user_comment`,
					`user_score`,
					(SELECT `solv_solved_at` FROM `solvs` WHERE `solv_user_no`=`user_no` ORDER BY `solv_no` DESC LIMIT 1) AS `user_last_solved_at`
				FROM
					`users`
				WHERE
					INSTR(LOWER(`user_name`), LOWER(:keyword)) OR INSTR(LOWER(`user_comment`), LOWER(:keyword))
				ORDER BY
					`user_score` DESC,
					`user_last_solved_at` ASC
				LIMIT
					:limit_start, :limit_end
			');
			$stmt->bindParam(':keyword', $keyword);
			$stmt->bindParam(':limit_start', $limit_start);
			$stmt->bindParam(':limit_end', $limit_end);
			$res = $stmt->execute();
			if($res === false) return false;
			$ranks = [];
			while($rank = $res->fetchArray(SQLITE3_ASSOC)) $ranks[] = $rank;
			return $ranks;
		}
		public static function get_solvs(string $keyword = '', int $limit_start = 0, int $limit_end = 30){
			global $db;
			$stmt = $db->prepare('
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
					`user_no`=`solv_user_no` AND `chal_no`=`solv_chal_no` AND
					(INSTR(LOWER(`user_name`), LOWER(:keyword)) OR INSTR(LOWER(`chal_title`), LOWER(:keyword)))
				ORDER BY
					`solv_no` DESC
				LIMIT
					:limit_start, :limit_end
			');
			$stmt->bindParam(':keyword', $keyword);
			$stmt->bindParam(':limit_start', $limit_start);
			$stmt->bindParam(':limit_end', $limit_end);
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
		public static function get_solv_count(string $keyword = ''){
			global $db;
			$stmt = $db->prepare('
				SELECT
					COUNT(*) AS `solv_count`
				FROM
					`solvs`,
					`chals`,
					`users`
				WHERE
					`user_no`=`solv_user_no` AND `chal_no`=`solv_chal_no` AND
					(INSTR(LOWER(`user_name`), LOWER(:keyword)) OR INSTR(LOWER(`chal_title`), LOWER(:keyword)))
			');
			$stmt->bindParam(':keyword', $keyword);
			$res = $stmt->execute();
			if($res === false) return false;
			$solv = $res->fetchArray(SQLITE3_ASSOC);
			if($solv === false) return false;
			return $solv['solv_count'];
		}
	}

	class Notifications{
		public static function get_notis(int $limit_start = 0, int $limit_end = 24){
			global $db;
			$stmt = $db->prepare('
				SELECT
					*
				FROM
					`notis`
				ORDER BY
					`noti_no` DESC
				LIMIT
					:limit_start, :limit_end
			');
			$stmt->bindParam(':limit_start', $limit_start);
			$stmt->bindParam(':limit_end', $limit_end);
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