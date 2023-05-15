<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "student"){
    echo "Hello student";
}
else{
    header("location: login.php");
}
?>



<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student</title>
</head>
<body>
<h1>Student</h1>
<a href="logout.php">logout</a>
<br>
<a href="test/asd.php">generate test</a>
</body>
