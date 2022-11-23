//code for paginating results

//variables to run the functions below
const resLimit = 10;
const maxFilmResults = (document.getElementsByClassName("film-s-result").length) - 1;
const maxActorResults = (document.getElementsByClassName("actor-s-result").length) - 1;
const maxDirResults  = (document.getElementsByClassName("dir-s-result").length) - 1;

var filmStartPoint = 1;
var filmendPoint = filmStartPoint + resLimit;
var actorStartPoint = 1;
var actorEndPoint = actorStartPoint + resLimit;
var dirStartPoint = 1;
var dirEndPoint = dirStartPoint + resLimit;


//make the first 10 elements visibile by default
for(var i=1; i<=resLimit; i++) {
    var resId = "f-result-"+i;
    document.getElementById(resId).style.display = "block";
    resId = "a-result-"+i;
    document.getElementById(resId).style.display = "block";
    resId = "d-result-"+i;
    document.getElementById(resId).style.display = "block";
}



//function to see next page of film results
function nextFilmResults() {

    if (filmendPoint<maxFilmResults) {
        for(var i=filmStartPoint; i<filmendPoint; i++){
            var resId = "f-result-"+i;
            document.getElementById(resId).style.display = "none";
        }
    
        filmStartPoint+=resLimit;
        filmendPoint+=resLimit;
    
        for(i=filmStartPoint; i<filmendPoint; i++){
            var resId = "f-result-"+i;
            document.getElementById(resId).style.display = "block";
        }

    } else {
        return null;
    }
    
}

//function to see previous page of film results
function prevFilmResults() {

    if(filmStartPoint>1) {
        for(var i=(filmendPoint-1); i>=filmStartPoint; i--) {
            var resId = "f-result-"+i;
            document.getElementById(resId).style.display = "none";
        }

        filmStartPoint-=resLimit;
        filmendPoint-=resLimit;

        for(var i=(filmendPoint-1); i>=filmStartPoint; i--){
            var resId = "f-result-"+i;
            document.getElementById(resId).style.display = "block";
        }

    } else {
        return null;
    }

}

//function to see next page of actor results
function nextActorResults() {

    if (actorEndPoint<maxActorResults) {
        for(var i=actorStartPoint; i<actorEndPoint; i++){
            var resId = "a-result-"+i;
            document.getElementById(resId).style.display = "none";
        }
    
        actorStartPoint+=resLimit;
        actorEndPoint+=resLimit;
    
        for(i=actorStartPoint; i<actorEndPoint; i++){
            var resId = "a-result-"+i;
            document.getElementById(resId).style.display = "block";
        }
        
    } else {
        return null;
    }
    
}


//function to see previous page of actor results
function prevActorResults() {

    if(actorStartPoint>1) {
        for(var i=(actorEndPoint-1); i>=actorStartPoint; i--) {
            var resId = "a-result-"+i;
            document.getElementById(resId).style.display = "none";
        }

        actorStartPoint-=resLimit;
        actorEndPoint-=resLimit;

        for(var i=(actorEndPoint-1); i>=actorStartPoint; i--){
            var resId = "a-result-"+i;
            document.getElementById(resId).style.display = "block";
        }

    } else {
        return null;
    }

}



//function to see next page of director results
function nextDirResults() {

    if (dirEndPoint<maxDirResults) {
        for(var i=dirStartPoint; i<dirEndPoint; i++){
            var resId = "d-result-"+i;
            document.getElementById(resId).style.display = "none";
        }
    
        dirStartPoint+=resLimit;
        dirEndPoint+=resLimit;
    
        for(i=dirStartPoint; i<dirEndPoint; i++){
            var resId = "d-result-"+i;
            document.getElementById(resId).style.display = "block";
        }
        
    } else {
        return null;
    }
    
}


//function to see previous page of director results
function prevDirResults() {

    if(dirStartPoint>1) {
        for(var i=(dirEndPoint-1); i>=dirStartPoint; i--) {
            var resId = "d-result-"+i;
            document.getElementById(resId).style.display = "none";
        }

        dirStartPoint-=resLimit;
        dirEndPoint-=resLimit;

        for(var i=(dirEndPoint-1); i>=dirStartPoint; i--){
            var resId = "d-result-"+i;
            document.getElementById(resId).style.display = "block";
        }

    } else {
        return null;
    }

}
