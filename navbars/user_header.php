<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// If the user is not logged in redirect to the login page...

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
}
if ($_SESSION['is_admin'] == 1) {
    die("Access Denied");
}

$image = $_SESSION['profile_pic'];
$username = $_SESSION['name'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Niconne&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_header.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <title>Cafe</title>
</head>

<body>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-sm navbar-warning navbar-fixed-top nav-bg py-0 px-0"> <a class="navbar-brand nav-link" id="logo" href="#">Cafe &nbsp;&nbsp;&nbsp;</a> <span class="v-line"></span> <button class="navbar-toggler mr-3" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active"> <a class="nav-link" href="../userView/home.php">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../userView/userOrder.php">My Orders</a> </li>
                    <li class="nav-item  ml-auto"> <a class="nav-link" href="#"><?php echo $username ?></a> </li>
                    <li class="nav-item"><?php echo '<img alt="user" class="avatar" src="../images/' . $image . '">'; ?>
                    </li>
                    <li class="nav-item "><a class="nav-link" href="../login/logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>

</html>