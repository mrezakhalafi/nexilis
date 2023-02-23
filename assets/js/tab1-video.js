var f_pin = "";
var loadVideo = 0;
var limitVideo = 0;
var likedPost = [];
var isSearchHidden = true;
var defaultCategory = '';
var activeFilter = '';
let currentUrl = '';
let limit = 10;
let offset = 0;
let isCalled = false;
let busy = false;
var navbarToTop = $('#company-logo-wrap').outerHeight();
var listLS = '';
var otherFilter = {
    friends: 0,
    verified: 1,
    others: 0
}
let mainVid = '';
let mainPoster = '';
var query = '';
let disableMainVid = false;
// '["02c4fab872|1665463735793|Fera  |profile-183C531472C.jpg|0~test ls 2|02c4fab872|3","02c4fd03a4|1665463735793|iksan  ||0~heyjude|02c4fd03a4|3"]'
// '["02c4fd03a4|1665462305190|iksan  ||1~hello 2|02c4fd03a4|4"]'
// 02ad34b479|1665371651354|Ronaldo  |-

let domain = "";

if (window.Android) {
    f_pin = window.Android.getFPin();

} else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
}

if (window.Android) {
    window.Android.tabShowHide(true);
} else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.tabShowHide) {
    window.webkit.messageHandlers.tabShowHide.postMessage({
        param1: true,
    });
}

function voiceSearch() {
    if (window.Android) {
        $isVoice = window.Android.toggleVoiceSearch();
        toggleVoiceButton($isVoice);
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.toggleVoiceSearch) {
        window.webkit.messageHandlers.toggleVoiceSearch.postMessage({
            param1: ""
        });
    }
}

function submitVoiceSearch($searchQuery) {
    // // // // console.log("submitVoiceSearch " + $searchQuery);
    $('#query').val($searchQuery);

    $('#delete-query').removeClass('d-none');
    searchFilter();
}

function toggleVoiceButton($isActive) {
    if ($isActive) {
        $("#voice-search").attr("src", "../assets/img/action_mic_blue.png");
    } else {
        $("#voice-search").attr("src", "../assets/img/action_mic.png");
    }
}

