<?php
//errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher") {
    //echo "Hello teacher";
} else {
    header("location: login.php");
}

require_once "config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

$sql = "SELECT * FROM student";
$stmt = $pdo->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql2 = "SELECT fullname FROM users";
$stmt2 = $pdo->prepare($sql2);
$studentsName = $stmt2->fetchAll(PDO::FETCH_ASSOC);
echo $studentsName[0];


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
        .headerTR th{
            border-bottom: black solid 1px;
            border-right: black solid 1px;
        }
        .tableTR td{
            border-right: black solid 1px;
        }
        td.last, th.last{
            border-right: none;
        }
    </style>
</head>
<body>
<h1 id="title">Teacher portal</h1>
<h3 id="name"><?php echo $_SESSION["fullname"]?></h3>
<div id="buttoncontainer">
    <a id="button" href="logout.php">logout</a>
    <a id="button" href="teacher_info.php">User guide</a>
    <a id="button" href="teacher.php">Back</a>
</div>

<br>
<div id="tableDiv">
    <table>
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
<div id="tableDiv">
    <!--class="table table-bordered table-striped  table-hover"-->
    <table>
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
        foreach ($students as $student){
            $solution = $student["answer"];
            //echo $solution;
            echo "<tr class='tableTR'>";
            echo "<td>".$studentsName[$student]."</td>"; //ez a neve tobbi jo
            echo "<td>".$student["login"]."</td>";
            echo "<td>"."<math-field id='mf' readonly='true' > $solution </math-field>"."</td>";
            echo "<td>".$student["points"]."</td>";
            echo "<td>".$student["testid"]."</td>";
            echo "<td class='last'>".$student["section"]."</td>";
            echo "</tr>";
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
