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
  $errors["name"] = "please enter a product name";
} else {
  $olddata["name"] = $_POST["name"];
}

if (empty($_POST["price"]) or $_POST["price"] == "") {
  $errors["price"] = "please choose a price";
} else {
  $olddata["price"] = $_POST["price"];
}

if (empty($_POST["category"]) or $_POST["category"] == "") {
  $errors["username"] = "please choose a category";
} else {
  $olddata["category"] = $_POST["category"];
}

// -------------- check for file ---------------

$filename = $_FILES['image']['name'];
$filetype = $_FILES['image']['type'];
$filetmp_name = $_FILES['image']['tmp_name'];
$filesize = $_FILES['image']['size'];
$ext = explode(".", $_FILES['image']['name']);
$fileExt = strtolower(end($ext));
$extensions = ["png", "jpg", "md", "jpeg", "png", "webp"];
if (empty($_FILES["image"]['name']) or $_FILES["image"]['name'] == "") {
  $errors["image"] = "please choose an image";
} elseif (in_array($fileExt, $extensions) === false) {
  $errors["image"] = "Extension is not Allowed ";
} else {
  $olddata["image"]['name'] = $_FILES["image"]['name'];
}


if (count($errors) > 0) {

  $err = json_encode($errors);

  if (count($olddata) > 0) {
    $old = json_encode($olddata);

    header("Location:add_product.php?errors={$err}&olddata={$old}");
  } else {
    header("Location:add_product.php?errors={$err}");
  }
} else {
  if (isset($_POST['save_task'])) {
    $name = $_POST['name'];
    $price = (int)$_POST['price'];
    $category = (int)$_POST['category'];
    move_uploaded_file($filetmp_name, "../images/" . $filename);
    $img_path = "../images/$filename";



    $query = "INSERT INTO product(name, price, pic, category_id) VALUES ('$name', $price, '$filename',$category)";
    $result = mysqli_query($conn, $query);

    if (!$result) {
      die("Query Failed.");
    }

    header('Location: add_product.php');
  }
}

?>