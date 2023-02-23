if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
} else {
    var cart = [];
}

window.addEventListener("storage", async function () {
    if (localStorage.getItem("cart") != null) {
        cart = JSON.parse(localStorage.getItem("cart"));
    } else {
        cart = [];
    }

    document.getElementById('cart-items').innerHTML = '';

    await populateCart();
    if (countTotal('all') == 'Rp 0') {
        document.getElementById('checkout-button').classList.add('d-none');
    }
}, false);

function getFpin() {
    let fpin;
    try {
        //android
        fpin = window.Android.getFPin();
    } catch (err) {
        //ios
        fpin = localStorage.getItem('f_pin');
    }

    return fpin;
}

$(document).ready(function() {
    if (localStorage.lang == 1) {
        $("#back-text").text("Kembali");
    }

    // $("body").css("visibility", "visible");
    document.querySelector("body").style.visibility = "visible";
})

function clearCart() {
    // let item_count = 0;
    // let item_to_remove_count = 0;
    // cart.forEach(merchant => {
    //     item_count += merchant.items.length;
    //     item_to_remove_count += merchant.items.filter(item => item.selected == 'checked').length;
    // })

    // if (item_count == item_to_remove_count) {
    localStorage.removeItem('cart');
    // } else {
    //     cart.forEach(merchant => {
    //         merchant.items = merchant.items.filter(item => item.selected != 'checked');
    //     })

    //     localStorage.setItem("cart", JSON.stringify(cart));
    // }
}

function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function countTotal(merchant, tab_name) {

    let totalPrice = 0;

    if (merchant == 'all') {
        cart.forEach(cartitem => {
            cartitem.items.forEach(item => {
                if (item.selected == 'checked') {
                    totalPrice += item.itemQuantity * item.itemPrice;
                }
            })
        })
    } else {
        cart.forEach(cartitem => {
            if (cartitem.merchant_name == merchant) {
                cartitem.items.forEach(item => {
                    if (tab_name == 'cart' && item.selected == 'checked') {
                        totalPrice += item.itemQuantity * item.itemPrice;
                    } else if (tab_name == 'saved' && item.selected != 'checked') {
                        totalPrice += item.itemQuantity * item.itemPrice;
                    }
                })
            }
        })
    }

    return "Rp " + numberWithDots(totalPrice);
};

function changeValue(id, mod, merchant, tab_name, maxQty) {
    if (mod == "add") {
        if (document.getElementById(id).value < maxQty) {
            document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;

        }
    } else {
        if (document.getElementById(id).value > 1) {
            document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
        }
    }

    cart.forEach(item => {
        item.items.forEach(item => {
            if (item.itemName == id.split('-')[0]) {
                item.itemQuantity = parseInt(document.getElementById(id).value);
                localStorage.setItem("cart", JSON.stringify(cart));
                countTotal(merchant, tab_name);
                tab_name == 'cart' ? populateCart() : populateSaved();
            }
        })

        if (item.merchant_name == merchant) {
            let total_merchant = document.getElementById("total-" + merchant.split(/\s+/).join('-') + tab_name == 'cart' ? 'cart' : 'saved');
            total_merchant.innerText = countTotal(merchant, tab_name);
        }
    })
};

function checkItem(item_name, tab_name) {
    cart.forEach((merchant) => {
        merchant.items.find((element) => {
            // if item on saved moved to cart
            if (element.itemName == item_name && element.selected == undefined) {
                element.selected = 'checked' // move to cart
                document.getElementById('checkout-button').classList.remove('d-none');
                localStorage.setItem('cart', JSON.stringify(cart));

                // of item on item cart move to saved
            } else if (element.itemName == item_name && element.selected != undefined) {
                element.selected = undefined; // move to saved
                localStorage.setItem('cart', JSON.stringify(cart));

                // delete checkout button if item in cart 0
                if (countTotal('all') == 'Rp 0') {
                    document.getElementById('checkout-button').classList.add('d-none');
                }
            }
        })
    })

    populateCart();
    populateSaved();
    changeTab(tab_name);
}

function populateSaved() {
    let saved = JSON.parse(localStorage.getItem("cart"));
    saved.forEach(merchant => merchant.items = merchant.items.filter(item => item.selected == undefined));
    saved = saved.filter(merchant => merchant.items.length > 0);

    document.getElementById('cart-items').classList.add('d-none');
    document.getElementById('pricetag').classList.add('d-none');
    document.getElementById('checkout-button').classList.add('d-none');

    document.getElementById('cart-saved').classList.remove('d-none');

    let cartItems = document.getElementById('cart-saved');
    cartItems.innerHTML = '';

    if (saved.length > 0) {
        document.getElementById('cart-empty').classList.add('d-none');
        document.getElementById('cart-body').classList.remove('d-none');

        saved.slice().reverse().forEach(item => {
            let merchant_name = item.merchant_name;

            let viewstore = "";
            if (localStorage.lang == 0) {
                viewstore = "View Store";
            } else {
                viewstore = "Lihat Toko";
            }

            let html_shop =
                `<div class="container-fluid p-4 shop">` +

                '<!-- shop name -->' +
                '<div class="row">' +
                '<div class="col-6">' +
                '<div class="row font-semibold store-name">' +
                `<div class="col-2"><img class="verified" src="../assets/img/cart/Verified.png"></div><div class="col-10 px-1">${merchant_name}</div>` +
                '</div>' +
                '</div>' +
                '<div class="col-6 align-items-center text-end small-text">' +
                `<a href="tab3-profile?store_id=${item.items[0].store_id}&f_pin=${getFpin()}" target = "_self">` +
                '<img class="view-store" src="../assets/img/cart/store_purple.png">' + viewstore +
                '</a>' +
                '</div>' +
                '</div>' +

                '<!-- item 1 -->' +
                `<div class="row mt-3" id="${merchant_name}-items-saved"></div>` +

                '</div>' +

                '<div class="container-fluid px-4 py-2">' +
                '<div class="row">' +
                '<div class="col-6 font-semibold">' +
                'Total' +
                '</div>' +
                `<div id="total-${merchant_name.split(/\s+/).join('-')}-saved" class="col-6 font-semibold text-end">` +
                `${countTotal(merchant_name, 'saved')}` +
                '</div>' +
                '</div>' +
                '</div>' +

                '<hr class="shop-border">';

            cartItems.innerHTML += html_shop;
            let shopItems = document.getElementById(`${merchant_name}-items-saved`);

            item.items.forEach(item => {

                function ext(url) {
                    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
                }

                let thumbnail_url;
                let thumb_src = item.thumbnail;
                if (thumb_src.includes("http://202.158.33.26")) {
                    thumb_src = thumb_src.replace("http://202.158.33.26", "");
                }
                if (!thumb_src.includes("/nexilis/images/")) {
                    thumb_src = "/nexilis/images/" + thumb_src;
                }
                if (ext(item.thumbnail) != 'mp4') {
                    thumbnail_url = `<img class="product-img" src="${thumb_src}">`;
                } else {
                    thumbnail_url = `<video class="product-img" autoplay muted>
                        <source src="${thumb_src}" type="video/mp4" />
                    </video>`;
                }

                let move = "";

                if (localStorage.lang == 0) {
                    move = "Move to cart";
                } else {
                    move = "Pindah ke keranjang";
                }

                let html_item =
                    `<div class="row mt-3">` +
                    '<!-- img -->' +
                    '<div class="col-3">' +
                    `${thumbnail_url}` +
                    '</div>' +
                    '<!-- details -->' +
                    '<div class="col-8 col-details font-medium">' +
                    '<div class="ps-3">' +
                    `<span class="item-name">${item.itemName}</span>` +
                    `<div class="item-price">Rp ${numberWithDots(item.itemPrice)}</div>` +
                    `<div class="row">` +
                    `<div class="col-5">` +
                    '<div class="input-group counter mt-2" style="width: 75px;">' +
                    `<button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeValue('${item.itemName}-quantity', 'sub', '${merchant_name}', 'saved', ${item.maxQty});">-</button>` +
                    `<input id="${item.itemName}-quantity" disabled type="number" maxlength="3" class="form-control text-center" min="1" value="${item.itemQuantity}">` +
                    `<button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeValue('${item.itemName}-quantity', 'add', '${merchant_name}', 'saved', ${item.maxQty});">+</button>` +
                    '</div>' +
                    `</div>` +
                    '<div class="col-6 d-flex align-items-end justify-content-center">' +
                    `<div class="text-grey" onclick="checkItem('${item.itemName}', 'cart')">` + move + `</div>` +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<!-- delete item -->' +
                    '<div class="col-1 col-delete">' +
                    '<div class="delete-btn">' +
                    `<img onclick="deleteItem('${merchant_name}', '${item.itemName}', 'saved');" class="delete-icon" src="../assets/img/cart/Delete.png">` +
                    '</div>' +
                    '</div>' +
                    '</div>';

                shopItems.innerHTML += html_item;
            })
        })
    } else {
        document.getElementById('cart-empty').classList.remove('d-none');
    }
}

