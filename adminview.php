<?php
ob_start();

include("header.php");
{
    // Set session cookie parameters
    @session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

    // Start the session
    @session_start();
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Fetch user information based on user ID
$user_id = $_SESSION['userid'];
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user is an admin
if ($user['admin'] != 1) {
    // Redirect or display an error message if the user is not an admin
    header("Location: index.php"); // You can change the location to the appropriate page
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['user_id'])) {
        // Delete user based on user_id
        $user_id = $_GET['user_id'];
        $sql = "DELETE FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: adminview.php"); 
    } elseif (isset($_GET['ride_id'])) {
        // Delete ride based on ride_id
        $ride_id = $_GET['ride_id'];
        $sql = "DELETE FROM rides WHERE ride_id = :ride_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ride_id', $ride_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: adminview.php"); 
        ob_end_flush();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User and Ride Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #343a40;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
            padding: 8px 12px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h2>User List</h2>
<table>
    <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Action</th>
    </tr>

    <?php
    //include('header.php');

    // Fetch users from the database
    $sql = "SELECT * FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['firstname']}</td>
        <td>{$row['lastname']}</td>
        <td>{$row['email']}</td>
        <td><a href='adminview.php?user_id={$row['id']}'>Delete</a></td>
      </tr>";
    }
    ?>
</table>

<h2>Ride List</h2>
<table>
    <tr>
        <th>Ride ID</th>
        <th>Rider ID</th>
        <th>From Location</th>
        <th>To Location</th>
        <th>When</th>
        <th>Action</th>
    </tr>

    <?php
    //include('header.php');

    // Fetch rides from the database
    $sql = "SELECT * FROM rides";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
        <td>{$row['ride_id']}</td>
        <td>{$row['rider_id']}</td>
        <td>{$row['from_location']}</td>
        <td>{$row['to_location']}</td>
        <td>{$row['ride_when']}</td>
        <td><a href='adminview.php?ride_id={$row['ride_id']}'>Delete</a></td>
      </tr>";
    }
    ?>
</table>

</body>
</html>
