<?php
    $get_status = $_GET["action"];

    try{
        include('pdo.php');

        if($get_status == "activate"){
            $status = "Active";
            if(isset($_GET["userid"])){
                $userId = $_GET["userid"];   
            }else $locationid = $_GET["locationid"];
        }else{
            $status = "Inactive";
            if(isset($_GET["userid"])){
                $userId = $_GET["userid"];   
            }else $locationid = $_GET["locationid"];
        }

        if(isset($_GET["userid"])){
            $query = "update `Users` set status='$status' WHERE id='$userId'";
        }else $query = "update `Locations` set status='$status' WHERE id='$locationid'";

        $statement = $pdo->prepare($query);
        $statement->execute();
        $pdo = null;

        if(isset($_GET["userid"])){
            if(isset($_GET["gen"]) && $_GET["gen"] == true){
                header("location: edituser.php?updated=true");
                exit; 
            }

            header("location: userdetails.php?userid=".$userId."&updated=true");
        }else header("location: locationdetails.php?locationid=".$locationid."&updated=true");
    }catch(PDOException $exception){
        echo $exception->getMessage();
    }
?>