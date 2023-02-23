function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// payment with ovo
function getWishlist(id_user) {

    let formData = new FormData();
    
    if (window.Android){
        formData.append("fpin", window.Android.getFPin());
    }else{
        formData.append("fpin", id_user);
    }

    // formData.append("fpin", "024ef50e8c");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/nexilis/logics/get_wishlist");

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

        } else { // show the result
            let responseObj = xhr.response;
            console.table(JSON.parse(responseObj));
            JSON.parse(responseObj).forEach(element => {

                var ext = '<img style="height:185.5px; width: 100%; object-fit: cover; object-position: center"';
                var ext2 = '';
                var is_vid='';

                if (element.THUMB_ID.split("|")[0].substr(-3) == 'mp4'){
                   ext = '<video style="margin-bottom: -7px; height: 185.5px; width: 100%; object-fit: cover; object-position: center"';
                   ext2 = '</video>';
                   is_vid = '#t=0.5'
                }else{
                    ext = '<img style="height: 185.5px; width: 100%; object-fit: cover; object-position: center"';
                }

                let wishlist_html = 
                '<div class="col-6 col-md-6 col-lg-4 col-xl-3 single-wishlist mb-1">'
                +ext+` src="/nexilis/images/${element.THUMB_ID.split("|")[0].replace("http://202.158.33.26", "")}`+is_vid+`" class="wishlist-images">`+ext2+
                '<img style="position: relative; z-index: 25" src="../assets/img/tab5/Add-to-Cart.png" class="add-to-cart" onclick="addToCart(\'' + element.CODE + '\', 1)">'+
                '<div class="row wishlist-desc gx-0">'+
                    `<b class="small-text">${element.NAME}</b>`+
                    `<p class="wishlist-price">Rp. ${numberWithDots(element.PRICE)}</p>`+
                '</div>'+
                '</div>';

                let wishlist_products = document.getElementById('wishlist-products');
                wishlist_products.innerHTML += wishlist_html;
            });
        }
    };

    xhr.send(formData);
}

// add product to wishlist
function addWishlist(product_code, element) {

    let star = element.querySelector('img').src;
    let operation;
    if (star == `${document.location.origin}/nexilis/assets/img/icons/Wishlist-fill.png`) {
        element.querySelector('img').src = '../assets/img/icons/Wishlist.png';
        operation = 'delete';
    } else {
        element.querySelector('img').src = '../assets/img/icons/Wishlist-fill.png'
        operation = 'add';
    }

    let formData = new FormData();
    formData.append("fpin", window.Android.getFPin());
    formData.append("code", product_code);
    formData.append('operation', operation);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/nexilis/logics/add_to_wishlist");

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

        } else { // show the result
            let responseObj = xhr.response;
            console.log(responseObj);
        }
    };

    xhr.send(formData);
}

// SEARCH WISHLIST

function getWishlistQuery(id_user, query) {

    let formData = new FormData();
    
    if (window.Android){
        formData.append("fpin", window.Android.getFPin());
    }else{
        formData.append("fpin", id_user);
    }

    formData.append("query", query);
    // formData.append("fpin", "024ef50e8c");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/nexilis/logics/get_wishlist_query");

    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

        } else { // show the result
            let responseObj = xhr.response;
            console.table(JSON.parse(responseObj));
            JSON.parse(responseObj).forEach(element => {

                var ext = '<img style="height: 185.5px;"';
                var ext2 = '';
                var is_vid = '';

                if (element.THUMB_ID.split("|")[0].substr(-3) == 'mp4'){
                   ext = '<video style="margin-bottom: -7px; height: 185.5px; width: 100%; object-fit: cover; object-position: center;"';
                   ext2 = '</video>';
                   is_vid = '#t=0.5'
                }else{
                    ext = '<img style="height: 185.5px;"';
                }

                let wishlist_html = 
                '<div class="col-6 col-md-6 col-lg-4 col-xl-3 single-wishlist mb-1">'
                +ext+` src="${"/nexilis/images/" + element.THUMB_ID.split("|")[0].replace("http://202.158.33.26", "")}`+is_vid+`" class="wishlist-images">`+ext2+
                '<img style="position: relative; z-index: 25" src="../assets/img/tab5/Add-to-Cart.png" class="add-to-cart" onclick="addToCart(\'' + element.CODE + '\', 1)">'+
                '<div class="row wishlist-desc gx-0">'+
                    `<b class="small-text">${element.NAME}</b>`+
                    `<p class="wishlist-price">Rp. ${numberWithDots(element.PRICE)}</p>`+
                '</div>'+
                '</div>';

                let wishlist_products = document.getElementById('wishlist-products');
                wishlist_products.innerHTML += wishlist_html;
            });
        }
    };

    xhr.send(formData);
}