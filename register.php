<?php
    session_start();
    include("config.php");
    if ($_GET["logout"]==1){
        unset($_SESSION["username"]);
    }
    
    if(isset($_SESSION["username"])){
        $username = $_SESSION["username"];
    }else{
        $username = null;
    }
    $display=null;
    $error = "";
    $success = null;
    $username = $_SESSION["username"];
    //active on form submission 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //check connection
        if ($con_error){
            $error = $con_error;
        }

        //assigning variables to post response
        $firstname=$_POST['firstname'];
        $surname=$_POST['surname'];
        $myusername=$_POST['username'];
        //md5 encryption so password is not stored in plain text
        $password=md5($_POST['password']);
        $confPass=md5($_POST['confirm-password']);
        $display = $_POST['display'];
        
        //presence check
        if(is_null($display) || !($firstname && $surname && $myusername && $password && $confPass)){
            $error = "Please fill in all fields";
        }

        //password match check
        if($confPass !== $password){
            $error = "Passwords don't match";

        }

        //check if username unique
        $sql = "SELECT * FROM Users WHERE username = '".$myusername."'";
        $result = mysqli_query($conn,$sql);

        $count = mysqli_num_rows($result);
        if($count>0){
            $error = "Username Already Exists";
        }
        
        //sql insert
        if(!$error){
            $sql = "INSERT INTO Users VALUES ('". $myusername ."', '".$firstname."', '".$surname."', '".$password."', '".$display."')";
            if (mysqli_query($conn, $sql)) {
                $success = true;
              } else {
                $error = "ERROR :(";
              }
        }
    }
?>
<html>
    <head>
        <title>Tetris-Register</title>
        <link rel="stylesheet" href="style.css">
        
    </head>
    <body>
    <div class="main">
        <div class="navbar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <div id="right">
                    <?php
                        if ($username){
                            echo "<li><a href='register.php?logout=1'>Logout</a></li>";
                        }else{
                            echo "<li><a href='index.php'>Login</a></li>";
                        }
                    ?>
                    <li><a href="tetris.php">Play Tetris</a></li>
                    <li><a href="leaderboard.php">Leaderboard</a></li>
                </div>
            </ul>
        </div>
        <?php
            if($username){
                echo "<p id='top-right'>Logged in as: <scan id='user'>". $username . "</scan></p>";
            }
        ?>
        <div class="content">
            <form method='POST'>
                <h1>Register</h1>
                <label for="firstname">First Name: </label>
                <input type="text" name="firstname" required>
                <br>
                <label for="surname">Last Name: </label>
                <input type="text" name="surname" required>
                <br>
                <label for="username">Username: </label>
                <input type="text" name="username" required>
                <br>
                <label for="password">Password: </label>
                <input type="password" name="password" required>
                <br>
                <label for="confirm-password">Confirm Password: </label>
                <input type="password" name="confirm-password" required>
                <br>
                <label>Display Scores on Leaderboard: </label>
                    <input type="radio" name="display" value=1 id="show-yes">
                    <label for="show-yes">Yes</label>
                    <input type="radio" name="display" value=0 id="show-no">
                    <label for="show-no">No</label>
                <?php
                if($error){
                    echo'<div class = "alert-danger">' . $error . '</div>';
                }
                ?>
                <input type="submit" value="Create Account">
            </form>
            <?php
                if($success){
                    echo "<h1>Account Registration Successful</h1>";
                    echo "<p>Please Login to play</p>";
                    echo "<a href='index.php'><button>Login</button></a>";
                }                   
            ?>
        </div>
    </body>
</html>