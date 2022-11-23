<?php
include("connection.php");



/*
Functions for adding to other tables
*/

//director function
function addUniqueDirector($fName, $sName)
{
    global $conn;

    //run a select query first to prevent duplication
    $checkSelQuery = "SELECT director_id FROM director WHERE dir_first_name='$fName' AND dir_last_name='$sName'";
    $dirInsertQuery = "INSERT INTO director (dir_first_name, dir_last_name) VALUES ('$fName', '$sName');";

    $selectResult = $conn->query($checkSelQuery);


    //check if director has already been added
    if (($selectResult->num_rows) > 0) {
        echo "<p>Director already in table</p>";
        
    } else {
        
        $insertRes = $conn->query($dirInsertQuery);

        if (!$insertRes) {
            echo $conn->error;
        }

        echo "<p>$dirInsertQuery</p>";
    }
}

//actor function
function addUniqueActor($fName, $sName)
{
    global $conn;

    //realescape apostrophe in data
    $fName = $conn->real_escape_string($fName);
    $sName = $conn->real_escape_string($sName);


    $checkSelQuery = "SELECT * FROM actor WHERE actor_first_name='$fName' AND actor_last_name='$sName'";
    $actInsertQuery = "INSERT INTO actor (actor_first_name, actor_last_name) VALUES ('$fName', '$sName');";

    $selectResult = $conn->query($checkSelQuery);


    // check if actor has already been added
    if (($selectResult->num_rows) > 0) {
        echo "<p>Actor already in table</p>";

    } else {
        
        $insertRes = $conn->query($actInsertQuery);

        if (!$insertRes) {
            echo $conn->error;
        }

        echo "<p>$actInsertQuery</p>";
    }
}

//genre function
function addUniqueGenre($gen)
{
    global $conn;

    $checkSelQuery = "SELECT * FROM genre WHERE genre_name='$gen'";
    $genInsertQuery = "INSERT INTO genre (genre_name) VALUES ('$gen');";

    $selectResult = $conn->query($checkSelQuery);

    // check if actor has already been added
    if (($selectResult->num_rows) > 0) {
        echo "<p>Genre already in table</p>";

    } else {
        $insertRes = $conn->query($genInsertQuery);


        if (!$insertRes) {
            echo $conn->error;
        }
        echo "<p>$genInsertQuery</p>";
    }
}


/*

    many-many relationship functions

*/

// function for film-genre table
function insertFilmGenre($filmName, $year, $genreName)
{

    global $conn;

    //check year and runtime in select query only if they are not null
    //otherwise the database will throw an error
    if($year != "NULL") {
        $checkYear = " AND release_year=".$year;
    } else {
        $checkYear = "";
    }


    

    //queries to find the id for genre and film
    $selectFId = "SELECT film_id FROM film WHERE film_title='$filmName' $checkYear;";
    $selectGId = "SELECT genre_id FROM genre WHERE genre_name='$genreName';";

    //perform select queries
    $filmResult = $conn->query($selectFId);
    $genreResult = $conn->query($selectGId);

    //assign results to variables
    $fId = $filmResult->fetch_assoc();
    $gId = $genreResult->fetch_assoc();

    //store id values as variables
    $filmIdNum = $fId['film_id'];
    $genreIdNum = $gId['genre_id'];

    //check that entry is not a duplicate
    $selectFilmGenreQ = "SELECT * FROM film_genre WHERE film_id = $filmIdNum AND genre_id = $genreIdNum;";
    $filmGenreRes = $conn->query($selectFilmGenreQ);

    if ($filmGenreRes->num_rows > 0) {

        echo "<p>film_genre already in table</p>";
    } else {
        //if this record does not already exist

        //generate insert query
        $insertFilGenQuery = "INSERT INTO film_genre (film_id, genre_id) VALUES ('$filmIdNum', '$genreIdNum');";

        //perform the query and add record to database
        $filGenResult = $conn->query($insertFilGenQuery);

        if (!$filGenResult) {
            echo $conn->error;
        } else {
            echo "<p>$insertFilGenQuery</p>";
        }
    }
}

