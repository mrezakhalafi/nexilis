let xReply = 0;
let xReffReply = 0;

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

    let f_pin = "";
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    formData.append("f_pin", f_pin);

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

modalAddToCart.addEventListener('show.bs.modal', function (e) {
    if ($("body").hasClass("no-modal")) {
        console.log("HAS CLASS");
        return false;
    }
    console.log("PAUSE", startPause);
    if (window.Android) {
        window.Android.tabShowHide(false);
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.tabShowHide) {
        window.webkit.messageHandlers.tabShowHide.postMessage({
            param1: false,
        });
    }
});

modalAddToCart.addEventListener('shown.bs.modal', function () {
    console.log('shown');
    if ($("body").hasClass("no-modal")) {
        console.log("HAS CLASS");
        hideAddToCart();
    }
    if (window.Android) {
        window.Android.setIsProductModalOpen(true);
    }
    checkButtonPos();
    playModalVideo();
});

modalAddToCart.addEventListener('hidden.bs.modal', function () {
    $("body").removeClass("no-modal");
    if (window.Android) {
        window.Android.tabShowHide(true);
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.tabShowHide) {
        window.webkit.messageHandlers.tabShowHide.postMessage({
            param1: true,
        });
    }
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

function goToLink(link) {
    // console.log('broh');
    // localStorage.setItem('close_modal', 1);
    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
    }
    window.location.href = link;
}

let $image_type_arr = ["jpg", "jpeg", "png", "webp"];
let $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
let ext_re = /(?:\.([^.]+))?$/;

function decodeURI(str) {
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));;
}

let richText = (content) => {
    let cont = content
        .replace(/\*([^\*]+)\*/g, "<strong>$1</strong>")
        .replace(/\^([^\^]+)\^/g, "<u>$1</u>")
        .replace(/\_([^\_]+)\_/g, "<i>$1</i>")
        .replace(/\~([^\~]+)\~/g, "<del>$1</del>")
        .replace(/[\n\r]+/g, "<br>");
    return cont;
};

// addtocart modal
class Addtocart {

    constructor(async_result, comments="") {

        let thumb_content = '';

        let thumb_id = async_result['THUMB_ID'].split('|');
        console.log(thumb_id);
        // let thumb_ext = ext(thumb_id).substr(1);
        // console.log(thumb_ext);
        let imageDivs = '';

        let domain = '';
        
        thumb_id.forEach((image, jIdx) => {
            var imgElem = '';
            var fileExt = ext_re.exec(image)[1];
            if ($image_type_arr.includes(fileExt)) {
                imgElem = `<img draggable="false" class="product-img" src="${ image.includes("http") ? image : domain + "/nexilis/images/" + image}">`;
            } else if ($video_type_arr.includes(fileExt)) {
                imgElem = `
              <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
              <video class="myvid" muted playsinline>
              <source src="${thumb_id.includes("http") ? image : domain + "/nexilis/images/" + image}">
              </video>
              <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
              <img draggable="false" src="../assets/img/video_mute.png" />
              </div>
              <div class="video-play d-none">
              '<img draggable="false" src="../assets/img/video_play.png" />
              </div>
              </div>
              `;
            }
            if (imgElem) {
                if (jIdx == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
                } else {
                    imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
                }
            }
        });

        let carouselControls = '';

        if (thumb_id.length > 1) {
            carouselControls = `
            <a class="carousel-control-prev" data-bs-target="#carousel-addtocart" data-bs-slide="prev" onclick="event.stopPropagation();"><span class="carousel-control-prev-icon"></span></a>
            <a class="carousel-control-next" data-bs-target="#carousel-addtocart" data-bs-slide="next" onclick="event.stopPropagation();"><span class="carousel-control-next-icon"></span></a>
            `;
        }

        console.log(thumb_content);



        let link = async_result.LINK;

        // if (async_result.LINK.substring(0,5) != "http") {
        //     link = "https://" + link;
        // }

        let link_thumb = imageDivs;

        let url_div = '';

        let btn_text = "Click here";

        if (localStorage.lang == 1) {
            btn_text = "Klik di sini";
        }

        if (link != null && link != "") {
            if (link.substring(0, 4) != "http") {
                link = "https://" + link;
            }
            console.log(link);
            // link_thumb = '<a href="' + link + '">' + thumb_content + '</a>';
            url_div = `
            <a class="btn btn-dark" onclick="goToLink('${link}');" style="font-size:12px;">${btn_text}</a>
            `;
        }

        let profpic = "";

        if (async_result.SHOP_THUMBNAIL != null && async_result.SHOP_THUMBNAIL.trim() != "") {
            profpic = "/filepalio/image/" + async_result.SHOP_THUMBNAIL;
        } else {
            profpic = "/nexilis/assets/img/ic_person_boy.png";
        }

        let f_pin = "";

        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get("f_pin");
        }

