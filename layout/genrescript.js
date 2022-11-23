const resLimit = 20;
const maxGenreResults = (document.getElementsByClassName("genre-result").length) - 1;
var genreStartPoint = 1;
var genreEndPoint = genreStartPoint + resLimit;

//set the first 20 results to visible by default
for (var i = 1; i <= resLimit; i++) {
    var resId = "genre-film-" + i;
    document.getElementById(resId).style.display = "block";
}

//function to show next page of results
function nextGenreResults() {

    if (genreEndPoint < maxGenreResults) {

        for (var i = genreStartPoint; i < genreEndPoint; i++) {
            var resId = "genre-film-" + i;
            document.getElementById(resId).style.display = "none";
        }

        genreStartPoint += resLimit;
        genreEndPoint += resLimit;

        for (var i = genreStartPoint; i < genreEndPoint; i++) {
            var resId = "genre-film-" + i;
            document.getElementById(resId).style.display = "block";
        }

    } else {
        return null;
    }
}



function prevGenreResults() {

    if(genreStartPoint>1){
        for(var i=(genreEndPoint-1); i>=genreStartPoint; i--) {
            var resId = "genre-film-" + i;
            document.getElementById(resId).style.display = "none";
            console.log(resId);
        }

        genreStartPoint -= resLimit;
        genreEndPoint -= resLimit;

        for(var i=(genreEndPoint-1); i>=genreStartPoint; i--) {
            var resId = "genre-film-" + i;
            document.getElementById(resId).style.display = "block";
            console.log(resId);
        }

    } else {
        return null;
    }

}
