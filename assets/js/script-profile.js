// let xReply = 0;
// let xReffReply = 0;

var data = [];
var dataFiltered = [];
var productImageMap = new Map();
var productImageStateMap = new Map();
var carouselIntervalId = 0;
var STORE_ID = new URLSearchParams(window.location.search).get('store_id');
var currentSort = '';
// var URLtype = new URLSearchParams(window.location.search).get('url_type');
let followEnabled = true;
var activeQuery = new URLSearchParams(window.location.search).get('query') ? new URLSearchParams(window.location.search).get('query') : "";
let activeFilter = new URLSearchParams(window.location.search).get('filter') ? new URLSearchParams(window.location.search).get('filter') : "";

window.addEventListener("storage", async function () {
    if (sessionStorage.getItem('refresh') == 1) {
        sessionStorage.removeItem('refresh');
        window.location.reload();
    }
}, false);

let f_pin = "";

var ua = window.navigator.userAgent;
var palioBrowser = !!ua.match(/PalioBrowser/i);
var isChrome = !!ua.match(/Chrome/i);

var myModalEl = document.getElementById('modal-product')

var modal = new bootstrap.Modal(myModalEl)

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
var nextCarouselIdx = 0;
var carouselList = [];

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
    let domain = '';
    dataFiltered.slice(off, lim + 1).forEach((element, i) => {
        var size = 1;
        var imageDivs = '';
        var imageArray = productImageMap.get(element.CODE);
        var delay = Math.floor(Math.random() * (5000)) + 5000;
        console.log('img ar', imageArray);
        if (imageArray) {
            imageArray.forEach((image, j) => {
                if (image.substr(0, 4) != "http") {
                    image = domain + '/nexilis/images/' + image;
                }
                if (isVideo(image) && j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><div class="center-crop-img"><video muted playsinline class="content-image"><source src="' + image + '"></video></div></div>';
                    j++;
                } else if (isVideo(image)) {
                    imageDivs = imageDivs + '<div class="carousel-item"><div class="center-crop-img"><video muted playsinline class="content-image"><source src="' + image + '"></video></div></div>';
                } else if (j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><div class="center-crop-img"><img draggable="false" class="content-image" src="' + image + '"/></div></div>';
                    j++;
                } else {
                    imageDivs = imageDivs + '<div class="carousel-item"><div class="center-crop-img"><img draggable="false" class="content-image" src="' + image + '"/></div></div>';
                }
            });
            var computed =
                // '<a href="#" data-bs-toggle="modal" data-bs-target="#modal-product">' + 
                // '<div class="inner" onclick="location.href=\'tab1-main?store_id=' + element.STORE_CODE + (fpin ? ('&f_pin=' + fpin) : '') + '#product-' + element.CODE + '\';">' +
                '<div class="inner" onclick="showProductModal(\'' + element.CODE + '\', ' + element.IS_PRODUCT + ');">' +
                '<div id="store-carousel-' + element.CODE + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
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

        if (imageArray.length > 1) {
            carouselList.push('#store-carousel-' + element.CODE + '');
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
    if (carouselIntervalId) {
        clearInterval(carouselIntervalId);
    }
    carouselIntervalId = setInterval(function () {
        carouselNext();
    }, 3000);
    checkVideoCarousel();
    checkVideoViewport();
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
    let domain = '';
    batch.forEach((element, i) => {
        var size = 1;
        var imageDivs = '';
        var imageArray = productImageMap.get(element.CODE);
        var delay = Math.floor(Math.random() * (5000)) + 5000;
        if (imageArray) {
            imageArray.forEach((image, j) => {
                if (image.substr(0, 4) != "http") {
                    image = domain + '/nexilis/images/' + image;
                }
                if (isVideo(image) && j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><video muted playsinline class="content-image"><source src="' + image + '"></video></div>';
                    j++;
                } else if (isVideo(image)) {
                    imageDivs = imageDivs + '<div class="carousel-item"><video muted playsinline class="content-image"><source src="' + image + '"></video></div>';
                } else if (j == 0) {
                    imageDivs = imageDivs + '<div class="carousel-item active"><img draggable="false" class="content-image" src="' + image + '"/></div>';
                    j++;
                } else {
                    imageDivs = imageDivs + '<div class="carousel-item"><img draggable="false" class="content-image" src="' + image + '"/></div>';
                }
            });
            var computed =
                // '<a href="#" data-bs-toggle="modal" data-bs-target="#modal-product">' + 
                // '<div class="inner" onclick="location.href=\'tab1-main?store_id=' + element.STORE_CODE + (fpin ? ('&f_pin=' + fpin) : '') + '#product-' + element.CODE + '\';">' +
                '<div class="inner" onclick="showProductModal(\'' + element.CODE + '\');">' +
                '<div id="store-carousel-' + element.CODE + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
                '<div class="carousel-inner">' +
                imageDivs +
                '</div>' +
                '</div>' +
                '</div>';
            if (!gridCheck(gridElements, element.CODE)) {
                gridElements.push({
                    id: element.CODE,
                    minW: size,
                    minH: size,
                    maxW: size,
                    maxH: size,
                    content: computed
                });
                grid_stack.addWidget({
                    id: element.CODE,
                    minW: size,
                    minH: size,
                    maxW: size,
                    maxH: size,
                    content: computed
                });
            }
        }

        if (imageArray.length > 1) {
            carouselList.push('#store-carousel-' + element.CODE + '');
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
    if (carouselIntervalId) {
        clearInterval(carouselIntervalId);
    }
    carouselIntervalId = setInterval(function () {
        carouselNext();
    }, 3000);
    checkVideoCarousel();
    checkVideoViewport();
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

let countVideoPlaying = 0;
var visibleCarousel = new Set();

function checkVideoViewport() {

    let videoWrapElements = document.querySelectorAll('video');
    let videoWrapArr = [].slice.call(videoWrapElements);
    // let carouselElements = document.querySelectorAll('.big-grid .carousel');
    // let carouselArr = [].slice.call(carouselElements);

    // let allElementsArr = videoWrapArr.concat(carouselArr);
    let allElementsArr = videoWrapArr.reverse();
    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // console.log(entry.target, entry.intersectionRatio)
            if (entry.intersectionRatio >= 0.7 && $('#modal-product').not('.show') && countVideoPlaying === 0) {
                playElement(entry.target, entry.intersectionRatio);
            } else if (entry.intersectionRatio < 0.7) {
                pauseElement(entry.target, entry.intersectionRatio);
            }
        });
    }, {
        threshold: 1
    });

    function playElement(el, ir) {
        //   let video = el.querySelector('video');
        let video = el;
        let carousel = el.querySelector('.carousel');
        if (video != null && video.paused) {
            video.play();
            console.log('play video', video);
            countVideoPlaying = 1;
        }
    }

    function pauseElement(el, ir) {
        //   let video = el.querySelector('video');
        let video = el;
        let carousel = el.querySelector('.carousel');
        // console.log('carousel', carousel);
        if (video != null && !video.paused) {
            // console.log('pause video', video);
            video.pause();
            countVideoPlaying = 0;
        }

    }

    allElementsArr.forEach((elements) => {
        // console.log(elements);
        observer.observe(elements);
    });
}

function carouselNext() {
    if (carouselList.length <= 0) return;
    let prevIdx = nextCarouselIdx;
    while (!$(carouselList[nextCarouselIdx]).is(":in-viewport")) {
        nextCarouselIdx = nextCarouselIdx + 1;
        if (nextCarouselIdx >= carouselList.length) {
            nextCarouselIdx = 0;
        }
        if (nextCarouselIdx == prevIdx) break;
    }
    $(carouselList[nextCarouselIdx]).carousel('next');
    nextCarouselIdx = nextCarouselIdx + 1;
    if (nextCarouselIdx >= carouselList.length) {
        nextCarouselIdx = 0;
    }
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
    // $(".carousel").on("slid.bs.carousel", function (e) {
    //     if ($(this).find("video").length) {
    //         if ($(this).find(".carousel-item").hasClass("active")) {
    //             $(this).find("video").get(0).play();
    //         } else {
    //             $(this).find("video").get(0).pause();
    //         }
    //     }
    // });
    console.log('listen carousel')
    $('#modal-product .carousel').on('slide.bs.carousel', function (arg) {
        var videoList = document.getElementById('modal-product').querySelector('.carousel-item video');
        videoList[arg.from].pause();
        videoList[arg.to].play();
    })
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
                    // window.Android.setCurrentStoreData(storeData);
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
    // if (document.referrer != '' && document.referrer != null && document.referrer != location.href) {
    //     window.location = document.referrer;
    // } else {

    let f_pin = '';

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    if (localStorage.getItem('currentTab') != null) {
        let currentTab = localStorage.getItem('currentTab');

        if (currentTab == '0') {
            window.location.href = 'tab1-main-only?f_pin=' + f_pin;
        } else if (currentTab == '1') {
            window.location.href = 'tab3-main-only?f_pin=' + f_pin;
        } else if (currentTab == '2') {
            // window.location.href = 'tab3-main-only?f_pin=' + f_pin;
            let mode = localStorage.getItem('is_grid')
            if (mode) {
                if (mode == '0') {
                    window.location.href = 'tab1-main?f_pin=' + f_pin;
                } else {
                    window.location.href = 'tab3-main?f_pin=' + f_pin;
                }
            } else {
                window.location.href = 'tab1-main?f_pin=' + f_pin;
            }
        } else if (currentTab == '4') {
            window.location.href = 'tab1-video?f_pin=' + f_pin;
        }
    } else {
        window.location = document.referrer;
    }
    // }
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
        try {
            window.Android.setCurrentStore(store_code, be_id);
        } catch (e) {}

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
            productArr = data;
            // $('#post-count').text(data.length);
            console.log(data);
            data.forEach(productEntry => {
                if (!productEntry.THUMB_ID.startsWith("http")) {
                    var root = 'http://' + location.host;
                }
                // console.log(productEntry.THUMB_ID);
                var thumbs = productEntry.THUMB_ID.split("|");
                thumbs.forEach(image => {
                    if (!productImageMap.has(productEntry.CODE) && image != null && image.trim() != "") {
                        productImageMap.set(productEntry.CODE, [image]);
                    } else if (image != null && image.trim() != "") {
                        productImageMap.set(productEntry.CODE, productImageMap.get(productEntry.CODE).concat([image]));
                    }
                });
            });
            filterStoreData(activeQuery, false, activeFilter);

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
    xmlHttp.open("get", "/nexilis/logics/fetch_products?store_id=" + store_id + "&f_pin=" + f_pin);
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
    let getPr = productArr.filter(prod => prod.CODE == pr)[0];

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


    // if (product_imgs.length == 1) {
    let extension = ext(product_imgs[0]);
    if (extension == ".jpg" || extension == ".png" || extension == ".webp") {
        product_showcase = `<img draggable="false" class="product-img" src="${product_imgs[0]}">`;
    } else if (extension == ".mp4" || extension == ".webm") {
        let poster = product_imgs[0].replace(extension, ".webp");
        product_showcase = `
        <div class="video-wrap"><video playsinline muted="" class="myvid" preload="metadata"
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
    <img draggable="false" id="btn-wishlist" class="addcart-wishlist follow-icon-${getPr.SHOP_CODE}" onclick="followUser('${getPr.SHOP_CODE}','${f_pin}')" src="${followSrc}">`;

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
    if ($('#modal-addtocart').hasClass('show')) {
        $('#modal-addtocart').modal('hide');
    } else if ($('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }

}

function pauseAll() {
    // $('video.content-image').each(function () {
    //     $(this).get(0).pause();
    // })
    $('.carousel-item video, .timeline-image video').each(function () {
        $(this).get(0).pause();
    })
    visibleCarousel.clear();
    $('.carousel').each(function () {
        $(this).carousel('pause');
    })
    if (carouselIntervalId) {
        clearInterval(carouselIntervalId);
        carouselIntervalId = 0;
    }
}

function resumeAll() {
    // $('video.content-image').each(function () {
    //     $(this).get(0).play();
    // })
    checkVideoViewport();
    checkVideoCarousel();
    checkCarousel();
    // updateCounter();
    // fetchNotifCount();
    if (carouselIntervalId) {
        clearInterval(carouselIntervalId);
    }
    carouselIntervalId = setInterval(function () {
        carouselNext();
    }, 3000);
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
    // var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
    // var webkit = !!ua.match(/WebKit/i);
    // var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);

    $('[data-bs-target="#modal-addtocart"]').click(function () {
        $('#modal-addtocart .modal-dialog').css('top', fixedPos);
        $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
        let bottomPos = parseInt(fixedPos.replace('px', '')) + 25;
        if (window.webkit && window.webkit.messageHandlers) {
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
        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
            window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
                param1: true
            });
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
        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
            window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
                param1: false
            });
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

function followUser($storeCode, flag, checkIOS = false) {
    let f_pin = '';
    flag = isFollowed;
    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
        f_pin = window.Android.getFPin();
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: $storeCode + '|' + flag, // values to be provided to followUser
            param2: 'follow_user'
        });
        return;
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    followEnabled = false;
    let follower = document.getElementById('follower-count').innerText;
    console.log('follower', follower);
    let count = parseInt(follower);

    let followBtn = "Follow";
    let unfollowBtn = "Unfollow";
    if (localStorage.lang == 1) {
        followBtn = "Ikuti";
        unfollowBtn = "Berhenti Mengikuti"
    }

    if (flag == 1) {
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
    // if (window.Android) {
    //     f_pin = window.Android.getFPin();
    // }

    var curTime = (new Date()).getTime();

    var formData = new FormData();

    formData.append('store_code', $storeCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_follow', isFollowed);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            // console.log(xmlHttp.responseText);
            updateScoreShop($storeCode);
            document.getElementById('follower-count').innerText = count;
            followEnabled = true;
        }
    }
    xmlHttp.open("post", "/nexilis/logics/follow_store");
    xmlHttp.send(formData);

}

function eraseQuery() {
    $("#delete-query").click(function () {
        $('#searchFilterForm-a input#query').val('');
        $('#delete-query').addClass('d-none');
        searchFilter();
    })

    if ($('#searchFilterForm-a input#query').val() != "") {
        $('#delete-query').removeClass('d-none');
    }

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

    let f_pin = "";
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  formData.append("f_pin", f_pin);

    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/nexilis/logics/get_product_thumbs");

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve({
                    thumb_id: JSON.parse(xhr.response).THUMB_ID,
                    thumbnail: JSON.parse(xhr.response).THUMBNAIL,
                    file_type: JSON.parse(xhr.response).FILE_TYPE,
                    name: JSON.parse(xhr.response).NAME,
                    description: JSON.parse(xhr.response).DESCRIPTION,
                    CODE: JSON.parse(xhr.response).CODE,
                    LINK: JSON.parse(xhr.response).LINK
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
                console.log("fetch comment", data)
                // $('#comments-modal').html(data);
                resolve(data);
            });
        // }
    })
    
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

    let videoWrap = document.querySelector('#' + code);
    let video = videoWrap.querySelector('video');
    let playButton = videoWrap.querySelector('.video-play');

    playButton.addEventListener('click', (e) => {
        console.log('click play')
        if (video.paused) {
            video.play();
            playButton.classList.add('d-none');
        }
    });

    video.addEventListener('click', (e) => {
        console.log('click video')
        if (video.paused) {
            video.play();
            playButton.classList.add('d-none');
        } else {
            video.pause();
            playButton.classList.remove('d-none');
        }
    })
}
let video_arr = ['webm', 'mp4', 'mov', 'avi'];
let img_arr = ['png', 'jpg', 'webp', 'gif', 'jpeg'];

class ShowProduct {

    constructor(async_result, comments="") {
        console.log("RSLT",comments)
        let thumbs = async_result.thumb_id.split('|');
        console.log(thumbs);
        let name = richText(decodeURI(async_result.name));
        let description = richText(decodeURI(async_result.description));

        let f_pin = ''
        if (window.Android) {
            f_pin = window.Android.getFPin();
        } else {
            f_pin = new URLSearchParams(window.location.search).get("f_pin");
        }

        let content = '';
        let domain = '';
        let preContent = '';

        // FOR X BUTTON

        // console.log(img_arr);
        // console.log(video_arr);

        preContent = `<img draggable="false" class="close-icon" onclick="closeModal()" src="../assets/img/close-icon.png" style="position: absolute;z-index: 500;right: 0;margin-right: 20px;margin-top: 20px;width: 27px;height: 27px;/* filter: drop-shadow(3px 0px 1px #222); */background-color: rgb(142 142 142 / 60%);border-radius: 20px;padding: 5px">`;

        if (thumbs.length == 1) {
            let type = ext(thumbs[0]);
            console.log('TYPE', type)
            // if (video_arr.includes(type)) {
            if (video_arr.includes(type)) {
                // content = `
                //     <video muted autoplay class="d-block w-100">
                //     <source src="../images/${thumbs[0]}#t=0.5" type="video/${type}">
                //     </video>
                // `;

                content = `
                <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                <video class="myvid" autoplay muted playsinline>
                <source src="${ async_result.thumb_id.includes("http") ?  async_result.thumb_id : domain + "/nexilis/images/" + async_result.thumb_id}">
                </video>
                <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                <img draggable="false" src="../assets/img/video_mute.png" />
                </div>
                <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                '<img draggable="false" src="../assets/img/video_play.png" />
                </div>
                </div>
                `;
            } else if (img_arr.includes(type)) {
                content = `
                    <img draggable="false" src="${ async_result.thumb_id.includes("http") ?  async_result.thumb_id : domain + "/nexilis/images/" +  async_result.thumb_id}" class="d-block w-100">
                `;
            }

        } else {
            content = `
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
            `;
            // console.log(content)
            let filteredThumbs = thumbs.filter(thumb => thumb.trim() != '' || thumb.length > 0);

            filteredThumbs.forEach((th, idx) => {
                if (th.trim() != '') {
                    content += `<div class="carousel-item${idx == 0 ? ' active' : ''}">`;

                    let type = ext(th);
                    if (video_arr.includes(type)) {
                        console.log(type)
                        content += `
                        <div class="video-wrap" id="videowrap-modal-${async_result.CODE}-${idx}">
                        <video class="myvid" autoplay muted playsinline src="${th.includes("http") ? th : domain + "/nexilis/images/" + th}" style="z-index:10;" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}-${idx}');"></video>
                        <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}-${idx}');">
                        <img draggable="false" src="../assets/img/video_mute.png" />
                        </div>
                        <div class="video-play" style="z-index:10;" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}-${idx}');">
                        <img draggable="false" src="../assets/img/video_play.png" />
                        </div>
                        </div>
                        `;
                    } else if (img_arr.includes(type)) {
                        console.log(type)
                        content += `
                        <img draggable="false" src="${th.substr(0,4) == "http" ? th : '/nexilis/images/' + th}" class="d-block w-100">
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

        console.log("CONTENT", content)

        let link = '';
        let url_div = '';

        if (async_result.LINK != null && async_result.LINK != undefined && async_result.LINK.trim() != "") {
            link = async_result.LINK;
            if (link.substring(0, 4) != "http") {
                link = "https://" + link;
            }
            console.log(link);
            // link_thumb = '<a href="' + link + '">' + thumb_content + '</a>';
            url_div = `
           <a href="${link}" style="text-decoration:underline; color:#0d6efd";>Link</a>
            `;
            if (async_result.CODE == '16467163130000246a901c4') {
                url_div = `
           <a href="${link + "?f_pin=" + f_pin}" style="text-decoration:underline; color:#0d6efd";>Register membership</a>
            `;
            }
        }

        let post_owner = new URLSearchParams(window.location.search).get('store_id');

        let reportPost = "Report/flag Content";
        let blockPost = "Remove/Block Content";
        let removeText = "Remove";
        let editText = "Edit";
        let deleteText = "Delete";

        if (localStorage.lang == 1) {
            reportPost = "Laporkan Konten";
            blockPost = "Hapus/Blokir Konten";
            removeText = "Hilangkan";
            editText = "Ubah";
            deleteText = "Hapus";
        } else {
            reportPost = "Report/flag Content";
            blockPost = "Remove/Block Content";
            removeText = "Remove";
            editText = "Edit";
            deleteText = "Delete";
        }

        // user_type = 1;


        console.log("USER_TYPE", user_type);
        console.log("POST OWNER", post_owner, f_pin);
        console.log("ICP", isChangedProfile);
        // codes below wil only run after getProductThumbs done executing
        this.html_header = `
        <div>${name}</div>
        <div class="dropdown dropdown-edit edit-menu-${post_owner}" data-isadmin="${user_type}"><a class="post-status dropdown-toggle" data-bs-toggle="dropdown" id="edt-del-${async_result.CODE}"><img draggable="false" src="../assets/img/icons/More.png" height="25" width="25" style="background-color:unset;"></a>
            <ul class="dropdown-menu" aria-labelledby="edt-del-${async_result.CODE}">
                <li><a class="dropdown-item button_edit ${post_owner == f_pin ? '' : 'd-none'}" onclick="editPost('${async_result.CODE}')">${editText}</a></li>
                <li><a class="dropdown-item button_delete ${post_owner == f_pin ? '' : 'd-none'}" onclick="deletePost('${async_result.CODE}')">${deleteText}</a></li>
                <li><a class="dropdown-item button_adminremove ${user_type === '1' && post_owner !== f_pin && isChangedProfile === "1" ? "" : "d-none"}" onclick="confirmRemovePost('${async_result.CODE}')">${removeText}</a></li>
                <li><a class="dropdown-item button_report ${post_owner != f_pin ? '' : 'd-none'}" onclick="reportContent('${async_result.CODE}',0)">${reportPost}</a></li>
                <li><a class="dropdown-item button_block ${post_owner != f_pin ? '' : 'd-none'}" onclick="blockContent('${async_result.CODE}')" style="color: brown">${blockPost}</a></li>
            </ul>
        </div>
        `;
        this.html_body = preContent + content;
        let truncateText = localStorage.lang == 1 ? "Selengkapnya" : "Read more";

        console.log("COMMNETS", comments)

        this.html_footer = `
    
        ${url_div != "" && url_div != null ? "<span style='font-size:13px;'>"+url_div+"</span>" : ""}
        <div class="prod-desc ${description.length > 128 ? "truncate" : ""}" style="font-size:13px;">${description}</div>
        ${description.length > 128 ? "<span class=\"truncate-read-more\" onclick=\"toggleProdDesc(this);\">" + truncateText + "</span>" : ""}
        <div class="container" id="comments-modal"> 
        ${comments}
        </div>`;

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
        if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
            window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
                param1: true
            });
        }

    }

    static async build(product_code, is_product) {
        let async_result = await getProductThumbs(product_code, is_product);
        let commentsection = "";
        // if (isChangedProfile == "1") {
            commentsection = await getComments(product_code);
        // }
        // let 
        return new ShowProduct(async_result, commentsection);
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

    _createModal(code) {

        // Main text
        this.modal_body.innerHTML = this.html_body;
        this.modal_header.innerHTML = this.html_header;
        this.modal_footer.innerHTML = this.html_footer;        

        var videoList = document.getElementById('modal-product').querySelectorAll('.carousel-item .video-wrap');
        console.log(videoList.length);

        console.log('listen carousel');

        // IF MODAL CONTAIN VIDEO WRAP DIV

        if (videoList.length > 0){
            $('#modal-product .carousel').on('slide.bs.carousel', function(arg) {
                console.log(">>>"+arg.from);
                var videoFrom = videoList[arg.from].querySelector('video');
                var videoPlayFrom = videoList[arg.from].querySelector('.video-play');
                videoFrom.pause();
                videoPlayFrom.classList.remove('d-none');
                var videoTo =videoList[arg.to].querySelector('video');
                var videoPlayTo = videoList[arg.to].querySelector('.video-play');
                videoTo.play();
                videoPlayTo.classList.add('d-none');
            })
        }

        // Let's rock
        $('#modal-product').modal('show');
    }

    _destroyModal() {
        this.modal_body.innerHTML = '';
        $('#modal-product').modal('toggle');
    }
}

function closeModal(checkIOS = false) {
    console.log("hide cuk");
    let videoPopUp = document.querySelector('#modal-product .modal-body video');
    if (videoPopUp) {
        videoPopUp.pause();
        videoPopUp.removeAttribute('src');
        videoPopUp.load();
    }
    $('#modal-product .modal-body').html('');
    $('#modal-product').modal('hide');

    if (window.Android) {
        window.Android.setIsProductModalOpen(false);
    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen && checkIOS == false) {
        window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
            param1: false
        });
    }
}

$('#modal-product').on('shown.bs.modal', function () {
    // checkVideoCarousel();    
    pauseAll();
})



$('#modal-product').on('hide.bs.modal', function () {
    console.log("hiding")
    let videoPopUp = document.querySelector('#modal-product .modal-body video');
    if (videoPopUp) {
        videoPopUp.pause();
        videoPopUp.removeAttribute('src');
        videoPopUp.load();
    }
    $('#modal-product .modal-body').html('');
})

$('#modal-product').on('hidden.bs.modal', function () {
    $('#modal-product .modal-body').html('');
    // $('#modal-product .modal-content .modal-body').empty();
    // checkVideoCarousel();
    console.log('hidden!')
    resumeAll();
})

$('#staticBackdrop').on('shown.bs.modal', function () {
    // checkVideoCarousel();
    pauseAll();
})

$('#staticBackdrop').on('hidden.bs.modal', function () {
    // checkVideoCarousel();
    resumeAll();
})

async function showProductModal(product_code, is_product) {

    event.preventDefault();

    let add = await ShowProduct.build(product_code, is_product);
    // let response = await add.question();

}

function checkVideoCarousel() {}

// END SHOW PRODUCT FUNCTIONS

function voiceSearch() {
    $('img#voice-search').click(function () {

        console.log('start voice');
        if (window.Android) {
            $isVoice = window.Android.toggleVoiceSearch();
            toggleVoiceButton($isVoice);
        } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.toggleVoiceSearch) {
            window.webkit.messageHandlers.toggleVoiceSearch.postMessage({
                param1: ""
            });
        }
    });
}




function submitVoiceSearch($searchQuery) {
    // // console.log("submitVoiceSearch " + $searchQuery);
    $('#searchFilterForm-a input#query').val($searchQuery);
    $('#delete-query').removeClass('d-none');
    searchFilter();
}

function toggleVoiceButton($isActive) {
    if ($isActive) {
        $("#voice-search").attr("src", "../assets/img/action_mic_blue.png");
    } else {
        $("#voice-search").attr("src", "../assets/img/action_mic.png");
    }
}
voiceSearch();

let gif_arr = [];
let gif_pos = [0, 1];

function getGIFs() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      gif_arr = JSON.parse(xmlHttp.responseText);
      // // console.log(gif_arr);
      let new_arr = gif_arr;
      if (gif_arr.length === 3) {
        new_arr = [gif_arr[1], gif_arr[0], gif_arr[2]];
      }
      drawGIFs(new_arr);
    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_gifs?f_pin=" + f_pin);
  xmlHttp.send();

}

function drawGIFs(arr) {
  let lastAd = parseInt(localStorage.getItem('last_ad'));

  if (lastAd == null) {
    lastAd = 0;
  }

  let currentAd = 0;
  if (lastAd + 1 <= arr.length - 1) {
    currentAd = lastAd + 1;
  } else {
    currentAd = 0;
  }

  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  localStorage.setItem('last_ad', currentAd);
  let pickGif = arr[currentAd];
  console.log("BANGSAT", pickGif);
  let currURL = window.location.href;
  // let pickGif = arr[currentAd];
  // // console.log(1);
  let url = "";
  if (pickGif.URL.includes('bni.co.id')) {
    url = pickGif.URL;
  }
  else {
    let store_id = new URLSearchParams(window.location.search).get("store_id");
    // if (window.location.pathname.includes("tab1-main")) {
    //   url = pickGif.URL + '&f_pin=' + f_pin + '&origin=1';
    // } else if (window.location.pathname.includes("tab1-main-only")) {
    //   url = pickGif.URL + '&f_pin=' + f_pin + '&origin=11';
    // }
    let path = window.location.pathname.split("/");
    if (path.includes("tab3-profile")) {
      url = pickGif.URL + '&f_pin=' + f_pin + '&origin=2&store_id=' + store_id;
      // if (currURL.includes('newuniverse.io')) {
      //   url = pickGif.URL + '&f_pin=' + f_pin + '&origin=1&url_type=0';
      // }
      // else if (currURL.includes('108.137.84.148')) {
      //     url = pickGif.URL + '&f_pin=' + f_pin + '&origin=1&url_type=1';
      // }
      // else if (currURL.includes('palio.web')) {
      //   url = 'http://palio.web/nexilis/pages/digipos?env=2&f_pin=' + f_pin + '&origin=1&url_type=2';
      // }
    } 
    // else if (path.includes("tab1-main-only")) {
    //   url = pickGif.URL + '&f_pin=' + f_pin + '&origin=11';
    //   // if (currURL.includes('newuniverse.io')) {
    //   //   url = pickGif.URL + '&f_pin=' + f_pin + '&origin=11&url_type=0';
    //   // }
    //   // else if (currURL.includes('108.137.84.148')) {
    //   //     url = pickGif.URL + '&f_pin=' + f_pin + '&origin=11&url_type=1';
    //   // }
    //   // else if (currURL.includes('palio.web')) {
    //   //   url = 'http://palio.web/nexilis/pages/digipos?env=2&f_pin=' + f_pin + '&origin=11&url_type=2';
    //   // }
    // }


  }
  let div = `
      <div id="gifs-${currentAd}" class="gifs">
      <a onclick="event.preventDefault(); goToURL('${url}');">
          <img draggable="false" src="/nexilis/assets/img/gif/${pickGif.FILENAME}">
        </a>
      </div>
    `;
  // // console.log("dir", pickGif.DIRECTION);
  // if (pickGif.FILENAME === "ppob-4.gif") {

  //   // // console.log('resize');
  //   $('.gifs img').css('width', '200px !important');
  //   $('.gifs img').css('height', 'auto !important');
  // }
  let dir = pickGif.DIRECTION;
  if (pickGif.DIRECTION === 'left') {
    // // console.log('sdnka');
    // if (pickGif.FILENAME.includes('ppob-4')) {
    //   $('#gif-container .gifs img').css('width', '200px');
    //   $('#gif-container .gifs img').css('height', 'auto');
    // }
    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass('right');
    // document.getElementById('gif-container').classList.add = 'right';
  } else if (pickGif.DIRECTION === 'right') {
    // // console.log('sdnka');
    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass('left');
    // document.getElementById('gif-container').classList.add = 'left';
  } else if (pickGif.DIRECTION === 'up') {
    // // console.log('sdnka');
    $('#gif-container').addClass('bottom');
    // document.getElementById('gif-container').classList.add = 'bottom';
    let dir = ['right', 'left'];
    let random = Math.floor(Math.random() * dir.length);
    // // console.log('random', dir[random])
    $('#gif-container').addClass(dir[random]);
    // document.getElementById('gif-container').classList.add = dir[random];
  } else if (pickGif.DIRECTION === 'down') {
    // // console.log('sdnka');
    $('#gif-container').addClass('top');
    // document.getElementById('gif-container').classList.add = 'top';
    let dir = ['right', 'left'];
    let random = Math.floor(Math.random() * dir.length);
    $('#gif-container').addClass(dir[random]);
    // document.getElementById('gif-container').classList.add = dir[random];
  } else if (pickGif.DIRECTION === null) {
    // // console.log('sdnka');
    let direct = ['right', 'left'];
    let random = Math.floor(Math.random() * direct.length);

    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass(direct[random]);
    if (direct[random] == 'left') {
      dir = 'right'
    } else {
      dir = 'left';
    }
    // document.getElementById('gif-container').classList.add = dir[random];
  }
  // document.getElementById('gif-container').innerHTML = div;
  $('#gif-container').append(div);
  // 
  if (document.getElementById('gifs-2') != null) {
    // // // console.log('sini anjing');
    // document.getElementById('gif-container').style.removeProperty('bottom');
    // document.getElementById('gif-container').style.top = '30px';
  }
  // randomAd(arr);
  animateAd(currentAd, pickGif.FILENAME, pickGif.ANIM_TYPE, dir);
}

function goToURL(url, checkIOS = false) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = url;
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.checkProfile.postMessage({
      param1: url,
      param2: 'gif'
    });
    return;

  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = url;
  }
}

function animateAd(current, filename, which, direction) {
  // // console.log(which);
  // // console.log(direction);
  var lineHeight = $('#gifs-' + current).width();
  // // console.log(lineHeight);
  if (which === 'horizontal') { // move horizontal
    var windowHeight = $(window).width();
    var lineHeight = $('#gifs-' + current).width();
    var desiredBottom = 20;
    var newPosition = windowHeight - (115 + desiredBottom);
    if (direction === 'right') {

      // // // console.log('lh', lineHeight);
      // // // console.log('db', desiredBottom);
      // // // console.log('np', newPosition);
      $('#gif-container').animate({
        left: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          right: desiredBottom + 'px',
          left: 'auto'
        });
        $('#gif-container').fadeOut();
      });
    } else if (direction === 'left') {
      // if (filename == 'ppob-4.gif') {
      //   var newPosition = windowHeight - (200 + desiredBottom);
      //   $('#gif-container').animate({
      //     right: newPosition + 'px',
      //   }, 5000, function () {
      //     $('#gif-container').css({
      //       right: 'auto',
      //       left: '20px'
      //     });
      //     $('#gif-container').fadeOut();
      //   });
      // } 
      // else {
      $('#gif-container').animate({
        right: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          right: 'auto',
          left: '20px'
        });
        $('#gif-container').fadeOut();
      });
      // }
    }
  } else if (which === 'vertical') {
    var windowHeight = $(window).height();
    var lineHeight = 170;
    var desiredBottom = 140;
    // // console.log(desiredBottom);
    if (direction === "up") {
      var newPosition = windowHeight - (lineHeight + 30);
      $('#gif-container').animate({
        bottom: newPosition + 'px',
        // top: '30px'
      }, 5000, function () {
        $('#gif-container').css({
          top: '30px',
          bottom: 'auto'
        });
        $('#gif-container').fadeOut();
      });
    } else {
      var newPosition = windowHeight - (lineHeight + 140);
      $('#gif-container').animate({
        top: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          top: 'auto',
          bottom: '140px'
        });
        $('#gif-container').fadeOut();
      });
    }


  }

  $(window).scroll(function () {
    let scroll = $(window).scrollTop();
    if (scroll >= 150) {
      /* insert what happens after scroll bigger than 350px */
      $('#gif-container').stop(true, false);
      $('#gif-container').fadeOut();
    }
  })
}

$(function () {
    if (document.getElementById('gif-container') != null) {
        getGIFs();
    }

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
            if (followEnabled) {
                followUser(store_code, f_pin);
            }
        })

        $('#modal-follow-btn').click(function () {
            followUser(store_code, f_pin);
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

function editPost(code) {
    localStorage.setItem('activeQueryProfile', window.location.search);
    if (window.Android) {
        let f_pin = window.Android.getFPin();

        window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code + "&origin=" + localStorage.getItem("currentTab"); 
    } else {
        let f_pin = new URLSearchParams(window.location.search).get("f_pin");

        window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code + "&origin=" + localStorage.getItem("currentTab"); 
    }
}


function deletePost(post_id) {

    $('#just-post').addClass('d-none');
    $('#just-product').addClass('d-none');
    $('#both-pp').addClass('d-none');

    $('#modal-product').modal('hide');
    $('#modal-delete-question').modal('show');

    var xmlHttp = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('post_id', post_id);

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            var response = xmlHttp.response.split("|");
            console.log(response);

            var isPost = response[0];
            var isProduct = response[1];

            if (isPost == 1){
                $('#just-post').removeClass('d-none');
                $('#just-post').attr('onclick','deletePostOnly(`'+post_id+'`)');
            }

            if (isProduct == 1){
                $('#just-product').removeClass('d-none');
                $('#just-product').attr('onclick','deleteProductOnly(`'+post_id+'`)');
            }

            if (isPost == 1 & isProduct == 1){
                $('#both-pp').removeClass('d-none');
                $('#both-pp').attr('onclick','deleteBothContent(`'+post_id+'`)');
            }

        }
    } 
    xmlHttp.open("POST", "/nexilis/logics/check_delete");
    xmlHttp.send(formData);

}

function deletePostOnly(post_id){

    $('#modal-delete-question').modal('hide');

    var xmlHttp = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('post_id', post_id);
    formData.append('ec_date', new Date().getTime());
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == "Success") {
                console.log(post_id + ' deleted');
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>Post deleted.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Postingan telah dihapus.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                    window.location.reload();
                });
                $('#delete-post-info').modal('toggle');
            } else {
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>An error occured while deleting post. Please refresh and try again.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Error saat menghapus post. Silahkan muat ulang dan coba lagi.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
                //   window.location.reload();
                // });
                $('#delete-post-info').modal('toggle');
            }
        }
    }
    xmlHttp.open("POST", "/nexilis/logics/delete_post");
    xmlHttp.send(formData);
}

function deleteProductOnly(post_id){

    $('#modal-delete-question').modal('hide');

    var xmlHttp = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('product_code', post_id);

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == "Success") {
                console.log(post_id + ' deleted');
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>Product deleted.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Produk telah dihapus.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                    window.location.reload();
                });
                $('#delete-post-info').modal('toggle');
            } else {
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>An error occured while deleting product. Please refresh and try again.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Error saat menghapus produk. Silahkan muat ulang dan coba lagi.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
                //   window.location.reload();
                // });
                $('#delete-post-info').modal('toggle');
            }
        }
    }
    xmlHttp.open("POST", "/nexilis/logics/delete_product_only");
    xmlHttp.send(formData);
}

function deleteBothContent(post_id){

    $('#modal-delete-question').modal('hide');

    var xmlHttp = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('post_id', post_id);
    formData.append('ec_date', new Date().getTime());
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            if (xmlHttp.responseText == "Success") {
                console.log(post_id + ' deleted');
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>Post and Product deleted.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Postingan dan Produk telah dihapus.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                    window.location.reload();
                });
                $('#delete-post-info').modal('toggle');
            } else {
                if (localStorage.lang == 0) {
                    $('#delete-post-info .modal-body').html('<h6>An error occured while deleting content. Please refresh and try again.</h6>');
                    $('#delete-post-close').text('Close');
                } else {
                    $('#delete-post-info .modal-body').html('<h6>Error saat menghapus konten. Silahkan muat ulang dan coba lagi.</h6>');
                    $('#delete-post-close').text('Tutup');
                }
                // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
                //   window.location.reload();
                // });
                $('#delete-post-info').modal('toggle');
            }
        }
    }
    xmlHttp.open("POST", "/nexilis/logics/delete_both_content");
    xmlHttp.send(formData);
}

function removePost(code) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            $('#modal-confirm-remove').modal('hide');

            // let path = window.location.href.split('?')[0] + '?f_pin=' + F_PIN;

            $('#modal-post-removed #post-removed-close').click(function () {
                //   window.location.href = path;
                window.location.reload();
            })

            $('#modal-post-removed').on('hidden.bs.modal', function () {
                //   window.location.href = path;
                window.location.reload();
            })

            $('#modal-post-removed').modal('show');
        }
    }
    xmlHttp.open("get", "/nexilis/logics/admin_remove_post?post_id=" + code);
    xmlHttp.send();
}

function confirmRemovePost(code) {
    $('#modal-product').modal('hide');
    $('#modal-confirm-remove #remove-post-accept').off('click');

    $('#modal-confirm-remove #remove-post-accept').click(function () {
        removePost(code);
    })

    $('#modal-confirm-remove').modal('show');
}

function toggleProdDesc(ele) {
    let prodDesc = document.querySelector('#modal-product .modal-footer .prod-desc');
    prodDesc.classList.toggle("truncate");
    let truncateButton = document.querySelector('#modal-product .modal-footer .truncate-read-more');

    if (!prodDesc.classList.contains("truncate")) {
        truncateButton.innerText = (localStorage.lang == 1 ? "Sembunyikan" : "Hide");
    } else {
        truncateButton.innerText = (localStorage.lang == 1 ? "Selengkapnya" : "Read more");
    }
}

$('#searchFilterForm-a').validate({
    rules: {
        // 'category[]': {
        //   required: true
        // }
    },
    messages: {
        // 'category[]': {
        //   required: '<div class="alert alert-danger" role="alert">Pilih minimal salah satu filter di atas</div>',
        // },
    },
    submitHandler: function (form) {
        searchFilter();
    },
    errorClass: 'help-block',
    errorPlacement: function (error, element) {
        // if (element.attr('name') == 'category[]') {

        //   error.insertAfter('#checkboxGroup');
        // }
    }

});

function filterStoreData($filterSearch, isSearching, $filterCategory = null) {
    console.log('FILTERSTORE', $filterSearch)
    
    if (window.Android) {
        try {
            hiddenStores = window.Android.getHiddenStores().split(",");
        } catch (error) {

        }
    }
    
    dataFiltered = [];
    data.forEach(storeEntry => {
        var isMatchCategory = false;
        if ($filterCategory != null && $filterCategory != "") {

            // var categoryArray = $filterCategory;
            var categoryArray = $filterCategory.split("-");
    
            isMatchCategory = categoryArray.indexOf(storeEntry.CATEGORY + "") > -1;
        } else {
            isMatchCategory = true;
        }

        var isMatchSearch = false;
        if ($filterSearch) {
            isMatchSearch = isMatchSearch || storeEntry.TITLE.toLowerCase().includes($filterSearch.toLowerCase());
            isMatchSearch = isMatchSearch || storeEntry.DESCRIPTION.toLowerCase().includes($filterSearch.toLowerCase());
            isMatchSearch = isMatchSearch || storeEntry.NAME.toLowerCase().includes($filterSearch.toLowerCase());
        } else {
            isMatchSearch = true;
        }
        if (isMatchSearch && isMatchCategory) {
            dataFiltered.push(storeEntry);
        }
    });
    // fetchProductPics(dataFiltered, isSearching);
}

async function searchFilter() {

    if (localStorage.lang == 1) {
        $('#no-content-text').text("Tidak ada konten yang sesuai dengan kriteria");
    } else {
        $('#no-content-text').text("Nothing Matches your criteria");
    }

    var selected_id = "";
    $('body').css('visibility', 'hidden');
    $('.has-story').removeClass("selected");
    var dest = window.location.href;
    var params = "";
    const query = $('#query').val();
    var filter = activeFilter;
    // // console.log('active filter: ' + filter);
    if (dest.includes('#')) {
        dest = dest.split('#')[0]
    }
    if (dest.includes('?')) {
        dest = dest.split('?')[0];
    }
    if (window.Android) {
        var f_pin = window.Android.getFPin();
        // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
        if (f_pin) {
            if (!params.includes("?")) {
                params = params + "?f_pin=" + f_pin;
            } else {
                params = params + "&f_pin=" + f_pin;
            }
        }
    } else {
        var f_pin = new URLSearchParams(window.location.search).get('f_pin');
        if (f_pin) {
            if (!params.includes("?")) {
                params = params + "?f_pin=" + f_pin;
            } else {
                params = params + "&f_pin=" + f_pin;
            }
        }
    }
    if (STORE_ID != "") {
        params = params + "&store_id=" + STORE_ID;
    }
    // if (query != "" || filter != "") {
    //     if (!params.includes("?")) {
    //         params = params + "?";
    //     } else {
    //         params = params + "&";
    //     }
    // }
    if (query != "") {
        let urlEncodedQuery = encodeURIComponent(query);
        params = params + "&query=" + urlEncodedQuery;        
    }
    if (filter != "") {
        let urlEncodedQuery = encodeURIComponent(filter);
        params = params + "&filter=" + urlEncodedQuery;
    }

    // console.log("params " + params);
    dest = dest + params;
    history.pushState({
        'search': query,
        'filter': filter,
    }, "Palio Browser", dest);
    offset = 0;

    console.log(params);
    $('#story-container').html('');
    filterStoreData(query, true, activeFilter);
    fillGridStack('#content-grid', limit, offset);
    $('body').css('visibility', 'visible');

}

$('.dropdown-edit').on('show.bs.dropdown', function () {
    $('.close-icon').hide();
});

$('.dropdown-edit').on('hide.bs.dropdown', function () {
    $('.close-icon').show();
});


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
            // if ($("#modal-addtocart").length > 0 && $("#modal-addtocart").hasClass("show")) {
            //     console.log("hide modal")
            //     $("#modal-addtocart").modal("hide");
            // }
        }
    } catch (err) {
        console.log(err);
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