        let description = "";

        let descClean = richText(decodeURI(async_result.DESCRIPTION))

        if (descClean.includes("klik disini saja")) {
            console.log('ada cuy');
            description = descClean.replace("klik disini saja", '<a href="' + link + '" style="text-decoration: underline; color:blue;">klik disini saja</a>');
        } else {
            description = descClean;
        }

        // chec like & comment
        let like_src = "../assets/img/jim_likes.png?v=2";
        // if (likedPost != undefined && likedPost != null) {            
        if (likedPost.length > 0 && likedPost.includes(async_result.CODE)) {
            console.log(likedPost);
            like_src = "../assets/img/jim_likes_red.png";
        }
        // }

        let comment_src = "../assets/img/jim_comments.png?v=2";
        // if (commentedProducts != undefined && commentedProducts != null) {
        if (commentedProducts.length > 0 && commentedProducts.includes(async_result.CODE)) {
            console.log(commentedProducts);
            comment_src = "../assets/img/jim_comments_blue.png";
        }
        // }

        // <div class="col-9 d-flex align-items-center justify-content-start">
        let like_comment_div = `
        <div class="row">
            <div class="col-12 d-flex align-items-center justify-content-start">
                        <div class="d-flex align-items-center">
                        <div class="like-button" onclick="likeProduct('${async_result.CODE}', '1')">
                            <img draggable="false" id="like-${async_result.CODE}" src="${like_src}" height="25" width="25">
                            <div id="like-counter-${async_result.CODE}" class="like-comment-counter">${async_result.TOTAL_LIKES}</div>
                        </div>
                        <div class="comment-button">
                            <a onclick="openComment('${async_result.CODE}',1)">
                            <img draggable="false" class="comment-icon-${async_result.CODE}" src="${comment_src}" height="25" width="25">
                            </a>
                            <div class="like-comment-counter">${async_result.TOTAL_COMMENT}</div>
                        </div>
                        </div>
                        </div>
                    </div>
        `;

        let titleClean = richText(decodeURI(async_result.PRODUCT_NAME));

        // console.log('f_pin', f_pin);
        // console.log('async_result', async_result.F_PIN);

        // <div class="col-9 d-flex align-items-center justify-content-start">
        let title_div = `
        <div class="row">
            <div class="col-10 d-flex align-items-center justify-content-start">
                <div class="product-name m-0 fw-bold" style="font-size:13px;">${titleClean}</div>
            </div>
            <div class="col-2 d-flex align-items-center justify-content-end">
                <div class="product-name m-0 fw-bold" style="font-size:13px;">
                    <img draggable="false" class="${async_result.F_PIN != f_pin ? '' : 'd-none'}" src="../assets/img/warning.png?v=2" style="width: 25px; height: 25px" id="dropdownMenuSelectLanguage" data-bs-toggle="dropdown" aria-expanded="false"></img>
                    <ul class="dropdown-menu shadow-lg" style="min-width: auto !important; position: absolute; border: 1px solid black; z-index: 1000" aria-labelledby="dropdownMenuLanguage">
                    
                    <li id="report_content" onclick="reportContent('${async_result.CODE}','${async_result.REPORT}')"><a class="dropdown-item" data-translate="tab5listing-10">Report/flag Content</a></li>
                    <li id="report_user" onclick="reportUser('${async_result.F_PIN}')" ><a class="dropdown-item" data-translate="tab5listing-10">Report/flag User</a></li>
                    <li id="block_content" class="${isChangedProfile === "0" ? "d-none" : ""}" onclick="blockContent('${async_result.CODE}')"><button type="submit" style="color:brown" class="dropdown-item">Remove/Block Content</button></li>
                    <li id="block_user" class="${isChangedProfile === "0" ? "d-none" : ""}" onclick="blockUser('${async_result.F_PIN}')"><button type="submit" style="color:brown" class="dropdown-item" data-translate="tab5listing-11">Remove/Block User</button></li>
                  
                  </ul>
                </div>
            </div>
        </div>
        `;

