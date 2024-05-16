<?php
session_start();
include 'db.php'; // Ensure your database connection file is correctly included

if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'])) {
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);
    $userId = $_SESSION['user_id'];

    $query = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id='$userId'";
    if ($conn->query($query)) {
        // Update session variables
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['email'] = $email;

        header('Location: ../profile.php');
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
