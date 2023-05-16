<?php
//show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "student"){
}
else{
    header("location: login.php");
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student portal</title>
    <link rel="stylesheet" href="beauty.css">
</head>
<body>
<h1 id="title">Student portal</h1>
<h3 id="name"><?php echo $_SESSION['fullname']?></h3>
<div id="buttoncontainer">
<a id="button" href="logout.php">logout</a>
<a id="button" href="test/asd.php">generate test</a>
</div>
</body>
