// edit modal
class EditModal {

    constructor() {

        this.html =
            '<form method="post">' +
            '<fieldset>' +
            '   <div class="col p-3 small-text">' +
            '       <div class="row my-3">' +
            `           <input name="collection-title" placeholder="Collection Title" required>` +
            '       </div>' +
            '       <div class="row my-3">' +
            `           <textarea rows="2" name="short-description" required>Short description (Optional)</textarea>` +
            '       </div>' +
            '       <div class="row">' +
            `           <button id="confirm-changes" class="py-1 px-3 m-0 my-1 fs-16">Save Changes</button>` +
            '       </div>' +
            '   </div>' +
            '</fieldset>' +
            '</form>';

        this.parent = document.body;
        this.modal = document.getElementById('modal-changes-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    question() {
        this.save_button = document.getElementById('confirm-changes');

        return new Promise((resolve, reject) => {
            this.save_button.addEventListener("click", () => {
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
        text.innerHTML = this.html;
        window.appendChild(text);

        // Let's rock
        $('#modal-changes').modal('show');
    }

    _destroyModal() {
        $('#modal-changes').modal('hide');
    }
}

async function editCollection() {

    event.preventDefault();

    let edit = new EditModal();
    let response = await edit.question();

}

// get product data based on its code
function getProductDetail(product_code) {
    let formData = new FormData();
    formData.append("product_id", product_code);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/nexilis/logics/get_product_data");

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
}

function getPostDetail(product_code) {
    let formData = new FormData();
    formData.append("product_id", product_code);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/nexilis/logics/get_post_data");

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
}

function changeTab(id) {
    const desc = document.getElementById('description')
    const rat = document.getElementById('ratings')
    const desctab = document.getElementById('description-tab')
    const rattab = document.getElementById('ratings-tab')

    if (id == "description") {
        desc.classList.remove('d-none');
        rat.classList.add('d-none');
        rattab.classList.remove('tab-active');
        desctab.classList.add('tab-active');
    } else {
        rat.classList.remove('d-none');
        desc.classList.add('d-none');
        desctab.classList.remove('tab-active');
        rattab.classList.add('tab-active');
    }
}

function playModalVideo() {
    $('#modal-addtocart .addcart-img-container video').each(function () {
        $(this).off("play");
        $(this).on("play", function (e) {
            $(this).closest(".carousel").carousel("pause");
        })
        $(this).get(0).play();
        let $videoPlayButton = $(this).parent().find(".video-play");
        $videoPlayButton.addClass("d-none");
    })
}



let modalAddToCart = document.getElementById('modal-addtocart');

modalAddToCart.addEventListener('shown.bs.modal', function() {
    console.log('shown');
    if (window.Android) {
        window.Android.setIsProductModalOpen(true);
    }
    checkButtonPos();
    playModalVideo();
});

modalAddToCart.addEventListener('hidden.bs.modal', function() {
    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
    }
    console.log('hidden'); 
    let modalVideo = $('#modal-addtocart').find('video');
    if (modalVideo.length > 0) {
        $('#modal-addtocart .modal-body video').get(0).pause();
    }
})

function ext(url) {
    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."));
}

let $image_type_arr = ["jpg", "jpeg", "png", "webp"];
let $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];

// addtocart modal
class Addtocart {

    constructor(async_result) {

        let thumb_content = '';

        let thumb_id = async_result['THUMB_ID'].split('|')[0];
        console.log(thumb_id);
        let thumb_ext = ext(thumb_id).substr(1);
        console.log(thumb_ext);

        if ($image_type_arr.includes(thumb_ext)) {
            thumb_content = `<img class="product-img" src="${ thumb_id }">`;
        } else if ($video_type_arr.includes(thumb_ext)) {
            thumb_content = `
            <video class="product-img" controls>
            <source src="${thumb_id}">
            </video>
            `;
        }

        console.log(thumb_content);

        // codes below wil only run after getProductDetail done executing
        this.html =
            `<div class="col-12 px-0 mb-3">
                <div class="addcart-img-container text-center">
                    ${thumb_content}
                    <hr id="drag-this">
                    <img class="addcart-wishlist logo" src="/nexilis/images/${async_result['SHOP_THUMBNAIL'].split('|')[0]}">
                    <img class="addcart-wishlist verif" src="../assets/img/icons/Verified-(Black).png">
                    <span class="d-flex align-items-center addcart-wishlist name small-text">${async_result['SHOP_NAME']}</span>
                    <img class="addcart-wishlist star" src="../assets/img/icons/wishlist-yellow.png">
                    <img class="addcart-wishlist more" src="../assets/img/icons/More-white.png">
                </div>
            </div>

            <div class="container">
                <div class="row px-3">
                    <div class="col-9 d-flex align-items-center justify-content-start">
                        <div class="product-name m-0 fs-6 fw-bold">${async_result.PRODUCT_NAME}</div>
                    </div>
                    <div class="col-3 d-flex align-items-center justify-content-end">
                        <img class="mx-1" src="../assets/img/icons/wishlist-yellow.png" width="20px"><div class="fs-6 fw-bold product-name">5.0</div>
                    </div>
                </div>
                <div class="row px-3">
                    <div class="col-8 d-flex align-items-center justify-content-start">
                        <h5 class="product-price fs-6 m-0">Rp ${async_result.PRICE.toLocaleString('en-US')}</h5>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <h5 class="product-price small-text">1,1RB Terjual</h5>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 bg-white small-text fw-bold" style="color: #bbb">
                <div class="row">
                    <div onclick="changeTab('description');" id="description-tab" class="col-6 p-2 text-center font-medium tab-active">
                        Description
                    </div>
                    <div onclick="changeTab('ratings');" id="ratings-tab" class="col-6 p-2 text-center font-medium">
                        Ratings
                    </div>
                </div>
            </div>

            <div class="container">
                <div id="description" class="m-3 prod-details">
                    <div class="col-12 small-text">
                    ${async_result.DESCRIPTION}
                    </div>
                </div>

                <div id="ratings" class="m-3 ratings d-none d-flex align-items-center">
                    <div class="col-12">
                        <div class="row my-4">
                            <ul class="list-group list-group-horizontal d-flex align-items-center justify-content-evenly">
                                <li class="list-group-item">100+ Friendly Seller</li>
                                <li class="list-group-item">100+ Quick Response</li>
                                <li class="list-group-item">100+ Quick Delivery</li>
                                <li class="list-group-item">100+ Great Packaging</li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col">
                                All reviews
                                <div class="overflow-auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col prod-addtocart">
                <div class="container py-1">
                    <div class="row">
                        <div class="col-3">
                            <div class="input-group counter">
                                <button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeItemQuantity('modal-item-qty','sub')">-</button>
                                <input id="modal-item-qty" type="text" pattern="\d*" maxlength="3" class="form-control text-center" placeholder="" value="1" min="1">
                                <button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeItemQuantity('modal-item-qty','add')">+</button>
                            </div>
                        </div>
                        <div class="col-9">
                            <button id="add-to-cart" class="btn btn-addcart w-100" onclick="addToCartPost('${async_result['CODE']}');">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>`;

        this.parent = document.body;
        this.modal = document.getElementById('modal-add-body');
        this.modal.innerHTML = " ";

        this._createModal();
    }

    static async build(product_code) {
        let async_result = await getProductDetail(product_code);
        return new Addtocart(async_result);
    }

    static async buildPost(post_code) {
        let async_result = await getPostDetail(post_code);
        return new Addtocart(async_result);
    }

    question() {
        this.save_button = document.getElementById('confirm-changes');

        return new Promise((resolve, reject) => {
            this.save_button.addEventListener("click", () => {
                event.preventDefault();
                resolve(true);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Main text
        this.modal.innerHTML = this.html;

        // Let's rock
        $('#modal-addtocart').modal('show');
    }

    _destroyModal() {
        $('#modal-addtocart').modal('hide');

    }
}


function hideAddToCart() {
    $('#modal-addtocart').modal('hide');
}

async function showAddModal(product_code) {

    event.preventDefault();

    let add = await Addtocart.build(product_code);
    // let response = await add.question();

}

async function showAddModalPost(post_code){
    event.preventDefault();

    let add = await Addtocart.buildPost(post_code);
}

function changeItemQuantity(id, mod) {
    if (mod == "add") {
        document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;
    } else {
        if (document.getElementById(id).value > 1) {
            document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
        }
    }
}

function saveToLocalStorage(product, quantity = 1) {

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

    var merchant = cart.find(el => el.merchant_name == product.SHOP_NAME);

    // merchant
    if (merchant != undefined) { // check if the merchant already in the json
        var item = merchant.items.find(el => el.itemName == product.PRODUCT_NAME);

        if (item != undefined) {
            item.itemQuantity = parseInt(item.itemQuantity) + parseInt(item_details.itemQuantity);
        } else {
            merchant.items.push(item_details);
        }

    } else {
        var new_merchant = {};
        new_merchant.merchant_name = product.SHOP_NAME;
        new_merchant.items = [];
        new_merchant.items.push(item_details);

        cart.push(new_merchant);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    // console.log('added successfully');
};

function savePostToLocalStorage(product, quantity = 1) {

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
    item_details.isPost = 1;

    var merchant = cart.find(el => el.merchant_name == product.SHOP_NAME);

    // merchant
    if (merchant != undefined) { // check if the merchant already in the json
        var item = merchant.items.find(el => el.itemName == product.PRODUCT_NAME);

        if (item != undefined) {
            item.itemQuantity = parseInt(item.itemQuantity) + parseInt(item_details.itemQuantity);
        } else {
            merchant.items.push(item_details);
        }

    } else {
        var new_merchant = {};
        new_merchant.merchant_name = product.SHOP_NAME;
        new_merchant.items = [];
        new_merchant.items.push(item_details);

        cart.push(new_merchant);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    // console.log('added successfully');
}

function addToCart(item) {
    console.log('tab5-collection');
    event.preventDefault();
    let quantity = document.getElementById('modal-item-qty').value;

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

function addToCartPost(item) {
    console.log('tab5-collection');
    event.preventDefault();
    let quantity = document.getElementById('modal-item-qty').value;

    //Login-form input values
    let formData = new FormData();
    formData.append("product_id", item);

    // 1. Create a new XMLHttpRequest object
    if (quantity == 0) {
        alert('Please set the quantity!');
    } else {
        let xhr = new XMLHttpRequest();

        // 2. Configure it: GET-request for the URL /article/.../load
        xhr.open('POST', '/nexilis/logics/get_post_data');

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
                savePostToLocalStorage(response, quantity);
                if ($('#modal-addtocart').length > 0) {
                    $('#modal-addtocart').modal('hide');
                }
                if ($('#addtocart-success').length > 0) {
                    $('#addtocart-success').modal('show');
                }

            }
        };
    }
}

function goBack() {
    if (window.Android) {
        window.Android.closeView();
    } else {
        window.history.back();
    }
}