<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher") {
    //echo "Hello teacher";
} else {
    header("location: login.php");
}

require_once "config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

$sql = "SELECT * FROM student";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql2 = "SELECT * FROM users WHERE type = 'student'";
$stmt2 = $pdo->query($sql2);
$users = $stmt2->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher</title>
    <link rel="stylesheet" href="beauty.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script defer src="//unpkg.com/mathlive"></script>

    <style>
        html, body {
            background-color: #f4f4f4;
            font-family: Calibri, monospace;
        }
        .headerTR th {
            border-bottom: black solid 1px;
            border-right: black solid 1px;
        }

        .tableTR td {
            border-right: black solid 1px;
        }


        th.last{
            border-right: none;
        }
        td.last{
            border-right: none;
        }

        .resultTable td{
            height: 40px;
            border-right: black solid 1px;
        }
        .tableDiv{
            width: 80%;
            margin: auto;
            border: white solid 1px;
            border-radius: 10px;
            padding: 10px;
            background: white;
            box-shadow: 0 1px 2px 1px #ddd;
        }
        .butt{
            text-decoration: none;
            line-height: 50px;
            background-color: #000000;
            color: white;
            padding: 10px;
        }
        .butt:hover{
            text-decoration: none;
            color: white;
        }
        #mf{
            background: white;
            border:none;
        }
    </style>
</head>
<body>
<h1 id="title">Teacher portal</h1>
<h3 id="name"><?= $_SESSION["fullname"] ?></h3>
<div id="buttoncontainer">
    <a id="button" class="butt" href="logout.php">logout</a>
    <a id="button" class="butt" href="teacher_info.php">User guide</a>
    <a id="button" class="butt" href="teacher.php">Back</a>
</div>

<br>
<div class="tableDiv">
    <table class="table">
        <thead>
        <tr class="headerTR">
            <th>Meno a priezvisko</th>
            <th>ID(login)</th>
            <th>Vygenerované testy</th>
            <th>Odovzdané testy</th>
            <th class="last">Všetky body</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 0;
        foreach ($users as $user) {
            //get the student fullname from users database where login is the same as student login
            /*$sql = "SELECT fullname FROM users WHERE login = :login";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['login' => $student["login"]]);
            $studentsName = $stmt->fetch(PDO::FETCH_ASSOC);*/

            //get the number of generated tests
            $sql = "SELECT COUNT(*) FROM generate WHERE login = :login";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['login' => $user["login"]]);
            $generated = $stmt->fetch(PDO::FETCH_ASSOC);
            //echo $generated["COUNT(*)"];

            //get the number of submitted tests
            $sql2 = "SELECT COUNT(*) FROM odovzdane WHERE login = :login";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute(['login' => $user["login"]]);
            $submitted = $stmt2->fetch(PDO::FETCH_ASSOC);
            //echo $submitted["COUNT(*)"];

            //get the number of points
            $sql3 = "SELECT SUM(points) FROM student WHERE login = :login";
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute(['login' => $user["login"]]);
            $points = $stmt3->fetch(PDO::FETCH_ASSOC);
            //echo $points["SUM(points)"];

            echo "<tr class='tableTR'>";
            echo "<td>".$user['fullname']."</td>";
            echo "<td>".$user["login"]."</td>";
            echo "<td>".$generated["COUNT(*)"]."</td>";
            echo "<td>".$submitted["COUNT(*)"]."</td>";
            echo "<td class='last'>".$points["SUM(points)"]."</td>";
            echo "</tr>";
            $count++;
        }
        ?>
        </tbody>
    </table>
</div>
<br>
<div class="tableDiv">
    <table class="table">
        <thead>
        <tr class="headerTR">
            <th>Meno a priezvisko</th>
            <th>ID(login)</th>
            <th>Answer</th>
            <th>Point</th>
            <th>Test ID</th>
            <th>Section</th>
            <th class="last"></th>
        </tr>
        </thead>

        <tbody>
        <?php
        $count = 0;
        foreach ($students as $student) {
            //get the student fullname from users database where login is the same as student login
            $sql = "SELECT fullname FROM users WHERE login = :login";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['login' => $student["login"]]);
            $studentsName = $stmt->fetch(PDO::FETCH_ASSOC);

            $correct = "images/correct.png";
            $failed = "images/failed.png";

            if ($student["points"] > 0){
                $imageSrc = $correct;
            } else {
                $imageSrc = $failed;
            }
            $lastCol = "<td class='last' style='border-right: none'><img src='$imageSrc' style='width: 25px'></td>";

            $solution = $student["answer"];
            echo "<tr class='tableTR, resultTable'>";
            echo "<td>".$studentsName['fullname']."</td>";
            echo "<td>".$student["login"]."</td>";
            echo "<td>"."<math-field id='mf' readonly='true'>$solution</math-field>"."</td>";
            echo "<td>".$student["points"]."</td>";
            echo "<td>".$student["testid"]."</td>";
            echo "<td>".$student["section"]."</td>";
            echo $lastCol;
            echo "</tr>";
            $count++;
        }
        ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('table').DataTable();
    });
</script>
</body>
</html>

