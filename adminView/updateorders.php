<?php
require '../dbConnections/pdo.php';
$OrderID="";
$status="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["order"])) {
    $OrderID="";
  }else{
    $OrderID=$_POST['order'];
  }

  if (empty($_POST['status'])) {
    $status="";
  }else{
    $status=$_POST['status'];
  }
  }

  if($status == "done"){
    $sql= "UPDATE orders SET status=? WHERE order_id=?";
    $stmt=$db->prepare($sql);
    $stmt->execute(['done',$OrderID]);
  }else if ($status == "deliver"){
    $sql= "UPDATE orders SET status=? WHERE order_id=?";
    $stmt=$db->prepare($sql);
    $stmt->execute(['out for delivery',$OrderID]);

  }


  ?>
