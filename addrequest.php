<?php
include('header.php');

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID'] != 0 && isset($_SESSION['id'])) {
    $user = $_SESSION['firstname'];
    $email = $_SESSION['email'];
    echo ('<br>');
    echo ('<br>');
    echo ('<br>');
} else {
    // Redirect to the login page if not logged in
    header("Location: index.php");
    exit(); // Ensure script stops here
}

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Get the ride_id from the URL (assuming it's an integer, adjust if necessary)
    $ride_id = isset($_GET['ride_id']) ? intval($_GET['ride_id']) : 0;

    // Validate and sanitize input
    if ($ride_id <= 0) {
        // Invalid or missing ride_id
        echo "Invalid or missing ride_id.";
    } else {
        // Use prepared statement to prevent SQL injection
        $insert_query = "INSERT INTO booking (ride_id, passenger_id, mode) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($insert_query);

        // Mode for the sample
        $sample = "requested";

        // Bind parameters
        $stmt->bindParam(1, $ride_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $_SESSION['userid'], PDO::PARAM_INT);
        $stmt->bindParam(3, $sample, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Request Raised.";
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }

        // Close the cursor
        $stmt->closeCursor();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM Share</title>
</head>
<body>
    <!-- Your HTML content goes here -->
</body>
</html>
