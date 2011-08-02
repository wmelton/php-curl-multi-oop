<?php
/**
 * Multi curl usage
 */
require 'autoload.php';

/**
 * Create class which uses curl
 */
class Curl_Tvnet extends Curl implements Curl_MultiReady {
	
	private static $counter = 1;
	
	public function __construct() {

		parent::__construct();
		
		$sleep_time = mt_rand(1,6);
		
		$this->set_url('http://phpcurl/test_pages/test_page_sleep.php?sleep='.$sleep_time.'&extra='.self::$counter++.'_'.$sleep_time);

	}

	public function executed() {
		echo curl_multi_getcontent($this->get_handle());
		echo ' received at - '.xdebug_time_index().'<br />';
	}
}

// create two instances to download test pages
$test1 = new Curl_Tvnet();
$test2 = new Curl_Tvnet();
$test3 = new Curl_Tvnet();
$test4 = new Curl_Tvnet();

$multi = new Curl_Multi();
$multi->add_job($test1);
$multi->add_job($test2);
$multi->add_job($test3);
$multi->add_job($test4);

$start_time = xdebug_time_index();
$multi->exec();

echo '<br/>total execution - '.(xdebug_time_index()-$start_time);