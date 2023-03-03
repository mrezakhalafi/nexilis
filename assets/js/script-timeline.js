let activeFilter = '';
let activeClass = '';
var otherFilter = {
  friends: 1,
  verified: 1,
  others: 0,
  official: 1,
}
if (localStorage.getItem('active_content_category') != null) {
  activeFilter = localStorage.getItem('active_content_category');
}
if (localStorage.getItem('active_content_classification') != null) {
  activeClass = localStorage.getItem('active_content_classification');
}

window.addEventListener("storage", async function () {
  if (sessionStorage.getItem('refresh') == 1) {
    sessionStorage.removeItem('refresh');
    window.location.reload();
  }
}, false);

let defaultCategory = '';
let visibleCategory = "";
let maxProducts = 0;

localStorage.setItem('is_clicked', 0);

function postData(actionUrl, method, data) {
  var mapForm = $('<form id="mapform" action="' + actionUrl + '" method="' + method.toLowerCase() + '"></form>');
  for (var key in data) {
    if (data.hasOwnProperty(key)) {
      mapForm.append('<input type="hidden" name="' + key + '" id="' + key + '" value="' + data[key] + '" />');
    }
  }
  $('body').append(mapForm);
  mapForm.submit();
}

var F_PIN = "";
if (window.Android) {
  F_PIN = window.Android.getFPin();
} else {
  F_PIN = new URLSearchParams(window.location.search).get('f_pin');
}

var ua = window.navigator.userAgent;
var palioBrowser = !!ua.match(/PalioBrowser/i);
var isChrome = !!ua.match(/Chrome/i);

// $('.carousel').carousel({
//   pause: true,
//   interval: false
// });

var didScroll;
var isSearchHidden = true;
var lastScrollTop = 0;
var delta = 3;
var navbarHeight = $('#header-layout').outerHeight();
var topPosition = 0;
var STORE_ID = "";
var FILTERS = "";

function hasScrolled() {
  var st = $(this).scrollTop();

  // Make sure they scroll more than delta
  if (Math.abs(lastScrollTop - st) <= delta)
    return;

  // If they scrolled down and are past the navbar, add class .nav-up.
  // This is necessary so you never see what is "behind" the navbar.
  if (st > lastScrollTop && st > navbarHeight) {
    // Scroll Down
    $('#header-layout').css('top', -navbarHeight + 'px');
    // $('#category-checkbox').addClass('d-none');
  } else {
    // Scroll Up
    if (st + $(window).height() < $(document).height()) {
      $('#header-layout').css('top', '0px');
    }
  }

  lastScrollTop = st;
}

setInterval(function () {
  if (didScroll) {
    hasScrolled();
    $('.dropdown-toggle').dropdown('hide')
    didScroll = false;
  }
}, 10);

function headerOut() {
  $('#category-checkbox').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

function headerOutAndReset() {
  $("#mic").attr("src", "../assets/img/action_mic.png");
  $('#query').val('');
  $('#switchAll').prop('checked', checked);
  setFilterCheckedAll(true);
  $('#category-checkbox').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};


let countVideoPlaying = 0;

let carouselIsPaused = true;

function checkVideoViewport() {
  // console.log("CHECKVIEWPORT");
  var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
  if (document.activeElement.className.match(pattern)) {
    return;
  }
  let videoWrapElements = document.querySelectorAll('.timeline-image .video-wrap>video, .timeline-image .carousel-item.active .video-wrap>video');
  let videoWrapArr = [].slice.call(videoWrapElements);
  let carouselElements = document.querySelectorAll('.timeline-image .carousel');
  let carouselArr = [].slice.call(carouselElements);

  let allElementsArr = videoWrapArr.concat(carouselArr);

  let observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      // // // console.log(entry.target);
      // // // console.log('ho', countVideoPlaying)
      if (entry.intersectionRatio >= 0.8 && $('#modal-addtocart').not('.show') && countVideoPlaying === 0) {
        playElement(entry.target);

      } else if (entry.intersectionRatio < 0.7) {
        pauseElement(entry.target);
      }
    });
  }, {
    threshold: 0.9
  });

  function playElement(el) {

    if (el.id.includes('video') && el.paused) {
      el.play();

      // // console.log(el.id, 'play');
      countVideoPlaying = 1;
    } else if (el.id.includes('carousel') && !visibleCarousel.has(el.id)) {
      visibleCarousel.add(el.id);
      //       $(this).carousel('cycle');
      // // console.log(el.id, 'play');
      $('#' + el.id).carousel('cycle');
      countVideoPlaying = 1;
    }
  }

  function pauseElement(el) {

    if (el.id.includes('video') && !el.paused) {
      el.pause();
      // // console.log(el.id, 'pause');
      countVideoPlaying = 0;
    } else if (el.id.includes('carousel') && visibleCarousel.has(el.id)) {
      visibleCarousel.delete(el.id);
      $('#' + el.id).carousel('pause');
      // // console.log(el.id, 'pause');
      countVideoPlaying = 0;
    }

  }

  function pauseCarousel(cr) {
    cr.pause();
  }

  function startCarousel(cr) {
    cr.cycle();
  }

  allElementsArr.forEach((elements) => {
    observer.observe(elements);
  });

  // [].forEach.call(carouselElements, (carousel) => {
  //   // // // console.log('loop');

  //   observer.observe(carousel);
  // })
  videoReplayOnEnd();
  playVid();
  // }
}

document.addEventListener('visibilitychange', function () {
  // document.title = document.visibilityState;

  if (document.visibilityState == "hidden") {
    $('.carousel-item video, .timeline-image video').each(function () {
      $(this).get(0).pause();
      $(this).parent().find(".video-play").removeClass("d-none");
    })
  } else {
    $('.carousel-item video, .timeline-image video').each(function () {
      // $(this).get(0).play();
      $(this).parent().find(".video-play").addClass("d-none");
    })
    checkVideoViewport();
  }

});

document.addEventListener('focusin', function () {
  var pattern = /(?:^|\s)simple-modal-button-green(?:\s|$)/
  if (document.activeElement.className.match(pattern)) {
    $('.carousel-item video, .timeline-image video').each(function () {
      $(this).get(0).pause();
    })
  }
}, true);

function checkVideoCarousel() {
  // play video when active in carousel
  // if (palioBrowser && isChrome) {
  $(".timeline-main .carousel").on("slid.bs.carousel", function (e) {
    // console.log($(this).find("video"));
    if ($(this).find("video").length) {
      let isPaused = $(this).find("video").get(0).paused;
      let $videoPlayButton = $(this).find(".video-play");
      if ($(this).find(".carousel-item").hasClass("active") && isPaused) {
        $(this).find("video").get(0).play();
        $videoPlayButton.addClass("d-none");
      } else {
        $(this).find("video").get(0).pause();
        $videoPlayButton.removeClass("d-none");
      }
    }
  });
  videoReplayOnEnd();
  playVid();
  // }
}

function onVideoStop(vid) {
  $(vid).parent().find(".video-play").removeClass("d-none");
}

function onVideoPlay(vid) {
  $(vid).parent().find(".video-play").addClass("d-none");
}


var visibleCarousel = new Set();

function checkCarousel() {
}

var startScrollPos = 0;
var finishScrollPos = 0;

$(function () {
  $(window).scroll(function () {
    scrollFunction();
    didScroll = true;
    finishScrollPos = $(document).scrollTop();
    if (!isSearchHidden && finishScrollPos != 0) {
      $('#category-checkbox').addClass('d-none')
      $('#toggle-filter').attr('src', '../assets/img/filter-icon-gray.png');
    }
    // play video when is in view
    checkVideoViewport();
    checkVideoCarousel();
    checkCarousel();


  });
});

function scrollFunction() {
  if ($(document).scrollTop() > navbarHeight) {
    $("#scroll-top").css('display', 'block');
    // setTimeout(function () {
    //   $("#scroll-top").css('display', 'none');
    // }, 5000);
  } else {
    $("#scroll-top").css('display', 'none');
  }
}

function topFunction(animate) {
  if (animate) {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  } else {
    window.scrollTo({
      top: 0
    });
  }
}

var productData = [];

var storeMap = new Map();
var storeIdMap = new Map();