function populateCart(mode) {
    // get all item in the cart (your cart tab)
    let cartStr = localStorage.getItem("cart");
    if (!cartStr) {
        cartStr = "[]";
    }
    let unsaved = JSON.parse(cartStr);
    unsaved.forEach(merchant => merchant.items = merchant.items.filter(item => item.selected == 'checked'));
    unsaved = unsaved.filter(merchant => merchant.items.length > 0);

    document.getElementById('cart-items').classList.remove('d-none');
    document.getElementById('cart-saved').classList.add('d-none');

    document.getElementById('cart-items').innerHTML = '';

    // if there are item(s) on your cart tab
    if (unsaved.length > 0) {

        document.getElementById('cart-empty').classList.add('d-none');
        document.getElementById('cart-body').classList.remove('d-none');
        document.getElementById('checkout-button').classList.remove('d-none');

        unsaved.slice().reverse().forEach(item => {
            let merchant_name = item.merchant_name;

            let cartItems = document.getElementById('cart-items');

            let viewstore = "";
            if (localStorage.lang == 0) {
                viewstore = "View Store";
            } else {
                viewstore = "Lihat Toko";
            }

            let html_shop =
                `<div class="container-fluid p-4 shop">` +

                '<!-- shop name -->' +
                '<div class="row">' +
                '<div class="col-6">' +
                '<div class="row font-semibold store-name">' +
                `<div class="col-2"><img class="verified" src="../assets/img/cart/Verified.png"></div><div class="col-10 px-1">${merchant_name}</div>` +
                '</div>' +
                '</div>' +
                '<div class="col-6 align-items-center text-end small-text">' +
                `<a href="tab3-profile?store_id=${item.items[0].store_id}&f_pin=${getFpin()}" target = "_self">` +
                '<img class="view-store" src="../assets/img/cart/store_purple.png"> ' + viewstore +
                '</a>' +
                '</div>' +
                '</div>' +

                '<!-- item 1 -->' +
                `<div class="row mt-3" id="${merchant_name}-items-cart"></div>` +

                '</div>' +

                '<div class="container-fluid px-4 py-2">' +
                '<div class="row">' +
                '<div class="col-6 font-semibold">' +
                'Total' +
                '</div>' +
                `<div id="total-${merchant_name.split(/\s+/).join('-')}-cart" class="col-6 font-semibold text-end">` +
                `${countTotal(merchant_name, 'cart')}` +
                '</div>' +
                '</div>' +
                '</div>' +

                '<hr class="shop-border">';

            cartItems.innerHTML += html_shop;
            let shopItems = document.getElementById(`${merchant_name}-items-cart`);

            item.items.forEach(item => {

                function ext(url) {
                    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
                }

                let thumbnail_url;
                let thumb_src = item.thumbnail;
                if (thumb_src.includes("http://202.158.33.26")) {
                    thumb_src = thumb_src.replace("http://202.158.33.26", "");
                }
                if (!thumb_src.includes("/nexilis/images/")) {
                    thumb_src = "/nexilis/images/" + thumb_src;
                }
                if (ext(item.thumbnail) != 'mp4') {
                    thumbnail_url = `<img class="product-img" src="${thumb_src}">`;
                } else {
                    thumbnail_url = `<video class="product-img" autoplay muted>
                        <source src="${thumb_src}" type="video/mp4" />
                    </video>`;
                }

                let save = "";
                if (localStorage.lang == 0) {
                    save = "Save for later";
                } else {
                    save = "Simpan barang";
                }

                let html_item =
                    `<div class="row mt-3">` +
                    '<!-- img -->' +
                    '<div class="col-3">' +
                    `${thumbnail_url}` +
                    '</div>' +
                    '<!-- details -->' +
                    '<div class="col-8 col-details font-medium">' +
                    '<div class="ps-3">' +
                    `<span class="item-name">${item.itemName}</span>` +
                    `<div class="item-price">Rp ${numberWithDots(item.itemPrice)}</div>` +
                    `<div class="row">` +
                    `<div class="col-5">` +
                    '<div class="input-group counter mt-2" style="width: 75px;">' +
                    `<button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeValue('${item.itemName}-quantity', 'sub', '${merchant_name}', 'cart', ${item.maxQty});">-</button>` +
                    `<input id="${item.itemName}-quantity" disabled type="number" maxlength="3" class="form-control text-center" min="1" value="${item.itemQuantity}">` +
                    `<button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeValue('${item.itemName}-quantity', 'add', '${merchant_name}', 'cart', ${item.maxQty});">+</button>` +
                    '</div>' +
                    `</div>` +
                    '<div class="col-6 d-flex align-items-end justify-content-center">' +
                    `<div class="text-grey" onclick="checkItem('${item.itemName}', 'saved')">` + save + `</div>` +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<!-- delete item -->' +
                    '<div class="col-1 col-delete">' +
                    '<div class="delete-btn">' +
                    `<img onclick="deleteItem('${merchant_name}', '${item.itemName}', 'cart');" class="delete-icon" src="../assets/img/cart/Delete.png">` +
                    '</div>' +
                    '</div>' +
                    '</div>';

                shopItems.innerHTML += html_item;
                payment();
            })
        })

    } else {
        document.getElementById('checkout-button').classList.add('d-none');
        document.getElementById('cart-empty').classList.remove('d-none');
        document.getElementById('pricetag').classList.add('d-none');
    }
};

