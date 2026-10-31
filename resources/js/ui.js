export function updateCartSnackbar(data) {
    const snackbar = document.getElementById("cartSnackbar");
    if (!snackbar) return;

    const qtyEl = snackbar.querySelector(".cart-qty");
    const totalEl = snackbar.querySelector(".cart-total");

    if (data.total_qty > 0) {
        snackbar.classList.remove("hidden");
        snackbar.classList.add("flex");
        qtyEl.innerText = data.total_qty;
        totalEl.innerText =
            "Rp" + Number(data.subtotal).toLocaleString("id-ID");
    } else {
        snackbar.classList.add("hidden");
        snackbar.classList.remove("flex");
    }
}

export function isVisible(el) {
    return el && el.offsetParent !== null;
}

export function shakeCart() {
    const cart = document.querySelector(".cart-qty");
    if (!cart) return;
    cart.classList.remove("cart-shake");
    void cart.offsetWidth;
    cart.classList.add("cart-shake");
}

export function flyToCart(menuId) {
    const card = document.querySelector(`[data-menu-id="${menuId}"]`);
    const img = card?.querySelector(".menu-image");
    const cart = document.querySelector(".cart-qty");
    const snackbar = document.getElementById("cartSnackbar");

    if (!img || !cart || isVisible(snackbar)) return;

    const imgRect = img.getBoundingClientRect();
    const cartRect = cart.getBoundingClientRect();

    const flyingImg = img.cloneNode(true);
    flyingImg.classList.add("fly-image");

    Object.assign(flyingImg.style, {
        left: imgRect.left + "px",
        top: imgRect.top + "px",
        width: imgRect.width + "px",
        height: imgRect.height + "px",
    });

    document.body.appendChild(flyingImg);

    requestAnimationFrame(() => {
        flyingImg.style.left = cartRect.left + "px";
        flyingImg.style.top = cartRect.top + "px";
        flyingImg.style.width = "24px";
        flyingImg.style.height = "24px";
        flyingImg.style.opacity = "0.2";
    });

    flyingImg.addEventListener("transitionend", () => flyingImg.remove());
}
