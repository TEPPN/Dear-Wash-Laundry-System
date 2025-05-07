<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in
    header("Location: ../login/sign-in/sign_in.html");
    exit();
}
