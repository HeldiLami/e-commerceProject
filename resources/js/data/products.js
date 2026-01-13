import { formatCurrency } from "../utils/money.js";
import { getAssetUrl } from "../utils/assets.js";

export function getProduct(productId) {
    return products.find((product) => product.id === productId);
}

class Product {
    id;
    image;
    name;
    rating;
    priceCents;

    constructor(productDetails) {
        this.id = productDetails.id;
        this.image = getAssetUrl(productDetails.image);
        this.name = productDetails.name;
        this.rating = productDetails.rating;
        this.priceCents = productDetails.priceCents;
        this.quantity = productDetails.quantity;
    }

    getStarsUrl() {
        return getAssetUrl(
            `images/ratings/rating-${this.rating.stars * 10}.png`
        );
    }
    getPrice() {
        return `$${formatCurrency(this.priceCents)}`;
    }
    getInventoryStatus() {
        return this.quantity > 0 ? `${this.quantity} in stock` : "Out of Stock";
    }
    extraInfoHTML() {
        return "";
    }
}

export let products = [];

export async function loadProductsFetch() {
    try {
        const response = await fetch("https://supersimplebackend.dev/products");

        const productsData = await response.json();

        products = productsData.map((productDetails) => {
            return new Product(productDetails);
        });

        console.log("Products loaded");
    } catch (error) {
        console.log("Unexpected error");
    }
}
