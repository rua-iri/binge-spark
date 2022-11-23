<?php
session_start();
include("connection.php");

//query to select all the genres in the database sorted alphabetically
$genreSelectQuery = "SELECT * FROM genre ORDER BY genre_name ASC;";
$genreRes = $conn->query($genreSelectQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Genres</title>
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
                    Genres
                </h1>
            </div>

            <div class="columns is-multiline mt-3">

                <?php
                

                while ($genreData = $genreRes->fetch_assoc()) {
                    $genreId = $genreData['genre_id'];

                    echo "<div class='column is-4-desktop'><div class='card has-text-centered'><div class='card-content'>";
                    echo "<a href='search/genresearch.php?genre_id=$genreId'>";
                    echo $genreData['genre_name'];
                    echo "</a></div></div></div>";
                }

                ?>

            </div>




        </div>

    </div>

    <?php include("layout/footer.php"); ?>

</body>

</html>
