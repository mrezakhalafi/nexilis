let items = [];
let purchases = [];

var didScroll;
var isSearchHidden = true;
var lastScrollTop = 0;
var delta = 1;
var navbarHeight = $('#header').outerHeight();
var topPosition = 0;

let headerHeight = $('#header').outerHeight();

function hasScrolled() {
    var st = $(this).scrollTop();

    // Make sure they scroll more than delta
    if (Math.abs(lastScrollTop - st) <= delta)
        return;

    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight) {
        // Scroll Down
        $('#header').css('top', -headerHeight + 'px');
    } else {
        // Scroll Up
        if (st + $(window).height() < $(document).height()) {
            $('#header').css('top', '0px');
        }
    }

    lastScrollTop = st;
}

setInterval(function () {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 10);

function goBack() {
    if (window.Android) {
        window.Android.closeView();
    } else {
        window.history.back();
    }
}

function fetchPurchases(f_pin) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let data = JSON.parse(xmlHttp.responseText);

            purchases = data;
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_purchases?f_pin=" + f_pin);
    xmlHttp.send();
}

function toggleCollection(code) {
    let codes = code.split('-');
    let productCode = codes[1];
    let isPost = codes[2];

    let item = {
        'productCode': productCode,
        'isPost': isPost
    }

    let checkCollection = items.includes(item);

    if (checkCollection) {
        let itemIndex = items.indexOf(item);
        items.splice(itemIndex, 1);
        $('.toggle-status#' + code + ' img').attr('src', '../assets/img/icons/Add-(Purple).png');
    } else {
        items.push(item);
        $('.toggle-status#' + code + ' img').attr('src', '../assets/img/icons/Delete.png');
    }
}

function toggleList() {
    $('input[type=radio][name=source-list]').change(function() {
        if ($(this).val() == 'list-purchases') {
            $('#recent-purchases').removeClass('d-none');
            $('#wishlist').addClass('d-none');
        }
        else if ($(this).val() == 'list-wishlist') {
            $('#recent-purchases').addClass('d-none');
            $('#wishlist').removeClass('d-none');
        }
    });
}

function popCollectionMsg(msg) {
    msg = '<h6>' + msg + '</h6>';
    $('#collection-msg .modal-body').html(msg);
    $('#collection-msg').modal('show');
}

function submitCollection(f_pin) {
    let collectionName = $('#collection-name').val();
    let collectionDesc = $('#collection-desc').val();
    let collectionStatus = 0;

    if ($('#collection-private').is(':checked')) {
        collectionStatus = 0;
    } else if ($('#collection-public').is(':checked')) {
        collectionStatus = 1;
    }

    if (collectionName.trim() == "") {
        popCollectionMsg("Please input a name for your collection.");
        return;
    } else if (items.length == 0) {
        popCollectionMsg('Please select one item or more for your collection.');
        return;
    } else {

        let itemString = btoa(JSON.stringify(items));

        let formData = new FormData();
        formData.append('name', collectionName);
        formData.append('desc', collectionDesc);
        formData.append('items', itemString);
        formData.append('f_pin', f_pin);
        formData.append('status', collectionStatus);
        formData.append('code', f_pin + new Date().getTime().toString());

        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == "success") {
                    $('#new-collection-success').modal('show');

                    var myModalEl = document.getElementById('new-collection-success')
                    myModalEl.addEventListener('hidden.bs.modal', function (event) {
                        window.location.href = 'tab5-main';
                    })
                }
            }
            
        }
        xmlHttp.open("post", "/nexilis/logics/add_new_collection");
        xmlHttp.send(formData);
    }
}

function countDescLength() {
    var textarea = document.getElementById("collection-desc");
    var textCounter = document.getElementById("text-length-counter");

    textarea.addEventListener("input", function () {
        var currentLength = this.value.length;

        textCounter.innerHTML = (200 - currentLength) + "/200";
    });
}

$(function () {

    let f_pin = "";

    $(window).scroll(function () {
        didScroll = true;
    });

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        let urlParams = new URLSearchParams(window.location.search);
        f_pin = urlParams.get('f_pin');
    }

    fetchPurchases(f_pin);

    toggleList();

    $('#create-collection').click(function () {
        submitCollection(f_pin);
    })

    countDescLength();
})