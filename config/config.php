<?php
/*
define('DB_SERVER', 'database-1.caxwicrpfcuz.us-east-2.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'toasteriscool');
define('DB_NAME', 'Phonix');*/

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'tracker');


$mysql_db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$mysql_db) {
	die("Error: Unable to connect " . $mysql_db->connect_error);
}
