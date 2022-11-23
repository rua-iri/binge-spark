<?php
session_start();
include("../connection.php");

//determine whether the user has been redirected by validatenewaccount.php or login.php
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {

    //select data from database about new user
    $selectNewUsrQ = "SELECT * FROM user WHERE username = '${_SESSION['username']}' AND password = '${_SESSION['password']}'";
    $selectNewUsrRes = $conn->query($selectNewUsrQ);
    $newUsrData = $selectNewUsrRes->fetch_assoc();

    //unset previous session variables
    unset($_SESSION['username']);
    unset($_SESSION['password']);

    $_SESSION['loggedIn'] = $newUsrData['username'];
    $_SESSION['loggedInId'] = $newUsrData['user_id'];

    //redirect to the homepage
    header("Location: ../index.php");


} else {
    //the user has been redirected from login.php

    //assign user's input to local variables and real escape them
    $usrName = $conn->real_escape_string($_POST['userfield']);
    $passWord = $conn->real_escape_string($_POST['passfield']);

    //remove whitespace from user input
    $usrName = trim($usrName);
    $passWord = trim($passWord);


    //Query to check if user exists in database
    $checkUsrQuery = "SELECT * FROM user WHERE username='$usrName';";

    $usrRes = $conn->query($checkUsrQuery);

    if (!$usrRes) {
        echo $conn->error;
    }

    $numRows = $usrRes->num_rows;

    //user record exists in database
    if ($numRows > 0) {
        $usrData = $usrRes->fetch_assoc();

        //check that user's password matches
        if (password_verify($passWord, $usrData['password'])) {
            $_SESSION['loggedIn'] = $usrName;
            $_SESSION['loggedInId'] = $usrData['user_id'];
            header("Location: ../index.php");
        } else {
            $_SESSION['userfield'] = $usrName;
            $_SESSION['passfield'] = $passWord;
            $_SESSION['loginerror'] = "Invalid Username or password";
            header("Location: login.php");
        }
    } else {
        $_SESSION['userfield'] = $usrName;
        $_SESSION['passfield'] = $passWord;
        $_SESSION['loginerror'] = "Invalid Username or password";
        header("Location: login.php");
    }
}
