import { cartRequest } from "./api";

async function addCart(menuId) {
    return cartRequest("/cart/add", { menu_id: menuId });
}

async function updateCart(menuId, action) {
    return cartRequest("/cart/update", {
        menu_id: menuId,
        action,
    });
}

export { addCart, updateCart };
