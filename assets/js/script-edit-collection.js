let collections = [];

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

function fetchCollection (f_pin) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let data = JSON.parse(xmlHttp.responseText);

            collections = data;
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_collection?f_pin=" + f_pin);
    xmlHttp.send();
}

function changeVisibilityStatus(collection_code) {
    let collection = collections.find(col => col.COLLECTION_CODE == collection_code);

    let flag = 0;

    if (collection.STATUS == 0) {
        flag = 1;
        collection.STATUS = 1;
    } else {
        flag = 0;
        collection.STATUS = 0;
    }

    let formData = new FormData();
    formData.append('code', collection_code);
    formData.append('status_flag', flag);

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            // alert('Collection visibility updated');
            if (xmlHttp.responseText == 'visibility updated') {
                if (flag == 1) {
                    $('#status-' + collection_code + ' img').attr('src', '../assets/img/icons/Eye-Icon-purple.png');
                    $('#status-' + collection_code + ' p').text('Public');
                    $('#status-' + collection_code + ' p').addClass('text-purple');
                } else {
                    $('#status-' + collection_code + ' img').attr('src', '../assets/img/icons/Security-darkgrey.png');
                    $('#status-' + collection_code + ' p').text('Private');
                    $('#status-' + collection_code + ' p').removeClass('text-purple');
                }                
                $('#update-visibility-success').modal('show');
            } else {
                alert('Error: ' + xmlHttp.responseText);
            }
        }
    }
    xmlHttp.open("post", "/nexilis/logics/change_collection_visibility");
    xmlHttp.send(formData);
}

function deleteCollection(collection_code) {

    $('#delete-collection-ok').off('click');

    let collection_name = collections.find(el => el.COLLECTION_CODE == collection_code).NAME;

    $('#delete-collection-prompt .modal-body').html('<h6>Delete collection "' + collection_name + '"?');

    $('#delete-collection-prompt').modal('show');

    $('#delete-collection-ok').click(function() {
        let formData = new FormData();
        formData.append('code', collection_code);

        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                if (xmlHttp.responseText == "collection deleted") {
                    let index = collections.indexOf(col => col.COLLECTION_CODE == collection_code);
                    collections.splice(index, 1);
                    $('#collection-' + collection_code).remove();
                    $('#delete-collection-prompt').modal('hide');
                    $('#delete-success').modal('show');
                } else {
                    console.log('Error: ' + xmlHttp.responseText);
                    $('#delete-error .modal-content').html('<h6>An error occured.</h6>');
                    $('#delete-error').modal('show');
                }
            }
        }
        xmlHttp.open("post", "/nexilis/logics/delete_collection");
        xmlHttp.send(formData);
    })
}

function checkPurchases(f_pin) {
    if (purchases.length > 0) {
        window.location.href = 'tab5-new-collection?f_pin=' + f_pin;
    } else {
        $('#no-purchases').modal('show');
    }
}

let purchases = [];
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


$(function() {
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

    console.log(f_pin);
    fetchPurchases(f_pin);
    fetchCollection(f_pin);

    $('#to-new-collection').click(function() {
        checkPurchases(f_pin);
    })
})