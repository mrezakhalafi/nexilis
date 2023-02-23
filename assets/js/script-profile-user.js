var data = [];
var dataFiltered = [];
var productImageMap = new Map();
var productImageStateMap = new Map();

let f_pin = "";

var ua = window.navigator.userAgent;
var palioBrowser = !!ua.match(/PalioBrowser/i);
var isChrome = !!ua.match(/Chrome/i);

if (window.Android) {
    f_pin = window.Android.getFPin();
} else {
    f_pin = new URLSearchParams(window.location.href).get("f_pin");
}

var grid_stack = GridStack.init({
    float: false,
    disableOneColumnMode: true,
    column: 3,
    margin: 2.5,
});

function getExtension(filename) {
    var parts = filename.split('.');
    return parts[parts.length - 1];
}

function isVideo(filename) {
    var ext = getExtension(filename);
    switch (ext.toLowerCase()) {
        case 'm4v':
        case 'avi':
        case 'mpg':
        case 'mp4':
            // etc
            return true;
    }
    return false;
}

let limit = 18;
let offset = 0;
let busy = false;

function gridCheck(arr, id) {
    const found = arr.some(el => el.id === id);
    return found;
}

var gridElements = [];
var fillGridStack = function ($grid, lim, off) {
    gridElements = [];
    let fpin = "";
    if (!fpin) {
        if (window.Android) {
            try {
                fpin = window.Android.getFPin();
            } catch (error) {

            }
        } else {
            fpin = new URLSearchParams(window.location.search).get("f_pin");
        }
    }
    dataFiltered.slice(off, lim + 1).forEach((element, i) => {
        var size = 1;
        var imageDivs = '';
        var imageArray = productImageMap.get(element.POST_ID);
        var delay = Math.floor(Math.random() * (5000)) + 5000;
        if (imageArray) {
            imageArray.forEach((image, j) => {
                if (image.substr(0, 4) != "http") {
                    image = '/nexilis/images/' + image;
                }
                if (isVideo(image) && j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><div class="center-crop-img"><video muted autoplay loop class="content-image"><source src="' + image + '"></video></div></div>';
                    j++;
                } else if (isVideo(image)) {
                    imageDivs = imageDivs + '<div class="carousel-item"><div class="center-crop-img"><video muted loop autoplay class="content-image"><source src="' + image + '"></video></div></div>';
                } else if (j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><div class="center-crop-img"><img class="content-image" src="' + image + '"/></div></div>';
                    j++;
                } else {
                    imageDivs = imageDivs + '<div class="carousel-item"><div class="center-crop-img"><img class="content-image" src="' + image + '"/></div></div>';
                }
            });
            var computed =
                // '<a href="#" data-bs-toggle="modal" data-bs-target="#modal-product">' + 
                // '<div class="inner" onclick="location.href=\'tab1-main?store_id=' + element.STORE_CODE + (fpin ? ('&f_pin=' + fpin) : '') + '#product-' + element.POST_ID + '\';">' +
                '<div class="inner" onclick="showProductModal(\'' + element.POST_ID + '\', ' + element.IS_PRODUCT + ');">' +
                '<div id="store-carousel-' + element.POST_ID + '" class="carousel slide pointer-event" ' +
                (imageArray.length > 1 ? ('data-ride="carousel" data-interval="' + delay + '"') : ('')) +
                ' data-touch="true">' +
                '<div class="carousel-inner">' +
                imageDivs +
                '</div>' +
                '</div>' +
                '</div>';
            gridElements.push({
                id: element.ID,
                minW: size,
                minH: size,
                maxW: size,
                maxH: size,
                content: computed
            });
        }
    });
    $('#loading').addClass('d-none');
    grid_stack.removeAll();
    grid_stack.load(gridElements, true);
    // grid_stack.commit();
    if (dataFiltered.length == 0) {
        $('#no-stores').removeClass('d-none');
    } else {
        $('#no-stores').addClass('d-none');
    }
    $('.carousel').each(function () {
        $(this).carousel();
        setTimeout(() => {
            $(this).carousel('next');
        }, Math.floor(Math.random() * (1000)) + 1000);
    });
    checkVideoCarousel();
    checkCarousel();
    correctVideoCrop();
    correctImageCrop();
};