async function firstRunVideo(url = "") {

    var formData = new FormData();
    formData.append('offset', 0);
    formData.append('f_pin', f_pin);
    formData.append('limit', limit);

    if (url != "") {

        let newurl = new URL(url);

        let urlParams = new URLSearchParams(newurl.search);

        if (urlParams.get('filter') != null && urlParams.get('filter') != '') {
            let filter = urlParams.get('filter');
            formData.append('filter', filter);
        }

        if (urlParams.get('query') != null && urlParams.get('query') != '') {
            let query = urlParams.get('query');
            formData.append('query', query);
        }
    }

    formData.append('verified', parseInt(otherFilter.verified));
    formData.append('friends', parseInt(otherFilter.friends));
    formData.append('others', parseInt(otherFilter.others));

    for (var pair of formData.entries()) {
        // console.log(pair[0] + ', ' + pair[1]);
    }

    let anyLS = await getListLS();

    console.log('anyLS', anyLS);

    // GET VIDEO

    let getVideo = new XMLHttpRequest();
    getVideo.onreadystatechange = function () {
        if (getVideo.readyState == 4 && getVideo.status == 200) {


            $('#section-list-video').html('');
            let response = JSON.parse(getVideo.responseText);
            // response.unshift(anyLS);
            anyLS.forEach(e => {
                response.unshift(e);
            })

            console.log(response);
            var arrayVid = response.length;

            if (arrayVid == 0) {

                var html = `<div class="container text-center pt-5">
                                <p>No Video Available</p>
                            </div>`;

                $('#section-list-video').html(html);
                // $('#btnLoadMore').addClass('d-none');

            } else {

                // $('#btnLoadMore').removeClass('d-none');

                for (var i = 0; i < arrayVid; i++) {

                    var title = response[i].TITLE;
                    var decodeTitle = decodeURIComponent((title + '').replace(/\+/g, '%20'));
                    var owner = response[i].USERNAME;
                    var likes = response[i].TOTAL_LIKES;
                    var comments = response[i].COMMENT_USER;
                    var verified = response[i].OFFICIAL_ACCOUNT;
                    var source = response[i].FILE_ID;
                    var thumb = response[i].THUMB_ID;
                    var link = response[i].USER_PIN;
                    var post_id = response[i].POST_ID;

                    var is_liked = response[i].IS_LIKED;
                    var is_comment = response[i].IS_COMMENT;
                    var is_ls = response[i].IS_LS;
                    var is_follow = response[i].IS_FOLLOW;
                    var follow_count = response[i].FOLLOW_SHOP;

                    if (is_liked == 1) {
                        // var likedImg = "jim_likes_red.png";
                        var likedImg = "jim_likes_red.png";
                    } else {
                        var likedImg = "jim_likes.png";
                    }

                    if (is_comment == 1) {
                        // var commentsImg = "jim_comments_blue.png";
                        var commentsImg = "jim_comments_blue.png";
                    } else {
                        var commentsImg = "jim_comments.png";
                    }

                    if (is_follow == 1) {
                        // var commentsImg = "jim_comments_blue.png";
                        var followImg = "followed.svg";
                    } else {
                        var followImg = "follow.svg";
                    }

                    var trimTitle = decodeTitle;

                    if (decodeTitle.length > 35) {
                        trimTitle = decodeTitle.substr(0, 35) + "...";
                    }

                    if (is_ls == 0) {
                        if (thumb == "" || thumb == null || thumb.split(".")[1] == "mp4") {

                            thumb = "../assets/img/empty-thumbnail.jpg";

                        } else {

                            if (thumb.includes("|")) {

                                var source_split = source.split("|");
                                var thumb_split = thumb.split("|");

                                console.log(source_split);

                                for (var i=0; i<source_split.length; i++){

                                    if (source_split[i].includes(".mp4")){

                                        var thumb_single = thumb_split[i];
                                        thumb = domain + "/nexilis/images/" + thumb_single;
                                        break;

                                    }

                                }

                            } else {

                                thumb = domain + "/nexilis/images/" + thumb;

                            }

                        }
                    } else {
                        if (thumb != "") {
                            thumb = domain + "/filepalio/image/" + thumb;
                        } else {
                            thumb = '/nexilis/assets/img/ic_person_boy.png';
                        }
                    }

                    let verif_icon = `
                    <div class="col-1 px-0 d-flex align-items-center">
                        <img src="../assets/img/ic_verified_flag.png" style="width: 13px; height: 13px; margin-left: 3px;"> 
                    </div>`;

                    if (localStorage.lang == 1) {
                        var recorded = is_ls == 0 ? "REKAMAN" : "LIVE";
                    } else {
                        var recorded = is_ls == 0 ? "RECORDED" : "LIVE";
                    }

                    var singleVid = `<div id="video-${post_id}" class="row gx-0 shadow-sm m-2" style="border-radius: 10px">
                                        <div class="col-5" ${is_ls == 0 ? "onclick=\"changeVideo('" + source + "', '" + thumb + "')\"" : "onclick=\"openLiveStream('"+ response[i].DATA +"')\""}>
                                            <div class="video-thumb-wrap">
                                                <img class="video-thumb" src="` + thumb + `" >
                                                <span class="text-white p-1 text-recorded">` + recorded + `</span>
                                            </div>
                                        </div>
                                        <div class="col-7 video-info" style=" padding: .5rem .75rem;">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h6 class="title" onclick="openProfile('` + link + `')">` + trimTitle + `</h6>
                                                </div>
                                                <div class="col-2">
                                                    <div class="dropdown">
                                                        <a class="post-status dropdown-toggle" data-bs-toggle="dropdown" id="report-`+post_id+`">
                                                            <img src="../assets/img/icons/More.png" height="25" width="25" style="background-color:unset;">
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="report-`+post_id+`">
                                                            <li>
                                                                <a class="dropdown-item button_report" onclick="reportContent('${post_id}','0')">Report/flag Content</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item button_report" onclick="reportUser('${link}')">Report/flag User</a>
                                                            </li>
                                                            <li>
                                                                <a style="color:brown" class="dropdown-item button_report" onclick="blockContent('${post_id}')">Remove/Block Content</a>
                                                            </li>
                                                            <li>
                                                                <a style="color:brown" class="dropdown-item button_report" onclick="blockUser('${link}')">Remove/Block User</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 bottom-info col-10" style="color: grey; font-size: 13px">
                                                <div class="row d-flex align-items-center" style="margin-left:1px">
                                                    ${verified == 2 ? verif_icon : ''}
                                                    <div class="col-11 ${verified == 2 ? 'ps-2 pe-0' : 'px-0'}">
                                                        <h6 class="mb-0 owner-name">${owner}</h6>
                                                    </div>
                                                </div>
                                                <div class="row section-social">
                                                    <div class="col-4" onclick="likeProduct('${post_id}','1')">
                                                        <img class="like-icon" id="like-${post_id}" src="../assets/img/${likedImg}"> <span class="like-counter" id="like-counter-${post_id}">` + likes + `</span>
                                                    </div>
                                                    <div class="col-4" onclick="openComment('${post_id}','1')">
                                                        <img class="comment-icon" src="../assets/img/${commentsImg}"> <span class="comment-counter">` + comments + ` </span>
                                                    </div>
                                                    <div class="col-4" id="follow-div-${post_id}" onclick="followUser('${link}','${is_follow}','${post_id}')">
                                                        <img id="follow-${link}" class="comment-icon" src="../assets/img/social/${followImg}" style="opacity: 0.6"> <span id="follow-counter-${post_id}" style="color: #3678bd">` + follow_count + ` </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

                    $('#section-list-video').append(singleVid);

                }


            }
            console.log(response);
            var mainVideo = response.find(e => e.IS_LS == 0);

            var poster = '';

            if (typeof mainVideo === 'undefined') {
                poster = "../assets/img/empty-thumbnail.jpg";
            } else {

                // console.log(mainVideo);

                var mainVideoThumb = mainVideo.THUMB_ID;
                var mainVideoSource = mainVideo.FILE_ID;

                if (mainVideoThumb == "" || mainVideoThumb == null || mainVideoThumb.split(".")[1] == "mp4") {
                    poster = "../assets/img/empty-thumbnail.jpg";
                } else {

                    if (mainVideoThumb.includes("|")) {

                        var mainVideoThumbSplit = mainVideoThumb.split("|");
                        var mainVideoSourceSplit = mainVideoSource.split("|");

                        // console.log("THUMB"+mainVideoThumbSplit);
                        // console.log("SOURCE"+mainVideoSourceSplit);

                        for (var i=0; i<mainVideoThumbSplit.length; i++){

                            if (mainVideoSourceSplit[i].includes(".mp4")){
                                poster = domain + "/nexilis/images/" + mainVideoThumbSplit[i];
                                // console.log(">>>"+mainVideoSourceSplit);
                                break;
                            }

                        }

                    } else {

                        poster = domain + "/nexilis/images/" + mainVideoThumb;

                    }
                }

            }

            // $('#main-video').attr('src', domain + '/nexilis/images/' + response[0].FILE_ID);
            $('#main-video').attr('src', poster);
            $('#main-video').attr('poster', poster);

            if (response.length == 0) {
                $('#text-recorded-main').addClass('d-none')
            } else {
                $('#text-recorded-main').removeClass('d-none')
            }

            if (localStorage.lang == 0) {
                // $('input#query').attr('placeholder', 'Search');
                document.getElementById('query').placeholder = "Search";
                $("#text-recorded-main").text("RECORDED");
            } else {
                document.getElementById('query').placeholder = "Pencarian";
                $("#text-recorded-main").text("REKAMAN");
            }

            $('body').css('visibility', 'visible');

            mainVid = mainVideo.FILE_ID;
            mainPoster = poster;
            $('#main-video').click(function () {
                changeVideo(mainVideo.FILE_ID, poster);
            })
        }
    }
    getVideo.open("post", "../logics/get_video");
    getVideo.send(formData);
}

// END

function checkLimitVideo(url = "") {

    // GET LIMIT VIDEO
    isCalled = true;

    return new Promise(function (resolve, reject) {

        var formData = new FormData();
        formData.append('f_pin', f_pin);

        if (url != "") {

            let newurl = new URL(url);

            let urlParams = new URLSearchParams(newurl.search);

            if (urlParams.get('filter') != null && urlParams.get('filter') != '') {
                let filter = urlParams.get('filter');
                formData.append('filter', filter);
            }

            if (urlParams.get('query') != null && urlParams.get('query') != '') {
                let query = urlParams.get('query');
                formData.append('query', query);
            }
        }

        formData.append('verified', parseInt(otherFilter.verified));
        formData.append('friends', parseInt(otherFilter.friends));
        formData.append('others', parseInt(otherFilter.others));

        for (var pair of formData.entries()) {
            // console.log(pair[0] + ', ' + pair[1]);
        }

        let getLimit = new XMLHttpRequest();
        getLimit.onreadystatechange = function () {
            if (getLimit.readyState == 4 && getLimit.status == 200) {

                // console.log(getLimit.responseText);
                // limitVideo = parseInt(getLimit.responseText);
                resolve(parseInt(getLimit.responseText));

            }
        }
        getLimit.open("post", "../logics/get_limit_video");
        getLimit.send(formData);
    })

    // END LIMIT VIDEO

}

function changeVideo(source, poster) {

    // $('#main-video').attr('poster', poster);
    // $('#main-video').attr('src', poster);

    // $('#main-video').attr('src', domain + '/nexilis/images/' + source);

    // FOR MULTIPLE VIDEO UPLOAD

    var source_split = source.split("|");
    var poster_split = poster.split("|");
    
    for (var i=0; i<source_split.length; i++){

        if (source_split[i].includes(".mp4")){

            source = source_split[i];
            poster = poster_split[i];
            break;

        }

    }

    if (disableMainVid == false) {
        $('.video-wrap video').attr('src', domain + '/nexilis/images/' + source);
        $('.video-wrap video').attr('poster', poster);

        showHideFilter(false);

        // $('#video-pop').modal('show');
        $('.video-wrap').removeClass('d-none');

        setTimeout(function() {
            document.querySelector('#video-wrap video').play();
        }, 1000);
    }
}

function openProfile(link) {

    localStorage.setItem('origin_page', window.location.href);

    window.location.href = "tab3-profile.php?f_pin=" + f_pin + "&store_id=" + link;

}

function LoadMore(url = "") {

    // checkLimitVideo();

    loadVideo = loadVideo + limit;
    // console.log(loadVideo);



    var formData = new FormData();
    formData.append('offset', loadVideo);
    formData.append('f_pin', f_pin);
    formData.append('limit', limit);

    if (url != "") {

        let newurl = new URL(url);

        let urlParams = new URLSearchParams(newurl.search);

        if (urlParams.get('filter') != null && urlParams.get('filter') != '') {
            let filter = urlParams.get('filter');
            formData.append('filter', filter);
        }

        if (urlParams.get('query') != null && urlParams.get('query') != '') {
            let query = urlParams.get('query');
            formData.append('query', query);
        }
    }

    formData.append('verified', parseInt(otherFilter.verified));
    formData.append('friends', parseInt(otherFilter.friends));
    formData.append('others', parseInt(otherFilter.others));

    for (var pair of formData.entries()) {
        // console.log(pair[0] + ', ' + pair[1]);
    }

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {


            let response = JSON.parse(xmlHttp.responseText);
            // console.log(response);

            var arrayVid = response.length;

            if (arrayVid == 0) {

                // console.log("OVER");

                if (localStorage.lang == 1) {
                    var vidCon = "Tidak Ada Video yang Tersedia";
                } else {
                    var vidCon = "No More Video Available";
                }

                var html = `<div id="noMoreVideo" class="container text-center pt-3">
                                <p>` + vidCon + `</p>
                            </div>`;

                $('#section-list-video').append(html);
                // $('#btnLoadMore').addClass('d-none');

            } else {

                for (var i = 0; i < arrayVid; i++) {

                    var title = response[i].TITLE;
                    var decodeTitle = decodeURIComponent((title + '').replace(/\+/g, '%20'));
                    var post_id = response[i].POST_ID;
                    var owner = response[i].USERNAME;
                    var likes = response[i].TOTAL_LIKES;
                    var comments = response[i].COMMENT_USER;
                    var followed = response[i].FOLLOW_SHOP;
                    var verified = response[i].OFFICIAL_ACCOUNT;
                    var source = response[i].FILE_ID;
                    var thumb = response[i].THUMB_ID;
                    var link = response[i].USER_PIN;

                    var is_liked = response[i].IS_LIKED;
                    var is_comment = response[i].IS_COMMENT;
                    var is_ls = response[i].IS_LS;
                    var is_follow = response[i].IS_FOLLOW;
                    var follow_count = response[i].FOLLOW_SHOP;

                    if (is_liked == 1) {
                        // var likedImg = "jim_likes_red.png";
                        var likedImg = "jim_likes_red.png";
                    } else {
                        var likedImg = "jim_likes.png";
                    }

                    if (is_comment == 1) {
                        // var commentsImg = "jim_comments_blue.png";
                        var commentsImg = "jim_comments_blue.png";
                    } else {
                        var commentsImg = "jim_comments.png";
                    }

                    if (is_follow == 1) {
                        // var commentsImg = "jim_comments_blue.png";
                        var followImg = "followed.svg";
                    } else {
                        var followImg = "follow.svg";
                    }

                    var trimTitle = decodeTitle;

                    if (decodeTitle.length > 35) {
                        trimTitle = decodeTitle.substr(0, 35) + "...";
                    }

                    if (is_ls == 0) {
                        if (thumb == "" || thumb == null || thumb.split(".")[1] == "mp4") {

                            thumb = "../assets/img/empty-thumbnail.jpg";

                        } else {

                            if (thumb.includes("|")) {

                                var thumb_single = thumb.split("|")[0];
                                thumb = domain + "/nexilis/images/" + thumb_single;

                            } else {

                                thumb = domain + "/nexilis/images/" + thumb;

                            }

                        }
                    } else {
                        if (thumb != "") {
                            thumb = domain + "/filepalio/image/" + thumb;
                        } else {
                            thumb = '/nexilis/assets/img/ic_person_boy.png';
                        }
                    }

                    let verif_icon = `
                    <div class="col-1 px-0 d-flex align-items-center">
                        <img src="../assets/img/ic_verified_flag.png" style="width: 13px; height: 13px; margin-left: 3px;"> 
                    </div>`;

                    if (localStorage.lang == 1) {
                        var recorded = "REKAMAN";
                    } else {
                        var recorded = "RECORDED";
                    }

                    var singleVid = `<div id="video-${post_id}" class="row gx-0 shadow-sm m-2" style="border-radius: 10px">
                                        <div class="col-5" onclick="changeVideo('` + source + `', '` + thumb + `')">
                                            <div class="video-thumb-wrap">
                                                <img class="video-thumb" src="` + thumb + `" >
                                                <span class="text-white p-1 text-recorded">` + recorded + `</span>
                                            </div>
                                        </div>
                                        <div class="col-7 video-info" style=" padding: .5rem .75rem;">
                                            <div class="row">
                                                <div class="col-10">
                                                    <h6 class="title" onclick="openProfile('` + link + `')">` + trimTitle + `</h6>
                                                </div>
                                                <div class="col-2">
                                                    <div class="dropdown">
                                                        <a class="post-status dropdown-toggle" data-bs-toggle="dropdown" id="report-`+post_id+`">
                                                            <img src="../assets/img/icons/More.png" height="25" width="25" style="background-color:unset;">
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="report-`+post_id+`">
                                                            <li>
                                                                <a class="dropdown-item button_report" onclick="reportContent('${post_id}','0')">Report/flag Content</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item button_report" onclick="reportUser('${link}')">Report/flag User</a>
                                                            </li>
                                                            <li>
                                                                <a style="color:brown" class="dropdown-item button_report" onclick="blockContent('${post_id}')">Remove/Block Content</a>
                                                            </li>
                                                            <li>
                                                                <a style="color:brown" class="dropdown-item button_report" onclick="blockUser('${link}')">Remove/Block User</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 bottom-info col-10" style="color: grey; font-size: 13px">
                                                <div class="row d-flex align-items-center" style="margin-left:1px">
                                                    ${verified == 2 ? verif_icon : ''}
                                                    <div class="col-11 ${verified == 2 ? 'ps-2 pe-0' : 'px-0'}">
                                                        <h6 class="mb-0 owner-name">${owner}</h6>
                                                    </div>
                                                </div>
                                                <div class="row section-social" onclick="likeProduct('${post_id}','1')">
                                                    <div class="col-4">
                                                        <img class="like-icon" id="like-${post_id}" src="../assets/img/${likedImg}"> <span class="like-counter" id="like-counter-${post_id}">` + likes + `</span>
                                                    </div>
                                                    <div class="col-4" onclick="openComment('${post_id}','1')">
                                                        <img class="comment-icon" src="../assets/img/${commentsImg}"> <span class="comment-counter">` + comments + ` </span>
                                                    </div>
                                                    <div class="col-4" id="follow-div-${post_id}" onclick="followUser('${link}','${is_follow}','${post_id}')"> 
                                                        <img id="follow-${link}" class="comment-icon" src="../assets/img/social/${followImg}" style="opacity: 0.6"> <span class="follow-counter-${post_id}" style="color: #3678bd">` + comments + ` </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

                    $('#section-list-video').append(singleVid);

                }

                // $('#btnLoadMore').removeClass('d-none');
                $('#noMoreVideo').remove();

                busy = false;

            }


        }
    }
    xmlHttp.open("post", "../logics/get_video");
    xmlHttp.send(formData);
}