// function for film-director table
function insertFilmDirector($filmName, $year, $dirFirstName, $dirLastName)
{

    global $conn;

    //real escape the apostrophes in names
    $dirFirstName = $conn->real_escape_string($dirFirstName);
    $dirLastName = $conn->real_escape_string($dirLastName);

    //check year and runtime in select query only if they are not null
    //otherwise the database will throw an error
    if($year != "NULL") {
        $checkYear = " AND release_year=".$year;
    } else {
        $checkYear = "";
    }
    

    //queries to find the id for director and film
    $selectFId = "SELECT film_id FROM film WHERE film_title='$filmName' $checkYear;";
    $selectDId = "SELECT director_id FROM director WHERE dir_first_name='$dirFirstName' AND dir_last_name='$dirLastName';";

    //perform select queries
    $filmResult = $conn->query($selectFId);
    $directorResult = $conn->query($selectDId);

    //assign results to variables
    $fId = $filmResult->fetch_assoc();
    $dId = $directorResult->fetch_assoc();

    //store id values as variables
    $filmIdNum = $fId['film_id'];
    $directorIdNum = $dId['director_id'];


    //check that entry is not a duplicate
    $selectFilmDirQ = "SELECT * FROM film_director WHERE film_id = $filmIdNum AND director_id = $directorIdNum;";
    $filmDirRes = $conn->query($selectFilmDirQ);

    if ($filmDirRes->num_rows > 0) {
        echo "<p>film_director already in table</p>";
    } else {

        //generate insert query
        $insertFilDirQuery = "INSERT INTO film_director (film_id, director_id) VALUES ('$filmIdNum', '$directorIdNum');";

        //perform the query and add record to database
        $filDirResult = $conn->query($insertFilDirQuery);

        if (!$filDirResult) {
            echo $conn->error;
        } else {
            echo "<p>$insertFilDirQuery</p>";
        }
    }
}



// function for film-actor table
function insertFilmActor($filmName, $year, $actFirstName, $actLastName)
{

    global $conn;

    //real escape the apostrophes in names
    $actFirstName = $conn->real_escape_string($actFirstName);
    $actLastName = $conn->real_escape_string($actLastName);

    //check year and runtime in select query only if they are not null
    //otherwise the database will throw an error
    if($year != "NULL") {
        $checkYear = " AND release_year=".$year;
    } else {
        $checkYear = "";
    }
    

    //queries to find the id for actor and film
    $selectFId = "SELECT film_id FROM film WHERE film_title='$filmName' $checkYear;";
    $selectAId = "SELECT actor_id FROM actor WHERE actor_first_name='$actFirstName' AND actor_last_name='$actLastName';";

    //perform select queries
    $filmResult = $conn->query($selectFId);
    $actorResult = $conn->query($selectAId);

    //assign results to variables
    $fId = $filmResult->fetch_assoc();
    $aId = $actorResult->fetch_assoc();

    //store id values as variables
    $filmIdNum = $fId['film_id'];
    $actorIdNum = $aId['actor_id'];

    //check that entry is not a duplicate
    $selectFilmActorQ = "SELECT * FROM film_actor WHERE film_id = $filmIdNum AND actor_id = $actorIdNum;";
    $filmActorRes = $conn->query($selectFilmActorQ);


    if ($filmActorRes->num_rows > 0) {
        echo "<p>film_actor already in table</p>";
    } else {

        //generate insert query
        $insertFilActQuery = "INSERT INTO film_actor (film_id, actor_id) VALUES ('$filmIdNum', '$actorIdNum');";


        //perform the query and add record to database
        $filActResult = $conn->query($insertFilActQuery);

        if (!$filActResult) {
            echo $conn->error;
        } else {
            echo "<p>$insertFilActQuery</p>";
        }
    }
}






//open the csv file and turn a single line into an array
$file = 'Movie-DataSet2_final.csv';
$readFile = fopen($file, "r");
$line = fgetcsv($readFile);
$line = fgetcsv($readFile);


