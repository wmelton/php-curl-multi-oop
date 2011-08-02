<?php
/**
 * This is a testpage for curl to access
 * Use like this to sleep for 1 second:
 * http://localhost/test_pages/test_page_sleep.php?sleep=1&extra=curl_1
 */
if(!isset($_GET['sleep'])) {
    echo 'sleep parameter missing';
}
else {
    usleep($_GET['sleep']*1000000);
}

echo '<br />';
echo 'random number - '.mt_rand(1,1000).'<br />';
if(isset($_GET['extra'])) {
	echo 'extra: '.$_GET['extra'].'<br />';
}
echo '<hr />';