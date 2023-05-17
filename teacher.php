<?php
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

    $selectedFiles = $_POST["selected_files"];
    $maxPoints = $_POST["max_points"];
    $fromDates = $_POST["from"];
    $toDates = $_POST["to"];

    $isValid = true;
    foreach ($selectedFiles as $key => $file) {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $format = pathinfo($file, PATHINFO_EXTENSION);
        $filename = $name . "." . $format;
        $maxPoint = $maxPoints[$key];
        $fromDate = !empty($fromDates[$key]) ? $fromDates[$key] : null;
        $toDate = !empty($toDates[$key]) ? $toDates[$key] : null;

        if (empty($maxPoint)) {
            $isValid = false;
            break;
        }

        $stmt = $pdo->prepare("INSERT INTO assignments (filename, point, time_from, time_to) VALUES (?, ?, ?, ?)");
        $stmt->execute([$filename, $maxPoint, $fromDate, $toDate]);
    }

    if ($isValid) {
        // Redirect or display a success message
        // header("Location: success.php");
        // echo "Files inserted successfully!";
        // You can choose to redirect the user to another page or display a success message here

        // Redirect to prevent duplicate form submission
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $warningMessage = "Please provide maximum points for all checked assignments.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher</title>
    <link rel="stylesheet" href="beauty.css">
    <!--<script>
        function validateForm() {
            var checkboxes = document.getElementsByName("selected_files[]");
            var maxPoints = document.getElementsByName("max_points[]");

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked && maxPoints[i].value === "") {
                    alert("Please provide maximum points for all checked assignments.");
                    return false;
                }
            }

            return true;
        }
    </script>-->
</head>
<body>
<h1 id="title">Teacher portal</h1>
<h3 id="name"><?php echo $_SESSION["fullname"]?></h3>
<div id="tableDiv">
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
        <?php if (isset($warningMessage)): ?>
            <div style="background-color: red; color: white"><?php echo $warningMessage; ?></div>
        <?php endif; ?>
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
        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
