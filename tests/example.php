<?php
// Include autoload function
require 'autoload.php';

// Create new Curl request
$tvnet = new Curl();

$tvnet->set_url('http://tvnet.lv/');
echo $tvnet->exec();