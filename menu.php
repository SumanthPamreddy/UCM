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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCM SHARE</title>
    <style type="text/css">   
        .split {
            height: 100%;
            width: 50%;
            position: fixed;
            z-index: 1;
            top: 40px;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .left {
            left: 0;
            background-color: white;
            border-style: solid;
            border-width: 1px;
            border-color: black;
           
        }

        .right {
            right: 0;
            background-color: white;
            border-style: solid;
            border-width: 1px;
            border-color: black;
        }

        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }


        .center{
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 400px;
  background: white;
  border-radius: 10px;
  box-shadow: 10px 10px 15px rgba(0,0,0,0.05);
}
.center h1{
  text-align: center;
  padding: 20px 0;
  border-bottom: 1px solid silver;
}
.center form{
  padding: 0 40px;
  box-sizing: border-box;
}
form .txt_field{
  position: relative;
  border-bottom: 2px solid #adadad;
  margin: 30px 0;
}
.txt_field input{
  width: 100%;
  padding: 0 5px;
  height: 40px;
  font-size: 16px;
  border: none;
  background: none;
  outline: none;
}
.txt_field label{
  position: absolute;
  top: 50%;
  left: 5px;
  color: #adadad;
  transform: translateY(-50%);
  font-size: 16px;
  pointer-events: none;
  transition: .5s;
}
.txt_field span::before{
  content: '';
  position: absolute;
  top: 40px;
  left: 0;
  width: 0%;
  height: 2px;
  background: red;
  transition: .5s;
}
.txt_field input:focus ~ label,
.txt_field input:valid ~ label{
  top: -5px;
  color: red;
}
.txt_field input:focus ~ span::before,
.txt_field input:valid ~ span::before{
  width: 100%;
}
.pass{
  margin: -5px 0 20px 5px;
  color: red;
  cursor: pointer;
}
.pass:hover{
  text-decoration: underline;
}
input[type="submit"]{
  width: 100%;
  height: 50px;
  border: 1px solid;
  background: black;
  border-radius: 25px;
  font-size: 18px;
  color: red;
  font-weight: 700;
  cursor: pointer;
  outline: none;
}
input[type="submit"]:hover{
  border-color: red;
  transition: .5s;
}
.signup_link{
  margin: 30px 0;
  text-align: center;
  font-size: 16px;
  color: #666666;
}
.signup_link a{
  color: red;
  text-decoration: none;
}
.signup_link a:hover{
  text-decoration: underline;
}

 
    </style>
</head>
<body>
    <br>
    <br>
        
    <div class="split left">
        <div class="centered">
        <h2>Request Ride</h2>
        <a href="requestride.php"><img src="requestride.png" alt="RequestRide" width="500" height="600"></a> 
        </div>
    </div>

    <div class="split right">
        <div class="centered">
        <h2>Post Ride</h2>
        <a href="shareride.php"><img src="shareride.png" alt="PostRide" width="500" height="600"></a> 
    </div>
    </div>
</body>
</html>