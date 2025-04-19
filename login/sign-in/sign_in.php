<?php
    session_start();
    require_once "../../connect/connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = trim($_POST["user"]);
        $password = $_POST["password"];

        $query = "SELECT user_id, user_name, user_email, user_password, 
                         user_role, user_created, order_count, user_coupon 
                         FROM user WHERE user_email = ? OR user_name = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
        die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $user_name, $user_email, $hashed_password, 
                           $user_role, $user_created, $order_count, $user_coupon);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {

            $_SESSION["user_id"] = $user_id;
            $_SESSION["user_name"] = $user_name;
            $_SESSION["user_email"] = $user_email;
            $_SESSION["user_role"] = $user_role;
            $_SESSION["user_created"] = $user_created;
            $_SESSION["order_count"] = $order_count;
            $_SESSION["user_coupon"] = $user_coupon;
            header("Location: ../../profile/profile.php");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Email not found!";
    }
    }
?>