var fillGridWidgets = function ($grid, lim, off) {
    let start = off;
    let end = off + lim;
    let fpin = new URLSearchParams(window.location.search).get("f_pin");
    if (!fpin) {
        if (window.Android) {
            try {
                fpin = window.Android.getFPin();
            } catch (error) {

            }
        } else {
            fpin = '';
        }
    }

    let batch = dataFiltered.slice(start + 1, end);

    batch.forEach((element, i) => {
        var size = 1;
        var imageDivs = '';
        var imageArray = productImageMap.get(element.POST_ID);
        var delay = Math.floor(Math.random() * (5000)) + 5000;
        if (imageArray) {
            imageArray.forEach((image, j) => {
                if (isVideo(image) && j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><video muted autoplay loop class="content-image"><source src="' + image + '"></video></div>';
                    j++;
                } else if (isVideo(image)) {
                    imageDivs = imageDivs + '<div class="carousel-item"><video muted loop autoplay class="content-image"><source src="' + image + '"></video></div>';
                } else if (j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><img class="content-image" src="' + image + '"/></div>';
                    j++;
                } else {
                    imageDivs = imageDivs + '<div class="carousel-item"><img class="content-image" src="' + image + '"/></div>';
                }
            });
            var computed =
                // '<a href="#" data-bs-toggle="modal" data-bs-target="#modal-product">' + 
                // '<div class="inner" onclick="location.href=\'tab1-main?store_id=' + element.STORE_CODE + (fpin ? ('&f_pin=' + fpin) : '') + '#product-' + element.POST_ID + '\';">' +
                '<div class="inner" onclick="showProductModal(\'' + element.POST_ID + '\');">' +
                '<div id="store-carousel-' + element.POST_ID + '" class="carousel slide pointer-event" ' +
                (imageArray.length > 1 ? ('data-ride="carousel" data-interval="' + delay + '"') : ('')) +
                ' data-touch="true">' +
                '<div class="carousel-inner">' +
                imageDivs +
                '</div>' +
                '</div>' +
                '</div>';
            if (!gridCheck(gridElements, element.POST_ID)) {
                gridElements.push({
                    id: element.POST_ID,
                    minW: size,
                    minH: size,
                    maxW: size,
                    maxH: size,
                    content: computed
                });
                grid_stack.addWidget({
                    id: element.POST_ID,
                    minW: size,
                    minH: size,
                    maxW: size,
                    maxH: size,
                    content: computed
                });
            }
        }
    });
    grid_stack.compact();
    busy = false;
    // grid_stack.commit();
    if (dataFiltered.length == 0) {
        $('#no-stores').removeClass('d-none');
    } else {
        $('#no-stores').addClass('d-none');
    }
    $('.carousel').each(function () {
        $(this).carousel();
        setTimeout(() => {
            $(this).carousel('next');
        }, Math.floor(Math.random() * (1000)) + 1000);
    });
    checkVideoCarousel();
    checkCarousel();
    correctVideoCrop();
    correctImageCrop();
};

function correctVideoCrop() {
    var videos = document.querySelectorAll("video.content-image");
    videos.forEach(function (elem) {
        elem.addEventListener("loadedmetadata", function () {
            if (elem.videoWidth > elem.videoHeight) {
                elem.classList.add("landscape");
            }
        })
    })
}

function correctImageCrop() {
    var images = document.querySelectorAll("img.content-image");
    images.forEach(function (elem) {
        elem.addEventListener("load", function () {
            if (elem.width > elem.height) {
                elem.classList.add("landscape");
            }
        })
    })
}

function checkVideoCarousel() {
    // play video when active in carousel
    $(".carousel").on("slid.bs.carousel", function (e) {
        if ($(this).find("video").length) {
            if ($(this).find(".carousel-item").hasClass("active")) {
                $(this).find("video").get(0).play();
            } else {
                $(this).find("video").get(0).pause();
            }
        }
    });
}

var visibleCarousel = new Set();

function checkCarousel() {
    $('.carousel').each(function () {
        if ($(this).is(":in-viewport")) {
            if (!visibleCarousel.has($(this).attr('id'))) {
                visibleCarousel.add($(this).attr('id'));
                $(this).carousel('cycle');
            }
        } else {
            if (visibleCarousel.has($(this).attr('id'))) {
                visibleCarousel.delete($(this).attr('id'));
                $(this).carousel('pause');
            }
        }
    });
}