function followUser(l_pin, is_follow, post_id) {

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
        f_pin = window.Android.getFPin();
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: $user + '|' + $flag, // values to be provided to followUser
            param2: 'follow_user'
        });
        return;
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    var curTime = (new Date()).getTime();

    var formData = new FormData();

    formData.append('l_pin', l_pin);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_follow', is_follow);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            var response = xmlHttp.responseText;
            var currentFollow = parseInt($('#follow-counter-'+post_id).text());
            console.log("Before do :"+currentFollow);

            if (response == 1){
                $("#follow-" + l_pin).attr("src", "../assets/img/social/followed.svg");

                currentFollow = currentFollow + 1;
                $('#follow-counter-'+post_id).text(currentFollow);

                // CHANGE FLAG IF DOUBLE CLICK TO FOLLOW UNFOLLOW

                $('#follow-div-'+post_id).attr('onclick','followUser("'+l_pin+'","1","'+post_id+'")');

            }else{
                $("#follow-" + l_pin).attr("src", "../assets/img/social/follow.svg");

                currentFollow = currentFollow - 1;
                $('#follow-counter-'+post_id).text(currentFollow);

                // CHANGE FLAG IF DOUBLE CLICK TO FOLLOW UNFOLLOW

                $('#follow-div-'+post_id).attr('onclick','followUser("'+l_pin+'","0","'+post_id+'")');
            }

            console.log("After do :"+currentFollow);

        }
    }
    xmlHttp.open("post", "/nexilis/logics/follow_user");
    xmlHttp.send(formData);

    console.log("Follow = "+l_pin);
    
}

