<?php
include("connection.php");
set_time_limit(0);

//assign variables to values for accessing the api
$apiKey = "api_key=f3ec2a0bfcf1fec135d2f48a0b1e7fcc";
$baseSearchUrl = "https://api.themoviedb.org/3";
$actorSearch = "/search/person?";
$actorLookup = "/person";
$baseImgUrl = "https://image.tmdb.org/t/p/w500";
$aSymb = "&";

//sql query to get data from the database
$actorSelectQuery = "SELECT *
FROM actor;";

$actorRes = $conn->query($actorSelectQuery);

while($actorData = $actorRes->fetch_assoc()) {

    //variables for each search
    $actorId = $actorData['actor_id'];
    $searchQ = "query=" . urlencode($actorData['actor_first_name']) . "+" . urlencode($actorData['actor_last_name']);

    //generate url for json with basic info to find the film's id
    $endPoint = $baseSearchUrl . $actorSearch . $apiKey . $aSymb . $searchQ;

    //convert json file into associative array
    $actorResponse = file_get_contents($endPoint);
    $actorAra = json_decode($actorResponse, true);

    //skip this iteration if no results are found
    if(!isset($actorAra['results'][0]['id'])) {
        continue;
    }



    //create new url for api using the actor's id
    $apiActorId = "/" . $actorAra['results'][0]['id'] . "?";
    $endPoint = $baseSearchUrl . $actorLookup . $apiActorId . $apiKey;

    //convert json file into associative array
    $actorResponse = file_get_contents($endPoint);
    $actorAra = json_decode($actorResponse, true);


    //variables to hold the relevant json data
    $biography = $conn->real_escape_string($actorAra['biography']);
    $birthday = $actorAra['birthday'];
    $photoPath = $baseImgUrl . $actorAra['profile_path'];
    $birthPlace = $conn->real_escape_string($actorAra['place_of_birth']);
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
    if($actorAra['gender']==1){
        $gender = 0;
    } else if($actorAra['gender']==2) {
        $gender = 1;
    } else {
        $gender = "'NULL'"; 
    }

    //set deathday only if actor is dead
    if(isset($actorAra['deathday'])) {
        $deathday = "death_date = '${actorAra['deathday']}',";
    } else {
        $deathday = "";
    }

    //generate update query using the variables above
    $actorUpdateQuery = "UPDATE actor
    SET gender = '$gender',
    birth_date = $birthday,
    $deathday
    birthplace = '$birthPlace',
    biography = '$biography',
    actor_image_path = '$photoPath'
    WHERE actor_id = $actorId;";

    $updateRes = $conn->query($actorUpdateQuery);

    if(!$updateRes) {
        echo $conn->error;
        echo "<br>";
    } else {
        echo "Data Successfully added to database<br>";
    }


    echo $actorUpdateQuery;
    echo "<br><br><br>";


}



?>
