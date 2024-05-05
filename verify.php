<?php
use Delight\Auth\Auth;

include_once('conn/db.php');
require __DIR__ . '/vendor/autoload.php';

if(isset($_SESSION['email'])){
    header("Location: index.php");
}


$auth = new Auth($db);

// Retrieve the selector and token from the URL parameters
$selector = $_GET['selector'] ?? '';
$token = $_GET['token'] ?? '';

try {
    $auth->confirmEmail($selector, $token);
    
    $successCode = 1; 
} catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
    $errorCode = 2; 
} catch (\Delight\Auth\TokenExpiredException $e) {
    $errorCode = 3; 
} catch (\Delight\Auth\UserAlreadyExistsException $e) {
    $errorCode = 4; 
} catch (\Delight\Auth\TooManyRequestsException $e) {
    $errorCode = 5; 
}

// Redirect to login page with error or success code
if (isset($errorCode)) {
    header("Location: http://localhost/main/login.php?error=" . $errorCode);
    exit();
}

if (isset($successCode)) {
    header("Location: http://localhost/main/login.php?success=" . $successCode);
    exit();
}
