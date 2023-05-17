<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher"){
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
</head>
<body>
<h1 id="title">Infoportal</h1>
<h3 id="name"><?php echo $_SESSION['fullname']?></h3>
<div id="buttoncontainer">
    <a id="button" href="logout.php">logout</a>
    <a id="button" onclick="window.print()">Download as PDF</a>
</div>
<div id="infocontainer">
    <h2>Funcions a Teacher can use</h2>
    <ul>
        <li>Define from which latex files the student will be able to generate tests</li>
        <li>Define how many points a student can get for which set of tests</li>
        <li>View a summary table of all students</li>
        <li>View which tasks each student generated, the result and the points</li>
    </ul>
    <h3>Define from which latex files the student will be able to generate tests</h3>
    <p>The teacher will be able to define from which latex files the student will be able to generate tests for solving and in which period he will be able to generate them. Each set of tests can have a different date when it can be used. If the date is not specified, the generation of tests from this set is open.</p>
    <h3>Define how many points a student can get for which set of tests</h3>
    <p>Define how many points a student can get for which set of tests (all tests defined in one file will have the same rating, i.e. this rating will also have a test generated for the student).</p>
    <h3>View a summary table of all students</h3>
    <p>The teacher can view a summary table of all students (name, surname, student ID) with information on how many tasks each student generated, how many they submitted and how many points they received for them.
        Students can be sorted according to all the above information (in case of equality of numerical values, the sorting according to the surname is taken as the second criterion).</p>
    <h3>View which tasks each student generated, the result and the points</h3>
    <p>The teacher can view which tasks each student generated, the result and the points. The teacher can also view the generated test in PDF format.</p>
</div>
<br>
<br>
<div id="infocontainer">
    <h2>Funkcionality studenta</h2>
    <ul>
        <li>Definovať, z ktorých latexových súborov si bude môcť študent generovať príklady</li>
        <li>Zadefinovať, koľko bodov môže študent získať, za ktorú sadu príkladov</li>
        <li>Prezerať prehľadnú tabuľku všetkých študentov</li>
        <li>Prezerať, aké úlohy si ktorý študent vygeneroval, vysledok a body </li>
    </ul>
    <h3>Definovať, z ktorých latexových súborov si bude môcť študent generovať príklady</h3>
    <p>Učiteľ bude mať možnosť definovať, z ktorých latexových súborov si bude môcť
        študent generovať príklady na riešenie a v ktorom období si ich bude môcť generovať.
        Každá sada príkladov môže mať iný dátum, kedy môže byť použitá. Ak dátum
        nebude určený, tak generovanie príkladov z tejto sady je otvorené.
    </p>
    <h3>Zadefinovať, koľko bodov môže študent získať</h3>
    <p>Zadefinovať, koľko bodov môže študent získať, za ktorú sadu príkladov (všetky
        príklady zadefinované v jednom súbore budú mať rovnaké hodnotenie, t.j. toto
        hodnotenie bude mať aj príklad vygenerovaný pre študenta).
    </p>
    <h3>Prezerať prehľadnú tabuľku všetkých študentov</h3>
    <p>Uciteľ si môže prezerať prehľadnú tabuľku všetkých študentov (meno, priezvisko, ID študenta) s informáciou, koľko úloh si ktorý študent vygeneroval, koľko ich odovzdal a koľko za ne zźskal bodov.
        Studentov bude možné zotriedovať podľa všetkých vyššie uvedených informácií (pri rovnosti číselnývch hodnôt sa ako druhé kritérium berie zoradenie podľa priezviska). Túto tabuľku je potrebné exportovať aj do CSV súboru.</p>
    <h3>Prezerať, aké úlohy si ktorý študent vygeneroval, vysledok a body</h3>
    <p>Uciteľ si môže prezerať, aké úlohy si ktorý študent vygeneroval, aké odovzdal, odovzdaný výsledok
        spolu s informáciou, či bol správny a koľko získal za ktorú úlohu bodov</p>
</div>
<br>
<br>
</body>
</html>

