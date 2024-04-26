<?php
ob_start();

include('header.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = "";
if (isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID'] != 0 && isset($_SESSION['id'])) {
    $user = $_SESSION['firstname'];
    $email = $_SESSION['email'];
    echo ('<br>');
    echo ('<br>');
    echo ('<br>');
} else {
    header("Location:index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if ($action == 'delete') {
        try {
            $deleteSql = "DELETE FROM users WHERE email = :email";
            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->bindParam(':email', $_SESSION['email']);
            $deleteStmt->execute();

            // Redirect after successful deletion
            unset($_SESSION['id']);
      unset($_SESSION['firstname']);
      unset($_SESSION['email']);
      unset($_SESSION['userid']);
      session_unset();
      session_destroy(); 
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo ($error);
            // Handle the error as needed
        }
    }
}

try {
    // Prepare and execute the SQL query
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    echo "<a href='editprofile.php?action=" . 'delete' . "'>Delete User</a><br>";
    echo "<a href='adminview.php" . "'>Admin View</a>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input for mobile, street, city, pincode
    if(isset($_POST['mobile'])){
    $mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING);
    $mobile = filter_var($mobile, FILTER_VALIDATE_INT); // Assuming mobile is an integer
    $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $pincode = filter_input(INPUT_POST, 'pincode', FILTER_SANITIZE_NUMBER_INT);

    // Update user information
    $updateStmt = $pdo->prepare("UPDATE users SET mobile=?, street=?, city=?, pincode=? WHERE email=?");
    $updateStmt->bindParam(1, $mobile, PDO::PARAM_INT);
    $updateStmt->bindParam(2, $street, PDO::PARAM_STR);
    $updateStmt->bindParam(3, $city, PDO::PARAM_STR);
    $updateStmt->bindParam(4, $pincode, PDO::PARAM_INT);
    $updateStmt->bindParam(5, $_SESSION['email'], PDO::PARAM_STR);
    $updateStmt->execute();
    }else{

    // Update vehicle information
    $manufacturer = filter_input(INPUT_POST, 'manufacturer', FILTER_SANITIZE_STRING);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
    $number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_STRING);

    // Insert vehicle information
    $insertStmt = $pdo->prepare("INSERT INTO vehicle (manufacturer, type, number, user_id) VALUES (?, ?, ?, ?)");
    $insertStmt->bindParam(1, $manufacturer, PDO::PARAM_STR);
    $insertStmt->bindParam(2, $type, PDO::PARAM_STR);
    $insertStmt->bindParam(3, $number, PDO::PARAM_STR);
    $insertStmt->bindParam(4, $_SESSION['userid'], PDO::PARAM_INT);
    $insertStmt->execute();

    echo("<br>");
    echo("Updated");
}
}
        ob_end_flush();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM SHARE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        #editform,
        #addVehicle {
            display: none;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid silver;
            border-radius: 5px;
            background-color: white;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #c82333;
        }

        input[type="tel"],
        input[type="text"],
        textarea,
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

        input[type="radio"] {
            
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #dc3545;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <br>
    <br>
    <br>
    <button onclick="toggleForm('editform')">Edit</button><br>
    <div id="editform">
        <p>Only Enter into fields for which you want to Update details</p>
        <form method="post">
            <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number">
            <br>
            <textarea type="text" id="street" name="street" placeholder="Enter Street and Apt Number in precise"></textarea>
            <br>
            <input type="text" id='city' name='city' placeholder="city">
            <br>
            <input type="number" id="pincode" name="pincode" placeholder="pincode" min="10000" max="999999">
            <br>
            <input type="submit" value="Update">
        </form>
    </div>
    <br>
    <button onclick="toggleForm('addVehicle')">Add Vehicle</button>
    <div id="addVehicle">

        <form method="post">
            <input type="text" id="manufacturer" name="manufacturer" placeholder="Vehicle Manufacturer" required>
            <br>
            <label>Type : </label>
            <label>
                <input type="radio" id="option1" name="type" value="Suv" required>
                Suv
            </label>

            <label>
                <input type="radio" id="option2" name="type" value="sedan" required>
                Sedan
            </label>

            <label>
                <input type="radio" id="option3" name="type" value="Coupe" required>
                Coupe
            </label>

            <label>
                <input type="radio" id="option4" name="type" value="Hatchback" required>
                Hatchback
            </label><br>

            <label>
                <input type="radio" id="option5" name="type" value="Pickup Truck" required>
                Pickup Truck
            </label>
            <br>

            <input type="text" id="number" name="number" placeholder="Number" required>
            <br>
            
            <input type="submit" value="Upload">
        </form>
    </div>

    <script>
        function toggleForm(formId) {
            var form = document.getElementById(formId);

            if (form.style.display === "none") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }
    </script>

</body>

</html>
