<?php
include("connection.php");
set_time_limit(0);

//assign variables to values for accessing the api
$apiKey = "api_key=f3ec2a0bfcf1fec135d2f48a0b1e7fcc";
$baseSearchUrl = "https://api.themoviedb.org/3";
$dirSearch = "/search/person?";
$dirLookup = "/person";
$baseImgUrl = "https://image.tmdb.org/t/p/w500";
$aSymb = "&";


//sql query to get data from the database
$dirSelectQuery = "SELECT *
FROM director;";

$dirRes = $conn->query($dirSelectQuery);


while ($dirData = $dirRes->fetch_assoc()) {

    //variables for each search
    $dirId = $dirData['director_id'];
    $searchQ = "query=" . urlencode($dirData['dir_first_name']) . "+" . urlencode($dirData['dir_last_name']);

    //generate url for json with basic info to find the film's id
    $endPoint = $baseSearchUrl . $dirSearch . $apiKey . $aSymb . $searchQ;


    $dirResponse = file_get_contents($endPoint);
    $dirAra = json_decode($dirResponse, true);

    if (!isset($dirAra['results'][0]['id'])) {
        continue;
    }


    $apiDirId = "/" . $dirAra['results'][0]['id'] . "?";
    $endPoint = $baseSearchUrl . $dirLookup . $apiDirId . $apiKey;

    $dirResponse = file_get_contents($endPoint);
    $dirAra = json_decode($dirResponse, true);

    $biography = $conn->real_escape_string($dirAra['biography']);
    $birthday = $dirAra['birthday'];
    $photoPath = $baseImgUrl . $dirAra['profile_path'];
    $birthPlace = $conn->real_escape_string($dirAra['place_of_birth']);
    $gender;
    $deathday;
    
    //set variables to null rather than empty string
    if(!isset($biography)) {
    	$biography = "NULL";
    }
    
    if(!isset($birthday)) {
    	$birthday = "NULL";
    } else {
    	$birthday = "'$birthday'";
    }
    
    if(!isset($photoPath)) {
    	$photoPath = "NULL";
    }
    if(!isset($birthPlace)) {
    	$birthPlace = "NULL";
    }


    //determine gender value for database
    if ($dirAra['gender'] == 1) {
        $gender = 0;
    } else if ($dirAra['gender'] == 2) {
        $gender = 1;
    } else {
        $gender = "NULL"; 
    }

    //set deathday only if director is dead
    if (isset($dirAra['deathday'])) {
        $deathday = "death_date = '${dirAra['deathday']}',";
    } else {
        $deathday = "";
    }

    //SQL query to add new data to the database
    $dirUpdateQuery = "UPDATE director
    SET gender = $gender,
    birth_date = $birthday,
    $deathday
    birthplace = '$birthPlace',
    biography = '$biography',
    dir_image_path = '$photoPath'
    WHERE director_id = $dirId;";


    $updateRes = $conn->query($dirUpdateQuery);

    if (!$updateRes) {
        echo $conn->error;
        echo "<br>";
    } else {
        echo "Data Successfully added to database<br>";
    }


    echo $dirUpdateQuery . "<br><br><br>";
}
