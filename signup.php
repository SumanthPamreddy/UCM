<?php
ob_start();

include('header.php');
//firstname
$firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
$firstname= strip_tags($firstname);

$lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
$lastname= strip_tags($lastname);

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$email= strip_tags($email);

// Validate email format
$emailPattern = '/^[a-zA-Z]{3}\d{5}@ucmo\.edu$/';

$error_msg="";
if (!preg_match($emailPattern, $email)) {
    $error_msg = "Invalid email format. It should have 8 characters (first 3 alphabets, next 5 digits) and end with @ucmo.edu";
}

$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$password = password_hash($password, PASSWORD_DEFAULT);

$mobile = filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING);
$mobile= strip_tags($mobile);

$street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING);
$street= strip_tags($street);

$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$city= strip_tags($city);

$pincode = filter_input(INPUT_POST, 'pincode', FILTER_SANITIZE_STRING);
$pincode= strip_tags($pincode);

$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
$gender= strip_tags($gender);


echo("<br><br><br>");

//echo($firstname. $lastname. $email . $password . $mobile . $street . $city . $pincode . $gender);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        // Insert user data into the 'users' table
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, mobile, street, city, pincode, gender) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bindParam(1, $firstname);
        $stmt->bindParam(2, $lastname);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $password);
        $stmt->bindParam(5, $mobile);
        $stmt->bindParam(6, $street);
        $stmt->bindParam(7, $city);
        $stmt->bindParam(8, $pincode);
        $stmt->bindParam(9, $gender);

        $stmt->execute();
        header("Location: signup.php");
        ob_end_flush();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM SHARE</title>
    <style type="text/css"> 
    html{
        background-color: #f0f0f0; 
    }
    body{
        color:black;
        font-size: large;
        font-family: "Arial", sans-serif;
    }  
   form {
    width: 50%;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid black;
    border-radius: 7px;
    background: white;
    box-shadow: 0 0 100px silver;
}

.child {
    display: flex;
    justify-content: space-between; /* Ensures the child elements are spaced evenly */
    margin-bottom: 0px; /* Adjust the margin as needed */
}

.child input {
    width: 48%; /* Adjust the width as needed */
}

form input,
form textarea,
form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-color:silver;
    box-sizing: border-box;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input:focus,
form textarea:focus,
form select:focus {
    border-color: silver;
    outline: none;
}

input[type="radio"] {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #ccc;
    outline: none;
    margin-right: 5px;
    accent-color: red
}

input[type="submit"] {
    display: block;
    margin: 10px auto;
    width: 50%;
    height: 50px;
    border: 1px solid;
    background: white;
    border-radius: 25px;
    font-size: 18px;
    color: black;
    font-weight: 700;
    cursor: pointer;
    outline: none;
}

input[type="submit"]:hover {
    background-color: red;
    color: white;
    transition: 0.5s;
}
      
</style>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
//used jquery part
$(document).ready(function(){
  $("#confirmpassword").blur(function(){
    var password = $("#password").val();
    var checkpassword = $("#confirmpassword").val();
    if(password != checkpassword){
        $("#showmsg").css("visibility", "visible");
        $("#showmsg").text("Password didn't match");
    } else {
        $("#showmsg").css("visibility", "hidden");
    }
  });

  $("#email").blur(function(){
    validateEmail();
  });

  $("form").submit(function(event){
                if (!validateEmail()) {
                    event.preventDefault(); // Prevent form submission
                    // Optionally, you can display a general error message or take other actions.
                }
            });

            function validateEmail() {
                var email = $("#email").val();
                var emailPattern = /^[a-zA-Z]{3}\d{5}@ucmo\.edu$/;

                if (!emailPattern.test(email)) {
                    $("#emailError").text("Invalid email ");
                    return false;
                } else {
                    $("#emailError").text("");
                    return true;
                }
            }

});
</script>




</head>
<body>
    <form method="post" class='centered'>
        <br>
            <div class="child">
            <input type="text" id="firstname" name="firstname" placeholder="FirstName" required>
            <input type="text" id="lastname" name="lastname" placeholder="LastName" required>
            </div>
            
            <input type="email" id="email" name="email" placeholder="UCMO Email" required>
            
            <p style="display: inline;" id="emailError" name="emailError" ></p>
            <br>

            <input type="password" id="password" name="password" placeholder="Enter Password" pattern=".{4,}" title="Password must be at least 8 characters long" required><br>

            <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password">
            <p style="display: inline;" id="showmsg" ></p>
            <br>
            <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number" required><br>

            <textarea type="text" id="street" name="street" placeholder="Enter Street and Apt Number in precise"></textarea><br>
            <input type="text" id='city' name='city' placeholder="city"><br>
            <input type="number" id="pincode" name="pincode"  placeholder="pincode" min="10000" max="999999"><br>

            <label>Gender : </label>
            <label>
            <input type="radio" id="option1" name="gender" value="male">
            Male
            </label>

            <label>
            <input type="radio" id="option2" name="gender" value="female">
            Female
            </label>

            <label>
            <input type="radio" id="option3" name="gender" value="other">
            Other
             </label>            


        <input type="submit" value="Sign-Up"><br>
            

    </form>
</body>
</html>