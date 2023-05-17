
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
    <a id="button" href="student_info.php">User guide</a>
</div>
</body>

<?php
//show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require_once "config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "student"){
}
else{
    header("location: login.php");
}
$sql = "SELECT filename,time_from,time_to,point FROM assignments ";
//create a table and show all the assignments and generate button after each assignment
echo "<table id='table'>";
echo "<tr><th>Assignment</th><th>Time from</th><th>Time to</th><th>Points</th><th>Generate</th></tr>";
foreach ($pdo->query($sql) as $row) {
    echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td><a href='/zaverecne/test/asd.php?filename=".$row['filename']."'>Generate</a></td></tr>";
}
echo "</table>";

?>


