// card payment template
var cardModalHtml =
    '<div class="overlay" style="display: none;"></div>' +
    '<div id="three-ds-container" style="display: none;">' +
    '   <iframe id="sample-inline-frame" name="sample-inline-frame" width="100%" height="400"> </iframe>' +
    '</div>' +
    '<form id="credit-card-form" name="creditCardForm" method="post" style="max-height: 300px; overflow-y: auto;">' +
    '  <div class="input-group btn border-70 p-0 mt-4">' +
    '    <input maxlength="16" size="16" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-number" placeholder="Nomor Kartu Kredit (Contoh: 4000000000000002)" name="creditCardNumber">' +
    '  </div>' +
    '  <div class="row input-group btn border-70 p-0 mt-4" style="text-align: left">' +
    '    <div class="col-sm-3">' +
    '      <p>Bulan Kadaluarsa</p>' +
    '      <div class="input-group btn border-70 p-0 mt-4">' +
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
    '    <div class="col-sm-6">' +
    '      <p>Tahun Kadaluarsa</p>' +
    '      <div class="input-group btn border-70 p-0 mt-4">' +
    '        <input maxlength="4" size="4" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-year" placeholder="TTTT" style="border-color: #608CA5" name="creditCardExpYear">' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-sm-3">' +
    '      <p>Kode CVV</p>' +
    '      <div class="input-group btn border-70 p-0 mt-4">' +
    '        <input maxlength="3" size="3" type="text" required class="form-control form-control fs-16 fontRobReg" id="credit-card-cvv" placeholder="123" style="border-color: #608CA5" name="creditCardCvv">' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '  <input onclick="return toSubmit();" type="submit" id="pay-with-credit-card" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="' + dictionary.checkout.buttons[defaultLang] + '" name="payWithCreditCard">' +
    '</form>';

// ovo payment template
var ovoModalHtml =
    '<form id="ovo-form" name="ovoForm" method="post">' +
    '  <div class="input-group btn border-70 p-0 mt-4">' +
    '    <input maxlength="16" size="16" type="text" required class="form-control form-control fs-16 fontRobReg" id="phone-number" placeholder="Nomor Telepon (Contoh: +6282111234567)" name="phoneNumber">' +
    '  </div>' +
    '  <input onclick="return toSubmitOVO();" type="submit" id="pay-with-ovo" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="' + dictionary.checkout.buttons[defaultLang] + '" name="payWithOVO">' +
    '</form>';

// dana payment template
var danaModalHtml =
    '<form id="dana-form" name="danaForm" method="post">' +
    '  <input onclick="return toSubmitDANA();" type="submit" id="pay-with-dana" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="' + dictionary.checkout.buttons[defaultLang] + '" name="payWithDANA">' +
    '</form>';

// linkaja payment template
var linkajaModalHtml =
    '<form id="linkaja-form" name="linkajaForm" method="post">' +
    '  <input onclick="return toSubmitLINKAJA();" type="submit" id="pay-with-linkaja" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="' + dictionary.checkout.buttons[defaultLang] + '" name="payWithLINKAJA">' +
    '</form>';

// shipping info
var shippingModalHtml =
    '<form id="shipping-form" name="shipping" method="post" style="max-height: 300px; overflow-y: auto;">' +
    '   <div class="col-sm-12">' +
    '       <span>Nama</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="John Doe" style="border-color: #608CA5" name="shipping_name">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Nomor Telepon</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input size="3" type="number" required class="form-control form-control fs-16 fontRobReg" placeholder="082123456789" style="border-color: #608CA5" name="shipping_phone">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Alamat</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="Jl. Sultan Iskandar Muda No.6C" style="border-color: #608CA5" name="shipping_address">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Catatan</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="Lantai 6 divisi IT" style="border-color: #608CA5" name="shipping_notes">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Provinsi</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="DKI Jakarta" style="border-color: #608CA5" name="shipping_province">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Kota</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input size="3" type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="Jakarta Selatan" style="border-color: #608CA5" name="shipping_city">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Kecamatan</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input size="3" type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="Kebayoran Lama" style="border-color: #608CA5" name="shipping_district">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Desa</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="Kebayoran Lama" style="border-color: #608CA5" name="shipping_village">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Kode Pos</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="number" required class="form-control form-control fs-16 fontRobReg" placeholder="12240" style="border-color: #608CA5" name="shipping_zipcode">' +
    '       </div>' +
    '   </div>' +
    '</form>';


// artist request form
var artistModalHtml =
    '<form id="artist-form" name="artist" method="post" style="max-height: 300px; overflow-y: auto;">' +
    '   <div class="col-sm-12">' +
    '       <span>Tanggal</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="date" required class="form-control form-control fs-16 fontRobReg" style="border-color: #608CA5" name="tanggal">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12">' +
    '       <span>Waktu</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="time" required class="form-control form-control fs-16 fontRobReg" style="border-color: #608CA5" name="waktu">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Tema Perform</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input size="3" type="number" required class="form-control form-control fs-16 fontRobReg" placeholder="Ucapan Ulang Tahun" style="border-color: #608CA5" name="tema">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Target Perform</span>' +
    '       <div class="input-group btn border-70 p-0">' +
    '           <input type="text" required class="form-control form-control fs-16 fontRobReg" placeholder="John Doe" style="border-color: #608CA5" name="target">' +
    '       </div>' +
    '   </div>' +
    '   <div class="col-sm-12 mt-2">' +
    '       <span>Notifikasi</span>' +
    '       <div class="input-group btn border-70 p-0 mt-2">' +
    '           <input type="radio" id="ya" name="notifikasi" value="Ya" style="border-color: #608CA5" checked>' +
    '           <label for="ya">Ya</label><br>' +
    '           <input type="radio" id="tidak" name="notifikasi" value="Tidak" style="margin-left: 20px;border-color: #608CA5">' +
    '           <label for="tidak">Tidak</label><br>' +
    '       </div>' +
    '   </div>' +
    '</form>';

var userAgent = /PalioBrowser/.test(navigator.userAgent);

// declare empty cart if localstorage is not set yet
if (localStorage.getItem("cart") != null) {
    var cart = JSON.parse(localStorage.getItem("cart"));
} else {
    var cart = [];
}

// to disable pull to refresh on android
function pullRefresh() {
    if (window.Android && $(window).scrollTop() == 0) {
        window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
    }
}

// clear the shopping cart
async function clearCart(method) {
    if (method == "checked_only") {
        cart = selectedItems("unchecked");
        if (cart.length == 0) {
            localStorage.removeItem("cart");
        } else {
            localStorage.setItem("cart", JSON.stringify(cart));
        }
        updateCounter();
        shippingRate();
        return true;
    }

    let confirmationModal = new SuccessModal(dictionary.cart.notice.clearCart[defaultLang], "confirmation");
    let response = await confirmationModal.question();

    if (response != true) {
        return false;
    } else {

        cart = [];
        localStorage.removeItem("cart");
        updateCounter();
        shippingRate();
        return true;
    }
    // alert("Your cart is now empty!");
}

// to get only the selected item on cart
function selectedItems(checked) {
    let new_cart = [];
    cart.forEach(merchant => {
        let new_object = {}; //objek merchant
        merchant.items.forEach(item => {
            if (item.checked == checked) {
                new_object.merchant_name = merchant.merchant_name;
                if (new_object.items == undefined) { new_object.items = [] }
                new_object.items.push(item);
            }
        })
        if (Object.entries(new_object).length !== 0) { new_cart.push(new_object) }
    })

    return new_cart;
}

function updateCounter() {
    let counter = 0;
    cart.forEach(item => {
        item.items.forEach(item => {
            counter += item.itemQuantity;
        })
    })

    if (counter == 0) {
        document.getElementById("cart-badge").classList.add('d-none');
    } else {
        document.getElementById("cart-badge").classList.remove('d-none');
    }

    if (counter > 99) {
        document.getElementById("cart-badge").innerText = "99+";
    } else {
        document.getElementById("cart-badge").innerText = counter;
    }

    try {
        if (window.Android) {
            window.Android.updateCounter(counter);
        }
    } catch (err) {
        console.log(err);
    }
}

// to update cummulative price in cart
function updateCummulativePrice() {
    document.getElementById("cummulative-price").innerHTML = "Rp" + numberWithDots(countPrice());
}

// prevent none item selected in cart
function minimalOne() {

    for (var i = 0; i < cart.length; i++) {
        for (var j = 0; j < cart[i].items.length; j++) {
            if (cart[i].items[j].checked == "checked") {
                return true;
            }
        }
    }

    return false;

}