function fetchStores() {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  var params = location.search
    .substr(1)
    .split("&");
  var fpin = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('f_pin=')) {
      tmp = params[i].split("=")[1];
      fpin = tmp;
    }
  }

  if (!fpin && window.Android) {
    try {
      fpin = window.Android.getFPin();
    } catch (error) {}
  }

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      data.forEach(storeEntry => {
        storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
        storeIdMap.set("" + storeEntry.ID, storeEntry.CODE);
      });
    }
  }
  if (fpin != "") {
    xmlHttp.open("get", "/nexilis/logics/fetch_stores?f_pin=" + fpin);
  } else {
    xmlHttp.open("get", "/nexilis/logics/fetch_stores");
  }
  xmlHttp.send();
}

function openStore($store_code, $store_link) {
  if (window.Android) {
    if (storeMap.has($store_code)) {
      var storeOpen = storeMap.get($store_code);
      window.Android.openStore(storeOpen);
    }
  } else {
    if ($store_link != "") {
      window.location.href = $store_link;
    } else {
      window.location.href = "/nexilis/pages/tab3-profile.php?store_id=" + $store_code + "&f_pin=02b3c7f2db";
    }
  }
}

var likedPost = [];

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
        // // console.log(xmlHttp.responseText);
        let likeData = JSON.parse(xmlHttp.responseText);
        likeData.forEach(product => {
          var productCode = product.PRODUCT_CODE;
          likedPost.push(productCode);
          $("#like-" + productCode).attr("src", "../assets/img/jim_likes_red.png");
        });
        // // // console.log('get likes', likedPost);
      }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_products_liked?f_pin=" + f_pin);
    xmlHttp.send();
  }
}

function checkEditMenu() {
  let f_pin = "";
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  $('.dropdown-edit').each(function () {
    if (isChangedProfile === "1") {
      if ($(this).hasClass('edit-menu-' + f_pin) && $(this).attr('data-isadmin') != "1") {
        $(this).removeClass('d-none');
        $(this).find('.button_adminremove').addClass('d-none');
        $(this).find('.button_edit').removeClass('d-none');
        $(this).find('.button_delete').removeClass('d-none');
      } else if ($(this).attr('data-isadmin') == "1" && !$(this).hasClass('edit-menu-' + f_pin)) {
        $(this).removeClass('d-none');
        $(this).find('.button_adminremove').removeClass('d-none');
        $(this).find('.button_edit').addClass('d-none');
        $(this).find('.button_delete').addClass('d-none');
      } else if ($(this).attr('data-isadmin') == "1" && $(this).hasClass('edit-menu-' + f_pin)) {
        $(this).removeClass('d-none');
        // $(this).find('.button_adminremove').removeClass('d-none');
        $(this).find('.button_edit').removeClass('d-none');
        $(this).find('.button_delete').removeClass('d-none');
      }
    }
  })
}

function deletePost(post_id) {
  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);
  formData.append('ec_date', new Date().getTime());
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      if (xmlHttp.responseText == "Success") {
        // // console.log(post_id + ' deleted');
        if (localStorage.lang == 0) {
          $('#delete-post-info .modal-body').html('<h6>Post deleted.</h6>');
          $('#delete-post-close').text('Close');
        } else {
          $('#delete-post-info .modal-body').html('<h6>Postingan telah dihapus.</h6>');
          $('#delete-post-close').text('Tutup');
        }
        $('#delete-post-info .modal-footer #delete-post-close').click(function () {
          // window.location.reload();
          let f_pin = window.Android ? window.Android.getFPin() : new URLSearchParams(window.location.search).get("f_pin");

          let reloadAll = window.location.pathname + "?f_pin=" + f_pin;
          window.location.href = reloadAll;
        });
        $('#delete-post-info').modal('toggle');
      } else {
        if (localStorage.lang == 0) {
          $('#delete-post-info .modal-body').html('<h6>An error occured while deleting post. Please refresh and try again.</h6>');
          $('#delete-post-close').text('Close');
        } else {
          $('#delete-post-info .modal-body').html('<h6>Error saat menghapus post. Silahkan muat ulang dan coba lagi.</h6>');
          $('#delete-post-close').text('Tutup');
        }
        // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
        //   window.location.reload();
        // });
        $('#delete-post-info').modal('toggle');
      }
    }
  }
  xmlHttp.open("POST", "/nexilis/logics/delete_post");
  xmlHttp.send(formData);
}

function editPost(code) {
  if (window.Android) {
    let f_pin = window.Android.getFPin();

    window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code + "&origin=" + localStorage.getItem("currentTab"); 
  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code + "&origin=" + localStorage.getItem("currentTab"); 
  }
}

function openComment(code, isPost, checkIOS = false) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();
      // window.Android.tabShowHide(false);
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

function updateCartCounter() {
  let counter_badge = 0;
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
    $('#cart-counter').removeClass('d-none');
    $('#cart-counter').html(counter_badge);
  } else {
    $('#cart-counter').addClass('d-none');
  }
}

function likeProduct($productCode, $is_post='1', checkIOS = false) {

  // console.log("START LIKE");
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
      // console.log("LIKE SUCCESS", xmlHttp.responseText);
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

var followedStore = [];

function getFollowedStores() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
  } else {
    var f_pin = new URLSearchParams(window.location.href).get('f_pin');
  }
  // if (f_pin) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let followData = JSON.parse(xmlHttp.responseText);
      followData.forEach(store => {
        var storeCode = store.STORE_CODE;
        followedStore.push(storeCode);
        $(".follow-icon-" + storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
      });
    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_stores_followed?f_pin=" + f_pin);
  xmlHttp.send();
  // }
}
// }

function followStore($productCode, $storeCode) {
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

  var score = parseInt($('#follow-counter-post-' + $productCode).text().slice(0, -9));
  var isFollowed = false;
  if (followedStore.includes($storeCode)) {
    followedStore = followedStore.filter(p => p !== $storeCode);
    $(".follow-icon-" + $storeCode).attr("src", "../assets/img/icons/Wishlist.png");
    if (score > 0) {
      $('.follow-counter-store-' + $storeCode).text((score - 1) + " pengikut");
    }
    isFollowed = false;
  } else {
    followedStore.push($storeCode);
    $(".follow-icon-" + $storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
    $('.follow-counter-store-' + $storeCode).text((score + 1) + " pengikut");
    isFollowed = true;
  }

  //TODO send like to backend
  if (window.Android) {
    var f_pin = window.Android.getFPin();
  } else {
    var f_pin = new URLSearchParams(window.location.href).get('f_pin');
  }
  var curTime = (new Date()).getTime();

  var formData = new FormData();

  formData.append('store_code', $storeCode);
  formData.append('f_pin', f_pin);
  formData.append('last_update', curTime);
  formData.append('flag_follow', (isFollowed ? 1 : 0));

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      // // // // console.log(xmlHttp.responseText);
      updateScoreShop($storeCode);
    }
  }
  xmlHttp.open("post", "/nexilis/logics/follow_store");
  xmlHttp.send(formData);
}

var commentedProducts = [];



function getCommentedProducts() {
  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      //   // // // console.log("GETCOMMENTED");
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          let likeData = JSON.parse(xmlHttp.responseText);
          likeData.forEach(product => {
            var productCode = product.PRODUCT_CODE;
            commentedProducts.push(productCode);
            $(".comment-icon-" + productCode).attr("src", "../assets/img/jim_comments.png");
          });
        }
      }
      xmlHttp.open("get", "/nexilis/logics/fetch_products_commented?f_pin=" + f_pin);
      xmlHttp.send();
    }
  }
}

$('#switchAll').click(function () {
  setFilterCheckedAll($('#switchAll').is(':checked'));
});

function checkSwitch(checked) {
  $('#switchAll').prop('checked', checked);
}

$('.checkbox-filter-cat').click(function () {
  if (!$(this).is(':checked')) {
    checkSwitch(false);
  } else if (isFilterCheckedAll()) {
    checkSwitch(true);
  }
});

function fillFilter() {
  var url_string = window.location.href;
  var url = new URL(url_string);
  var searchValue = url.searchParams.get("query");
  if (searchValue != null) {
    $('#query').val(searchValue);
  }
  var filterValue = url.searchParams.get("filter");
  if (filterValue != null) {
    filterArr = filterValue.split("-");
    filterArr.forEach(filterId => {
      $('#checkboxFilter-' + filterId).prop('checked', true);
    });
  }
  // var filterGear = document.getElementById("gear");
  if (filterValue || searchValue) {
    // filterGear.classList.add("filter-yellow");
  } else {
    // filterGear.classList.remove("filter-yellow");
  }
}

