function isFilterCheckedAny() {
    let result = false;
    $('.checkbox-filter-cat').each(function () {
        if ($(this).is(':checked')) {
            result = true;
        }
    });
    return result;
}

function isFilterCheckedAll() {
    let result = true;
    $('.checkbox-filter-cat').each(function () {
        if (!$(this).is(':checked')) {
            result = false;
        }
    });
    return result;
}

function setFilterCheckedAll(checked) {
    $('.checkbox-filter-cat').each(function () {
        $(this).prop('checked', checked);
    });
}

function getFilterCheckboxValue() {
    var result = "";
    $('.checkbox-filter-cat').each(function () {
        if ($(this).is(':checked')) {
            if (result != "") {
                result = result + "-";
            }
            result = result + $(this).data('value');
        }
    });
    return result;
}

function onFocusSearch() {
    if (window.Android) {
        try {
            window.Android.onFocusSearch();
        } catch (e) {

        }
    }
}