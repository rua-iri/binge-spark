<?php
session_start();
include("connection.php");

//get director id from the page's url
$directorId = htmlentities($_GET['director_id']);

//select data about director from the database
$selectDirQuery = "SELECT * FROM director WHERE director_id = $directorId";
$selectDirRes = $conn->query($selectDirQuery);
$dirData = $selectDirRes->fetch_assoc();

//assign data from that query to variables
$dirName = $dirData['dir_first_name'] . " " . $dirData['dir_last_name'];
$photoPath = $dirData['dir_image_path'];
$dirBio = $dirData['biography'];
$bornDate = $dirData['birth_date'];
$diedDate = $dirData['death_date'];
$birthPlace = $dirData['birthplace'];
$gender;

//process born and died data
if (isset($bornDate)) {
    $bornDate = date("jS F Y", strtotime($bornDate));
}

if (isset($diedDate)) {
    $diedDate = date("jS F Y", strtotime($diedDate));
}

//process gender data
if (isset($dirData['gender'])) {
    $gender = "Female";
    if($dirData['gender'] ==0) {
    
    } else if ($dirData['gender'] == 1) {
    $gender = "Male";
    }
} else {
    $gender = null;
}



//select data about director's films from database
$dirFilmSelectQ = "SELECT * FROM film_director
INNER JOIN film
ON film_director.film_id = film.film_id
WHERE film_director.director_id = $directorId
ORDER BY film.release_year DESC";
$dirFilmRes = $conn->query($dirFilmSelectQ);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | <?= $dirName ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php include("layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">

        <div class="section">

            <div class="columns mt-2">

                <div class="column is-5">

                    <h1 class="title is-2 mt-5"><?=$dirName?></h1>

                    <div class="notification is-size-5">
                        <?php
                        //check that data is available before displaying it
                        if (isset($gender)) {
                            echo "<p>Gender: $gender</p>";
                        }

                        if (isset($bornDate)) {
                            echo "<p>Born: $bornDate</p>";
                        }

                        if (isset($birthPlace)) {
                            echo "<p>Birthplace: $birthPlace</p>";
                        }

                        if (isset($diedDate)) {
                            echo "<p>Died: $diedDate</p>";
                        }
                        ?>
                    </div>






                    <div>
                        <h1 class="title is-5">Films Directed</h1>

                        <?php
                        //loop through the films directed 
                        while ($dirFilmDetails = $dirFilmRes->fetch_assoc()) {
                            echo "<p class='is-size-5'> <a href='film.php?film_id=${dirFilmDetails['film_id']}'>";
                            echo $dirFilmDetails['film_title'];
                            echo " (${dirFilmDetails['release_year']})";
                            echo "</a></p>";
                        }
                        ?>


                    </div>



                </div>

                <?php
                //only display director photo if the path is set
                if(isset($photoPath)) {
                    include("layout/dirphoto.php");
                }

                ?>


            </div>

            <div class="card mt-3">
                <?php

                if (isset($dirBio)) {
                    echo "<div class='card-header'>";
                    echo "<p class='card-header-title title has-text-weight-semibold is-centered'>Biography</p>";
                    echo "</div>";
                    echo "<p class='card-content'>$dirBio</p>";
                }
                ?>

            </div>


        </div>
    </div>

    <?php include("layout/footer.php"); ?>
</body>

</html>
