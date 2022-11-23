<?php
session_start();
include("../connection.php");

$userId = htmlentities($_GET['user_id']);

//retrive user information from the database
$userSelectQuery = "SELECT *
FROM user
WHERE user.user_id = '$userId'";

$userSelectRes = $conn->query($userSelectQuery);

if (!$userSelectRes) {
    echo $conn->error;
}

$userData = $userSelectRes->fetch_assoc();

//convert joined date to a better format using GMT
$joinDate = strtotime($userData['joined_timestamp']);
$joinDate = gmdate("jS M Y", $joinDate);


//select data relating to user's reviews
$selectReviewQuery = "SELECT * 
FROM review 
INNER JOIN film
ON review.film_id = film.film_id
WHERE user_id = $userId";
$reviewRes = $conn->query($selectReviewQuery);

$numReviews = $reviewRes->num_rows;

//select data relating to user's favorites
$selectFavQuery = "SELECT * FROM favorites
INNER JOIN film
ON favorites.film_id = film.film_id
WHERE user_id = $userId;";
$selectFavRes = $conn->query($selectFavQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | <?= $userData['username'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<?php include("../layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">

        <div class="section">
            <h1 class="title is-2 mt-5">
                Username:
                <?= $userData['username'] ?>
            </h1>
            <h1 class="title is-5">
                Email:
                <?= $userData['user_email'] ?>
            </h1>
            <h1 class="title is-5">
                Member since:
                <?= $joinDate ?>
            </h1>

            <h1 class="title is-5">
                Reviews Posted:
                <?= $numReviews ?>
            </h1>
        </div>

        <div class="section has-text-centered">
            <h1 class="title is-size-4"><?= $userData['username'] ?>'s Watchlist</h1>

            <div class="columns is-multiline">

            <?php

            if($selectFavRes->num_rows > 0) {
                //if user has favorited films
                while($favData = $selectFavRes->fetch_assoc()) {
                    echo "<div class='column is-4 user-fav'><div class='card'><div class='card-header'>";

                    echo "<p class='card-header-title is-centered'>
                    <a href='../film.php?film_id=${favData['film_id']}'>${favData['film_title']}</a>
                    </p>";

                    echo "</div></div></div>";
                }
                echo "<div class='column'>";
                echo "<button class='button is-medium' id='show-fav-button'>Show Watchlist</button>";
                echo "</div>";

            } else {
                //if user has no favorite films
                echo "<div class='column'>";
                echo "<p class='is-size-5'>${userData['username']} has not added any films to their watchlist</p>";
                echo "</div>";
            }

            ?>

            </div>


        </div>


        <div class="section has-text-centered">
            <?php
            //show reviews section only if user has posted any reviews

            if ($numReviews > 0) {

                echo "<h1 class='title is-size-4'>${userData['username']}'s Reviews</h1>";
                echo "<div class='columns is-multiline'>";

                while ($reviewData = $reviewRes->fetch_assoc()) {
                    echo "<div class='column is-4 user-rev'><div class='card has-text-centered'><div class='card-header'>";

                    echo "<p class='card-header-title is-centered'>
                        <a href='../film.php?film_id=${reviewData['film_id']}'>${reviewData['film_title']}</a>
                        </p>";

                    echo "</div><div class='card-content'>";
                    echo "<p>${reviewData['review_rating']} - ${reviewData['review_text']}</p>";
                    echo "</div><div class='card-footer'>";
                    echo "<p class='card-footer-item'>${reviewData['review_timestamp']}</p>";

                    echo "</div></div></div>";
                }
                

                echo "<div class='column'>";
                echo "<button class='button is-medium' id='show-review-button'>Show Reviews</button>";
                echo "</div></div>";
            }

            ?>

        </div>

    </div>

    <?php include("../layout/footer.php"); ?>

    <script src="../layout/userscript.js"></script>
</body>

</html>