        let is_paid = async_result.PRICING == 1;

        
        let verified_icon = "";
        if (async_result['OFFICIAL_ACCOUNT'] == 1 || async_result['OFFICIAL_ACCOUNT'] == 3) {
            verified_icon = '<img draggable="false" src="/nexilis/assets/img/ic_official_flag.webp" style="width:15px; height:15px;"/>';
          } else if (async_result['OFFICIAL_ACCOUNT'] == 2) {
            verified_icon = '<img draggable="false" src="/nexilis/assets/img/ic_verified_flag.png" style="width:15px; height:15px;"/>';
          }
        this.html =
            `<div class="container-fluid">
                <div class="col-12 px-0 mb-3">
                    <div class="addcart-img-container text-center">
                        <div id="carousel-addtocart" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
                            <div class="carousel-inner">
                                ${link_thumb}
                                ${carouselControls}
                            </div>
                        </div>
                        <img draggable="false" class="addcart-wishlist logo" src="${profpic}">
                        <span class="d-flex align-items-center addcart-wishlist name small-text">${verified_icon}&emsp;${async_result['SHOP_NAME']}</span>
                        <img draggable="false" class="addcart-wishlist star d-none" src="../assets/img/icons/wishlist-yellow.png" style="right:15px;">
                        <img draggable="false" class="addcart-wishlist more d-none" src="../assets/img/icons/More-white.png">
                    </div>
                </div>
            </div>

            <div class="container content-section">
                <div class="container">
                    <div class="row px-3">
                        
                        <div class="col-12">
                        ${window.location.href.includes('tab3-main') ? title_div+like_comment_div : title_div}
                        </div>
                        <div class="col-3 d-none d-flex align-items-center justify-content-end">
                            <img draggable="false" class="mx-1" src="../assets/img/icons/wishlist-yellow.png" width="20px"><div class="fs-6 fw-bold product-name">5.0</div>
                        </div>
                    </div>
                    <div class="row px-3 ${is_paid ? "" : "d-none"}">
                        <div class="col-8 d-flex align-items-center justify-content-start">
                            <h5 class="product-price fs-6 m-0">Rp ${async_result.PRICE.toLocaleString('en-US')}</h5>
                        </div>
                        <div class="col-4 d-flex align-items-center justify-content-end">
                            <h5 class="product-price small-text">1,1RB Terjual</h5>
                        </div>
                    </div>
                </div>

                <div class="container-fluid d-none mt-2 bg-white small-text fw-bold" style="color: #bbb">
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
                        ${url_div != '' ? url_div + '<br><br>' : ''}                    
                        ${description}
                        </div>
                    </div>

                    <div id="ratings" class="d-none m-3 ratings d-none d-flex align-items-center">
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

                <div class="container" id="comments-modal" style="margin-bottom: 5rem"> 
                ${comments}
                </div>
                
                <div class="col prod-addtocart ${is_paid ? "" : "d-none"}">
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
                </div>
            </div>
            `;

        this.parent = document.body;
        this.modal = document.getElementById('modal-add-body');
        this.modal.innerHTML = " ";

        this._createModal();
        // toggleVideoMute();
        playVid();
    }

    static async build(product_code) {
        let async_result = await getProductDetail(product_code);
        let commentsection = "";
        if (isChangedProfile == "1") {
            commentsection = await getComments(product_code);
        }
        return new Addtocart(async_result,commentsection);
    }

    static async buildPost(post_code) {
        let async_result = await getPostDetail(post_code);
        let commentsection = "";
        // if (isChangedProfile == "1") {
            commentsection = await getComments(post_code);
        // }
        return new Addtocart(async_result,commentsection);
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

    _createModal(code) {

        // Main text
        this.modal.innerHTML = this.html;

        // Let's rock
        $('#modal-addtocart').modal('show');

        if (window.Android) {
            window.Android.setIsProductModalOpen(true);
        }
        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
            window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
                param1: true
            });
        }
    }

    _destroyModal() {
        $('#modal-addtocart').modal('hide');

    }
}

function getComments(code) {
    // get comments

    return new Promise(function (resolve, reject) {
        let fpin = window.Android ? window.Android.getFPin() : new URLSearchParams(window.location.search).get("f_pin");
        let commentURL = "comment_modal.php?product_code=" + code + "&f_pin=" + fpin + "&is_post=1&icp=" + isChangedProfile + "&lang=" + localStorage.lang;
        console.log("code", code)
        console.log("ICP", isChangedProfile)
        xReply = 1;
        xReffReply = 10;
        var commentsection = "";
        // if (isChangedProfile == "1") {
        $.get(commentURL, function (data) {
            // console.log("fetch comment", data)
            // $('#comments-modal').html(data);
            resolve(data);
        });
        // }
    })

}


