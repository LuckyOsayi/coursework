<?php
    session_start();

    //Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true || $_SESSION["role"] != "Admin"){
        header("location: sign_in.php");
        exit;
    }

    error_reporting(E_ALL);
        
    try{
        include('pdo.php');

        $qry = "select * from Users";
        $stmt = $pdo->prepare($qry);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($users);

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

    <title>All Users</title>
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
                    <?php if(!empty($_GET["error"]) && ($_GET["error"] == "noUser")){ ?>
                        <div class="alert alert-danger" role="alert">No such user.</div>;
                    <?php } ?>
                    <div class="card">
                        <div class="card-header">
                            <strong>Details</strong>
                            <table class="table table-striped table-hover" id="userTable">
                            <thead>
                                <th class="col-md-3">Full name</th>
                                <th class="col-md-2">Email</th>
                                <th class="col-md-2">Phone</th>
                                <th class="col-md-1">Status</th>
                                <th class="col-md-2">Userrole</th>
                                <th class="col-md-2">Action</th>
                            </thead>
                            <tbody>
                                <?php if(!empty($users)) { ?>
                                    <?php foreach($users as $user) { ?>
                                        <tr>
                                            <td><?php echo $user['lname'].", ". $user['fname'] ?></td>
                                            <td><?php echo $user['email'] ?></td>
                                            <td><?php echo $user['phone'] ?></td>
                                            <td><?php echo $user['status'] ?></td>
                                            <td><?php echo $user['role']; ?></td>
                                            <td>
                                                <?php echo "<a href=\"userdetails.php?userid=".$user["id"]."\">View Details</a>"; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                        <script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
                        <script>
                        $(document).ready(function() {
                        $('#userTable').DataTable();
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
</body>

<?php
    include('footer.php');
?>