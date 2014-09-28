<?php
include_once('qiniu.class.php')
error_reporting(E_ALL);
ini_set('display_errors', '1');

$key = "jobs.jpg";
$one = new Qiniu();
//$one->download($key);
$one->upload($key);

return;
?>
