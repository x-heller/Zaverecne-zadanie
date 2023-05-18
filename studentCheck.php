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
        .headerTR th {
            border-bottom: black solid 1px;
            border-right: black solid 1px;
        }

        .tableTR td {
            border-right: black solid 1px;
        }

        td.last,
        th.last {
            border-right: none;
        }
    </style>
</head>
<body>
<h1 id="title">Teacher portal</h1>
<h3 id="name"><?= $_SESSION["fullname"] ?></h3>
<div id="buttoncontainer">
    <a id="button" href="logout.php">logout</a>
    <a id="button" href="teacher_info.php">User guide</a>
    <a id="button" href="teacher.php">Back</a>
</div>

<br>
<div class="tableDiv">
    <table class="table">
        <thead>
        <tr class="headerTR">
            <th>Meno</th>
            <th>Priezvisko</th>
            <th>ID(login)</th>
            <th>Vygenerované</th>
            <th>Odovzdané</th>
            <th class="last">Všetky body</th>
        </tr>
        </thead>
    </table>
</div>
<br>
<div class="tableDiv">
    <table class="table">
        <thead>
        <tr class="headerTR">
            <th>Meno Priezvisko</th>
            <th>ID(login)</th>
            <th>Answer</th>
            <th>Point</th>
            <th>Test ID</th>
            <th class="last">Section</th>
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

            $solution = $student["answer"];
            echo "<tr class='tableTR'>";
            echo "<td>".$studentsName['fullname']."</td>";
            echo "<td>".$student["login"]."</td>";
            echo "<td>"."<math-field id='mf' readonly='true'>$solution</math-field>"."</td>";
            echo "<td>".$student["points"]."</td>";
            echo "<td>".$student["testid"]."</td>";
            echo "<td class='last'>".$student["section"]."</td>";
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

