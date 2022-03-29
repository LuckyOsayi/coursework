<?php
    session_start();

    if(isset($_POST["submit"])){
        try{
            include('pdo.php');
            if(isset($_POST["submit"])) $email = strtolower(trim($_POST["email"]));

            $query = $pdo->prepare("select * from Users where email = ?");
            $query->execute([$email]); 
            
            $count = $query->fetchColumn();

            if ($count > 0){
                $pdo = null;
                header("location: addnewadmin.php?error=oneUser");
                exit;
            }

        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
        
        try{
            include('pdo.php');

            if(isset($_POST["submit"])){
                if(trim($_POST["password"]) != trim($_POST["cpassword"])){
                    header("location: addnewadmin.php?error=mismatch");
                    exit;
                }
                
                $fname = ucfirst(trim($_POST["fname"]));
                $lname = ucfirst(trim($_POST["lname"]));
                $phone = trim($_POST["phone"]);
                $address = trim($_POST["address"]);
                $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
                $status = "Active";
                $role = "Admin";
            }

            

            $query="insert into Users (fname, lname, email, phone, address, password, status, role) values ('$fname','$lname','$email','$phone','$address','$password','$status','$role')";

            $result = $pdo->exec($query);
            $pdo = null;

            header("location: addnewadmin.php?reg=success");
        }catch(PDOException $exception){
            echo "div class='error'>" . $exception->getMessage() . "</div>";
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

<title>Register</title>
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
                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "oneUser")){ ?>
                            <div class="alert alert-warning" role="alert">Email already exists. Use another.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "mismatch")){ ?>
                            <div class="alert alert-danger" role="alert">Passwords don't match.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["reg"]) && ($_GET["reg"] == "success")){ ?>
                            <div class="alert alert-success" role="alert">Admin user created successfully.</div>
                    <?php } ?>
                    <div class="card">
                        <div class="card-header">
                        <strong>Input your Info</strong>
                        <form action="addnewadmin.php" method="POST">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" class="form-control" required="required">

                            <label for="fname">Last Name</label>
                            <input type="text" name="lname" id="lname" class="form-control" required="required">
                            
                            <label for="address">Address</label>
                            <input type="text" name="address" required="required" class="form-control" required="required">
                            
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required="required">
                            
                            <label for="cpassword">Conirm Password</label>
                            <input type="password" name="cpassword" class="form-control" required="required">
                            
                            <label for="phone">Phone number</label>
                            <input type="text" name="phone" id="phone"class="form-control" required="required">
                            
                            <label for="Email">Email</label>
                            <input type="email" name="email" class="form-control" required="required">
                            <p></p>
                            <button class="btn btn-primary" type="submit" name="submit">Register</button>
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