function hideAddToCart() {
    // $('#modal-addtocart').modal('hide');
    if ($('#modal-addtocart').hasClass('show')) {
        $('.modal.in').modal('hide')
        $('#modal-addtocart').modal('hide');
    } else if ($('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }
    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
        window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
            param1: false
        });
    }
}

async function showAddModal(product_code) {

    event.preventDefault();

    let add = await Addtocart.build(product_code);
    // let response = await add.question();

}

async function showAddModalPost(post_code) {
    event.preventDefault();
    console.log("SHOW", startPause);
    // if (startPause != 1) {
        let add = await Addtocart.buildPost(post_code);
    // }
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

// function goBack() {
//     if (window.Android) {
//         window.Android.closeView();
//     } else {
//         window.history.back();
//     }
// }

// FUNCTION REPORT

$(document).on('click', '.dropdown-menu', function (e) {
    e.stopPropagation();
});

function reportContent(product_id, report_count, checkIOS = false) {

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: product_id + '|' + report_count,
            param2: 'report_content'
        });
        return;
    }

    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');

    }

    // $('#modal-addtocart').modal('hide');
    // $('#modal-addtocart').addClass('d-none');
    $('#modal-category').modal('show');

    localStorage.setItem("report_post_id", product_id);
    localStorage.setItem("report_count", report_count);

};

function reportContentSubmit() {

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var f_pin = f_pin;
    var post_id = localStorage.getItem("report_post_id");
    var report_category = $('input[name="report_category"]:checked').val();
    var count_report = localStorage.getItem("report_count");

    var formData = new FormData();

    formData.append('f_pin', f_pin);
    formData.append('post_id', post_id);
    formData.append('report_category', report_category);
    formData.append('count_report', count_report);


    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);

            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report Content Berhasil");
                $('#modal-category').modal('hide');
                if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
                    $('#modal-product').modal('hide');
                }
                $('#modal-report-success').modal('show');
                // location.reload();
            } else {
                alert("Report Content Gagal");
            }
        }

    }

    xmlHttp.open("post", "../logics/report_content");
    xmlHttp.send(formData);

};

function reportUser(f_pin_reported, checkIOS = false) {

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        } else {

            // $('#modal-addtocart').modal('hide');
            // $('#modal-addtocart').addClass('d-none');
            $('#modal-category2').modal('show');

            localStorage.setItem("f_pin_reported", f_pin_reported);
        }
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: f_pin_reported,
            param2: 'report_user'
        });
        return;

    }

    console.log('aaa');
    // $('#modal-addtocart').modal('hide');
    // $('#modal-addtocart').addClass('d-none');
    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }
    $('#modal-category2').modal('show');

    localStorage.setItem("f_pin_reported", f_pin_reported);




};

function reportUserSubmit() {

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var f_pin_reported = localStorage.getItem("f_pin_reported");;
    var report_category = $('input[name="report_category"]:checked').val();
    var count_report = 1 + 1;

    formData.append('f_pin', f_pin);
    formData.append('f_pin_reported', f_pin_reported);
    formData.append('report_category', report_category);
    formData.append('count_report', count_report);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-category2').modal('hide');
                $('#modal-report-success').modal('show');
                // location.reload();
            } else {
                alert("Report User Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/report_user");
    xmlHttp.send(formData);

};

function blockUser(l_pin, checkIOS = false) {
    if (window.Android) {
        if (window.Android.checkProfile()) {
            f_pin = window.Android.getFPin();
        } else {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: l_pin,
            param2: 'block_user'
        });
        return;

    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }
    var formData = new FormData();

    var f_pin = f_pin;
    var l_pin = l_pin

    console.log("SSS", f_pin);
    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }

    formData.append('f_pin', f_pin);
    formData.append('l_pin', l_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');
                $('#modal-block-success').modal('show');

                // if (window.Android) {
                //     window.Android.blockContact(l_pin);
                // }

                if (window.Android) {

                    window.Android.blockContact(l_pin);

                } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockContact) {

                    window.webkit.messageHandlers.blockContact.postMessage({
                        param1: l_pin
                    });
                    return;

                }
                // location.reload();
            } else {
                alert("Block User Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/block_user");
    xmlHttp.send(formData);
};

