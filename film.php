<?php
session_start();
include("connection.php");

$filmId = htmlentities($_GET["film_id"]);

//select queries to get all film data
$selectFilmQuery = "SELECT * 
FROM film
INNER JOIN film_actor
ON film.film_id = film_actor.film_id
INNER JOIN actor
ON film_actor.actor_id = actor.actor_id
WHERE film.film_id = $filmId";

$filmGenreQuery = "SELECT * 
FROM film
INNER JOIN film_genre
ON film.film_id = film_genre.film_id
INNER JOIN genre
ON film_genre.genre_id = genre.genre_id
WHERE film.film_id=$filmId";

$filmDirectorQuery = "SELECT * 
FROM film
INNER JOIN film_director
ON film.film_id = film_director.film_id
INNER JOIN director
ON film_director.director_id = director.director_id
WHERE film.film_id = $filmId";

//run the film Queries
$filmResult = $conn->query($selectFilmQuery);
$filmGenreResult = $conn->query($filmGenreQuery);
$filmDirectorResult = $conn->query($filmDirectorQuery);


//Relay information about an error inputting review to the user
if (isset($_SESSION['reviewerror'])) {
    $reviewError = $_SESSION['reviewerror'];
    unset($_SESSION['reviewerror']);
} else {
    $reviewError = "";
}


// SQL query to select all user reviews for this film
// the results will be looped through at the bottom of the page
$selectReviewsQuery = "SELECT *
FROM review
INNER JOIN user
ON review.user_id = user.user_id
WHERE film_id = $filmId";

$reviewsRes = $conn->query($selectReviewsQuery);




//check that user is logged in first
if (isset($_SESSION['loggedInId'])) {
    //show buttons if user is logged in
    $disa = "";

    //SQL query to determine if the user has favorited the film
    $favSelectQ = "SELECT * FROM favorites
    WHERE user_id = ${_SESSION['loggedInId']} AND film_id = $filmId;";
    $favSelectRes = $conn->query($favSelectQ);

    //change button text depending on whether
    //the user has favorited the film or not
    if (($favSelectRes->num_rows) > 0) {
        $buttonStyle = "is-danger";
        $buttonText = "Remove From Watchlist";
    } else {
        $buttonStyle = "is-primary";
        $buttonText = "Add to Watchlist";
    }
} else {
    //disable buttons if user isn't logged in
    $disa = "disabled";
    $buttonStyle = "is-primary";
    $buttonText = "Add to Watchlist";
}



//variables for the general film details
$filmDetails = $filmResult->fetch_assoc();

