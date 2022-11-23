<?php
session_start();
include("connection.php");

$topSelect = "SELECT *
FROM film
ORDER BY film_rating DESC
LIMIT 25;";

$topResult = $conn->query($topSelect);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Top 25</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php include("layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">

        <div class="section has-text-centered">

            <h1 class="title">BingeSpark Top 25 Films</h1>
            <h2 class="subtitle">Ranked By BingeSpark users</h2>

            <?php
            $filmCount = 1;
            
            while($topData = $topResult->fetch_assoc()){
                $fTitle = $topData['film_title'];
                $fYear = $topData['release_year'];
                $fRat = $topData['film_rating'];
                $fId = $topData['film_id'];

                echo "<div class='pt-3 pb-3'><h1 class='title is-size-4'><a href='film.php?film_id=${fId}'>";
                echo "${filmCount}. ${fTitle} (${fYear}) - ${fRat}";
                echo "</a></h1></div>";

                $filmCount++;
            }

            ?>
        </div>

    </div>

    <?php include("layout/footer.php"); ?>
</body>

</html>