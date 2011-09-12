<?php

/**
 * php multi curl class.
 * @author   Martins Balodis <martins256@gmail.com>
 * @category Curl_Base_Class
 * @package  Curl
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/martinsbalodis/php-curl-multi-oop
 */
Class Curl_Multi {
	
	/**
	 * Multi Curl Handle
	 * @var resource
	 */
	private $mch;
	
	/**
	 * Limit of handles being executed at the same time
	 * @var integer
	 */
	protected $handle_limit = 5;

	/**
	 * Curl jobs to execute or being executed
	 * @var Curl[]
	 */
	private $jobs = array();
	
	/**
	 * Jobs that are waiting in the line to be executed.
	 * @var type 
	 */
	private $jobs_in_queue = array();

	/**
	 * Add new job
	 * @param Curl $curl
	 */
	public function add_job(Curl_MultiReady $curl) {
		
		// Add jobs for execution
		if(count($this->jobs) <= $this->handle_limit) {
			
			$this->jobs[] = $curl;
			curl_multi_add_handle($this->mch, $curl->get_handle());
			
		} 
		// Add jobs to wait in line
		else {
			
			$this->jobs_in_queue[] = $curl;
			
		}
		
	}
	
	/**
	 * Constructor. Initializes multi curl handle
	 */
	public function __construct() {
			$this->mch = curl_multi_init();
			curl_multi_select($this->mch);
	}

	/**
	 * Execute the handles
	 */
	public function exec() {
		
		// Multi curl is active
		$active = null;
		
		// execute the handles
		do {
			$mrc = curl_multi_exec($this->mch, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
		if($mrc != CURLM_OK) {
			throw new Exception('Something went wrong!');
		}
		
		// Execute the handles
		while ($active && $mrc == CURLM_OK) {
			
			// Wait for activity on any curl_multi connection
			if (curl_multi_select($this->mch) != -1) {
				do {
					
					// Run the sub-connections of the current cURL handle
					$mrc = curl_multi_exec($this->mch, $active);
					
					// Read execution statuses.
					while ($info = curl_multi_info_read($this->mch, $msgs_in_queue)) {

						// Find corresponding resource. 
						foreach($this->jobs as $job) {

							/* @var $job Curl_MultiReady */
							if($job->get_handle() === $info['handle']) {

								// handle found
								$job->executed();

								break;
							}
						}
					}
					
					// @TODO add new jobs from queue

				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
			else {
				throw new Exception('cURL select failure or timeout.');
			}
		}
	}
}
