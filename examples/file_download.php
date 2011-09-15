<?php

/**
 * This example tries to download files 
 */
require 'autoload.php';

/**
 * This class is for a single file download
 */
class Downloadable_File extends Curl implements Curl_MultiReady {
	
	/**
	 * Directory where to download file
	 * @var string 
	 */
	public $directory = '../downloads/';
	
	
	/**
	 * Extend set url method.
	 * When usr is set download it into a file
	 * @param type $url 
	 */
	public function set_url($url) {
		
		$filename = basename($url);
		$this->save_into_file($this->directory.$filename);
		
		parent::set_url($url);
	}
	
	/**
	 * Executed when file downloaded
	 */
	public function executed() {
		
	}
}

// list of files to download
$files = array(
		'http://www.bildites.lv/images/thjoo72thgbe1zchm29.jpg',
		'http://www.bildites.lv/images/ldkdbsfypmu02h8s256.jpg',
		'http://www.bildites.lv/images/icujtts8m2xpm9j4cvzo.jpg',
		'http://www.bildites.lv/images/5lno9l1sncshe2f8gos.jpg',
		'http://www.bildites.lv/images/d4qgj1lbv1nnf81ahtb.jpg',
		);

$multi_curl = new Curl_Multi();

// Add files to queue
foreach($files as $file) {
	
	$file_to_download = new Downloadable_File();
	$file_to_download->set_url($file);
	$multi_curl->add_job($file_to_download);
}

// Download all files at the same time.
$multi_curl->exec();