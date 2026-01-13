import { formatCurrency } from "../utils/money.js";
import { getAssetUrl } from "../utils/assets.js";

export function getProduct(productId) {
    let cartSummaryHTML = "";

    let matchingProduct;

    products.forEach((product) => {
        if (product.id === productId) {
            matchingProduct = product;
        }
    });

    return matchingProduct;
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
    }

    getStarsUrl() {
        return getAssetUrl(
            `images/ratings/rating-${this.rating.stars * 10}.png`
        );
    }
    getPrice() {
        return `$${formatCurrency(this.priceCents)}`;
    }
    extraInfoHTML() {
        return "";
    }
}

class Clothing extends Product {
    sizeChartLink;

    constructor(productDetails) {
        super(productDetails);
        this.sizeChartLink = getAssetUrl(productDetails.sizeChartLink);
    }
    extraInfoHTML() {
        return `<a href="${this.sizeChartLink}" target="_blank">Size Chart</a>`;
    }
}
class Appliance extends Product {
    instructionsLink;
    warrantyLink;

    constructor(productDetails) {
        super(productDetails);
        this.instructionsLink = getAssetUrl(productDetails.instructionsLink);
        this.warrantyLink = getAssetUrl(productDetails.warrantyLink);
    }
    extraInfoHTML() {
        return `<a href="${this.instructionsLink}" target="_blank">Instructions</a>
            <a href="${this.warrantyLink}" target="_blank">warranty</a>`;
    }
}

const product1 = new Product({
    id: "e43638ce-6aa0-4b85-b27f-e1d07eb678c6",
    image: "images/products/athletic-cotton-socks-6-pairs.jpg",
    name: "Black and Gray Athletic Cotton Socks - 6 Pairs",
    rating: {
        stars: 4.5,
        count: 87,
    },
    priceCents: 1090,
    keywords: ["socks", "sports", "apparel"],
});

/*
const date = new Date();
console.log(date);
console.log(date.toLocaleTimeString());


console.log(this);

const object2 = {
  a: 2,
  b: this.a
}
  
 
function logThis(){
  console.log(this);
}
logThis.call('hello');


const object3={
  method: ()=>{
    console.log(this);
  }
}
*/

export let products = [];

export async function loadProductsFetch() {
    try {
        const response = await fetch("https://supersimplebackend.dev/products");

        const productsData = await response.json();

        products = productsData.map((productDetails) => {
            switch (productDetails.type) {
                case "clothing":
                    return new Clothing(productDetails);
                case "appliance":
                    return new Appliance(productDetails);
                default:
                    return new Product(productDetails);
            }
        });

        console.log("Products loaded");
    } catch (error) {
        console.log("Unexpected error");
    }
}
