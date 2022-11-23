<?php
session_start();
include("../connection.php");

$year = htmlentities($_GET["year"]);

//SQL query to select all films release that year
$filmSelectQ = "SELECT * FROM film WHERE release_year = $year";
$filmSelectRes = $conn->query($filmSelectQ);

$numRow = $filmSelectRes->num_rows;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | <?= $year ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<?php include("../layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">
    <div class="container">

        <div class="section has-text-centered">

            <h1 class="title">
                Films Released in <?= $year ?>
            </h1>

            <div class="columns">

                <?php

                echo "<div class='column'>";

                // echo half of the results in one column
                for ($i = 0; $i < ($numRow / 2); $i++) {
                    $filmData = $filmSelectRes->fetch_assoc();

                    echo "<p class='pb-2 is-size-5'>";
                    echo "<a href='../film.php?film_id=${filmData['film_id']}'>";
                    echo $filmData['film_title'];
                    echo "</a></p>";
                }
                echo "</div>";


                echo "<div class='column'>";
                // echo the other half in another column
                while ($filmData = $filmSelectRes->fetch_assoc()) {

                    echo "<p class='pb-2 is-size-5'>";
                    echo "<a href='../film.php?film_id=${filmData['film_id']}'>";
                    echo $filmData['film_title'];
                    echo "</a></p>";

                }
                echo "</div>";


                ?>

            </div>

        </div>
    </div>

    <?php include("../layout/footer.php"); ?>
</body>

</html>