function unblockUser(l_pin) {
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var l_pin = l_pin

    console.log("SSS", f_pin);

    formData.append('f_pin', f_pin);
    formData.append('l_pin', l_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');

                if (localStorage.lang == 0) {
                    $('#modal-block-success .modal-body>p').text('You unblocked this user.');
                    $("#close-blocked").text("Close");
                } else {
                    $('#modal-block-success .modal-body>p').text('Anda telah membuka blokir user ini.');
                    $("#close-blocked").text("Tutup");
                }
                $('#modal-block-success').modal('show');

                if (window.Android) {

                    window.Android.blockUser(l_pin, false);

                } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

                    window.webkit.messageHandlers.blockUser.postMessage({
                        param1: l_pin,
                        param2: false
                    });
                    return;

                }
                // location.reload();
            } else {
                alert("Block User Failed");
            }
        }
    }

    xmlHttp.open("post", "../logics/unblock_user");
    xmlHttp.send(formData);
}

function blockContent(postId, checkIOS = false) {

    var f_pin = "";

    if (window.Android) {
        if (window.Android.checkProfile()) {
            f_pin = window.Android.getFPin();
        } else {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: postId,
            param2: 'block_content'
        });
        return;

    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }
    var formData = new FormData();

    var f_pin = f_pin;
    var post_id = postId;
    var time = new Date();
    now = time.getTime();

    console.log("SSS", f_pin);
    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }

    formData.append('f_pin', f_pin);
    formData.append('post_id', post_id);
    formData.append('time', now);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Success") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');
                $('#modal-block-content-success').modal('show');

            } else {
                alert("Block Post Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/block_post");
    xmlHttp.send(formData);
};

function reloadPages() {
    $('#modal-report-success').modal('hide');
    $('#modal-addtocart').modal('hide');
    // $('#modal-addtocart').removeClass('d-none');
    // location.reload();
}

function reloadPagesBlock() {
    // $('#modal-block-content-success').modal('hide');
    location.reload();
}

// comment functions

function showAlert(word) {
    if (window.Android) {
        window.Android.showAlert(word);
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.showAlert) {
        window.webkit.messageHandlers.showAlert.postMessage({
            param1: word,
        });
        return;
    }
}


function commentProduct($productCode) {

    if (document.getElementById("input").value.trim() != '') {
        $('input:text').click(
            function () {
                $(this).val('');
            });
    } else {
        let str = "Write a comment..."
        if (localStorage.lang == 1) {
            str = "Isi komentar...";
        }
        showAlert(str);
        return;
    }

    //TODO send like to backend
    var f_pin = "";
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    if (f_pin != "") {
        var curTime = (new Date()).getTime();

        var formData = new FormData();

        var discussion_id = curTime.toString() + f_pin;

        formData.append('product_code', $productCode);
        formData.append('f_pin', f_pin);
        formData.append('last_update', curTime);
        formData.append('comment', document.getElementById("input").value.trim());
        formData.append('discussion_id', discussion_id);
        xReply++;

        let is_post = new URLSearchParams(window.location.search).get('is_post');
        formData.append('is_post', 1);

        if (!document.getElementById("reply-div").classList.contains("d-none")) {
            var commentId = getCookie("commentId");
            formData.append('reply_id', commentId);
            xReffReply = xReffReply + 10;
        }

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == 'Success Comment') {
                    if (!document.getElementById("reply-div").classList.contains("d-none")) {
                        deleteAllCookies();
                        $("#reply-div").addClass('d-none');
                        document.getElementById("content-comment").style.marginBottom = "3.5rem";
                    }
                    $('input#input').val('');
                    // location.reload();
                    appendComment(formData);
                    // window.scrollTo(0, document.body.scrollHeight);
                    updateScore($productCode, 'comment');
                }
            }
        }
        xmlHttp.open("post", "/nexilis/logics/comment_product");
        xmlHttp.send(formData);
    }
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