async function searchFilter() {
    var dest = window.location.href;
    var params = "";
    query = $('#query').val();
    var filter = activeFilter;

    // // // console.log('active filter: ' + filter);
    if (window.Android) {
        var f_pin = window.Android.getFPin();
        // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
        if (f_pin) {
            if (!params.includes("?")) {
                params = params + "?f_pin=" + f_pin;
            } else {
                params = params + "&f_pin=" + f_pin;
            }
        }
    } else {
        var f_pin = new URLSearchParams(window.location.search).get('f_pin');
        if (f_pin) {
            if (!params.includes("?")) {
                params = params + "?f_pin=" + f_pin;
            } else {
                params = params + "&f_pin=" + f_pin;
            }
        }
    }
    if (dest.includes('#')) {
        dest = dest.split('#')[0]
    }
    if (dest.includes('?')) {
        dest = dest.split('?')[0];
    }
    if (query != "" || filter != "") {
        if (!params.includes("?")) {
            params = params + "?";
        } else {
            params = params + "&";
        }
    }
    if (query != "") {
        let urlEncodedQuery = encodeURIComponent(query);
        params = params + "query=" + urlEncodedQuery;
        if (filter != "") {
            params = params + "&";
        }
    }
    if (filter != "") {
        let urlEncodedFilter = encodeURIComponent(filter);
        params = params + "filter=" + urlEncodedFilter;
    }

    // check verified
    params = params + '&verified=' + otherFilter.verified;

    // check friends
    params = params + '&friends=' + otherFilter.friends;

    // check others
    params = params + '&others=' + otherFilter.others;

    console.log("params " + params);
    dest = dest + params;
    offset = 0;
    // $('#section-list-video').html('');
    window.history.replaceState(null, "", dest);
    firstRunVideo(dest);

}

