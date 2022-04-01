<?php
    include "config.php";
    session_start();
    if ($_GET["logout"]==1){
        unset($_SESSION["username"]);
    }
    
    if(isset($_SESSION["username"])){
        $username = $_SESSION["username"];
    }else{
        $username = null;
    }
    
    $Score=$_GET["score"];
    if(!($_SESSION["username"])){
        $saveScore=false;
        $error = "not logged in";
    }
    $sql = "SELECT Display FROM Users WHERE UserName = '". $_SESSION["username"]  . "'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

    $count = mysqli_num_rows($result);

    if($row["Display"]==0){
        $error = "dont display";
    }
    if (!$error){
        $sql="INSERT INTO Scores (Username, Score) VALUES ('".$_SESSION["username"]."', " .$Score.")";
        mysqli_query($conn, $sql);
    }
    $sql = "SELECT * FROM Scores ORDER BY Score DESC";
    $result = mysqli_query($conn,$sql);
    $table = array();
    
?>
<html>
    <head>
        <title>Tetris-Leaderboard</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="main">
            <div class = "navbar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <div id="right">
                        <?php
                            if ($username){
                                echo "<li><a href='leaderboard.php?logout=1'>Logout</a></li>";
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
                <center>
                    <?php
                        if ($Score){
                            echo"<div class='GameOver'>";
                            echo"<h1>Game Over!</h1>";
                            echo"<h2>Your Score</h2>";
                            echo"<h2 id='finalScore'>".$Score."</h2>";
                            echo"<a href='tetris.php'><button>Play Again?</button></a>";
                            echo"</div>";
                        }
                    ?>
                    <h2>Leaderboard</h2>
                    <table>
                        <tr>
                            <th><p>Username</p></th>
                            <th><p>Score</p></th>
                        </tr>
                        <?php 
                        while ($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            $counter = 3;
                            foreach ($row as $field => $value){
                                if ($counter !=3){
                                    echo "<td>" . $value . "</td>";
                                } else {$counter=0;}
                                $counter++;
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </center>
            </div>
        </div>
    </body>
</html>