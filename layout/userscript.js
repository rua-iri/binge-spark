//constants for accessing the buttons and their elements
const favbutton = document.getElementById("show-fav-button");
const favElements = document.getElementsByClassName("user-fav");
const revButton = document.getElementById("show-review-button");
const revElements = document.getElementsByClassName("user-rev");

//add event listener to the favorites button
favbutton.addEventListener("click", function() {
    //change each element's display when button is clicked
    for(let i=0; i<favElements.length; i++) {
        favElements[i].classList.toggle("user-fav-active");
    }
    
    //change button text depending on whether elements are shown or not
    if(favElements[0].classList.contains("user-fav-active")) {
        favbutton.innerHTML = "Hide Watchlist";        
    } else {
        favbutton.innerHTML = "Show Watchlist";
    }
})

//add event listener to the reviews button
revButton.addEventListener("click", function() {
    //change each element's display when button is clicked
    for(let i=0; i<revElements.length; i++) {
        revElements[i].classList.toggle("user-rev-active");
    }

    //change button text depending on whether elements are shown or not
    if(revElements[0].classList.contains("user-rev-active")) {
        revButton.innerHTML = "Hide Reviews";        
    } else {
        revButton.innerHTML = "Show Reviews";
    }

})