let category_arr = [];

let categoryTree;

function fetchCategory() {
    let f_pin = '';
    if (window.Android) {

        try {
            f_pin = window.Android.getFPin();
        } catch (e) {

        }
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            category_arr = JSON.parse(xmlHttp.responseText);
            // // console.log(category_arr);

            if (category_arr.length > 0) {
                categoryTree = unflatten(category_arr);
                // // console.log(categoryTree);

                let objTree = {
                    CATEGORY_ID: "0",
                    NAME: "root",
                    CHILDREN: categoryTree
                }

                // // console.log(objTree);

                createCategoryCheckbox($('#category-checkbox ul#root-category'), objTree);
            }
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_posts_category?f_pin=" + f_pin);
    xmlHttp.send();
}

const unflatten = data => {
    const tree = data.map(e => ({
        ...e
    })).reduce((a, e) => {
        a[e.CATEGORY_ID] = a[e.CATEGORY_ID] || e;
        a[e.PARENT] = a[e.PARENT] || {};
        const parent = a[e.PARENT];
        parent.CHILDREN = parent.CHILDREN || [];
        parent.CHILDREN.push(e);
        return a;
    }, {});
    return Object.values(tree)
        .find(e => e.CATEGORY_ID === undefined).CHILDREN;
};

function createCategoryCheckbox(parentUL, branch) {
    // // console.log(branch);
    for (var key in branch.CHILDREN) {
        if (branch.CHILDREN != null) {
            var item = branch.CHILDREN[key];
            $item = $('<li>', {
                id: "item-" + item.CATEGORY_ID
            });
            $item.append($('<input>', {
                type: "checkbox",
                id: item.CATEGORY_ID,
                name: "item-" + item.CATEGORY_ID
            }));
            $item.append($('<label>', {
                for: item.CATEGORY_ID,
                text: item.NAME
            }));
            parentUL.append($item);
            if (item.CHILDREN) {
                var $ul = $('<ul>').appendTo($item);
                createCategoryCheckbox($ul, item);
            }
        }
    }
    checkboxBehavior();
}

function checkboxBehavior() {
    $('#categoryFilter-body li :checkbox').on('click', function () {
        // // console.log('asdmas');
        var isChecked = $(this).is(":checked");

        //down
        $(this).closest('ul').find("ul li input:checkbox").prop("checked", isChecked);
        // });
    });
}

function fetchDefaultCategory() {
    let f_pin = '';
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            let webform = JSON.parse(xmlHttp.responseText);
            // // // console.log(category_arr);
            // // console.log(webform);

            if (webform.APP_URL === '1' || webform.APP_URL === '2' || webform.APP_URL === '0') {
                if (webform.APP_URL_DEFAULT !== null && webform.APP_URL_DEFAULT !== '') {
                    defaultCategory = webform.APP_URL_DEFAULT;
                }
            } else if (webform.CONTENT_TAB_LAYOUT === '1' || webform.CONTENT_TAB_LAYOUT === '2' || webform.CONTENT_TAB_LAYOUT === '0') {
                if (webform.CONTENT_TAB_DEFAULT !== null && webform.CONTENT_TAB_DEFAULT !== '') {
                    defaultCategory = webform.CONTENT_TAB_DEFAULT;
                }
            }
            // // console.log(defaultCategory);
            if (defaultCategory !== '') {
                let defCat = defaultCategory.split(',');

                defCat.forEach(dc => {
                    $('#croot-category input#' + dc).prop('checked', true);
                })
            }

            // fetchLinks();
            // filters.forEach(fi => {
            //   $('categoryFilter input #' + fi).prop('checked', true);
            // })
        }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_default_category?f_pin=" + f_pin);
    xmlHttp.send();
}

function activeCategoryTab() {
    let urlSearchParams = new URLSearchParams(window.location.search);
    let activeParam = urlSearchParams.get('filter');

    $('#filter-friends').prop('checked', otherFilter.friends == 1);
    $('#filter-verified').prop('checked', otherFilter.verified == 1);
    $('#filter-others').prop('checked', otherFilter.others == 1);

    if (activeParam != null) {
        let filters = activeParam.split('-');

        filters.forEach(fi => {
            $('#categoryFilter-body input#' + fi).prop('checked', true);
        })
    } else {
        fetchDefaultCategory();
    }
}

function selectCategoryFilter() {
    let selected = [];
    $('#root-category input:checked').each(function () {
        selected.push($(this).attr('id'));
    });
    activeFilter = selected.join('-');

    $('#other-category li input').each(function () {
        otherFilter.verified = $('#filter-verified').is(':checked') ? 1 : 0;
        otherFilter.friends = $('#filter-friends').is(':checked') ? 1 : 0;
        otherFilter.others = $('#filter-others').is(':checked') ? 1 : 0;
    });
    console.log('root', activeFilter);
    console.log('other', otherFilter);
    // // console.log('checked', selected);
    // $('#section-list-video').html('');
    $('#modal-categoryFilter').modal('toggle');
    searchFilter();
}