function resetAllVideos() {
  let allVideos = document.querySelectorAll('.video-wrap video');
  allVideos.forEach(v => {
    v.pause();
    v.removeAttribute('src'); // empty source
    v.load();
  })
}

function resetFilter() {
  activeFilter = '';

  query = '';
  $('#query').val('');
  $('#delete-query').addClass('d-none')
  otherFilter.verified = 1;
  otherFilter.friends = 1;
  otherFilter.others = 0;
  otherFilter.official = 1;
  $('#filter-verified').prop('checked', true);
  $('#filter-friends').prop('checked', true);
  $('#filter-others').prop('checked', false);
  $('#filter-official').prop('checked', true);
  resetAllVideos();
  // showHideFilter(false);
  if (defaultCategory != "") {
    let defCat = defaultCategory.split(',');
    let visCat = visibleCategory.split(',');
    // console.log(defCat);
    if (activeFilter == "") {
      defCat.forEach(dc => {
        $('#root-category input#' + dc).prop('checked', true);
      })
      
      if (BE_ID === "347") {
        activeFilter = defaultCategory.replaceAll(",", "-");
      }
    }

    try {
      // console.log(defCat);
      if (BE_ID === "347") {
        $("#root-category li").each(function () {
          let catId = $(this).attr('id').split("-")[1];
          // console.log(catId);
          if (!visCat.includes(catId)) {
            $(this).addClass("d-none");
          }
        })
      }
    } catch (e) {

    }
  } else {
    $('#root-category input:checkbox').each(function () {
      $(this).prop('checked', false);
    })
  }
  searchFilter();
  // window.location.reload();
}

// ON SPECIFIED STORE CLICK (STORY)

function onClickHasStory() {
  $(".has-story").click(function (e) {
    e.preventDefault();
    busy = true;
    if (this.id == "all-store") {
      STORE_ID = "";
      $('#query').val('');
      $('#delete-query').addClass('d-none');
      let currentClass = localStorage.getItem('active_content_classification');
      let currentFilter = localStorage.getItem('active_content_category');
      // if (currentClass && !currentClass.includes(",")) {
      //   buttonTheme(currentClass);
      // } else {
      //   buttonTheme(currentFilter ? currentFilter : "");
      // }
      // searchFilter();
    } else if (this.id == "store-nexilis") {
      STORE_ID = this.id;
    } else {
      let prev_STORE_ID = STORE_ID;
      STORE_ID = this.id.split("-")[1];
      // buttonTheme(STORE_ID);
      // fetchProductCount(STORE_ID, prev_STORE_ID);
    }
    searchFilter();
  });
}

function fetchProductCount(store_id, prev_STORE_ID) {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let data = JSON.parse(xmlHttp.responseText);
      searchFilter();
    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_store_product_count?store_id=" + store_id);
  xmlHttp.send();
}

function highlightStore() {
  if (STORE_ID != "") {
    selected_id = "#store-" + STORE_ID;
    // todo: kalo store ga ada
  } else {
    selected_id = '#all-store';
  }
  $('.has-story').removeClass('selected')
  $(selected_id).addClass("selected");
  horizontalScrollPos(STORE_ID);
}

function selectCategoryFilter() {
  $('#other-category li input').each(function () {
    otherFilter.verified = $('#filter-verified').is(':checked') ? 1 : 0;
    otherFilter.friends = $('#filter-friends').is(':checked') ? 1 : 0;
    otherFilter.others = $('#filter-others').is(':checked') ? 1 : 0;
    otherFilter.official = $('#filter-official').is(':checked') ? 1 : 0;
  });
  let selected = [];
  $('#root-category input:checked').each(function () {
    selected.push($(this).attr('id'));
  });
  activeFilter = selected.join('-');
  // // console.log('checked', selected);
  $('#modal-categoryFilter').modal('toggle');
  searchFilter();
}

function changeBg(category) {
  let imgBg = document.querySelector('.demo-bg');

  if (category == '313') { //soccer
    imgBg.src = "../assets/img/nxsport_bg/bg3.png";
  } else if (category == '314') { //basketball
    imgBg.src = "../assets/img/nxsport_bg/bg2.png";
  } else if (category == '315') { //boxing
    imgBg.src = "../assets/img/nxsport_bg/bg4.png";
  } else if (category == '316') { //tennis
    imgBg.src = "../assets/img/nxsport_bg/bg5.png";
  } else if (category == '317') { // racing
    imgBg.src = "../assets/img/nxsport_bg/bg6.png";
  } else {
    imgBg.src = "../assets/img/nxsport_bg/bg1.png";
  }
}

