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
}

function getThumbIdReff(fPin, sub, index) {
    let thumb = '';
    try {
        if (window.Android) {
            thumb = window.Android.getImagePerson(fPin);
        }
    } catch (err) {}
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

function showProfile(fPin) {
    window.Android.showProfile(fPin);
}