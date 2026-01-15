
export function getProduct(productId) {
    return products.find((product) => product.id === productId);
}
export let products = [];