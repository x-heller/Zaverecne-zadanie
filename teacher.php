<?php

session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher"){
    //echo "Hello teacher";
}
else{
    header("location: login.php");
}

$files = scandir('assignments');

require_once "config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("TRUNCATE TABLE assignments");
    $stmt->execute();

    $selectedFiles = $_POST["selected_files"];

    foreach ($selectedFiles as $file) {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $format = pathinfo($file, PATHINFO_EXTENSION);

        $filename = $name . "." .$format;

        $stmt = $pdo->prepare("INSERT INTO assignments (filename) VALUES (?)");
        $stmt->execute([$filename]);
    }

    // Redirect or display a success message
    // header("Location: success.php");
    // echo "Files inserted successfully!";
    // You can choose to redirect the user to another page or display a success message here

    // Redirect to prevent duplicate form submission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher</title>

    <link rel="stylesheet" href="beauty.css">
</head>
<body>
    <h1 id="title">Teacher portal</h1>
    <h3 id="name"><?php echo $_SESSION["fullname"]?></h3>

    <div id="tableDiv">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>Available assignments</th>
                    <th>Max points</th>
                    <th>From-To</th>

                </tr>
                </thead>

                <tbody>
                <?php foreach ($files as $file): ?>
                    <?php if ($file !== '.' && $file !== '..'): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_files[]" value="<?php echo $file; ?>"></td>
                            <td id="asName"><?php echo pathinfo($file, PATHINFO_FILENAME); ?></td>

                            <td><input type="number" id="max-points" name="max_points" min="0" max="10"></td>
                            <td><input type="date" id="from" name="from"> <input type="date" id="to" name="to"></td>
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

