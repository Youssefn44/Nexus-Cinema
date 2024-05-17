<?php
include('db.php'); // Ensure you have the correct path to db.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Retrieve form data
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, set error message and redirect
        $_SESSION['message'] = "Email already exists";
        $_SESSION['msg_type'] = "danger";
        $check_stmt->close();
        $conn->close();
        header("Location: ../login.php");
        exit();
    }

    $check_stmt->close();

    // Insert new user data
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Sign up successful!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    $conn->close();
    header("Location: ../login.php");
    exit();
}
?>
