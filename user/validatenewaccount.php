<?php
session_start();
include("../connection.php");

//create local variables from the previous form and real escape them
$usrName = $conn->real_escape_string($_POST['newUsrName']);
$passWord1 = $conn->real_escape_string($_POST['newUsrPass1']);
$passWord2 = $conn->real_escape_string($_POST['newUsrPass2']);
$email = $conn->real_escape_string($_POST['newEmail']);

//remove whitespace from the user's input
$usrName = trim($usrName);
$passWord1 = trim($passWord1);
$passWord2 = trim($passWord2);
$email = trim($email);


//check that no users have the same username or password
$noDuplicatesSelect = "SELECT * FROM user WHERE username='$usrName' OR user_email='$email'";

$duplicatesRes = $conn->query($noDuplicatesSelect);
$duplicateEntry = $duplicatesRes->num_rows;

//check that user's data is valid
if ($duplicateEntry > 0) {
    //if user with the same username or password already exists
    $_SESSION['newUsrName'] = $usrName;
    $_SESSION['newEmail'] = $email;
    $_SESSION['createerror'] = "Another user already exists with the same username or email";
    header("Location: createaccount.php");

} elseif ($passWord1 != $passWord2) {
    //if passwords don't match
    $_SESSION['newUsrName'] = $usrName;
    $_SESSION['newEmail'] = $email;
    $_SESSION['createerror'] = "Passwords don't match";
    header("Location: createaccount.php");

} elseif((strlen($passWord1))<6) {
    //if password is shorter than 6 letters
    $_SESSION['newUsrName'] = $usrName;
    $_SESSION['newEmail'] = $email;
    $_SESSION['createerror'] = "Passwords must be at least 6 characters long";
    header("Location: createaccount.php");

}elseif (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
    //if email format invalid
    $_SESSION['newUsrName'] = $usrName;
    $_SESSION['createerror'] = "Invalid email";
    header("Location: createaccount.php");

} else {
    /*
    * This code will execute if the users details have not failed the previous checks
    * Their details will be added to the database
    */

    //hash user's password
    $passWordHashed = password_hash($passWord1, PASSWORD_DEFAULT);


    //insert new record into database
    $insertQuery = "INSERT INTO user (username, password, user_email) VALUES ('$usrName', '$passWordHashed', '$email');";

    $insertRes = $conn->query($insertQuery);

    if(!$insertRes) {
        echo $conn->error;
    }

    //store values in session variables to send to the next page
    $_SESSION['username'] = $usrName;
    $_SESSION['password'] = $passWordHashed;


    //redirect to sign.php to log the user in automatically
    header("Location: sign.php");


}


?>
