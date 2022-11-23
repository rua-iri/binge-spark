<?php
session_start();
include("connection.php");

//select all films sorted by the 
$filmSelectQuery = "SELECT release_year FROM film GROUP BY release_year ORDER BY release_year DESC;";
$filmSelectRes = $conn->query($filmSelectQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Search By year</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php include("layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">
        <br>

        <div class="section has-text-centered">


            <div>
                <h1 class="title is-2">
                    Years
                </h1>
            </div>

            <div class="columns is-multiline mt-3">

                <?php

                while ($yearData = $filmSelectRes->fetch_assoc()) {
                    $year = $yearData['release_year'];

                    echo "<div class='column is-4-desktop'><div class='card has-text-centered'><div class='card-content'>";
                    echo "<a href='search/yearsearch.php?year=$year'>";
                    echo $year;
                    echo "</a></div></div></div>";
                }

                ?>

            </div>




        </div>

    </div>

    <?php include("layout/footer.php"); ?>

</body>

</html>