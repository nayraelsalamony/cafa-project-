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
                    <form action="save_product.php" method="POST" enctype="multipart/form-data">

                        <div class="title form-group">
                            <h1> Add Product </h1>
                        </div>

                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" autofocus value="<?php if (isset($olddata->name)) {
                                                                                                                                            echo $olddata->name;
                                                                                                                                        } ?>">
                            <?php
                            if (isset($errors->name)) {
                                echo "<p class='error'> $errors->name</p>";
                            }

                            ?>
                        </div>

                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" id="price" name="price" class="form-control " placeholder="0.0" min="0" autofocus value="<?php if (isset($olddata->price)) {
                                                                                                                                                echo $olddata->price;
                                                                                                                                            } ?>">
                            <span>EGP</span>
                            <?php
                            if (isset($errors->price)) {
                                echo "<p class='error'> $errors->price</p>";
                            }

                            ?>

                        </div>

                        <div class="form-group">
                            <label for="category">category</label>
                            <select class="form-control" id="category" name="category" value="<?php if (isset($olddata->category)) {
                                                                                                    echo $olddata->category;
                                                                                                } ?>">

                                <?php
                                $query = "SELECT * FROM category";
                                $result_tasks = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result_tasks)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                            <?php
                            if (isset($errors->category)) {
                                echo "<p class='error'> $errors->category</p>";
                            }

                            ?>
                            <br />
                            <a href="add_category.php" class="cat">Add category</a>
                        </div>

                        <div class=" form-group">
                            <div>
                                <label for="inputGroupFile01">Choose image</label>
                                <input type="file" class="upload" name="image" aria-describedby="inputGroupFileAddon01">
                                <?php
                                if (isset($errors->image)) {
                                    echo "<p class='error' >$errors->image</p>";
                                }
                                ?>

                            </div>
                        </div>
                        <div class="form-group btns">
                            <input type="submit" name="save_task" class="btn btn-success btn-block up fw-bold" value="Save Product">
                            <input type="reset" name="reset" class="btn btn-danger btn-block res fw-bold" value="Reset">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>