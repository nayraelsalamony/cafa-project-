<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE) {
  if ($_SESSION['is_admin'] != 1) {
    header("Location: ../userView/home.php");
  } else {
    header("Location: ../adminView/manualOrder.php");
  }
}

if (isset($_GET['errors'])) {
  $errors = json_decode($_GET['errors']);
}

if (isset($_GET['data'])) {
  $data = json_decode($_GET['data']);
}

if (isset($_GET['status'])) {
  $status = json_decode($_GET['status']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/form.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Cairo&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

  <style>

  </style>
  <title>Login</title>
</head>

<body>
  <div class="overlay-bg"></div>
  <div class="cont">
    <div class="alert parent" style="width: 30%;">
      <h1 class="text-center"><img src="../images/logo.png" alt="logo" class="logo" width="100px" height="100px" /></h1>
      <h1 class="text-center text-logo">Cafe</h1>
      <form action="login_validation.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="username" class="text-color text-size title">Username</label>
          <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" value="<?php if (isset(($data)->username)) {
                                                                                                                      echo $data->username;
                                                                                                                    } ?>">
          <p class="error"><?php if (isset($errors->username)) {
                              echo $errors->username;
                            } ?></p>
        </div><br />
        <div class="form-group">
          <label for="password" class="text-color text-size title">Password</label>
          <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
          <p class="error"><?php if (isset($errors->password)) {
                              echo $errors->password;
                            } ?></p>
          <br>
          <div class="text-center">
            <p class="text-center success"><?php if (isset(($status)->success))  echo $status->success; ?></p>
            <button type="submit" class="btn text-size text-color btn-log">Login</button>
            <br><br>
            <a href="forget_password.php" class="text-color title fw-bold ">Forget your password? Reset it.</a>
          </div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>

</html>