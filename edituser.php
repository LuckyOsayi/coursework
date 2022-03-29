<?php
    session_start();

    if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true || $_SESSION["role"] != "generalUser"){
        header("location: login.php");
        exit;
    }
    if(isset($_POST["submit"])){
        try{
            include('pdo.php');

            if(isset($_POST["fname"])) $fname = ucfirst(trim($_POST["fname"]));
            if(isset($_POST["lname"])) $lname = ucfirst(trim($_POST["lname"]));
            if(isset($_POST["phone"])) $phone = trim($_POST["phone"]);
            if(isset($_POST["address"])) $address = trim($_POST["address"]);
            if(isset($_SESSION["userid"])) $userid = $_SESSION["userid"];
            
            $query = "update `Users` set fname='$fname', lname='$lname', phone='$phone', address='$address' WHERE id='$userid'";

            $statement = $pdo->prepare($query);
            $statement->execute();

            if($statement === false){
                header("location: edituser.php?updated=false");
                exit;
            }

            $pdo = null;
            
            header("location: edituser.php?userid=".$userid."&updated=true");
            exit;
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
    }

    try{
        include('pdo.php');

        $query = "select * from Users where id = :userid";
        $statement = $pdo->prepare($query);

        $userid = $_SESSION["userid"];

        $statement->bindValue(':userid', $userid);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        
        $pdo = null;
    }catch(PDOException $exception){
        echo $exception->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no"
/>

<!-- Bootstrap CSS -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
    integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn"
    crossorigin="anonymous"
/>

    <title>User Details</title>
</head>
<body class="bg-light">

    <!--nav-->
    <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light text-white bg-warning">
        <a class="navbar-brand" href="index.php"><img src="assests/images/finder-logos_white.png" width="80px"/></a> 
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <?php 
                include('nav.php');
            ?>
        </ul>
        </div>
    </nav>
    </div>
        
        <!--nav end-->

        <!--div start-->
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if(!empty($_GET["updated"]) && ($_GET["updated"] == "false")){ ?>
                        <div class="alert alert-danger" role="alert">Update Failed. Try again.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["updated"]) && ($_GET["updated"] == "true")){ ?>
                        <div class="alert alert-success" role="alert">Update Successful!</div>
                    <?php } ?>
                    <div class="card">
                        <div class="card-header">
                            <strong>Details</strong>
                            <form action="edituser.php" method="POST">
                                <label for="fname">First name</label>
                                <input type="text" name="fname" class="form-control" value="<?php echo $user["fname"] ?>" required="required">

                                <label for="lname">Last name</label>
                                <input type="text" name="lname" class="form-control" value="<?php echo $user["lname"] ?>" required="required">

                                <label for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo $user["phone"] ?>" required="required">

                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" value="<?php echo $user["address"] ?>" required="required">
                                
                                <label for="picfile">Status</label>
                                <input type="text" value="<?php echo $user["status"] ?>" class="form-control" readonly>
                                
                                <label for="title">Email</label>
                                <input type="text" value="<?php echo $user["email"] ?>" class="form-control" readonly>

                                <label for="title">Userrole</label>
                                <input type="text" value="<?php echo $user["role"] ?>" class="form-control" readonly>

                                <input type="hidden" name="userid" value="<?php echo $user["id"] ?>">
                                <p></p>
                                <button class="btn btn-primary" type="submit" name="submit">Update User Profile</button>

                                <?php
                                    $act = "locale.php?userid=".$user["id"]."&action=activate&gen=true";
                                    $de_act = "locale.php?userid=".$user["id"]."&action=deactivatee&gen=true";

                                    if($user["status"] === "Active"){
                                        echo "<button type=\"button\" onclick=\"location.href='$de_act'\" class=\"btn btn-warning\">Deactivate</button>";
                                    }else{
                                        echo "<button type=\"button\" onclick=\"location.href='$act'\" class=\"btn btn-info\">Activate</button>";
                                    }
                                ?>
                            </form>
                    </div>
                </div>
            </div>
        </div>
</body>

<?php
    include('footer.php');
?>