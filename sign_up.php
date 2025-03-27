<?php
    require_once "connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        $checkQuery = "SELECT id FROM account WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
        echo "Email already registered! Try another one.";
    } else {
        // Insert new user
        $insertQuery = "INSERT INTO account (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "Signup successful! <a href='sign_in.html'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    }
?>