// window.onscroll = function () {
//     scrollFunction();
//     checkVideoCarousel();
// };

function scrollFunction() {
    if ($(document).scrollTop() > 20) {
        $("#scroll-top").css('display', 'block');
    } else {
        $("#scroll-top").css('display', 'none');
    }
}

function topFunction() {
    $(document).scrollTop(0);
}

var storeData = null;

function openStore($store_code, $store_link) {
    if (window.Android) {
        if (storeData) {
            window.Android.openStore(storeData);
        }
    } else {
        window.location.href = $store_link;
    }
}

function fetchStoreData() {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let dataStore = JSON.parse(xmlHttp.responseText);
            storeData = JSON.stringify(dataStore[0]);

            try {
                if (window.Android) {
                    window.Android.setCurrentStoreData(storeData);
                }
            } catch (err) {
                console.log(err);
            }
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_stores_specific?store_id=" + store_code);
    xmlHttp.send();
}

function visitStore($store_code, $f_pin, $is_entering) {
    var formData = new FormData();

    formData.append('store_code', $store_code);
    formData.append('f_pin', $f_pin);
    formData.append('flag_visit', ($is_entering ? 1 : 0));

    if ($store_code && $f_pin) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                // console.log(xmlHttp.responseText);
            }
        }
        xmlHttp.open("post", "/nexilis/logics/visit_store");
        xmlHttp.send(formData);
    }
}

function goBack() {
    // if (window.Android) {
    //     window.Android.closeView();
    // } else {
    //     window.history.back();
    // }
    let isGrid = localStorage.getItem('is_grid');
    if (window.Android) {
        // window.Android.goBack();
        if (document.referrer != '' && document.referrer != null) {
            window.location = document.referrer;
        } else {
            // window.location = 'tab1-main.php?f_pin=' + window.Android.getFPin();
            if (isGrid != null) {
                if (isGrid == "1") {
                    window.location = 'tab3-main.php?f_pin=' + window.Android.getFPin();
                } else {
                    window.location = 'tab1-main.php?f_pin=' + window.Android.getFPin();
                }
            } else {
                window.location = document.referrer;
            }
        }
    } else {
        if (document.referrer != '' && document.referrer != null) {
            window.location = document.referrer;
        } else {
            // window.location = 'tab1-main.php?f_pin=<?php echo $id_shop; ?>';
            if (isGrid != null) {
                if (isGrid == "1") {
                    window.location = 'tab3-main.php?f_pin=' + window.Android.getFPin();
                } else {
                    window.location = 'tab1-main.php?f_pin=' + window.Android.getFPin();
                }
            } else {
                window.location = document.referrer;
            }
        }
    }
}

function pullRefresh() {
    if (window.Android && $(window).scrollTop() == 0) {
        window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
    }
}

function ext(url) {
    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
}

function updateCartCounter() {
    let counter_badge = 0;
    if (localStorage.getItem("cart") != null) {
        var cart = JSON.parse(localStorage.getItem("cart"));
    } else {
        var cart = [];
    }
    cart.forEach(item => {
        item.items.forEach(item => {
            counter_badge += parseInt(item.itemQuantity);
        })
    })
    if (counter_badge != 0) {
        $('#cart-counter').removeClass('d-none');
        $('#cart-counter').html(counter_badge);
    } else {
        $('#cart-counter').addClass('d-none');
    }
}