// cart checkbox
function checkUncheck(merchant_name, item_name) {

    let status = cart.find(e => e.merchant_name == merchant_name).items.find(e => e.itemName == item_name).checked == "checked" ? "unchecked" : "checked";
    cart.find(e => e.merchant_name == merchant_name).items.find(e => e.itemName == item_name).checked = status;

    // if (!minimalOne()) {
    //     let status = cart.find(e => e.merchant_name == merchant_name).items.find(e => e.itemName == item_name).checked == "checked" ? "unchecked" : "checked";
    //     cart.find(e => e.merchant_name == merchant_name).items.find(e => e.itemName == item_name).checked = status;

    //     showSuccessModal(dictionary.cart.notice.minimalOneSelected[defaultLang], "");

    //     return;
    // }
    // console.log(cart);

    localStorage.setItem("cart", JSON.stringify(cart));
    updateRewardInfo(merchant_name);
    shippingRate();
    updateCummulativePrice();

}

function updateRewardInfo(merchant_name) {
    let merchantTotalReward = countRewardPts(merchant_name);
    if (merchantTotalReward == 0) {
        document.getElementById("listitems-" + merchant_name).innerHTML = "";
    } else {
        document.getElementById("listitems-" + merchant_name).innerHTML = "Kamu bisa dapat reward <b>" + merchantTotalReward + " poin</b> dari <b>" + merchant_name + "</b>";
    }
}

// change value of input number
function changeValue(id, method, merchant_name, item_name) {
    if (method == "add") {
        document.getElementById(id).value = Number(document.getElementById(id).value) + 1;
    } else if (method == "sub") {
        document.getElementById(id).value = Number(document.getElementById(id).value) - 1;
    }

    //check the input value
    let inputBoxValue = document.getElementById(id).value;
    if (inputBoxValue < 1) {
        showSuccessModal(dictionary.addItem.notice.isBelowOne[defaultLang], "updatecart");
        document.getElementById(id).value = 1;
        return;
    }

    cart.forEach(item => {
        if (item.merchant_name == merchant_name) {
            item.items.forEach(item => {
                if (item.itemName == item_name) {
                    if (method == "add") {
                        item.itemQuantity += 1;
                    } else {
                        item.itemQuantity -= 1;
                    }
                    localStorage.setItem("cart", JSON.stringify(cart));
                    updateCounter();
                    shippingRate();
                    updateCummulativePrice();
                    updateRewardInfo(merchant_name);
                }
            })
        }
    })
}

// format number with dots. eg. 1000 = 1.000
function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// validate form input
function validateForm(formId) {

    var elements = document.getElementById(formId);

    for (var i = 0; i < elements.length; i++) {
        if (elements[i].value == "") {
            return false;
        }
    }

    return true;
}

// save form input
function saveForm(formId) {

    var elements = document.getElementById(formId);
    var input_json = {};

    for (var i = 0; i < elements.length; i++) {
        input_json[elements[i].name] = elements[i].value;
    }

    localStorage.setItem(formId, JSON.stringify(input_json));
}

// save form input
function saveXenditResponse(response) {

    let res = JSON.stringify(response);
    localStorage.setItem("xendit-response", res);
}

// fill form
function fillForm(formId) {

    let formData = JSON.parse(localStorage.getItem(formId))
    var form = document.getElementById(formId);

    for (var i = 0; i < form.length; i++) {
        document.getElementById(formId).elements[form[i].name].value = formData[form[i].name];
    }

}

let rewardPts = [];
// let fpin_lokal = "02b3c7f2db";
let fpin_lokal = window.Android.getFPin();

function fetchRewardPoints() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            rewardPts = JSON.parse(xmlHttp.responseText);
            // console.log(rewardPts);
        }
    };

    xmlHttp.open("GET", "/nexilis/logics/fetch_stores_reward_user_raw.php?f_pin=" + fpin_lokal);
    xmlHttp.send();
}
fetchRewardPoints();

// find store code by merchant name
function countDiscount(merchant_name) {
    let total_point = rewardPts.find(el => el.NAME == merchant_name).AMOUNT;
    // let total_discount = total_point * getDiscountRate(merchant_name);
    let total_discount = total_point * 100;
    return total_discount;
}

// get discount rate per merchant from rewardPts array
// function getDiscountRate(merchant_name){
//     let discount_rate = rewardPts.find(el => el.NAME == merchant_name).RATE;
//     return discount_rate;
// }

// count total price
function countPrice() {
    let totalPrice = 0;
    // let totalDiscount = 0;

    cart.forEach(cartitem => {
        cartitem.items.forEach(item => {
            if (item.checked == "checked") {
                totalPrice += item.itemQuantity * item.itemPrice;
            }
        })

        // cummulative discount from each merchants
        // totalDiscount += countDiscount(cartitem.merchant_name);
    })

    return totalPrice;
}

// redirect with post
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

// get detail of an item (weight, height, length, etc)
function getItemDetail(item_name){
    // let formData = new FormData();
    // formData.append("item_name", item_name);

    // let response;

    // var xhr = new XMLHttpRequest();

    // xhr.addEventListener("readystatechange", function () {
    //     if (this.readyState === 4) {
    //         response = JSON.parse(this.responseText);
    //     }
    // });

    // xhr.open("POST", "/nexilis/logics/fetch_item_details.php", false);
    // xhr.send(formData);

    // return response;

    let dummy = JSON.parse('{"ID":1,"PRODUCT_CODE":"1","LENGTH":12,"WIDTH":13,"HEIGHT":14,"IS_FRAGILE":0,"WEIGHT":1,"CATEGORY":"Snack"}');
    return dummy;
}

// get shipping address of a merchant
function getMerchantAddress(merchant_name){
    // let formData = new FormData();
    // formData.append("merchant_name", merchant_name);

    // let response;

    // var xhr = new XMLHttpRequest();

    // xhr.addEventListener("readystatechange", function () {
    //     if (this.readyState === 4) {
    //         response = JSON.parse(this.responseText);
    //     }
    // });

    // xhr.open("POST", "/nexilis/logics/fetch_merchant_address.php", false);
    // xhr.send(formData);

    // return response;

    let dummy = JSON.parse('{"ID":1,"STORE_CODE":"1","ADDRESS":"Jl. Sultan Iskandar Muda No.6C","VILLAGE":"Kebayoran Lama","DISTRICT":"Kebayoran Lama","CITY":"Jakarta Selatan","PROVINCE":"DKI Jakarta","ZIP_CODE":"12240","PHONE_NUMBER":"081987654321","NOTE":"Lantai 6 divisi IT"}')
    return dummy;
}

// get shipment rate and update cummulative price
async function shippingRate() {

    let formData = new FormData();
    let rate = 0;

    if (localStorage.getItem("shipping-form") == undefined) {
        document.getElementById("cummulative-price").innerHTML = "Isi alamat";
    } else {
        let merchantCount = selectedItems("checked").length;
        if (merchantCount == 0) {
            // no item selected
            document.getElementById("shipment-cost").innerHTML = "Rp0";
            let tot = countPrice() + 0;
            document.getElementById("cummulative-price").innerHTML = "Rp" + numberWithDots(tot);
        }
        for (let i = 0; i < merchantCount; i++) {
    
            // get merchant address
            let origin = await getMerchantAddress(selectedItems("checked")[i].merchant_name);
            let items = selectedItems("checked")[0].items;
    
            for (let item of items) {
    
                let itemDetail = await getItemDetail(item.itemName);
    
                // origin
                formData.append("address_origin", origin.ADDRESS);
                formData.append("province_origin", origin.PROVINCE);
                formData.append("city_origin", origin.CITY);
                formData.append("district_origin", origin.DISTRICT);
                formData.append("zip_code_origin", origin.ZIP_CODE);
    
                let destination = JSON.parse(localStorage.getItem("shipping-form"));
    
                // destination
                formData.append("address_destination", destination.shipping_address);
                formData.append("province_destination", destination.shipping_province);
                formData.append("city_destination", destination.shipping_city);
                formData.append("district_destination", destination.shipping_district);
                formData.append("zip_code_destination", destination.shipping_zipcode);
    
                // items
                formData.append("weight_items", itemDetail.WEIGHT);
                formData.append("length_items", itemDetail.LENGTH);
                formData.append("width_items", itemDetail.WIDTH);
                formData.append("height_items", itemDetail.HEIGHT);
    
                var xhr = new XMLHttpRequest();
    
                xhr.addEventListener("readystatechange", function () {
                    if (this.readyState === 4) {
                        // console.log(this.responseText);
                        // JSON.parse(responseText)
                        // return responseText.data.fixed_price
                        // return 100000;
                        // rate += 1;
    
                        let response = JSON.parse(this.responseText);
                        rate += response.data.fixed_price;
                    }
                });
    
                xhr.open("POST", "/nexilis/logics/shipment_api/paxel_shipments_rate");
                xhr.send(formData);
    
                xhr.addEventListener('loadend', function () {
                    localStorage.setItem("shipping-rate", rate);
    
                    // change total value in cart
                    let newTotal = countPrice() + rate;
                    document.getElementById("cummulative-price").innerHTML = "Rp" + numberWithDots(newTotal);
                    document.getElementById("shipment-cost").innerHTML = "Rp" + numberWithDots(rate);
                });
                
            };
    
        }
    }
}

