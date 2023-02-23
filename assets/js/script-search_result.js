function eraseQuery() {
    if ($('#searchFilterForm-a input#query').val() != '') {
        $('#delete-query').removeClass('d-none');
    }

    $("#delete-query").click(function () {
        // $('#searchFilterForm-a input#query').val('');
        // $('#delete-query').addClass('d-none');
        window.location.href = "/nexilis/pages/search-result?query=";
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
    window.history.back();
}

function openShopProfile(shop_id) {
    if (window.Android) {
        let f_pin = window.Android.getFPin();

        window.location.href = 'tab3-profile.php?store_id=' + shop_id + '&f_pin=' + f_pin;
    } else {
        window.location.href = 'tab3-profile.php?store_id=' + shop_id;
    }
}

function resumeAll() {
    updateCounter();
}

$(function() {
    eraseQuery();
    updateCounter();
})