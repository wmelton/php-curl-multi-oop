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
	protected $ch;
	
	/**
	 * Fetch received headers
	 * @var boolean
	 */
	protected $fetch_headers = false;
	
	/**
	 * Received headers after execution
	 * @var string 
	 */
	protected $headers_received;

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
		
		// Received headers must be retrieved
		if($this->fetch_headers) {
			
			// set curl to return headers
			$this->setopt(CURLOPT_HEADER, true);
			
			// Executes the request
			$result = curl_exec($this->ch);
			
			// set curl to NOT return headers
			$this->setopt(CURLOPT_HEADER, false);
			
			// Seperate header from body
			echo $result;
			$pos = strpos("\n\n", $result);
			
			if($pos === false) {
				throw new Exception('No headers received!', 2);
			}
			
			$this->headers_received = substr($result, 0, $pos);
			
			$result = substr($result, $pos+4, strlen($result)-$pos-4);
			
		}
		else {
			// Executes the request
			$result = curl_exec($this->ch);
		}

		

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
	 * Set to fetch received headers.
	 * This must be set before executing request via exec method
	 * afterwars use get_headers_received method
	 */
	public function set_fetch_headers() {
		
		$this->fetch_headers = true;
	}
	
	public function get_headers_receved() {
		return $this->headers_received;
	}
	
	/**
	 * Returns curl handle.
	 * @return type 
	 */
	public function get_handle() {
		return $this->ch;
	}
	
	/**
	 * File download
	 * Save results into a file
	 * @param string $filename 
	 */
	public function save_into_file($filename) {
		
		$file_handle = fopen($filename,'c+x+');
		
		$this->setopt(CURLOPT_FILE, $file_handle);
	}
}