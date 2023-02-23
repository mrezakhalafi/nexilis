function updateCounter() {

    let counter_badge = 0;
    let counter_position = document.getElementById('counter-here');
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
        counter_position.innerHTML =
            `
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px; top: 4px !important;">
                ${counter_badge}
            </span>
        `
    } else {
        counter_position.innerHTML = '';
    }

}



function fetchNotifCount() {
    let f_pin = '0296c47cdd';
    // let f_pin = '';
    if (window.Android) {
        f_pin = window.Android.getFPin();
    }

    let notif_order = [];
    let notif_activity = [];

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let data = JSON.parse(xmlHttp.responseText);

            notif_activity = data.notifs_activity;
            notif_order = data.notifs_orders;

            updateNotifCounter(notif_activity, notif_order);
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_notifications?f_pin=" + f_pin);
    xmlHttp.send();
}

function updateNotifCounter(arr_a, arr_b) {

    let unread_notifs = 0;
    let unread_orders = 0;
    arr_a.forEach(ex => {
        if (ex.read_status == 0) {
            unread_notifs++;
        }
    })

    arr_b.forEach(ex => {
        if (ex.read_status == 0) {
            unread_notifs++;
            unread_orders++;
        }
    })

    localStorage.setItem('notif_counter', unread_notifs);

    let counter_notif = document.getElementById('counter-notifs');
    if (counter_notif != null) {
        if (unread_notifs != 0) {
            counter_notif.innerHTML =
                `
                <span id="counter-notif-text" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px; top: 4px !important;">
                    ${unread_notifs}
                    <span class="visually-hidden">unread messages</span>
                </span>
            `
        } else {
            counter_notif.innerHTML = '';
        }
    }

    let counter_notif_subtab = document.getElementById('counter-sub-tab');
    if (counter_notif_subtab != null) {
        if (unread_orders != 0) {
            counter_notif_subtab.innerHTML =
            `
            <span id="counter-subtab-text" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px;">
                ${unread_orders}
            </span>
            `
        } else {
            counter_notif_subtab.innerHTML = '';
        }
    }
}

function toggleFB(toggle) {
    if (toggle) {
        $('#searchFilterForm-a').css('background-color', 'rgba(255,255,255,.75)');
    } else {
        $('#searchFilterForm-a').css('background-color', 'rgba(255,255,255,.55)');
    }
}

$(function () {
    updateCounter()
    fetchNotifCount();

})

window.addEventListener("storage", async function () {
    updateCounter()
}, false);