// track shipment status
function trackShipment(airway_bill){
    let formData = new FormData();
    formData.append("airway_bill", airway_bill);

    let response;

    var xhr = new XMLHttpRequest();

    xhr.addEventListener("readystatechange", function () {
        if (this.readyState === 4) {
            // console.log(this.responseText);
            response = JSON.parse(this.responseText);
        }
    });

    xhr.open("POST", "/nexilis/logics/shipment_api/paxel_track_shipment_status", false);
    xhr.send(formData);

    return response;
}

// check ewallet payment status
function checkEwallet(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', 'http://202.158.33.26/nexilis/logics/ewallet_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check.php?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            // console.log(responseObj);

            if (responseObj.status == "SUCCEEDED") {
                // alert(`Payment received!`); // response is the server response
                // postForm("http://192.168.0.56/nexilis/logics/store_payment", { fpin: fpin, method: responseObj.channel_code, status: "success", items: items });
                if (responseObj.channel_code == "ID_DANA") {
                    var method = "DANA";
                } else if (responseObj.channel_code == "ID_LINKAJA") {
                    var method = "LINKAJA";
                }
                saveXenditResponse(responseObj);
                updateRewardPts();
                let response = await createShipment(responseObj);
                if(response != "error"){
                    showSuccessModal(dictionary.checkout.notice.success[defaultLang], method);
                } else {
                    showSuccessModal("Error");
                }

            } else {
                checkEwallet(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    // xhr.onprogress = function (event) {
    //     if (event.lengthComputable) {
    //         alert(`Received ${event.loaded} of ${event.total} bytes`);
    //     } else {
    //         alert(`Received ${event.loaded} bytes`); // no Content-Length
    //     }

    // };

    xhr.onerror = function () {
        // alert("Request failed");
        showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

// store awb
function storeAWB(awb_code, shipping_cost, est_pickup, est_arrival, created_datetime, merchant_name){

    let formData = new FormData();

    formData.append("awb_code", awb_code);
    formData.append("shipping_cost", shipping_cost);
    formData.append("est_pickup", est_pickup);
    formData.append("est_arrival", est_arrival);
    formData.append("created_datetime", created_datetime);
    formData.append("fpin", fpin_lokal);
    formData.append("merchant_name", merchant_name);

    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('POST', '/nexilis/logics/shipment_api/paxel_store_awb');

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send(formData);

    // 4. This will be called after the response is received
    xhr.onload = async function () {

        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            console.log('error');

        } else { // show the result
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
            console.log('awb stored');

        }

    };

}

// store awb
function getOrders() {

    let formData = new FormData();
    formData.append("fpin", fpin_lokal);

    let response;

    var xhr = new XMLHttpRequest();

    xhr.addEventListener("readystatechange", function () {
        if (this.readyState === 4) {
            try {
                response = JSON.parse(this.responseText);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                response = []
            }
        }
    });

    xhr.open("POST", "/nexilis/logics/shipment_api/paxel_get_orders", false);
    xhr.send(formData);

    return response;

}

// shipment process
function createShipment(xendit) {
    let merchants = selectedItems("checked");
    let airway_bills = [];
    for (let merchant of merchants) {

        let formData = new FormData();

        formData.append("invoice_number", xendit.reference_id);
        formData.append("payment_type", xendit.channel_code);
        formData.append("invoice_value", xendit.charge_amount);

        merchant_address = getMerchantAddress(merchant.merchant_name);

        formData.append("name_origin", merchant.merchant_name);
        formData.append("phone_origin", merchant_address.PHONE_NUMBER);
        formData.append("address_origin", merchant_address.ADDRESS);
        formData.append("note_origin", merchant_address.NOTE);
        formData.append("province_origin", merchant_address.PROVINCE);
        formData.append("city_origin", merchant_address.CITY);
        formData.append("district_origin", merchant_address.DISTRICT);
        formData.append("village_origin", merchant_address.VILLAGE);
        formData.append("zip_code_origin", merchant_address.ZIP_CODE);

        destination_address = JSON.parse(localStorage.getItem("shipping-form"));

        formData.append("name_destination", destination_address.shipping_name);
        formData.append("phone_destination", destination_address.shipping_phone);
        formData.append("address_destination", destination_address.shipping_address);
        formData.append("note_destination", destination_address.shipping_notes);
        formData.append("province_destination", destination_address.shipping_province);
        formData.append("city_destination", destination_address.shipping_city);
        formData.append("district_destination", destination_address.shipping_district);
        formData.append("village_destination", destination_address.shipping_village);
        formData.append("zip_code_destination", destination_address.shipping_zipcode);

        let items = []
        for(let item of merchant.items){
            single_item = {};
            item_detail = getItemDetail(item.itemName);

            single_item.code = item_detail.PRODUCT_CODE;
            single_item.name = item.itemName;
            single_item.category = item_detail.CATEGORY;
            single_item.is_fragile = (item_detail.IS_FRAGILE == 1) ? true : false;
            single_item.price = item.itemPrice;
            single_item.quantity = item.itemQuantity;
            single_item.weight = item_detail.WEIGHT;
            single_item.length = item_detail.LENGTH;
            single_item.width = item_detail.WIDTH;
            single_item.height = item_detail.HEIGHT;

            items.push(single_item);
        }

        formData.append("items", JSON.stringify(items));
        formData.append("first_item_name", items[0].name);

        // 1. Create a new XMLHttpRequest object
        let xhr = new XMLHttpRequest();

        // 2. Configure it: GET-request for the URL /article/.../load
        xhr.open('POST', '/nexilis/logics/shipment_api/paxel_create_shipments');

        xhr.responseType = 'json';

        // 3. Send the request over the network
        xhr.send(formData);

        // 4. This will be called after the response is received
        xhr.onload = async function () {

            if (xhr.status != 200) { // analyze HTTP status of the response
                // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
                let responseObj = xhr.response;
                // showSuccessModal(JSON.stringify(responseObj));
                return "error";

            } else { // show the result
                // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
                let responseObj = xhr.response;
                // console.log(responseObj);
                // airway_bills.push(responseObj.data.airwaybill_code);
                storeAWB(responseObj.data.airwaybill_code, responseObj.data.shipping_cost, responseObj.data.estimated_pickup_date, responseObj.data.estimated_arrival_date, responseObj.data.created_datetime, merchant.merchant_name)

            }

        };

    }

    // console.log(airway_bills);
    return airway_bills;
}

let allStores = [];
function fetchAllStores() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', '/nexilis/logics/fetch_all_stores.php');
    xhr.responseType = 'json';

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else { // show the result
            let responseObj = xhr.response;
            allStores.push(responseObj);
            listRegisteredStores();
        }
    };
    xhr.send();

    xhr.onerror = function () {
        console.log("Request failed");
    };
}
fetchAllStores();

let allProducts = [];
function fetchProducts() {
    let xhr = new XMLHttpRequest();
    let params = window.location.search;
    if (params == "") {
        params = "?f_pin=" + fpin_lokal;
    } else if (params != "" && !params.includes("f_pin")) {
        params = params + "&f_pin=" + fpin_lokal;
    }
    console.log(params);
    xhr.open('GET', '/nexilis/logics/fetch_products_json.php' + params);
    xhr.responseType = 'json';

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else { // show the result
            let responseObj = xhr.response;
            // console.log(responseObj);
            // allProducts.push(responseObj);
            allProducts = responseObj;
        }
    };
    xhr.send();

    xhr.onerror = function () {
        console.log("Request failed");
    };
}
fetchProducts();

let registeredStores = [];

function countRewardPts(merchant) {

    let merchantCart = cart.find(mc => mc.merchant_name == merchant).items;

    let merchantTotalPts = 0;
    merchantCart.forEach(item => {
        if (item.checked == "checked") {
            let rewardPoint = allProducts.find(it => it.CODE == item.itemCode).REWARD_POINT;
            if (item.itemQuantity >= 5 && item.itemQuantity < 10) {
                rewardPoint = rewardPoint * 2;
            } else if (item.itemQuantity >= 10) {
                rewardPoint = rewardPoint * 3;
            }
            merchantTotalPts += rewardPoint;
        }
    });

    return merchantTotalPts;
}

