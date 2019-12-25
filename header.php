<?php
//common header for all files
//start a session
session_start();
echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    ';
require_once('functions.php');
$userstr = ' (Guest)';
if (isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $loggedin = TRUE; 
    $userstr = " ($user)";
}
else $loggedin = FALSE;

echo "
    <title>$appname$userstr</title>
    <link rel='stylesheet' href='static/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='static/fontawesome/css/all.min.css'>
    <script src='static/js/jquery.min.js'></script>
    </head>
    <body>
    <div class='container'>
    <div class='appname'>
    <h1>
    $appname$userstr
    </h1>
    </div>
";

if($loggedin){
    echo "
    <nav class='navbar navbar-expand-lg navbar-light bg-light'>
    <a class='navbar-brand' href='#'>Dovet</a>
    <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNavAltMarkup' aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>
        <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarNavAltMarkup'>
        <div class='navbar-nav'>
        <a class='nav-item nav-link active' href='members.php?view=$user'>Home <span class='sr-only'>(current)</span></a>
        <a class='nav-item nav-link' href='members.php'>Members</a>
        <a class='nav-item nav-link' href='friends.php'>Friends</a>   
        <a class='nav-item nav-link' href='messages.php'>Messages</a>
        <a class='nav-item nav-link' href='profile.php'>Edit Profile</a>  
        <a class='nav-item nav-link' href='logout.php'>Log Out</a>
        </div>
    </div>
    </nav>
    ";
}
else{
    echo '
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
        <a class="nav-item nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link" href="signup.php">Sign Up</a>  
        <a class="nav-item nav-link" href="login.php">Log In</a>
        </div>
    </div>
    </nav>
    ';

    echo "<div class='alert alert-info m-5'>&#8658; You must be logged in to " .
        "view this page.</div>";

}
?>