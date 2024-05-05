<?php
include_once('conn/db.php');

use Delight\Auth\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/vendor/autoload.php';

 if(isset($_SESSION['email'])){
    header("Location: index.php");
}

$auth = new Auth($db);
// Define variables to store messages and errors
$message = '';
$errors = [];

// Check if the form is submitted for user registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Check if passwords match
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errors[] = 'Passwords do not match';
    } else {
        try {
            // Register the user
            $userId = $auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                // Send verification email
                sendVerificationEmail($_POST['email'], $selector, $token);
            });

            $message = 'User registered successfully. Please check your email to verify your account.';
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $errors[] = 'Invalid email address';
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $errors[] = 'Invalid password';
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $errors[] = 'User already exists';
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $errors[] = 'Too many requests';
        }
    }
}

// Function to send verification email
function sendVerificationEmail($email, $selector, $token) {
    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // SMTP server
        $mail->SMTPAuth   = true;                 // Enable SMTP authentication
        $mail->Username   = 'YOUR GMAIL';   // SMTP username
        $mail->Password   = 'YOUR PASSWORD';       // SMTP password
        $mail->SMTPSecure = 'tls';                
        $mail->Port       = 587;                  

        // Sender
        $mail->setFrom('YOUR GMAIL', 'NAME');

        // Recipient
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = 'Verification Email';
        $mail->Body    = 'Click the following link to verify your email: <a href="http://localhost/main/verify.php?selector=' . urlencode($selector) . '&token=' . urlencode($token) . '">Verify Email</a>';

        // Send the email
        $mail->send();
        $message = 'User registered successfully. Please check your email to verify your account.';
    } catch (Exception $e) {
        $message = "Email could not be sent. Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<section>
    <div class="container">
        <div class="user signupBx">
            <div class="formBx">
                <form action="register.php" method="POST">
                    <h2>Create an account</h2>
                    <?php if (!empty($errors)): ?>
                        <?php foreach ($errors as $error): ?>
                            <p class="error"><?php echo "*" .$error; ?></p>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($message !== ''): ?>
                        <p class="message"><?php echo "*" .$message; ?></p>
                    <?php endif; ?>
                    <input type="text" name="username" placeholder="Username" required/>
                    <input type="email" name="email" placeholder="Email Address" required/>
                    <input type="password" name="password" placeholder="Create Password" required/>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required/>
                    <input type="submit" name="register" value="Sign Up" />
                    <p class="signup">
                        Already have an account ?
                        <a href="login.php">Sign in.</a>
                    </p>
                </form>
            </div>
            <div class="imgBx"><img src="images/back.jpg" alt="" /></div>
        </div>
    </div>
</section>
</body>
</html>



