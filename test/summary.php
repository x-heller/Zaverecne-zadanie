<?php
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
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3><?php echo $login?></h3>
            <h1>Test Complete || Test bol odoslaný</h1>
            <h3>Points gained || Body získané</h3>
            <p><?php echo $pointsgained; ?>/<?php echo $points;?></p>
            <h3>Test Summary || Podrobnosti</h3>
            <p class="title">Test ID || Čislo testu</p>
            <p><?php echo $testid;?></p>
            <p class="title">Section ID || Čislo príkladu</p>
            <p><?php echo $section;?></p>

            <h3>Your answer was || Vas odpoveď bol</h3>
            <math-field id="mf"><?php echo $solution;?></math-field>



            <a id="button" href="../logout.php">Odhlásiť sa</a>
            <a id="button" href="../student.php" >Späť na testy</a>
        </div>
    </div>
</div>
</body>

</html>



