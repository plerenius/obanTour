<?php
// Fill in all the info we need to connect to the database.
// This is the same info you need even if you're using the old mysql_ library.
$host = 'localhost';
$port = 3306; // This is the default port for MySQL
$database = 'imath_se';
$username = 'root';
$password = '';

// Construct the DSN, or "Data Source Name".  Really, it's just a fancy name
// for a string that says what type of server we're connecting to, and how
// to connect to it.  As long as the above is filled out, this line is all
// you need :)
$dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";

// Connect!
global $db;
$db = new PDO($dsn, $username, $password);

// Avoid errors due to large requests
$comp_id_q = $db->prepare("SET SQL_BIG_SELECTS=1");
$comp_id_q->execute();
?>