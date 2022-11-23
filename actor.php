<?php
session_start();
include("connection.php");

$actorId = htmlentities($_GET['actor_id']);

//select actor and their films in chronological order
$selectActorQuery = "SELECT *
FROM actor
INNER JOIN film_actor
ON actor.actor_id = film_actor.actor_id
INNER JOIN film
ON film_actor.film_id = film.film_id
WHERE actor.actor_id = $actorId
ORDER BY film.release_year DESC";

$actorResult = $conn->query($selectActorQuery);

if (!$actorResult) {
    echo $conn->error;
}

$actorDetails = $actorResult->fetch_assoc();

//variables for actor details
$actorName = $actorDetails['actor_first_name'] . " " . $actorDetails['actor_last_name'];
$photoPath = $actorDetails['actor_image_path'];
$actorBio = $actorDetails['biography'];
$bornDate = $actorDetails['birth_date'];
$diedDate = $actorDetails['death_date'];
$birthPlace = $actorDetails['birthplace'];
$gender;


//process born and died data
if (isset($bornDate)) {
    $bornDate = date("jS F Y", strtotime($bornDate));
}

if (isset($diedDate)) {
    $diedDate = date("jS F Y", strtotime($diedDate));
}


//process gender data
if (isset($actorDetails['gender'])) {
    $gender = "Female";
    if($actorDetails['gender'] ==0) {
    
    } else if ($actorDetails['gender'] == 1) {
    $gender = "Male";
    }
} else {
    $gender = null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | <?= $actorName ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="style.css">
</head>


<?php include("layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <br>

    <div class="container">

        <div class="section">
            <div class="columns mt-2">

                <div class="column is-5">

                    <h1 class="title is-2 mt-5"><?= $actorName ?></h1>

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
                        <h1 class="title is-5">Filmography</h1>

                        <p class="is-size-5">
                            <a href="film.php?film_id=<?= $actorDetails['film_id'] ?>">
                                <?= $actorDetails['film_title'] . " (${actorDetails['release_year']})" ?>
                            </a>
                        </p>
                        <?php
                        //loop through the rest of the actor's filmography
                        while ($actorDetails = $actorResult->fetch_assoc()) {
                            echo "<p class='is-size-5'> <a href='film.php?film_id=${actorDetails['film_id']}'>";
                            echo $actorDetails['film_title'];
                            echo " (${actorDetails['release_year']})";
                            echo "</a></p>";
                        }
                        ?>


                    </div>
                </div>

                <?php
                //only display actor photo if the path is set
                if(isset($photoPath)) {
                    include("layout/actorphoto.php");
                }

                ?>

            </div>

            <div class="card mt-3">
                <?php

                if (isset($actorBio)) {
                    echo "<div class='card-header'>";
                    echo "<p class='card-header-title title has-text-weight-semibold is-centered'>Biography</p>";
                    echo "</div>";
                    echo "<p class='card-content'>$actorBio</p>";
                }
                ?>

            </div>


        </div>

    </div>

    <?php include("layout/footer.php"); ?>

</body>

</html>
