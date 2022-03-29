<?php
    session_start();

    if(!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== "Admin"){
        $_SESSION = array();
        session_destroy();
        header("location: index.php");
        exit;
    }

    if(isset($_POST["submit"])){
        $dir = "uploads/";
        $filename = $dir . basename($_FILES["picfile"]["name"]);
        $upload_status = 1;
        $file_type = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

        $ifImage = getimagesize($_FILES["picfile"]["tmp_name"]);
        if($ifImage === false) {
            header("location: addlocation.php?error=notanimage");
            exit; 
        }

        if (file_exists($filename)) {
            header("location: addlocation.php?error=existingFile");
            exit;
        }

        if ($_FILES["picfile"]["size"] > 50000000) {
            header("location: addlocation.php?error=tooLarge");
            exit; 
        }

        if($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg"
        && $file_type != "gif" ) {
            header("location: addlocation.php?error=allowedFiles");
            exit; 
        }
        
        if (move_uploaded_file($_FILES["picfile"]["tmp_name"], $filename)) {
            try{
                include('pdo.php');

                $picurl = $filename;
                if(isset($_POST["submit"])){
                    $title = ucfirst(trim($_POST["title"]));
                    $description = trim($_POST["description"]);
                }
                $status = "Active";
                $userid = $_SESSION["userid"];

                $query="insert into Locations (title, description, picurl, status, userid) values ('$title','$description','$picurl','$status','$userid')";

                $result = $pdo->exec($query);
                $pdo = null;

                header("location: addlocation.php?success=true");
                exit;
            }catch(PDOException $exception){
                echo "div class='error'>" . $exception->getMessage() . "</div>";
            }
        } else {
            header("location: addlocation.php?error=failedUpload");
            exit;
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
                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "existingFile")){ ?>
                        <div class="alert alert-danger" role="alert">File already exists.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "tooLarge")){ ?>
                        <div class="alert alert-danger" role="alert">File too large.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "allowedFiles")){ ?>
                        <div class="alert alert-danger" role="alert">JPG, PNG and GIF only.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "failedUpload")){ ?>
                        <div class="alert alert-danger" role="alert">Upload Failed. Try again.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "notanimage")){ ?>
                        <div class="alert alert-danger" role="alert">Not an Image.</div>
                    <?php } ?>

                    <?php if(!empty($_GET["success"]) && ($_GET["success"] == "true")){ ?>
                        <div class="alert alert-success" role="alert">Location was saved successfully.</div>
                    <?php } ?>
                    <div class="card">
                        <div class="card-header">
                            <strong>Input your Info</strong>
                            <form action="addlocation.php" method="POST" enctype="multipart/form-data">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control" required="required">

                                <label for="description">Description</label>
                                <textarea type="text" name="description" class="form-control" required="required" maxlength="249" rows="3"></textarea>
                                
                                <label for="picfile">Image</label>
                                <input type="file" name="picfile" class="form-control" required="required">
                                
                                <p></p>
                                <button class="btn btn-primary" type="submit" name="submit">Add Location!</button>
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