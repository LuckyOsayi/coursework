<?php
    session_start();

    if(!isset($_SESSION["logged_in"])){
        $_SESSION = array();
        session_destroy();
        header("location: index.php");
        exit;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        try{
            include('pdo.php');

            if(isset($_POST["password"])) $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
            if(isset($_POST["cpassword"])) $cpassword = password_hash(trim($_POST["cpassword"]), PASSWORD_DEFAULT);

            if(!isset($_SESSION["userid"]) || empty($_SESSION["userid"])){
                header("location: login.php?error=noUser");
                exit;
            }

            if(trim($_POST["password"]) != trim($_POST["cpassword"])){
                header("location: changepassword.php?match=false");
                exit;
            }

            $query = "update Users set password=? WHERE id=?";
            $statement = $pdo->prepare($query);
            $statement->execute([$password, $_SESSION["userid"]]);

            $pdo = null;
            
            header("location: changepassword.php?updated=true");
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
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

    <title>Change Password</title>
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
                <?php if(!empty($_GET["updated"]) && ($_GET["updated"] == "true")){ ?>
                        <div class="alert alert-success" role="alert">Password updated successfully.</div>
                <?php } ?>

                <?php if(!empty($_GET["match"]) && ($_GET["match"] == "false")){ ?>
                        <div class="alert alert-danger" role="alert">Passwords don't match.</div>
                <?php } ?>
                <div class="card">
                    <div class="card-header">
                    <strong>Change Password</strong>
                    <form action="changepassword.php" method="POST">
                        <label for="password">New Password</label>
                        <input type="password" name="password" class="form-control" required="required">

                        <label for="cpassword">Confirm New Password</label>
                        <input type="password" name="cpassword" class="form-control" required="required">
                        <p></p>
                        <button class="btn btn-primary" type="submit" name="submit">Change Password</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
    include('footer.php');
?>