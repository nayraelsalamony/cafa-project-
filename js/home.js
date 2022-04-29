let items = [...document.getElementsByClassName("product")]


for (const item of items) {

    item.addEventListener("click", function (e) {
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
        deleteBtn.addEventListener("click", function () {
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
            elementPrice.innerText = itemPrice + "EG";

            total.innerText = "Total: " + totalOrderPrice() + "EG";
        })

        plusBtn.addEventListener("click", () => {
            let count = parseInt(quantity.value) + 1;
            quantity.value = count;
            let itemPrice = price * parseInt(quantity.value);
            elementPrice.innerText = itemPrice + "EG";

            total.innerText = "Total: " + totalOrderPrice() + "EG";

        })

        total.innerText = "Total: " + totalOrderPrice() + "EG";
    })
}


const totalOrderPrice = function () {
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