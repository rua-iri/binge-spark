<?php
session_start();

//check that user is in fact logged in
if(!isset($_SESSION['loggedIn'])) {
    header("Location: ../index.php");
} else {
    //destroy session to log user out
    session_destroy();
    header("Location: ../index.php");
}


?>