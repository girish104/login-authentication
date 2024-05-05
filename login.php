<?php
session_start(); // Start the session
use Delight\Auth\Auth;

include_once('conn/db.php');
require __DIR__ . '/vendor/autoload.php';




if(isset($_SESSION['email'])){
    header("Location: index.php");
}

$auth = new Auth($db);



$errors = []; // Array to store errors

$errorMessages = [
    2 => 'Invalid or expired verification link',
    3 => 'Verification link expired',
    4 => 'User already exists',
    5 => 'Too many requests'
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set and not empty
    if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        try {

            $auth->login($_POST['email'], $_POST['password']);
            
            $_SESSION['email'] = $_POST['email'];  
            header("Location: index.php");
            exit();

        } catch (\Delight\Auth\InvalidEmailException $e) {
            $errors[] = 'Wrong email address';
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $errors[] = 'Wrong password';
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $errors[] = 'Email not verified';
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $errors[] = 'Too many requests';
        }
    } else {
        $errors[] = 'Email and password are required.';
    }
}

// if there is an error code in the URL and add corresponding error message to errors array
if (isset($_GET['error']) && isset($errorMessages[$_GET['error']])) {
    $errors[] = $errorMessages[$_GET['error']];
}

// if there is a success message in the URL and add it to errors array (for display purposes)
if (isset($_GET['success'])) {
    $errors[] = $_GET['success'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<section>
    <div class="container">
      <div class="user signinBx">
        <div class="imgBx"><img src="images/front.jpg" alt="" /></div>
        <div class="formBx">
          <form action="login.php" method="post">
            <h2>Sign In</h2>
            <?php foreach ($errors as $error): ?>
              <p class="error"><?php echo "*" .$error; ?></p>
            <?php endforeach; ?>
            <input type="text" name="email" placeholder="email" required/>
            <input type="password" name="password" placeholder="Password" required/>
            <input type="submit" name="login" value="Login" />
            <p class="signup">
              Don't have an account ?
              <a href="register.php">Sign Up.</a>
            </p>
          </form>
        </div>
      </div>
    </div>
</section>
</body>
</html>