async function searchFilter() {
  var selected_id = "";
  limit = 3;
  $('.has-story').removeClass("selected");
  var dest = window.location.href;
  var product_dest = "timeline_products";
  var filter_dest = "timeline_story_container_timeline";
  var params = "";
  const query = $('#query').val();
  // console.log(activeFilter);
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
  if (STORE_ID != "") {
    params = params + "&store_id=" + STORE_ID;
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
    params = params + "query=" + query;
    if (filter != "") {
      params = params + "&";
    }
  }
  if (filter != "") {
    // let urlEncodedFilter = encodeURIComponent(filter);
    params = params + "filter=" + filter.replaceAll(",","-");
  }

  // check official
  params = params + '&official=' + otherFilter.official;

  // check verified
  params = params + '&verified=' + otherFilter.verified;

  // check friends
  params = params + '&friends=' + otherFilter.friends;

  // check friends
  params = params + '&others=' + otherFilter.others;

  // // console.log("params " + params);
  dest = dest + params;
  product_dest = product_dest + params;
  filter_dest = filter_dest + params;
  // window.location.href = dest;
  // // // // console.log("filter " + filter + " x " + FILTERS);
  // if (filter != FILTERS) {
  // // console.log(filter_dest);
  $.get(filter_dest, function (data) {
    $('#story-container').html(data);
    // highlightStore();
    onClickHasStory();
  });
  // }
  //  else {
  //   highlightStore();
  // }
  offset = 0;
  $('#pbr-timeline').html('');
  // // // console.log('busy: ' + busy);
  isCalled = false;
  countVideoPlaying = 0;
  getMaxProducts(params);
  await displayRecords(params, 3, offset);
  redrawLikeFollowComment();
  window.history.replaceState(null, "", dest);
  reinitCarousel();
  hideProdDesc();
  toggleProdDesc();
  setCurrentStore(STORE_ID);
  checkVideoViewport();
  // checkCarousel();
  // toggleVideoMute();
  fetchProductMap(params);
  addToCartModal();
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

function hasStoreId() {
  var tmp = "";
  var params = location.search
    .substr(1)
    .split("&");
  var id = "#all-store";
  var filter = "";
  for (var i = 0; i < params.length; i++) {
    if (params[i].includes('store_id=')) {
      tmp = params[i].split("=")[1];
      STORE_ID = tmp;
    }
    if (params[i].includes('filter=')) {
      tmp = params[i].split("=")[1];
      FILTERS = tmp;
    }
  }
  highlightStore();
  const scrollLeft = $(id).position()['left'];
  $("#story-container ul").scrollLeft(scrollLeft);
  if (location.href.includes('#product')) {
    var product_id = '#' + location.href.split('#')[1]
    $(product_id)[0].scrollIntoView();
  }
}


onClickHasStory();

if (performance.navigation.type == 2) {
  location.reload(true);
}

function redrawLikeFollowComment() {
  likedPost.forEach(productCode => {
    $("#like-" + productCode).attr("src", "../assets/img/jim_likes_red.png");
  });
  followedStore.forEach(storeCode => {
    $(".follow-icon-" + storeCode).attr("src", "../assets/img/icons/Wishlist-fill.png");
  });
  commentedProducts.forEach(productCode => {
    $(".comment-icon-" + productCode).attr("src", "../assets/img/jim_comments.png");
  });
}

function reinitCarousel() {
  $('.carousel').each(function () {
    $(this).carousel();
  });
}

function horizontalScrollPos(selected) {
  let selectedPos = 0;
  try {
    selectedPos = document.querySelector('.has-story#store-' + selected).offsetLeft;
  } catch (e) {

  }
  // document.querySelector('#story-container ul').scrollBy({
  //   left: selectedPos,
  //   behavior: 'smooth'
  // });

  $('#story-container ul').animate({
    scrollLeft: selectedPos
  })
}

function setCurrentStore($store_id) {
  if (storeIdMap.has($store_id)) {
    var $store_code = storeIdMap.get($store_id);
    if (storeMap.has($store_code) && window.Android) {
      var storeOpen = JSON.parse(storeMap.get($store_code));
      if (storeOpen.IS_VERIFIED == 1 && !storeOpen.LINK) {
        window.Android.setCurrentStore($store_code, storeOpen.BE_ID);
      } else {
        window.Android.setCurrentStore('', '');
      }
    }
  }
}

function hideProdDesc() {
  // // // console.log('hide dong');
  $(".prod-desc").each(function () {
    if ($(this).text().length > 100 && $(this).siblings('.truncate-read-more').length == 0) {
      $(this).toggleClass("truncate");
      let toggleText = document.createElement("span");
      toggleText.innerHTML = localStorage.lang == 1 ? "Selengkapnya" : "Read more";
      toggleText.classList.add("truncate-read-more");
      $(this).parent().append(toggleText);
    }
  });
}

function toggleProdDesc() {
  $(".truncate-read-more").each(function () {
    $(this).unbind('click');
    $(this).click(function () {
      // // console.log("read more");
      $(this).parent().find(".prod-desc").toggleClass("truncate");
      if ($(this).text() == "Selengkapnya" || $(this).text() == "Read more") {
        $(this).text(localStorage.lang == 1 ? "Sembunyikan" : "Hide");
      } else {
        $(this).text(localStorage.lang == 1 ? "Selengkapnya" : "Read more");
      }
    });
  });
}

function toggleVideoMute(code) {
  // // console.log(code);
  let videoWrap = document.getElementById(code);
  let videoElement = videoWrap.querySelector('video');
  // // // console.log(videoElement);

  // // console.log('#' + code + ' .video-sound img');
  let muteIcon = document.querySelector('#' + code + ' .video-sound img');

  if (videoElement.muted) {
    videoElement.muted = false;
    muteIcon.src = "../assets/img/video_unmute.png";
  } else {
    videoElement.muted = true;
    muteIcon.src = "../assets/img/video_mute.png";
  }

  // // console.log(code + ' ' + videoElement.muted);
}

function videoMuteAll() {
  $(".video-sound").each(function () {
    let $videoElement = $(this).parent().find("video.myvid");
    $videoElement.prop("muted", true);
    $(this).find("img").attr("src", "../assets/img/video_mute.png");
  });
}

var productMap = new Map();

function fetchProductMap(str) {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  // var params = "";
  // if (str == "") {
  //   params = location.search;
  // } else {
  //   params = str;
  // }

  // var xmlHttp = new XMLHttpRequest();
  // xmlHttp.onreadystatechange = function () {
  //   if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
  //     let data = JSON.parse(xmlHttp.responseText);
  //     data.forEach(productEntry => {
  //       productMap.set(productEntry.CODE, JSON.stringify(productEntry));
  //     });
  //   }
  // }
  // xmlHttp.open("get", "/nexilis/logics/fetch_products_json" + params);
  // xmlHttp.send();
}

function openProductMenu($productCode) {
  if (window.Android) {
    if (productMap.has($productCode)) {
      var productOpen = productMap.get($productCode);
      window.Android.openProductMenu(productOpen);
    }
  }
}

function videoReplayOnEnd() {
  $(".myvid").each(function (i, obj) {
    $(this).on('ended', function () {
      // // // // console.log("video ended");
      let $videoPlayButton = $(this).parent().find(".video-play");
      $videoPlayButton.removeClass("d-none");
    })
  })
}

function playVid() {
  $("div.video-play").each(function () {
    $(this).unbind('click');
    $(this).click(function (e) {
      e.stopPropagation();
      $(this).parent().find("video.myvid").get(0).play();
      $(this).addClass("d-none");
    })
  })
}

let startPause = 0;

let isMultiTouch = 0;
// document.addEventListener("touchstart", function(e) {
//   console.log(e.touches.item(0));
// })

function pauseAll() { // FUNCTION CALLED IN NATIVE WHILE SWITCHING TAB
  console.log("PAUSE ALL");
  document.querySelectorAll(".product-row .timeline-main video").forEach(vid => {
    vid.pause();
    vid.src= "";
  })
  document.querySelectorAll("#modal-addtocart .modal-body video").forEach(vid => {
    vid.pause();
})
  document.addEventListener("touchstart", function(e) {
    console.log(e.touches);
  })
  // document.querySelectorAll("video").forEach(vid => {
  //   vid.pause();
  //   vid.src = "";
  //   // console.log(vid.id, vid.src);
  // })
  $('#pbr-timeline').html('');
  // startPause = 1;
  $("body").addClass('no-modal');
  // removePinchZoom();
  // resetFilter();
  searchFilter();
  // $('.carousel-item video, .timeline-image video').each(function () {

  
  visibleCarousel.clear();
  $('.carousel').each(function () {
    $(this).carousel('pause');
    // // console.log('pause carousel');
  })

  // window.location.reload();
  // refreshClean();
}

function resumeAll() {
  countVideoPlaying = 0;
  $("body").removeClass('no-modal');
  startPause = 0;
  // window.location.reload();
  console.log('RESUME');
  // checkVideoViewport();
  // checkVideoCarousel();
  // checkCarousel();
  // updateCounter();
  // fetchNotifCount();
  playModalVideo();
}

function visitStore($store_code, $f_pin, $is_entering) {
  var formData = new FormData();

  formData.append('store_code', $store_code);
  formData.append('f_pin', $f_pin);
  formData.append('flag_visit', ($is_entering ? 1 : 0));

  if ($store_code && $f_pin) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // // // console.log(xmlHttp.responseText);
      }
    }
    xmlHttp.open("post", "/nexilis/logics/visit_store");
    xmlHttp.send(formData);
  }
}

function activeCategoryTab() {
  let urlSearchParams = new URLSearchParams(window.location.search);
  let activeParam = urlSearchParams.get('filter');

  // // console.log(activeParam);

  // if (activeParam == null) {
  //   activeParam = "all";
  // }

  $('#filter-friends').prop('checked', otherFilter.friends == 1);
  $('#filter-verified').prop('checked', otherFilter.verified == 1);
  $('#filter-others').prop('checked', otherFilter.others == 1);
  $('#filter-official').prop('checked', otherFilter.official == 1);

  // $('#categoryFilter-' + activeParam).addClass('active');
  // $('#category-tabs .nav-link:not(#categoryFilter-' + activeParam + ')').removeClass('active');
  if (activeParam != null) {
    activeFilter = activeParam;
    let filters = activeParam.split('-');

    filters.forEach(fi => {
      $('#root-category input#' + fi).prop('checked', true);
    })
  } else {
    // fetchDefaultCategory();
    if (defaultCategory != "") {
      let defCat = defaultCategory.split(',');
      let visCat = visibleCategory.split(',');
      // console.log(defCat);
      if (activeFilter == "") {
        defCat.forEach(dc => {
          $('#root-category input#' + dc).prop('checked', true);
        })
      }

      try {
        // console.log(defCat);
        if (BE_ID === "347") {
          $("#root-category li").each(function () {
            let catId = $(this).attr('id').split("-")[1];
            // console.log(catId);
            if (!visCat.includes(catId)) {
              $(this).addClass("d-none");
            }
          })
        }
      } catch (e) {

      }
    }
  }
}

function pullRefresh() {
  if (window.Android && $(window).scrollTop() == 0) {
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
  }
}

function ext(url) {
  return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."))
}

function goBack() {
  if (window.Android) {
    window.Android.closeView();
  } else {
    window.history.back();
  }
}

function numberWithCommas(x) {
  // return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
  return x.toLocaleString();
}

