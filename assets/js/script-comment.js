let xReply = 1;
let xReffReply = 10;

function commentProduct($productCode) {
    // var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0,-9));
    // var isFollowed = false;
    // if (followedStore.includes($storeCode)) {
    //   followedStore = followedStore.filter(p => p !== $storeCode);
    //   $(".follow-icon-" + $storeCode).attr("src", "../assets/img/person_add.png");
    //   if (score > 0) {
    //     $('.follow-counter-store-' + $storeCode).text((score - 1)+" pengikut");
    //   }
    //   isFollowed = false;
    // } else {
    //   followedStore.push($storeCode);
    //   $(".follow-icon-" + $storeCode).attr("src", "../assets/img/ic_nuc_follow3_check.png");
    //   $('.follow-counter-store-' + $storeCode).text((score + 1)+" pengikut");
    //   isFollowed = true;
    // }

    if (document.getElementById("input").value.trim() != '') {
        $('input:text').click(
            function () {
                $(this).val('');
            });
    } else {
        let str = "Write a comment..."
        if (localStorage.lang == 1) {
            str = "Isi komentar...";
        }
        showAlert(str);
        return;
    }

    //TODO send like to backend
    var f_pin = "";
    if (window.Android) {
        f_pin = window.Android.getFPin();
    } else {
        f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }
    if (f_pin != "") {
        var curTime = (new Date()).getTime();

        var formData = new FormData();

        var discussion_id = curTime.toString() + f_pin;

        formData.append('product_code', $productCode);
        formData.append('f_pin', f_pin);
        formData.append('last_update', curTime);
        formData.append('comment', document.getElementById("input").value.trim());
        formData.append('discussion_id', discussion_id);
        xReply++;

        let is_post = new URLSearchParams(window.location.search).get('is_post');
        formData.append('is_post', is_post);

        if (!document.getElementById("reply-div").classList.contains("d-none")) {
            var commentId = getCookie("commentId");
            formData.append('reply_id', commentId);
            xReffReply = xReffReply + 10;
        }

        let xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                console.log(xmlHttp.responseText);
                if (xmlHttp.responseText == 'Success Comment') {
                    if (!document.getElementById("reply-div").classList.contains("d-none")) {
                        deleteAllCookies();
                        $("#reply-div").addClass('d-none');
                        document.getElementById("content-comment").style.marginBottom = "150px";
                    }
                    $('input#input').val('');
                    // location.reload();
                    appendComment(formData);
                    window.scrollTo(0, document.body.scrollHeight);
                    updateScore($productCode, 'comment');
                }
            }
        }
        xmlHttp.open("post", "/nexilis/logics/comment_product");
        xmlHttp.send(formData);
    }
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}

