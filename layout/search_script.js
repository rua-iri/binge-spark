
document.getElementsByClassName("s-icon-background")[0].addEventListener("click", function() {
    document.getElementsByClassName("search-bar")[0].classList.toggle("search-bar-active");
    document.getElementsByClassName("search-bar")[0].focus();
});

const burgerIcon = document.querySelector("#burger");
const navbarMenu = document.querySelector("#nav-links");

burgerIcon.addEventListener("click", function() {
    navbarMenu.classList.toggle("is-active");
})