async function payment() {

    let unsaved = JSON.parse(localStorage.getItem("cart"));
    unsaved.forEach(merchant => merchant.items = merchant.items.filter(item => item.selected == 'checked'));
    unsaved = unsaved.filter(merchant => merchant.items.length > 0);

    if (unsaved.length == 0) {

        let cartBody = document.getElementById('cart-body');
        cartBody.classList.add('d-none');

        let checkoutButton = document.getElementById('checkout-button');
        checkoutButton.classList.add('d-none');

        let cartEmpty = document.getElementById('cart-empty');
        cartEmpty.classList.remove('d-none');

    } else {

        let rate = await shippingRate();
        let tax = 0;
        let delivery = rate.data.fixed_price;
        let totalPrice = 0;
        let totalItem = 0;

        // delivery options
        let time_detail = rate.data.time_detail;
        time_detail.forEach(td => {

            let html_delivery =
                '<div class="row mb-2" style="border: 1px solid lightgray">' +
                '<div class="col-1 d-flex align-items-center justify-content-center p-0 delivery-options" style="margin-right: 5px;" onclick="checkThis(this);"><span style="height: 15px;width: 15px;background-color: transparent;border: 1px solid lightgray;border-radius: 50%;display: inline-block;"></span></div>' +
                '<div class="col-9">' +
                `<div class="row fw-bold">${td.service} ${delivery}</div>` +
                `<div class="row gray-text">Delivered on or before ${td.time_delivery_end} ${td.service == 'same_day' ? 'today' : 'next day'}</div>` +
                '</div>' +
                '</div>';


            document.getElementById("delivery-options") != null ? document.getElementById("delivery-options").innerHTML += html_delivery : {};
        });
        // checkThis(document.querySelector('.delivery-options'));

        unsaved.forEach(merchant => {
            merchant.items.forEach(item => {
                if (item.selected != undefined) {
                    totalPrice += item.itemQuantity * item.itemPrice;
                    totalItem += parseInt(item.itemQuantity);
                }
            })
        })

        let totalPriceTaxInc = totalPrice + tax / 100 * totalPrice + delivery;
        localStorage.setItem('grand-total', totalPriceTaxInc);

        document.getElementById("total-item").innerHTML = `Sub-total ( ${totalItem} items )`;
        document.getElementById("total-price").innerHTML = `Rp ${numberWithDots(totalPrice)}`;
        document.getElementById("delivery-cost").innerHTML = `Rp ${numberWithDots(delivery)}`;
        document.getElementById("total-price-tax-inc").innerHTML = `Rp ${numberWithDots(totalPriceTaxInc)}`;

    }

}

async function deleteItem(merchant_name, product_name, tab_name) {

    let confirmationModal = new ConfirmModal(merchant_name, product_name);
    let response = await confirmationModal.question();

    if (response == true) {

        // get items from selected merchant
        let items = cart.find(merchant => merchant.merchant_name == merchant_name).items;

        // get the index of deleted item
        let indexItem = items.indexOf(items.find(item => item.itemName == product_name));
        let indexMerchant = cart.indexOf(cart.find(merchant => merchant.merchant_name == merchant_name));

        // remove selected item
        cart.find(merchant => merchant.merchant_name == merchant_name).items.splice(indexItem, 1);

        // delete merchant from cart if no item
        if (cart.find(merchant => merchant.merchant_name == merchant_name).items.length == 0) {
            cart.splice(indexMerchant, 1);
        }

        // update localstorage
        localStorage.setItem('cart', JSON.stringify(cart));
        cart = JSON.parse(localStorage.getItem('cart'));
        countTotal(merchant_name, tab_name);
    }

    if (tab_name == 'cart') {
        populateCart();
    } else {
        populateSaved();
    }

}

function goBack() {
    // if (window.Android) {
    // window.Android.closeView();
    window.history.back();
    // } else if (window.webkit) {
    //     window.webkit.messageHandlers.messageHandler.postMessage({
    //         "message": "goBack"
    //     });
    // } else {
    //     window.history.back();
    // }
}

function selectMethod(e) {

    // document.getElementById('dropdownMenuSelectMethod').innerHTML = `${e} >`;
    // localStorage.setItem('payment-method', e.innerHTML);
    payment_method = e;
    console.log('select', payment_method);
}

function getMerchantAddress(merchant_name) {

    let dummy = JSON.parse('{"ID":1,"STORE_CODE":"1","ADDRESS":"Jl. Sultan Iskandar Muda No.6C","VILLAGE":"Kebayoran Lama","DISTRICT":"Kebayoran Lama","CITY":"Jakarta Selatan","PROVINCE":"DKI Jakarta","ZIP_CODE":"12240","PHONE_NUMBER":"081987654321","NOTE":"Lantai 6 divisi IT"}')
    return dummy;
}

function getItemDetail(item_name) {

    let dummy = JSON.parse('{"ID":1,"PRODUCT_CODE":"1","LENGTH":12,"WIDTH":13,"HEIGHT":14,"IS_FRAGILE":0,"WEIGHT":1,"CATEGORY":"Snack"}');
    return dummy;
}

function selectedItems() {
    let new_cart = [];
    cart.forEach(merchant => {
        let new_object = {}; //objek merchant
        merchant.items.forEach(item => {
            new_object.merchant_name = merchant.merchant_name;
            if (new_object.items == undefined) {
                new_object.items = []
            }
            new_object.items.push(item);
        })
        if (Object.entries(new_object).length !== 0) {
            new_cart.push(new_object)
        }
    })

    return new_cart;
}

function shippingRate() {

    let formData = new FormData();
    let rate = 0;

    let merchantCount = selectedItems().length;
    for (let i = 0; i < merchantCount; i++) {

        // get merchant address
        let origin = getMerchantAddress(selectedItems("checked")[i].merchant_name);
        let items = selectedItems("checked")[0].items;

        for (let item of items) {

            let itemDetail = getItemDetail(item.itemName);

            // origin
            formData.append("address_origin", origin.ADDRESS);
            formData.append("province_origin", origin.PROVINCE);
            formData.append("city_origin", origin.CITY);
            formData.append("district_origin", origin.DISTRICT);
            formData.append("zip_code_origin", origin.ZIP_CODE);

            // destination
            formData.append("address_destination", "jl sultan iskandar muda no 6c");
            formData.append("province_destination", "dki jakarta");
            formData.append("city_destination", "jakarta selatan");
            formData.append("district_destination", "kebayoran lama");
            formData.append("zip_code_destination", "12240");

            // items
            formData.append("weight_items", itemDetail.WEIGHT);
            formData.append("length_items", itemDetail.LENGTH);
            formData.append("width_items", itemDetail.WIDTH);
            formData.append("height_items", itemDetail.HEIGHT);

            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "/nexilis/logics/shipment_api/paxel_shipments_rate");

                xhr.onload = function () {
                    if (this.status >= 200 && this.status < 300) {
                        resolve(JSON.parse(xhr.response));
                    } else {
                        reject({
                            status: this.status,
                            statusText: xhr.statusText
                        });
                    }
                };
                xhr.onerror = function () {
                    reject({
                        status: this.status,
                        statusText: xhr.statusText
                    });
                };

                xhr.send(formData);
            });

        };

    }

}

