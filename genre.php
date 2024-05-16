<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genre - NEXUS Cinema</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/genre.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">NEXUS Cinema</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <?php
                if(isset($_SESSION['user_id'])) {
                    echo '<li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="php/logout.php">Logout</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                }
                ?>
                <li class="nav-item"><a class="nav-link" href="genre.php">Genre</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h2>Genres</h2>
        <div class="genres">
            <?php
            include('php/db.php');
            $sql = "SELECT DISTINCT genre FROM movies";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='genre'>";
                    echo "<h3>" . $row['genre'] . "</h3>";
                    
                    $genre = $row['genre'];
                    $sql_movies = "SELECT * FROM movies WHERE genre='$genre'";
                    $result_movies = $conn->query($sql_movies);
                    
                    if ($result_movies->num_rows > 0) {
                        echo "<div class='movies'>";
                        while($movie = $result_movies->fetch_assoc()) {
                            echo "<div class='movie'>";
                            echo "<img src='" . $movie['poster'] . "' alt='" . $movie['title'] . "'>";
                            echo "<p>" . $movie['title'] . "</p>";
                            echo "</div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<p>No movies found in this genre.</p>";
                    }

                    echo "</div>";
                }
            } else {
                echo "<p>No genres found.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
