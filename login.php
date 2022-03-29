<?php
    session_start();

    if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true){
        header("location: index.php");
        exit;
    }

    if(isset($_POST["submit"])){
        try{
            include('pdo.php');

            $email = strtolower(trim($_POST["email"]));
            $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
            $status = "Active";

            $query = "select * from Users where email = :email and status = :status";
            $stmt = $pdo->prepare($query);

            $stmt->bindValue(':status', $status);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user === false){
                header("location: login.php?error=noUser");
                exit;
            }else{
                $validPassword = password_verify(trim($_POST["password"]), $user["password"]);
                
                if($validPassword == true){

                    $_SESSION["logged_in"] = true;
                    $_SESSION["fname"] = $user["fname"];
                    $_SESSION["lname"] = $user["lname"];
                    $_SESSION["role"] = $user["role"];
                    $_SESSION["userid"] = $user["id"];

                    header("location: index.php");
                    exit;
                }else{
                    header("location: login.php?error=noPass");
                    exit;
                }
            }

            $pdo = null;
            
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

    <title>Login</title>
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
                  <?php if(!empty($_GET["reg"]) && ($_GET["reg"] == "success")){ ?>
                    <div class="alert alert-success" role="alert">User created. You can sign in now.</div>
                  <?php } ?>

                  <?php if(!empty($_GET["error"]) && ($_GET["error"] == "noUser")){ ?>
                          <div class="alert alert-danger" role="alert">Incorrect email.</div>
                  <?php } ?>

                  <?php if(!empty($_GET["error"]) && ($_GET["error"] == "noPass")){ ?>
                          <div class="alert alert-danger" role="alert">Incorrect password.</div>
                  <?php } ?>
                  <div class="card">
                      <div class="card-header">
                        <strong>User Login</strong>
                        <form action="login.php" method="POST">
                          <label for="Email">Email</label>
                          <input type="email" name="email" class="form-control" required="required">

                          <label for="password">Password</label>
                          <input type="password" name="password" id="password" class="form-control" required="required">
                          <p></p>
                          <button class="btn btn-primary" type="submit" name="submit">Login</button>
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