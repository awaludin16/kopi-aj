const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
export const csrfToken = csrfTokenMeta ? csrfTokenMeta.content : null;

async function cartRequest(url, payload = {}) {
    const res = await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(payload),
    });

    return res.json();
}

export function addCart(menuId) {
    return cartRequest("/cart/add", { menu_id: menuId });
}

export function updateCart(menuId, action) {
    return cartRequest("/cart/update", {
        menu_id: menuId,
        action,
    });
}

export async function fetchCartItems() {
    const res = await fetch("/cart/items");
    return res.json();
}

export async function fetchCartSummary() {
    const res = await fetch("/cart/summary");
    return res.json();
}

export async function fetchOrderSummary() {
    const res = await fetch("/order/summary");
    return res.json();
}

export async function saveOrderNote(orderId, note) {
    await fetch("/order/note", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            order_id: orderId,
            note: note,
        }),
    });
}