async function appendComment(formData) {
    let object = {};
    formData.forEach((value, key) => object[key] = value);

    // console.log("comment", object);   

    let date = new Date(parseInt(object.last_update));

    let time = date.getHours() + ":" + date.getMinutes();

    let commentId = object.f_pin + object.last_update;

    let profpic = await getProfpic(object.f_pin);
    let username = await getUserName(object.f_pin);

    let balas = "Reply";
    let hapus = "Delete";

    if (localStorage.lang == 0) {
        balas = "Reply";
        hapus = "Delete";
    } else if (localStorage.lang == 1) {
        balas = "Balas";
        hapus = "Hapus";
    }

    let fpin = window.Android ? window.Android.getFPin() : new URLSearchParams(window.location.search).get("f_pin");

    if (!object.hasOwnProperty('reply_id')) {

        let content_comment = document.getElementById('content-comment');

        let comment_html = `
        <div class="row mx-0 comments" id="${commentId}">
            <div class="commentId" style="display: none;">${commentId}</div>
            <div class="fPin" style="display: none;">${object.f_pin}</div>
            <div class="col-2">
            <img onclick="window.location.href='tab3-profile?f_pin=${fpin}&store_id=${object.f_pin}'" id="user-thumb-new-${xReply}" alt="Profile Photo" class="rounded-circle my-3" style="height:50px; width:50px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" src="${profpic}">
            </div>
            <div class="col-10 text-break">
            <div style="font-weight: bold;" class="mt-3 mb-1 mr-3">
                <span id="user-name-new-${xReply}">${username} </span>
                <span style="font-weight: 300;"> ${object.comment}</span>
            </div>
            <div style="font-weight: 100; color: grey;" class="my-1">${time}&emsp; <span style="font-weight: 300;" data-translate="comment-2" onclick="onReply(true,'user-name-new-${xReply}','${commentId}');">${balas}</span>&emsp;<span class="text-delete" style="font-weight: 300;" onclick="showSuccessModal('${commentId}');">${hapus}</span>
            </div>
            </div>
        </div>
        `;

        content_comment.innerHTML += comment_html;
    } else {

        let parent_id = object.reply_id;

        let comment_html = `
        <div class="row comments cmt-reply" id="${commentId}" style="width:100%;">
        <div class="commentId" style="display: none;">${commentId}</div>
        <div class="fPin" style="display: none;">${object.f_pin}</div>
        <div class="col-2">
          <img onclick="window.location.href='tab3-profile?f_pin=${fpin}&store_id=${object.f_pin}'" id="user-thumb-reff-new-${xReffReply}" alt="Profile Photo" class="rounded-circle my-3" style="height:40px; width:40px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" src="${profpic}">
        </div>
        <div class="col-10 text-break">
          <div style="font-weight: bold;" class="mt-3 mb-1 mr-3">
            <span id="user-name-reff-new-${xReffReply}">${username} </span>
            <span style="font-weight: 300;"> ${object.comment}</span>
          </div>
          <div style="font-weight: 100; color: grey;" class="my-1">${time}&emsp; <span data-translate="comment-2" style="font-weight: 300;" onclick="onReply(true,'user-name-reff-new-${xReffReply}','${commentId}');">${balas}</span>&emsp;<span class="text-delete" style="font-weight: 300;" onclick="showSuccessModal('${commentId}');">${hapus}</span>
          </div>
        </div>
      </div>
        `;

        console.log("parent", parent_id);
        console.log("this", commentId);

        let isLv1 = !$('.comments#' + parent_id).hasClass('cmt-reply');

        if (isLv1) {
            $('.comments#' + parent_id).append(comment_html);
        } else {
            $('.comments#' + parent_id).after(comment_html);
        }
        // if (isLv1) {
        //     $('.comments-tree#cmt-tree-' + parent_id).append(comment_html);
        // } else {
        //     let newTree = `
        //     <div class="comment-tree" id="cmt-tree-${commentId}">
        //     ${comment_html}
        //     </div>
        //     `;
        // }

    }


    enableDelete();
    // 
    if (!object.hasOwnProperty('reply_id')) {
        document.getElementById(commentId).scrollIntoView({
            behavior: "smooth"
        });
    } else {
        console.log("scrollto", commentId);
        var element = document.getElementById(commentId);
        var headerOffset = 45;
        var elementPosition = element.getBoundingClientRect().top;
        var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
        });
    }
}

function openDelete(commentId) {
    return function curried_func(event) {
        event.preventDefault();
        event.stopPropagation();
        showSuccessModal(commentId, console.log(""));
    }
}

