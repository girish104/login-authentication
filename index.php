<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="css/style.css">
  </head>
<body>
  <div class="container">
    <?php if(isset($_SESSION['email'])): ?>
      <h2>Welcome <?php echo $_SESSION['email'];?></h2>
      <a href="logout.php">Logout</a>
    <?php else : ?>
      <h2>Welcome to the Land of Doraemon!</h2>
      <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to continue!</p>  
    <?php endif ; ?>
  </div>
</body>
</html>