function openDetailProduct(pr) {
  let getPr = JSON.parse(productMap.get(pr));

  $('#modal-addtocart .addcart-img-container').html('');
  $('#modal-addtocart .product-name').html('');
  $('#modal-addtocart .product-price').html('');
  $('#modal-addtocart .prod-details .col-11').html('');

  let product_imgs = getPr.THUMB_ID.split('|');
  let product_name = getPr.NAME;
  let product_price = numberWithCommas(getPr.PRICE);
  // let product_price = getPr.PRICE;
  let product_desc = getPr.DESCRIPTION;

  let product_showcase = "";

  // if (product_imgs.length == 1) {
  let extension = ext(product_imgs[0]);
  if (extension == ".jpg" || extension == ".png" || extension == ".webp") {
    product_showcase = `<img class="product-img" src="${product_imgs[0]}">`;
  } else if (extension == ".mp4" || extension == ".webm") {
    let poster = product_imgs[0].replace(extension, ".webp");
    product_showcase = `
      <div class="video-wrap"><video playsinline muted="" class="myvid" preload="metadata"
              poster="${poster}">
              <source src="${product_imgs[0]}" type="video/mp4"></video>
      </div>
      `;
  }

  let followSrc = "../assets/img/icons/Wishlist-(White).png";
  if (followedStore.includes(getPr.SHOP_CODE)) {
    followSrc = "../assets/img/icons/Wishlist-fill.png";
  }

  product_showcase += `
  <hr id="drag-this">
  <img id="btn-wishlist" class="addcart-wishlist follow-icon-${getPr.SHOP_CODE}" onclick="followStore('${getPr.CODE}','${getPr.SHOP_CODE}')" src="${followSrc}">`;

  $('#modal-addtocart .addcart-img-container').html(product_showcase);
  $('#modal-addtocart .product-name').html(product_name);
  $('#modal-addtocart .product-price').html('Rp ' + product_price);
  $('#modal-addtocart .prod-details .col-11').html(product_desc);
}

function hideAddToCart() {
  $('#modal-addtocart').modal('hide');
  $("body").removeClass("no-modal");
}

function pauseAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    let isPaused = $(this).get(0).paused;
    $(this).off("stop pause ended");
    $(this).on("stop pause ended", function (e) {
      $(this).closest(".carousel").carousel();
    });
    if (!isPaused) {
      $(this).get(0).pause();
    }
  });
}

function playAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    // pause carousel when video is playing
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  });
}

function playModalVideo() {
  $('#modal-addtocart .addcart-img-container video').each(function () {
    let isPaused = $(this).get(0).paused;
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    if (isPaused) {
      $(this).get(0).play();
      let $videoPlayButton = $(this).parent().find(".video-play");
      $videoPlayButton.addClass("d-none");
    }
  })
}

function changeItemQuantity(id, mod) {
  if (mod == "add") {
    document.getElementById(id).value = parseInt(document.getElementById(id).value) + 1;
  } else {
    if (document.getElementById(id).value > 1) {
      document.getElementById(id).value = parseInt(document.getElementById(id).value) - 1;
    }
  }
}

let product_id = "";

function checkButtonPos() {
  try {
    let elem = document.querySelector('.prod-addtocart');
    let bounding = elem.getBoundingClientRect();

    if (bounding.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
      // // console.log('out')
      elem.style.bottom = elem.offsetHeight + 20 + 'px';
    } else {
      elem.style.bottom = '25px';
    }
  } catch (e) {
    // console.log(e);
  }
}

function addToCartModal() {
  /* start handle detail product popup */
  const initPos = parseInt($('#header').offset().top + $('#header').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));

  // let product_id = "";

  let init = parseInt(fixedPos.replace('px', ''));

  var ua = window.navigator.userAgent;

  $('#modal-addtocart').on('shown.bs.modal', function () {
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    $('.timeline-image .carousel').each(function () {
      $(this).carousel('pause');
      // // console.log('pause');
    })
    checkButtonPos();
    pullRefresh();
    pauseAllVideo();
    playModalVideo();

    if (window.Android) {
      window.Android.setIsProductModalOpen(true);
    }

    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
      window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
        param1: true
      });
    }
  })

  $('.product-row .timeline-main').click(function () {
    // // // console.log('init: ' + init);
    // $('#modal-addtocart .modal-dialog').css('top', '55px');
    $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#modal-addtocart').on('hide.bs.modal', function () {
    $('#modal-addtocart #modal-add-body').html('');
  })

  $('#modal-addtocart').on('hidden.bs.modal', function () {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    // let modalVideo = $('#modal-addtocart').find('video');

    // if (modalVideo.length > 0) {

    //   $('#modal-addtocart .modal-body video').get(0).pause();
    // }
    countVideoPlaying = 0;
    pullRefresh();
    checkVideoViewport();
    if (window.Android) {
      window.Android.setIsProductModalOpen(false);
    }
    if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
      window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
        param1: false
      });
    }
  })

  /* end handle detail product popup */
}

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

      let url = new URL(window.location.href);

      url.searchParams.set('query', '');
      window.history.replaceState({}, '', url);
      searchFilter();
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
  // $('#searchFilterForm-a input#query').val('');
}

let busy = false;
let limit = 3;
let offset = 0;
let firstLoad = true;
let time = new Date().getTime();
let seed = JSON.parse(JSON.stringify(time));
let isCalled = false;

function getMaxProducts(param) {
  // // // console.log('getmax', param);
  // isCalled = true;
  // return new Promise(function (resolve, reject) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        // // // console.log('getmaxresponse', xmlHttp.responseText);
        maxProducts = parseInt(xmlHttp.responseText);
        console.log("LOAD", maxProducts);
        // resolve(xmlHttp.responseText);
      }
    }
    xmlHttp.open("get", "/nexilis/logics/get_max_products" + param);
    xmlHttp.send();
  // });
}

function checkDupes() {
  var nodes = document.querySelectorAll('#pbr-timeline>*');
  var ids = {};
  var totalNodes = nodes.length;

  for (var i = 0; i < totalNodes; i++) {
    var currentId = nodes[i].id ? nodes[i].id : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }

  // console.log(ids);
}

// DISPLAY 5-5 CONTENT IN TAB 1



async function displayRecords(par, lim, off) {
  // let queryStr = window.location.search;

  let params = '';

  if (par.length > 0) {
    let searchQuery = par.substr(1).split("&");

    let limitIdx = searchQuery.findIndex(x => x.includes('limit'));
    let offsetIdx = searchQuery.findIndex(x => x.includes('offset'));
    let fpinIdx = searchQuery.findIndex(x => x.includes('f_pin'));
    if (limitIdx > -1) {
      searchQuery[limitIdx] = 'limit=' + lim;
    } else {
      searchQuery.push('limit=' + lim);
    }
    if (offsetIdx > -1) {
      searchQuery[offsetIdx] = 'offset=' + off;
    } else {
      searchQuery.push('offset=' + off);
    }

    if (fpinIdx > -1) {

    } else {

    }
    params = searchQuery.join('&');
  } else {
    params = 'limit=' + lim + '&offset=' + off;
  }

  if (otherFilter.official == 1 && !params.includes("official")) {
    params += "&official=1";
  }
  if (otherFilter.verified == 1 && !params.includes("verified")) {
    params += "&verified=1";
  }
  if (otherFilter.friends == 1 && !params.includes("friends")) {
    params += "&friends=1";
  }
  if (otherFilter.others == 1 && !params.includes("others")) {
    params += "&others=1";
  }

  let url = 'timeline_products';

  params += '&seed=' + seed + "&t=" + new Date().getTime();

  // console.log(params);

  // // // console.log('scroll:' + url);
  $.ajax({
    type: "GET",
    url: url,
    data: params,
    beforeSend: function () {
      $("#loader_message").removeClass("d-none");
      $("#loader_message").addClass("d-flex");
      // $('#loader_image').show();
    },
    success: function (html) {
      
      $("#loader_message").addClass("d-none");
      $("#loader_message").removeClass("d-flex");
      if (off > 0 && html.includes('Tidak ada produk')) {

      } else {
        $("#pbr-timeline")
          .append(html)
          .ready(function () {
            translateTimestamp(); // TRANSLATE DATE AGO POST
            hasStoreId(); // CHECK IF USER SELECT SPECIFIED STORE
            checkVideoViewport(); // LIMIT VIDEO PLAY 1, VIDEO WHO ALREADY SCROLLED UP-DOWN WILL BE TURNED OFF
            checkVideoCarousel(); // LIMIT VIDEO PLAY 1, VIDEO WHO SCROLLED OUT IN CAROUSEL WILL BE TURNED OFF
            hideProdDesc(); // MAKE A SHORT DESC OF POST
            toggleProdDesc(); 
            // checkCarousel();
            // toggleVideoMute();
            videoReplayOnEnd();
            playVid();
            addToCartModal();
            checkEditMenu();
          });
      }
      // $('#loader_image').hide();
      // if (html == "") {
      //   // $("#loader_message").html('').show();
      // } else {
      //   // $("#loader_message").html('').show();
      // }
      if ($('.product-row').length > 0) {
        $('#product-null').addClass('d-none');
      }
      busy = false;
      // // // console.log('busy: ' + busy);
    }
  });
}

function openNotifs() {
  let f_pin = '';

  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  window.location.href = 'notifications.php?f_pin=' + f_pin;
}