async function appendComment(formData) {
    let object = {};
    formData.forEach((value, key) => object[key] = value);

    // console.log("comment", object);   

    let date = new Date(parseInt(object.last_update));

    let time = date.getHours() + ":" + date.getMinutes();

    let commentId = object.f_pin + object.last_update;

    let profpic = await getProfpic(object.f_pin);
    let username = await getUserName(object.f_pin);

    let balas = "Reply";
    let hapus = "Delete";

    if (localStorage.lang == 0) {
        balas = "Reply";
        hapus = "Delete";
    } else if (localStorage.lang == 1) {
        balas = "Balas";
        hapus = "Hapus";
    }

    let fpin = window.Android ? window.Android.getFPin() : new URLSearchParams(window.location.search).get("f_pin");

    if (!object.hasOwnProperty('reply_id')) {

        let content_comment = document.getElementById('content-comment');

        let comment_html = `
        <div class="row mx-0 comments" id="${commentId}">
            <div class="commentId" style="display: none;">${commentId}</div>
            <div class="fPin" style="display: none;">${object.f_pin}</div>
            <div class="col-2">
            <img draggable="false" onclick="window.location.href='tab3-profile?f_pin=${fpin}&store_id=${object.f_pin}'" id="user-thumb-new-${xReply}" class="rounded-circle my-2" style="height:40px; width:40px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" src="${profpic}">
            </div>
            <div class="col-10 text-break">
            <div style="font-weight: bold;" class="mt-2 mb-1 mr-3">
                <span id="user-name-new-${xReply}">${username} </span>
                <span style="font-weight: 300;"> ${object.comment}</span>
            </div>
            <div style="font-weight: 100; color: grey;" class="my-1">${time}&emsp; <span style="font-weight: 300;" data-translate="comment-2" onclick="onReply(true,'user-name-new-${xReply}','${commentId}');">${balas}</span>&emsp;<span class="text-delete" style="font-weight: 300;" onclick="showSuccessModal('${commentId}');">${hapus}</span>
            </div>
            </div>
        </div>
        `;

        content_comment.innerHTML += comment_html;
    } else {

        let parent_id = object.reply_id;

        let comment_html = `
        <div class="row comments cmt-reply" id="${commentId}" style="width:100%;">
        <div class="commentId" style="display: none;">${commentId}</div>
        <div class="fPin" style="display: none;">${object.f_pin}</div>
        <div class="col-2">
          <img draggable="false" onclick="window.location.href='tab3-profile?f_pin=${fpin}&store_id=${object.f_pin}'" id="user-thumb-reff-new-${xReffReply}" class="rounded-circle my-2" style="height:40px; width:40px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" src="${profpic}">
        </div>
        <div class="col-10 text-break">
          <div style="font-weight: bold;" class="mt-2 mb-1 mr-3">
            <span id="user-name-reff-new-${xReffReply}">${username} </span>
            <span style="font-weight: 300;"> ${object.comment}</span>
          </div>
          <div style="font-weight: 100; color: grey;" class="my-1">${time}&emsp; <span data-translate="comment-2" style="font-weight: 300;" onclick="onReply(true,'user-name-reff-new-${xReffReply}','${commentId}');">${balas}</span>&emsp;<span class="text-delete" style="font-weight: 300;" onclick="showSuccessModal('${commentId}');">${hapus}</span>
          </div>
        </div>
      </div>
        `;

        console.log("parent", parent_id);
        console.log("this", commentId);

        let isLv1 = !$('.comments#' + parent_id).hasClass('cmt-reply');

        if (isLv1) {
            $('.comments#' + parent_id).append(comment_html);
        } else {
            $('.comments#' + parent_id).after(comment_html);
        }

    }


    enableDelete();
}

function openDelete(commentId) {
    return function curried_func(event) {
        event.preventDefault();
        event.stopPropagation();
        showSuccessModal(commentId, console.log(""));
    }
}

function enableDelete() {
    $('.first-comment').each(function () {
        if (!$(this).hasClass('is-deleted')) {
            let commentId = $(this).siblings('.commentId').text();
            let fPinContent = $(this).siblings('.fPin').text();
            var f_pin = '';
            try {
                // if (window.Android) {
                //     f_pin = window.Android.getFPin();
                // } else {
                f_pin = new URLSearchParams(window.location.search).get('f_pin');
                // }
            } catch (err) {

            }
            // var f_pin = "028a5119b2";
            if (fPinContent == f_pin) {
                $(this).unbind('contextmenu');
                $(this).contextmenu(function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    showSuccessModal(commentId, console.log(""));
                })
            } else {
                return;
            }
        }
    })

    console.log("woi brapa", $('.comments').length)
    $('.comments').each(function () {
        // if (!$(this).hasClass('is-deleted')) {
        let commentId = $(this).find('.commentId').text();
        let fPinContent = $(this).find('.fPin').text();
        var f_pin = '';
        try {
            // if (window.Android) {
            //     f_pin = window.Android.getFPin();
            // } else {
            f_pin = new URLSearchParams(window.location.search).get('f_pin');
            // }
        } catch (err) {

        }
        // var f_pin = "028a5119b2";
        if (fPinContent == f_pin) {
            $(this).unbind('contextmenu');
            $(this).contextmenu(function (e) {
                e.preventDefault();
                e.stopPropagation();
                showSuccessModal(commentId, console.log(""));
            })
        } else {
            return;
        }
        // }
    })

    // toggleProdDesc();
}

