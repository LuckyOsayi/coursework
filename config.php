<?php
    $database_password = "";
    $database_host = "localhost";
    $database_user = "root";
    $database_name = "finder";
    $connection = new PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_password);
	if(!$connection){
		die("Fatal Error: Connection Failed!");
	}
?>