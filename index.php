<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BingeSpark | Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php include("layout/navbar.php"); ?>

<body class="has-navbar-fixed-top" id="homepage-body">


    <div class="container py-6 my-6">

        <div class="section has-text-centered">
            <div class="columns">

            <div class="column is-3"></div>
                <div class="column">


                    <div class="card">
                        <div class="card-content">

                            <h1 class="title is-size-1">
                                Welcome to BingeSpark
                            </h1>
                        </div>
                    </div>

                </div>

                <div class="column is-3"></div>

            </div>
        </div>

        <div class="my-6"></div>


    </div>

    <?php include("layout/footer.php"); ?>

</body>

</html>