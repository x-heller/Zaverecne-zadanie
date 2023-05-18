<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher") {
    //echo "Hello teacher";
} else {
    header("location: login.php");
}

$files = scandir('assignments');

require_once "config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $stmt = $pdo->prepare("TRUNCATE TABLE assignments");
    $stmt->execute();
    //if there are no selected files, dont do nothing
    if (!isset($_POST["selected_files"])) {
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    else{

    $selectedFiles = $_POST["selected_files"];
    $maxPoints = $_POST["max_points"];
    $fromDates = $_POST["from"];
    $toDates = $_POST["to"];
    // in foreach check all files if they are in selected files array if they are upload them to database
    $count = 0;
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            if (in_array($file, $selectedFiles)) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $format = pathinfo($file, PATHINFO_EXTENSION);
                $filename = $name . "." . $format;
                $fromDate = $fromDates[$count];
                // If 'from' date is not set, set it to null
                if ($fromDate == "") {
                    $fromDate = null;
                }
                $toDate = $toDates[$count];
                // If 'to' date is not set, set it to null
                if ($toDate == "") {
                    $toDate = null;
                }
                $stmt = $pdo->prepare("INSERT INTO assignments (filename, time_from, time_to, point) VALUES (?, ?, ?, ?)");
                $stmt->execute([$filename, $fromDate, $toDate, $maxPoints[$count]]);
            }
            $count++;
        }
    }




    $isValid = true;
    //create a foreach for files and check if  selected files are in the array


    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
    /*if ($isValid) {
        // Redirect or display a success message
        // header("Location: success.php");
        // echo "Files inserted successfully!";
        // You can choose to redirect the user to another page or display a success message here

        // Redirect to prevent duplicate form submission
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $warningMessage = "Please provide maximum points for all checked assignments.";
    }*/
}}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher</title>
    <link rel="stylesheet" href="beauty.css">

    <script>
        function validateForm() {
            var checkboxes = document.getElementsByName("selected_files[]");
            var maxPoints = document.getElementsByName("max_points[]");

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked && maxPoints[i].value === "") {
                    //alert("Please provide maximum points for all checked assignments.");
                    document.getElementById("warning").innerHTML = "Please provide maximum points for all checked assignments.";
                    return false;
                }
            }

            return true;
        }

        document.getElementsByTagName('td').st
    </script>

</head>
<body>
<h1 id="title">Teacher portal</h1>
<h3 id="name"><?php echo $_SESSION["fullname"]?></h3>
<div id="buttoncontainer">
    <a id="button" href="logout.php">Logout</a>
    <a id="button" href="teacher_info.php">User guide</a>
    <a id="button" href="studentCheck.php">Check Students</a>
</div>
<div id="tableDiv">
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
        <?php /*if (isset($warningMessage)): */?><!--
            <div style="background-color: red; color: white"><?php /*echo $warningMessage; */?></div>
        --><?php /*endif; */?>
        <div id="warning" style="background-color: red; color: white"></div>
        <table>
            <thead>
            <tr>
                <th></th>
                <th>Available assignments</th>
                <th>Max points</th>
                <th>From</th>
                <th>To</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($files as $file): ?>
                <?php if ($file !== '.' && $file !== '..'): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_files[]" value="<?php echo $file; ?>"></td>
                        <td id="asName"><?php echo pathinfo($file, PATHINFO_FILENAME); ?></td>
                        <td><input type="number" id="max-points" name="max_points[]" min="0" max="10"></td>
                        <td><input type="date" id="from" name="from[]"></td>
                        <td><input type="date" id="to" name="to[]"></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button id="faszgomb" type="submit">Save</button>
    </form>
</div>
</body>
</html>
