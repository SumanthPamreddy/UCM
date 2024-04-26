<?php
include("header.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = "";

if (isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID'] != 0 && isset($_SESSION['id'])) {
    $user = $_SESSION['firstname'];
    $email = $_SESSION['email'];
    echo ('<br>');
    echo ('<br>');
    echo ('<br>');

    // Validate and sanitize user input
    $userid = filter_var($_SESSION['userid'], FILTER_VALIDATE_INT);

    if ($userid === false) {
        // Invalid user ID
        echo "Invalid user ID.";
    } else {
        $select_query = "SELECT * FROM booking WHERE passenger_id = ?";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(1, $userid, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Fetch data and store it in $data
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM Share</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: silver;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #dc3545;
            color: Black;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <table border='1'>
        <tr>
            <th>Ride ID</th>
            <th>Mode</th>
        </tr>

        <?php foreach ($data as $request) : ?>
            <tr>
                <td><?php echo $request['ride_id']; ?></td>
                <td><?php echo $request['mode']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>
