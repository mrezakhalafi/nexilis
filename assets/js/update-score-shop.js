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