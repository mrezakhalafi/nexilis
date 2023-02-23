let notifs_orders = [];
let notifs_activity = [];
// let f_pin = '0297f26f80';
let f_pin = '02185ae524';
// let f_pin = window.Android.getFPin();

function goBack() {
    if (window.Android) {
        window.Android.closeView();
    } else if (window.webkit) {
        window.webkit.messageHandlers.messageHandler.postMessage({
            "message": "goBack"
        });
    } else {
        window.history.back();
    }
}

function eraseQuery() {
    if ($('#searchFilterForm-a input#query').val() != '') {
        $('#delete-query').removeClass('d-none');
    }

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

function resetSearch() {
    window.history.back();
}

function resumeAll() {
    updateCounter();
}

function fetchNotifs() {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let data = JSON.parse(xmlHttp.responseText);

            notifs_activity = data.notifs_activity;
            notifs_orders = data.notifs_orders;

            drawActivityNotif(notifs_activity);
            drawOrderNotif(notifs_orders);
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_notifications?f_pin=" + f_pin);
    xmlHttp.send();
}

function drawActivityNotif(arr) {
    let activityTab = document.querySelector('#activity-tab .container-fluid');

    if (arr.length > 0) {
        activityTab.innerHTML = '';
        arr.forEach(ex => {
            let read_sts = '';
            if (ex.read_status == 0) {
                read_sts = '<span class="material-icons read-status">fiber_manual_record</span> ';
            }
            let content = '<span class="notification-name">' + ex.follower_name + '</span> followed you.'
            activityTab.innerHTML += `
            <div class="row py-3 activity-items">
                <div class="col-11 mx-auto">
                    <div class="row">
                        <div class="col-2">
                            <img class="logo-merchant" src="../images/uwitan.webp">
                        </div>
                        <div class="col-10 ps-1 align-self-center">

                            <div class="row">
                                <div class="col-9 ps-2">
                                    ${read_sts + content}
                                </div>
                                <div class="col-3 text-end">
                                    <span class="time">${ex.time}></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        })
    } else {
        activityTab.innerHTML = `<div class="row">
            <div class="col-12 text-center">
                <h6>Belum ada notifikasi</h6>
            </div>
        </div>`;
    }
}

function openOrders(id, isBuyer) {
    if (isBuyer) {
        window.location.href = 'tab5-orders.php?f_pin=' + id;
    } else {
        window.location.href = 'tab5-your-orders.php?shop_code=' + id;
    }
}

function ext(url) {
    // return new Promise(function (resolve, reject) {
    //     resolve(url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."));
    // });
    return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."))
}

var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];


function drawOrderNotif(arr) {
    let orderTab = document.querySelector('#order-tab .container-fluid');

    if (arr.length > 0) {
        orderTab.innerHTML = '';
        arr.forEach(ex => {
            console.log('start loop');
            let read_sts = '';
            if (ex.read_status == 0) {
                read_sts = `<div class="col-auto p-0">
                    <span class="material-icons read-status">
                        fiber_manual_record
                    </span>
                </div>`;
            }

            let isBuyer = ex.buyer == f_pin;

            let id = '';

            if (!isBuyer) {
                id = ex.merchant_code;
            } else {
                id = ex.buyer;
            }

            let thumb_id = ex.product_thumb;

            let thumb = '';

            let ph1 = thumb_id.substr(1 + thumb_id.lastIndexOf("/")).split('?')[0];
            let ph2 = ph1.split('#')[0].substr(ph1.lastIndexOf(".") + 1);

            console.log(ph2);

            if ($image_type_arr.includes(ph2)) {
                thumb = '<img class="logo-order" src="' + thumb_id + '">';
            } else if ($video_type_arr.includes(ph2)) {
                // thumb = await getVideoImage(thumb_id, 3);
                let img_name = thumb_id.replace(ph2, "");
                console.log(img_name);
                thumb = `
                <video class="logo-order" autoplay muted preload="metadata">
                <source src="${thumb_id}">
                </video>
                `;
            }

            let header_first = `
            <div class="row py-3 activity-items ${parseInt(ex.state) == 3 && f_pin == ex.buyer ? 'order-sent' : ''}" id="trx-${ex.trx_id}" onclick="openOrders('${id}','${isBuyer}')">
                <div class="col-11 mx-auto">
                    <div class="row">
                        <div class="col-2 px-1">
            `;

            let header_bottom = `
            </div>
            <div class="col-10 align-self-center ${parseInt(ex.state) == 3 && f_pin == ex.buyer ? 'order-group' : ''}">
            `;



            let content = '';

            if (ex.state == '4' && f_pin != ex.buyer) {
                content = `
                <div class="row">
                    <div class="col-10 ps-1">
                    <span class="notification-name">Order <a class="a-trx-id">${ex.trx_id}</a> telah diterima pembeli.</span>
                        <p class="mb-0">
                            Produk telah sampai pada pembeli.
                        </p>
                    </div>
                    <div class="col-1 pe-0 align-self-center text-end">
                        <span class="time">${ex.time}</span>
                    </div>
                </div>
                `;
            } else if (ex.state == '4' && f_pin == ex.buyer) {
                content = `
                <div class="row">
                    <div class="col-10 ps-1">
                    <span class="notification-name">Order <a class="a-trx-id">${ex.trx_id}</a> telah diterima.</span>
                        <p class="mb-0">
                            Segera periksa kelengkapan produk Anda. Jangan lupa beri penilaian pada produk.
                        </p>
                    </div>
                    <div class="col-1 pe-0 align-self-center text-end">
                        <span class="time">${ex.time}</span>
                    </div>
                </div>
                `;
            } else if (ex.state == '3' && f_pin == ex.buyer) {
                content = `
                <div class="row">
                    <div class="col-10 pb-3 order-sub-item ps-1">
                        <span class="notification-name">Order <a class="a-trx-id">${ex.trx_id}</a> telah dikirim.</span>
                        <p class="mb-0">
                            Paket telah dikirimkan oleh penjual.
                        </p>
                    </div>
                    <div class="col-2 pb-3 pe-2 align-self-center text-end">
                        <span class="time">11:25</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-10 pt-3 order-sub-item ps-1">
                        <span class="notification-name">Order <a class="a-trx-id">${ex.trx_id}</a> terkonfirmasi.</span>
                        <p class="mb-0">
                            Pembayaran terkonfirmasi. Mohon menunggu produk dikirimkan oleh penjual.
                        </p>
                    </div>
                    <div class="col-2 pt-3 pe-2 align-self-center text-end">
                        <span class="time">11:25</span>
                    </div>
                </div>
                `;
            } else if (ex.state == '3' && f_pin != ex.buyer) {
                content = `
                <div class="row">
                    <div class="col-10 ps-1">
                    <span class="notification-name">Order <a class="a-trx-id">${ex.trx_id}</a> terkonfirmasi.</span>
                        <p class="mb-0">
                            Pembayaran terkonfirmasi. Mohon segera mengirimkan produk kepada pembeli.
                        </p>
                    </div>
                    <div class="col-1 pe-0 align-self-center text-end">
                        <span class="time">${ex.time}</span>
                    </div>
                </div>
                `;
            } else if (ex.state == '2') {
                content = `
                <div class="row">
                    <div class="col-10 ps-1">
                    <span class="notification-name">Order <a class="a-trx-id">${ex.trx_id}</a> terkonfirmasi.</span>
                        <p class="mb-0">
                            Pembayaran terkonfirmasi. Mohon menunggu produk dikirimkan oleh penjual.
                        </p>
                    </div>
                    <div class="col-1 pe-0 align-self-center text-end">
                        <span class="time">${ex.time}</span>
                    </div>
                </div>
                `;
            }

            let footer = `
            </div></div></div></div>
            `;

            let header = header_first + thumb + header_bottom;

            let all = header + content + footer;

            orderTab.innerHTML += all;
        })
    } else {
        orderTab.innerHTML = `<div class="row">
            <div class="col-12 text-center">
                <h6>Belum ada notifikasi</h6>
            </div>
        </div>`;
    }
}

function readActivityNotif() {

}

function updateCounterNotifText(amt) {
    var currentAmt = parseInt($('span#counter-notif-text').html());

    currentAmt = currentAmt - amt;

    if (currentAmt == 0) {
        $('span#counter-notifs').html('');
    } else {
        $('span#counter-notif-text').html(currentAmt);
    }
}

function readOrderNotif() {
    let formData = new FormData();

    let unread_order_notifs = [];

    notifs_orders.forEach(ex => {
        if (ex.read_status == 0) {
            unread_order_notifs.push(ex.notif_id);
        }
    })

    formData.append('notif_ids', JSON.stringify(unread_order_notifs));

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            updateCounterNotifText(unread_order_notifs.length);

            let prevNotifCount = parseInt(localStorage.getItem('notif_counter'));

            let currentNotifCount = prevNotifCount - unread_order_notifs.length;

            localStorage.setItem('notif_counter', currentNotifCount);
            let counter_notif_subtab = document.getElementById('counter-sub-tab');
            counter_notif_subtab.innerHTML = '';
        }
    }
    xmlHttp.open("post", "/nexilis/logics/read_notifs");
    xmlHttp.send(formData);
}

function checkCurrentTab() {
    let activeTab = window.location.hash.substr(1);

    if (activeTab == 'activity' || activeTab == '') {
        $('.nav-link#activity').addClass('active');
        $('.nav-link:not(#activity)').removeClass('active');
        $('.tab-content #activity-tab').addClass('show');
        $('.tab-pane#activity-tab').addClass('active');
        $('.tab-pane:not(#activity-tab)').removeClass('show');
        $('.tab-pane:not(#activity-tab)').removeClass('active');
    } else if (activeTab == 'order') {
        $('.nav-link#order').addClass('active');
        $('.nav-link:not(#order)').removeClass('active');
        $('.tab-content #order-tab').addClass('show');
        $('.tab-pane#order-tab').addClass('active');
        $('.tab-pane:not(#order-tab)').removeClass('show');
        $('.tab-pane:not(#order-tab)').removeClass('active');
    }
}

$(function () {
    fetchNotifs();
    eraseQuery();
    updateCounter();
    checkCurrentTab();

    $('.nav-link#activity').click(function () {
        window.location.hash = $(this).attr('id');
        $('.tab-pane#activity-tab').addClass('show');
        $('.tab-pane#activity-tab').addClass('active');
        $('.tab-pane#order-tab').removeClass('show');
        $('.tab-pane#order-tab').removeClass('active');
        $('.tab-pane#promotion-tab').removeClass('show');
        $('.tab-pane#promotion-tab').removeClass('active');
    })

    $('.nav-link#order').click(function () {
        window.location.hash = $(this).attr('id');
        readOrderNotif();
        $('.tab-pane#activity-tab').removeClass('show');
        $('.tab-pane#activity-tab').removeClass('active');
        $('.tab-pane#order-tab').addClass('show');
        $('.tab-pane#order-tab').addClass('active');
        $('.tab-pane#promotion-tab').removeClass('show');
        $('.tab-pane#promotion-tab').removeClass('active');
    })

    // $('.nav-link#promotion').click(function() {
    //     $('.tab-pane#activity-tab').removeClass('show');
    //     $('.tab-pane#activity-tab').removeClass('active');
    //     $('.tab-pane#order-tab').removeClass('show');
    //     $('.tab-pane#order-tab').removeClass('active');
    //     $('.tab-pane#promotion-tab').addClass('show');
    //     $('.tab-pane#promotion-tab').addClass('active');
    // })
})