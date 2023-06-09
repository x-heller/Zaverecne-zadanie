<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student portal</title>
    <link rel="stylesheet" href="beauty.css">
</head>
<body>
<h1 id="title">Student portal</h1>
<h3 id="name"><?php echo $_SESSION["fullname"]?></h3>
<div id="buttoncontainer">
    <a id="button" href="logout.php">Logout</a>
    <a id="button" href="student_info.php">User guide</a>
    <style>
        html, body {
            background-color: #f4f4f4;
            font-family: Calibri, monospace;
        }
        #table{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-collapse: collapse;
            width: 100%;
            margin: auto;
        }
        tbody{
            border: 5px solid white;
            border-radius: 10px;
            background: white;
            box-shadow: 0 1px 2px 1px #ddd;
        }
    </style>
</div>
</body>

<?php
//show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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
    //if in table student already did the assignment then instead of generate button write completed
    $sql2 = "SELECT * FROM student WHERE login = :login AND testid = :filename";
    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam(':login', $_SESSION['login']);
    $stmt->bindParam(':filename', $row['filename']);
    $stmt->execute();
    //get the points from sql2
    $points = $stmt->fetchColumn(3);
    //check if the current time is between time_from and time_to
    // if time_from or time_to is not null
    if($row['time_from'] != null && $row['time_to'] != null){

    $time_from = strtotime($row['time_from']);
    $time_to = strtotime($row['time_to']);
    $current_time = strtotime(date("Y-m-d H:i:s"));
    if($current_time < $time_from || $current_time > $time_to){
        //instead of generate button take image from images/lock.png
        echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><img width=30px height=30px src='images/lock.png'></td></tr>";
        continue;


    }
    else {


        echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><a href='/zaverecne/test/asd.php?filename=".$row['filename']."'>Generate</a></td></tr>";
    }
    }
    else if ($row['time_from'] != null){
        $time_from = strtotime($row['time_from']);
        $current_time = strtotime(date("Y-m-d H:i:s"));
        if($current_time < $time_from){
            //instead of generate button take image from images/lock.png
            echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><img width=30px height=30px src='images/lock.png'></td></tr>";


            continue;


        }
        else {


            echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><a href='/zaverecne/test/asd.php?filename=".$row['filename']."'>Generate</a></td></tr>";
        }
    }
    else if( $row['time_to'] != null){
        $time_to = strtotime($row['time_to']);
        $current_time = strtotime(date("Y-m-d H:i:s"));
        if($current_time > $time_to){
            //instead of generate button take image from images/lock.png
            echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><img width=30px height=30px src='images/lock.png'></td></tr>";

            continue;


        }
        else {


            echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><a href='/zaverecne/test/asd.php?filename=".$row['filename']."'>Generate</a></td></tr>";
        }
    }
    if($stmt->rowCount() > 0){
        echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'>Completed, points: $points</td></tr>";
    }
    else if( $stmt->rowCount() == 0  && $row['time_from'] == null && $row['time_to'] == null){


        echo "<tr><td>".$row['filename']."</td><td>".$row['time_from']."</td><td>".$row['time_to']."</td><td>".$row['point']."</td><td id='lastrow'><a href='/zaverecne/test/asd.php?filename=".$row['filename']."'>Generate</a></td></tr>";
}}
echo "</table>";

?>


