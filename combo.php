<?php
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        try{
            include('pdo.php');

            if(isset($_POST["comment"])) $comment = $_POST["comment"];
            if(isset($_POST["locationid"])) $locationid = $_POST["locationid"];
            $name = ucfirst($_SESSION["lname"]). ", " . ucfirst($_SESSION["fname"]);
            $userid = $_SESSION["userid"];

            $query="insert into Comments (name, comment, storyid, userid) values ('$name','$comment','$locationid','$userid')";

            $result = $pdo->exec($query);
            $pdo = null;

            header("location: moredetails.php?locationid=".$locationid."&updated=true");
            exit;
        }catch(PDOException $exception){
            echo "div class='error'>" . $exception->getMessage() . "</div>";
        }
    }
?>