import { addCart, updateCart, fetchCartItems, fetchCartSummary } from "./api";
import { updateCartSnackbar, flyToCart, shakeCart, isVisible } from "./ui";

document.addEventListener("DOMContentLoaded", async () => {
    const items = await fetchCartItems();
    restoreMenuUI(items);

    const summary = await fetchCartSummary();
    updateCartSnackbar(summary);
});

function restoreMenuUI(items) {
    items.forEach((item) => {
        const card = document.querySelector(`[data-menu-id="${item.menu_id}"]`);
        if (!card) return;

        renderStepper(card, item.qty);
    });
}

window.addToCart = async function (menuId) {
    const card = document.querySelector(`[data-menu-id="${menuId}"]`);
    if (!card) return;

    const data = await addCart(menuId);

    card.classList.remove("border-white");
    card.classList.add("border-primary-500");

    renderStepper(card, data.qty);
    updateCartSnackbar(data);

    if (data.total_qty > 1) {
        requestAnimationFrame(() => {
            flyToCart(menuId);
        });
        setTimeout(shakeCart, 400);
    }
};

async function changeQty(menuId, action) {
    const card = document.querySelector(`[data-menu-id="${menuId}"]`);
    if (!card) return;

    if (action === "increase") {
        flyToCart(menuId);
        setTimeout(shakeCart, 400);
    }

    const data = await updateCart(menuId, action);

    if (data.qty <= 0) {
        card.classList.remove("border-primary-500");
        card.classList.add("border-white");
        resetToAddButton(card, menuId);
    } else {
        renderStepper(card, data.qty);
    }

    updateCartSnackbar(data);
}

function renderStepper(card, qty) {
    card.classList.remove("border-white");
    card.classList.add("border-primary-500");

    const actionBox = card.querySelector(".menu-action");
    const template = document
        .getElementById("qtyInput")
        .content.cloneNode(true);

    template.querySelector(".qty").innerText = qty;
    template.querySelector('[data-action="increase"]').onclick = () =>
        changeQty(card.dataset.menuId, "increase");

    template.querySelector('[data-action="decrease"]').onclick = () =>
        changeQty(card.dataset.menuId, "decrease");

    actionBox.innerHTML = "";
    actionBox.appendChild(template);
}

function resetToAddButton(card, menuId) {
    card.classList.remove("border-primary-500");
    card.querySelector(".menu-action").innerHTML = `
        <button onclick="addToCart(${menuId})"
            class="w-full bg-primary-500 text-white rounded-lg py-1 text-sm">
            Tambah
        </button>
    `;
}
