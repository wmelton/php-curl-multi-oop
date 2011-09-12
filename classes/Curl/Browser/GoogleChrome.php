<?php

/**
 * Simulates google chrome browser for requests
 * @author   Martins Balodis <martins256@gmail.com>
 * @category Curl_Browser_Class
 * @package  Curl
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/martinsbalodis/php-curl-multi-oop
 */
class Curl_Browser_GoogleChrome extends Curl {
	
	public function __construct() {
		
		// initialize curl
		parent::__construct();
		
		// Sent headers will be saved
		$this->setopt(CURLINFO_HEADER_OUT, true);
		
		// Chrome user agent
		$this->setopt(CURLOPT_USERAGENT, "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.5 Safari/535.2");
		
		// Follow redirects. This is limited to 4 redirects
		$this->setopt(CURLOPT_FOLLOWLOCATION, true);
		$this->setopt(CURLOPT_MAXREDIRS, 4);
		
		// The contents of the "Accept-Encoding: " header. This enables decoding of the response. Supported encodings are "identity", "deflate", and "gzip". If an empty string, "", is set, a header containing all supported encoding types is sent. 
		$this->setopt(CURLOPT_ENCODING, '');
	}
}
?>
