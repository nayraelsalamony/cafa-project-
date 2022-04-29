<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
}
if ($_SESSION['is_admin'] != 1) {
    die("Access Denied");
}
?>


<?php include "../dbConnections/mysqli.php"; ?>

<link rel="stylesheet" href="../css/listProducts.css" />
<link rel="stylesheet" href="https://bootswatch.com/4/yeti/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<body>

    <?php include('../navbars/admin_header.php') ?>
    <main id='main-container' class="container p-4">

        <div id="table-container">


            <div class='table-title'>
                <div>All Products</div>
                <button><a href="add_product.php">Add product</a></button>
            </div>

            <table>
                <tr class="table-header">
                    <th>Product</th>
                    <th>Price (EG)</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>

                <?php
                $query = "SELECT p.* ,c.name as category FROM product p, category c Where p.category_id=c.id";
                $result_tasks = mysqli_query($conn, $query);


                while ($row = mysqli_fetch_assoc($result_tasks)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><img src="<?php echo '../images/' . $row['pic']; ?>" alt="" width=50px></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['product_id'] ?>" class="btn btn-success">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="delete_product.php?id=<?php echo $row['product_id'] ?>" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
</body>