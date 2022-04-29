<?php

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

include "../dbConnections/mysqli.php";


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM user WHERE user_id =$id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $name = $row['username'];
        $room = $row['room'];
        $pic = $row['profile_pic'];
        $ext = $row['ext'];
    }
}



if (isset($_POST['update'])) {

    $filename = $_FILES['file']['name'];
    $filetype = $_FILES['file']['type'];
    $filetmp_name = $_FILES['file']['tmp_name'];
    $filesize = $_FILES['file']['size'];
    $exte = explode(".", $_FILES['file']['name']);
    $fileExt = strtolower(end($exte));

    $err = array();
    $extensions = ["png", "jpg", "md", "jpeg", "png", "webp"];

    if (in_array($fileExt, $extensions) === false) {
        $err[] = "----------Extension is not Allawoed -----";
    }


    if (empty($err) == true) {
        move_uploaded_file($filetmp_name, "../images/" . $filename);
    }

    $name = $_POST['username'];
    $room = $_POST['room'];
    $ext = $_POST['ext'];
    $img_path = "../images/$filename";



    $query = "UPDATE user SET username = '$name', room = '$room',room= '$room', profile_pic = '$filename', ext = $ext WHERE user_id=$id";


    $result = mysqli_query($conn, $query);


    header('Location: list_users.php');
}

?>


<body>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/adminNav.css" />
    <?php include('../navbars/admin_header.php') ?>
    <link rel="stylesheet" href="../css/add_product.css" />
    <main>
        <div class="container p-5">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-5">
                    <div class="card card-body">
                        <form action="edit_user.php?id=<?php echo $_GET['id']; ?>" method="POST" enctype="multipart/form-data">
                            <div class="title form-group">
                                <h1> Update User </h1>
                            </div>

                            <div class="form-group">
                                <label for="name">Username</label>
                                <input type="text" id="name" name="username" class="form-control" value="<?php echo $row['username']; ?>" placeholder="User Name" autofocus>
                            </div>

                            <div class="form-group">
                                <label for="price">Room</label>
                                <input type="number" id="room" name="room" class="form-control" value="<?php echo $row['room']; ?>" placeholder="room" min="0" autofocus>
                            </div>

                            <div class="form-group">
                                <label for="price">Ext</label>
                                <input type="number" id="ext" name="ext" class="form-control" value="<?php echo $row['ext']; ?>" placeholder="ext" autofocus>
                            </div>


                            <div class="form-group ">
                                <div>
                                    <label for="inputGroupFile01">Choose file</label>
                                    <input type="file" name="file" class="upload" aria-describedby="inputGroupFileAddon01">

                                </div>
                            </div>
                            <div class="form-group btns">
                                <input type="submit" name="update" class="btn btn-success btn-block up fw-bold" value="Update">
                                <input type="reset" name="reset" class="btn btn-danger btn-block res fw-bold" value="Reset">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>