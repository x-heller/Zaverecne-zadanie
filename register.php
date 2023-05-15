<?php
//show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ------- ------- ------- -------


// Konfiguracia PDO
require_once 'config.php';
// ------- Pomocne funkcie -------
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
$db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
function checkEmpty($field) {
    // Funkcia pre kontrolu, ci je premenna po orezani bielych znakov prazdna.
    // Metoda trim() oreze a odstrani medzery, tabulatory a ine "whitespaces".
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max) {
    // Funkcia, ktora skontroluje, ci je dlzka retazca v ramci "min" a "max".
    // Pouzitie napr. pre "login" alebo "password" aby mali pozadovany pocet znakov.
    $string = trim($field);     // Odstranenie whitespaces.
    $length = strlen($string);      // Zistenie dlzky retazca.
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}


function checkUsername($username) {
    // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}

//function checkGmail($email) {
//    // Funkcia pre kontrolu, ci zadany email je gmail.
//    if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
//        return false;
//    }
//    return true;
//}

function userExist($db, $login, $email) {
    // Funkcia pre kontrolu, ci pouzivatel s "login" alebo "email" existuje.
    $exist = false;

    $param_login = trim($login);
    $param_email = trim($email);

    $sql = "SELECT id FROM users WHERE login = :login OR email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);
    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $exist = true;
    }

    unset($stmt);

    return $exist;
}

// ------- ------- ------- -------



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    // Validacia username
    if (checkEmpty($_POST['login']) === true) {
        $errmsg .= "<p>Zadajte login.</p>";
    } elseif (checkLength($_POST['login'], 6,32) === false) {
        $errmsg .= "<p>Login musi mat min. 6 a max. 32 znakov.</p>";
    } elseif (checkUsername($_POST['login']) === false) {
        $errmsg .= "<p>Login moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
    }

    // Kontrola pouzivatela
    if (userExist($pdo, $_POST['login'], $_POST['email']) === true) {
        $errmsg .= "Pouzivatel s tymto e-mailom / loginom uz existuje.</p>";
    }

    // Validacia mailu
//    if (checkGmail($_POST['email'])) {
//        $errmsg .= "Prihlaste sa pomocou Google prihlasenia";
//        // Ak pouziva google mail, presmerujem ho na prihlasenie cez Google.
//        // header("Location: google_login.php");
//    }

    // TODO: Validacia hesla
    // TODO: Validacia mena, priezviska

    if (empty($errmsg)) {
        $sql = "INSERT INTO users (fullname, login, email, password, type) VALUES (:fullname, :login, :email, :password,:type)";

        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $type = $_POST['type'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
        echo $type;

        // Bind parametrov do SQL
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(":type",$type, PDO::PARAM_STR);
        $stmt->execute();

        unset($stmt);
    }
    unset($pdo);
}
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
    <title>Login/register s 2FA - Register</title>

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
        <h1>Registracia</h1>
        <h2>Vytvorenie noveho konta pouzivatela</h2>
    </hgroup>
</header>
<main>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <br>
        <label for="firstname">
            Meno:
            <input type="text" name="firstname" value="" id="firstname" placeholder="napr. Jonatan" required>
        </label>

        <br>
        <label for="lastname">
            Priezvisko:
            <input type="text" name="lastname" value="" id="lastname" placeholder="napr. Petrzlen" required>
        </label>

        <br>

        <label for="email">
            E-mail:
            <input type="email" name="email" value="" id="email" placeholder="napr. jpetrzlen@example.com" required>
        </label>

        <br>
        <label for="login">
            Login:
            <input type="text" name="login" value="" id="login" placeholder="napr. jperasin" required">
        </label>

        <br>

        <label for="password">
            Heslo:
            <input type="password" name="password" value="" id="password" required>
        </label>
        <br>
        <label for="type">
            Student:
            <input type="radio" name="type" value="student" checked>
            Teacher:
            <input type="radio" name="type" value="teacher">
        </label>

        <br>
        <button type="submit">Vytvorit konto</button>

        <?php
        if (!empty($errmsg)) {
            // Tu vypis chybne vyplnene polia formulara.
            echo $errmsg;
        }
        echo '<p>Teraz sa mozte prihlasit: <a href="login.php" role="button">Login</a></p>';
        ?>
    </form>
</main>
</body>
</html>