// SHOW PRODUCT FUNCTIONS
function getProductThumbs(product_code, is_product) {
  let formData = new FormData();
  formData.append("product_id", product_code);
  formData.append("is_product", is_product);

  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/nexilis/logics/get_product_thumbs");

    xhr.onload = function () {
      if (this.status >= 200 && this.status < 300) {
        resolve({
          thumb_id: JSON.parse(xhr.response).THUMB_ID,
          name: JSON.parse(xhr.response).NAME,
          description: JSON.parse(xhr.response).DESCRIPTION
        });
      } else {
        reject({
          status: this.status,
          statusText: xhr.statusText
        });
      }
    };
    xhr.onerror = function () {
      reject({
        status: this.status,
        statusText: xhr.statusText
      });
    };

    xhr.send(formData);
  });
}

let video_arr = ['webm', 'mp4'];
let img_arr = ['png', 'jpg', 'webp', 'gif', 'jpeg'];

class ShowProduct {

  constructor(async_result) {

    let thumbs = async_result.thumb_id.split('|');

    let content = '';

    if (thumbs.length == 1) {
      // let type = ext(thumbs[0]);

      let ph1 = thumbs[0].substr(1 + thumbs[0].lastIndexOf("/")).split('?')[0];
      let ph2 = ph1.split('#')[0].substr(ph1.lastIndexOf(".") + 1);

      if (video_arr.includes(ph2)) {
        content = `
                  <video class="d-block w-100" autoplay muted>
                  <source src="${thumbs[0]}" type="video/${type}">
                  </video>
              `;
      } else if (img_arr.includes(ph2)) {
        content = `
                  <img src="${thumbs[0]}" class="d-block w-100">
              `;
      }
    } else {
      content = `
          <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-inner">
          `;

      thumbs.forEach((th, idx) => {
        content += `<div class="carousel-item${idx == 0 ? ' active' : ''}">`;

        // let type = ext(th);

        let ph1 = th.substr(1 + th.lastIndexOf("/")).split('?')[0];
        let ph2 = ph1.split('#')[0].substr(ph1.lastIndexOf(".") + 1);

        if (video_arr.includes(ph2)) {
          content += `
                  <video class="d-block w-100" autoplay muted>
                  <source src="${th.substr(0,4) == "http" ? th : '/nexilis/images/' + th}" type="video/${type}">
                  </video>
              `;
        } else if (img_arr.includes(ph2)) {
          content += `
                  <img src="${th.substr(0,4) == "http" ? th : '/nexilis/images/' + th}" class="d-block w-100">
              `;
        }

        content += `</div>`;
      })

      content += `
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
              </button>
          </div>
          `;
    }

    // codes below wil only run after getProductThumbs done executing
    this.html = content;

    this.parent = document.body;
    this.modal = document.querySelector('#modal-product .modal-body');
    this.modal.innerHTML = " ";



    this._createModal();
  }

  static async build(product_code, is_product, category) {
    let async_result = await getProductThumbs(product_code, is_product);
    if (window.Android) {
      // window.Android.setButtonTheme(category);
    }
    return new ShowProduct(async_result);
  }

  question() {

  }

  _createModal() {

    // Main text
    this.modal.innerHTML = this.html;

    // Let's rock
    $('#modal-product').modal('show');
  }

  _destroyModal() {
    $('#modal-product').modal('hide');
    if (window.Android) {
      // window.Android.setButtonTheme('');
    }
  }
}

$('#modal-product').on('shown.bs.modal', function () {
  checkVideoCarousel();
  pauseAll();
})

$('#modal-product').on('hidden.bs.modal', function () {
  checkVideoCarousel();
  resumeAll();
})

async function showProductModal(product_code, is_product, category) {

  event.preventDefault();

  let add = await ShowProduct.build(product_code, is_product, category);
  // let response = await add.question();

}

function refreshClean() {
  // let f_pin = "";
  // let p = "";
  // let url = 'tab1-main.php';
  // if (window.Android) {
  //   f_pin = window.Android.getFPin();
  //   url = url + '?f_pin=' + f_pin;
  // } else {
  //   f_pin = new URLSearchParams(window.location.search).get('f_pin');
  //   if (f_pin != null) {
  //     url = url + '?f_pin=' + f_pin;
  //   } else {
  //     p = new URLSearchParams(window.location.search).get('p');
  //     url = url + '?p=' + p;
  //   }
  // }
  // window.location.href = url;
  window.location.reload();
}

function timedRefresh(timeoutPeriod) {
  setTimeout(function () {
    // refreshClean();
  }, timeoutPeriod);
}

function translateTimestamp() {
  // $('.prod-timestamp').each(function() {
  //   if (localStorage.lang == 1) {
  //     $(this).text().replace('days ago', 'hari lalu');
  //     $(this).text().replace('Today', 'Hari ini');
  //     $(this).text().replace('Yesterday', 'Kemarin');
  //   }
  // })
  document.querySelectorAll('.prod-timestamp').forEach(x => {
    if (localStorage.lang == 1) {
      let timeStamp = x.innerHTML.replace('days ago', 'hari lalu');
      timeStamp = timeStamp.replace('Today', 'Hari ini');
      timeStamp = timeStamp.replace('Yesterday', 'Kemarin');
      timeStamp = timeStamp.replace("May", "Mei");
      timeStamp = timeStamp.replace("Aug", "Ags");
      timeStamp = timeStamp.replace("Oct", "Okt");
      timeStamp = timeStamp.replace("Dec", "Des");

      x.innerHTML = timeStamp;
    }
  })
}

let refreshCd = 300000; //5mins
// 10000
// 300000



function changeLayout() {
  let pickGrid = document.getElementById('to-grid-layout');
  let pickList = document.getElementById('to-list-layout');

  let f_pin = "";

  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  if (pickGrid && pickList) {
    pickGrid.addEventListener('click', function () {
      if (window.Android) {
        // window.Android.isGrid("1");
        window.Android.setStateContentMode(1);
      }
      localStorage.setItem("is_grid", "1");
      window.location = 'tab3-main.php?f_pin=' + f_pin;
    })

    pickList.addEventListener('click', function () {
      if (window.Android) {
        // window.Android.isGrid("0");
        window.Android.setStateContentMode(0);
      }
      localStorage.setItem("is_grid", "0");
      window.location = 'tab1-main.php?f_pin=' + f_pin;
    })
  }
}


let gif_arr = [];
let gif_pos = [0, 1];

function getGIFs() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      gif_arr = JSON.parse(xmlHttp.responseText);
      // // console.log(gif_arr);
      let new_arr = gif_arr;
      if (gif_arr.length === 3) {
        new_arr = [gif_arr[1], gif_arr[0], gif_arr[2]];
      }
      if (new_arr.length > 0) {
        drawGIFs(new_arr);
      }
    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_gifs?f_pin=" + f_pin);
  xmlHttp.send();

}