function checkThis(e) {

    let verif = '<img src="../assets/img/tab5/verified-black.png" alt="verified-icon" class="small-icon">';
    let deliv = document.querySelectorAll('.delivery-options');
    deliv.forEach(d => {
        d.innerHTML = `<span style="height: 15px;width: 15px;background-color: transparent;border: 1px solid lightgray;border-radius: 50%;display: inline-block;"></span>`;
    });

    e.innerHTML = verif;
}


// card payment template
var cardModalHtml =
    '<div id="three-ds-container" style="display: none;">' +
    '   <iframe id="sample-inline-frame" name="sample-inline-frame" width="100%" height="400"> </iframe>' +
    '</div>' +
    '<form id="credit-card-form" name="creditCardForm" method="post">' +
    '<fieldset id="fieldset-card">' +
    '<div class="col p-3">' +
    '  <div class="row">' +
    '    Credit Card Number' +
    '  </div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" required class="form-control" id="credit-card-number" placeholder="e.g: 4000000000000002" name="creditCardNumber">' +
    '  </div>' +
    '  <div class="row mb-4">' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    Month' +
    '  </div>' +
    '      <div class="row">' +
    '        <select required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-month" placeholder="MM" style="border-color: #608CA5" name="creditCardExpMonth">' +
    '          <option>01</option>' +
    '          <option>02</option>' +
    '          <option>03</option>' +
    '          <option>04</option>' +
    '          <option>05</option>' +
    '          <option>06</option>' +
    '          <option>07</option>' +
    '          <option>08</option>' +
    '          <option>09</option>' +
    '          <option>10</option>' +
    '          <option>11</option>' +
    '          <option>12</option>' +
    '        </select>' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-5 mx-1">' +
    '  <div class="row">' +
    '    Year' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="4" size="4" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-year" placeholder="YYYY" style="border-color: #608CA5" name="creditCardExpYear">' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    CVV' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="3" size="3" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-cvv" placeholder="123" style="border-color: #608CA5" name="creditCardCvv">' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '<div class="row">' +
    '  <input class="pay-button" onclick="return toSubmit();" type="submit" id="pay-with-credit-card" value="Pay" name="payWithCreditCard">' +
    '</div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';


// let env = new URLSearchParams(window.location.search).get("env");
// console.log("ENV", env)
let buttonColor = env == "1" ? "#f06270" : "red";

// ovo payment template
var ovoModalHtml =
    '<form id="ovo-form" name="ovoForm" method="post">' +
    '<fieldset id="fieldset-ovo">' +
    '<div class="col p-3">' +
    '  <div class="row">Phone Number</div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" required id="phone-number" placeholder="e.g: +6282111234567" name="phoneNumber">' +
    '  </div>' +
    '  <div class="row">' +
    '       <input style="background-color: '+buttonColor+'" class="pay-button" onclick="return toSubmitOVO();" type="submit" id="pay-with-ovo" value="Pay" name="payWithOVO">' +
    '  </div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';

// dana payment template
var danaModalHtml =
    '<form id="dana-form" name="danaForm" method="post">' +
    '<fieldset id="fieldset-dana">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: '+buttonColor+'" class="pay-button" onclick="return toSubmitDANA();" type="submit" id="pay-with-dana" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithDANA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// linkaja payment template
var linkajaModalHtml =
    '<form id="linkaja-form" name="linkajaForm" method="post">' +
    '<fieldset id="fieldset-linkaja">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: '+buttonColor+'" class="pay-button" onclick="return toSubmitLINKAJA();" type="submit" id="pay-with-linkaja" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithLINKAJA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// shopeepay template
var shopeepayModalHtml =
    '<form id="shopeepay-form" name="shopeepayForm" method="post">' +
    '<fieldset id="fieldset-shopeepay">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: '+buttonColor+'" class="pay-button" onclick="return toSubmitSHOPEE();" type="submit" id="pay-with-shopeepay" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithSHOPEEPAY">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// QRIS template
var qrisModalHtml =
    '<form id="qris-form" name="qrisForm" method="post">' +
    '<fieldset id="fieldset-qris">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <div id="qrcode"></div>' +
    '           <input style="background-color: '+buttonColor+'" class="pay-button" onclick="return toSubmitQRIS();" type="submit" id="pay-with-qris" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Generate QR Code" name="payWithQRIS">' +
    '           <br><button type="button" style="background-color: '+buttonColor+'" class="pay-button mt-3 d-none" id="simulate-qris-payment">Pay QRIS</button>' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// payment with ovo
function toSubmitOVO() {
    event.preventDefault();

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;

    var js = {
        phone_number: $('#phone-number').val(),
        amount: amt,
    };

    // var callbackURL = this.callbackURL;
    // var amount = this.price;

    $.post("../logics/paliobutton/php/paliopay_ovo",
        js,
        function (data, status) {
            try {
                if (data == "SUCCEEDED") {

                    // HIT API
                    // ganti vbot_ sesuai pilihan
                    let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                    digipos_cart.method = "OVO";
                    digipos_cart.last_update = new Date().getTime();

                    vbotAPI(digipos_cart);
                } else {
                    alert("Credit card transaction failed");
                    // showSuccessModal(dictionary.checkout.notice.failed[defaultLang], "OVO");
                }
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
            }
        }
    );

    // alert("Please finish your payment.");
}

