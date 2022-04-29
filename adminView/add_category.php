<?php

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
}
if ($_SESSION['is_admin'] != 1) {
    die("Access Denied");
}

if (isset($_GET["errors"])) {
    $errors = json_decode($_GET["errors"]);
}
if (isset($_GET["olddata"])) {
    $olddata = json_decode($_GET["olddata"]);
}
?>


<body>
    <?php include "../dbConnections/mysqli.php"; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/adminNav.css" />
    <?php include('../navbars/admin_header.php') ?>
    <link rel="stylesheet" href="../css/add_product.css" />



    <main class="container p-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5">


                <!-- ADD TASK FORM -->

                <div class="card card-body ">
                    <form action="save_category.php" method="POST" enctype="multipart/form-data">

                        <div class="title form-group">
                            <h1> Add Category </h1>
                        </div>

                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Category Name" autofocus value="<?php if (isset($olddata->name)) {
                                                                                                                                            echo $olddata->name;
                                                                                                                                        } ?>">
                            <?php
                            if (isset($errors->name)) {
                                echo "<p class='error'> $errors->name</p>";
                            }

                            ?>
                        </div>

                        <div class="form-group btns">
                            <input type="submit" name="save_task" class="btn btn-success up fw-bold btn-block" value="Save Category">
                            <input type="reset" name="reset" class="btn btn-danger res fw-bold btn-block" value="Reset">
                        </div>
                    </form>
                </div>
            </div>

        </div>

        </div>
    </main>
</body>