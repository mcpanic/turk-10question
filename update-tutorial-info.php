<?php
/*
	For videos that are available on YouTube: YouTube API is used.
*/
define("DB_HOST", "50.116.6.114");    // MySQL host name
define("DB_USERNAME", "turk_10questions");    // MySQL username
define("DB_PASSWD", "xLhbByt5AHZa2NQS");    // MySQL password
define("DB_NAME", "turk_10questions");    // MySQL database name. vt.sql uses the default video_learning name. So be careful.

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$workerid = $_GET["workerid"];
$success = TRUE;
if ($mysqli->query("INSERT INTO worker_tutorial (workerid) VALUES ('$workerid')") === FALSE) {
	$success = FALSE;
}
$mysqli->close();

echo $success;
?>