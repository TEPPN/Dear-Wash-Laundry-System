<?php
    session_start();
    require_once "../../connect/connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = trim($_POST["user"]);
        $password = $_POST["password"];

        $query = "SELECT user_id, user_name, user_email, user_password FROM user WHERE user_email = ? OR user_name = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
        die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {

            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_email"] = $email;
            header("Location: ../../homepage/home.html");
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Email not found!";
    }
    }
?>