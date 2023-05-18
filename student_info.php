<?php
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
    <script src="download.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
</head>
<body>
<h1 id="title">Infoportal</h1>
<h3 id="name"><?php echo $_SESSION['fullname']?></h3>
<div id="buttoncontainer">
    <a id="button" href="logout.php">Logout</a>
    <a id="downloadeng">Download as PDF</a>
    <a id="downloadsk">Stiahnut ako PDF</a>
    <a id="button" href="student.php">Back</a>
</div>
<div id="downloade">
    <div id="infocontainer">
        <h2>Funcions a student can use</h2>
        <ul>
            <li>Generate test</li>
            <li>See your test results</li>
            <li>See your test history</li>
        </ul>
        <h3>Generate test</h3>
        <p>First you have to choose which assigned test you want to take. After you have chosen when you click on the generate test button you will be redirected to the test page where you can answer the questions. After you have answered and submitted all the questions you will see the test results.</p>
        <h3>See your test history</h3>
        <p>In the main hub you can see all the tests you have completed and the ones you did not. You can see the date and time you can take the test and if completed the result you got on the test.</p>
    </div>
</div>
    <br>
    <br>
<div id="downloads">
    <div id="infocontainer">
        <h2>Funkcionality studenta</h2>
        <ul>
            <li>Vygenerovanie príkladov na riešenie</li>
            <li>Výsledky testov</li>
            <li>Prehľad zadaných úloh</li>
        </ul>
        <h3>Vygenerovanie príkladov na riešenie</h3>
        <p>Najprv si musíte vybrať, ktorý test chcete absolvovať. Po výbere, keď kliknete na tlačidlo vygenerovať test, budete presmerovaní na stránku testu, kde môžete odpovedať na otázky. Po zodpovedaní a odoslaní všetkých otázok sa zobrazia výsledky testu.</p>
        <h3>História svojich testov</h3>
        <p>V hlavnom centre môžete vidieť všetky testy, ktoré ste dokončili, a tie, ktoré ste neurobili. Môžete si pozrieť dátum a čas, kedy môžete test vykonať, a ak ste ho dokončili, výsledok, ktorý ste v teste dosiahli.</p>
    </div>
</div>
<br>
<br>
</body>
</html>