$(function () {
    fetchStoreData();
    fetchProducts();
    updateCounter();
    // fillGridStack('#content-grid');
    // PullToRefresh.init({
    //     mainElement: 'body',
    //     onRefresh: function () {
    //         window.location.reload();
    //     }
    // });

    let prevStore = sessionStorage.getItem("currentStore");
    let curStore = new URLSearchParams(window.location.search).get("store_id");
    sessionStorage.setItem("currentStore", curStore);

    if (prevStore != curStore || prevStore == null) {
        sessionStorage.setItem("profileTabPos", 0);
        $(".tab-pane#timeline").addClass("show active");
        $(".nav-link#timeline-tab").addClass("active");
        $(".tab-pane#profile").removeClass("show active");
        $(".nav-link#profile-tab").removeClass("active");
    } else {
        let profileTabPos = sessionStorage.getItem("profileTabPos");
        if (profileTabPos != null) {
            if (profileTabPos == 0) {
                $(".tab-pane#timeline").addClass("show active");
                $(".nav-link#timeline-tab").addClass("active");
                $(".tab-pane#profile").removeClass("show active");
                $(".nav-link#profile-tab").removeClass("active");
            } else {
                $(".tab-pane#timeline").removeClass("show active");
                $(".nav-link#timeline-tab").removeClass("active");
                $(".tab-pane#profile").addClass("show active");
                $(".nav-link#profile-tab").addClass("active");
            }
        } else {
            // console.log("no pos set");
            $(".tab-pane#timeline").addClass("show active");
            $(".nav-link#timeline-tab").addClass("active");
            $(".tab-pane#profile").removeClass("show active");
            $(".nav-link#profile-tab").removeClass("active");
        }
    }

    if (window.Android) {
        window.Android.setCurrentStore(store_code, be_id);

        var isInternal = false;
        try {
            isInternal = window.Android.getIsInternal();
        } catch (error) {}

        if (isInternal) {
            $("#gear").removeClass("d-none");
            $('#header').click(function () {
                if (window.Android) {
                    let curStore = new URLSearchParams(window.location.search).get("store_id");
                    window.Android.openStoreAdminMenu(curStore);
                }
            });
        } else {
            $("#gear").addClass("d-none");
        }
    }

    $('#addtocart-success').on('hide.bs.modal', function () {
        updateCounter();
    })
});

$(".nav-link#timeline-tab").click(function () {
    sessionStorage.setItem("profileTabPos", 0);
});

$(".nav-link#profile-tab").click(function () {
    sessionStorage.setItem("profileTabPos", 1);
});

let productArr = [];

function fetchProducts() {
    // var formData = new FormData();
    // formData.append('f_pin', localStorage.F_PIN);

    let f_pin = "";
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    localStorage.setItem('save_f_pin', f_pin);

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            data = JSON.parse(xmlHttp.responseText);
            console.log(data);
            productArr = data;
            // $('#post-count').text(data.length);
            data.forEach(productEntry => {
                if (!productEntry.THUMB_ID.startsWith("http")) {
                    var root = 'http://' + location.host;
                }
                // console.log(productEntry.THUMB_ID);
                var thumbs = productEntry.THUMB_ID.split("|");
                console.log(thumbs)
                thumbs.forEach(image => {
                    // console.log(image);
                    if (!productImageMap.has(productEntry.POST_ID) && image != null && image.trim() != "") {
                        productImageMap.set(productEntry.POST_ID, [image]);
                    } else if (image != null && image.trim() != "") {
                        productImageMap.set(productEntry.POST_ID, productImageMap.get(productEntry.POST_ID).concat([image]));
                    }
                });
            });
            dataFiltered = [];
            dataFiltered = dataFiltered.concat(data);
            fillGridStack('#content-grid', limit, offset);

            try {
                if (window.Android) {
                    window.Android.setCurrentProductsData(xmlHttp.responseText);
                }
            } catch (err) {
                console.log(err);
            }
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_posts_profile?f_pin=" + store_id);
    xmlHttp.send();
}


function changeStoreSettings($newSettings) {
    let dataStoreSettings = JSON.parse($newSettings);

    if (dataStoreSettings.STORE == null || dataStoreSettings.IS_SHOW == null) {
        showAlert("Gagal mengubah pengaturan. Coba lagi nanti.")
        return;
    }

    $store_code = dataStoreSettings.STORE;
    $is_show = dataStoreSettings.IS_SHOW;

    var formData = new FormData();

    formData.append('store_code', $store_code);
    formData.append('is_show', $is_show);

    if ($store_code) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4) {
                if (xmlHttp.status == 200) {
                    showAlert("Berhasil mengubah pengaturan.");
                    fetchStoreData();
                } else {
                    showAlert("Gagal mengubah pengaturan. Coba lagi nanti.");
                }
            }
        }
        xmlHttp.open("post", "/nexilis/logics/change_store_settings");
        xmlHttp.send(formData);
    }
}

