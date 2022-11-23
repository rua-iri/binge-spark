<?php
session_start();
include("../connection.php");


$genreId = htmlentities($_GET["genre_id"]);

//SQL query to select genre name
$genreSelectQuery = "SELECT * FROM genre WHERE genre_id = '$genreId';";
$genreRes = $conn->query($genreSelectQuery);
$genreData = $genreRes->fetch_assoc();
$genreName = $genreData['genre_name'];

//SQL query to select film data for films in this genre
$filmSelectQuery = "SELECT * 
FROM film_genre
INNER JOIN film
ON film_genre.film_id = film.film_id
WHERE film_genre.genre_id = $genreId";

$filmRes = $conn->query($filmSelectQuery);

$numRows = $filmRes->num_rows;

/*
while ($filmData = $filmRes->fetch_assoc()) {
    echo "<p>${filmData['film_title']}</p>";
}
*/


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | <?= $genreName ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<?php include("../layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">

        <div class="section has-text-centered">

            <h1 class="title">
                <?= $genreName ?> Films
            </h1>


            <div class="columns">


                <?php

                $elementCounter = 1;

                echo "<div class='column'>";
                // echo half of the results in one column
                for ($i = 0; $i < ($numRows / 2); $i++) {
                    $filmData = $filmRes->fetch_assoc();

                    echo "<p class='pb-2 genre-result is-size-5' id='genre-film-$elementCounter'>";
                    echo "<a href='../film.php?film_id=${filmData['film_id']}'>";
                    echo $filmData['film_title'];
                    echo "</a></p>";

                    $elementCounter+=2;
                }
                echo "</div>";

                $elementCounter = 2;

                echo "<div class='column'>";
                // echo the other half in another column
                while ($filmData = $filmRes->fetch_assoc()) {

                    echo "<p class='pb-2 genre-result is-size-5' id='genre-film-$elementCounter'>";
                    echo "<a href='../film.php?film_id=${filmData['film_id']}'>";
                    echo $filmData['film_title'];
                    echo "</a></p>";

                    $elementCounter+=2;
                }
                echo "</div>";

                //add extra rows so that the javascript works properly
                if(($numRows%20)!=0) {
                    $extraRows = $numRows;
                    while (($extraRows % 20) != 0) {
                        $extraRows++;
                        echo "<p class='genre-result' id='genre-film-$extraRows'> </p>";
                    }
                }

                ?>



            </div>

            <div class="has-text-centered">

                <button class="button mt-4 is-medium" id="genre-prev-button" onclick="prevGenreResults()">
                <p class="is-size-2">👈</p>
                </button>
                <button class="button mt-4 is-medium" id="genre-next-button" onclick="nextGenreResults()">
                <p class="is-size-2">👉</p>
                </button>

            </div>


        </div>

    </div>

    <script src="../layout/genrescript.js"></script>

    <?php include("../layout/footer.php"); ?>
</body>

</html>