<?php
 
header('Content-Type: text/html; charset=utf-8');

require_once( dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php' );
require_once(dirname(__FILE__) . '/posts-compare.php');

postscompare_hourly();

echo "Done!";

die();
?>