function changeStoreShowcaseSettings($newSettings) {
    $dataShowcaseSettings = JSON.parse($newSettings);

    if ($dataShowcaseSettings == null) {
        showAlert("Gagal mengubah pengaturan. Coba lagi nanti.")
        return;
    }

    var settingsData = "";
    $dataShowcaseSettings.forEach(store_setting => {
        var storeSettingsData = "".concat(store_setting["PRODUCT_CODE"], "~", store_setting["IS_SHOW"]);
        if (settingsData == "") {
            settingsData = storeSettingsData;
        } else {
            settingsData = settingsData.concat(",", storeSettingsData);
        }
    });

    var formData = new FormData();

    formData.append('settings_data', settingsData);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.status == 200) {
                showAlert("Berhasil mengubah pengaturan.");
                fetchProducts();
            } else {
                showAlert("Gagal mengubah pengaturan. Coba lagi nanti.");
            }
        }
    }
    xmlHttp.open("post", "/nexilis/logics/change_store_showcase_settings");
    xmlHttp.send(formData);
}

function showAlert(word) {
    if (window.Android) {
        window.Android.showAlert(word);
    } else {
        console.log(word);
    }
}

function numberWithCommas(x) {
    // return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    return x.toLocaleString();
}

function openDetailProduct(pr) {
    let getPr = productArr.filter(prod => prod.POST_ID == pr)[0];

    $('#modal-addtocart .addcart-img-container').html('');
    $('#modal-addtocart .product-name').html('');
    $('#modal-addtocart .product-price').html('');
    $('#modal-addtocart .prod-details .col-11').html('');

    // console.log(getPr);

    let product_imgs = getPr.THUMB_ID.split('|');
    let product_name = getPr.NAME;
    let product_price = numberWithCommas(getPr.PRICE);
    let product_desc = getPr.DESCRIPTION;

    // console.log(product_imgs);
    // console.log(product_price);
    // console.log(product_desc);

    let product_showcase = "";

    // video
    //   <div class="video-wrap"><video muted="" class="myvid" preload="metadata"
    //         poster="http://202.158.33.26/nexilis/images/Kembang_Goyang_Khas_Betawi.webp">
    //         <source src="http://202.158.33.26/nexilis/images/Kembang_Goyang_Khas_Betawi.mp4" type="video/mp4"></video>
    //     <div class="timeline-product-tag-video"><img src="../assets/img/icons/Tagged-Product.png"></div>
    //     <div class="video-sound"><img src="../assets/img/video_mute.png"></div>
    //     <div class="video-play"><img src="../assets/img/video_play.png"></div>
    // </div>

    // if (product_imgs.length == 1) {
    let extension = ext(product_imgs[0]);
    if (extension == ".jpg" || extension == ".png" || extension == ".webp") {
        product_showcase = `<img class="product-img" src="${product_imgs[0]}">`;
    } else if (extension == ".mp4" || extension == ".webm") {
        let poster = product_imgs[0].replace(extension, ".webp");
        product_showcase = `
        <div class="video-wrap"><video muted="" class="myvid" preload="metadata"
                poster="${poster}">
                <source src="${product_imgs[0]}" type="video/mp4"></video>
        </div>
        `;
    }
    // } 

    let followSrc = "../assets/img/icons/Wishlist-(White).png";
    if (isFollowed == 1) {
        followSrc = "../assets/img/icons/Wishlist-fill.png";
    }

    product_showcase += `
    <hr id="drag-this">
    <img id="btn-wishlist" class="addcart-wishlist follow-icon-${getPr.SHOP_CODE}" onclick="followStore('${getPr.SHOP_CODE}','${f_pin}')" src="${followSrc}">`;

    $('#modal-addtocart .addcart-img-container').html(product_showcase);
    $('#modal-addtocart .product-name').html(product_name);
    $('#modal-addtocart .product-price').html('Rp ' + product_price);
    $('#modal-addtocart .prod-details .col-11').html(product_desc);
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

function hideAddToCart() {
    $('#modal-addtocart').modal('hide');
}

function pauseAll() {
    $('video.content-image').each(function () {
        $(this).get(0).pause();
    })
}

function resumeAll() {
    $('video.content-image').each(function () {
        $(this).get(0).play();
    })
    updateCounter();
}

function addToCartModal() {
    /* start handle detail product popup */
    const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
    const fixedPos = JSON.parse(JSON.stringify(initPos));

    let product_id = "";

    let init = parseInt(fixedPos.replace('px', ''));

    $('#modal-addtocart .modal-dialog').draggable({
        handle: ".modal-content",
        axis: "y",
        drag: function (event, ui) {

            // Keep the left edge of the element
            // at least 100 pixels from the container
            if (ui.position.top < init) {
                ui.position.top = init;
            }

            let dialog = ui.position.top + window.innerHeight;
            if (dialog - window.innerHeight > 150) {
                $('#modal-addtocart').modal('hide');
            }
        }
    })

    var ua = window.navigator.userAgent;
    var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
    var webkit = !!ua.match(/WebKit/i);
    var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);

    $('[data-bs-target="#modal-addtocart"]').click(function () {
        $('#modal-addtocart .modal-dialog').css('top', fixedPos);
        $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
        let bottomPos = parseInt(fixedPos.replace('px', '')) + 25;
        if (iOSSafari || iOS) {
            console.log('is iOS/apple');
            bottomPos = parseInt(fixedPos.replace('px', '')) + 90;
        }
        $('#modal-addtocart .prod-addtocart').css('bottom', bottomPos + 'px');
        let getPrId = $(this).attr('id').split('-')[1];
        product_id = getPrId;
        showAddModal(product_id);
    })



    $('#modal-addtocart').on('shown.bs.modal', function () {
        $('.modal').css('overflow', 'hidden');
        $('.modal').css('overscroll-behavior-y', 'contain');
        pullRefresh();
        // pauseAllVideo();
        playModalVideo();

        if (window.Android) {
            window.Android.setIsProductModalOpen(true);
        }
    })

    $('#modal-addtocart').on('hidden.bs.modal', function () {
        $('.modal').css('overflow', 'auto');
        $('.modal').css('overscroll-behavior-y', 'auto');
        pullRefresh();
        // checkVideoViewport();
        $('#modal-addtocart .addcart-img-container').html('');
        $('#modal-addtocart .product-name').html('');
        $('#modal-addtocart .product-price').html('');
        $('#modal-addtocart .prod-details .col-11').html('');

        if (window.Android) {
            window.Android.setIsProductModalOpen(false);
        }
    })



    $('#add-to-cart').click(function () {
        let itemQty = parseInt($('#modal-item-qty').val());
        addToCart(product_id, itemQty);
    })
}

