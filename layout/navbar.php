<?php

$baseURL = "http://rmcguigan14.webhosting6.eeecs.qub.ac.uk/bingespark";

$homeLink = $baseURL;
$loginLink = $baseURL . "/user/login.php";
$searchLink = $baseURL ."/search/index.php";
$logoutLink = $baseURL . "/user/logout.php";
$sIconLink = $baseURL . "/src/magnifying-glass.svg";
$topLink = $baseURL . "/top25.php";
$genreLink = $baseURL . "/genre.php";
$logoLink = $baseURL . "/src/bingesparklogo.png";
$yearLink = $baseURL . "/year.php";

$jsSource = $baseURL . "/layout/search_script.js";


$logout = "";

//change navbar layout if user is logged in or not
if (isset($_SESSION['loggedIn'])) {
    $rightBox = $_SESSION['loggedIn'];
    $rightLink = $baseURL . "/user/user.php?user_id=${_SESSION['loggedInId']}";

    $logout = "<a class='navbar-item' href='$logoutLink'>Logout</a>";
} else {
    $rightBox = "Log In";
    $rightLink = $loginLink;
}

?>


<nav class="navbar is-fixed-top has-shadow is-white">

    <div class="navbar-brand">
        <a class="navbar-item" href="<?= $homeLink ?>">
            <img class="image is-rounded" id="binges-logo" src="<?= $logoLink ?>" alt="BingeSpark Logo">
        </a>

        <a class="navbar-burger" id="burger">
            <span></span>
            <span></span>
            <span></span>
        </a>
    </div>



    <div class="navbar-menu" id="nav-links">

        <div class="navbar-end">
            <form action="<?= $searchLink ?>" method="GET" class="navbar-item">
                <input type="text" name="searchfor" class="search-bar input" placeholder="Search for an actor, director or film...">
                <span class="s-icon-background">
                    <img class="search-icon" src="<?= $sIconLink ?>" alt="icon for search">
                </span>
            </form>

            <a href="<?= $yearLink ?>" class="navbar-item">Search By Year</a>
            <a href="<?= $genreLink ?>" class="navbar-item">Genre</a>
            <a href="<?= $topLink ?>" class="navbar-item">Top 25</a>

            <a class="navbar-item" href="<?= $rightLink ?>">
                <?= $rightBox ?>
            </a>
            <?= $logout ?>
        </div>
    </div>

    <script src="<?= $jsSource ?>"></script>

</nav>
