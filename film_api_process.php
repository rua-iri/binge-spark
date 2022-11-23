<?php
include("connection.php");
set_time_limit(0);

//variables for api key and urls which will remain constant
$apiKey = "api_key=f3ec2a0bfcf1fec135d2f48a0b1e7fcc";
$baseSearchUrl = "https://api.themoviedb.org/3";
$movieSearch = "/search/movie?";
$movieLookup = "/movie";
$baseImgUrl = "https://image.tmdb.org/t/p/w500";
$aSymb = "&";


//sql query to get film's title and release date to search api
$filmSQuery = "SELECT film_id, film_title, release_year
FROM film;";

$filmRes = $conn->query($filmSQuery);



while($filmData = $filmRes->fetch_assoc()){

    //variables for each search
    $filmId = $filmData['film_id'];
    $searchQ = "query=" . urlencode($filmData['film_title']);
    $yearQ = "year=" . $filmData['release_year'];

    //generate url for json with basic info to find the film's id
    $endPoint = $baseSearchUrl . $movieSearch . $apiKey . $aSymb . $searchQ . $aSymb . $yearQ;

    //convert json file into associative array
    $filmResponse = file_get_contents($endPoint);
    $filmAra = json_decode($filmResponse, true);


    //if no results are found skip this iteration of the loop
    if(!isset($filmAra['results'][0]['id'])) {
        continue;
    }




    //use film's id to access more data about it
    $apiFilmId = "/" . $filmAra['results'][0]['id'] . "?";

    //generate new url for more detailed json
    $endPoint = $baseSearchUrl . $movieLookup . $apiFilmId . $apiKey;

    //convert json file into associative array
    $filmResponse = file_get_contents($endPoint);
    $filmAra = json_decode($filmResponse, true);




    //variables to hold the relevant json data
    //real escape text data in case it includes undesirable characters
    $filmDesc = $conn->real_escape_string($filmAra['overview']);
    $tagLine = $conn->real_escape_string($filmAra['tagline']);

    $posterPath = $baseImgUrl . $filmAra['poster_path'];
    $releaseYear = substr($filmAra['release_date'], 0, 4);
    $runTime = $filmAra['runtime'];

    //csv file's revenue data is based on the US and Canada only, so we will update it as well
    $revenue = $filmAra['revenue'] / 1000000;



    //update film table in database using the data from the api
    $filmUpdateQuery = "UPDATE film
    SET film_summary = '$filmDesc',
    film_tagline = '$tagLine', 
    film_image_path = '$posterPath'";


    //if release year is null in database, update it with data from api
    if(!isset($filmData['release_year'])) {
        $filmUpdateQuery = $filmUpdateQuery . ", release_year = '$releaseYear'";
    }

    //if runtime is null in the database, update it with data from api
    if(!isset($filmData['runtime'])) {
        $filmUpdateQuery = $filmUpdateQuery . ", runtime = '$runTime'";
    }
    
    //if revenue retrived from the api is not zero, update the
    //value in the database so that it reflects the global revenue
    if($revenue!=0) {
        $filmUpdateQuery = $filmUpdateQuery . ", revenue = '$revenue'";
    }


    //add the where clause to the update query
    $filmUpdateQuery = $filmUpdateQuery . " WHERE film_id = $filmId;";

    //run the sql query to insert new data into the database
    $updateRes = $conn->query($filmUpdateQuery);

    if(!$updateRes) {
        echo $conn->error;
        echo "<br>";
    } else {
        echo "Data added successfully<br>";
    }

    //echo the query so that we can troubleshoot if anything goes wrong
    echo $filmUpdateQuery;
    echo "<br><br><br>";

}





?>