function showHideFilter(bool) {
    if (bool) {
        $('#header').removeClass('d-none');
        isSearchHidden = false;
        $('#company-logo').addClass('d-none');
    } else {
        // console.log('tutup');
        $('#header').addClass('d-none');
        isSearchHidden = true;
        selectCategoryFilter();
        $('#company-logo').removeClass('d-none');
    }
}

$('#toggle-filter').click(function (e) {
    e.stopPropagation();
    window.scrollTo({
        top: 0,
        behavior: 'instant',
    });
    showHideFilter($('#header').hasClass('d-none'))
    // console.log(isSearchHidden);
    // navbarHeight = $('#header-layout').outerHeight();
    // $('#header-layout').css('top', '0px');

    $('#toggle-filter').rotate({
        angle: 0,
        animateTo: 180
    });
})

$('#searchFilterForm-a').validate({
    rules: {
        // 'category[]': {
        //   required: true
        // }
    },
    messages: {
        // 'category[]': {
        //   required: '<div class="alert alert-danger" role="alert">Pilih minimal salah satu filter di atas</div>',
        // },
    },
    submitHandler: function (form) {
        searchFilter();
    },
    errorClass: 'help-block',
    errorPlacement: function (error, element) {
        // if (element.attr('name') == 'category[]') {

        //   error.insertAfter('#checkboxGroup');
        // }
    }

});

function eraseQuery() {
    if ($('#searchFilterForm-a input#query').val().trim().length > 0) {
        $('#delete-query').removeClass('d-none');
    }

    $("#delete-query").click(function () {
        $('#searchFilterForm-a input#query').val('');
        $('#delete-query').addClass('d-none');
        let searchQ = new URLSearchParams(window.location.search).get('query');

        if (searchQ != null && searchQ != "") {
            searchFilter();
        }
    })

    $('#searchFilterForm-a input#query').keyup(function () {

        if ($(this).val() != '') {
            $('#delete-query').removeClass('d-none');
        } else {
            $('#delete-query').addClass('d-none');
        }
    })
}

$('#searchFilterForm-a input#query').bind('input propertychange', function () {
    if ($(this).val() != '') {
        $('#delete-query').removeClass('d-none');
    } else {
        $('#delete-query').addClass('d-none');
    }
})

function resetSearch() {
    $('#searchFilterForm-a input#query').val('');
}

$(window).scroll(function () {
    $('#header').addClass('d-none');
})

// // $('#btnLoadMore').click(function () {
//     let params = new URLSearchParams(window.location.search);
//     if ((params.get('query') != null && params.get('query') != '') ||
//         params.get('filter') != null && params.get('filter') != '') {
//         currentUrl = window.location.href;
//     }
//     LoadMore(currentUrl);
// })

function openSetting() {
    // console.log('open setting');
    if (window.Android) {
        window.Android.openSetting();
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.openSetting) {
        window.webkit.messageHandlers.openSetting.postMessage({
            param1: '',
        });
        return;

    }
}

function toggleVideoMute() {
    // // console.log(code);
    let videoWrap = document.getElementById('video-wrap');
    let videoElement = videoWrap.querySelector('video');
    // // // console.log(videoElement);

    // // console.log('#' + code + ' .video-sound img');
    let muteIcon = videoWrap.querySelector('.video-sound img');

    if (videoElement.muted) {
        videoElement.muted = false;
        muteIcon.src = "../assets/img/video_unmute.png";
    } else {
        videoElement.muted = true;
        muteIcon.src = "../assets/img/video_mute.png";
    }

    // // console.log(code + ' ' + videoElement.muted);
}

function toggleFullscreen() {
    var elem = document.querySelector('#video-wrap video');
    // console.log(elem)
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) {
        /* Firefox */
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
        /* Chrome, Safari & Opera */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) {
        /* IE/Edge */
        elem.msRequestFullscreen();
    }
}

function closeVideo() {
    $('#video-wrap').addClass('d-none');
    $('#video-wrap video').attr('src', '');
    $('#main-video').off('click');
    setTimeout(function() {
        $('#main-video').click(function() {
            changeVideo(mainVid, mainPoster);
        })
    }, 600)
}

function resetFilter() {
    activeFilter = '';
    query = '';
    $('#query').val('');
    $('#delete-query').addClass('d-none')
    otherFilter.verified = 1;
    otherFilter.friends = 0;
    otherFilter.others = 0;
    $('#filter-verified').prop('checked', true);
    $('#filter-friends').prop('checked', false);
    $('#root-category input:checkbox').each(function() {
        $(this).prop('checked', false);
    })
    showHideFilter(false);
    searchFilter();
}

function pauseAll() {
    disableMainVid = true;
    closeVideo();
    resetFilter();
}

function getListLS() {
    return new Promise((resolve, reject) => {
        if (window.Android) {
            try {
                listLS = window.Android.getListLiveStreaming();
            } catch (e) {
                console.log('errer listLS', e)
            }
        }

        let arrLS = [];

        try {

            let list = listLS.slice(1, -1).replaceAll('\"', '').replaceAll('\"', "");

            let parselist = [];

            if (list != '' && list.length > 0) {
                parselist = list.split(',');
            }

            console.log(parselist);

            if (parselist.length > 0) {
                parselist.forEach(ele => {
                    let elements = ele.split('|');
                    console.log(elements)
                    let obj = {
                        'USER_PIN': elements[0],
                        'TIME': parseInt(elements[1]),
                        'USERNAME': elements[2].trim(),
                        'THUMB_ID': elements[3].trim(),
                        'TITLE': elements[4].split('~')[1],
                        'TOTAL_LIKES': 0,
                        'COMMENT_USER': 0,
                        'FILE_ID': '',
                        'POST_ID': elements[0] + parseInt(elements[1]),
                        'OFFICIAL_ACCOUNT': 0,
                        'IS_LIKED': 0,
                        'IS_COMMENT': 0,
                        'IS_LS': 1,
                        'DATA': ele.replaceAll('| |', '||')
                    };
                    arrLS.push(obj);
                })
            }
        } catch (e) {
            console.log('getListLS', e);
        }


        resolve(arrLS);
    })
}

