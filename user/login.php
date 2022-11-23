<?php
session_start();
include("../connection.php");

//redirect to homepage if user is logged in but enters the url manually
if (isset($_SESSION['loggedIn'])) {
    header("Location: ..");
}


$usrInput = "";

//asign username input to a variable
//this will refill the form using post or session variables 
//to be reused if page is reloaded
if (isset($_POST['userfield'])) {
    $usrInput = $_POST['userfield'];
} else if (isset($_SESSION['userfield'])) {
    $usrInput = $_SESSION['userfield'];
    unset($_SESSION['userfield']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<?php include("../layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">

        <div class="section">

            <div class="columns">

                <div class="column is-5">


                    <h1 class="title py-3">Login to BingeSpark</h1>

                    <form action="sign.php" method="POST">

                        <label class="label has-text-danger">
                            <?php
                            if (isset($_SESSION['loginerror'])) {
                                echo $_SESSION['loginerror'];
                                unset($_SESSION['loginerror']);
                            }
                            ?>
                        </label>

                        <div class="field">
                            <label class="label">Username</label>
                            <div class="control">
                                <input class="input" type="text" name="userfield" value="<?= $usrInput; ?>" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Password</label>
                            <div class="control">
                                <input class="input" type="password" name="passfield" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="button is-medium is-primary" type="submit" name="submitlogin" id="login-submit" value="Login">
                            </div>
                        </div>
                    </form>




                    <div class="mt-6">
                        <h2 class="is-size-6">Dont have an account?<h2>
                                <a class="is-size-6" href="createaccount.php">Create one here!</a>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include("../layout/footer.php"); ?>
</body>

</html>