enableDelete();


async function showSuccessModal(commentId, method) {
    event.preventDefault();

    console.log("cmt id", commentId)
    $('body').css('overflow', 'hidden');
    this.myModal = new SuccessModal(commentId, method);

    try {
        const modalResponse = await myModal.question();
        if (modalResponse == "delete") {
            this.myModal._destroyModal();
            $(".comments#" + commentId).addClass("is-deleted")
        }
    } catch (err) {
        console.log(err);
    }
}

class SuccessModal {

    constructor(commentId, method) {
        if (localStorage.lang == 0) {
            this.modalTitle = "Are you sure you want to delete this comment?";
            this.acceptText = "Delete";
            this.cancelText = "Cancel";
        } else {
            this.modalTitle = "Yakin ingin menghapus komentar ini?";
            this.acceptText = "Hapus";
            this.cancelText = "Batal";
        }

        this.parent = document.body;
        this.commentId = commentId;
        this.method = method;

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

            this.acceptButton.addEventListener("click", () => {
                var formData = new FormData();

                formData.append('comment_id', this.commentId);
                let is_post = new URLSearchParams(window.location.search).get('is_post');
                formData.append('is_post', is_post);
                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function () {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                        if (xmlHttp.responseText == 'Success Delete Comment') {
                            // location.reload();
                            resolve("delete");
                        }
                    }
                }
                xmlHttp.open("post", "/nexilis/logics/delete_comment");
                xmlHttp.send(formData);
            });

            this.cancelButton.addEventListener("click", () => {
                this._destroyModal();
                resolve("destroy");
                $('body').css('overflow', 'auto');
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.setAttribute("style", "z-index: 1100;");
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
        titleText.style.marginLeft = "5px";
        titleText.style.marginRight = "5px";
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // // Main text
        // const text = document.createElement('span');
        // text.setAttribute("id", "payment-form");
        // text.classList.add('simple-modal-text');
        // text.innerHTML = this.status;
        // window.appendChild(text);

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
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

function onReply(condition, name, commentId) {
    if (condition) {
        $("#input").focus();
        finalName = document.getElementById(name).innerHTML;

        if (localStorage.lang == 0) {
            document.getElementById("content-reply").innerHTML = "Reply to " + finalName;
        } else if (localStorage.lang == 1) {
            document.getElementById("content-reply").innerHTML = "Balas ke " + finalName;
        }

        document.getElementById("content-comment").style.marginBottom = "3.5rem";
        document.cookie = "commentId=" + commentId;
        $("#reply-div").removeClass('d-none');

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        // sleep(500).then(() => {
        //     window.scrollTo(0, document.body.scrollHeight);
        // });
    } else {
        deleteAllCookies();
        $("#reply-div").addClass('d-none');
        document.getElementById("content-comment").style.marginBottom = "3.5rem";
    }
    // window.scrollTo(0, document.body.scrollHeight);
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}

function getDisplayName(fPin, index) {
    let name = fPin;
    try {
        if (window.Android) {
            name = window.Android.getDisplayName(fPin);
        }
    } catch (err) {}

    if (name == fPin) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var personData = JSON.parse(xmlHttp.responseText);
                if (personData.length > 0) {
                    var person = personData[0];

                    var first_name = person.FIRST_NAME;
                    var last_name = person.LAST_NAME;
                    var full_name = "";
                    if (last_name) {
                        full_name = first_name + " " + last_name;
                    } else {
                        full_name = first_name;
                    }

                    document.getElementById('user-name-' + index).innerHTML = full_name;
                }
            }
        }
        xmlHttp.open("get", "/nexilis/logics/fetch_person?f_pin=" + fPin);
        xmlHttp.send();
    } else {
        document.getElementById('user-name-' + index).innerHTML = name;
    }
}

function getUserName(fPin) {
    let name = fPin;

    return new Promise(function (resolve, reject) {
        try {
            if (window.Android) {
                name = window.Android.getDisplayName(fPin);
            }
        } catch (err) {}

        if (name == fPin) {
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    var personData = JSON.parse(xmlHttp.responseText);
                    if (personData.length > 0) {
                        var person = personData[0];

                        var first_name = person.FIRST_NAME;
                        var last_name = person.LAST_NAME;
                        var full_name = "";
                        if (last_name) {
                            full_name = first_name + " " + last_name;
                        } else {
                            full_name = first_name;
                        }

                        // document.getElementById(elementId).innerHTML = full_name;
                        // return full_name;

                        name = full_name;
                        resolve(name);
                    }
                }
            }
            xmlHttp.open("get", "/nexilis/logics/fetch_person?f_pin=" + fPin);
            xmlHttp.send();
        } else {
            resolve(name);
        }
    });
    // return name;
}