$filmT = $filmDetails['film_title'];
$rYear = $filmDetails['release_year'];
$rTime = $filmDetails['runtime'];
$revenue = $filmDetails['revenue'];
$imgPath = $filmDetails['film_image_path'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | <?= $filmDetails['film_title'] ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php include("layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <br>

    <div class="container">

        <div class="section">


            <div class="columns">


                <div class="column is-5 mt-5">

                    <h1 class="title is-2">
                        <?= $filmT ?>
                    </h1>

                    <p class="subtitle">
                        <?php
                        //display tagline if it is stored in database
                        if (isset($filmDetails['film_tagline'])) {
                            echo "<p class='is-size-6 pb-5'>(${filmDetails['film_tagline']})<p>";
                        }
                        ?>
                    </p>

                    <h2 class='subtitle is-4'>
                        Release Year: <?= "<a href='search/yearsearch.php?year=$rYear'>$rYear</a>" ?>
                    </h2>


                    <h2 class='subtitle is-4'>
                        Runtime: <?= $rTime ?> Minutes
                    </h2>

                    <?php
                    //only display revenue if data is stored in the database
                    if (isset($revenue)) {
                        echo "<h2 class='subtitle is-4'>";
                        echo "Revenue: $$revenue million";
                        echo "</h2>";
                    }
                    ?>

                    <form class="py-3" action="process/processfavorite.php" method="POST">
                        <input type="hidden" name="filmid" value="<?= $filmId ?>">
                        <div class="field">
                            <div class="control">
                                <input class="button is-medium <?= $buttonStyle ?>" type="submit" value="<?= $buttonText ?>" <?= $disa ?>>
                            </div>
                        </div>
                    </form>

                    <?php
                    //only display user rating if the film has been rated
                    if (isset($filmDetails['film_rating'])) {
                        echo "<h2 class='is-size-4 has-text-weight-bold'>User Rating: ${filmDetails['film_rating']}</h2>";
                    }
                    ?>


                    <div class="notification is-size-5">
                        <p class="has-text-weight-semibold">Summary:</p>
                        <p><?= $filmDetails['film_summary'] ?></p>
                    </div>

                    <div class="mt-5 pb-2">
                        <h1 class="title is-4">Genre</h1>
                        <?php
                        //loop through all genres the film belongs to
                        while ($filmGenreDetails = $filmGenreResult->fetch_assoc()) {
                            echo "<p class='is-size-5'><a href='search/genresearch.php?genre_id=${filmGenreDetails['genre_id']}'>";
                            echo $filmGenreDetails['genre_name'];
                            echo "</a></p>";
                        }
                        ?>
                    </div>

                    <div class="columns mt-5">
                        <div class="column">
                            <h1 class="title is-4">Directed by:</h1>
                            <?php
                            //loop through director(s)
                            while ($filmDirectorDetails = $filmDirectorResult->fetch_assoc()) {
                                echo "<p class='is-size-5'><a href='director.php?director_id=${filmDirectorDetails['director_id']}'>";
                                echo $filmDirectorDetails['dir_first_name'] . " " . $filmDirectorDetails['dir_last_name'];
                                echo "</a></p>";
                            }
                            ?>
                        </div>



                        <div class="column">
                            <h1 class="title is-4">Cast</h1>
                            <p class='is-size-5'>
                                <a href="actor.php?actor_id=<?= $filmDetails['actor_id'] ?>">
                                    <?= $filmDetails['actor_first_name'] . " " . $filmDetails['actor_last_name'] ?>
                                </a>
                            </p>
                            <?php
                            //a while loop to list all the actors in the film
                            while ($filmDetails = $filmResult->fetch_assoc()) {
                                echo "<p class='is-size-5'> <a href='actor.php?actor_id=${filmDetails['actor_id']}'>";
                                echo $filmDetails['actor_first_name'] . " " . $filmDetails['actor_last_name'];
                                echo "</a> </p>";
                            }

                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php
                //show film poster if its path is stored in database
                if (isset($imgPath)) {
                    include("layout/filmposter.php");
                }

                ?>
            </div>




        </div>


        <div class="section">

            <h1 class="title">User Reviews</h1>
            <button class="button is-primary" id="rev-modal-button" <?= $disa ?>>Review <?= $filmT ?></button>

            <div class="columns is-multiline mt-3">

                <?php

                //check if there are user reviews or not
                if ($reviewsRes->num_rows == 0) {
                    echo "<h2 class='title is-4 mt-4'>No users have reviewed this film yet...</h2>";
                } else {

                    while ($revData = $reviewsRes->fetch_assoc()) {
                        $reviewDate = strtotime($revData['review_timestamp']);
                        $reviewDate = gmdate("j/M/Y", $reviewDate);
                        $reviewerId = $revData['user_id'];

                        echo "<div class='column is-4-desktop'><div class='card has-text-centered'><div class='card-content'>";


                        echo "<p class='title'><a href='user/user.php?user_id=$reviewerId'>${revData['username']}</a></p>";
                        echo "<p class='subtitle'>${revData['review_rating']} - ";
                        echo "\"${revData['review_text']}\"</p></div>";

                        echo "<div class='card-footer pt-3 pb-3'>
                <p class='card-footer-item'>($reviewDate)</p>
                </div>";
                        echo "</div></div>";
                        echo "<br>";
                    }
                }
                ?>
            </div>

        </div>


    </div>


    <?php include("layout/footer.php");
    include("layout/reviewform.php"); ?>

    <script type="text/javascript" src="layout/reviewscript.js"></script>

</body>

</html>
