<?php 
include("header.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user="";
echo($_COOKIE['PHPSESSID']."sam".$_COOKIE['PHPSESSID']."sam".$_SESSION['id'].$_SESSION['firstname']);
if(isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID']!=0 && isset($_SESSION['id'])){
    $user=$_SESSION['firstname'];
}else{
    header("Location:index.php");
}

try {
    echo "<br>";
    echo "<br>";
    echo "<table>";
    echo "<tr>";
    echo "<th>From Location</th>";
    echo "<th>To Location</th>";
    echo "<th>How Many</th>";
    echo "<th>Ride When</th>";
    echo "<th>Rider Email</th>";
    echo "<th>Rider Mobile </th>";
    echo "<th>Vehicle Manufacturer</th>";
    echo "<th>Vehicle Type</th>";
    echo "<th>Vehicle Number</th>";
    echo "<th>Action</th>"; 
    echo "</tr>";




$sql = "SELECT r.ride_id,r.rider_id, r.from_location, r.to_location, r.how_many, r.ride_when, u.email,u.mobile, v.manufacturer, v.type, v.number 
FROM rides r
INNER JOIN users u ON r.rider_id = u.id
INNER JOIN vehicle v ON r.rider_id = v.user_id
WHERE r.ride_when >= :current_date
AND v.id = (
    SELECT MAX(id) 
    FROM vehicle 
    WHERE user_id = r.rider_id
)";


    $stmt = $pdo->prepare($sql);
    $currentDate = date('Y-m-d H:i:s');
    $stmt->bindParam(':current_date', $currentDate, PDO::PARAM_STR);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if($row['email'] != $_SESSION['email']){

        echo "<tr>";
        echo "<td>" . $row['from_location'] . "</td>";
        echo "<td>" . $row['to_location'] . "</td>";
        echo "<td>" . $row['how_many'] . "</td>";
        echo "<td>" . $row['ride_when'] . "</td>";
        echo '<td><a href="mailto:' . $row['email'] . '">' . $row['email'] . "</a></td>";
        echo "<td>" . $row['mobile'] . "</td>";
        echo "<td>" . $row['manufacturer'] . "</td>";
        echo "<td>" . $row['type'] . "</td>";
        echo "<td><b>" . $row['number'] . "</b></td>";
        $rideId = 5; // Replace this with your dynamic value or variable

        echo '<td>
        <a href="addrequest.php?ride_id=' . $row['ride_id'] . '">Book</a>
      </td>';


        echo "</tr>";
        }
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

      
        

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM Share</title>
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    
</style>
</head>
<body>
    
</body>
</html>