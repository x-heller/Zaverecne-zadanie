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
</head>
<body>
    <h1>Teacher portal</h1>
    <h3><?php echo $_SESSION["fullname"]?></h3>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <table>
            <thead>
            <tr>
                <th>File Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $counter = 1;
            foreach ($files as $file):
                if ($file !== '.' && $file !== '..'):
                    $newName = 'Assignment' . $counter;
                    ?>
                    <tr>
                        <td><?php echo $newName; ?></td>
                        <td><input type="checkbox" name="selected_files[]" value="<?php echo $file; ?>"></td>
                    </tr>
                    <?php
                    $counter++;
                endif;
            endforeach;
            ?>
            </tbody>
        </table>
        <button type="submit">Save</button>
    </form>
</body>
</html>

