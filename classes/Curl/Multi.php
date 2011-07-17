<?php
Class Curl_Multi {
	/**
	 * Multi Curl Handle
	 * @var
	 */
	private $mch;

	/**
	 * Izpildāmie uzdevumi
	 * @var Curl[]
	 */
	private $jobs = array();

	/**
	 * Pievieno darbu izpildei
	 * @param Curl $curl
	 */
	public function add_job(Curl_MultiReady $curl) {

		$this->jobs = $curl;
		curl_multi_add_handle($this->mch, $curl->get_handle());
	}

	public function __construct() {
		$this->mch = curl_multi_init();
		curl_multi_select($this->mch);
	}

	public function exec() {
		$start = xdebug_time_index();
		echo xdebug_time_index() . '<br />';
		$active = null;
		//execute the handles
		do {
			$mrc = curl_multi_exec($this->mch, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);

		echo xdebug_time_index() - $start . '<br />';

		while ($active && $mrc == CURLM_OK) {
			if (curl_multi_select($this->mch) != -1) {
				do {
					$mrc = curl_multi_exec($this->mch, $active);

					while ($info = curl_multi_info_read($this->mch,
							$msgs_in_queue)) {
						var_dump($info);

						var_dump(curl_multi_getcontent($info['handle']));
					}

				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}

//		$running=null;
//
//
//
//
//		do {
//			curl_multi_exec($this->mch,$running);
//			// Apstādina ciklu kamēr kāds cURL uzpildās
//			$ready=curl_multi_select($this->mch);
//
//			if($ready>0)
//			{
//				echo $ready;
//				var_dump(curl_multi_info_read($this->mch));
//
////				// Ir izpildījušies pieprasījumi. Šeit tiek iegūti to rezultāti
////				while($info=curl_multi_info_read($this->mch,$msgs_in_queue))
////				{
////					var_dump($info);
////
////					// Piemeklē rezultātam
////					foreach($this->jobs as $key=>$job)
////					{
////						/* @var $job Curl */
////						if($job->get_handle()===$info['handle'])
////						{
////							$content = curl_multi_getcontent($info['handle']);
////							$job->executed($content);
////							break;
////						}
////					}
////
//////					$status=curl_getinfo($info['handle'],CURLINFO_HTTP_CODE);
//////					if($status==200){
//////						$successUrl=curl_getinfo($info['handle'],CURLINFO_EFFECTIVE_URL);
//////						break 2;
//////					}
////				}
//			}
//		} while ($running>0 && $ready!=-1);
	}
}
