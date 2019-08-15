<?php
session_start();

class AV {
	private $config;
	private $MySQLi;

	public $currentUser = false;
	public $language;

	public function __construct() {
		include("config.inc.php");
		$this->config = $config;

		$this->MySQLi = new mysqli($config['mysql']['host'], $config['mysql']['username'], $config['mysql']['password'], $config['mysql']['database']) or die($this->MySQLi->error);

		//set options vars
		$q = $this->MySQLi->query("SELECT option_name, option_value FROM {$this->config['mysql']['table_prefix']}options");
		while($r = $q->fetch_array(MYSQLI_ASSOC))
			$this->config[$r['option_name']] = $r['option_value'];

		//check if language file exists
		if(file_exists("{$this->config['site_path']}/languages/{$this->config['site_language']}.php")) {
			include("{$this->config['site_path']}/languages/{$this->config['site_language']}.php");
			$this->language = $lang;
		} else {
			die("Language file not exists.");
		}

		if($this->userLoggedIn() != false) {
			$this->currentUser = $this->currentUser();
		} else {
		}
	}

	public function userData($username_id) {
		if(is_int($username_id))
			$q = $this->MySQLi->query("SELECT id, name, surname, city, username, avatar FROM {$this->config['mysql']['table_prefix']}users WHERE id=\"{$username_id}\"") or die($this->MySQLi->error);
		else
			$q = $this->MySQLi->query("SELECT id, name, surname, city, username, avatar FROM {$this->config['mysql']['table_prefix']}users WHERE username=\"{$username_id}\"") or die($this->MySQLi->error);
		if(!$q->num_rows)
			return false;

		return $q->fetch_array(MYSQLI_ASSOC);
	}

	public function templateHeader($title, $description = "", $css = "") {
		$custom_css = "";
		if(is_array($css)) {
			foreach($css as $cssfile)
				$custom_css .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{{url}}/assets/css/{$cssfile}.css\" />\n";
		}
		include("header.php");
	}

	public function templateFooter($javascript = NULL) {
		$custom_js = "";
		if(is_array($javascript)) {
			foreach($javascript as $jsfile)
				$custom_js .= "<script type=\"text/javascript\" src=\"{{url}}/assets/js/{$jsfile}.js\"></script>";
		}
		include("footer.php");
	}

	public function parseHTMLContent() {
		ob_start(function($content) {
			$content = str_replace("{{url}}", $this->config['site_url'], $content);
			$content = str_replace("{{website_name}}", $this->config['site_name'], $content);
			$content = str_replace("{{google_apikey}}", $this->config['google_apikey'], $content);

			//language system
			$content = preg_replace_callback("/\{lang\[\'(.*?)\'\]\}/", function($matches) {
				if(isset($this->language[$matches[1]]))
					return $this->language[$matches[1]];
				else
					return $matches[1];
			}, $content);

			if($this->currentUser != false) {
				$content = preg_replace_callback("/\{user\[\'(.*?)\'\]\}/", function($matches) {
					return $this->currentUser[$matches[1]];
				}, $content);
			}

			return $content;
		});
	}

	public function logout() {
		session_destroy();
	}

	public function userRegistration($name, $surname, $city, $username, $password, $email, $phone) {
		$alreadyExists = false;
		$q = $this->MySQLi->query("SELECT id FROM {$this->config['mysql']['table_prefix']}users WHERE email=\"{$email}\" or phone=\"{$phone}\" or username=\"{$username}\"");
		if($q->num_rows)
			$alreadyExists = true;

		if(!$alreadyExists) {
			$verifyHash = md5($name . $username . $city . $username . $password . $email . $phone . time());
			$q = $this->MySQLi->query("INSERT INTO {$this->config['mysql']['table_prefix']}users (name, surname, city, username, password, email, phone, avatar, verified, verify_hash) VALUES (\"{$name}\", \"{$surname}\", \"{$city}\", \"{$username}\", \"{$password}\", \"{$email}\", \"{$phone}\", \"assets/images/no-avatar.png\", 0, \"{$verifyHash}\")") or die($this->MySQLi->error);

			$this->language['mail-verification-content'] = str_replace("{{url}}", $this->config['site_url'], $this->language['mail-verification-content']);
			$this->language['mail-verification-content'] = str_replace("{{username}}", $username, $this->language['mail-verification-content']);
			$this->language['mail-verification-content'] = str_replace("{{verify_hash}}", $verifyHash, $this->language['mail-verification-content']);

			if(!$q)
				return false;
			elseif(!$this->sendMail($email, $this->language['mail-verificaiton-subject'], $this->language['mail-verification-content']))
				return false;
			else
				return true;
		} else {
			return false;
		}
	}

	public function redirect($path) {
		header("Location: " . $this->config['site_url'] . $path);
	}

