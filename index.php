<?php
include('pdo.php');
ob_start();
include('header.php'); 

// Start session management and include necessary functions
//session_set_cookie_params(86400); 
//session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_EMAIL);
  $username = strip_tags($username);

  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  $sql = "SELECT * FROM users WHERE email = :em";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':em', $username);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
      // Verify the password
      if (password_verify($password, $row['password'])) {
          // Password is correct
        $_SESSION['firstname']=$row['firstname'];
        $_SESSION['id']=1;
        $_SESSION['email']=$username;
        $_SESSION['userid']=$row['id'];
        header("Location: menu.php");

        exit();
      } else {
        ob_end_flush();
          // Password is incorrect
          //echo "Invalid username or password.";
      }
  } else {
      // No user found with the given username
      //echo "Invalid username or password.";
  }
}

if ( ! isset($_COOKIE['visit']) ) {
    setcookie("visit", 1, time()+strtotime("+30 years")); 
}else{
    setcookie("visit", $_COOKIE['visit']+1, time()+strtotime("+30 years")); 

}

$action = filter_input(INPUT_GET, 'action',FILTER_SANITIZE_STRING );

if(isset($_COOKIE['PHPSESSID']) && $_COOKIE['PHPSESSID']!=0 && $action==false && isset($_SESSION['id'])){
  $action = 'show_admin_menu';
}else if($action != false){
  //use the action from get method
}else{
  //ask user to login
  $action='login';
}

switch($action) {
  /*
  case 'login':
      //In case login was successful
      //header("Location: .");

      //In case ogin was not successful
      $login_message = 'You must login to view this page.';
      include('view/login.php');
      break;
      */
  case 'show_admin_menu':
      header('Location:menu.php');
      ob_end_flush();
      exit();
  case 'logout':
      //Session destory
      unset($_SESSION['id']);
      unset($_SESSION['firstname']);
      unset($_SESSION['email']);
      unset($_SESSION['userid']);
      session_unset();
      session_destroy(); 
      $login_message = 'You have been logged out.';
      header('Location:index.php');
      break;
}

        ob_end_flush();

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
            border-radius: 20px;
           
        }

        .right {
            right: 0;
            background-color: #f5f5f5;
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
    
    <div class="split left">
        <div class="centered">
        <img src="car.jpg" alt="UCMO">
        </div>
    </div>

    <div class="split right">
        <div class="centered">
        <div class="center">
      <h1>Login</h1>
      <form method="post">
        <div class="txt_field">
          <input type="text" name="username"required>
          <span></span>
          <label>Username</label>
        </div>
        <div class="txt_field">
          <input type="password" name="password" required>
          <span></span>
          <label>Password</label>
        </div>
        <!--div class="pass">Forgot Password?</div><!-->
        <input type="submit" value="Login">
        <div class="signup_link">
          Not a member? <a href="signup.php">Signup</a>
        </div>
      </form>
    </div>
        </div>
    </div>
     
</body>
</html>
