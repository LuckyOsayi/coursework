<?php
    session_start();

    require_once "config.php";

    try{
        include('pdo.php');

        $query = "select * from Locations where status = :status";
        $statement = $pdo->prepare($query);

        $statement->bindValue(':status', "Active");
        $statement->execute();

        $locations = $statement->fetchAll(PDO::FETCH_ASSOC);
        $count = count($locations);

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Story Telling Web App</title>
</head>
<body class="bg-light">
    <!--nav-->
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light text-white bg-warning">
            <a class="navbar-brand" href="index.php"><img src="assests/images/finder-logos_white.png" width="80px"/></a> 
            </button>

            <div class="collapse navbar-collapse">
            <ul class="navbar-nav" style="padding: 0; margin: 0;">
                <?php 
                    include('nav.php');
                ?>
            </ul>
            </div>
        </nav>
        </div>
    <!--end nav-->
    
    <!--content start-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if(!empty($_GET["signout"]) && ($_GET["signout"] == "true")){ ?>
                    <div class="alert alert-primary" role="alert">Logout successful.</div>
                <?php } ?>
            </div>
        
        <?php foreach($locations as $location) { ?>
            <div class="col-md-4">
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
                            if(strlen($location["description"]) > 100){
                                echo substr($location["description"], 0, 100 - 0)."...";
                            }else echo $location["description"]; 
                        ?>
                        <p style="margin: 20px 0;">
                        <button type="submit" onclick="location.href='moredetails.php?locationid=<?php echo $location['id'] ?>'" class="btn btn-primary">Read More</button>
                        </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
</div>
    <!--content end-->
<?php
    include('footer.php');
?>