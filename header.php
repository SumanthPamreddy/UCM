<?php
include('pdo.php');
session_set_cookie_params(86400); 

session_start();

if (isset($_SESSION['id'])) {
    $username = $_SESSION['firstname'];
    $logoutUrl = 'index.php?action=logout'; 
} else {
    $username = 'Guest';
    $loginUrl = 'index.php'; 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">   
        header {
            background-color: #f5f5f5;
            position: fixed;
            left: 0;
            right: 0;
            top: 0px;
            height: 40px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        header * {  
            display: inline;  
        }  

        header li {  
            margin: 20px;  
        }   

        header li a {  
            color: black;  
            text-decoration: none;  
        }  

        header img {
            width: 30px;
            height: auto;
        }

        

        
    </style>   
</head>  
<body>   
    <header>  
        <nav>  
            <ul class="left-section">
                <li>  
                    <a href="index.php">
                        <img src="UCM.png" alt="Logo">
                    </a>
                </li>  
            </ul>

            <ul class="right-section">  
                <li>
                    <?php if (isset($username)): ?>
                        <a href="editprofile.php">Welcome, <?php echo $username; ?>!</a>
                    <?php endif; ?>
                </li>
                <li>
                    <a href="bookings.php">
                        Bookings
                    </a>
                </li>

                <li>
                    <?php if (isset($logoutUrl)): ?>
                        <a href="<?php echo $logoutUrl; ?>">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo $loginUrl; ?>">Login</a>
                    <?php endif; ?>
                </li>
               
            </ul>  
        </nav>  
    </header>  
</body>   
</html>