function resumeAll() {
    disableMainVid = false;
    // getListLS();
}

function playPause() {
    // let video = document.querySelector('#video-wrap video');

    // if (video.paused) {
    //     video.play();
    // } else {
    //     video.pause();
    // }
}

function checkVideoPlayPause() {
    let video = document.querySelector('#video-wrap video');

    video.addEventListener('pause', (e) => {
        $('.video-play').removeClass('d-none');
        $('#video-wrap video').off('click');
        setTimeout(function() {
            $('#video-wrap video').click(function() {
                if (disableMainVid == false) video.play();
            })
        }, 600)
    })

    video.addEventListener('play', (e) => {
        $('.video-play').addClass('d-none');
        $('#video-wrap video').off('click');
        setTimeout(function() {
            $('#video-wrap video').click(function() {
                video.pause();
            })
        }, 600)
    })
}

function scrollingFunction() {
    if ($(document).scrollTop() > navbarToTop) {
        //   $("#scroll-top").css('display', 'block');
        $("#scroll-to-top").removeClass('d-none');
    } else {
        //   $("#scroll-top").css('display', 'none');
        $("#scroll-to-top").addClass('d-none');
    }
}

function totopFunction(animate) {
    window.scrollTo({
        top: 0
    });
}

function openLiveStream(data) {
    // do something
    try {
        if (window.Android) {
            window.Android.openLiveStream(data);
        }
    } catch (e) {
        console.log('openLS', e);
    }
}

$(document).ready(function (e) {
    let params = new URLSearchParams(window.location.search);
    if ((params.get('query') != null && params.get('query') != '') ||
        params.get('filter') != null && params.get('filter') != '') {
        currentUrl = window.location.href;
    }
    // if (params.get('verified') != null) {
    //     otherFilter.verified = params.get('verified')
    // }
    // if (params.get('friends') != null) {
    //     otherFilter.friends = params.get('friends')
    // }
    $('#company-logo').click(function () {
        openSetting();
    })

    $('.video-play').click(function () {
        $('.video-wrap video').trigger('play');
    })

    fetchCategory();
    // checkLimitVideo(currentUrl);
    eraseQuery();


    activeCategoryTab();
    firstRunVideo(currentUrl);

    if (localStorage.lang == 0) {
        // $('input#query').attr('placeholder', 'Search');
        document.getElementById('query').placeholder = "Search";
        $("#text-recorded-main").text("RECORDED");
    } else {
        document.getElementById('query').placeholder = "Pencarian";
        $("#text-recorded-main").text("REKAMAN");
    }
    checkVideoPlayPause();

    $(window).scroll(async function () {
        scrollingFunction();

        if ($(window).scrollTop() + $(window).height() > ($("#section-list-video").height() + $('#main-video').height()) && !busy && !isCalled) {
            let maxProducts = await checkLimitVideo(currentUrl);
            console.log(maxProducts);
            if (offset < maxProducts) {
                isCalled = false;
                busy = true;
                offset = limit + offset;
                // $('#loader-image').removeClass('d-none');
                LoadMore(currentUrl);
            }
            // // // console.log(offset);
        }
    });
});

// COMMENT SECTION

function openComment(code, isPost, checkIOS = false) {
    if (window.Android) {
      if (window.Android.checkProfile()) {
        let f_pin = window.Android.getFPin();
  
        window.location = "comment.php?product_code=" + code + "&is_post=" + isPost + "&f_pin=" + f_pin;
      }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
      window.webkit.messageHandlers.checkProfile.postMessage({
        param1: code + '|' + isPost,
        param2: 'comment'
      });
      return;
  
    } else {
      let f_pin = new URLSearchParams(window.location.search).get("f_pin");
  
      window.location = "comment.php?product_code=" + code + "&is_post=" + isPost + "&f_pin=" + f_pin;
    }
  }

// REPORT SECTION


function reportContent(product_id, report_count, checkIOS = false) {

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: product_id + '|' + report_count,
            param2: 'report_content'
        });
        return;
    }

    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
        
    }

    $('#modal-category').modal('show');

    localStorage.setItem("report_post_id", product_id);
    localStorage.setItem("report_count", report_count);

};

function reportContentSubmit() {

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var f_pin = f_pin;
    var post_id = localStorage.getItem("report_post_id");
    var report_category = $('input[name="report_category"]:checked').val();
    var count_report = localStorage.getItem("report_count");

    var formData = new FormData();

    formData.append('f_pin', f_pin);
    formData.append('post_id', post_id);
    formData.append('report_category', report_category);
    formData.append('count_report', count_report);


    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);

            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report Content Berhasil");
                $('#modal-category').modal('hide');
                if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
                    $('#modal-product').modal('hide');
                }
                $('#modal-report-success').modal('show');
                // location.reload();
            } else {
                alert("Report Content Gagal");
            }
        }

    }

    xmlHttp.open("post", "../logics/report_content");
    xmlHttp.send(formData);

};

function reportUser(f_pin_reported, checkIOS = false) {

    if (window.Android) {
        if (!window.Android.checkProfile()) {
            return;
        } else {

            $('#modal-category2').modal('show');

            localStorage.setItem("f_pin_reported", f_pin_reported);
        }
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: f_pin_reported,
            param2: 'report_user'
        });
        return;

    }

    console.log('aaa');

    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }
    $('#modal-category2').modal('show');

    localStorage.setItem("f_pin_reported", f_pin_reported);

};

function reportUserSubmit() {

    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var f_pin_reported = localStorage.getItem("f_pin_reported");;
    var report_category = $('input[name="report_category"]:checked').val();
    var count_report = 1 + 1;

    formData.append('f_pin', f_pin);
    formData.append('f_pin_reported', f_pin_reported);
    formData.append('report_category', report_category);
    formData.append('count_report', count_report);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-category2').modal('hide');
                $('#modal-report-success').modal('show');
                // location.reload();
            } else {
                alert("Report User Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/report_user");
    xmlHttp.send(formData);

};

