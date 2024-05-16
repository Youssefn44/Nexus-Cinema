<?php
session_start();
include('db.php');

if (isset($_POST['showtime_id'], $_POST['seat_id'])) {
    $showtime_id = $_POST['showtime_id'];
    $seat_id = $_POST['seat_id'];
    $user_id = $_SESSION['user_id'];

    // Update seat availability
    $conn->query("UPDATE seats SET is_available = 0 WHERE id = '$seat_id'");

    // Insert new booking record
    $conn->query("INSERT INTO bookings (user_id, ticket_id, booking_time) VALUES ('$user_id', '$ticket_id', NOW())");

    echo "Booking successful!";
} else {
    echo "Error: Booking failed.";
}
?>
