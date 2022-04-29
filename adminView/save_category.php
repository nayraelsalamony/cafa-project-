<?php
include "../dbConnections/mysqli.php";

session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
}
if ($_SESSION['is_admin'] != 1) {
  die("Access Denied");
}

?>

<?php

$errors = [];
$olddata = [];

if (empty($_POST["name"]) or $_POST["name"] == "") {
    $errors["name"] = "Please enter a category name";
} else {
    $olddata["name"] = $_POST["name"];
}

if (count($errors) > 0) {

    $err = json_encode($errors);

    if (count($olddata) > 0) {
        $old = json_encode($olddata);

        header("Location:add_category.php?errors={$err}&olddata={$old}");
    } else {
        header("Location:add_category.php?errors={$err}");
    }
} else {
    try {
        $name = $_POST['name'];

        $query = "INSERT INTO category(name) VALUES ('$name')";
        $result = mysqli_query($conn, $query);
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $errors["name"] = "Please enter a new category name";
        $err = json_encode($errors);
        header("Location:add_category.php?errors={$err}");
    }
    if (!$result) {
        die("Query Failed.");
    }

    header('Location: add_product.php');
}

?>
