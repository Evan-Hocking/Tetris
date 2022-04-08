<?php
    $db_username = "**";
    $db_password = "**";
    $db_database = "tetris";
    $conn = mysqli_connect("localhost", $db_username, $db_password, $db_database);
    if (mysqli_connect_errno()) {
        die( "Failed to connect to MySQL: " . mysqli_connect_error($conn));
    }
?>