// function goBack() {
//     if (window.Android) {
//         window.Android.closeView();
//     } else {
//         window.history.back();
//     }
// }

function changeProfileTab(tab_name) {
    event.preventDefault();
    posts = document.getElementById('posts');
    shop = document.getElementById('shop');
    posts_tab = document.getElementById('posts-tab');
    shop_tab = document.getElementById('shop-tab');
    if (tab_name == 'posts') {
        posts.classList.remove('d-none');
        shop.classList.add('d-none');
        posts_tab.classList.add('active')
        shop_tab.classList.remove('active');
    } else {
        posts.classList.add('d-none');
        shop.classList.remove('d-none');
        posts_tab.classList.remove('active')
        shop_tab.classList.add('active');
    }
}

function followStore($storeCode, f_pin) {
    let follower = document.getElementById('follower-count').innerText;
    console.log('follower', follower);
    let count = parseInt(follower);

    let followBtn = "Follow";
    let unfollowBtn = "Unfollow";
    if (localStorage.lang == 1) {
        followBtn = "Ikuti";
        unfollowBtn = "Berhenti Mengikuti"
    }

    if (isFollowed == 1) {
        isFollowed = 0;
        $('#btn-follow').text(followBtn);
        $('#modal-addtocart #btn-wishlist').attr("src", "../assets/img/icons/Wishlist.png");
        count -= 1;
        if (count == 0) {
            count = 0;
        }
    } else {
        isFollowed = 1;
        $('#btn-follow').text(unfollowBtn);
        $('#modal-addtocart #btn-wishlist').attr("src", "../assets/img/icons/Wishlist-fill.png");
        count += 1;
    }
    console.log('count', count);

    //TODO send like to backend
    if (window.Android) {
        f_pin = window.Android.getFPin();
    }

    var curTime = (new Date()).getTime();

    var formData = new FormData();

    formData.append('store_code', $storeCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_follow', (isFollowed == 1 ? 1 : 0));

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            // console.log(xmlHttp.responseText);
            updateScoreShop($storeCode);
            document.getElementById('follower-count').innerText = count;
        }
    }
    xmlHttp.open("post", "/nexilis/logics/follow_store");
    xmlHttp.send(formData);

}

