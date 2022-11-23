
//code for the numbers that accompany the slider
function revSlide(rating) {

    var revEmoji = "";


    if(rating>0 && rating<3){
        revEmoji = "😞";
    } else if(rating>2 && rating<5) {
        revEmoji = "😑";
    } else if(rating>4 && rating<7) {
        revEmoji = "🙂";
    } else if(rating>6 && rating<9) {
        revEmoji = "😀";
    } else if(rating>8 && rating<11) {
        revEmoji = "😁";
    }


    document.getElementById("slider-rating").innerHTML = rating + "&ensp;" + revEmoji;
}





//code for the add review modal 
const button = document.getElementById("rev-modal-button");
const modal = document.querySelector(".modal");
const modalBkg = document.querySelector(".modal-background");

button.addEventListener("click", function() {
    modal.classList.add("is-active");
})

modalBkg.addEventListener("click", function() {
    modal.classList.remove("is-active");
})