	public function checkLoginHash($loginHash) {
		$q = $this->MySQLi->query("SELECT id FROM {$this->config['mysql']['table_prefix']}users WHERE login_hash = \"{$loginHash}\"") or die($this->MySQLi->error);
		if($q->num_rows) {
			$r = $q->fetch_array(MYSQLI_ASSOC);
			return $r['id'];
		}

		return false;
	}

	public function uploadImage($image) {
		$allowed_extensions = array("jpg");
		$file_ext = strtolower(end(explode('.', $image['name'])));
		if(in_array($file_ext, $allowed_extensions) === false)
			return false;
		if($image['size'] > 2097152)
			return false;

		$image['name'] = "image-" . time() . ".{$file_ext}";
		if(move_uploaded_file($image['tmp_name'], "{$this->config['site_path']}/assets/images/uploads/{$image['name']}")) {
			$filename = "{$this->config['site_path']}/assets/images/uploads/{$image['name']}";
			
			include("external/ImageManipulator.php");
			$im = new ImageManipulator($filename);
			$centreX = round($im->getWidth() / 2);
			$centreY = round($im->getHeight() / 2);

			$x1 = $centreX - 150;
			$y1 = $centreY - 150;

			$x2 = $centreX + 150;
			$y2 = $centreY + 150;

			$im->crop($x1, $y1, $x2, $y2); // takes care of out of boundary conditions automatically
			$im->save($filename);

			return "assets/images/uploads/{$image['name']}";
		}
		else
			return false;
	}

	public function userUpdate($values) {
		$query = Array();
		foreach($values as $key => $value) {
			$query[] = "{$key} = \"{$value}\"";
		}

		$query = implode(", ", $query);

		$q = $this->MySQLi->query("UPDATE {$this->config['mysql']['table_prefix']}users SET {$query} WHERE id={$this->currentUser['id']}") or die($this->MySQLi->error);

		return $q;
	}

	public function userLoggedIn() {
		if(isset($_SESSION['login_hash']) and $this->checkLoginHash($_SESSION['login_hash']))
			return $this->checkLoginHash($_SESSION['login_hash']);

		return false;
	}

	public function getTrips($user_id) {
		$trips = Array();

		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}trips WHERE user_id = {$user_id}") or die($this->MySQLi->error);

		while($r = $q->fetch_array(MYSQLI_ASSOC))
			$trips[] = $r;

		return $trips;

	}

	public function tripData($trip_id) {
		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}trips WHERE id = {$trip_id}") or die($this->MySQLi->error);

		if(!$q->num_rows)
			return false;

		$tripData = $q->fetch_array(MYSQLI_ASSOC);
		$tripData['partecipants'] = unserialize($tripData['partecipants']);
		$partecipants = Array();

		foreach($tripData['partecipants'] as $key => $value) {
			$userData = $this->userData($value);
			$partecipants[$key] = $userData;
		}

		$tripData['partecipants'] = $partecipants;
		$tripData['partecipants']['total'] = count($partecipants);

		return $tripData;
	}

	public function currentUser() {
		$id = $this->userLoggedIn();
		if($id == false)
			return false;

		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}users WHERE id=\"{$id}\"") or die($this->MySQLi->error);
		return $q->fetch_array(MYSQLI_ASSOC);
	}

	public function setSession($session_name, $session_value) {
		$_SESSION[$session_name] = $session_value;
		return true;
	}

	public function verifyUser($verifyHash) {
		$q = $this->MySQLi->query("SELECT id FROM {$this->config['mysql']['table_prefix']}users WHERE verify_hash=\"{$verifyHash}\"") or die($this->MySQLi->error);
		if($q->num_rows) {
			$q = $this->MySQLi->query("UPDATE {$this->config['mysql']['table_prefix']}users SET verified = \"1\" WHERE verify_hash=\"{$verifyHash}\"") or die($this->MySQLi->error);

			return true;
		}

		return false;
	}

	public function login($username, $password) {
		$q = $this->MySQLi->query("SELECT id FROM {$this->config['mysql']['table_prefix']}users WHERE username=\"{$username}\" and password=\"{$password}\" and verified = \"1\"") or die($this->MySQLi->error);
		if($q->num_rows) {
			$r = $q->fetch_array(MYSQLI_ASSOC);
			$login_hash = md5($username . time());
			$q = $this->MySQLi->query("UPDATE {$this->config['mysql']['table_prefix']}users SET last_login = \"" . time() . "\", login_hash = \"{$login_hash}\" WHERE id=\"{$r['id']}\"") or die($this->MySQLi->error);

			return $login_hash;
		} else
			return false;
	}

	public function sendMail($to, $subject, $message) {
		return mail($to, $subject, $message);
	}

	public function escapeString($string) {
		return $this->MySQLi->real_escape_string($string);
	}
}