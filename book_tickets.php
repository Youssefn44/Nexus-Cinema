<?php
session_start();
include('php/db.php');  // Include the database connection file

// Fetch movie details
if (isset($_GET['movie_id'])) {
    $movie_id = intval($_GET['movie_id']);
    $sql = "SELECT * FROM movies WHERE id = $movie_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
    } else {
        die("No movie found.");
    }
} else {
    die("No movie ID provided.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/seats.css"> <!-- Include your seats.css file -->
</head>
<body>
    <nav class="navbar">
        <div class="logo">NEXUS Cinema</div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php
                if(isset($_SESSION['user_id'])) {
                    echo '<li><a href="profile.php">Profile</a></li>';
                    echo '<li><a href="php/logout.php">Logout</a></li>';
                } else {
                    echo '<li><a href="login.php">Login</a></li>';
                }
            ?>
            <li><a href="genre.php">Genre</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <input type="text" placeholder="Search">
    </nav>
    <div class="content" id="book-tickets-content">
        <div class='movie-details'>
            <div class='movie'>
                <img src='<?php echo $movie['poster']; ?>' alt='<?php echo $movie['title']; ?>'>
                <p><?php echo $movie['title']; ?></p>
                <p>Release Date: <?php echo $movie['release_date']; ?></p>
            </div>
            <div class="dropdown">
                <label for="showtimes">Select Showtime:</label>
                <select id="showtimes" onchange="fetchSeats(this.value)">
                    <option value="">Select a showtime</option>
                    <?php
                    // Fetch showtimes for the selected movie
                    $showtimes_sql = "SELECT * FROM showtimes WHERE movie_id = $movie_id";
                    $showtimes_result = $conn->query($showtimes_sql);

                    if ($showtimes_result->num_rows > 0) {
                        while ($showtime = $showtimes_result->fetch_assoc()) {
                            echo "<option value='" . $showtime['id'] . "'>" . $showtime['show_time'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No showtimes available</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="seats-container-wrapper">
            <div class="screen">Screen</div>
            <div class="seats-container" id="seats-container">
                <!-- Seats will be dynamically loaded here based on showtime selection -->
            </div>
            <button id="confirmBookingButton" class="confirm-booking-button" style="display:none;" onclick="confirmBooking()">Confirm Booking</button>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
