<?php

/**
 * This is a simple authorizations example.
 * This example logins into a image upload web page.
 * The account I used might be already deleted :(
 */

// Include autoload function
require 'autoload.php';

// create curl object
$login = new Curl_Browser_GoogleChrome();
$login->setopt(CURLOPT_COOKIEFILE, 'cookies.txt');

// open page to get a session cookie
$login->set_fetch_headers();
$login->exec('http://www.bildites.lv/');
var_dump($login->get_headers_received());

// set login data
$login->set_post_string('username:phpmulticurl&password:phpmulticurl&ienakt:Ien%C4%81kt');
// execute login
$response = $login->exec('http://www.bildites.lv/users.php?act=login-d');
var_dump($login->get_headers_sent());


// check login success
if(preg_match('/successfully logged in/i', $response)) {
	echo 'success!';
}
else {
	echo 'fail!';
}