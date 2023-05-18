<?php
//error check
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$login = $_GET['login'];
$points = $_GET['points'];
$testid = $_GET['testid'];
$section = $_GET['section'];
$solution = $_GET['solution'];
$correct = $_GET['correct'];

if($correct=="false"){
    $pointsgained = 0;
}
else{
    $pointsgained = $points;
}

require_once "../config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
//check which user sent the test and add +1 to the number of tests sent
$sql = "SELECT * FROM users WHERE login = :login";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':login', $login);
$success = $stmt->execute();
if ($success) {
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $tests_sent = $row['odovzdanie'];
            $tests_sent = intval($tests_sent) + 1;
            $sql = "UPDATE users SET odovzdanie = :tests_sent WHERE id = :id";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->bindParam(':tests_sent', $tests_sent);
            $stmt2->bindParam(':id', $id);
            $success = $stmt2->execute();
        }
    }
}

$sql = "INSERT INTO student (login, answer, points, testid, section) VALUES (:login, :answer, :points, :testid,:section)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':login', $login);
$stmt->bindParam(':answer', $solution);
$stmt->bindParam(':points', $pointsgained);
$stmt->bindParam(':testid', $testid);
$stmt->bindParam(':section', $section);
$stmt->execute();
?>

<html>
<head>
    <title>Test</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../beauty.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_CHTML"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/9.4.4/math.js"></script>
    <script src="//unpkg.com/@cortex-js/compute-engine"></script>
    <script defer src="//unpkg.com/mathlive"></script>
</head>
<body>

<div class="container">
    <div class="row1">
        <div class="col-12">
            <h3 class="name"><?php echo $login?></h3>
            <h1 class="stitle">Test Complete || Test bol odoslaný</h1>
            <h3 class="bigtitle">Points gained || Body získané</h3>
            <p class="textt"><?php echo $pointsgained; ?>/<?php echo $points;?></p>
            <h3 class="bigtitle">Test Summary || Podrobnosti</h3>
            <p class="title">Test ID || Čislo testu</p>
            <p class="textt"><?php echo $testid;?></p>
            <p class="title">Section ID || Čislo príkladu</p>
            <p class="textt"><?php echo $section;?></p>

            <h3 class="bigtitle">Your answer was || Vas odpoveď bol</h3>
            <math-field id="mf1" readonly="true" ><?php echo $solution;?></math-field>


            <br>
            <div class="buttoncontainer2">
                <a id="button" href="../logout.php">Odhlásiť sa</a>
                <a id="button" href="../student.php" >Späť na testy</a>
            </div>
        </div>
    </div>
</div>
</body>

</html>