function blockUser(l_pin, checkIOS = false) {
    if (window.Android) {
        if (window.Android.checkProfile()) {
            f_pin = window.Android.getFPin();
        } else {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: l_pin,
            param2: 'block_user'
        });
        return;

    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }
    var formData = new FormData();

    var f_pin = f_pin;
    var l_pin = l_pin

    console.log("SSS", f_pin);
    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }

    formData.append('f_pin', f_pin);
    formData.append('l_pin', l_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-block-success').modal('show');

                // if (window.Android) {
                //     window.Android.blockContact(l_pin);
                // }

                if (window.Android) {

                    window.Android.blockContact(l_pin);

                } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockContact) {

                    window.webkit.messageHandlers.blockContact.postMessage({
                        param1: l_pin
                    });
                    return;

                }
                // location.reload();
            } else {
                alert("Block User Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/block_user");
    xmlHttp.send(formData);
};

function unblockUser(l_pin) {
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }

    var formData = new FormData();

    var f_pin = f_pin;
    var l_pin = l_pin

    console.log("SSS", f_pin);

    formData.append('f_pin', f_pin);
    formData.append('l_pin', l_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Berhasil") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');

                if (localStorage.lang == 0) {
                    $('#modal-block-success .modal-body>p').text('You unblocked this user.');
                    $("#close-blocked").text("Close");
                } else {
                    $('#modal-block-success .modal-body>p').text('Anda telah membuka blokir user ini.');
                    $("#close-blocked").text("Tutup");
                }
                $('#modal-block-success').modal('show');

                if (window.Android) {

                    window.Android.blockUser(l_pin, false);

                } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockUser) {

                    window.webkit.messageHandlers.blockUser.postMessage({
                        param1: l_pin,
                        param2: false
                    });
                    return;

                }
                // location.reload();
            } else {
                alert("Block User Failed");
            }
        }
    }

    xmlHttp.open("post", "../logics/unblock_user");
    xmlHttp.send(formData);
}

function blockContent(postId, checkIOS = false) {

    var f_pin = "";

    if (window.Android) {
        if (window.Android.checkProfile()) {
            f_pin = window.Android.getFPin();
        } else {
            return;
        }
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
        window.webkit.messageHandlers.checkProfile.postMessage({
            param1: l_pin,
            param2: 'block_content'
        });
        return;

    } else {
        f_pin = new URLSearchParams(window.location.search).get("f_pin");
    }
    var formData = new FormData();

    var f_pin = f_pin;
    var post_id = postId;
    var time = new Date();
    now = time.getTime();

    console.log("SSS", f_pin);
    if ($('#modal-product').length > 0 && $('#modal-product').hasClass('show')) {
        $('#modal-product').modal('hide');
    }

    formData.append('f_pin', f_pin);
    formData.append('post_id', post_id);
    formData.append('time', now);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {

        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
            console.log(xmlHttp.responseText);
            if (xmlHttp.responseText == "Success") {
                // alert("Report User Berhasil");
                $('#modal-addtocart').modal('hide');
                $('#modal-block-content-success').modal('show');

                // if (window.Android) {
                //     window.Android.blockContact(l_pin);
                // }

                // if (window.Android) {

                //     window.Android.blockContact(l_pin);

                // } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.blockContact) {

                //     window.webkit.messageHandlers.blockContact.postMessage({
                //         param1: l_pin
                //     });
                //     return;

                // }
                // location.reload();
            } else {
                alert("Block Post Gagal");
            }
        }
    }

    xmlHttp.open("post", "../logics/block_post");
    xmlHttp.send(formData);
};

function reloadPages() {
    $('#modal-report-success').modal('hide');
    $('#modal-block-success').modal('hide');
    // location.reload();
}

function reloadPagesBlock() {
    $('#modal-block-content-success').modal('hide');
    location.reload();
}

// SECTION LIKE 

function getLikedProducts() {
    var f_pin = ""
    if (window.Android) {
      f_pin = window.Android.getFPin();
  
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    if (f_pin != "") {
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          // console.log(xmlHttp.responseText);
          let likeData = JSON.parse(xmlHttp.responseText);
          likeData.forEach(product => {
            var productCode = product.PRODUCT_CODE;
            likedPost.push(productCode);
            $("#like-" + productCode).attr("src", "../assets/img/jim_likes_red.png");
          });
          console.log('get likes', likedPost);
        }
      }
      xmlHttp.open("get", "/nexilis/logics/fetch_products_liked?f_pin=" + f_pin);
      xmlHttp.send();
    }
  }

  getLikedProducts();

function likeProduct($productCode, $is_post) {
    if (window.Android) {
      if (!window.Android.checkProfile()) {
        return;
      }
    }
  
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
      window.webkit.messageHandlers.checkProfile.postMessage({
        param1: $productCode,
        param2: 'like'
      });
      return;
    }
  
    var score = parseInt($('#like-counter-' + $productCode).text());
    var isLiked = false;
    if (likedPost.includes($productCode)) {
      likedPost = likedPost.filter(p => p !== $productCode);
      $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.png");
      if (score > 0) {
        $('#like-counter-' + $productCode).text(score - 1);
      }
      isLiked = false;
    } else {
      likedPost.push($productCode);
      $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.png");
      $('#like-counter-' + $productCode).text(score + 1);
      isLiked = true;
    }
  
    //TODO send like to backend
    // var f_pin = "02b46dfe44";
    var f_pin = "";
    if (window.Android) {
      f_pin = window.Android.getFPin();
  
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    var curTime = (new Date()).getTime();
  
    var formData = new FormData();
  
    formData.append('product_code', $productCode);
    formData.append('f_pin', f_pin);
    formData.append('last_update', curTime);
    formData.append('flag_like', (isLiked ? 1 : 0));
    formData.append('is_post', $is_post);
  
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // // console.log(xmlHttp.responseText);
        updateScore($productCode, 'like', isLiked);
      }
    }
  
    if (window.Android) {
      if (window.Android.checkProfile()) {
        xmlHttp.open("post", "/nexilis/logics/like_product");
        xmlHttp.send(formData);
      }
    } else {
      xmlHttp.open("post", "/nexilis/logics/like_product");
      xmlHttp.send(formData);
    }
  
  
  }