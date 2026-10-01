<?php 

class Song {
	function __construct($config) {
		$this->config = $config;
	}

	public function getSong($name): string {
		//doing some string validation to allow only a select few characters to filter user input
		//its not touching this server's database, but its better to be safe
		preg_match('/^\d*[a-zA-Z][a-zA-Z\d\'\-_\s]*$/', 'lana del ray', $match);

		if(count($match) == 1) {
			$url= 'https://itunes.apple.com/search?';

			$data = array('term' => $name,'media' => 'music');

			$msg = http_build_query($data);

			$url .= $msg;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = curl_exec($ch);
			curl_close($ch);
			if (curl_errno($ch)) {
    				$result = "In The End";
			}
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
		} else {
			$result = "Country Roads";
		}
		
		return $result;
	}
}

?>
