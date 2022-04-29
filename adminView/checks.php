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


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Checks</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
	<link rel="stylesheet" href="../css/check.css" />
</head>



<body>
	<?php include('../navbars/admin_header.php') ?>
	<div class="form-container">
		<?php
		$from = NULL;
		$to = NULL;
		$user_id  =  NULL;

		if (isset($_GET['from'])) {

			$from = $_GET['from'];
		}
		if (isset($_GET['to'])) {

			$to = $_GET['to'];
		}
		if (isset($_GET['user_id'])) {

			$user_id = $_GET['user_id'];
		}

		$opj = new Order();


		$allUsers = $opj->selectAllUsers();



		?>

		<form method="get" action="checks.php" style="text-align: center">
			<label class="fw-bold"> From </label><i class="fa-solid fa-calendar-days cal"></i>
			<input type="date" name="from" />
			<label class="fw-bold"> To </label><i class="fa-solid fa-calendar-days cal"></i>
			<input type="date" name="to" />
			</br></br></br>
			<label class="fw-bold"> User </label><i class="fa-solid fa-user cal"></i>
			<select name="user_id" class="sel-user">
				<option value=""></option>
				<?php

				foreach ($allUsers as $user) {

					$selected = '';
					if ($user->user_id == $user_id) {
						$selected = 'selected';
					}

					echo "<option" . $selected . " value='" . $user->user_id . "'>" . $user->username . "</option>";
				}

				?>
			</select>

			<button type="submit" class="form-btn">Search</button>
		</form>
	</div>

	<div class="container tbl">
		<table style="width: 100%">
			<tr>
				<th style="width: 70%; ">Client</th>
				<th>Total Amount</th>
			</tr>
			<?php
			$users = select($from, $to, $user_id);
			$i = 0;
			foreach ($users as $user) {

			?>
				<tr>
					<td>
						<div class="accordion" id="accordionFlushExample">


							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingOne">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne<?= $i ?>" aria-expanded="false" aria-controls="flush-collapseOne" style="width: 300px; font-weight: bold; text-align:center">
										<?= $user->username ?>

									</button>
								</h2>
								<div id="flush-collapseOne<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<div class="accordion" id="accordionFlushExample1">
											<?php
											$orders = getUserOrders($user->user_id, $from, $to);
											foreach ($orders as  $order) {
											?>
												<div class="accordion-item">
													<h2 class="accordion-header" id="headingOne">
														<button class="accordion-button collapsed" style="width: 100%;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?= $i ?>" aria-expanded="false" aria-controls="collapseOne">
															<table style="width: 100%" class="tb-child">
																<tr>
																	<th style="border-right: 1px solid #fff;"> Order date </th>
																	<th> Amount </th>
																</tr>
																<tr style="font-weight: bold; font-size:18px ; text-align:center">
																	<td style=" font-size:16px ; "> <?= $order->date ?> </td>
																	<td> <?= $order->total ?> EG</td>
																</tr>
															</table>
														</button>
													</h2>
													<div id="collapseOne<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFlushExample1">
														<div class="accordion-body d-flex" style="flex-wrap: wrap;">
															<?php
															$opj = new Order();
															$products = $opj->selectProduct($order->order_id);
															foreach ($products as  $product) {
															?>
																<div class="card">
																	<img src="../images/<?= $product->pic ?>" class="card-img-top" alt="..." style="width:100% ; height:100px">
																	<div class="card-body">
																		<h5 class="card-title" style="font-weight: bold; text-align:center">
																			<?= $product->name ?></h5>
																		<hr />
																		<h6 style="text-align:center">price: <?= $product->price ?> EG</h6>
																		<h6 style="text-align:center">quantity: <?= $product->quantity ?></h6>
																	</div>
																</div>
															<?php	} ?>
														</div>
													</div>
												</div>
											<?php	} ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</td>
					<td style="font-weight: bold; font-size:18px ; text-align:center"> <?= $user->total ?> EG</td>
				</tr>

			<?php $i++;
			} ?>
		</table>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
	</script>
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

	public function selectAllUsers()
	{
		try {
			$opj = new Order();
			$db = $opj->connect();
			$select_query = "SELECT DISTINCT user.username , orders.user_id , SUM(product.price*order_product.quantity) as total from user,orders,product,order_product
			                 where user.user_id = orders.user_id and orders.order_id=order_product.order_id and order_product.product_id=product.product_id GROUP by username ";
			$stmt = $db->prepare($select_query);
			$res = $stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			return ($rows);
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}

	public function selectUser($user_id)
	{
		try {
			$opj = new Order();
			$db = $opj->connect();
			$select_query = "SELECT DISTINCT user.username, orders.user_id , SUM(product.price*order_product.quantity) as total from user,orders,product,order_product 
			                 where user.user_id = orders.user_id and orders.user_id = '" . $user_id . "' and orders.order_id=order_product.order_id and order_product.product_id=product.product_id ";
			$stmt = $db->prepare($select_query);
			$res = $stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			return ($rows);
		} catch (PDOException $e) {
			$e->getMessage();
		}
		return ($rows);
	}

	public function selectDate($from, $to)
	{
		try {
			$opj = new Order();
			$db = $opj->connect();
			$select_query = "SELECT DISTINCT user.username , orders.user_id , SUM(product.price*order_product.quantity) as total from user,orders,product,order_product 
			                 where user.user_id = orders.user_id and orders.date BETWEEN '" . $from . "' and  '" . $to . "' and orders.order_id=order_product.order_id and order_product.product_id=product.product_id GROUP by username";
			$stmt = $db->prepare($select_query);
			$res = $stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			return ($rows);
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}

	public function selectDateUser($from, $to, $user_id)
	{
		try {
			$opj = new Order();
			$db = $opj->connect();
			$select_query = "SELECT DISTINCT user.username , orders.user_id , SUM(product.price*order_product.quantity) as total from user,orders,product,order_product  
			                where user.user_id = orders.user_id and orders.date BETWEEN '" . $from . "' and  '" . $to . "' and orders.user_id = '" . $user_id . "' and orders.order_id=order_product.order_id and order_product.product_id=product.product_id ";
			$stmt = $db->prepare($select_query);
			$res = $stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			return ($rows);
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}

	public function UsersOrder($user_id, $from, $to)
	{
		try {
			$opj = new Order();
			$db = $opj->connect();
			$select_query = "SELECT date , orders.order_id , SUM(product.price*order_product.quantity) as total from orders,product,order_product 
							 where orders.user_id = '" . $user_id . "' and orders.date BETWEEN '" . $from . "' and  '" . $to . "' and orders.order_id=order_product.order_id and order_product.product_id=product.product_id GROUP by orders.order_id";
			$stmt = $db->prepare($select_query);
			$res = $stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			return ($rows);
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}

	public function order($user_id)
	{
		try {
			$opj = new Order();
			$db = $opj->connect();
			$select_query = "SELECT date , orders.order_id , SUM(product.price*order_product.quantity) as total from orders,product,order_product
			                 where orders.user_id = '" . $user_id . "' and orders.order_id=order_product.order_id and order_product.product_id=product.product_id GROUP by orders.order_id";
			$stmt = $db->prepare($select_query);
			$res = $stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			return ($rows);
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
};


function select($from, $to, $user_id)
{
	$opj = new Order();
	if ($from == null && $to == null && $user_id == null) {
		$selectUsers = $opj->selectAllUsers();
		return $selectUsers;
	}

	if ($from == null && $to == null && $user_id != null) {
		$selectUser = $opj->selectUser($user_id);
		return $selectUser;
	}

	if ($from != null && $to != null && $user_id == null) {
		$selectDate = $opj->selectDate($from, $to);
		return $selectDate;
	} else {
		$selectDateUser = $opj->selectDateUser($from, $to, $user_id);
		return $selectDateUser;
	}
}

function getUserOrders($user_id, $from, $to)
{
	$opj = new Order();
	if ($from == null && $to == null) {
		$order = $opj->order($user_id);
		return $order;
	} else {
		$UsersOrder = $opj->UsersOrder($user_id, $from, $to);
		return $UsersOrder;
	}
}


?>