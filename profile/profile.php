<?php
    session_start();
    include ('../nav/nav.php');

    $user_name = $_SESSION['user_name'];
    $user_id = $_SESSION['user_id'];

    echo "<h1>Welcome, $user_name</h1>";
    echo "<h2>Your Profile</h2>";
    echo "<p>User ID: $user_id</p>";    
?>