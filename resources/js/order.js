import { updateCart, saveOrderNote } from "./api";
import { updateCartSnackbar, shakeCart } from "./ui";

document.addEventListener("click", async (e) => {
    const btn = e.target.closest("[data-action]");
    if (!btn) return;

    const action = btn.dataset.action;
    const menuId = btn.dataset.id;
    if (!menuId) return;

    const data = await updateCart(menuId, action);

    updateOrderRow(menuId, data);
    updateCartSnackbar(data);
    shakeCart();
});

function updateOrderRow(menuId, order) {
    const orderBox = document.getElementById("data-order");
    const row = orderBox.querySelector(`[data-order-item="${menuId}"]`);
    if (!row) return;

    const menuHeading = orderBox.querySelector(".menu-heading");
    const paymentDetails = orderBox.querySelector(".payment-details");
    const fixedButtonBox = orderBox.querySelector(".fixed-button-box");

    if (order.total_item > 0) {
        if (order.qty > 0) {
            row.querySelector(".qty").innerText = order.qty;
            row.querySelector(".subtotal-item").innerText =
                "Rp" + Number(order.subtotal_item).toLocaleString("id-ID");
        } else {
            row.remove();
        }
        // console.log("items" + order.total_item);
        menuHeading.querySelector(
            ".total-item"
        ).innerText = `(${order.total_item})`;
        paymentDetails.querySelector(
            ".total-item"
        ).innerText = `(${order.total_item})`;
        orderBox.querySelector(".subtotal").innerText =
            "Rp" + Number(order.subtotal).toLocaleString("id-ID");
        orderBox.querySelector(".other-costs").innerText =
            "Rp" + Number(order.otherCosts).toLocaleString("id-ID");
        orderBox.querySelector(".pb1").innerText =
            "Rp" + Number(order.pb1).toLocaleString("id-ID");
        orderBox.querySelector(".rounding").innerText =
            "-Rp" + Number(order.rounding).toLocaleString("id-ID");
        paymentDetails.querySelector(".total").innerText =
            "Rp" + Number(order.total).toLocaleString("id-ID");
        fixedButtonBox.querySelector(".total").innerText =
            "Rp" + Number(order.total).toLocaleString("id-ID");
    } else {
        const baseURL = "http://localhost:8000";
        location.href = `${baseURL}/menu`;
    }
}

let noteTimer = null;

document.addEventListener("input", (e) => {
    if (!e.target.classList.contains("order-note")) return;

    const menuId = e.target.dataset.menuId;
    const note = e.target.value;

    clearTimeout(noteTimer);

    noteTimer = setTimeout(() => {
        saveOrderNote(menuId, note);
    }, 500); // debounce
});
