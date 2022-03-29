<?php
    session_start();

    if(isset($_GET["locationid"]) && !empty($_GET["locationid"])){
        try{
            include('pdo.php');

            $query = "select * from Locations where id = :location_id";
            $query2 = "select * from Comments where storyid = :location_id";
            $statement = $pdo->prepare($query);
            $statement2 = $pdo->prepare($query2);

            $locationid = $_GET["locationid"];
            $status = "Active";

            $statement->bindValue(':location_id', $locationid);
            $statement2->bindValue(':location_id', $locationid);
            $statement->execute();
            $statement2->execute();

            $location = $statement->fetch(PDO::FETCH_ASSOC);
            $comments = $statement2->fetchAll(PDO::FETCH_ASSOC);
            
            $count = $statement->fetchColumn();
            $count2 = $statement2->fetchColumn();

            if ($count > 0){
                $pdo = null;
                header("location: index.php?error=noDetails");
                exit;
            }

            $pdo = null;
        }catch(PDOException $exception){
            echo $exception->getMessage();
        }
    }else{
        header("location: index.php?error=noDetails");
        exit;
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
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div>
                            <img src="<?php echo $location["picurl"] ?>" class="xhibit" height="200">
                        </div>
                        <div class="carddescription">
                            <p style="font-weight: bold;">
                                <?php
                                    if(strlen($location["title"]) > 29){
                                        echo substr($location["title"], 0, 29 - 0)."...";
                                    }else echo $location["title"]; 
                                ?>
                            </p>
                            <?php
                                echo $location["description"]; 
                            ?>
                        </div>
                        <p></p>
                        <?php if(isset($_SESSION["role"])){ ?>
                            <form action="combo.php" method="POST">
                                <div class="mb-3">
                                    <textarea type="text" rows="3" maxlength="249" class="form-control" name="comment" id="comment" required="required"></textarea>
                                    <input type="hidden" id="locationid" name="locationid" value="<?php echo $locationid ?>">
                                </div>
                                <button type="submit" class="btn btn-success" style="margin-top: 10px;">Add Comment</button>
                            </form>
                        <?php } ?>
                        <p></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <?php
                        if(!empty($comments)){
                            foreach($comments as $comment){
                                echo "
                                <div class=\"well well-sm\">".$comment["comment"]."
                                    <div style=\"font-size:9px; text-align: left; font-weight: bold;\">[by: ".$comment["name"]." ]</div>
                                </div><hr/>";
                            }
                        }else{
                            echo "<p>Oops! There are no reviews yet!</p>";
                        }
                    ?>
                </div>
            </div>
</body>

<?php
    include('footer.php');
?>