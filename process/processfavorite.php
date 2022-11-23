<?php
session_start();
include("../connection.php");

//redirect to homepage if user is not logged in
if(!isset($_SESSION['loggedInId'])) {
    header("Location: ../index.php");
}

$filmId = $_POST['filmid'];
$userId = $_SESSION['loggedInId'];

//check if user has favorited the film before
$favSelectQuery = "SELECT * 
FROM favorites
WHERE user_id = $userId AND film_id = $filmId;";
$favSelectRes = $conn->query($favSelectQuery);



if ($favSelectRes->num_rows > 0) {

    //delete favorite from database
    $favDeleteQuery = "DELETE FROM favorites
    WHERE user_id = $userId AND film_id = $filmId;";

    $favDeleteRes = $conn->query($favDeleteQuery);


} else {

    //insert new favorite into database
    $favInsertQuery = "INSERT INTO favorites
(user_id, film_id) VALUES ('$userId', '$filmId');";

    $favInsertRes = $conn->query($favInsertQuery);

}


//redirect back to the film page
header("Location: ../film.php?film_id=$filmId");