while ($line != null) {

    //assigning the values to variables
    $title = $line[0];
    $genre = $line[1];
    $director = str_replace("'", "", $line[2]);
    $actors = $line[3];
    $year = $line[4];
    $runtime = $line[5];
    $revenue = $line[6];



    $title = $conn->real_escape_string($title);

    if ($year === "") {
        $year = "NULL";
    }
    if ($runtime === "") {
        $runtime = "NULL";
    }
    if ($revenue === "") {
        $revenue = "NULL";
    }



    //check that entry is not a duplicate
    //this will allow for remakes that have the same name but different year
    $selectFilmQ = "SELECT * FROM film 
    WHERE film_title = '$title'
    AND release_year = $year;";

    $filmSelRes = $conn->query($selectFilmQ);


    if (($filmSelRes->num_rows) > 0) {
        echo "<p>film_actor already in table</p>";
    } else {


        //writing the sql query using the variables above
        $movieQuery = "INSERT INTO film (film_title, release_year, runtime, revenue) VALUES ('$title', $year, $runtime, $revenue);";

        $movieResult = $conn->query($movieQuery);

        if (!$movieResult) {
            echo $conn->error;
        } else {
            echo "<p>$movieQuery</p>";
        }
    }

    //add directors to table
    //If there are two directors
    if (strpos($director, ",")) {
        //split the string into two directors
        $multiDirector = explode(",", $director);
        $dirOne = $multiDirector[0];
        $dirTwo = substr($multiDirector[1], 1);

        //where is the space between the first and last name
        $dir1spa = strpos($dirOne, " ");
        $dir2spa = strpos($dirTwo, " ");


        //Use number calculated above to find the first and last name
        //first director
        $dir1FName = substr($dirOne, 0, $dir1spa);
        $dir1LName = substr($dirOne, ($dir1spa + 1));
        //second director
        $dir2FName = substr($dirTwo, 0, $dir2spa);
        $dir2LName = substr($dirTwo, ($dir2spa + 1));

        //add records to database
        //first director
        addUniqueDirector($dir1FName, $dir1LName);
        insertFilmDirector($title, $year, $dir1FName, $dir1LName);
        //second director
        addUniqueDirector($dir2FName, $dir2LName);
        insertFilmDirector($title, $year, $dir2FName, $dir2LName);
    } else {
        //else there is only one director

        //where is the space between the first and last name
        $dirspa = strpos($director, " ");

        if ($dirspa) {
            //Use number calculated above to find the first and last name
            $dirFName = substr($director, 0, $dirspa);
            $dirLName = substr($director, ($dirspa + 1));
        } else {
            $dirFName = $director;
            $dirLName = "NULL";
        }

        //add record to database
        addUniqueDirector($dirFName, $dirLName);
        insertFilmDirector($title, $year, $dirFName, $dirLName);
    }


    //add actor
    $actors = substr($actors, 1, (strlen($actors) - 2));

    //split actors into an array
    $actAra = explode(", ", $actors);


    //cycle through the actors in the array
    foreach ($actAra as $act) {
        $firstSpace = strpos($act, " ");

        //check if mononym
        if ($firstSpace) {
            $actFName = substr($act, 0, $firstSpace);
            $actLName = substr($act, ($firstSpace + 1));
        } else {
            $actFName = $act;
            $actLName = null;
        }

        //declare variables in case of extra actor
        $actFNameExtra;
        $actLNameExtra;

        //check if actor inlcudes another actor due to bad data
        if (strpos($actLName, ",")) {
            $secondPart = $actLName;
            $splitPoint = strpos($actLName, ",");
            $actLName = substr($secondPart, 0, $splitPoint);

            $extraActor = substr($secondPart, ($splitPoint + 1));
            $secondSpace = strpos($extraActor, " ");

            //check if mononym
            if ($secondSpace) {
                $actFNameExtra = substr($extraActor, 0, $secondSpace);
                $actLNameExtra = substr($extraActor, ($secondSpace + 1));
            } else {
                $actFNameExtra = $extraActor;
                $actLNameExtra = null;
            }

            //add actor records to database
            addUniqueActor($actFNameExtra, $actLNameExtra);
            insertFilmActor($title, $year, $actFNameExtra, $actLNameExtra);
        }

        //add actor records to database
        addUniqueActor($actFName, $actLName);
        insertFilmActor($title, $year, $actFName, $actLName);
    }

    //declare array for genres on a line
    $genAra;
    //split the genres
    if (strpos($genre, ",")) {
        $genAra = explode(",", $genre);

        //cycle through each genre in the array
        foreach ($genAra as $genSingle) {
            //remove the apostrophies
            $genSingle = str_replace("'", "", $genSingle);
            //remove the movies at the end
            $genSingle = str_replace(" Movies", "", $genSingle);
            //remove the unnecessary spaces
            $genSingle = str_replace(" ", "", $genSingle);

            //improve genre names
            //change romantic to romance
            $genSingle = str_replace("Romantic", "Romance", $genSingle);
            //change Documentaries to Documentary
            $genSingle = str_replace("Documentaries", "Documentary", $genSingle);
            //change Dramas to Drama
            $genSingle = str_replace("Dramas", "Drama", $genSingle);
            //change Thrillers to Thriller
            $genSingle = str_replace("Thrillers", "Thriller", $genSingle);


            //insert into database
            addUniqueGenre($genSingle);

            insertFilmGenre($title, $year, $genSingle);
        }
    }



    echo "<br>";


    $line = fgetcsv($readFile);
}
