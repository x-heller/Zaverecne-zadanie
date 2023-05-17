<?php
$login = $_GET['login'];
$points = $_GET['points'];
$testid = $_GET['testid'];
$section = $_GET['section'];
$solution = $_GET['solution'];
$correct = $_GET['correct'];

echo $login, $points, $testid, $section, $solution, $correct,'<br>';
echo $correct;
if($correct=="false"){
    $pointsgained = 0;
}
else{
    $pointsgained = $points;
}
echo $pointsgained;

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


