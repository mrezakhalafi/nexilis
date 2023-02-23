var F_PIN = "0275f69fe1";
var menu = "";

if (window.Android) {
    F_PIN = window.Android.getFPin();
} else {
    // F_PIN = new URLSearchParams(window.location.search).get('f_pin');
    let currentUrl = window.location.href;

    if (currentUrl.includes('DigiNetS')) {
        F_PIN = '02f041b31a';
    } else if (currentUrl.includes('TNIADigiComm')) {
        F_PIN = '02d7c16d7a';
    }
}


getNews(0, '');

// FOR AUTOLOAD MENU FROM PREV PAGE

function goToPage(id) {

    // let f_pin = "";

    switch (id) {
        case "to-all-news":
            window.location.href = "news_update.php?f_pin=" + F_PIN;
            break;
    }
}

function timeSince(date) {

    var seconds = Math.floor((new Date() - date) / 1000);

    var interval = seconds / 31536000;

    if (interval > 1) {
        let timeInt = Math.floor(interval);
        let singular = "";
        let plural = "";
        if (localStorage.lang == 0) {
            singular = " year ago";
            plural = " years ago";
        } else {
            singular = " tahun lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " months ago" : timeInt + " month ago";
        if (localStorage.lang == 0) {
            singular = " month ago";
            plural = " months ago";
        } else {
            singular = " bulan lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 86400;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " days ago" : timeInt + " day ago";
        if (localStorage.lang == 0) {
            singular = " day ago";
            plural = " days ago";
        } else {
            singular = " hari lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 3600;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " hours ago" : timeInt + " hour ago";
        if (localStorage.lang == 0) {
            singular = " hour ago";
            plural = " hours ago";
        } else {
            singular = " jam lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    interval = seconds / 60;
    if (interval > 1) {
        let timeInt = Math.floor(interval);
        // let timeStr = timeInt > 1 ? timeInt + " minutes ago" : timeInt + " minute ago";
        if (localStorage.lang == 0) {
            singular = " minute ago";
            plural = " minutes ago";
        } else {
            singular = " menit lalu";
            plural = singular;
        }
        let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
        return timeStr;
    }
    let timeInt = Math.floor(seconds);
    if (localStorage.lang == 0) {
        singular = " second ago";
        plural = " seconds ago";
    } else {
        singular = " detik lalu";
        plural = singular;
    }
    let timeStr = timeInt > 1 ? timeInt + plural : timeInt + singular;
    // let timeStr = timeInt > 1 ? timeInt + " seconds ago" : timeInt + " second ago";
    return timeStr;
}

var offset = 0;

let domain = "/nexilis/images/";

let activeCategory = "";

function getTotalNews() {

    let formData = new FormData();
    console.log('activeCategory', activeCategory);
    if (activeCategory !== "" || activeCategory !== "all") {
        formData.append('category', activeCategory);
    }

    return new Promise(function (resolve, reject) {
        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                // // console.log(xmlHttp.responseText);
                resolve(xmlHttp.responseText);
            }
        }
        xmlHttp.open("post", "/nexilis/logics/get_total_news?f_pin=" + F_PIN);
        xmlHttp.send(formData);
    });

}

function openNews(post_id) {
    window.location.href = "news_article.php?post_id=" + post_id;
}

const getHostnameFromRegex = (url) => {
    // run against regex
    const matches = url.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i);
    // extract hostname (will be null if no match is found)
    return matches && matches[1];
}

async function getNews(offset = 0, params = "") {
    var formData = new FormData();

    // var index = offset;

    // formData.append('offset', index);
    // params = params + "&offset=" + offset;
    // let par = params + "&offset=" + offset;

    console.log('getnews', params);

    let par = "";
    if (params == "") {
        par = "?f_pin=" + F_PIN + "&offset=" + offset;
    } else {
        par = params + "&offset=" + offset
    }

    // let params = "?offset=" + index;
    // if (activeCategory !== "") {
    //     params += "&category=" + activeCategory;
    // }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            // console.log(xmlHttp.responseText);
            var data = JSON.parse(xmlHttp.responseText);
            // console.log(data);

            let news = '';

            if (data.length > 0) {
                console.log(data);

                $('#empty-news').addClass('d-none');
                // get first news

                if (offset == 0) {
                    

                    let currentTime = new Date().getTime();
                    let first_news = data[0];

                    let thumbnail = first_news.FILE_ID.split('|')[0];
                    let title = first_news.TITLE.length > 60 ? first_news.TITLE.substr(0, 60) + "..." : first_news.TITLE;
                    let time = timeSince(parseInt(first_news.CREATED_DATE));

                    let firstNews = `
                    <div class="card mt-4 mb-3" onclick="window.location.href='news_article.php?post_id=${first_news.POST_ID}'">
                        <img class="card-img-top" loading="lazy" src="${domain + thumbnail + '?r=' + currentTime}" alt="Card image cap">
                        <div class="card-body p-4">
                            <div class="news-timestamp mb-3">${time}</div>
                            <h5 class="card-text">${title}</h5>
                            <span style="font-size:11px; float:right; margin-bottom:5px;">${getHostnameFromRegex(first_news.LINK)}</span>
                        </div>
                    </div>`;

                    $('#news-section').append(firstNews);

                    let all_news = data.slice(1);

                    all_news.forEach(d => {
                        let thumbnail = d.FILE_ID.split('|')[0];

                        let title = d.TITLE.length > 45 ? d.TITLE.substr(0, 45) + "..." : d.TITLE;
                        let desc = d.DESCRIPTION.length > 50 ? d.DESCRIPTION.substr(0, 50) + "..." : d.DESCRIPTION;
                        let time = timeSince(parseInt(d.CREATED_DATE));

                        // onclick="openNews('${d.POST_ID}')"                        

                        news += `
                        <div class="row single-news mb-3 gx-0" onclick="window.location.href='news_article.php?post_id=${d.POST_ID}'">
                            <div class="col-4 news-img-col">
                                <img loading="lazy" class="news-img" src="${domain + thumbnail + '?r=' + currentTime}">
                            </div>
                            <div class="col-8 pt-3 ps-3 pe-3 pb-0">
                                <span class="text-secondary small-text">${time} | ${d.CATEGORY}</span>
                                <h5 class="news-title">${title}</h5>
                                <span style="font-size:11px; float:right; margin-bottom:5px;">${getHostnameFromRegex(d.LINK)}</span>
                            </div>
                        </div>
                        `;
                    })
                    $('#news-section').append(news);
                    $('#section-load-more').removeClass('d-none')
                } else {

                    let currentTime = new Date().getTime();

                    data.forEach(d => {
                        let thumbnail = d.FILE_ID.split('|')[0];

                        let title = d.TITLE.length > 45 ? d.TITLE.substr(0, 45) + "..." : d.TITLE;
                        let desc = d.DESCRIPTION.length > 50 ? d.DESCRIPTION.substr(0, 50) + "..." : d.DESCRIPTION;
                        let time = timeSince(parseInt(d.CREATED_DATE));

                        // onclick="openNews('${d.POST_ID}')"

                        news += `
                        <div class="row single-news mb-3 gx-0" onclick="window.location.href='news_article.php?post_id=${d.POST_ID}'">
                            <div class="col-4 news-img-col">
                                <img loading="lazy" class="news-img" src="${domain + thumbnail + '?r=' + currentTime}">
                            </div>
                            <div class="col-8 pt-3 ps-3 pe-3 pb-0">
                                <span class="text-secondary small-text">${time} | ${d.CATEGORY}</span>
                                <h5 class="news-title">${title}</h6>
                                <span style="font-size:11px; float:right; margin-bottom:5px;">${getHostnameFromRegex(d.LINK)}</span>
                            </div>
                        </div>
                        `;
                    })
                    $('#news-section').append(news);
                    $('#section-load-more').removeClass('d-none')
                }
            } else {

                if ($('.single-news').length == 0) {
                    $('#empty-news').removeClass('d-none');
                }
                $('#section-load-more').addClass('d-none')
            }



        }
    }
    xmlHttp.open("get", "../../logics/get_news" + par);
    xmlHttp.send();
}