function updateRewardPts() {
    let updateMerchantArr = [];

    cart.forEach(cartitem => {
        // let merchantTotalPrice = 0;
        let merchantTotalRewards = 0;
        cartitem.items.forEach(item => {
            // merchantTotalPrice += item.itemQuantity * item.itemPrice;
            // merchantTotalRewards += allProducts.find(it => it.CODE == item.itemCode).REWARD_POINT;
            let rewardPoint = allProducts.find(it => it.CODE == item.itemCode).REWARD_POINT;
            if (item.itemQuantity >= 5 && item.itemQuantity < 10) {
                rewardPoint = rewardPoint * 2;
            } else if (item.itemQuantity >= 10) {
                rewardPoint = rewardPoint * 3;
            }
            merchantTotalRewards += rewardPoint;
        })

        let regStore = registeredStores.some(el => el.NAME == cartitem.merchant_name);
        // let regStore = true;
        if (regStore) {
            console.log("is registered: " + cartitem.merchant_name);
            if (rewardPts.some(el => el.NAME == cartitem.merchant_name)) {
                let existingMerchant = rewardPts.find(el => el.NAME == cartitem.merchant_name);
                existingMerchant.AMOUNT += merchantTotalRewards;
                updateMerchantArr.push(existingMerchant);
            } else {
                let newMerchant = {};
                newMerchant.AMOUNT = merchantTotalRewards;
                newMerchant.STORE_CODE = allStores[0].find(el => el.NAME == cartitem.merchant_name).CODE;
                // newMerchant.F_PIN = window.Android.getFPin();
                newMerchant.F_PIN = fpin_lokal;
                newMerchant.NAME = cartitem.merchant_name;
                updateMerchantArr.push(newMerchant);
            }
        }
    })

    console.log(updateMerchantArr);

    let xhr = new XMLHttpRequest();

    let base64Rewards = btoa(JSON.stringify(updateMerchantArr));

    let formData = new FormData();
    formData.append("reward_list", base64Rewards);
    formData.append("f_pin", fpin_lokal);

    xhr.open('POST', '/nexilis/logics/update_store_rewards');
    xhr.responseType = 'json';

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else { // show the result
            console.log(xhr.response);
            // let responseObj = xhr.response;
            // allStores.push(responseObj);
        }
    };
    xhr.send(formData);

    xhr.onerror = function () {
        console.log("Request failed");
    };
}

// payment with ovo
function toSubmitOVO() {
    event.preventDefault();

    if (validateForm("ovo-form") == false) {
        showSuccessModal(dictionary.checkout.notice.emptyForm[defaultLang]);
        return;
    };

    // add please wait text and disable button
    var form = document.getElementById("payment-choices");
    document.getElementById("pay-with-ovo").disabled = true;
    while (form.firstChild) {
        form.removeChild(form.firstChild);
    }
    form.innerHTML = dictionary.checkout.notice.pleaseWait[defaultLang];

    $("#ovo-form :input").prop('readonly', true);

    var js = {
        phone_number: $('#phone-number').val(),
        amount: this.price,
    };

    // var callbackURL = this.callbackURL;
    // var amount = this.price;

    $.post("http://202.158.33.26/paliobutton/php/paliopay_ovo",
        js,
        function (data, status) {
            try {
                if (data == "SUCCEEDED") {
                    // postForm("http://192.168.0.56/nexilis/logics/store_payment", { fpin: fpin, method: "ovo", status: "success", items: items });
                    showSuccessModal(dictionary.checkout.notice.success[defaultLang], "OVO");
                    updateRewardPts();
                } else {
                    // alert("Credit card transaction failed");
                    showSuccessModal(dictionary.checkout.notice.failed[defaultLang], "OVO");
                }
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
            }
        }
    );

    // alert("Please finish your payment.");
}

// payment with dana
function toSubmitDANA() {
    event.preventDefault();

    // add please wait text and disable button
    var form = document.getElementById("payment-choices");
    document.getElementById("pay-with-dana").disabled = true;
    while (form.firstChild) {
        form.removeChild(form.firstChild);
    }
    form.innerHTML = dictionary.checkout.notice.pleaseWait[defaultLang];

    $("#dana-form :input").prop('readonly', true);

    var js = {
        // callback: this.callbackURL,
        callback: "http://202.158.33.26/paliobutton/php/close.php",
        amount: this.price,
    };

    $.post("http://202.158.33.26/paliobutton/php/paliopay_dana",
    // $.post("/test/paliopay_dana.php",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);

                checkEwallet(response.id);

                window.open(response.actions.desktop_web_checkout_url, "_blank");
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment with linkaja
function toSubmitLINKAJA() {
    event.preventDefault();

    // add please wait text and disable button
    var form = document.getElementById("payment-choices");
    document.getElementById("pay-with-linkaja").disabled = true;
    while (form.firstChild) {
        form.removeChild(form.firstChild);
    }
    form.innerHTML = dictionary.checkout.notice.pleaseWait[defaultLang];

    var js = {
        // callback: this.callbackURL,
        callback: "http://202.158.33.26/paliobutton/php/close.php",
        amount: this.price,
    };

    $.post("http://202.158.33.26/paliobutton/php/paliopay_linkaja",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);

                checkEwallet(response.id);

                window.open(response.actions.desktop_web_checkout_url, "_blank");
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                showSuccessModal(dictionary.checkout.notice.error[defaultLang], "LINKAJA");
            }
        }
    );
}

// xendit cc functions
function toSubmit() {
    event.preventDefault();

    if (validateForm("credit-card-form") == false) {
        showSuccessModal(dictionary.checkout.notice.emptyForm[defaultLang]);
        return;
    };

    // add please wait text and disable button
    var form = document.getElementById("payment-choices");
    document.getElementById("pay-with-credit-card").disabled = true;
    while (form.firstChild) {
        form.removeChild(form.firstChild);
    }
    form.innerHTML = dictionary.checkout.notice.pleaseWait[defaultLang];

    // disable input
    $("#credit-card-form :input").prop('readonly', true);
    $("#credit-card-exp-month").attr('disabled', true);

    // document.getElementById("credit-card-form").classList.add('d-none');

    //dev
    // Xendit.setPublishableKey('xnd_public_development_QToOEG2Dx1gvrMjuOjwbWKcOttQTwjhPtjI3JYUMzv7mzAzRTmT9iHQonH12');
    //prod
    Xendit.setPublishableKey('xnd_public_production_qoec6uRBSVSb4n0WwIijVZgDJevwSZ5xKuxaTRh4YBix0nMSsKgxi226yxtTd7');

    var tokenData = getTokenData();

    Xendit.card.createToken(tokenData, xenditResponseHandler);
}

