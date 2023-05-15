<?php
//show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION["loggedin"] = false;
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION["type"] == "student"){
        header("location: student.php");
        exit;
    }
    if($_SESSION["type"] == "teacher"){
        header("location: teacher.php");
        exit;
    }

    exit;
}

require_once "config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // TODO: Skontrolovat ci login a password su zadane (podobne ako v register.php).

    $sql = "SELECT fullname, email, login, password,type FROM users WHERE login = :login";

    $stmt = $pdo->prepare($sql);

    // TODO: Upravit SQL tak, aby mohol pouzivatel pri logine zadat login aj email.
    $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Uzivatel existuje, skontroluj heslo.
            $row = $stmt->fetch();
            $hashed_password = $row["password"];

            if (password_verify($_POST['password'], $hashed_password)) {
                // Heslo je spravne.

                    // Uloz data pouzivatela do session.
                    $_SESSION["loggedin"] = true;
                    $_SESSION["login"] = $row['login'];
                    $_SESSION["fullname"] = $row['fullname'];
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["type"] = $row['type'];

                    if($_SESSION["type"] == "student"){
                        header("location: student.php");
                    }
                    if($_SESSION["type"] == "teacher"){
                        header("location: teacher.php");
                    }

                    //  Presmeruj pouzivatela na zabezpecenu stranku.
                }
            } else {
                echo "Nespravne meno alebo heslo.";
            }
        } else {
            echo "Nespravne meno alebo heslo.";
        }
    } else {
        echo "Ups. Nieco sa pokazilo!";
    }
    unset($stmt);
    unset($pdo);
?>

<!doctype html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <title>Login/register s 2FA - Login</title>

    <style>
        html {
            max-width: 70ch;
            padding: 3em 1em;
            margin: auto;
            line-height: 1.75;
            font-size: 1.25em;
        }

        h1,h2,h3,h4,h5,h6 {
            margin: 1em 0 1em;
        }

        p,ul,ol {
            margin-bottom: 2em;
            color: #1d1d1d;
            font-family: sans-serif;
        }
    </style>
</head>
<body>
<header>
    <hgroup>
        <h1>Prihlasenie</h1>
        <h2>Prihlasenie pouzivatela po registracii</h2>
    </hgroup>
</header>
<main>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">

        <label for="login">
            Prihlasovacie meno:
            <input type="text" name="login" value="" id="login" required>
        </label>
        <br>
        <br>
        <label for="password">
            Heslo:
            <input type="password" name="password" value="" id="password" required>
        </label>
        <br>
        <button type="submit">Prihlasit sa</button>
    </form>
    <br>
    <p>Este nemate vytvorene konto?<br><br><a style="box-shadow: 0 1px 2px 1px #ddd;color: #545454;background-color: #ffffff;" class="btn btn-lg btn-google btn-block text-uppercase btn-outline" href="register.php">Registrujte sa tu</a></p>
</main>
</body>
</html>