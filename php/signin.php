<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password, firstname, lastname FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Setting session variables for user details
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            
            $stmt->close();
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = 'Incorrect password!';
            $stmt->close();
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = 'No user found!';
        $stmt->close();
        header("Location: ../login.php");
        exit();
    }
}
$conn->close();
?>
