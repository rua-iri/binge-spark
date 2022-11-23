<?php
session_start();
include("../connection.php");

$searchTerm = $conn->real_escape_string($_GET['searchfor']);

//generate sql queries to search in database
$filmSearchQuery = "SELECT *
FROM film
WHERE film_title LIKE '%$searchTerm%'
ORDER BY film_rating;";

$actorSearchQuery = "SELECT *
FROM actor
WHERE actor_first_name LIKE '%$searchTerm%'
OR actor_last_name LIKE '%$searchTerm%';";

$dirSearchQuery = "SELECT *
FROM director
WHERE dir_first_name LIKE '%$searchTerm%'
OR dir_last_name LIKE '%$searchTerm%';";


// run queries in database
$filmSearchRes = $conn->query($filmSearchQuery);
$actorSearchRes = $conn->query($actorSearchQuery);
$dirSearchRes = $conn->query($dirSearchQuery);


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Search for "<?=$searchTerm?>"</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">

</head>

<?php include("../layout/navbar.php"); ?>


<body class="has-navbar-fixed-top">

    <div class="container">
        <br>

        <div class="section">

            <div>
                <h1 class="title is-2">
                    Results for "<?= $searchTerm ?>"
                </h1>
            </div>

            <br>

            <div class="columns has-text-centered mt-5">

                <div class="column">

                    <?php
                    //print search results for films
                    echo "<h1 class='title'>Films</h1>";

                    if ($filmSearchRes->num_rows == 0) {
                        //if there are no results
                        echo "No films found";
                    } else if ($filmSearchRes->num_rows < 10) {
                        //if there are some results but less than a full page
                        while ($searchFilm = $filmSearchRes->fetch_assoc()) {
                            echo "<p class='is-size-6'><a href='../film.php?film_id=${searchFilm['film_id']}'>";
                            echo $searchFilm['film_title'];
                            echo "</a></p>";
                            echo "<br>";
                        }
                    } else {
                        // if there is at least a full page of results
                        $numResults = $filmSearchRes->num_rows;
                        $filmData = $filmSearchRes->fetch_all();
                        $extraRows = 0;

                        if (($numResults % 10) != 0) {
                            $extraRows = $numResults;
                            while (($extraRows % 10) != 0) {
                                $extraRows++;
                            }
                        }

                        //for loop to cycle through the films
                        for ($i = 0; $i < $numResults; $i++) {
                            $film_id = $filmData[$i][0];
                            $resNum = $i + 1;
                            echo "<p class='is-size-6 mt-3 film-s-result' id='f-result-$resNum'><a href='../film.php?film_id=${film_id}'>";
                            echo $filmData[$i][1];
                            echo "</a></p>";
                        }

                        //add extra rows so that the javascript works better
                        for ($i = ($numResults + 1); $i <= $extraRows; $i++) {
                            echo "<p class='film-s-result' id='f-result-$i'> </p>";
                        }

                    }
                    ?>

                    <button class='button mt-4 is-medium' id='film-back-button' onclick='prevFilmResults()'>
                    <p class="is-size-2">👈</p>
                    </button>

                    <button class='button mt-4 is-medium' id='film-next-button' onclick='nextFilmResults()'>
                    <p class="is-size-2">👉</p>
                    </button>

                </div>


                <div class="column">

                    <?php
                    //print search results for actors
                    echo "<h1 class='title'>Actors</h1>";

                    //if no results are found
                    if ($actorSearchRes->num_rows == 0) {
                        //if there are no results
                        echo "No actors found";
                    } else if ($actorSearchRes->num_rows < 10) {
                        //if there is less than a full page of results
                        while ($searchActor = $actorSearchRes->fetch_assoc()) {
                            echo "<p class='is-size-6'><a href='../actor.php?actor_id=${searchActor['actor_id']}'>";
                            echo $searchActor['actor_first_name'] . " " . $searchActor['actor_last_name'];
                            echo "</a></p>";
                            echo "<br>";
                        }
                    } else {
                        // if there is at least a full page of results
                        $numResults = $actorSearchRes->num_rows;
                        $actorData = $actorSearchRes->fetch_all();
                        $extraRows = 0;

                        //calculate how many extra rows are need for javascript
                        if (($numResults % 10) != 0) {
                            $extraRows = $numResults;
                            while (($extraRows % 10) != 0) {
                                $extraRows++;
                            }
                        }

                        //for loop to cycle through the actors
                        for ($i = 0; $i < $numResults; $i++) {
                            $actor_id = $actorData[$i][0];
                            $resNum = $i + 1;
                            echo "<p class='is-size-6 mt-3 actor-s-result' id='a-result-$resNum'><a href='../actor.php?actor_id=${actor_id}'>";
                            echo $actorData[$i][1] . " " . $actorData[$i][2];
                            echo "</a></p>";
                        }

                        //add extra rows so that the javascript works better
                        for ($i = ($numResults + 1); $i <= $extraRows; $i++) {
                            echo "<p class='actor-s-result' id='a-result-$i'> </p>";
                        }

                    }
                    ?>

                    <button class='button mt-4 is-medium' id='actor-back-button' onclick='prevActorResults()'>
                    <p class="is-size-2">👈</p>
                    </button>

                    <button class='button mt-4 is-medium' id='actor-next-button' onclick='nextActorResults()'>
                    <p class="is-size-2">👉</p>
                    </button>
                </div>

                <div class="column">

                    <?php
                    //print search results for directors
                    echo "<h1 class='title'>Directors</h1>";

                    //if no results are found
                    if ($dirSearchRes->num_rows == 0) {
                        //if there are no results
                        echo "No directors found";
                    } else if ($dirSearchRes->num_rows < 10) {
                        //if there is less than a full page of results
                        while ($searchDir = $dirSearchRes->fetch_assoc()) {
                            echo "<p class='is-size-6'><a href='../director.php?director_id=${searchDir['director_id']}'>";
                            echo $searchDir['dir_first_name'] . " " . $searchDir['dir_last_name'];
                            echo "</a></p>";
                            echo "<br>";
                        }
                    } else {
                        // if there is at least a full page of results
                        $numResults = $dirSearchRes->num_rows;
                        $dirData = $dirSearchRes->fetch_all();
                        $extraRows = 0;

                        //calculate how many extra rows are need for javascript
                        if (($numResults % 10) != 0) {
                            $extraRows = $numResults;
                            while (($extraRows % 10) != 0) {
                                $extraRows++;
                            }
                        }

                        //for loop to cycle through the actors
                        for ($i = 0; $i < $numResults; $i++) {
                            $dir_id = $dirData[$i][0];
                            $resNum = $i + 1;
                            echo "<p class='is-size-6 mt-3 dir-s-result' id='d-result-$resNum'><a href='../director.php?director_id=$dir_id'>";
                            echo $dirData[$i][1] . " " . $dirData[$i][2];
                            echo "</a></p>";
                        }

                        //add extra rows so that the javascript works better
                        for ($i = ($numResults + 1); $i <= $extraRows; $i++) {
                            echo "<p class='dir-s-result' id='d-result-$i'> </p>";
                        }

                    }
                    ?>

                    <button class='button mt-4 is-medium' id='dir-back-button' onclick='prevDirResults()'>
                    <p class="is-size-2">👈</p>
                    </button>

                    <button class='button mt-4 is-medium' id='dir-next-button' onclick='nextDirResults()'>
                    <p class="is-size-2">👉</p>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../layout/search_page_script.js"></script>
    <script>
        var sBar = document.getElementsByClassName("search-bar")[0];
        sBar.style.transition = "none";
        sBar.classList.toggle("search-bar-active");
        sBar.setAttribute("value", "<?= $searchTerm ?>");
    </script>

    <?php include("../layout/footer.php"); ?>
</body>

</html>
