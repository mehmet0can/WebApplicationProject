<?php
	// Database credentials
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'UserAdmin');
	define('DB_PASSWORD', 'password1234');
	define('DB_NAME', 'WebApplicationDB');

	// Attempt to connect to MySQL database
	$mysql_db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if (!$mysql_db) {
		die("Error: Unable to connect " . $mysql_db->connect_error);
	}
