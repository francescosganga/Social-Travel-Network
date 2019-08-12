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

		if($this->userLoggedIn()) {
			$this->currentUser = $this->currentUser();
		}
	}

	public function userData($username) {
		$q = $this->MySQLi->query("SELECT name, surname, city, username, avatar FROM {$this->config['mysql']['table_prefix']}users WHERE username=\"{$username}\"") or die($this->MySQLi->error);
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

			if(!$q)
				return false;
			elseif(!$this->sendMail($email, "Conferma Indirizzo Email", "Hash per verificare: {$verifyHash}"))
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

	public function userLoggedIn() {
		if(isset($_SESSION['loggedId']))
			return true;

		return false;
	}

	public function currentUser() {
		if(!$this->userLoggedIn())
			return false;
		$id = $_SESSION['loggedId'];
		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}users WHERE id=\"{$id}\"") or die($this->MySQLi->error);
		return $q->fetch_array(MYSQLI_ASSOC);
	}

	public function setSession($username, $password) {
		$q = $this->MySQLi->query("SELECT id FROM {$this->config['mysql']['table_prefix']}users WHERE username=\"{$username}\" and password=\"{$password}\"") or die($this->MySQLi->error);
		$r = $q->fetch_array(MYSQLI_ASSOC);
		$_SESSION['loggedId'] = $r['id'];
		return true;
	}

	public function login($username, $password) {
		$q = $this->MySQLi->query("SELECT id FROM {$this->config['mysql']['table_prefix']}users WHERE username=\"{$username}\" and password=\"{$password}\"") or die($this->MySQLi->error);
		if($q->num_rows) {
			$r = $q->fetch_array(MYSQLI_ASSOC);
			$q = $this->MySQLi->query("UPDATE {$this->config['mysql']['table_prefix']}users SET last_login = \"" . time() . "\" WHERE id=\"{$r['id']}\"") or die($this->MySQLi->error);
			return true;
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