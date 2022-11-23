<?php
session_start();

$newUName = "";
$newEmail = "";

if (isset($_POST['newUsrName'])) {
    $newUName = $_POST['newUsrName'];
} else if (isset($_SESSION['newUsrName'])) {
    $newUName = $_SESSION['newUsrName'];
    unset($_SESSION['newUsrName']);
}

if (isset($_POST['newEmail'])) {
    $newEmail = $_POST['newEmail'];
} else if (isset($_SESSION['newEmail'])) {
    $newEmail = $_SESSION['newEmail'];
    unset($_SESSION['newEmail']);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Create a New Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../style.css">
</head>

<?php include("../layout/navbar.php"); ?>

<body class="has-navbar-fixed-top">

    <div class="container">

        <div class="section">

            <div class="columns">

                <div class="column is-5">

                    <h1 class="title py-3">Create a new BingeSpark account</h1>

                    <!-- form to create a new account -->
                    <form action="validatenewaccount.php" method="POST">

                        <label class="label has-text-danger">
                            <?php
                            if (isset($_SESSION['createerror'])) {
                                echo $_SESSION['createerror'];
                                unset($_SESSION['createerror']);
                            }
                            ?>
                        </label>

                        <div class="field">
                            <label class="label">Username:</label>
                            <div class="control">
                                <input class="input" type="text" name="newUsrName" value="<?= $newUName ?>" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Password:</label>
                            <div class="control">
                                <input class="input" type="password" name="newUsrPass1" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Confirm Password:</label>
                            <div class="control">
                                <input class="input" type="password" name="newUsrPass2" placeholder="Confirm Password" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email Address:</label>
                            <div class="control">
                                <input class="input" type="text" name="newEmail" value="<?= $newEmail ?>" placeholder="Email Address" required>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input class="button is-medium is-primary" type="submit" name="newUsrSubmit" value="Create Account">
                            </div>
                        </div>

                    </form>

                    <div class="mt-6">
                        <p>
                            <a href="login.php">Log In to an existing account</a>
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php include("../layout/footer.php"); ?>

</body>

</html>