function eraseQuery() {
    $("#delete-query").click(function () {
        $('#searchFilterForm-a input#query').val('');
        $('#delete-query').addClass('d-none');
    })

    $('#searchFilterForm-a input#query').keyup(function () {
        console.log('is typing: ' + $(this).val());
        if ($(this).val() != '') {
            $('#delete-query').removeClass('d-none');
        } else {
            $('#delete-query').addClass('d-none');
        }
    })
}

function resetSearch() {
    $('#searchFilterForm-a input#query').val('');
}

// SHOW PRODUCT FUNCTIONS
function getProductThumbs(product_code, is_product) {
    let formData = new FormData();
    formData.append("product_id", product_code);
    formData.append("is_product", is_product);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/nexilis/logics/get_product_thumbs");

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve({
                    thumb_id: JSON.parse(xhr.response).THUMB_ID,
                    name: JSON.parse(xhr.response).NAME,
                    description: JSON.parse(xhr.response).DESCRIPTION,
                    CODE: JSON.parse(xhr.response).CODE
                });
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

function toggleVideoMute(code) {
    console.log(code);
    let videoWrap = document.getElementById(code);
    let videoElement = videoWrap.querySelector('video');
    // console.log(videoElement);

    console.log('#' + code + ' .video-sound img');
    let muteIcon = document.querySelector('#' + code + ' .video-sound img');

    if (videoElement.muted) {
        videoElement.muted = false;
        muteIcon.src = "../assets/img/video_unmute.png";
    } else {
        videoElement.muted = true;
        muteIcon.src = "../assets/img/video_mute.png";
    }

    console.log(code + ' ' + videoElement.muted);
}

function playVid(code) {
    // $("div.video-play").each(function () {
    //     $(this).unbind('click');
    //     $(this).click(function (e) {
    //         e.stopPropagation();
    //         $(this).parent().find("video.myvid").get(0).play();
    //         $(this).addClass("d-none");
    //     })
    // })
    let videoWrap = document.querySelector('#videowrap-modal-' + code);
    let playButton = video.querySelector('.video-play');
    let video = video.querySelector('video');

    playButton.addEventListener('click', (e) => {
        if (video.paused) {
            video.play();
            playButton.classList.add('d-none');
        }
    });
}

let video_arr = ['webm', 'mp4'];
let img_arr = ['png', 'jpg', 'webp', 'gif'];

class ShowProduct {

    constructor(async_result) {

        let thumbs = async_result.thumb_id.split('|');
        let name = async_result.name;
        let description = async_result.description;

        let content = '';
        let domain = '';



        if (thumbs.length == 1) {
            let type = ext(thumbs[0]);
            if (video_arr.includes(type)) {
                // content = `
                //     <video muted autoplay loop class="d-block w-100">
                //     <source src="../images/${thumbs[0]}#t=0.5" type="video/${type}">
                //     </video>
                // `;
                content = `
                <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                <video class="myvid" autoplay muted playsinline>
                <source src="${thumbs[0].includes("http") ? thumbs[0] : domain + "/nexilis/images/" + thumbs[0]}">
                </video>
                <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                <img src="../assets/img/video_mute.png" />
                </div>
                <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                '<img src="../assets/img/video_play.png" />
                </div>
                </div>
                `;
            } else if (img_arr.includes(type)) {
                content = `
                    <img src="../images/${thumbs[0]}" class="d-block w-100">
                `;
            }
        } else {
            content = `
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
            `;

            let filteredThumbs = thumbs.filter(thumb => thumb.trim() != '' || thumb.length > 0);

            filteredThumbs.forEach((th, idx) => {
                if (th.trim() != '') {
                    content += `<div class="carousel-item${idx == 0 ? ' active' : ''}">`;

                    let type = ext(th);
                    if (video_arr.includes(type)) {
                        //     content += `
                        //     <video autoplay muted class="d-block w-100">
                        //     <source src="${th.substr(0,4) == "http" ? th : 'https://qmera.io/nexilis/images/' + th}#t=0.5" type="video/${type}">
                        //     </video>
                        // `;
                        content = `
                        <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                        <video class="myvid" autoplay muted playsinline>
                        <source src="${thumbs[0].includes("http") ? thumbs[0] : domain + "/nexilis/images/" + thumbs[0]}">
                        </video>
                        <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                        <img src="../assets/img/video_mute.png" />
                        </div>
                        <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                        '<img src="../assets/img/video_play.png" />
                        </div>
                        </div>
                        `;
                    } else if (img_arr.includes(type)) {
                        content += `
                        <img src="${th.substr(0,4) == "http" ? th : location.protocol + '//' + location.host + '/nexilis/images/' + th}" class="d-block w-100">
                    `;
                    }

                    content += `</div>`;
                }
            })

            if (filteredThumbs.length > 1) {
                content += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            `;
            }
        }

        // codes below wil only run after getProductThumbs done executing
        this.html_body = content;
        this.html_header = `<div>${name}</div>`;
        this.html_footer = `<div>${description}</div>`;

        this.parent = document.body;
        this.modal_header = document.querySelector('#modal-product .modal-header');
        this.modal_body = document.querySelector('#modal-product .modal-body');
        this.modal_footer = document.querySelector('#modal-product .modal-footer');

        this.modal_header.innerHTML = " ";
        this.modal_body.innerHTML = " ";
        this.modal_footer.innerHTML = " ";

        this._createModal();

        if (window.Android) {
            window.Android.setIsProductModalOpen(true);
        }
    }

    static async build(product_code, is_product) {
        let async_result = await getProductThumbs(product_code, is_product);
        return new ShowProduct(async_result);
    }

    question() {
        // this.save_button = document.getElementById('confirm-changes');

        // return new Promise((resolve, reject) => {
        //     this.save_button.addEventListener("click", () => {
        //         event.preventDefault();
        //         resolve(true);
        //         this._destroyModal();
        //     })
        // })
    }

    _createModal() {

        // Main text
        this.modal_body.innerHTML = this.html_body;
        this.modal_header.innerHTML = this.html_header;
        this.modal_footer.innerHTML = this.html_footer;

        // Let's rock
        $('#modal-product').modal('show');
    }

    _destroyModal() {
        $('#modal-product').modal('hide');
    }
}

function hideProductModal() {
    $('#modal-product').modal('hide');
}

$('#modal-product').on('shown.bs.modal', function () {
    checkVideoCarousel();
    pauseAll();
})

$('#modal-product').on('hidden.bs.modal', function () {
    $('#modal-product .modal-content .modal-body').empty();
    checkVideoCarousel();
    resumeAll();
})

$('#staticBackdrop').on('shown.bs.modal', function () {
    checkVideoCarousel();
    pauseAll();
})

$('#staticBackdrop').on('hidden.bs.modal', function () {
    checkVideoCarousel();
    resumeAll();
})

async function showProductModal(product_code, is_product) {

    event.preventDefault();

    let add = await ShowProduct.build(product_code, is_product);
    // let response = await add.question();

}

function checkVideoCarousel() {
    // play video when active in carousel
    if (palioBrowser && isChrome) {
        $("#modal-product .modal-body .carousel").on("slid.bs.carousel", function (e) {
            if ($(this).find("video").length) {
                if ($(this).find(".carousel-item").hasClass("active")) {
                    $(this).find("video").get(0).play();
                    // let $videoPlayButton = $(this).find(".video-play");
                    // $videoPlayButton.addClass("d-none");
                } else {
                    $(this).find("video").get(0).pause();
                }
            }
        });
    }
}

// END SHOW PRODUCT FUNCTIONS

$(function () {


    addToCartModal();

    if (isFollowed == 0) {
        // $('#staticBackdrop').modal('show');
        // $('#btn-follow').text('Follow');
    }

    const urlSearchParams = new URLSearchParams(window.location.search);

    if (urlSearchParams.has('store_id')) {
        let store_code = urlSearchParams.get('store_id');
        let f_pin = urlSearchParams.get('f_pin');
        if (f_pin == null || typeof (f_pin) == 'undefined') {
            f_pin = "";
        }

        $('#btn-follow').click(function () {
            followStore(store_code, f_pin);
        })

        $('#modal-follow-btn').click(function () {
            followStore(store_code, f_pin);
        })
    }

    eraseQuery();

    $(window).scroll(function () {
        // make sure u give the container id of the data to be loaded in.
        if ($(window).scrollTop() + $(window).height() > $("#content-grid").height() && !busy) {
            console.log('add');
            busy = true;
            offset = limit + offset;
            // displayRecords(limit, offset);
            setTimeout(fillGridWidgets('#content-grid', limit, offset), 3000);
        }
    });
})