// payment with dana
function toSubmitDANA() {
    event.preventDefault();

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;
    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    let env = new URLSearchParams(window.location.search).get('env');
    let origin = new URLSearchParams(window.location.search).get('origin');
    let store_id = new URLSearchParams(window.location.search).get('store_id');

    var js = {
        // callback: this.callbackURL,
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin + "&env=" + env + "&origin=" + origin + "&store_id=" + store_id,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_dana",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url);
                window.location.href = response.actions.desktop_web_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment shopeepay
function toSubmitSHOPEE() {
    event.preventDefault();

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;
    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    let env = new URLSearchParams(window.location.search).get('env');
    let origin = new URLSearchParams(window.location.search).get('origin');
    let store_id = new URLSearchParams(window.location.search).get('store_id');

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin + "&env=" + env + "&origin=" + origin + "&store_id=" + store_id,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_shopee",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url, "_blank");
                // window.open(response.actions.mobile_deeplink_checkout_url);
                window.location.href = response.actions.mobile_deeplink_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

function checkQRISStatus(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/qris_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            console.log('checkqris', responseObj);

            if (responseObj.status == "COMPLETED") {
                // alert(`Payment received!`); // response is the server response

                // HIT API
                // ganti vbot_ sesuai pilihan
                let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                digipos_cart.method = responseObj.payment_detail.source;
                digipos_cart.last_update = new Date().getTime();

                vbotAPI(digipos_cart);

            } else {
                checkQRISStatus(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

function simulateQRISPayment(ext_id) {

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;

    var js = {
        amount: amt,
        external_id: ext_id
    };

    $.post("../logics/paliobutton/php/qris_check",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                let responseObj = JSON.parse(data);
                console.log('simulateqris', responseObj);

                if (responseObj.status == "COMPLETED") {
                    // alert(`Payment received!`); // response is the server response
                    var method = responseObj.payment_details.source ? responseObj.payment_details.source : "TEST_QRIS";

                    // HIT API
                    // ganti vbot_ sesuai pilihan
                    let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                    digipos_cart.method = method;
                    digipos_cart.last_update = new Date().getTime();

                    vbotAPI(digipos_cart);

                } else {
                    checkQRISStatus(id);
                }
            } catch (err) {
                console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

function runSimulateQRIS(ext_id) {
    $('#simulate-qris-payment').off('click');

    $('#simulate-qris-payment').removeClass('d-none');

    $('#simulate-qris-payment').click(function (e) {
        e.preventDefault();

        simulateQRISPayment(ext_id);
    })
}

function toSubmitQRIS() {
    event.preventDefault();

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/payment.php?f_pin=" + getFpin(),
        amount: amt,
    };

    $("#pay-with-qris").prop("disabled", true);
    $("div#qrcode").html("");

    $.post("../logics/paliobutton/php/paliopay_qris",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                console.log(response);

                new QRCode(document.getElementById('qrcode'), response.qr_string);

                runSimulateQRIS(response.external_id);

            } catch (err) {
                console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                $("#pay-with-qris").prop("disabled", false);
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment with linkaja
function toSubmitLINKAJA() {
    event.preventDefault();

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;

    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    let env = new URLSearchParams(window.location.search).get('env');
    let origin = new URLSearchParams(window.location.search).get('origin');
    let store_id = new URLSearchParams(window.location.search).get('store_id');

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin + "&env=" + env + "&origin=" + origin + "&store_id=" + store_id,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_linkaja",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                console.log(response);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url);
                window.location.href = response.actions.desktop_web_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "LINKAJA");
            }
        }
    );
}

// check ewallet payment status
function checkEwallet(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/ewallet_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            console.log(`Error ${xhr.status}: ${xhr.statusText}`);
            $('#modal-payment').modal('toggle');
            $('#modal-payment-status-body').text("Payment failed");
            $('#modal-payment-status').modal('toggle');
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            console.log(responseObj);

            if (responseObj.status == "SUCCEEDED" || responseObj.status == "COMPLETED") {
                // alert(`Payment received!`); // response is the server response
                localStorage.removeItem('ewallet_id');
                if (responseObj.channel_code == "ID_DANA") {
                    var method = "DANA";
                } else if (responseObj.channel_code == "ID_LINKAJA") {
                    var method = "LINKAJA";
                } else if (responseObj.channel_code == "ID_SHOPEEPAY") {
                    var method = "SHOPEEPAY";
                }

                // HIT API
                // ganti vbot_ sesuai pilihan
                let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                digipos_cart.method = method;
                digipos_cart.last_update = new Date().getTime();

                vbotAPI(digipos_cart);

            } else {
                checkEwallet(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

// xendit cc functions
function toSubmit() {
    event.preventDefault();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.setAttribute('disabled', 'disabled');

    // document.getElementById("credit-card-form").classList.add('d-none');

    //dev
    Xendit.setPublishableKey('xnd_public_development_qcfW9OvrvG3U0ph6Dc01xNMhKhhW2On4a0l7ZMUS696BBWR8vNbkSKyRZGlOLQ');
    // //prod
    // // Xendit.setPublishableKey('xnd_public_production_qoec6uRBSVSb4n0WwIijVZgDJevwSZ5xKuxaTRh4YBix0nMSsKgxi226yxtTd7');

    var tokenData = getTokenData();

    console.log(tokenData);

    Xendit.card.createToken(tokenData, xenditResponseHandler);
    // displaySuccess('abc');
}

function postForm(path, params, method) {
    method = method || 'post';

    var form = document.createElement('form');
    form.setAttribute('method', method);
    form.setAttribute('action', path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', key);
            hiddenField.setAttribute('value', params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function xenditResponseHandler(err, creditCardCharge) {
    if (err) {
        console.log(err);
        return displayError(err);
        // console.log(err);
    }

    console.log('cc_charge', creditCardCharge);

    if (creditCardCharge.status === 'APPROVED' || creditCardCharge.status === 'VERIFIED') {
        console.log("success");
        displaySuccess(creditCardCharge);
    } else if (creditCardCharge.status === 'IN_REVIEW') {
        window.open(creditCardCharge.payer_authentication_url, 'sample-inline-frame');
        $('.overlay').show();
        $('#three-ds-container').show();
    } else if (creditCardCharge.status === 'FRAUD') {
        displayError(creditCardCharge);
    } else if (creditCardCharge.status === 'FAILED') {
        displayError(creditCardCharge);
    }
}

function displayError(err) {
    // alert('Request Credit Card Charge Failed');
    $('#modal-payment').modal('toggle');
    $('#modal-payment-status-body').text("Credit card transaction failed");
    $('#modal-payment-status').modal('toggle');
    $('#three-ds-container').hide();
    $('.overlay').hide();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.removeAttribute('disabled');
    // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");
};

function displaySuccess(creditCardCharge) {
    var $form = $('#credit-card-form');
    $('#three-ds-container').hide();
    $('.overlay').hide();

    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;

    var js = {
        token_id: creditCardCharge.id,
        amount: amt,
        cvv: $form.find('#credit-card-cvv').val()
    };

    // if (userAgent) {
    //     var fpin = getFpin();
    // } else {
    //     var fpin = "test";
    // }

    $.post("../logics/paliobutton/php/paliopay",
        js,
        function (data, status) {
            try {
                console.log(data);
                if (data.status == "CAPTURED") {

                    let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                    digipos_cart.method = "CARD";
                    digipos_cart.last_update = new Date().getTime();

                    vbotAPI(digipos_cart);
                } else {
                    // alert("Credit card transaction failed");
                    $('#modal-payment').modal('toggle');
                    $('#modal-payment-status-body').text("Credit card transaction failed");
                    $('#modal-payment-status').modal('toggle');
                    let fieldset = document.getElementById('fieldset-card');
                    fieldset.removeAttribute('disabled');
                }
            } catch (err) {
                console.log(err);
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Credit card transaction failed");
                $('#modal-payment-status').modal('toggle');
                let fieldset = document.getElementById('fieldset-card');
                fieldset.removeAttribute('disabled');
            }
        }, 'json'
    );
}

function getTokenData() {
    var $form = $('#credit-card-form');
    let amt = JSON.parse(localStorage.getItem('digipos_cart')).amount;
    return {
        // amount: $form.find('#credit-card-amount').val(),
        amount: amt,
        card_number: $form.find('#credit-card-number').val(),
        card_exp_month: $form.find('#credit-card-exp-month').val(),
        card_exp_year: $form.find('#credit-card-exp-year').val(),
        card_cvn: $form.find('#credit-card-cvv').val(),
        is_multiple_use: false,
        should_authenticate: true
    };
}

function vbotAPI(digipos_cart) {
    var form_data = new FormData();

    console.log('digipos_cart', digipos_cart);

    for (var key in digipos_cart) {
        form_data.append(key, digipos_cart[key]);
    }

    let url = "";

    if (digipos_cart.command === "PURCHASE") {
        url = "../logics/digipos/vbot_pulsa";
    } else if (digipos_cart.command === "INQUIRY") {
        url = "../logics/digipos/vbot_bill_inquiry";
    } else if (digipos_cart.command === "BILL") {
        url = "../logics/digipos/vbot_bill_pay";
    } else if (digipos_cart.command === "SCHEDULE") {
        url = "../logics/digipos/vbot_schedule";
    }else if(digipos_cart.command === "BOOKING"){
        url = "../logics/digipos/vbot_booking";
    }else if(digipos_cart.command === "CONFIRM"){
        url = "../logics/digipos/vbot_confirmbooking";
    }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            clearCart();
            let f_pin = new URLSearchParams(window.location.search).get('f_pin');
            let env = new URLSearchParams(window.location.search).get('env');
            let origin = new URLSearchParams(window.location.search).get('origin');
            let store_id = new URLSearchParams(window.location.search).get('store_id');

            if (digipos_cart.command == "PURCHASE") {

                window.location.href = 'digipos-success.php?f_pin=' + f_pin + '&env=' + env + '&origin=' + origin + '&store_id=' + store_id;
                
            } else if (digipos_cart.command == "INQUIRY") {

                var result = JSON.parse(xmlHttp.responseText);

                $('#name-purchase').text("Rp " + result.data.customer_name);
                $('#fee-purchase').text("Rp " + result.amount);
                $('#admin-purchase').text("Rp " + result.data.admin_fee);
                $('#price-purchase').text("Rp " + result.total);

                price = result.total;

            } else if (digipos_cart.command == "BILL") {

                window.location.href = 'digipos-success.php?f_pin=' + f_pin + '&env=' + env + '&origin=' + origin + '&store_id=' + store_id;

            } else if (digipos_cart.command == "SCHEDULE") {

                var check = JSON.parse(xmlHttp.responseText);

                if (check.hasOwnProperty('data')){

                    var obj = JSON.parse(xmlHttp.responseText).data.departures;

                    if (obj.length > 0){

                        obj.forEach((item)=>{ 

                            // IF KERETA OR PESAWAT (DIFFERENT JSON)

                            var classes;
                            var seat;
                            var departure_time;
                            var arrival_time;
                            var type;

                            if (digipos_cart.product == "KERETA"){
                                classes = item.sub_class;
                            }else{
                                classes = item.info[0].class;
                            }

                            if (digipos_cart.product == "KERETA"){
                                seat = "Available Seat : "+item.available_seat;
                            }else{
                                seat = "Stopover : "+item.stop;
                            }

                            if (digipos_cart.product == "KERETA"){
                                departure_time = item.departure_time;
                            }else{
                                departure_time = item.departure_date + " " + item.departure_time;
                            }

                            if (digipos_cart.product == "KERETA"){
                                arrival_time = item.arrival_time;
                            }else{
                                arrival_time = item.arrival_date + " " + item.arrival_time;
                            }

                            if (digipos_cart.product == "KERETA"){
                                type = "KERETA";
                            }else{
                                type = "PESAWAT";
                            }

                            var html = `<div class="row gx-0 p-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                                            <div class="col-6">
                                                <p class="mb-0" style="font-size: 18px; padding-bottom: 10px"><b>`+item.name+`</b></p>
                                                <p style="color: var(--NN600,#6D7588)">Class : `+classes+`</p>
                                                <ul style="margin-left: -12px" type="circle">
                                                    <li>`+departure_time+`</li>
                                                </ul>
                                                <ul style="margin-left: -12px">
                                                    <li>`+arrival_time+`</li>
                                                </ul>
                                                <p style="color: var(--NN600,#6D7588)">`+seat+`</p>
                                            </div>
                                            <div class="col-6 pt-3">
                                                <div class="row text-end">
                                                    <p style="color: var(--NN600,#6D7588)" class="mb-0 mt-5 pt-4"><span style="font-weight: 700; color: black; font-size: 16px">Rp. `+item.price_adult+`</span>/orang</p>
                                                </div>
                                                <div class="row mt-3 d-flex justify-content-end">
                                                    <div class="choice-tiket btn text-light w-75" onclick="choiceTiketBerangkat('`+item.schedule_id+`','`+classes+`')">Pesan Tiket</div>
                                                    <button class="btn btn-secondary w-75 mt-2" onclick="berangkatDetail('`+item.schedule_id+`','`+type+`')">Details</button>
                                                </div>
                                            </div>
                                        </div>`;

                            $('#train-schedule-list').append(html);
                            
                            // CEK INI
                            $("#train-schedule").hide();
                            $("#train-data").show();

                        });

                    }else{

                        // alert("Jadwal Berangkat Tidak Tersedia.");
                        $('#validation-text').text('Jadwal Berangkat Tidak Tersedia.');
                        $('#modal-validation').modal('show');

                    }
                }else{

                    // alert("Jadwal Berangkat Tidak Tersedia.");
                    $('#validation-text').text('Jadwal Berangkat Tidak Tersedia.');
                    $('#modal-validation').modal('show');

                }

                if ($('#depart').is(':checked')) {

                    var check = JSON.parse(xmlHttp.responseText);

                    if (check.hasOwnProperty('data')){

                        var obj_back = JSON.parse(xmlHttp.responseText).data.returns;

                        if (obj_back.length > 0){

                            obj_back.forEach((item)=>{

                                // IF KERETA OR PESAWAT (DIFFERENT JSON)

                                var classes;
                                var seat;
                                var departure_time;
                                var arrival_time;
                                var type;

                                if (digipos_cart.product == "KERETA"){
                                    classes = item.sub_class;
                                }else{
                                    classes = item.info[0].class;
                                }
    
                                if (digipos_cart.product == "KERETA"){
                                    seat = "Available Seat : "+item.available_seat;
                                }else{
                                    seat = "Stopover : "+item.stop;
                                }
    
                                if (digipos_cart.product == "KERETA"){
                                    departure_time = item.departure_time;
                                }else{
                                    departure_time = item.departure_date + " " + item.departure_time;
                                }
    
                                if (digipos_cart.product == "KERETA"){
                                    arrival_time = item.arrival_time;
                                }else{
                                    arrival_time = item.arrival_date + " " + item.arrival_time;
                                }

                                if (digipos_cart.product == "KERETA"){
                                    type = "KERETA";
                                }else{
                                    type = "PESAWAT";
                                }

                                var html = `<div class="row gx-0 p-3" style="border-bottom: 1px solid var(--N75,#E5E7E9)">
                                                <div class="col-6">
                                                    <p class="mb-0" style="font-size: 18px; padding-bottom: 10px"><b>`+item.name+`</b></p>
                                                    <p style="color: var(--NN600,#6D7588)">Class : `+classes+`</p>
                                                    <ul style="margin-left: -12px" type="circle">
                                                        <li>`+departure_time+`</li>
                                                    </ul>
                                                    <ul style="margin-left: -12px">
                                                        <li>`+arrival_time+`</li>
                                                    </ul>
                                                    <p style="color: var(--NN600,#6D7588)">`+seat+`</p>
                                                </div>
                                                <div class="col-6 pt-3">
                                                    <div class="row text-end">
                                                        <p style="color: var(--NN600,#6D7588)" class="mb-0 mt-5 pt-4"><span style="font-weight: 700; color: black; font-size: 16px">Rp. `+item.price_adult+`</span>/orang</p>
                                                    </div>
                                                    <div class="row mt-3 d-flex justify-content-end">
                                                        <div class="choice-tiket btn text-light w-75" onclick="choiceTiketTiba('`+item.schedule_id+`','`+classes+`')">Pesan Tiket</div>
                                                        <button class="btn btn-secondary w-75 mt-2" onclick="tibaDetail('`+item.schedule_id+`','`+type+`')">Details</button>
                                                    </div>
                                                </div>
                                            </div>`;

                                $('#train-schedule-list-back').append(html);

                            });

                        }else{

                            // alert("Jadwal Pulang Tidak Tersedia.");
                            $('#validation-text').text('Jadwal Pulang Tidak Tersedia.');
                            $('#modal-validation').modal('show');

                        }

                    }else{

                        // alert("Jadwal Pulang Tidak Tersedia.");
                        $('#validation-text').text('Jadwal Pulang Tidak Tersedia.');
                        $('#modal-validation').modal('show');

                    }
                }

            }else if(digipos_cart.command == "BOOKING"){
            
                var result = JSON.parse(xmlHttp.responseText);

                trx_id = result.trx_id;
                amount_price = result.data.amount;
                admin_fee = result.data.admin_fee;
                total_fee = result.total;

                total_price = total_fee;
                
                $('#name-purchase').text(trx_id);
                $('#fee-purchase').text("Rp "+amount_price);
                $('#admin-purchase').text("Rp "+admin_fee);
                $('#price-purchase').text("Rp "+total_fee);

                $('#section-purchase').show();

            }else if(digipos_cart.command == "CONFIRM"){

                console.log(JSON.parse(xmlHttp.responseText));
            
                window.location.href = 'digipos-success.php';

            }
        }
    }
    xmlHttp.open("post", url);
    xmlHttp.send(form_data);
}

function berangkatDetail(schedule_id,type) {

    $("#modal-details").modal('show');

    var data = {
        "schedule_id" : schedule_id
    }

    var command = "SCHEDULE_DETAILS";
    var product = type;

    var digipos_cart = {
        "command": command,
        "product": product,
        "data": btoa(JSON.stringify(data))
    }

    console.log(digipos_cart);

    var form_data = new FormData();

    for (var key in digipos_cart) {
        form_data.append(key, digipos_cart[key]);
    }

    let url = "../logics/digipos/vbot_schedule";

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            console.log(xmlHttp.responseText);

            // if (type == "KERETA"){
            //     $('#details-station').text("");
            //     $('#details-adult').text("");
            //     $('#details-child').text("");
            //     $('#details-baby').text("");
            //     $('#details-departure').text("");
            //     $('#details-arrival').text("");
            //     $('#details-class').text("");
            //     $('#details-seat').text("");
            // }else{
            //     $('#details-station').text("");
            //     $('#details-adult').text("");
            //     $('#details-child').text("");
            //     $('#details-baby').text("");
            //     $('#details-departure').text("");
            //     $('#details-arrival').text("");

            //     $('#details-start').text("");
            //     $('#details-end').text("");
            //     $('#details-departure-sub').text("");
            //     $('#details-arrival-sub').text("");
            //     $('#details-duration').text("");
            //     $('#details-class').text("");
            //     $('#details-flight-number').text("");
            //     $('#details-luggage').text("");
            //     $('#details-transit').text("");
            // }

        }
    }
    xmlHttp.open("post", url);
    xmlHttp.send(form_data);

}

function tibaDetail(schedule_id,type) {

    $("#modal-details").modal('show');

    var data = {
        "schedule_id" : schedule_id
    }

    var command = "SCHEDULE_DETAILS";
    var product = type;

    var digipos_cart = {
        "command": command,
        "product": product,
        "data": btoa(JSON.stringify(data))
    }

    console.log(digipos_cart);

    var form_data = new FormData();

    for (var key in digipos_cart) {
        form_data.append(key, digipos_cart[key]);
    }

    let url = "../logics/digipos/vbot_schedule";

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            console.log(xmlHttp.responseText);

            // if (type == "KERETA"){
            //     $('#details-station').text("");
            //     $('#details-adult').text("");
            //     $('#details-child').text("");
            //     $('#details-baby').text("");
            //     $('#details-departure').text("");
            //     $('#details-arrival').text("");
            //     $('#details-class').text("");
            //     $('#details-seat').text("");
            // }else{
            //     $('#details-station').text("");
            //     $('#details-adult').text("");
            //     $('#details-child').text("");
            //     $('#details-baby').text("");
            //     $('#details-departure').text("");
            //     $('#details-arrival').text("");

            //     $('#details-start').text("");
            //     $('#details-end').text("");
            //     $('#details-departure-sub').text("");
            //     $('#details-arrival-sub').text("");
            //     $('#details-duration').text("");
            //     $('#details-class').text("");
            //     $('#details-flight-number').text("");
            //     $('#details-luggage').text("");
            //     $('#details-transit').text("");
            // }

        }
    }
    xmlHttp.open("post", url);
    xmlHttp.send(form_data);

}

function checkStock() {
    let item_temp = cart.map(merchant => merchant.items);
    let items = [].concat(...item_temp);
    let mapped = items.map(({
        itemCode
    }) => itemCode)

    // console.log(items);

    let formData = new FormData();
    formData.append('items', btoa(JSON.stringify(mapped)));

    // return new Promise(function (resolve, reject) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let stock = JSON.parse(xmlHttp.responseText);
            console.log(stock);

            let stockValid = true;

            let item_name = '';

            items.forEach(it => {
                let getInStock = stock.find(st => st.CODE == it.itemCode);
                if (it.itemQuantity > getInStock.QUANTITY) {
                    console.log('qty > stock!');
                    item_name = getInStock.NAME;
                    stockValid = false;
                    $('#modal-warning-stock .modal-body').html("The item '" + item_name + "' is no longer in stock. If you wish to proceed with the purchase, please remove it from your cart.");
                }
            })

            if (stockValid) {
                palioPay();
            } else {
                $('#modal-warning-stock').modal('toggle');
                console.log('stock valid?', stockValid);
            }
            // resolve(stockValid);
        }
    }
    xmlHttp.open("post", "../logics/check_stock");
    xmlHttp.send(formData);
    // });
}
// summmon payment modal
async function palioPay() {
    this.myModal = new SimpleModal();

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

"use strict";

var payment_method = "";

// payment modal
class SimpleModal {

    constructor(modalTitle) {
        this.modalTitle = "title";
        this.parent = document.body;

        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = "";

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            this.closeButton.addEventListener("click", () => {
                resolve(null);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");

        // let payment_method = document.getElementById('dropdownMenuSelectMethod').innerHTML;
        // let payment_method = localStorage.getItem('payment-method');
        console.log('method', payment_method);
        if (payment_method.includes("CARD")) {
            text.innerHTML = cardModalHtml;
        } else if (payment_method.includes("OVO")) {
            text.innerHTML = ovoModalHtml;
        } else if (payment_method.includes("DANA")) {
            text.innerHTML = danaModalHtml;
        } else if (payment_method.includes("LINKAJA")) {
            text.innerHTML = linkajaModalHtml;
        } else if (payment_method.includes("SHOPEEPAY")) {
            text.innerHTML = shopeepayModalHtml;
        } else if (payment_method.includes("QRIS")) {
            text.innerHTML = qrisModalHtml;
        } else {
            text.innerHTML = cardModalHtml;
        }

        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// delete modal
class ConfirmModal {

    constructor(merchant_name, product_name) {

        if (localStorage.lang == 0) {
            this.html =
                '<form method="post">' +
                '<fieldset>' +
                '   <div class="col p-3">' +
                '       <div class="row">' +
                `           Delete this item from your cart?` +
                '       </div>' +
                '       <div class="row">' +
                `           <button id="confirm-delete" class="col-md-12 py-1 px-3 m-0 my-1 fs-16">Delete</button>` +
                '       </div>' +
                '   </div>' +
                '</fieldset>' +
                '</form>';
        } else if (localStorage.lang == 1) {
            this.html =
                '<form method="post">' +
                '<fieldset>' +
                '   <div class="col p-3">' +
                '       <div class="row">' +
                `           Hapus barang ini dari keranjang?` +
                '       </div>' +
                '       <div class="row">' +
                `           <button id="confirm-delete" class="col-md-12 py-1 px-3 m-0 my-1 fs-16">Hapus</button>` +
                '       </div>' +
                '   </div>' +
                '</fieldset>' +
                '</form>';
        }

        this.parent = document.body;
        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    question() {
        this.delete_button = document.getElementById('confirm-delete');

        return new Promise((resolve, reject) => {
            this.delete_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(true);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.innerHTML = this.html;
        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        $('#modal-payment').modal('hide');
    }
}

// input promo code modal
class PromoModal {

    constructor() {

        let str = "Insert Promo Code";
        let str_apply = "Apply";

        if (localStorage.lang == 1) {
            str = "Masukkan kode promo";
            str_apply = "Aplikasikan";
        }

        this.html =
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3">' +
            '       <div class="row font-semibold">' +
            str +
            '       </div>' +
            '       <div class="row d-flex align-items-center justify-content-end">' +
            `           <input type="text" id="input-promo" class="position-relative py-3 px-3 m-0 my-1 fs-16" style="border: 1px solid lightgrey;">` +
            `           <span id="confirm-promo" class="position-absolute font-semibold  py-1 px-3 m-0 my-1 fs-16" style="width: auto; background-color: transparent; color: black;">` + str_apply + `</span>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    question() {
        this.delete_button = document.getElementById('confirm-promo');

        return new Promise((resolve, reject) => {
            this.delete_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(document.getElementById('input-promo').value);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.innerHTML = this.html;
        window.appendChild(text);

        // Let's rock
        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        $('#modal-payment').modal('hide');
    }
}


async function enterPromoCode() {
    let confirmationModal = new PromoModal();
    let response = await confirmationModal.question();

    let default_text = localStorage.lang == 1 ? "Masukkan kode promo >" : "Enter promo code >";

    console.log(response);

    if (response.trim() !== '') {
        document.getElementById('promo-code').innerHTML = `<b>${response}</b>`;
    } else {
        document.getElementById('promo-code').innerHTML = `${default_text}`;
    }
}

function pullRefresh() {
    if (window.Android && $('#your-cart').scrollTop() == 0) {
        window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
    }
}

$(function () {
    $('#your-cart').scroll(function () {
        console.log($(this).scrollTop());
    })
    if (localStorage.getItem('ewallet_id') !== null) {
        checkEwallet(localStorage.getItem('ewallet_id'));
    }
    pullRefresh();
})

// change/get address modal
function deliveryAddress(address = '') {
    let formData = new FormData();
    formData.append("fpin", getFpin());
    formData.append("address", address);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/nexilis/logics/get_delivery_address");

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };

        xhr.send(formData);
    });
}

class AddressModal {

    constructor(address) {
        this.delivery_address = address;

        this.html =
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3">' +
            '       <div class="row">' +
            `           Insert delivery address` +
            '       </div>' +
            '       <div class="row">' +
            `           <input type="text" id="input-address" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-1 fs-16" value='${this.delivery_address}'>` +
            `           <button id="confirm-address" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-1 fs-16">OK</button>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-address-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    static async build() {
        let address = await deliveryAddress();
        return new AddressModal(address);
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "address-form");
        text.innerHTML = this.html;
        window.appendChild(text);

        this.delete_button = document.getElementById('confirm-address');
        this.delete_button.addEventListener("click", () => {
            let new_delivery_address = document.getElementById('input-address').value;
            event.preventDefault();
            deliveryAddress(new_delivery_address);
            document.getElementById('delivery-address').innerHTML = new_delivery_address;
            this._destroyModal();
        });

        // Let's rock
        $('#modal-address').modal('show');
    }

    _destroyModal() {
        $('#modal-address').modal('hide');
    }
}

async function changeDeliveryAddress() {
    // let response = await AddressModal.build();
    window.open('/nexilis/pages/tab5-change-address', '_self')
}

function pauseVideos() {
    $('video').each(function () {
        $(this).get(0).pause();
    });
}

