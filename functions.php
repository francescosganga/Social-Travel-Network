<?php
include("external/PHPMailer/PHPMailerAutoload.php");
//@ini_set("display_errors", 1);
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

	public function queryTrips($city, $country) {
		$trips = Array();

		if($city == "")
			$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}trips WHERE country = \"{$country}\"") or die($this->MySQLi->error);
		else
			$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}trips WHERE city = \"{$city}\" and country = \"{$country}\"") or die($this->MySQLi->error);

		while($r = $q->fetch_array(MYSQLI_ASSOC))
			$trips[] = $r;

		return $trips;
	}

	public function pageNotFound() {
		http_response_code(404);
		$this->parseHTMLContent();
		$this->templateHeader("{lang['not-found-title']}");
		?>
		<div class="dashboard_content">
			<div class="row">
				<div class="col-md-12">
					{lang['not-found-string']}
				</div>
			</div>
		</div>
		<?php
		$this->templateFooter();
		die();
	}

	public function userData($username_id) {
		if(is_int($username_id))
			$q = $this->MySQLi->query("SELECT id, name, surname, city, username, avatar, privacy FROM {$this->config['mysql']['table_prefix']}users WHERE id=\"{$username_id}\"") or die($this->MySQLi->error);
		else
			$q = $this->MySQLi->query("SELECT id, name, surname, city, username, avatar, privacy FROM {$this->config['mysql']['table_prefix']}users WHERE username=\"{$username_id}\"") or die($this->MySQLi->error);
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
			$content = str_replace("{{google_gaid}}", $this->config['google_gaid'], $content);

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
			
			if(!$this->sendMail(Array($email, "{$name} {$surname}"), $this->language['mail-verification-subject'], $this->language['mail-verification-content'])) 
				return false;
			
			return true;
		} else {
			return false;
		}
	}

	public function userResendVerificationMail($user_id) {
		$user_id = $this->escapeString($user_id);
		$q = $this->MySQLi->query("SELECT name, surname, email, verify_hash FROM {$this->config['mysql']['table_prefix']}users WHERE id={$user_id}") or die($this->MySQLi->error);

		if(!$q->num_rows)
			return false;

		$r = $q->fetch_array(MYSQLI_ASSOC);

		$this->language['mail-verification-content'] = str_replace("{{url}}", $this->config['site_url'], $this->language['mail-verification-content']);
		$this->language['mail-verification-content'] = str_replace("{{username}}", $r['username'], $this->language['mail-verification-content']);
		$this->language['mail-verification-content'] = str_replace("{{verify_hash}}", $r['verify_hash'], $this->language['mail-verification-content']);
			
		if(!$this->sendMail(Array($r['email'], "{$r['name']} {$r['surname']}"), $this->language['mail-verification-subject'], $this->language['mail-verification-content'])) 
			return false;

		return true;
	}

	public function sendLastTripNotify() {
		$q = $this->MySQLi->query("SELECT id, title FROM {$this->config['mysql']['table_prefix']}trips ORDER BY id DESC LIMIT 1");
		$trip = $q->fetch_array(MYSQLI_ASSOC);

		$q = $this->MySQLi->query("SELECT username, name, surname, email FROM {$this->config['mysql']['table_prefix']}users LIMIT 5") or die($this->MySQLi->error);
		while($r = $q->fetch_array(MYSQLI_ASSOC)) {
			$trip_url = strtolower(str_replace(" ", "-", "https://www.amiciviaggiando.it/viaggi/{$trip['title']}-id{$trip['id']}/"));
			$this->language['mail-notify-trip-content'] = str_replace("{{username}}", $r['username'], $this->language['mail-notify-trip-content']);
			$this->language['mail-notify-trip-content'] = str_replace("{{trip_url}}", $trip_url, $this->language['mail-notify-trip-content']);

			if(!$this->sendMail(Array($r['email'], "{$r['name']} {$r['surname']}"), $this->language['mail-notify-trip-subject'], $this->language['mail-notify-trip-content']))
				return false;
		}

		return true;
	}

	public function getTripImage($city, $country) {
		$city = strtolower($city);
		$country = strtolower($country);

		if(file_exists("{$this->config['site_path']}/assets/images/trips/{$city}.jpg"))
			return "{$this->config['site_url']}/assets/images/trips/{$city}.jpg";
		elseif(file_exists("{$this->config['site_path']}/assets/images/trips/{$country}.jpg"))
			return "{$this->config['site_url']}/assets/images/trips/{$country}.jpg";
		else
			return "{$this->config['site_url']}/assets/images/placeholder.jpg";
	}

	public function redirect($path = null) {
		if($path == null)
			header("Location: " . $_SERVER['REQUEST_URI']);
		else
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

			$x1 = $centreX - 250;
			$y1 = $centreY - 250;

			$x2 = $centreX + 250;
			$y2 = $centreY + 250; 

			$im->crop($x1, $y1, $x2, $y2);
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

	public function tripUpdate($trip_id, $values) {
		$query = Array();
		foreach($values as $key => $value) {
			$query[] = "{$key} = \"{$value}\"";
		}

		$query = implode(", ", $query);

		$q = $this->MySQLi->query("UPDATE {$this->config['mysql']['table_prefix']}trips SET {$query} WHERE id={$trip_id}") or die($this->MySQLi->error);

		return $q;
	}

	public function userLoggedIn() {
		if(isset($_SESSION['login_hash']) and $this->checkLoginHash($_SESSION['login_hash']))
			return $this->checkLoginHash($_SESSION['login_hash']);

		return false;
	}

	public function getTrips($user_id = NULL, $LIMIT = "0, 10") {
		$trips = Array();
		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}trips ORDER BY id DESC LIMIT {$LIMIT}") or die($this->MySQLi->error);

		while($r = $q->fetch_array(MYSQLI_ASSOC)) {
			$r['slug'] = str_replace(" ", "-", strtolower($r['title'])) . "-id" . $r['id'];
			if($user_id == NULL) {
				$trips[] = $r;
			} else {
				$r['partecipants'] = unserialize($r['partecipants']);
				if(array_search((int)$user_id, $r['partecipants']) !== false)
					$trips[] = $r;
			}
		}

		return $trips;

	}

	public function badWordsFilter($content) {
		return $content;
	}

	public function currentUserInsertTrip($title, $description, $from_city, $to, $when) {
		if(empty($title) or empty($description) or empty($from_city) or empty($to) or empty($when))
			return false;

		$user_id = $this->currentUser['id'];
		$title = $this->badWordsFilter($title);
		$description = $this->badWordsFilter($description);
		$when = DateTime::createFromFormat($this->config['date_format'], $when);
		$date = $when->getTimestamp();
		$partecipants = $this->escapeString(serialize(Array($user_id)));
		$q = $this->MySQLi->query("INSERT INTO {$this->config['mysql']['table_prefix']}trips (user_id, title, description, from_city, city, country, date, partecipants) VALUES ({$user_id}, \"{$title}\", \"{$description}\", \"{$from_city}\", \"{$to['city']}\", \"{$to['country']}\", \"{$date}\", \"{$partecipants}\")") or die($this->MySQLi->error);

		return $q;
	}

	public function getTripComments($trip_id) {
		$comments = Array();

		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}comments WHERE trip_id = {$trip_id} ORDER BY time DESC") or die($this->MySQLi->error);

		if($q->num_rows) {
			while($r = $q->fetch_array(MYSQLI_ASSOC)) {
				$comment = $r;
				$comment['time'] = date("{$this->config['date_format']} {$this->config['time_format']}", $comment['time']);
				$comment['userData'] = $this->userData((int)$comment['user_id']);
				$comments[] = $comment;
			}
		} else {
			return false;
		}

		return $comments;
	}

	public function tripData($trip_id, $partecipantsUnserialize = true) {
		$q = $this->MySQLi->query("SELECT * FROM {$this->config['mysql']['table_prefix']}trips WHERE id = {$trip_id} LIMIT 1") or die($this->MySQLi->error);

		if(!$q->num_rows)
			return false;

		$tripData = $q->fetch_array(MYSQLI_ASSOC);
		
		if($partecipantsUnserialize == true) {
			$tripData['partecipants'] = unserialize($tripData['partecipants']);
			$partecipants = Array();

			foreach($tripData['partecipants'] as $partecipant) {
				$partecipants[] = $this->userData((int)$partecipant);
			}

			$tripData['partecipants'] = $partecipants;
			$tripData['partecipants']['total'] = count($partecipants);
		}

		return $tripData;
	}

	public function currentUserCommentTrip($trip_id, $comment) {
		$time = time();
		$q = $this->MySQLi->query("INSERT INTO {$this->config['mysql']['table_prefix']}comments (trip_id, user_id, comment, time) VALUES ({$trip_id}, {$this->currentUser['id']}, \"{$comment}\", \"{$time}\")") or die($this->MySQLi->error);

		return true;
	}

	public function currentUserPartecipateToTrip($trip_id) {
		$tripData = $this->tripData($trip_id, false);
		$tripData['partecipants'] = unserialize($tripData['partecipants']);
		$tripData['partecipants'][] = (int)$this->currentUser['id'];

		$this->tripUpdate($trip_id, Array('partecipants' => $this->escapeString(serialize($tripData['partecipants']))));

		return true;
	}

	public function currentUserUnpartecipateToTrip($trip_id) {
		$tripData = $this->tripData($trip_id, false);
		$tripData['partecipants'] = unserialize($tripData['partecipants']);
		if(($key = array_search($this->currentUser['id'], $tripData['partecipants'])) !== false)
			unset($tripData['partecipants'][$key]);

		$this->tripUpdate($trip_id, Array('partecipants' => $this->escapeString(serialize($tripData['partecipants']))));

		return true;
	}

	public function checkUserPartecipateToTrip($user_id, $trip_id) {
		$tripData = $this->tripData($trip_id);
		foreach($tripData['partecipants'] as $partecipant) {
			if($partecipant['id'] == (int)$user_id)
				return true;
		}

		return false;
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
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = $this->config['mail_host'];
		$mail->SMTPAuth = true;
		$mail->Username = $this->config['mail_username'];
		$mail->Password = $this->config['mail_password'];
		
		if($this->config['mail_port'] == 587)
			$mail->SMTPSecure = 'tls';
		elseif($this->config['mail_port'] == 465)
			$mail->SMTPSecure = 'ssl';
		
		$mail->Port = $this->config['mail_port'];

		$mail->setFrom($this->config['mail_username'], $this->config['site_name']);
		$mail->addAddress($to[0], $to[1]);
		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		if(!$mail->send())
		    return false;
		else
		    return true;
	}

	public function escapeString($string) {
		return $this->MySQLi->real_escape_string($string);
	}

	public function updateSitemap() {
		include("external/SitemapGenerator.php");
		$generator = new \Icamys\SitemapGenerator\SitemapGenerator($this->config['site_url']);

		$generator->createGZipFile = false;
		$generator->maxURLsPerSitemap = 50000;
		$generator->sitemapFileName = "sitemap.xml";

		//already known urls
		$generator->addUrl("/", new DateTime(), 'always', '0.5');
		$generator->addUrl("/dashboard/", new DateTime(), 'always', '0.5');
		$generator->addUrl("/registrazione/", new DateTime(), 'always', '0.5');

		//add profile urls
		$q = $this->MySQLi->query("SELECT username FROM {$this->config['mysql']['table_prefix']}users") or die($this->MySQLi->error);
		while($r = $q->fetch_array(MYSQLI_ASSOC))
			$generator->addUrl("/profilo/{$r['username']}/", new DateTime(), 'always', '0.5');

		//add trip urls
		$q = $this->MySQLi->query("SELECT id, title FROM {$this->config['mysql']['table_prefix']}trips") or die($this->MySQLi->error);
		while($r = $q->fetch_array(MYSQLI_ASSOC)) {
			$trip_slug = str_replace(" ", "-", strtolower($r['title'])) . "-id" . $r['id'];
			$generator->addUrl("/viaggi/{$trip_slug}/", new DateTime(), 'always', '0.5');
		}

		$generator->createSitemap();
		$generator->writeSitemap();
	}
}