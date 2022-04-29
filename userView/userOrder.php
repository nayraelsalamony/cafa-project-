<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
}
if ($_SESSION['is_admin'] == 1) {
    die("Access Denied");
}
$fromdate = NULL;
$todate = NULL;
$user_id = NULL;

if (isset($_GET['from'])) {

    $fromdate = $_GET['from'];
}
if (isset($_GET['to'])) {

    $todate = $_GET['to'];
}
if ($fromdate == NULL) {
    $fromdate = '2022-04-01';
}
if ($todate == NULL) {
    $todate = '2022-04-30';
}
$user_id = $_SESSION['id'];

$opj = new Order();
$orders = $opj->select($fromdate, $todate, $_SESSION['id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="../css/check.css" />
</head>

<body>
    <?php include('../navbars/user_header.php') ?>
    <div class="form-container">
        <form method="get" style="text-align: center">
            <label class="fw-bold"> From </label><i class="fa-solid fa-calendar-days cal"></i>
            <input type="date" name="from" />
            <label class="fw-bold"> To </label><i class="fa-solid fa-calendar-days cal"></i>
            <input type="date" name="to" />
            </br></br></br>

            <button type="submit" class="form-btn">Search</button>
        </form>
    </div>
    <div class="container tbl">
        <table style="width: 100% ;text-align: center">
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            <?php
            $opj = new Order();
            $orders = $opj->select($fromdate, $todate, $_SESSION['id']);
            $i = 0;
            foreach ($orders as  $order) {
            ?>
                <tr>
                    <td>
                        <div class="accordion" id="accordionFlushExample1">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?= $i ?>" aria-expanded="false" aria-controls="collapseOne">
                                        <?= $order->date ?>
                                    </button>
                                </h2>
                                <div id="collapseOne<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFlushExample1">
                                    <div class="accordion-body d-flex" style="flex-wrap: wrap;">
                                        <?php
                                        $opj = new Order();
                                        $products = $opj->selectProduct($order->order_id);

                                        foreach ($products as  $product) {
                                        ?>
                                            <div class="card" style="width: 18rem;">
                                                <img src="../images/<?= $product->pic ?>" class="card-img-top" alt="...">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="font-weight: bold; text-align:center"> <?= $product->name ?></h5>
                                                    <hr />
                                                    <h6>price: <?= $product->price ?> EG</h6>
                                                    <h6>quantity: <?= $product->quantity ?></h6>
                                                </div>
                                            </div>
                                        <?php    } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                    <td> <?= $order->status ?></td>
                    <td> <?= $order->amount . " EG" ?> </td>
                    <?php
                    if ($order->status == "processing")
                        echo " <td> 
                   <a href='deleteOrder.php?id=$order->order_id' class='btn btn-danger fw-bold'>
                  Cancel
                  </a>
                   </td>";
                    ?>

                </tr>
            <?php $i++;
            } ?>
        </table>
        <?php
        $total = null;
        foreach ($orders as  $order) {
            $total += $order->amount;
        }
        echo "<p style='text-align: right;font-size:20px' class='m-5 fw-bold'> Total price = $total EG</p>";
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
<?php
class Order
{
    public function connect()
    {
        try {
            $servername = "109.106.246.1";
            $username = "u635309332_root1";
            $password = "rootPass1";
            $db = new PDO("mysql:host=$servername;dbname=u635309332_cafateria1", $username, $password);
            return  $db;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function select($from, $to, $user_id)
    {
        try {
            $opj = new Order();
            $db = $opj->connect();
            $select_query = "SELECT orders.order_id,orders.user_id,orders.date,orders.status,SUM(product.price*order_product.quantity) as amount 
            from orders,order_product,product
             WHERE orders.order_id=order_product.order_id and
              order_product.product_id=product.product_id and
              `date` BETWEEN  '" . $from . "' and  '" . $to . "' and
              orders.user_id=$user_id GROUP BY orders.order_id";
            $stmt = $db->prepare($select_query);
            $res = $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $rows;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }
    public function selectProduct($order_id)
    {
        try {
            $opj = new Order();
            $db = $opj->connect();
            $select_query = "SELECT product.name ,product.price , product.pic ,order_product.quantity FROM orders ,product,order_product where orders.order_id=order_product.order_id AND order_product.product_id=product.product_id and orders.order_id= '" . $order_id . "'";
            $stmt = $db->prepare($select_query);
            $res = $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
            return ($rows);
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }
    public function deleteOrder($order_id)
    {
        try {
            $opj = new Order();
            $db = $opj->connect();
            if ($db) {
                $delete_query = "DELETE FROM `orders` WHERE `order_id`= $order_id";
                $del_stmt = $db->prepare($delete_query);
                $res = $del_stmt->execute();
                return $res;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function deletefromProdactOrder($order_id)
    {
        try {
            $opj = new Order();
            $db = $opj->connect();
            if ($db) {
                $delete_query = "DELETE FROM `order_product` WHERE `order_id`= $order_id";
                $del_stmt = $db->prepare($delete_query);
                $res = $del_stmt->execute();
                return $res;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
};
?>