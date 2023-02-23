function updateScoreShop(shop_code) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '/nexilis/logics/rating_engine/rating_engine_shop.php?shop_code=' + shop_code);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            console.log(`Something went wrong.`); // e.g. 404: Not Found

        } else { // show the result
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
            console.log(`Success.`); // response is the server response
        }
    };

    xhr.onerror = function () {
        // alert("Request failed");
        console.log("Request failed");
    };
}

function updateScore(product_code, activity, flag_like=false){
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('product_code', product_code);
    formData.append('activity', activity);
    if (activity == 'like') {
        formData.append('flag_like', flag_like == true ? 1 : 0);
    }

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('POST', '/nexilis/logics/rating_engine/rating_engine');

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send(formData);

    // 4. This will be called after the response is received
    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            console.log(`Something went wrong.`); // e.g. 404: Not Found

        } else { // show the result
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
            console.log(`Success.`); // response is the server response
            // updateScoreShop(shop_code);
        }
    };

    xhr.onerror = function () {
        // alert("Request failed");
        console.log("Request failed");
    };
}