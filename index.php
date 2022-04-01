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
    if ($_GET['logout']==1){
        unset($_SESSION["username"]);
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        $myusername = $_POST['username'];
        $mypassword = md5($_POST['password']);
        $sql = "SELECT * FROM Users WHERE UserName = '". $myusername . "' AND Password ='" . $mypassword . "'";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        $count = mysqli_num_rows($result);

        if($count==1){
            $_SESSION["username"] = $username = $myusername;
        }else {
            $error = "Your Username or Password is Invalid";
        }
    }
    
?>
<html>
    <head>
        <title>Tetris-Home</title>
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
                            echo "<li><a href='index.php?logout=1'>Logout</a></li>";
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
                <?php
                    if(isset($username)){
                        echo "<center><h1>Welcome to Tetris</h1>";
                        echo "<a href='tetris.php'><button>Click here to play</button></a></center>";
                    }else{
                        echo "<h1>Please Login to Play</h1>";
                        echo "<form method='POST'>";
                            echo "<p>Username: <input type='text' name='username' placeholder='username'></p>";
                            echo "<p>Password: <input type='password' name='password' placeholder='password'></p>";
                        echo "<p>Don't have a user account? <a href='register.php'>Register now</a></p>";
                        echo "<input type='submit' value='Login'>";
                        echo " </form>";
                    }
                    if($error){
                        echo'<div class="alert-danger">' . $error . '</div>';
                    }
                ?>
            </div>
        </div>
    </body>
</html>