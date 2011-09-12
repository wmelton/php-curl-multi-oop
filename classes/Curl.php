<?php

/**
 * php curl functions wrapped in a class
 * @author   Martins Balodis <martins256@gmail.com>
 * @category Curl_Base_Class
 * @package  Curl
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/martinsbalodis/php-curl-multi-oop
 */
class Curl {

	/** 	
	 * Curl Handle
	 * @var 
	 */
	private $ch;

	/**
	 * Initializes curl handler
	 */
	public function __construct() {
		
		// Initializes Curl handler
		$this->ch = curl_init();
		
		// Result will be returned not outputed
		$this->setopt(CURLOPT_RETURNTRANSFER, true);
	}

	// @TODO Jāizveido iespēja rezultātu uzreiz saglabāt failā uz fopen handle

	/**
	 * Set curl parameter
	 * @param integer $option Curl constant
	 * @see http://php.net/manual/en/function.curl-setopt.php
	 * @param mixed $value 
	 */
	public function setopt($option, $value) {

		curl_setopt($this->ch, $option, $value);
	}

	/**
	 * Sets data request method
	 * @param string $method 
	 */
	public function set_method($method) {
		
		switch ($method) {
			case 'POST' : curl_setopt($this->ch, CURLOPT_POST, true);
				break;
			case 'GET' : curl_setopt($this->ch, CURLOPT_HTTPGET, TRUE);
				break;
			default : throw new Exception('Invalid request method. ' . htmlspecialchars($method), 1);
		}
	}

	/**
	 * Set post parameter string.
	 * Data will be sent as post.
	 * @param string $post_string 
	 */
	public function set_post_string($post_string) {
		
		$this->setopt(CURLOPT_POSTFIELDS, $post_string);
	}

	/**
	 * Set execution url. This can be also set in exec method.
	 * @param string $url 
	 */
	public function set_url($url) {
		
		curl_setopt($this->ch, CURLOPT_URL, $url);
	}

	/**
	 * Execute request
	 * @param string $url 
	 * @return string
	 */
	public function exec($url = null) {
		
		// sets execution url if it is supplied
		if ($url !== null) {
			$this->set_url($url);
		}

		// Executes the request
		$result = curl_exec($this->ch);

		return $result;
	}

	/**
	 * Atgriež headerus kādi tika nosūtīti uz serveri
	 * @return string 
	 */
	public function get_headers_sent() {
		
		return curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
	}
	
	/**
	 * Returns curl handle.
	 * @return type 
	 */
	public function get_handle() {
		return $this->ch;
	}
}