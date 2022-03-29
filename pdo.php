<?php
    require_once "config.php";

    $source = "mysql:host=$database_host;dbname=$database_name;";
    $pdo = new PDO($source,$database_user, $database_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>