function drawGIFs(arr) {
  let lastAd = parseInt(localStorage.getItem('last_ad'));

  if (lastAd == null) {
    lastAd = 0;
  }

  let currentAd = 0;
  if (lastAd + 1 <= arr.length - 1) {
    currentAd = lastAd + 1;
  } else {
    currentAd = 0;
  }

  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  localStorage.setItem('last_ad', currentAd);
  let pickGif = arr[currentAd];
  let currURL = window.location.href;
  // let pickGif = arr[currentAd];
  // // console.log(1);
  let url = "";
  if (arr.length > 0) {
    if (pickGif.URL.includes('bni.co.id')) {
      url = pickGif.URL;
    } else {
      let path = window.location.pathname.split("/");
      if (path.includes("tab1-main")) {
        url = pickGif.URL + '&f_pin=' + f_pin + '&origin=1';
      } else if (path.includes("tab1-main-only")) {
        url = pickGif.URL + '&f_pin=' + f_pin + '&origin=11';
      }
    }

  }
  let div = `
      <div id="gifs-${currentAd}" class="gifs">
      <a onclick="event.preventDefault(); goToURL('${url}');">
          <img src="/nexilis/assets/img/gif/${pickGif.FILENAME}">
        </a>
      </div>
    `;
  // // console.log("dir", pickGif.DIRECTION);
  // if (pickGif.FILENAME === "ppob-4.gif") {

  //   // // console.log('resize');
  //   $('.gifs img').css('width', '200px !important');
  //   $('.gifs img').css('height', 'auto !important');
  // }
  let dir = pickGif.DIRECTION;
  if (pickGif.DIRECTION === 'left') {
    // // console.log('sdnka');
    // if (pickGif.FILENAME.includes('ppob-4')) {
    //   $('#gif-container .gifs img').css('width', '200px');
    //   $('#gif-container .gifs img').css('height', 'auto');
    // }
    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass('right');
    // document.getElementById('gif-container').classList.add = 'right';
  } else if (pickGif.DIRECTION === 'right') {
    // // console.log('sdnka');
    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass('left');
    // document.getElementById('gif-container').classList.add = 'left';
  } else if (pickGif.DIRECTION === 'up') {
    // // console.log('sdnka');
    $('#gif-container').addClass('bottom');
    // document.getElementById('gif-container').classList.add = 'bottom';
    let dir = ['right', 'left'];
    let random = Math.floor(Math.random() * dir.length);
    // // console.log('random', dir[random])
    $('#gif-container').addClass(dir[random]);
    // document.getElementById('gif-container').classList.add = dir[random];
  } else if (pickGif.DIRECTION === 'down') {
    // // console.log('sdnka');
    $('#gif-container').addClass('top');
    // document.getElementById('gif-container').classList.add = 'top';
    let dir = ['right', 'left'];
    let random = Math.floor(Math.random() * dir.length);
    $('#gif-container').addClass(dir[random]);
    // document.getElementById('gif-container').classList.add = dir[random];
  } else if (pickGif.DIRECTION === null) {
    // // console.log('sdnka');
    let direct = ['right', 'left'];
    let random = Math.floor(Math.random() * direct.length);

    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass(direct[random]);
    if (direct[random] == 'left') {
      dir = 'right'
    } else {
      dir = 'left';
    }
    // document.getElementById('gif-container').classList.add = dir[random];
  }
  // document.getElementById('gif-container').innerHTML = div;
  $('#gif-container').append(div);
  // 
  if (document.getElementById('gifs-2') != null) {
    // // // console.log('sini anjing');
    // document.getElementById('gif-container').style.removeProperty('bottom');
    // document.getElementById('gif-container').style.top = '30px';
  }
  // randomAd(arr);
  animateAd(currentAd, pickGif.FILENAME, pickGif.ANIM_TYPE, dir);
}

function goToURL(url, checkIOS = false) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = url;
    }
  } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.checkProfile && checkIOS === false) {
    window.webkit.messageHandlers.checkProfile.postMessage({
      param1: url,
      param2: 'gif'
    });
    return;

  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = url;
  }
}

function animateAd(current, filename, which, direction) {
  // // console.log(which);
  // // console.log(direction);
  var lineHeight = $('#gifs-' + current).width();
  // // console.log(lineHeight);
  if (which === 'horizontal') { // move horizontal
    var windowHeight = $(window).width();
    var lineHeight = $('#gifs-' + current).width();
    var desiredBottom = 20;
    var newPosition = windowHeight - (115 + desiredBottom);
    if (direction === 'right') {

      // // // console.log('lh', lineHeight);
      // // // console.log('db', desiredBottom);
      // // // console.log('np', newPosition);
      $('#gif-container').animate({
        left: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          right: desiredBottom + 'px',
          left: 'auto'
        });
        $('#gif-container').fadeOut();
      });
    } else if (direction === 'left') {
      // if (filename == 'ppob-4.gif') {
      //   var newPosition = windowHeight - (200 + desiredBottom);
      //   $('#gif-container').animate({
      //     right: newPosition + 'px',
      //   }, 5000, function () {
      //     $('#gif-container').css({
      //       right: 'auto',
      //       left: '20px'
      //     });
      //     $('#gif-container').fadeOut();
      //   });
      // } 
      // else {
      $('#gif-container').animate({
        right: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          right: 'auto',
          left: '20px'
        });
        $('#gif-container').fadeOut();
      });
      // }
    }
  } else if (which === 'vertical') {
    var windowHeight = $(window).height();
    var lineHeight = 170;
    var desiredBottom = 140;
    // // console.log(desiredBottom);
    if (direction === "up") {
      var newPosition = windowHeight - (lineHeight + 30);
      $('#gif-container').animate({
        bottom: newPosition + 'px',
        // top: '30px'
      }, 5000, function () {
        $('#gif-container').css({
          top: '30px',
          bottom: 'auto'
        });
        $('#gif-container').fadeOut();
      });
    } else {
      var newPosition = windowHeight - (lineHeight + 140);
      $('#gif-container').animate({
        top: newPosition + 'px',
      }, 5000, function () {
        $('#gif-container').css({
          top: 'auto',
          bottom: '140px'
        });
        $('#gif-container').fadeOut();
      });
    }


  }

  $(window).scroll(function () {
    let scroll = $(window).scrollTop();
    if (scroll >= 150) {
      /* insert what happens after scroll bigger than 350px */
      $('#gif-container').stop(true, false);
      $('#gif-container').fadeOut();
    }
  })
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

  if (defaultCategory !== '' && defaultCategory !== null) {
    let visCat = visibleCategory.split('-');

    try {
      if (BE_ID === "347") {
        // // console.log("BE_ID", BE_ID);
        // // console.log("viscat", visCat);
        $("#root-category li").each(function () {
          let catId = $(this).attr('id').split("-")[1];
          // // console.log("CATID", catId);
          if (!visCat.includes(catId)) {
            // console.log('hide non-default')
            $(this).addClass("d-none");
          }
        })
        activeFilter = defaultCategory;
        const url = new URL(window.location);
        url.searchParams.set('filter', defaultCategory.replaceAll(',', '-'));
        window.history.pushState({}, '', url);
      }
    } catch (e) {
      // // console.log(e);
    }
  }
  checkboxBehavior();
  activeCategoryTab();
}

function checkboxBehavior() {
  $('#categoryFilter-body li :checkbox').on('click', function () {
    // // console.log('asdmas');
    var isChecked = $(this).is(":checked");

    //down
    $(this).closest('ul li').find("input:checkbox").prop("checked", isChecked);
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
      //
      if (defaultCategory !== '') {
        visibleCategory = defaultCategory;
        let defCat = defaultCategory.split(',');
        let visCat = visibleCategory.split(',');
        // console.log(defCat);
        if (activeFilter == "") {
          defCat.forEach(dc => {
            $('#root-category input#' + dc).prop('checked', true);
          })
        }

        try {
          // console.log(defCat);
          if (BE_ID === "347") {
            $("#root-category li").each(function () {
              let catId = $(this).attr('id').split("-")[1];
              // console.log(catId);
              if (!visCat.includes(catId)) {
                $(this).addClass("d-none");
              }
            })
            activeFilter = defaultCategory;
            const url = new URL(window.location);
            url.searchParams.set('filter', defaultCategory.replaceAll(',', '-'));
            window.history.pushState({}, '', url);
          }
        } catch (e) {

        }
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

function showHideFilter(bool) {
  if (bool) {
    $('#category-checkbox').removeClass('d-none');
    $('#toggle-filter').attr('src', '../assets/img/filter-icon-black.png')
    isSearchHidden = false;
  } else {
    // console.log('tutup');
    $('#category-checkbox').addClass('d-none');
    $('#toggle-filter').attr('src', '../assets/img/filter-icon-gray.png')
    isSearchHidden = true;
    selectCategoryFilter();
  }
}

$('#toggle-filter').click(function () {
  // $('#modal-categoryFilter').modal('toggle');
  // $(document).scrollTop(0);
  // startScrollPos = $(document).scrollTop();
  window.scrollTo({
    top: 0,
    behavior: 'instant',
  });
  showHideFilter($('#category-checkbox').hasClass('d-none'))
  // console.log(isSearchHidden);
  // navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
})

$(document).ready(function () {
  // randomAd();
  // if (document.getElementById('gif-container') != null) {
  //   getGIFs();
  // }
  let params = new URLSearchParams(window.location.search);
  if (params.get('verified') != null) {
    otherFilter.verified = params.get('verified')
  }
  if (params.get('friends') != null) {
    otherFilter.friends = params.get('friends')
  }
  if (params.get('others') != null) {
    otherFilter.others = params.get('others')
  }
  if (params.get('official') != null) {
    otherFilter.official = params.get('official')
  }
  // console.log(otherFilter);
  getMaxProducts(window.location.search);
  
  displayRecords(window.location.search, limit, offset, firstLoad);

  setInterval(function(){

    $('#pbr-timeline').html("");
    displayRecords(window.location.search, limit, offset);

  },600000);
  
  fetchCategory(); // LOAD BE CATEGORY FOR FILTER ICON
  getLikedProducts(); // LOAD ALL LIKED POST IN TABLE TO SAVED IN ARRAY (FOR COMPARISON LATER)
  getFollowedStores(); // LOAD ALL FOLLOWED STORE IN TABLE TO SAVED IN ARRAY (FOR COMPARISON LATER & ICON)
  getCommentedProducts(); // LOAD ALL COMMENT POST IN TABLE TO SAVED IN ARRAY (FOR COMPARISON LATER & ICON)
  fetchDefaultCategory(); // LOAD SPESIFIC CATEGORY DECLARED IN WEBAPPFORM
  fetchStores();
  activeCategoryTab(); // MATCHES $_GET DATA FILTER TO CHECKED CATEGORY
  fetchProductMap("");
  eraseQuery(); // DELETE RESET SEARCH BAR

  // selectCategoryFilter();
  // updateCounter();
  // timedRefresh(refreshCd);
  changeLayout(); // FUNCTION SET TO GRID/TIMELINE ICON CHANGES
  // fetchDefaultcategory();
  if (STORE_ID != "") {
    setCurrentStore(STORE_ID);
  }

  $('#submitCategory').click(function () {
    selectCategoryFilter();
  })



  $('#add-to-cart').click(function () {
    let itemQty = parseInt($('#modal-item-qty').val());
    addToCart(product_id, itemQty);
  })

  $(window).scroll(async function () { // ON SCROLL

    
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle.show'))
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl).toggle();
    })
    // make sure u give the container id of the data to be loaded in.
    // console.log("SCROLL", $(window).scrollTop() + $(window).height());
    // console.log("HEIGHT", $("#pbr-timeline").height() - ($(window).height()/2));
    if ($(window).scrollTop() + $(window).height() > ($("#pbr-timeline").height() - ($(window).height()/2)) && !busy && !isCalled) {
      // let maxProducts = await getMaxProducts(window.location.search); // CHECK ON MAX PRODUCTS
      console.log("SCROLL", maxProducts);
      isCalled = true;
      if (offset < maxProducts) {
        isCalled = false;
        busy = true;
        
        if (firstLoad == true) {
          offset = 3;
          limit = 5;
        } else {
          offset = limit + offset;
        }
        $('#loader-image').removeClass('d-none');

        displayRecords(window.location.search, limit, offset);

        firstLoad = false;
      }
      // // // console.log(offset);
    }
  });

  $('#addtocart-success').on('hide.bs.modal', function () {
    // updateCounter();
  })
});

window.onload = (event) => {

  horizontalScrollPos();
  if (document.getElementById('gif-container') != null) {
    getGIFs();
  }
};

$(window).on('unload', function () {
  $(window).scrollTop(0);
});
window.onunload = function () {
  window.scrollTo(0, 0);
}
if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}

