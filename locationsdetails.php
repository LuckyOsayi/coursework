<?php
    session_start();

    if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true || $_SESSION["role"] != "Admin"){
        header("location: sign_in.php");
        exit;
    }
    if(isset($_POST["submit"])){
        try{
            include('pdo.php');

            if(isset($_POST["title"])) $title = ucfirst(trim($_POST["title"]));
            if(isset($_POST["description"])) $description = trim($_POST["description"]);
            if(isset($_POST["locationid"])) $locationid = $_POST["locationid"];
            
            $query = "update `Locations` set title='$title', description='$description' WHERE id='$locationid'";

            $statement = $pdo->prepare($query);
            $statement->execute();

            if($statement === false){
                header("location: locationdetails.php?updated=false");
                exit;
            }

            $pdo = null;
            
            header("location: locationdetails.php?locationid=".$locationid."&updated=true");
            exit;
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
    }

    try{
        include('pdo.php');

        $query = "select * from Locations where id = :locationid";
        $statement = $pdo->prepare($query);

        if(!isset($_GET["locationid"]) || empty($_GET["locationid"])){
            header("location: locations.php?error=noLocation");
        }else{
            $locationid = $_GET["locationid"];
        }

        $statement->bindValue(':locationid', $locationid);
        $statement->execute();

        $location = $statement->fetch(PDO::FETCH_ASSOC);
        
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

    <title>Add Location</title>
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
                            <form action="locationdetails.php" method="POST">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo $location["title"] ?>" required="required">

                                <label for="description">Description</label>
                                <textarea type="text" name="description" value="<?php echo $location["description"] ?>" class="form-control" required="required" maxlength="249" rows="3"><?php echo $location["description"] ?></textarea>
                                
                                <label for="picfile">Image</label>
                                <input type="text" name="picfile" value="<?php echo $location["picurl"] ?>" class="form-control" readonly>
                                
                                <label for="status">Status</label>
                                <input type="text" name="status" value="<?php echo $location["status"] ?>" class="form-control" readonly>

                                <input type="hidden" id="locationid" name="locationid" value="<?php echo $location["id"] ?>">
                                <p></p>
                                <button class="btn btn-primary" type="submit" name="submit">Update Location</button>

                                <?php
                                    $act = "locale.php?locationid=".$location["id"]."&action=activate";
                                    $de_act = "locale.php?locationid=".$location["id"]."&action=deactivate";

                                    if($location["status"] === "Active"){
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