function xenditResponseHandler(err, creditCardCharge) {
    if (err) {
        return displayError(err);
        // console.log(err);
    }

    if (creditCardCharge.status === 'APPROVED' || creditCardCharge.status === 'VERIFIED') {
        console.log("success");
        updateRewardPts();
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
    $('#three-ds-container').hide();
    $('.overlay').hide();
    $("#credit-card-form :input").prop('readonly', false);
    $("#credit-card-exp-month").attr('disabled', false);
    // alert('Request Credit Card Charge Failed');

    showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");
};

function displaySuccess(creditCardCharge) {
    var $form = $('#credit-card-form');
    $('#three-ds-container').hide();
    $('.overlay').hide();

    // loading modal
    // $('#creditModalCenter').modal('show');

    var js = {
        token_id: creditCardCharge["id"],
        amount: this.price,
        cvv: $form.find('#credit-card-cvv').val()
    };
    var callbackURL = this.callbackURL;
    var amount = this.price;
    var items = JSON.stringify(cart);

    if (userAgent) {
        var fpin = window.Android.getFPin();
    } else {
        var fpin = "test";
    }

    $.post("http://202.158.33.26/paliobutton/php/paliopay",
        js,
        function (data, status) {
            try {
                if (data.status == "CAPTURED") {
                    // postForm("http://192.168.0.56/nexilis/logics/store_payment", { fpin: fpin, method: "card", status: "success", items: items });
                    showSuccessModal(dictionary.checkout.notice.success[defaultLang], "CARD");
                } else {
                    // alert("Credit card transaction failed");
                    showSuccessModal(dictionary.checkout.notice.failed[defaultLang], "CARD");
                }
            } catch (err) {
                console.log(err);
                // alert("Error occured");
                showSuccessModal(dictionary.checkout.notice.error[defaultLang], "CARD");
            }
        }, 'json'
    );
}

function getTokenData() {
    var $form = $('#credit-card-form');
    return {
        // amount: $form.find('#credit-card-amount').val(),
        amount: this.price,
        card_number: $form.find('#credit-card-number').val(),
        card_exp_month: $form.find('#credit-card-exp-month').val(),
        card_exp_year: $form.find('#credit-card-exp-year').val(),
        card_cvn: $form.find('#credit-card-cvv').val(),
        is_multiple_use: false,
        should_authenticate: true
    };
}

// summmon payment modal
async function palioPay(callbackURL, price, name, quantity, itemcode) {
    event.preventDefault();

    // this.callbackURL = callbackURL || window.location.href;
    this.callbackURL = callbackURL || "-";
    this.price = price || 0;
    this.name = name || null;
    this.quantity = quantity || 0;
    this.itemCode = itemcode;

    if (this.name != null && this.quantity != 0) {
        // cart modal
        $('body').css('overflow', 'hidden');
        $('body').css('overscroll-behavior-y', 'contain');
        pullRefresh();
        this.myModal = new CartModal(this.callbackURL, this.price, this.name, this.quantity, this.itemCode);
    } else {
        // payment modal
        $('body').css('overflow', 'hidden');
        $('body').css('overscroll-behavior-y', 'contain');
        pullRefresh();
        this.myModal = new SimpleModal();
    }

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

"use strict";

// payment modal
class SimpleModal {

    constructor(modalTitle) {
        this.modalTitle = modalTitle || dictionary.checkout.title[defaultLang];
        this.parent = document.body;

        this.modal = undefined;
        this.closeButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.closeButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.closeButton.addEventListener("click", () => {
                resolve(null);
                $('body').css('overflow', 'auto');
                $('body').css('overscroll-behavior-y', 'auto');
                pullRefresh();
                this._destroyModal();
            })
        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // Close
        this.closeButton = document.createElement('button');
        this.closeButton.type = "button";
        this.closeButton.innerHTML = "&times;";
        this.closeButton.classList.add('simple-modal-close');
        title.appendChild(this.closeButton);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        buttonGroup.setAttribute("id", "payment-choices");
        window.appendChild(buttonGroup);

        // credit / debit button
        this.cardButton = document.createElement('button');
        this.cardButton.type = "button";
        this.cardButton.setAttribute("id", "credit");
        this.cardButton.classList.add('simple-modal-button-green');
        this.cardButton.classList.add('pay-method');
        this.cardButton.classList.add('simple-modal-button-red');
        this.cardButton.textContent = "CARD";
        buttonGroup.appendChild(this.cardButton);

        // ovo button
        this.ovoButton = document.createElement('button');
        this.ovoButton.type = "button";
        this.ovoButton.setAttribute("id", "ovo");
        this.ovoButton.classList.add('simple-modal-button-green');
        this.ovoButton.classList.add('pay-method');
        this.ovoButton.textContent = "OVO";
        buttonGroup.appendChild(this.ovoButton);

        // dana button
        this.danaButton = document.createElement('button');
        this.danaButton.type = "button";
        this.danaButton.setAttribute("id", "dana");
        this.danaButton.classList.add('simple-modal-button-green');
        this.danaButton.classList.add('pay-method');
        this.danaButton.textContent = "DANA";
        buttonGroup.appendChild(this.danaButton);

        // linkaja button
        this.linkajaButton = document.createElement('button');
        this.linkajaButton.type = "button";
        this.linkajaButton.setAttribute("id", "linkaja");
        this.linkajaButton.classList.add('simple-modal-button-green');
        this.linkajaButton.classList.add('pay-method');
        this.linkajaButton.textContent = "LINKAJA";
        buttonGroup.appendChild(this.linkajaButton);

        // horizontal line
        this.hr = document.createElement('hr');
        buttonGroup.appendChild(this.hr);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.classList.add('simple-modal-text');
        text.innerHTML = cardModalHtml;
        window.appendChild(text);

        // Let's rock
        this.parent.appendChild(this.modal);
        changeColor();
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// add to cart modal
class CartModal {

    constructor(itemSource, itemPrice, itemName, itemQuantity, itemCode) {
        this.modalTitle = dictionary.addItem.title[defaultLang];
        this.acceptText = dictionary.addItem.buttons.yes[defaultLang];
        this.cancelText = dictionary.addItem.buttons.no[defaultLang];

        this.itemSource = itemSource;
        this.itemPrice = itemPrice;
        this.itemName = itemName;
        this.itemQuantity = itemQuantity;
        this.itemCode = itemCode;
        this.checked = "checked";

        this.parent = document.body;

        this.modal = undefined;
        this.acceptButton = undefined;
        this.cancelButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.acceptButton || !this.cancelButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.acceptButton.focus();

            this.acceptButton.addEventListener("click", () => {

                // items
                var item_details = {};
                item_details.itemName = this.itemName;
                item_details.itemPrice = this.itemPrice;
                item_details.itemCode = this.itemCode;
                item_details.checked = this.checked;

                // if the item value is not valid
                item_details.itemQuantity = Number(document.getElementById('new-item-quantity').value);
                if (item_details.itemQuantity == "" || item_details.itemQuantity == undefined) {
                    showSuccessModal(dictionary.addItem.notice.isEmpty[defaultLang], "");
                    document.getElementById('new-item-quantity').value = 1;
                    return;
                } else if (item_details.itemQuantity < 1) {
                    showSuccessModal(dictionary.addItem.notice.isBelowOne[defaultLang], "");
                    document.getElementById('new-item-quantity').value = 1;
                    return;
                } else if (Number.isInteger(item_details.itemQuantity) == false) {
                    showSuccessModal(dictionary.addItem.notice.isDecimal[defaultLang], "");
                    document.getElementById('new-item-quantity').value = 1;
                    return;
                }

                var merchant = cart.find(el => el.merchant_name == this.itemSource);

                // merchant
                if (merchant != undefined) { // check if the merchant already in the json
                    var item = merchant.items.find(el => el.itemName == this.itemName);

                    if (item != undefined) {
                        item.itemQuantity = parseInt(item.itemQuantity) + parseInt(item_details.itemQuantity);
                    } else {
                        merchant.items.push(item_details);
                    }

                } else {
                    var new_merchant = {};
                    new_merchant.merchant_name = this.itemSource;
                    new_merchant.items = [];
                    new_merchant.items.push(item_details);

                    cart.push(new_merchant);
                }

                localStorage.setItem("cart", JSON.stringify(cart));
                updateCounter();
                // alert("Item successfully added to your cart.");
                showSuccessModal(dictionary.notice.successAdd.text[defaultLang], "clear_modal");
                this._destroyModal();
            });

            this.cancelButton.addEventListener("click", () => {
                resolve(false);
                $('body').css('overflow', 'auto');
                $('body').css('overscroll-behavior-y', 'auto');
                pullRefresh();
                this._destroyModal();
                checkVideoViewport(); // continue video
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.style.margin = "0px";
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // Main text
        const item = document.createElement('span');
        item.classList.add('simple-modal-text');
        item.classList.add('text-center');
        item.classList.add('mt-3');
        item.classList.add('mb-3');
        item.innerHTML = this.itemName + "<br>" +
            "<button onclick='changeValue(\"new-item-quantity\",\"sub\");' class='text-white simple-modal-button-red'> - </button>" +
            "<input type='number' class='mx-1' id='new-item-quantity' min='1' style='width:3em;' value='" + this.itemQuantity + "'>" +
            "<button onclick='changeValue(\"new-item-quantity\",\"add\");' class='text-white simple-modal-button-red'> + </button>" +
            "<span class='price-tag'>X Rp" + numberWithDots(this.itemPrice) + "</span>";
        window.appendChild(item);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.acceptButton = document.createElement('button');
        this.acceptButton.type = "button";
        this.acceptButton.classList.add('simple-modal-button-green');
        this.acceptButton.textContent = this.acceptText;
        buttonGroup.appendChild(this.acceptButton);

        // Cancel button
        this.cancelButton = document.createElement('button');
        this.cancelButton.type = "button";
        this.cancelButton.classList.add('simple-modal-button-red');
        this.cancelButton.textContent = this.cancelText;
        buttonGroup.appendChild(this.cancelButton);

        // Let's rock
        this.parent.appendChild(this.modal);
        changeColor();
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// order history modal
class ShowOrderModal {

    constructor() {
        this.cartTitle = dictionary.cart.title[defaultLang];
        this.orderTitle = dictionary.cart.order[defaultLang];

        this.parent = document.body;

        this.modal = undefined;
        this.closeButton = undefined;
        this.okButton = undefined;

        this.dropdown = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {

            this.okButton.addEventListener("click", () => {
                this._destroyModal();

            });

            this.dropdown.addEventListener("change", (e) => {
                console.log(e.target.value); // keranjang kamu / pesanan kamu

                this._destroyModal();
                showCart();
            });

            this.closeButton.addEventListener('click', () => {
                resolve(null);
                $('body').css('overflow', 'auto');
                $('body').css('overscroll-behavior-y', 'auto');
                pullRefresh();
                this._destroyModal();
                checkVideoViewport();
            });

            this.okButton.addEventListener('click', () => {
                resolve(null);
                $('body').css('overflow', 'auto');
                $('body').css('overscroll-behavior-y', 'auto');
                pullRefresh();
                this._destroyModal();
                checkVideoViewport();
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // select pesanan / keranjang
        this.dropdown = document.createElement('select');
        this.dropdown.classList.add('cart-dropdown-title');
        title.appendChild(this.dropdown);

        // Title text
        const yourOrder = document.createElement('option');
        yourOrder.classList.add('simple-modal-title-text');
        yourOrder.textContent = this.orderTitle;
        this.dropdown.appendChild(yourOrder);

        const yourCart = document.createElement('option');
        yourCart.classList.add('simple-modal-title-text');
        yourCart.textContent = this.cartTitle;
        this.dropdown.appendChild(yourCart);

        // Close
        this.closeButton = document.createElement('button');
        this.closeButton.type = "button";
        this.closeButton.innerHTML = "&times;";
        this.closeButton.classList.add('simple-modal-close');
        title.appendChild(this.closeButton);

        // Main text
        const table = document.createElement('table');
        table.setAttribute("border", "1");
        table.classList.add("m-1")
        table.style.width = "90%";
        window.appendChild(table);

        const tr = document.createElement('tr');
        table.appendChild(tr);

        const th = document.createElement('th');
        th.textContent = "Nama toko";
        tr.appendChild(th);

        const th2 = document.createElement('th');
        th2.textContent = "Perkiraan sampai";
        tr.appendChild(th2);

        const th3 = document.createElement('th');
        th3.textContent = "Status";
        tr.appendChild(th3);

        let orders = getOrders();
        if(orders.length == 0) {

            const tr2 = document.createElement('tr');
            table.appendChild(tr2);

            const td = document.createElement('td');
            td.textContent = "Kosong";
            tr2.appendChild(td);

            const td2 = document.createElement('td');
            td2.textContent = "Kosong";
            tr2.appendChild(td2);

            const td3 = document.createElement('td');
            td3.textContent = "Kosong";
            tr2.appendChild(td3);

        } else {
            orders.forEach(element => {

                const tr2 = document.createElement('tr');
                table.appendChild(tr2);

                const td = document.createElement('td');
                td.textContent = element.MERCHANT_NAME;
                tr2.appendChild(td);

                const td2 = document.createElement('td');
                td2.textContent = element.EST_ARRIVAL;
                tr2.appendChild(td2);

                let status = trackShipment(element.AWB_CODE);
                const td3 = document.createElement('td');
                td3.textContent = status.data.logs.length == 0 ? "" : status.data.logs[0].status;
                tr2.appendChild(td3);

            });
        }
        

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.okButton = document.createElement('button');
        this.okButton.type = "button";
        this.okButton.classList.add('simple-modal-button-green');
        this.okButton.textContent = "OK";
        buttonGroup.appendChild(this.okButton);

        // Let's rock
        this.parent.appendChild(this.modal);
        changeColor();
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }

}

// show cart
class ShowCartModal {

    constructor() {
        this.cartTitle = dictionary.cart.title[defaultLang];
        this.orderTitle = dictionary.cart.order[defaultLang];
        this.checkoutText = dictionary.cart.buttons.checkout[defaultLang];
        this.clearCartText = dictionary.cart.buttons.clear[defaultLang];

        this.parent = document.body;

        this.modal = undefined;
        this.checkoutButton = undefined;
        this.clearCartButton = undefined;
        this.closeButton = undefined;
        this.shipmentButton = undefined;

        this.dropdown = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.checkoutButton || !this.clearCartButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            if (localStorage.getItem("shipping-form") != undefined) {
                shippingRate();
            }

            this.checkoutButton.focus();

            this.checkoutButton.addEventListener("click", async () => {
                if (!minimalOne()) {
                    showSuccessModal(dictionary.cart.notice.minimalOneSelected[defaultLang], "");
                    return;
                }

                if (localStorage.getItem("shipping-form") == undefined) {
                    let shipmentBox = document.getElementById("shipment-cost");

                    // Add a class that defines an animation
                    shipmentBox.classList.add('error');

                    // remove the class after the animation completes
                    setTimeout(function () {
                        shipmentBox.classList.remove('error');
                    }, 300);

                    return;
                }

                collectiveCheckout('');
                this._destroyModal();

            });

            this.clearCartButton.addEventListener("click", async () => {
                resolve(false);
                let response = await clearCart();

                if (response == true) {
                    showSuccessModal(dictionary.notice.successClear.text[defaultLang], "clear_modal");
                    $('body').css('overflow', 'auto');
                    $('body').css('overscroll-behavior-y', 'auto');
                    pullRefresh();
                    this._destroyModal();
                }
            });

            this.closeButton.addEventListener("click", () => {
                resolve(null);
                $('body').css('overflow', 'auto');
                $('body').css('overscroll-behavior-y', 'auto');
                pullRefresh();
                this._destroyModal();
                checkVideoViewport();
            });

            this.shipmentButton.addEventListener("click", async () => {
                let response = await addShippingInfo();

                if (response) {
                    // update total cost
                    shippingRate();

                } else {
                    this._destroyModal();
                    showCart();
                }
            });

            this.dropdown.addEventListener("change", (e) => {
                console.log(e.target.value); // keranjang kamu / pesanan kamu
                
                this._destroyModal();
                showOrder();
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // select pesanan / keranjang
        this.dropdown = document.createElement('select');
        this.dropdown.classList.add('cart-dropdown-title');
        title.appendChild(this.dropdown);

        // Title text
        const yourCart = document.createElement('option');
        yourCart.classList.add('simple-modal-title-text');
        yourCart.textContent = this.cartTitle;
        this.dropdown.appendChild(yourCart);

        const yourOrder = document.createElement('option');
        yourOrder.classList.add('simple-modal-title-text');
        yourOrder.textContent = this.orderTitle;
        this.dropdown.appendChild(yourOrder);

        // Close
        this.closeButton = document.createElement('button');
        this.closeButton.type = "button";
        this.closeButton.innerHTML = "&times;";
        this.closeButton.classList.add('simple-modal-close');
        title.appendChild(this.closeButton);

        // Main text
        const cartList = document.createElement('span');
        cartList.setAttribute("id", "cart-list");
        cartList.classList.add('simple-modal-text');
        cartList.classList.add('overflow-cart');
        window.appendChild(cartList);

        cart.forEach(item => {
            let merchant_name = item.merchant_name;

            const merchantSpan = document.createElement('span');
            merchantSpan.setAttribute("id", item.merchant_name.replace(/ /g, "-"));
            merchantSpan.innerHTML = "<b>" + item.merchant_name + " : </b>";
            cartList.appendChild(merchantSpan);

            let i = item.items.length;
            item.items.forEach(item => {
                let quantityValue = item.itemName.replace(/ /g, "-") + "-quantity";

                const text = document.createElement('span');
                text.classList.add('simple-modal-text');
                text.setAttribute("id", item.itemName.replace(/ /g, "-"));
                text.innerHTML = "<span class='cart-item'>- " + item.itemName + "</span>" +
                    "<input onclick='checkUncheck(\"" + merchant_name + "\",\"" + item.itemName + "\");' id='" + item.itemName + "' type='checkbox' class='cart-checkbox' " + item.checked + ">" +
                    "<button onclick='changeValue(\"" + quantityValue + "\",\"sub\",\"" + merchant_name + "\",\"" + item.itemName + "\");' class='text-white simple-modal-button-red'> - </button>" +
                    "<input id=\"" + quantityValue + "\" class='mx-1' type='number' min='1' style='width:3em;' value='" + item.itemQuantity + "' disabled>" +
                    "<button onclick='changeValue(\"" + quantityValue + "\",\"add\",\"" + merchant_name + "\",\"" + item.itemName + "\");' class='text-white simple-modal-button-red'> + </button>" +
                    "<span class='price-tag'>X Rp" + numberWithDots(item.itemPrice) + "</span>" +
                    "<span style='float: right; cursor: pointer;' onclick='deleteItem(\"" + merchant_name + "\",\"" + item.itemName + "\");'><i class='fas fa-trash'></i></span><br>";
                merchantSpan.appendChild(text);

                i--;
                if (i == 0) {
                    const merchantRewardPts = document.createElement('span');
                    merchantRewardPts.id = "listitems-" + merchant_name;
                    merchantRewardPts.setAttribute("style", "color: #00ccd6; margin-top: 10px !important");
                    let storeIsReg = registeredStores.some(st => st.NAME == merchant_name);
                    if (storeIsReg) {
                        let merchantTotalReward = countRewardPts(merchant_name);
                        if (merchantTotalReward > 0) {
                            merchantRewardPts.innerHTML = "Kamu bisa dapat reward <b>" + merchantTotalReward + " poin</b> dari <b>" + merchant_name + "</b>";
                        } else {
                            merchantRewardPts.innerHTML = "";
                        }
                        merchantSpan.appendChild(merchantRewardPts);
                    }

                    // horizontal line
                    this.hr = document.createElement('hr');
                    merchantSpan.appendChild(this.hr);
                }
            })

        })

        // shipment group div
        const totalShipment = document.createElement('span');
        totalShipment.classList.add('shipment-total');
        window.appendChild(totalShipment);

        // shipment
        const shipment = document.createElement('span');
        shipment.classList.add('float-left');
        shipment.textContent = 'Biaya kirim';
        totalShipment.appendChild(shipment);

        // shipment cost
        if (localStorage.getItem("shipping-form") == undefined) {
            this.shipmentButton = document.createElement('span');
            this.shipmentButton.setAttribute("id", "shipment-cost");
            this.shipmentButton.classList.add('float-right');
            this.shipmentButton.classList.add("insert-address");
            this.shipmentButton.textContent = "Isi alamat";
            totalShipment.appendChild(this.shipmentButton);
        } else {
            this.shipmentButton = document.createElement('span');
            this.shipmentButton.classList.add("shipment-cost");
            this.shipmentButton.classList.add("insert-address");
            this.shipmentButton.setAttribute("id", "shipment-cost");
            totalShipment.appendChild(this.shipmentButton);
        }

        // total price group div
        const totalPrice = document.createElement('span');
        totalPrice.classList.add('price-total');
        window.appendChild(totalPrice);

        // total
        const total = document.createElement('span');
        total.classList.add('float-left');
        total.textContent = 'Total';
        totalPrice.appendChild(total);

        // price
        const total2 = document.createElement('span');
        total2.classList.add('float-right');
        total2.setAttribute("id", "cummulative-price");
        total2.textContent = "Rp" + numberWithDots(countPrice());
        totalPrice.appendChild(total2);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.checkoutButton = document.createElement('button');
        this.checkoutButton.type = "button";
        this.checkoutButton.classList.add('simple-modal-button-green');
        this.checkoutButton.textContent = this.checkoutText;
        // this.checkoutButton.setAttribute("onclick", "updateRewardPts();");
        buttonGroup.appendChild(this.checkoutButton);

        // Cancel button
        this.clearCartButton = document.createElement('button');
        this.clearCartButton.type = "button";
        this.clearCartButton.setAttribute("id", dictionary.cart.buttons.clear[defaultLang]);
        this.clearCartButton.classList.add('simple-modal-button-red');
        this.clearCartButton.textContent = this.clearCartText;
        buttonGroup.appendChild(this.clearCartButton);

        // Let's rock
        this.parent.appendChild(this.modal);
        changeColor();
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// shipping info
class ShippingModal {

    constructor() {
        this.modalTitle = dictionary.shipping.title[defaultLang];
        this.acceptText = dictionary.shipping.buttons.ok[defaultLang];
        this.cancelText = dictionary.shipping.buttons.cancel[defaultLang];

        this.parent = document.body;

        this.modal = undefined;
        this.acceptButton = undefined;
        this.cancelButton = undefined;

        this._createModal();

        // check if shipping address already exist
        if (localStorage.getItem("shipping-form") != undefined) {
            fillForm("shipping-form")
        }
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.acceptButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.acceptButton.focus();

            this.acceptButton.addEventListener("click", async () => {
                // confirm shipping info
                let response = await validateForm("shipping-form");
                if (response) {
                    saveForm("shipping-form");
                    resolve(true)
                    this._destroyModal();
                } else {
                    showSuccessModal("Data tidak boleh kosong");
                }
            });

            this.cancelButton.addEventListener("click", () => {
                // abort deletion
                resolve(false);
                this._destroyModal();
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.classList.add('ml-0');
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.classList.add('simple-modal-text');
        text.innerHTML = shippingModalHtml;
        window.appendChild(text);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.acceptButton = document.createElement('button');
        this.acceptButton.type = "button";
        this.acceptButton.classList.add('simple-modal-button-green');
        this.acceptButton.textContent = "Ok";
        buttonGroup.appendChild(this.acceptButton);

        // Cancel button
        this.cancelButton = document.createElement('button');
        this.cancelButton.type = "button";
        this.cancelButton.classList.add('simple-modal-button-red');
        this.cancelButton.textContent = "Batal";
        buttonGroup.appendChild(this.cancelButton);

        // Let's rock
        this.parent.appendChild(this.modal);
        changeColor();
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

async function showSuccessModal(status, method) {
    event.preventDefault();

    $('body').css('overflow', 'hidden');
    $('body').css('overscroll-behavior-y', 'contain');
    pullRefresh();
    this.myModal = new SuccessModal(status, method);

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

// add to cart modal
class SuccessModal {

    constructor(status, method) {
        this.modalTitle = dictionary.notice.title[defaultLang];
        this.acceptText = "Ok";

        this.parent = document.body;
        this.status = status;
        this.method = method;

        this.modal = undefined;
        this.acceptButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (this.method == 'wait') {
                resolve(true);
            }

            this.acceptButton.focus();

            this.acceptButton.addEventListener("click", () => {
                var items = JSON.stringify(selectedItems("checked"));
                var base64items = btoa(items);

                if (this.status == dictionary.checkout.notice.success[defaultLang]) {
                    clearCart("checked_only");
                    postForm("http://202.158.33.26/paliobutton/php/store_payment", {
                    // postForm("/test/store_payment.php", {
                        fpin: fpin_lokal,
                        method: this.method,
                        status: "SUCCESS",
                        items: base64items
                    });
                }

                if (this.method != "updatecart") {
                    $('body').css('overflow', 'auto');
                    $('body').css('overscroll-behavior-y', 'auto');
                    pullRefresh();
                }

                if (this.method == "confirmation") {
                    // continue deletion
                    resolve(true);
                }

                if (this.method == "clear_modal") {
                    lostFocus();
                    checkVideoViewport(); // continue video
                }

                this._destroyModal();
            });

            this.cancelButton.addEventListener("click", () => {
                // abort deletion
                resolve(false);
                this._destroyModal();
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.style.margin = "0px";
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.classList.add('simple-modal-text');
        text.innerHTML = this.status;
        window.appendChild(text);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        if (this.method == "confirmation") {
            // Accept button
            this.acceptButton = document.createElement('button');
            this.acceptButton.type = "button";
            this.acceptButton.classList.add('simple-modal-button-green');
            this.acceptButton.textContent = "Ya";
            buttonGroup.appendChild(this.acceptButton);

            // Cancel button
            this.cancelButton = document.createElement('button');
            this.cancelButton.type = "button";
            this.cancelButton.classList.add('simple-modal-button-red');
            this.cancelButton.textContent = "Tidak";
            buttonGroup.appendChild(this.cancelButton);

        } else if (this.method != "wait") {
            // Accept button
            this.acceptButton = document.createElement('button');
            this.acceptButton.type = "button";
            this.acceptButton.classList.add('simple-modal-button-green');
            this.acceptButton.textContent = this.acceptText;
            buttonGroup.appendChild(this.acceptButton);

        }

        // Let's rock
        this.parent.appendChild(this.modal);
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// change color of the clicked payment method button
function changeColor() {
    var buttons = document.querySelectorAll(".pay-method");

    for (button in buttons) {
        buttons[button].onclick = function () {
            buttons.forEach(function (btn) {
                btn.classList.remove('simple-modal-button-red');
            })
            this.classList.add('simple-modal-button-red');

            var form = document.getElementById("payment-form");
            if (this.id == "credit") {
                form.removeChild(form.firstChild);
                form.innerHTML = cardModalHtml;
            } else if (this.id == "ovo") {
                form.removeChild(form.firstChild);
                form.innerHTML = ovoModalHtml;
            } else if (this.id == "dana") {
                form.removeChild(form.firstChild);
                form.innerHTML = danaModalHtml;
            } else if (this.id == "linkaja") {
                form.removeChild(form.firstChild);
                form.innerHTML = linkajaModalHtml;
            }
        }
    }
}

// check if page empty
function noProduct() {
    // -1 = no product
    return document.body.innerHTML.search("Tidak ada produk");
}

// artist request form
async function artistRequestForm() {

    $('body').css('overflow', 'hidden');
    $('body').css('overscroll-behavior-y', 'contain');
    pullRefresh();

    let artistModal = new ArtistModal();
    let response = await artistModal.question();

    return response;
}

// artist request form
class ArtistModal {

    constructor() {
        this.modalTitle = "Form Permintaan";
        this.acceptText = "Ok";
        this.cancelText = "Batal";

        this.parent = document.body;

        this.modal = undefined;
        this.acceptButton = undefined;
        this.cancelButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.acceptButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.acceptButton.focus();

            this.acceptButton.addEventListener("click", async () => {
                // confirm shipping info
                let response = await validateForm("artist-form");
                if (response) {
                    saveForm("artist-form");
                    resolve(true)
                    this._destroyModal();
                } else {
                    showSuccessModal("Data tidak boleh kosong");
                }
            });

            this.cancelButton.addEventListener("click", () => {
                // abort deletion
                resolve(false);
                this._destroyModal();
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.classList.add('ml-0');
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");
        text.classList.add('simple-modal-text');
        text.innerHTML = artistModalHtml;
        window.appendChild(text);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.acceptButton = document.createElement('button');
        this.acceptButton.type = "button";
        this.acceptButton.classList.add('simple-modal-button-green');
        this.acceptButton.textContent = "Ok";
        buttonGroup.appendChild(this.acceptButton);

        // Cancel button
        this.cancelButton = document.createElement('button');
        this.cancelButton.type = "button";
        this.cancelButton.classList.add('simple-modal-button-red');
        this.cancelButton.textContent = "Batal";
        buttonGroup.appendChild(this.cancelButton);

        // Let's rock
        this.parent.appendChild(this.modal);
        changeColor();
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

// timeline image long click behaviour
function registerPalioContextMenu() {
    updateCounter();
    if (noProduct() != "-1") {
        return;
    }
    document.querySelectorAll(".my-2").forEach(item => {
        let itemImage = item.querySelector(".timeline-image");
        if (itemImage.getAttribute('data-hascontext') !== 'true' && item.getAttribute('data-iscontent') !== 'true') {
            let itemSource = item.querySelector(".store-name").innerText;
            let itemName = item.querySelector(".prod-name").innerText;
            let itemCode = item.id.split("-")[1];

            itemImage.addEventListener('contextmenu', event => {
                event.preventDefault();
                let itemPrice = allProducts.find(el => el.CODE == itemCode).PRICE;
                palioPay(itemSource, itemPrice, itemName, 1, itemCode);
            }, false);

            itemImage.setAttribute('data-hascontext', 'true');
        } 
        // else {

        //     // artist form request
        //     itemImage.addEventListener('contextmenu', event => {
        //         event.preventDefault();
        //         artistRequestForm();
        //     }, false);

        // }
    });
}
registerPalioContextMenu();

// checkout multiple items from multiple merchants
function collectiveCheckout(callbackURL) {
    // document.getElementById("collective-checkout").addEventListener("click", event => {
    //    event.preventDefault();

    if (cart.length == 0) {
        // alert("Your cart is empty!");
        showSuccessModal(dictionary.notice.successClear.text[defaultLang], "");
        return false;
    }

    let total = document.getElementById("cummulative-price").innerHTML;
    let totalPrice = parseInt(total.replace(/\D/g, ''));

    // console.log(totalPrice);
    // do the collective checkout
    palioPay(callbackURL, totalPrice);

    // }); 
}

// insert recipient / destination address
async function addShippingInfo() {

    $('body').css('overflow', 'hidden');
    $('body').css('overscroll-behavior-y', 'contain');
    pullRefresh();

    let shippingModal = new ShippingModal();
    let response = await shippingModal.question();

    return response;
}

function listRegisteredStores() {
    let regStoreIDs = "";
    if (window.Android) {
        // console.log(window.Android.getRegisteredStores());
        regStoreIDs = window.Android.getRegisteredStores();
    } else {
        regStoreIDs = "28";
    }

    if (regStoreIDs != "") {
        let tempArr = regStoreIDs.split(',');
        for (i = 0; i < tempArr.length; i++) {
            let storeObj = {};
            storeObj.BE_ID = tempArr[i];
            let findStore = allStores[0].find(el => el.BE_ID == parseInt(tempArr[i]));
            if(findStore){
                storeObj.NAME = findStore.NAME;
                storeObj.STORE_CODE = findStore.CODE;
                if (!registeredStores.some(el => el.STORE_CODE == findStore.CODE)) {
                    registeredStores.push(storeObj);
                }
            }
        }
    }
}

function checkRegStorePeriodic() {
    setInterval(function () {
        listRegisteredStores();
    }, 5000);
}
// checkRegStorePeriodic();

// listRegisteredStores();

async function showCart() {
    event.preventDefault();

    $('body').css('overflow', 'hidden');
    $('body').css('overscroll-behavior-y', 'contain');
    pullRefresh();

    this.myModal = new ShowCartModal();

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

function showOrder() {
    event.preventDefault();

    $('body').css('overflow', 'hidden');
    $('body').css('overscroll-behavior-y', 'contain');
    pullRefresh();

    this.myModal = new ShowOrderModal();

    try {
        const modalResponse = myModal.question();
    } catch (err) {
        console.log(err);
    }
}

document.getElementById('cart').addEventListener('click', event => {
    event.stopPropagation();
    if (cart.length > 0) {
        listRegisteredStores();
        showCart();

        // handle landscape orientation
        if (window.innerHeight < window.innerWidth) {
            document.querySelector('.simple-modal-text.overflow-cart').style.setProperty("max-height", "171px");
        } else {
            document.querySelector('.simple-modal-text.overflow-cart').style.removeProperty("max-height");
        }
    } else {
        // alert("Your cart is empty!");
        showSuccessModal(dictionary.notice.emptyCart.text[defaultLang], "");
    }

})

// delete item in cart
async function deleteItem(merchantName, itemName) {

    $('body').css('overflow', 'hidden');
    $('body').css('overscroll-behavior-y', 'contain');
    pullRefresh();

    let confirmationModal = new SuccessModal(dictionary.deleteItem.notice[defaultLang], "confirmation");
    let response = await confirmationModal.question();

    if (response != true) {
        return;
    }

    // get items from selected merchant
    let items = cart.find(merchant => merchant.merchant_name == merchantName).items;

    // get the index of deleted item
    let indexItem = items.indexOf(items.find(item => item.itemName == itemName));
    let indexMerchant = cart.indexOf(cart.find(merchant => merchant.merchant_name == merchantName));

    // remove selected item
    let itemDiv = document.getElementById(itemName.replace(/ /g, "-"));
    itemDiv.parentNode.removeChild(itemDiv);
    cart.find(merchant => merchant.merchant_name == merchantName).items.splice(indexItem, 1);

    // delete merchant from cart if no item
    if (cart.find(merchant => merchant.merchant_name == merchantName).items.length == 0) {
        cart.splice(indexMerchant, 1);

        // delete merchant div
        let merchantDiv = document.getElementById(merchantName.replace(/ /g, "-"));
        merchantDiv.parentNode.removeChild(merchantDiv);
    }

    // update localstorage
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCounter();
    shippingRate();

    // if the cart is empty after deletion
    if (cart == "") {
        // document.getElementById(dictionary.cart.buttons.clear[defaultLang]).click();
        showSuccessModal(dictionary.notice.successClear.text[defaultLang], "clear_modal");
    }

}

// when user move from tab 2
function lostFocus() {
    const elements = document.getElementsByClassName("simple-modal-dialog");
    while (elements.length > 0) {
        elements[0].parentNode.removeChild(elements[0]);
    }

    $('body').css('overflow', 'auto');
    $('body').css('overscroll-behavior-y', 'auto');
    pullRefresh();
}

// when user move from tab 2
window.document.addEventListener("focusout", function () {
    if (document.hasFocus() == false) {
        lostFocus();
        listRegisteredStores();
    }
});

// shopping history
function getShoppingHistory(fpin) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            // let response = JSON.parse(xmlHttp.responseText);
            // let items = atob(a[0][0]);
            console.log(xmlHttp.responseText);
        }
    };

    xmlHttp.open("GET", "/nexilis/logics/fetch_shopping_history.php?f_pin=" + fpin);
    xmlHttp.send();
}

window.addEventListener("orientationchange", function (event) {
    var element = document.querySelector('.simple-modal-text.overflow-cart');
    var element2 = document.querySelector('#shipping-form');
    var element3 = document.querySelector('#payment-form');
    if (typeof (element) != 'undefined' && element != null) {
        // handle landscape orientation
        if (window.innerHeight > window.innerWidth) {
            document.querySelector('.simple-modal-text.overflow-cart').style.setProperty("max-height", window.innerWidth/3 + "px");
            document.querySelector('.shipment-total').style.setProperty("margin-top", "15px");
        } else {
            document.querySelector('.simple-modal-text.overflow-cart').style.removeProperty("max-height");
            document.querySelector('.shipment-total').style.setProperty("margin-top", "50px");
        }
    }

    if (typeof (element2) != 'undefined' && element2 != null) {
        if (window.innerHeight > window.innerWidth) {
            document.querySelector('#shipping-form').style.setProperty("max-height", window.innerWidth / 3 + "px");
        } else {
            document.querySelector('#shipping-form').style.setProperty("max-height", "300px");
        }
    }

    if (typeof (element3) != 'undefined' && element3 != null) {
        if (window.innerHeight > window.innerWidth) {
            document.querySelector('#payment-form').style.setProperty("max-height", window.innerWidth / 3 + "px");
            document.querySelector('#payment-form').style.setProperty("overflow", "auto");
        } else {
            document.querySelector('#payment-form').style.removeProperty("max-height");
            document.querySelector('#payment-form').style.removeProperty("overflow");
        }
    }
});