function buttonTheme(category) {
  // // console.log('cat: ' + category);
  if (window.Android) {
    // window.Android.setButtonTheme(category);
  }
}

// window.addEventListener("touchstart", function (e) {
//   console.log("touchstart", e)
// })

// window.addEventListener("touchmove", function (e) {
//   console.log("touchmove", e)
// })

// window.addEventListener("touchend", function (e) {
//   console.log("touchend", e)
// })


let activeImage = null;

function resetZoom(activeImage) {
  try{
    activeImage.style.transform = "";
    activeImage.style.WebkitTransform = "";
    activeImage.style.zIndex = "";
      activeImage = null;
    let parent = activeImage.closest(".timeline-image");
    parent.style.zIndex = "";
    console.log("RESET ZOOM");
  } catch (e) {

  }
}

const pinchZoom = (imageElement) => {
  // // // console.log('element', imageElement);
  let imageElementScale = 1;

  let start = {};

  // Calculate distance between two fingers
  const distance = (event) => {
    return Math.hypot(event.touches[0].pageX - event.touches[1].pageX, event.touches[0].pageY - event.touches[1].pageY);
  };

  function pinchStart(event) {
    if (event.touches.length === 2) {
      event.preventDefault(); // Prevent page scroll
      isMultiTouch = event.touches.length;
      
  
      // Calculate where the fingers have started on the X and Y axis
      start.x = (event.touches[0].pageX + event.touches[1].pageX) / 2;
      start.y = (event.touches[0].pageY + event.touches[1].pageY) / 2;
      start.distance = distance(event);
    }
  }
  
  function pinchMove(event) {
    // for (let i = 0; i < event.targetTouches.length; i++) {
    //   console.log(`touchpoint[${i}].target`,event.targetTouches[i].target);
    // }

    if (event.touches.length === 2) {
      isMultiTouch = event.touches.length;
      event.preventDefault(); // Prevent page scroll

      
      // if (startPause == 0) {
        // Safari provides event.scale as two fingers move on the screen
        // For other browsers just calculate the scale manually
        let scale;
        if (event.scale) {
          scale = event.scale;
        } else {
          const deltaDistance = distance(event);
          scale = deltaDistance / start.distance;
        }
        imageElementScale = Math.min(Math.max(1, scale), 4);
  
        // Calculate how much the fingers have moved on the X and Y axis
        const deltaX = (((event.touches[0].pageX + event.touches[1].pageX) / 2) - start.x) * 2; // x2 for accelarated movement
        const deltaY = (((event.touches[0].pageY + event.touches[1].pageY) / 2) - start.y) * 2; // x2 for accelarated movement
  
        // Transform the image to make it grow and move with fingers
        const transform = `translate3d(${deltaX}px, ${deltaY}px, 0) scale(${imageElementScale})`;

        activeImage = imageElement;
        imageElement.style.transform = transform;
        imageElement.style.WebkitTransform = transform;
        imageElement.style.zIndex = "99999";
  
        let parent = imageElement.closest(".timeline-image");
        parent.style.zIndex = "99999"
    }
  }

  function pinchEnd(event) {
    console.log('touchend', event.touches);
    let touchLength = event.touches.length;
    for(let i = 0; i < touchLength; i++) {
      event.touches.item(i)
    }
    imageElement.style.transform = "";
    imageElement.style.WebkitTransform = "";
    imageElement.style.zIndex = "";
    activeImage = null;
    let parent = imageElement.closest(".timeline-image");
    parent.style.zIndex = "";
  }

  imageElement.removeEventListener('touchstart', pinchStart);
  imageElement.addEventListener('touchstart', pinchStart);

  imageElement.removeEventListener('touchmove', pinchMove);
  imageElement.addEventListener('touchmove', pinchMove);

  imageElement.removeEventListener('touchend', pinchEnd);
  imageElement.addEventListener('touchend', pinchEnd);


  imageElement.addEventListener("touchcancel", (e) => {
    console.log("CANCELED");
  })

  window.addEventListener("beforeunload",() => {
    console.log("PAUSE PINCH");
    imageElement.removeEventListener('touchstart', pinchStart);
    imageElement.removeEventListener('touchmove', pinchMove);
    imageElement.removeEventListener('touchend', pinchEnd);
  })
}

function removePinchZoom() {
  $(".timeline-main img, .timeline-main video").each(function() {
    $(this).off("touchstart");
    $(this).off("touchmove");
    $(this).off("touchend");
  })
}

function removePost(code) {
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      $('#modal-confirm-remove').modal('hide');

      let path = window.location.href.split('?')[0] + '?f_pin=' + F_PIN;

      $('#modal-post-removed #post-removed-close').click(function () {
        window.location.href = path;
      })

      $('#modal-post-removed').on('hidden.bs.modal', function () {
        window.location.href = path;
      })

      $('#modal-post-removed').modal('show');
    }
  }
  xmlHttp.open("get", "/nexilis/logics/admin_remove_post?post_id=" + code);
  xmlHttp.send();
}

function confirmRemovePost(code) {
  $('#modal-confirm-remove #remove-post-accept').off('click');

  $('#modal-confirm-remove #remove-post-accept').click(function () {
    removePost(code);
  })

  $('#modal-confirm-remove').modal('show');
}

function openProfile(url) {
  // localStorage.setItem('origin_page', location.href);

  // window.location.href = url;
  let finalUrl = url;

  if (BE_ID == "347") {
    let query = new URLSearchParams(window.location.search).get("query");
    let filter = new URLSearchParams(window.location.search).get("filter");
    if (query && query != "") {
      finalUrl += "&query=" + query;
    }
    if (filter && filter != "") {
      finalUrl += "&filter=" + filter;
    }
  }

  window.location.href = finalUrl;
}