function enableDelete() {
    $('.first-comment').each(function () {
        if (!$(this).hasClass('is-deleted')) {
            let commentId = $(this).siblings('.commentId').text();
            let fPinContent = $(this).siblings('.fPin').text();
            var f_pin = '';
            try {
                // if (window.Android) {
                //     f_pin = window.Android.getFPin();
                // } else {
                f_pin = new URLSearchParams(window.location.search).get('f_pin');
                // }
            } catch (err) {

            }
            // var f_pin = "028a5119b2";
            if (fPinContent == f_pin) {
                $(this).unbind('contextmenu');
                // $(this).contextmenu(function (e) {
                //     e.preventDefault();
                //     e.stopPropagation();
                //     showSuccessModal(commentId, console.log(""));
                // })
            } else {
                return;
            }
        }
    })

    $('.comments').each(function () {
        if (!$(this).hasClass('is-deleted')) {
            let commentId = $(this).find('.commentId').text();
            let fPinContent = $(this).find('.fPin').text();
            var f_pin = '';
            try {
                // if (window.Android) {
                //     f_pin = window.Android.getFPin();
                // } else {
                f_pin = new URLSearchParams(window.location.search).get('f_pin');
                // }
            } catch (err) {

            }
            // var f_pin = "028a5119b2";
            if (fPinContent == f_pin) {
                $(this).unbind('contextmenu');
                // $(this).contextmenu(function (e) {
                //     e.preventDefault();
                //     e.stopPropagation();
                //     showSuccessModal(commentId, console.log(""));
                // })
            } else {
                return;
            }
        }
    })

    toggleProdDesc();
}

enableDelete();

// function enableDelete(fpin, commentId) {
//     // let item = document.querySelector(".comments#" + commentId);
//     var f_pin = '';
//     try {
//         if (window.Android) {
//             f_pin = window.Android.getFPin();
//         } else {
//             f_pin = new URLSearchParams(window.location.search).get("f_pin");
//         }
//     } catch (err) {}
//     // var f_pin = "02b3c7f2db";
//     if (fpin == f_pin) {
//         document.querySelector(".comments#" + commentId).addEventListener('contextmenu', event => {
//             event.preventDefault();
//             showSuccessModal(commentId, console.log(""));
//         }, false)
//     } else {
//         return;
//     }
// }

