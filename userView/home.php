<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: ../login/login.php');
}
if ($_SESSION['is_admin'] == 1) {
    die("Access Denied");
}

// default value for testing

$user_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../css/adminNav.css" />
    <link rel="stylesheet" href="../css/manualOrder.css" />


    <title>Home</title>


</head>



<!-- TO DO getting all user info  -->

<body>
    <?php include('../navbars/user_header.php') ?>

    <div class="main">
        <form class="order-data" id="form" action="insertOrder.php" method="post">
            <div class="order">
                <div id="list"></div>
            </div>
            <hr>
            <div class="notes">
                <label id="notes" for="notes">Notes:</label>
                <textarea name="notes" id="notes" rows="4" cols="50"></textarea>
            </div>
            <div class="room">
                <label for="room">Room</label>
                <select name="room" id="room">
                    <option value="1001">1001</option>
                    <option value="1002">1002</option>
                    <option value="1003">1003</option>
                </select>
            </div>
            <div class='footer'>
                <div id="orderFooter" class="orderFooter">
                    <hr>
                    <h6 id=total>Total: 0 EG</h6>
                    <hr>
                </div>
                <button class="confirm" type="submit">Confirm</button>
            </div>
        </form>
        <div class="product-list-addUser">
            <?php
            include "../dbConnections/pdo.php";
            //latest order
            $user_id = $_SESSION['id'];

            $query = "SELECT p.name , o.quantity ,p.pic FROM order_product o,product p WHERE p.product_id=o.product_id AND order_id=(SELECT order_id FROM orders WHERE user_id=$user_id ORDER by date DESC limit 1 )";
            $stmt = $db->query($query);
            $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);

            echo "<div class='d-flex'>
            <input class='form-control me-2 search-bar' type='search' placeholder='Search' aria-label='Search'
                name='search' id='search'>
             </div>";

            echo "<div class='latest-order mx-5 my-5'>
            <h5 class='mb-4'>Latest Order</h5>
            <div class='all-items' style='display: flex;justify-content: flex-start;'>";

            while ($ele = $stmt->fetch()) {
                echo ("<div class='order-item mr-3'>
                <img class='item-img' src='../images/{$ele['pic']}'  />
                <div>{$ele['name']}</div>
            </div>");
            }
            echo "</div></div>";

            // show all products
            $query = "SELECT product_id,name,price,pic FROM product";
            $stmt = $db->query($query);

            $res = $stmt->setFetchMode(PDO::FETCH_ASSOC);


            echo "<div class='products-list'><h3 class='products-list-title'>Available Products</h3>";
            echo "<div class='items-list'>";
            while ($ele = $stmt->fetch()) {
                echo ("<div class=item>
                <img class='item-img product'  data-price={$ele['price']} data-name={$ele['name']} data-id={$ele['product_id']} src=../images/{$ele['pic']}  />
                <div class='item-body'>
                        <h5 style='font-weight:bold'>{$ele['name']}</h5>
                        <hr/>
                        <h6>{$ele['price']} EG</h6>
                </div>

            </div>");
            }

            echo "</div>";
            echo "</div>";
            $db = null;
            ?>
        </div>
    </div>

    <script>
        let items = [...document.getElementsByClassName("product")]


        for (const item of items) {

            item.addEventListener("click", function(e) {
                let orderList = document.getElementById("list")
                let {
                    name,
                    price,
                    id
                } = e.target.dataset;
                price = parseInt(price);

                let elementExist = document.getElementById(`${id}`);
                let total = document.getElementById("total");

                if (elementExist) {
                    return;
                }

                let div = document.createElement("div");
                div.setAttribute("class", "list_element");
                div.setAttribute("id", `${id}`);

                let span = document.createElement("div");
                span.innerText = `${name}`;
                span.setAttribute("class", "item-name");
                div.appendChild(span);
                let counterDiv = document.createElement("div");
                counterDiv.setAttribute("class", "item-input");
                let minusBtn = document.createElement("button");
                minusBtn.setAttribute("class", "minus");
                minusBtn.type = "button";
                minusBtn.innerText = "-";
                counterDiv.appendChild(minusBtn);
                let quantity = document.createElement("input");
                quantity.setAttribute("name", `quantity[${id}]`);
                quantity.setAttribute("type", "text");
                quantity.setAttribute("value", "1");
                quantity.setAttribute("data-price", `${price}`);
                counterDiv.appendChild(quantity);
                let plusBtn = document.createElement("button");
                plusBtn.type = "button";
                plusBtn.innerText = "+";
                plusBtn.setAttribute("class", "plus");
                counterDiv.appendChild(plusBtn)
                div.appendChild(counterDiv);

                let elementPrice = document.createElement("span");

                elementPrice.innerText = `${price} EG`
                elementPrice.setAttribute("class", "elementPrice");
                div.appendChild(elementPrice);

                let deleteBtn = document.createElement("button");
                deleteBtn.innerText = "X";
                deleteBtn.type = "button"
                deleteBtn.setAttribute("class", "deleteBtn");
                deleteBtn.addEventListener("click", function() {
                    orderList.removeChild(div);
                    total.innerText = totalOrderPrice() + "EG";
                })
                div.appendChild(deleteBtn);
                orderList.appendChild(div);

                minusBtn.addEventListener("click", () => {
                    let count = parseInt(quantity.value) - 1;
                    count = count < 1 ? 1 : count;
                    quantity.value = count;
                    let itemPrice = price * parseInt(quantity.value);
                    elementPrice.innerText = itemPrice + " EG";

                    total.innerText = "Total: " + totalOrderPrice() + " EG";
                })

                plusBtn.addEventListener("click", () => {
                    let count = parseInt(quantity.value) + 1;
                    quantity.value = count;
                    let itemPrice = price * parseInt(quantity.value);
                    elementPrice.innerText = itemPrice + " EG";

                    total.innerText = "Total: " + totalOrderPrice() + " EG";

                })

                total.innerText = "Total: " + totalOrderPrice() + " EG";
            })
        }


        const totalOrderPrice = function() {
            let eachElementPrice = [...document.getElementsByClassName("elementPrice")];
            let sum = 0;
            for (const item of eachElementPrice) {
                sum += parseInt(item.innerText);
            }

            return sum;
        }

        let searchfield = document.getElementById("search");

        searchfield.addEventListener("keyup", (e) => {
            const searchText = e.target.value;

            [...document.body.getElementsByClassName("item")].forEach(item => {

                if (item.childNodes[1].dataset.name.includes(searchText)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            })

        })

        const form = document.getElementById("form");
    </script>

</body>

</html>