<?php
    session_start();
    
    session_destroy();

    header("Location: sign-in/sign_in.html");
?>