async function showSuccessModal(commentId, method) {
    event.preventDefault();

    $('body').css('overflow', 'hidden');
    this.myModal = new SuccessModal(commentId, method);

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

class SuccessModal {

    constructor(commentId, method="") {
        if (localStorage.lang == 0) {
            this.modalTitle = "Are you sure you want to delete this comment?";
            this.acceptText = "Delete";
            this.cancelText = "Cancel";
        } else {
            this.modalTitle = "Yakin ingin menghapus komentar ini?";
            this.acceptText = "Hapus";
            this.cancelText = "Batal";
        }

        this.parent = document.body;
        this.commentId = commentId;
        this.method = method;

        this.modal = undefined;
        this.acceptButton = undefined;
        this.cancelButton = undefined;

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            if (!this.modal || !this.acceptButton) {
                reject("There was a problem creating the modal window!");
                return;
            }

            this.acceptButton.addEventListener("click", () => {
                var formData = new FormData();

                formData.append('comment_id', this.commentId);
                let is_post = new URLSearchParams(window.location.search).get('is_post');
                formData.append('is_post', is_post);
                let xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function () {
                    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                        if (xmlHttp.responseText == 'Success Delete Comment') {
                            location.reload();
                        }
                    }
                }
                xmlHttp.open("post", "/nexilis/logics/delete_comment");
                xmlHttp.send(formData);
            });

            this.cancelButton.addEventListener("click", () => {
                this._destroyModal();
                $('body').css('overflow', 'auto');
            });

        })
    }

    _createModal() {
        // Background dialog
        this.modal = document.createElement('dialog');
        this.modal.setAttribute("style", "z-index: 1031;");
        this.modal.classList.add('simple-modal-dialog');
        this.modal.show();

        // Message window
        const window = document.createElement('div');
        window.classList.add('simple-modal-window');
        this.modal.appendChild(window);

        // Title
        const title = document.createElement('div');
        title.classList.add('simple-modal-title');
        window.appendChild(title);

        // Title text
        const titleText = document.createElement('span');
        titleText.classList.add('simple-modal-title-text');
        titleText.style.marginLeft = "5px";
        titleText.style.marginRight = "5px";
        titleText.textContent = this.modalTitle;
        title.appendChild(titleText);

        // // Main text
        // const text = document.createElement('span');
        // text.setAttribute("id", "payment-form");
        // text.classList.add('simple-modal-text');
        // text.innerHTML = this.status;
        // window.appendChild(text);

        // Accept and cancel button group
        const buttonGroup = document.createElement('div');
        buttonGroup.classList.add('simple-modal-button-group');
        window.appendChild(buttonGroup);

        // Accept button
        this.acceptButton = document.createElement('button');
        this.acceptButton.type = "button";
        this.acceptButton.classList.add('simple-modal-button-green');
        this.acceptButton.textContent = this.acceptText;
        buttonGroup.appendChild(this.acceptButton);

        // Cancel button
        this.cancelButton = document.createElement('button');
        this.cancelButton.type = "button";
        this.cancelButton.classList.add('simple-modal-button-red');
        this.cancelButton.textContent = this.cancelText;
        buttonGroup.appendChild(this.cancelButton);

        // Let's rock
        this.parent.appendChild(this.modal);
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

function onReply(condition, name, commentId) {
    if (condition) {
        $("#input").focus();
        finalName = document.getElementById(name).innerHTML;

        if (localStorage.lang == 0) {
            document.getElementById("content-reply").innerHTML = "Reply to " + finalName;
        } else if (localStorage.lang == 1) {
            document.getElementById("content-reply").innerHTML = "Balas ke " + finalName;
        }

        document.getElementById("content-comment").style.marginBottom = "200px";
        document.cookie = "commentId=" + commentId;
        $("#reply-div").removeClass('d-none');

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        sleep(500).then(() => {
            window.scrollTo(0, document.body.scrollHeight);
        });
    } else {
        deleteAllCookies();
        $("#reply-div").addClass('d-none');
        document.getElementById("content-comment").style.marginBottom = "200px";
    }
    window.scrollTo(0, document.body.scrollHeight);
}

function deleteAllCookies() {
    var cookies = document.cookie.split(";");

    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}

function goBack() {
    if (window.Android) {
        // window.Android.closeView();
        window.history.back();
    } else {
        window.history.back();
    }
}

function showAlert(word) {
    if (window.Android) {
        window.Android.showAlert(word);
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.showAlert) {
        window.webkit.messageHandlers.showAlert.postMessage({
            param1: word,
        });
        return;
    }
}

function hideProdDesc() {
    $(".prod-desc").each(function () {
        if ($(this).text().length > 50) {
            $(this).addClass("mb-3");
            $(this).toggleClass("truncate");
            let toggleText = document.createElement("span");
            toggleText.innerHTML = localStorage.lang == 1 ? "Selengkapnya..." : "Read more...";
            // toggleText.href = "#";
            toggleText.style.color = "#999999";
            toggleText.classList.add("truncate-read-more");
            $(this).parent().append(toggleText);
        }
    });
}

function toggleProdDesc() {
    $(".truncate-read-more").each(function () {
        $(this).off("click");
        $(this).click(function () {
            console.log("read more");
            $(this).parent().find(".prod-desc").toggleClass("truncate");
            if ($(this).text() == "Selengkapnya..." || $(this).text() == "Read more...") {
                $(this).text(localStorage.lang == 1 ? "Sembunyikan" : "Hide");
            } else {
                $(this).text(localStorage.lang == 1 ? "Selengkapnya..." : "Read more...");
            }
        });
    });
}

function onFocusInput() {
    if (window.Android) {
        try {
            window.Android.onFocusInput();
        } catch (e) {

        }
    }
}

$(function () {
    // hideProdDesc();
    toggleProdDesc();
    if (window.Android) {
        window.Android.tabShowHide(false);
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.tabShowHide) {
        window.webkit.messageHandlers.tabShowHide.postMessage({
            param1: false,
        });
    }
    // $(".prod-desc").readmore({
    //     moreLink: '<a href="#">Selengkapnya...</a>',
    //     lessLink: '<a href="#">Sembunyikan</a>',
    //     collapsedHeight: 22
    // });
})