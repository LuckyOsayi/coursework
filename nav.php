<?php if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] ===  true) {
        if($_SESSION["role"] == "generalUser") { ?>
            <li class="nav-item active">
            <a class="nav-link pl-5 text-white" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="edituser.php">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="changepassword.php">Change Passowrd</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="logout.php">[ LOGOUT ]</a>
            </li>
    <?php }
    elseif($_SESSION["role"] == "Admin"){ ?>
            <li class="nav-item active">
            <a class="nav-link pl-5 text-white" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="addlocation.php">Add Location</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="locations.php">View Locations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="addnewadmin.php">Add Admin User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="users.php">View Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="changepassword.php">Change Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="logout.php">LOGOUT</a>
            </li>
    <?php }else { ?>
            <a class="nav-link pl-5 text-white" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="register.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="login.php">Login</a>
            </li>
    <?php } 
    }else{ ?>
            <li class="nav-item active">
            <a class="nav-link pl-5 text-white" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="register.php">Register</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pl-5 text-white" href="login.php">Login</a>
            </li>
<?php } ?>