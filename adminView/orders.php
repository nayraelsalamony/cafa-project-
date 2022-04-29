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
include "../dbConnections/pdo.php"; 
$sql = "SELECT order_id,date,status,username,room,ext FROM orders o,user u WHERE o.user_id = u.user_id and NOT o.status = 'done'";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$query = "SELECT o.order_id,o.status,p.name,p.price,p.pic,op.quantity FROM orders o join order_product op on o.order_id = op.order_id join product p on p.product_id = op.product_id where NOT o.status = 'done' order by o.order_id";
$stmt = $db->prepare($query);
$stmt->execute();
$res = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check orders</title>
    <link rel="stylesheet" href="../css/admin_header.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/listProducts.css" />


</head>

<body>
    <?php include('../navbars/admin_header.php') ?>
    <main id='main-container' class="container p-4">

        <div id="table-container">


            <div class='table-title'>
                <div style="font-size: 50px; margin:auto">Orders</div>
            </div>

            <table>
                <tr class="table-header">
                    <th>Order Date</th>
                    <th>Name</th>
                    <th>Room</th>
                    <th>Ext</th>
                    <th>Actions</th>
                </tr>

                <?php
        foreach ($result as $data) {
          echo "<tr class='mb-3' style='font-weight:1000;'>";
          echo "<td>" . $data['date'] . "</td><td>" . $data['username'] . "</td><td>" . $data['room'] . "</td><td>" . $data['ext'] . "</td><td>";
          if ($data['status'] != "out for delivery") {
            echo "<button class='btn btn-success mr-3' onclick=updateOrder(" . $data['order_id'] . ",'deliver',this)>Deliver</button>";
          }
          echo "<button class='btn btn-info'  onclick=updateOrder(" . $data['order_id'] . ",'done',this)>Done</button>
      </td>";
          echo "</tr>";
          echo "<tr><td class='row-data' colspan='100%'><div class='orderr-items' >";
          $total = 0;
          foreach ($res as $orderdata) {
            if ($data['order_id'] == $orderdata['order_id']) {
              echo "<div class='card'>
          <div class='item-img'> <img src='../images/" . $orderdata['pic'] . "' class='card-img-top' style='width:100% ; height:100px'></div>
        
          <div class='card-body'>
                  <h5 class='card-title' style='font-weight: bold; text-align:center'>" . $orderdata['name'] . "</h5>
                  <hr />
                  <h6>price: " . $orderdata['price'] . " EG" . "</h6>
                  <h6>quantity: " . $orderdata['quantity'] . "</h6>
                </div>
          
                </div>";
              $total += $orderdata['price'] * $orderdata['quantity'];
            }
          }
          echo "</div>";
          echo " <div class='container '  >
        <ul class='list-group list-group-flush'>
        <li class='list-group-item' ><div class='label'>Total price = " . $total . " EG" . "</li>
        <li class='list-group-item'  id='" . $data['order_id'] . "'>Status : " . $data['status'] . "</li></ul></div> </div>";
          echo "</td></tr>";
        }
        ?>
            </table>
        </div>
    </main>

    <script>
    function updateOrder(id, status, e) {
        var rout = new XMLHttpRequest();
        rout.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (status == "deliver") {
                    document.getElementById(id).innerText = "Status : out for delivery";
                    e.remove();
                } else {
                    e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
                    let remove = document.getElementById(id);
                    remove.parentNode.parentNode.parentNode.removeChild(remove.parentNode.parentNode);
                }
            }
        };
        rout.open("POST", "updateorders.php", true);
        rout.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        rout.send("order=" + id + "&status=" + status);
    }
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>

</html>