function loadNews() {
    $('#btn-loadmore').click(async function () {
        let maxNews = await getTotalNews();
        if (offset < maxNews) {
            offset = offset + 5;
            getNews(offset, window.location.search, offset);
        }
    })
}

function selectNewsCategory() {
    $('.category').each(function (e) {
        $(this).click(async function (ev) {
            let fpin = "";
            let dest = window.location.href.split('?')[0];
            if (window.Android) {
                fpin = window.Android.getFPin();
            } else {
                fpin = new URLSearchParams(window.location.search).get('f_pin');
            }
            let params = "?f_pin=" + fpin;
            if ($(this).attr('id') === "all") {
                activeCategory = "all";
            } else {
                activeCategory = $(this).attr('id');
                params += "&category=" + activeCategory;
            }
            dest += params;
            console.log(activeCategory);
            $('#list-category .category:not(#' + activeCategory + ')').removeClass('active');
            $(this).addClass('active');
            $('#news-section').html('');
            console.log(params);
            // let selectedPos = 0;
            // try {
            //     selectedPos = document.querySelector('.category#' + activeCategory).offsetLeft;
            // } catch (e) {

            // }
            // document.querySelector('#list-category').scrollBy({
            //     left: selectedPos,
            //     behavior: 'smooth'
            // });
            await getNews(0, params);
            window.history.replaceState(null, "", dest);
        })
    })
}

function currentCategory() {
    let urlSearchParams = new URLSearchParams(window.location.search);
    let activeParam = urlSearchParams.get('category');

    if (activeParam == null) {
        activeCategory = "all";
    } else {
        activeCategory = activeParam;
    }

    if ($('#list-category .category').length > 0) {
        $('#list-category .category#' + activeCategory + '').addClass('active');
        $('#list-category .category:not(#' + activeCategory + ')').removeClass('active');
    }
}

window.onload = () => {
    loadNews();
    currentCategory();
    selectNewsCategory();
}