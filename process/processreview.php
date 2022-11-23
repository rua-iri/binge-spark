<?php
session_start();
include("../connection.php");


//assign values to variables
$filmId = $_POST['filmid'];
$reviewText = $conn->real_escape_string($_POST['review']);

//check that user is logged in
if (isset($_SESSION['loggedIn'])) {
    $user = $_SESSION['loggedIn'];
} else {
    //redirect to homepage if user is not logged in
    header("Location: ../film.php?film_id=$filmId");
}

//check that review rating is valid and user has not manipulated the html
if ($_POST['rating'] >= 1 && $_POST['rating'] <= 10) {
    $reviewRating = $_POST['rating'];
} else {
    header("Location: ../film.php?film_id=$filmId");
}

//check that review text is not null
if ($reviewText == null) {
    header("Location: ../film.php?film_id=$filmId");
}

//check that user has not already reviewed this film
$selectUserReview = "SELECT * 
FROM review 
WHERE user_id = ${_SESSION['loggedInId']}
AND film_id = $filmId;";

$userReviewRes = $conn->query($selectUserReview);


if (($userReviewRes->num_rows) > 0) {
    //if the user has review this film before
    $_SESSION['reviewerror'] = "You have already reviewed this title";
    header("Location: ../film.php?film_id=$filmId");

} else {
    //if the user hasn't reviewed this film before
    //Create Insert Query
    $reviewInsertQuery = "INSERT INTO review 
(review_id, review_rating, review_text, user_id, film_id, review_timestamp) 
VALUES (NULL, '$reviewRating', '$reviewText', '${_SESSION['loggedInId']}', '$filmId', current_timestamp()); ";

    //perform insert query
    $reviewResult = $conn->query($reviewInsertQuery);

    if (!$reviewResult) {
        echo $conn->error;
    }

    //update overall film rating using the multi_query function

    $updateFilmScoreQuery = "SELECT @totalScore := SUM(review_rating) FROM `review` WHERE film_id = $filmId;
SELECT @numReview :=  COUNT(review_id) FROM `review` WHERE film_id = $filmId;
SELECT @avgReview := @totalScore / @numReview;
UPDATE film SET film_rating = @avgReview WHERE film_id = $filmId;";

    $filmScoreRes = $conn->multi_query($updateFilmScoreQuery);

    if (!$filmScoreRes) {
        echo $conn->error;
    }


    header("Location: ../film.php?film_id=$filmId");
}