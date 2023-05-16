<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher"){
}
else{
    header("location: login.php");
}
?>