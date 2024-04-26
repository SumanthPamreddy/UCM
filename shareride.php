<?php
include("header.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = "";

if (isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID'] != 0 && isset($_SESSION['id'])) {
    $user = $_SESSION['firstname'];
    echo ("<br><br>");
} else {
    header("Location:index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_STRING);
    $from = strip_tags($from);
    $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_STRING);
    $to = strip_tags($to);
    $howmany = filter_input(INPUT_POST, 'howmany', FILTER_SANITIZE_STRING);
    $howmany = strip_tags($howmany);
    $when = filter_input(INPUT_POST, 'when', FILTER_SANITIZE_STRING);
    $when = strip_tags($when);

    echo ("<br><br>");

    $rider_id = $_SESSION['userid'];

    // db insertion
    $insert_query = "INSERT INTO rides (rider_id, from_location, to_location, how_many, ride_when) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->bindParam(1, $rider_id);
    $stmt->bindParam(2, $from);
    $stmt->bindParam(3, $to);
    $stmt->bindParam(4, $howmany);
    $stmt->bindParam(5, $when);

    if ($stmt->execute()) {
        echo "Ride information inserted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $mode = "";
    $rideid = 0;

    if (isset($_GET['accept'])) {
        $mode = 'accepted';
        $rideid = $_GET['accept'];
    } else if (isset($_GET['reject'])) {
        $mode = 'rejected';
        $rideid = $_GET['reject'];
    }

    // Update the mode in the booking table
    $update_query = "UPDATE booking SET mode = ? WHERE booking_id = ?";
    $stmt = $pdo->prepare($update_query);
    $stmt->bindParam(1, $mode);
    $stmt->bindParam(2, $rideid);

    if ($stmt->execute()) {
        //echo "Update successful.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$sql = "SELECT * FROM booking WHERE mode='requested'";
$s = $pdo->prepare($sql);
$s->execute();

echo ("<br><br>");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid silver;
            border-radius: 5px;
            background-color: white;
        }

        input[type="text"],
        input[type="datetime-local"],
        input[type="number"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            outline: none;
            border: 1px solid silver;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: white;
            color: black;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: red;
            color: white;
            outline: 2px solid red;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #dc3545;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        a.accept {
            background-color: #28a745;
            color: white;
        }

        a.reject {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <br>
    <br>
    <form method="post">
        <input type="text" id="from" name="from" placeholder="From" required>
        <input type="text" id="to" name="to" placeholder="To" required>
        <br><br>
        <label for="when">Date : </label>
        <br><br>
        <input type="datetime-local" id="when" name="when" required>
        <br>
        <input type="number" id="howmany" name="howmany" min=1 max=4 placeholder="For How many" required style="width: 400px;">
        <br><br>
        <input type="submit" value='Share Ride'>
    </form>

    <table>
        <tr>
            <th>First Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Passenger Address</th>
            <th>From</th>
            <th>To </th>
            <th>When</th>
            <th colspan="2">Decision</th>
        </tr>

        <?php
        while ($row = $s->fetch(PDO::FETCH_ASSOC)) {
            $booking_id = $row['booking_id'];
            $ride_id = $row['ride_id'];
            $sqltofindrider = "SELECT * FROM rides where ride_id=? && rider_id=?";
            $findriderstmt = $pdo->prepare($sqltofindrider);
            $findriderstmt->bindparam(1, $ride_id);
            $findriderstmt->bindparam(2, $_SESSION['userid']);
            $findriderstmt->execute();

            while ($data = $findriderstmt->fetch(PDO::FETCH_ASSOC)) {
                $passengerdetails = "SELECT * FROM users where id=?";
                $passenger = $pdo->prepare($passengerdetails);
                $passenger->bindparam(1, $row['passenger_id']);
                $passenger->execute();
                $passe = $passenger->fetch(PDO::FETCH_ASSOC);

                echo "<tr>";
                echo "<td>" . htmlspecialchars($passe['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($passe['email']) . "</td>";
                echo "<td>" . htmlspecialchars($passe['mobile']) . "</td>";
                echo "<td>" . htmlspecialchars($passe['street'] . $passe['city']) . "</td>";
                echo "<td>" . htmlspecialchars($data['from_location']) . "</td>";
                echo "<td>" . htmlspecialchars($data['to_location']) . "</td>";
                echo "<td>" . htmlspecialchars($data['ride_when']) . "</td>";
                echo "<td><a class='accept' href='shareride.php?accept=" . $booking_id . "'>Accept</a></td>";
                echo "<td><a class='reject' href='shareride.php?reject=" . $booking_id . "'>Reject</a></td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>

</html>
