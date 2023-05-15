<?php

session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["type"] == "teacher"){
    echo "Hello teacher";
}
else{
    header("location: login.php");
}