function getDisplayNameReff(fPin, sub, index) {
    let name = fPin;
    try {
        if (window.Android) {
            name = window.Android.getDisplayName(fPin);
        }
    } catch (err) {}

    if (name == fPin) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                var personData = JSON.parse(xmlHttp.responseText);
                if (personData.length > 0) {
                    var person = personData[0];

                    var first_name = person.FIRST_NAME;
                    var last_name = person.LAST_NAME;
                    var full_name = "";
                    if (last_name) {
                        full_name = first_name + " " + last_name;
                    } else {
                        full_name = first_name;
                    }

                    console.log("display", 'user-name-reff-' + sub + index);
                    document.getElementById('user-name-reff-' + sub + index).innerHTML = full_name;
                }
            }
        }
        xmlHttp.open("get", "/nexilis/logics/fetch_person?f_pin=" + fPin);
        xmlHttp.send();
    } else {
        document.getElementById('user-name-reff-' + sub + index).innerHTML = name;
    }
}

function getThumbId(fPin, index) {
    let thumb = '';
    try {
        if (window.Android) {
            thumb = window.Android.getImagePerson(fPin);
        }
    } catch (err) {}

    try {
        if (thumb == '') {
            thumb = '../assets/img/ic_person_boy.png';
            document.getElementById('user-thumb-' + index).src = thumb;

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    var personData = JSON.parse(xmlHttp.responseText);
                    if (personData.length > 0) {
                        var person = personData[0];
                        if (person.IMAGE) {
                            thumb = '/filepalio/image/' + person.IMAGE;
                        }
                        document.getElementById('user-thumb-' + index).src = thumb;
                    }
                }
            }
            xmlHttp.open("get", "/nexilis/logics/fetch_person?f_pin=" + fPin);
            xmlHttp.send();
        } else {
            thumb = '/filepalio/image/' + thumb;
            document.getElementById('user-thumb-' + index).src = thumb;
        }
    } catch (e) {
        console.log("getthumbid", e)
    }
}

function getThumbIdReff(fPin, sub, index) {
    let thumb = '';
    try {
        if (window.Android) {
            thumb = window.Android.getImagePerson(fPin);
        }
    } catch (err) {}

    try {
        if (thumb == '') {
            thumb = '../assets/img/ic_person_boy.png';
            document.getElementById('user-thumb-reff-' + sub + index).src = thumb;

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    var personData = JSON.parse(xmlHttp.responseText);
                    if (personData.length > 0) {
                        var person = personData[0];
                        if (person.IMAGE) {
                            thumb = '/filepalio/image/' + person.IMAGE;
                        }
                        document.getElementById('user-thumb-reff-' + sub + index).src = thumb;
                    }
                }
            }
            xmlHttp.open("get", "/nexilis/logics/fetch_person?f_pin=" + fPin);
            xmlHttp.send();
        } else {
            thumb = '/filepalio/image/' + thumb;
            document.getElementById('user-thumb-reff-' + sub + index).src = thumb;
        }
    } catch (e) {
        console.log("getthumbidreff", e)
    }
}

function getProfpic(fPin) {
    let thumb = '';

    return new Promise(function (resolve, reject) {
        try {
            if (window.Android) {
                thumb = window.Android.getImagePerson(fPin);
            }
        } catch (err) {}
        if (thumb == '') {
            thumb = '../assets/img/ic_person_boy.png';
            // document.getElementById('user-thumb-' + index).src = thumb;

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    var personData = JSON.parse(xmlHttp.responseText);
                    if (personData.length > 0) {
                        var person = personData[0];
                        if (person.IMAGE) {
                            thumb = '/filepalio/image/' + person.IMAGE;
                        }
                        // document.getElementById(elementId).src = thumb;
                        // return thumb;

                        console.log(thumb);
                        resolve(thumb);
                    }
                }
            }
            xmlHttp.open("get", "/nexilis/logics/fetch_person?f_pin=" + fPin);
            xmlHttp.send();
        } else {
            thumb = '/filepalio/image/' + thumb;
            // document.getElementById('user-thumb-' + index).src = thumb;
            // return thumb;
            resolve(thumb);
        }
    });
    // return thumb;
}

function onFocusInput() {
    if (window.Android) {
        try {
            window.Android.onFocusInput();
        } catch (e) {

        }
    }
}