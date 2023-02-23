// declare empty cart if localstorage is not set yet
if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
} else {
    var cart = [];
}

function saveToLocalStorage(product, quantity = 1) {
    
    console.log("CONTENT", product);

    if (localStorage.getItem("cart") != null) {
        var cart = JSON.parse(localStorage.getItem("cart"));
    } else {
        var cart = [];
    }

    // items
    var item_details = {};
    item_details.store_id = product.SHOP_CODE;
    item_details.itemName = product.PRODUCT_NAME;
    item_details.thumbnail = product.THUMB_ID.split('|')[0];
    item_details.itemPrice = product.PRICE;
    item_details.itemCode = product.CODE;
    item_details.itemQuantity = quantity;
    item_details.maxQty = product.QUANTITY;
    item_details.selected = 'checked';

    var merchant = cart.find(el => el.merchant_name == product.SHOP_NAME);

    // merchant
    if (merchant != undefined) { // check if the merchant already in the json
        var item = merchant.items.find(el => el.itemName == product.PRODUCT_NAME);

        if (item != undefined) {
            // item.itemQuantity = parseInt(item.itemQuantity) + parseInt(item_details.itemQuantity);
            let currentQty = item.itemQuantity;

            if (item.itemQuantity + item_details.itemQuantity < item.maxQty) {
                item.itemQuantity = currentQty + parseInt(item_details.itemQuantity);
                if (localStorage.lang == 0) {

                    $('#addtocart-success .add-to-cart-text').html('Product added successfully');
                } else {

                    $('#addtocart-success .add-to-cart-text').html('Produk berhasil ditambahkan');
                }
                $('#addtocart-success').modal('show');
            } else {
                if (localStorage.lang == 0) {

                    $('#addtocart-success .add-to-cart-text').html('Stock limit reached!');
                } else {

                    $('#addtocart-success .add-to-cart-text').html('Jumlah barang sudah maksimum!');
                }
                $('#addtocart-success').modal('show');
            }
        } else {
            merchant.items.push(item_details);
            if (localStorage.lang == 0) {

                $('#addtocart-success .add-to-cart-text').html('Product added successfully');
            } else {

                $('#addtocart-success .add-to-cart-text').html('Produk berhasil ditambahkan');
            }
            $('#addtocart-success').modal('show');
        }

    } else {
        var new_merchant = {};
        new_merchant.merchant_name = product.SHOP_NAME;
        new_merchant.official_acc = product.OFFICIAL_ACCOUNT;
        new_merchant.items = [];
        new_merchant.items.push(item_details);

        cart.push(new_merchant);
        if (localStorage.lang == 0) {

            $('#addtocart-success .add-to-cart-text').html('Product added successfully');
        } else {

            $('#addtocart-success .add-to-cart-text').html('Produk berhasil ditambahkan');
        }
        $('#addtocart-success').modal('show');
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    console.log('added successfully');

    // if ($('#modal-addtocart').length > 0) {
    //     $('#modal-addtocart').modal('hide');
    // }
    // if ($('#addtocart-success').length > 0) {
    //     $('#addtocart-success').modal('show');
    // }
};

function addToCart(item, quantity, checkIOS=false) {
    event.preventDefault();

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: '"' + item + '", ' + quantity + '"',
            param2: 'addtocart'
        });
        return;

    }

    //Login-form input values
    let formData = new FormData();
    formData.append("product_id", item);

    // 1. Create a new XMLHttpRequest object
    if (quantity == 0) {
        alert('Please set the quantity!');
    } else {
        let xhr = new XMLHttpRequest();

        // 2. Configure it: GET-request for the URL /article/.../load
        xhr.open('POST', '/nexilis/logics/get_product_data');

        // 3. Send the request over the network
        xhr.send(formData);

        // 4. This will be called after the response is received
        xhr.onload = async function () {

            //Request error
            if (xhr.status != 200) { // analyze HTTP status of the response

                alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

                //Request success
            } else { // show the result

                // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
                let response = JSON.parse(xhr.response);
                saveToLocalStorage(response, quantity);
                if ($('#modal-addtocart').length > 0) {
                    $('#modal-addtocart').modal('hide');
                }
                if ($('#addtocart-success').length > 0) {
                    $('#addtocart-success').modal('show');
                }
            }
        };
    }
};
