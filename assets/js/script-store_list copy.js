var data = [];
var dataFiltered = [];

let limit = 21;
let offset = 0;
let busy = false;
let r = Math.floor(Math.random() * (2 - 1 + 1)) + 1; // grid random kiri atau kanan

var grid_stack = GridStack.init({
  float: false,
  disableOneColumnMode: true,
  column: 3,
  margin: 2.5,
  animate: false,
});

function loadClient() {
  // gapi.client.setApiKey("AIzaSyBlAojol4WmDKcloEy5OZHZa6SOkz88xEs"); //support@palio.io
  gapi.client.setApiKey("AIzaSyA7dTHAdSUFafMmm6W2BxHBhZ30ZarJmwk"); //
  return gapi.client.load("https://content.googleapis.com/discovery/v1/apis/customsearch/v1/rest")
    .then(function () {
        console.log("GAPI client loaded for API");
        // execute(searchSettings[0]);
        searchFilter();
      },
      function (err) {
        console.error("Error loading GAPI client for API", err);
      });
}

let cx = "920664ad732204d7a";

let searchSettings = [{
    "cx": cx,
    "q": "art",
    "excludeTerms": "jual",
    "safe": "active",
    "searchType": "image"
  },
  {
    "cx": cx,
    "exactTerms": "resep masakan",
    "excludeTerms": "jual",
    "safe": "active",
    "searchType": "image"
  },
  {
    "cx": cx,
    "excludeTerms": "jual",
    "q": "fashion",
    "safe": "active",
    "searchType": "image"
  },
  {
    "cx": cx,
    "exactTerms": "sepak bola",
    "excludeTerms": "jual",
    "safe": "active",
    "searchType": "image"
  },
  {
    "cx": cx,
    "exactTerms": "game terbaru",
    "dateRestrict": "d",
    "safe": "active",
    "searchType": "image"
  }
]

async function execute(list_obj) {
  await executeGAPI(list_obj);
  list_obj.start = 11;
  await executeGAPI(list_obj);
  fetchProductPics(currentShuffle);
}

// Make sure the client is loaded before calling this method.
function executeGAPI(list_obj) {
  return gapi.client.search.cse.list(list_obj)
    .then(function (response) {
        // Handle the results here (response.result has the parsed body).
        console.log(response.result);
        // currentShuffle = response.result.items;
        let arr = response.result.items;
        arr.forEach(ele => {
          currentShuffle.push(ele);
        })
        // fetchProductPics(currentShuffle);
      },
      function (err) {
        console.error("Execute error", err);
      });
}

gapi.load("client");

var ua = window.navigator.userAgent;
var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
var webkit = !!ua.match(/WebKit/i);
var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
var palioBrowser = !!ua.match(/PalioBrowser/i);
var isChrome = !!ua.match(/Chrome/i);

var big_list = new Map();

function isBig($position) {
  var div = Math.floor($position / 9);
  if (big_list.has(div)) {
    return (big_list.get(div) == $position);
  } else {
    var pos = (div * 9) + Math.floor(Math.random() * 8);
    big_list.set(div, pos);
    return (pos == $position);
  }
}

// to randomized array js
function shuffle(array) {
  var currentIndex = array.length,
    randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    // And swap it with the current element.
    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex], array[currentIndex]
    ];
  }

  return array;
}

var currentSort = 'popular';

// to get merchants that have products
function nonEmptyMerchants() {
  let xhr = new XMLHttpRequest();
  xhr.open('GET', '/nexilis/logics/non_empty_merchants.php');
  xhr.responseType = 'json';
  xhr.send();
  xhr.onload = function () {
    if (xhr.status != 200) { // analyze HTTP status of the response
      // console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found

    } else { // show the result
      let responseObj = xhr.response; // array
      localStorage.setItem("non_empty_merchant", JSON.stringify(responseObj));
      // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
    }
  };

  xhr.onerror = function () {
    // console.log("Request failed");
  };
}
nonEmptyMerchants();

// to shuffle product order in tab 1
function shuffleMerchants(sort_by) {
  let finalArr = [];
  if (sort_by == 'popular') {
    let all_merchants = dataFiltered; // array of all merchant
    let non_empty_merchants = JSON.parse(localStorage.getItem('non_empty_merchant')); // array of merchant code (that has products)

    let non_empty = [];
    let empty = [];
    all_merchants.forEach(merchant => {
      if (non_empty_merchants.includes(merchant.CODE)) {
        non_empty.push(merchant);
      } else {
        empty.push(merchant);
      }
    });

    finalArr = shuffle(non_empty).concat(shuffle(empty));
  } else if (sort_by == 'date') {
    let all_merchants = dataFiltered; // array of all merchant


    finalArr = all_merchants.sort((a, b) => (a.CREATED_DATE > b.CREATED_DATE) ? -1 : ((b.CREATED_DATE > a.CREATED_DATE) ? 1 : 0));
  } else if (sort_by == 'follower') {
    let all_merchants = dataFiltered; // array of all merchant

    finalArr = all_merchants.sort((a, b) => (a.TOTAL_FOLLOWER > b.TOTAL_FOLLOWER) ? -1 : ((b.TOTAL_FOLLOWER > a.TOTAL_FOLLOWER) ? 1 : 0))
  }

  // to make the non empty appear based on score, remove shuffle from non-empty
  // return non_empty.concat(shuffle(empty)) // shuffling only merchant with no products
  // return finalArr;
  return new Promise(function (resolve, reject) {
    resolve(finalArr);
  });
}

function gridCheck(arr, id) {
  const found = arr.some(el => el.id === id);
  return found;
}

var enableFollow = 0;
var showLinkless = 2;
var f_pin = '';
var gridElements = [];
var carouselIntervalId = 0;
let defaultSort = 'popular';
let currentShuffle = [];
var fillGridStack = async function ($grid, sort_by, lim, off) {

  // console.log('fillgridstack');

  gridElements = [];
  big_list.clear();
  var baseDelay = 5000; //(Math.max(5, dataFiltered.length) * 1000) / 2;

  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
  var $shop_blacklist = ["17b0ae770cd"]; //isi manual
  var ext_re = /(?:\.([^.]+))?$/;

  if (window.Android) {
    try {
      f_pin = window.Android.getFPin();
    } catch (err) {
      // console.log(err);
    }
  }

  // console.log('total: ' + currentShuffle.length);

  currentShuffle = await shuffleMerchants(defaultSort);

  //   console.log(currentShuffle);

  currentShuffle.slice(off, lim + 1).forEach((element, idx) => {
    if ($shop_blacklist.includes(element.CODE)) {
      return;
    }

    // console.log(element.CODE);
    var size;
    if (r == 1) {
      size = ((idx % 12 == 0 || idx % 12 == 7) ? 2 : 1);
    } else {
      size = ((idx % 12 == 1 || idx % 12 == 6) ? 2 : 1);
    }

    // console.log(idx + ', ' + size);

    var imageDivs = '';
    var imageArray = productImageMap.get(element.CODE);
    var delay = Math.floor(Math.random() * (baseDelay)) + 5000;
    //     console.log(imageArray);

    var merchantWebURL = ''
    if (element.LINK == null || element.LINK == '' || element.LINK == undefined) {
      // merchantWebURL = '/nexilis/pages/profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin;
      merchantWebURL = '/nexilis/pages/tab3-profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin;
    } else {
      merchantWebURL = element.LINK;
    }

    if (imageArray) {
      //       console.log('here');
      imageArray.forEach((image, jIdx) => {
        var imgElem = '';
        var fileExt = ext_re.exec(image)[1];
        if ($image_type_arr.includes(fileExt)) {
          imgElem = '<img class="content-image" src="' + image + '"/>'
        } else if ($video_type_arr.includes(fileExt)) {
          imgElem = '<video muted loop class="content-image"><source src="' + image + '" type="video/' + fileExt + '"></video>';
        }
        if (imgElem) {
          if (jIdx == 0) {
            imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
          } else {
            imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
          }
        }
      });
      var computed =
        // '<div class="grid-stack-item">'+
        // '<div class="grid-stack-item-content">' +
        // '<div class="inner" onclick="openStore(\'' + element.CODE + '\',\'' + merchantWebURL + '\');" ' +
        // ' oncontextmenu="openStoreMenu(\'' + element.CODE + '\',\'' + element.NAME + '\')"' +
        // '>' +
        '<a href="tab3-profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin + '">' +
        '<div class="inner">' +
        // '<img id="store-image-' + element.CODE + '" class="content-image" src="' + element.THUMB_ID + '" />' +
        '<div id="store-carousel-' + element.CODE + '" class="carousel slide" ' +
        // (imageArray.length > 1 ?
        ('data-bs-ride="carousel" data-bs-interval="false"')
        //  : (''))
        +
        '>' +
        '<div class="carousel-inner">' +
        imageDivs +
        '</div>' +
        '</div>' +
        '<div class="icon-merchant' + (element.BE_ID != null ? '' : ' d-none') + '"><img src="/nexilis/assets/img/icons/Verified-(Black).png"/><div class="merchant-name">' + element.NAME + '</div></div>' +
        '<div class="icon-live ' + (element.IS_LIVE_STREAMING > 0 ? '' : 'd-none') + '" id="live-' + element.CODE + '"><img src="/nexilis/assets/img/live_indicator.png"/></div>' +
        '<div class="viewer-count ' + (element.SHOW_FOLLOWS == 1 ? "" : "d-none") + '" id="visitor-' + element.CODE + '">' +
        '<img src="/nexilis/assets/img/jim_likes_red.png"/>' +
        '<span class="visitor-amt">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.TOTAL_LIKES) +
        '</span>' +
        '<img src="/nexilis/assets/img/jim_comments_blue.png"/>' +
        '<span class="follower-amt">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.COMMENTS) +
        '</span>' +
        '</div>' +
        '</div>' +
        '</a>'
      //  +
      // '</div></div>' 
      ;
      // grid_stack.addWidget({
      //   w: size,
      //   h: size,
      //   content: computed
      // });
      if (!gridCheck(gridElements, element.CODE)) {
        gridElements.push({
          id: element.CODE,
          minW: size,
          minH: size,
          maxW: size,
          maxH: size,
          content: computed
        });
      }
      if (imageArray.length > 1) {
        //   $('#store-carousel-' + element.CODE).carousel({
        //     ride: 'carousel',
        //     interval: delay
        //   });
        carouselList.push('#store-carousel-' + element.CODE + '');
      }
    }
  });


  // grid_stack.batchUpdate();

  //   console.log(gridElements);

  grid_stack.removeAll(true);
  grid_stack.load(gridElements, true);
  // grid_stack.commit();
  if (dataFiltered.length == 0) {
    $('#no-stores').removeClass('d-none');
  } else {
    $('#no-stores').addClass('d-none');
  }
  $('.carousel').each(function () {
    $(this).carousel();
    // setTimeout(() => {
    //   $(this).carousel('next');
    // }, Math.floor(Math.random() * (1000)) + 1000);
  });
  $('#stack-top').css('display', 'none');
  $('.overlay').addClass('d-none');
  checkVideoViewport();
  checkVideoCarousel();
  checkCarousel();
  correctVideoCrop();
  correctImageCrop();

  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  carouselIntervalId = setInterval(function () {
    carouselNext();
  }, 3000);

  $('#loading').addClass('d-none');

  // Promise.all(Array.from(document.querySelectorAll('.carousel-item.active img.content-image')).filter(img => !img.complete).map(img => new Promise(resolve => {
  //   img.onload = img.onerror = resolve;
  // }))).then(() => {
  //   // console.log('images finished loading');
  //   $('#loading').addClass('d-none');
  //   $('#content-grid').removeClass('d-none');
  // });
};

function fillGridWidgets(grid, sort_by, lim, off) {
  let start = off;
  let end = off + lim;

  console.log('next page');

  var baseDelay = 5000; //(Math.max(5, dataFiltered.length) * 1000) / 2;
  // console.table(dataFiltered);
  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
  var $shop_blacklist = ["17b0ae770cd"]; //isi manual
  var ext_re = /(?:\.([^.]+))?$/;

  if (window.Android) {
    try {
      f_pin = window.Android.getFPin();
    } catch (err) {
      // console.log(err);
    }
  }

  let batch = currentShuffle.slice(start, end);
  // console.log(batch);

  batch.forEach((element, idx) => {
    if ($shop_blacklist.includes(element.CODE)) {
      return;
    }

    var size;
    if (r == 1) {
      size = ((idx % 12 == 0 || idx % 12 == 7) ? 2 : 1);
    } else {
      size = ((idx % 12 == 1 || idx % 12 == 6) ? 2 : 1);
    }

    // console.log(idx + ', '  + size);

    var imageDivs = '';
    var imageArray = productImageMap.get(element.CODE);
    var delay = Math.floor(Math.random() * (baseDelay)) + 5000;

    var merchantWebURL = ''
    if (element.LINK == null || element.LINK == '' || element.LINK == undefined) {
      // merchantWebURL = '/nexilis/pages/profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin;
      merchantWebURL = '/nexilis/pages/tab3-profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin;
    } else {
      merchantWebURL = element.LINK;
    }

    // console.log(imageArray);

    if (imageArray) {
      imageArray.forEach((image, jIdx) => {
        var imgElem = '';
        var fileExt = ext_re.exec(image)[1];
        if ($image_type_arr.includes(fileExt)) {
          imgElem = '<img class="content-image" src="' + image + '"/>'
        } else if ($video_type_arr.includes(fileExt)) {
          imgElem = '<video muted loop class="content-image"><source src="' + image + '" type="video/' + fileExt + '"></video>';
        }
        if (imgElem) {
          if (jIdx == 0) {
            imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
          } else {
            imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
          }
        }
      });
      var computed =
        // '<div class="inner" onclick="openStore(\'' + element.CODE + '\',\'' + merchantWebURL + '\');" ' +
        // ' oncontextmenu="openStoreMenu(\'' + element.CODE + '\',\'' + element.NAME + '\')"' +
        // '>' +
        '<a href="tab3-profile.php?store_id=' + element.CODE + '&f_pin=' + f_pin + '">' +
        '<div class="inner">' +
        '<div id="store-carousel-' + element.CODE + '" class="carousel slide" ' +
        ('data-bs-ride="carousel" data-bs-interval="false"') +
        '>' +
        '<div class="carousel-inner">' +
        imageDivs +
        '</div>' +
        '</div>' +
        '<div class="icon-merchant' + (element.BE_ID != null ? '' : ' d-none') + '"><img src="/nexilis/assets/img/icons/Verified-(Black).png"/><div class="merchant-name">' + element.NAME + '</div></div>' +
        '<div class="icon-live ' + (element.IS_LIVE_STREAMING > 0 ? '' : 'd-none') + '" id="live-' + element.CODE + '"><img src="/nexilis/assets/img/live_indicator.png"/></div>' +
        '<div class="viewer-count ' + (element.SHOW_FOLLOWS == 1 ? "" : "d-none") + '" id="visitor-' + element.CODE + '">' +
        '<img src="/nexilis/assets/img/jim_likes_red.png"/>' +
        '<span class="visitor-amt">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.TOTAL_LIKES) +
        '</span>' +
        '<img src="/nexilis/assets/img/jim_comments_blue.png"/>' +
        '<span class="follower-amt">' +
        new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(element.COMMENTS) +
        '</span>' +
        '</div>' +
        '</div>' +
        '</a>';
      if (!gridCheck(gridElements, element.CODE)) {
        gridElements.push({
          id: element.CODE,
          minW: size,
          minH: size,
          maxW: size,
          maxH: size,
          content: computed
        });
        grid_stack.addWidget({
          id: element.CODE,
          minW: size,
          minH: size,
          maxW: size,
          maxH: size,
          content: computed
        });
      }
      if (imageArray.length > 1) {
        carouselList.push('#store-carousel-' + element.CODE + '');
      }
    }
  });

  grid_stack.compact();

  if (dataFiltered.length == 0) {
    $('#no-stores').removeClass('d-none');
  } else {
    $('#no-stores').addClass('d-none');
  }
  $('.carousel').each(function () {
    $(this).carousel();
  });
  $('#stack-top').css('display', 'none');
  $('.overlay').addClass('d-none');
  checkVideoViewport();
  checkVideoCarousel();
  checkCarousel();
  correctVideoCrop();
  correctImageCrop();

  busy = false;

  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  carouselIntervalId = setInterval(function () {
    carouselNext();
  }, 3000);
}

var nextCarouselIdx = 0;
var carouselList = [];

function carouselNext() {
  if (carouselList.length <= 0) return;
  let prevIdx = nextCarouselIdx;
  while (!$(carouselList[nextCarouselIdx]).is(":in-viewport")) {
    nextCarouselIdx = nextCarouselIdx + 1;
    if (nextCarouselIdx >= carouselList.length) {
      nextCarouselIdx = 0;
    }
    if (nextCarouselIdx == prevIdx) break;
  }
  $(carouselList[nextCarouselIdx]).carousel('next');
  nextCarouselIdx = nextCarouselIdx + 1;
  if (nextCarouselIdx >= carouselList.length) {
    nextCarouselIdx = 0;
  }
}



function correctVideoCrop() {
  var videos = document.querySelectorAll("video.content-image");
  videos.forEach(function (elem) {
    elem.addEventListener("loadedmetadata", function () {
      if (elem.videoWidth > elem.videoHeight) {
        elem.classList.add("landscape");
      }
    })
  })
}

function correctImageCrop() {
  var images = document.querySelectorAll("img.content-image");
  images.forEach(function (elem) {
    elem.addEventListener("load", function () {
      if (elem.width > elem.height) {
        elem.classList.add("landscape");
      }
    })
  })
}

function openStore($store_code, $store_link) {
  if (window.Android) {
    if (storeMap.has($store_code)) {
      var storeOpen = storeMap.get($store_code);

      var xmlHttp = new XMLHttpRequest();
      xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
          if (xmlHttp.status == 200) {
            let dataStore = JSON.parse(xmlHttp.responseText);
            storeData = JSON.stringify(dataStore[0]);
          }
          window.Android.openStore(storeOpen);
        }
      }
      xmlHttp.open("get", "/nexilis/logics/fetch_stores_specific?store_id=" + $store_code);
      xmlHttp.send();
    }
  } else {
    window.location.href = $store_link;
  }
}

function openStoreMenu($storeCode, $storeName) {
  if (window.Android) {
    if (storeMap.has($storeCode)) {
      var storeOpen = storeMap.get($storeCode);
      window.Android.openStoreMenu(storeOpen);
    }
  }
}

function fetchRewardPoints() {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let resp = JSON.parse(xmlHttp.responseText);
      // // console.log(resp);

      if (resp.length > 0) {
        resp.forEach(abc => {
          let storeIndex = dataFiltered.findIndex(dt => dt.CODE == abc.STORE_CODE);
          dataFiltered[storeIndex].REWARD_PTS = abc.AMOUNT;
          // // console.log(storeIndex);
        });
      }
    }
  };

  if (window.Android) {
    var f_pin = window.Android.getFPin();
    // var f_pin = "0282aa57c9";
    // var fpin_lokal = "0282aa57c9";
    if (f_pin) {
      xmlHttp.open("get", "/nexilis/logics/fetch_stores_reward_user_raw?f_pin=" + f_pin);
    } else {
      xmlHttp.open("get", "/nexilis/logics/fetch_stores_reward_user_raw");
    }
  } else {
    xmlHttp.open("get", "/nexilis/logics/fetch_stores_reward_user_raw");
    // var f_pin = "0282aa57c9";
    // xmlHttp.open("get", "/nexilis/logics/fetch_stores_reward_user_raw?f_pin=" + f_pin);
  }

  xmlHttp.send();
}

function checkVideoViewport() {

  if (palioBrowser && isChrome) {
    $('.carousel-item video, .timeline-image video').each(function () {
      if ($(this).is(":in-viewport")) {
        // pause carousel when video is playing
        $(this).off("play");
        $(this).on("play", function (e) {
          $(this).closest(".carousel").carousel("pause");
        })
        $(this).get(0).play();
      } else {
        // start carousel when video is not playing
        $(this).off("stop pause ended");
        $(this).on("stop pause ended", function (e) {
          $(this).closest(".carousel").carousel();
        });
        $(this).get(0).pause();
      }
    })
  }
}

function checkVideoCarousel() {
  // play video when active in carousel
  $(".carousel").on("slid.bs.carousel", function (e) {
    if (palioBrowser && isChrome) {
      if ($(this).find("video").length) {
        if ($(this).find(".carousel-item").hasClass("active")) {
          $(this).find("video").get(0).play();
        } else {
          $(this).find("video").get(0).pause();
        }
      }
    }
  });
}

var visibleCarousel = new Set();

function checkCarousel() {
  $('.carousel').each(function () {
    if ($(this).is(":in-viewport")) {
      if (!visibleCarousel.has($(this).attr('id'))) {
        visibleCarousel.add($(this).attr('id'));
        $(this).carousel('cycle');
      }
    } else {
      if (visibleCarousel.has($(this).attr('id'))) {
        visibleCarousel.delete($(this).attr('id'));
        $(this).carousel('pause');
      }
    }
  });
}

// start periodic when window in focus
$(window).focus(function () {
  //do something
  refreshId = setInterval(function () {
    updateStoreViewer();
  }, 10000);
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  carouselIntervalId = setInterval(function () {
    carouselNext();
  }, 3000);
});

// stop periodic when window out of focus
$(window).blur(function () {
  //do something
  clearInterval(refreshId);
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
    carouselIntervalId = 0;
  }

});

var refreshId = 0;
$(function () {
  // fillGridStack('#content-grid');
  // registerPulldown();
  $(window).scroll(function () {
    scrollFunction();
    didScroll = true;

    // play video when is in view
    checkVideoViewport();
    checkVideoCarousel();
    checkCarousel();
  });
  if (localStorage.getItem("store_data") !== null && localStorage.getItem("store_pics_data") !== null) {
    prefetchStores();
  }
  fillFilter();
  // getFollowSetting();
  getShowLinklessSetting();
  fetchStores();
  updateStoreViewer();
  activeCategoryTab();
  // selectCategoryFilter();
});

var storeMap = new Map();

function prefetchStores() {
  data = JSON.parse(localStorage.getItem("store_data"));
  filterStoreData(filter, search, false);
  dataFiltered.forEach(storeEntry => {
    storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
  });
  dataFiltered = [];
  dataFiltered = dataFiltered.concat(data);

  var productData = JSON.parse(localStorage.getItem("store_pics_data"));
  productData.forEach(storeEntry => {
    $thumb_ids = storeEntry.THUMB_ID.split("|");
    $thumb_ids.forEach(function (thumbid, index) {
      if (!thumbid.startsWith("http")) {
        var root = 'http://' + location.host;
        var profPic = "";

        if (thumbid == null || thumbid == "") {
          profPic = "/nexilis/assets/img/palio.png";
        } else {
          // profpic = root + ":2809/file/image/" + storeEntry.THUMB_ID;
          profPic = "/nexilis/images/" + thumbid;
        }
        $thumb_ids[index] = profPic;
      }
    });
    if (!productImageMap.has(storeEntry.STORE_CODE)) {
      productImageMap.set(storeEntry.STORE_CODE, $thumb_ids);
    } else if (productImageMap.get(storeEntry.STORE_CODE).length < 3) {
      productImageMap.set(storeEntry.STORE_CODE, productImageMap.get(storeEntry.STORE_CODE).concat($thumb_ids));
    }
  });
  // fillGridStack('#content-grid', currentSort, limit, offset);
}

function getFollowSetting() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      dataFollowSetting = JSON.parse(xhr.responseText);

      // // console.log(data);
      enableFollow = dataFollowSetting;
    }
  };
  xhr.open("get", "/nexilis/logics/fetch_stores_settings?param=stats");
  xhr.send();
}

function getShowLinklessSetting() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      dataShowLinklessSetting = JSON.parse(xhr.responseText);

      // // console.log(data);
      showLinkless = dataShowLinklessSetting;
    }
  };
  xhr.open("get", "/nexilis/logics/fetch_stores_settings?param=show_linkless");
  xhr.send();
}

// function fetchStores() {
//   // var formData = new FormData();
//   // formData.append('f_pin', localStorage.F_PIN);

//   var xmlHttp = new XMLHttpRequest();
//   xmlHttp.onreadystatechange = function () {
//     if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
//       data = JSON.parse(xmlHttp.responseText);
//       filterStoreData(filter, search, false);
//       dataFiltered.forEach(storeEntry => {
//         storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
//       });
//       // dataFiltered = [];
//       // dataFiltered = dataFiltered.concat(data);
//       localStorage.setItem("store_data", xmlHttp.responseText);
//       fetchProductPics();

//     }
//   }

//   if (window.Android) {
//     var f_pin = window.Android.getFPin();
//     if (f_pin) {
//       xmlHttp.open("get", "/nexilis/logics/fetch_stores?f_pin=" + f_pin);
//     } else {
//       xmlHttp.open("get", "/nexilis/logics/fetch_stores");
//     }
//   } else {
//     xmlHttp.open("get", "/nexilis/logics/fetch_stores");
//     // var f_pin = "0282aa57c9";
//     // xmlHttp.open("get", "/nexilis/logics/fetch_stores?f_pin=" + f_pin);
//   }

//   xmlHttp.send();
// }

function fetchStores() {
  // var formData = new FormData();
  // formData.append('f_pin', localStorage.F_PIN);

  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      data = JSON.parse(xmlHttp.responseText);
      filterStoreData(filter, search, false);
      dataFiltered.forEach(storeEntry => {
        storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
      });
      // dataFiltered = [];
      // dataFiltered = dataFiltered.concat(data);
      localStorage.setItem("store_data", xmlHttp.responseText);
      fetchProductPics();

    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_links");

  xmlHttp.send();
}

function updateStoreViewer() {
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      let dataStoreViewer = JSON.parse(xmlHttp.responseText);
      dataStoreViewer.forEach(storeEntry => {
        if (storeEntry.IS_LIVE_STREAMING > 0) {
          $('#live-' + storeEntry.CODE).removeClass('d-none');
        } else {
          $('#live-' + storeEntry.CODE).addClass('d-none');
        }
        $('#visitor-' + storeEntry.CODE + ' span.visitor-amt').html('' + new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(storeEntry.TOTAL_VISITOR));
        $('#visitor-' + storeEntry.CODE + ' span.follower-amt').html('' + new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(storeEntry.TOTAL_FOLLOWER));
      });
    }
  }

  if (window.Android) {
    var f_pin = window.Android.getFPin();
    if (f_pin) {
      xmlHttp.open("get", "/nexilis/logics/fetch_stores?f_pin=" + f_pin);
    } else {
      xmlHttp.open("get", "/nexilis/logics/fetch_stores");
    }
  } else {
    xmlHttp.open("get", "/nexilis/logics/fetch_stores");
  }

  xmlHttp.send();
}

var productImageMap = new Map();
var productImageCountMap = new Map();

function fetchProductPics() {
  var formData = new FormData();
  formData.append('f_pin', localStorage.F_PIN);


  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      var productData = JSON.parse(xmlHttp.responseText);
      productData.forEach(storeEntry => {
        if (storeEntry.IS_SHOW == "0") return;
        if (productImageCountMap.has(storeEntry.STORE_CODE) && productImageCountMap.get(storeEntry.STORE_CODE) >= 3) {
          return;
        }

        $thumb_ids = storeEntry.THUMB_ID.split("|");
        $thumb_ids.forEach(function (thumbid, index) {
          if (!thumbid.startsWith("http")) {
            var root = 'http://' + location.host;
            var profPic = "";

            if (thumbid == null || thumbid == "") {
              profPic = "/nexilis/assets/img/palio.png";
            } else {
              // profpic = root + ":2809/file/image/" + storeEntry.THUMB_ID;
              profPic = "/nexilis/images/" + thumbid;
            }
            $thumb_ids[index] = profPic;
          }
        });

        if (!productImageMap.has(storeEntry.STORE_CODE)) {
          productImageMap.set(storeEntry.STORE_CODE, $thumb_ids);
        }
        //  else {
        //   productImageMap.set(storeEntry.STORE_CODE, productImageMap.get(storeEntry.STORE_CODE).concat($thumb_ids));
        // }
        // // console.log(productImageMap);
        if (!productImageCountMap.has(storeEntry.STORE_CODE)) {
          productImageCountMap.set(storeEntry.STORE_CODE, 1);
        } else {
          productImageCountMap.set(storeEntry.STORE_CODE, productImageCountMap.get(storeEntry.STORE_CODE) + 1);
        }
      });
      localStorage.setItem("store_pics_data", xmlHttp.responseText);
      fillGridStack('#content-grid', currentSort, limit, offset);
    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_products_image");
  xmlHttp.send();
}

var hiddenStores = [];

function filterStoreData($filterCategory, $filterSearch, isSearching) {
  if (window.Android) {
    try {
      hiddenStores = window.Android.getHiddenStores().split(",");
    } catch (error) {

    }
  }

  dataFiltered = [];
  data.forEach(storeEntry => {
    if (showLinkless == 2 || (showLinkless == 1 && !storeEntry.LINK) || (showLinkless == 0 && storeEntry.LINK)) {
      var isMatchCategory = false;
      if ($filterCategory) {
        var categoryArray = $filterCategory.split("-");
        isMatchCategory = categoryArray.indexOf(storeEntry.CATEGORY + "") > -1;
      } else {
        isMatchCategory = true;
      }

      var isMatchSearch = false;
      if ($filterSearch) {
        isMatchSearch = isMatchSearch || storeEntry.NAME.toLowerCase().includes($filterSearch.toLowerCase());
        isMatchSearch = isMatchSearch || storeEntry.DESCRIPTION.toLowerCase().includes($filterSearch.toLowerCase());
        isMatchSearch = isMatchSearch || storeEntry.LINK.toLowerCase().includes($filterSearch.toLowerCase());
      } else {
        isMatchSearch = true;
      }

      if (isMatchCategory && isMatchSearch && !hiddenStores.includes(storeEntry.CODE)) {
        dataFiltered.push(storeEntry);
        if (isSearching) {
          // fillGridStack('#content-grid', currentSort, limit, offset);
        }
      }
    }

  });
  if (isSearching) {
    fillGridStack('#content-grid', currentSort, limit, offset);
  }
  fetchRewardPoints();
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
        // // console.log(xmlHttp.responseText);
        let dataVisitStore = JSON.parse(xmlHttp.responseText);
        $('#visitor-' + $store_code + ' span').html('' + new Intl.NumberFormat('en-US', {
          maximumFractionDigits: 1,
          notation: "compact"
        }).format(dataVisitStore[0].TOTAL_VISITOR));
      }
    }
    xmlHttp.open("post", "/nexilis/logics/visit_store");
    xmlHttp.send(formData);
  }
}

var mouseY = 0;
var startMouseY = 0;

// function registerPulldown() {
//   PullToRefresh.init({
//     mainElement: '#content-grid',
//     onRefresh: function () {
//       window.location.reload();
//     }
//   });
// }

var didScroll;
var isSearchHidden = true;
var lastScrollTop = 0;
var delta = 1;
var navbarHeight = $('#header-layout').outerHeight();
var topPosition = 0;

setInterval(function () {
  if (didScroll) {
    hasScrolled();
    didScroll = false;
  }
}, 10);

function headerOut() {
  $('#searchFilter').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

$('#header').click(function () {
  $(document).scrollTop(0);
  if ($('#searchFilter').hasClass('d-none')) {
    $('#searchFilter').removeClass('d-none');
    isSearchHidden = false;
  } else {
    $('#searchFilter').addClass('d-none');
    isSearchHidden = true;
    const query = $('#query').val();

    if (!isFilterCheckedAny()) {
      resetFilter();
    } else
    if (isFilterCheckedAny() || query != "") {
      searchFilter();
    } else if (query == "") {
      var url_string = window.location.href;
      var url = new URL(url_string);
      var paramValue = url.searchParams.get("query");
      if (paramValue != null) {
        searchFilter();
      }
    }
  }
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  $('#gear').rotate({
    angle: 0,
    animateTo: 180
  });
});

// function hasScrolled() {
//   var st = $(this).scrollTop();

//   // Make sure they scroll more than delta
//   // if(Math.abs(lastScrollTop - st) <= delta)
//   //     return;

//   // If they scrolled down and are past the navbar, add class .nav-up.
//   // This is necessary so you never see what is "behind" the navbar.
//   // console.log(navbarHeight);
//   if (st > lastScrollTop) {
//     if (topPosition - (st - lastScrollTop) < -navbarHeight) {
//       topPosition = -navbarHeight;
//     } else {
//       topPosition = topPosition - (st - lastScrollTop);
//     }
//     const tp = '' + topPosition + "px";

//     // Scroll Down
//     $('#header-layout').css('top', tp);
//   } else {
//     if (topPosition - (st - lastScrollTop) > 0) {
//       topPosition = 0;
//     } else {
//       topPosition = topPosition - (st - lastScrollTop);
//     }
//     const tp = '' + topPosition + "px";

//     // Scroll Up
//     if (st + $(window).height() < $(document).height()) {
//       $('#header-layout').css('top', tp);
//     }
//   }
//   lastScrollTop = st;
// }

let headerHeight = $('#header-layout').outerHeight();

function hasScrolled() {
  var st = $(this).scrollTop();

  // Make sure they scroll more than delta
  if (Math.abs(lastScrollTop - st) <= delta)
    return;

  // If they scrolled down and are past the navbar, add class .nav-up.
  // This is necessary so you never see what is "behind" the navbar.
  if (st > lastScrollTop && st > navbarHeight) {
    // Scroll Down
    $('#header-layout').css('top', -headerHeight + 'px');
  } else {
    // Scroll Up
    if (st + $(window).height() < $(document).height()) {
      $('#header-layout').css('top', '0px');
    }
  }

  lastScrollTop = st;
}

$(function () {
  $(window).scroll(function () {
    scrollFunction();
    didScroll = true;
  });
});

function scrollFunction() {
  if ($(document).scrollTop() > navbarHeight) {
    // if (!isSearchHidden)
    // headerOut();
    $("#scroll-top").css('display', 'block');
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
}

function resetFilter() {
  var needRefresh = false;
  if ($('#query').val() || !$("#switchAll").is(':checked')) {
    needRefresh = true;
  }
  $("#mic").attr("src", "../assets/img/action_mic.png");
  $('#query').val('');
  $('#switchAll').prop('checked', true);
  setFilterCheckedAll(true);
  if (!isSearchHidden) {
    // headerOut();
  }
  if (needRefresh) {
    searchFilter();
  }
}

function changeBg(category) {
  let imgBg = document.querySelector('.demo-bg');
  // console.log(category);

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

let activeFilter = '';

function searchFilter() {
  // $('#loading').removeClass('d-none');
  setTimeout(function () {
    // // console.log("here");
    var dest = "";
    const query = $('#query').val();
    var filter = activeFilter;
    if (query != "" || filter != "") {
      if (!dest.includes("?")) {
        dest = dest + "?";
      } else {
        dest = dest = "&";
      }
    }
    if (query != "") {
      let urlEncodedQuery = encodeURIComponent(query);
      dest = dest + "query=" + urlEncodedQuery;
      if (filter != "") {
        dest = dest + "&";
      }
    }
    if (filter != "") {
      let urlEncodedFilter = encodeURIComponent(filter);
      dest = dest + "filter=" + urlEncodedFilter;
    }
    // window.location.href = dest;
    if (!dest) dest = "?"
    history.pushState({
      'search': query,
      'filter': filter
    }, "Palio Browser", dest);
    offset = 0;
    r = Math.floor(Math.random() * (2 - 1 + 1)) + 1;
    filterStoreData(filter, query, true);
  }, 500);
}

function selectCategoryFilter() {
  $('#category-tabs .nav .nav-item .nav-link').each(function () {
    $(this).click(function () {
      busy = true;
      // STORE_ID = "";
      activeFilter = $(this).attr('id').split('-')[1];
      if (activeFilter == "all") {
        activeFilter = "";
      }
      $(this).addClass('active');
      $('#category-tabs .nav-link:not(#categoryFilter-' + activeFilter + ')').removeClass('active');
      $('#content-grid').html('');
      // $('#content-grid').addClass('d-none');
      searchFilter();
    })
  });
}

function activeCategoryTab() {
  let urlSearchParams = new URLSearchParams(window.location.search);
  let activeParam = urlSearchParams.get('filter');
  activeFilter = activeParam;

  if (activeParam == null) {
    activeParam = "all";
    activeFilter = "";
  }

  $('#categoryFilter-' + activeParam).addClass('active');
  $('#category-tabs .nav-link:not(#categoryFilter-' + activeParam + ')').removeClass('active');
}

function voiceSearch() {
  if (window.Android) {
    $isVoice = window.Android.toggleVoiceSearch();
    toggleVoiceButton($isVoice);
  }
}

function submitVoiceSearch($searchQuery) {
  // // console.log("submitVoiceSearch " + $searchQuery);
  $('#query').val($searchQuery);
  searchFilter();
}

function toggleVoiceButton($isActive) {
  if ($isActive) {
    $("#mic").attr("src", "../assets/img/action_mic_blue.png");
  } else {
    $("#mic").attr("src", "../assets/img/action_mic.png");
  }
}

function pauseAll() {
  $('.carousel-item video, .timeline-image video').each(function () {
    $(this).get(0).pause();
  })
  visibleCarousel.clear();
  $('.carousel').each(function () {
    $(this).carousel('pause');
  })
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
    carouselIntervalId = 0;
  }
}

function resumeAll() {
  checkVideoViewport();
  checkVideoCarousel();
  checkCarousel();
  updateCounter();
  fetchNotifCount();
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  carouselIntervalId = setInterval(function () {
    carouselNext();
  }, 3000);
}

$('#searchFilterForm').validate({
  rules: {
    'category[]': {
      required: true
    }
  },
  messages: {
    'category[]': {
      required: '<div class="alert alert-danger" role="alert">Pilih minimal salah satu filter di atas</div>',
    },
  },
  submitHandler: function (form) {
    searchFilter();
  },
  errorClass: 'help-block',
  errorPlacement: function (error, element) {
    if (element.attr('name') == 'category[]') {
      error.insertAfter('#checkboxGroup');
    }
  }

});

function hideSortDropdown() {
  $('#stack-top').css('display', 'none');
  $('#grid-overlay').addClass('d-none');
}

async function changeSort(sort) {
  currentSort = sort;
  currentShuffle = await shuffleMerchants(sort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
}

$('#sort-store-popular').click(async function () {
  currentSort = 'popular';
  currentShuffle = await shuffleMerchants(currentSort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
  $('#sort-store-popular .check-mark').removeClass('d-none');
  $('#sort-store-date .check-mark').addClass('d-none');
  $('#sort-store-follower .check-mark').addClass('d-none');
})

$('#sort-store-date').click(async function () {
  currentSort = 'date';
  currentShuffle = await shuffleMerchants(currentSort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
  $('#sort-store-popular .check-mark').addClass('d-none');
  $('#sort-store-date .check-mark').removeClass('d-none');
  $('#sort-store-follower .check-mark').addClass('d-none');
})

$('#sort-store-follower').click(async function () {
  currentSort = 'follower';
  currentShuffle = await shuffleMerchants(currentSort);
  offset = 0;
  fillGridStack('#content-grid', currentSort, limit, offset);
  $('#sort-store-popular .check-mark').addClass('d-none');
  $('#sort-store-date .check-mark').addClass('d-none');
  $('#sort-store-follower .check-mark').removeClass('d-none');
})

function eraseQuery() {

  if ($('#searchFilterForm-a input#query').val() != '') {
    $('#delete-query').removeClass('d-none');
  }

  $("#delete-query").click(function () {
    $('#searchFilterForm-a input#query').val('');
    $('#delete-query').addClass('d-none');
  })

  $('#searchFilterForm-a input#query').keyup(function () {
    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }
  })
}

function resetSearch() {
  document.getElementById('query').value = '';
}

function checkDupes() {
  let nodes = document.querySelectorAll('#content-grid>div[gs-id]');
  let ids = {};
  let totalNodes = nodes.length;

  for (let i = 0; i < totalNodes; i++) {
    let currentId = nodes[i].gridstackNode.id ? nodes[i].gridstackNode.id : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }

  // console.log(ids);
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

function onFocusSearch() {
  if (window.Android) {
    try {
      window.Android.onFocusSearch();
    } catch (e) {

    }
  }
}

$(function () {
  // selectCategoryFilter();
  updateCounter();

  let urlParams = new URLSearchParams(window.location.search);
  let activeCat = urlParams.get('filter');

  if (activeCat != null) {
    $('#categoryFilter-' + activeCat).addClass('active');
    $('.nav-link:not(#categoryFilter-' + activeCat + ')').removeClass('active');
  } else {
    $('#categoryFilter-all').addClass('active');
    $('.nav-link:not(#categoryFilter-all)').removeClass('active');
  }

  let sortMenu = document.getElementById("stack-top");
  $('#grid-overlay').click(function () {
    if (sortMenu.style.display == "block") {
      sortMenu.style.display = 'none';
      $('#grid-overlay').addClass('d-none');
    }
  });

  eraseQuery();

  $('form#searchFilterForm-a').get(0).reset();
  $('#delete-query').addClass('d-none');
})

$(window).on('load', function () {
  $(window).scroll(function () {
    // make sure u give the container id of the data to be loaded in.
    // console.log(Math.ceil($(window).scrollTop() + $(window).height()) >= $("#content-grid").height());
    // console.log(busy);
    if ((Math.ceil($(window).scrollTop() + ($(window).height() * 1.5)) >= $("#content-grid").height()) && !busy) {
      // console.log('scroll here');
      busy = true;
      offset = limit + offset;
      // displayRecords(limit, offset);
      fillGridWidgets('#content-grid', currentSort, limit, offset);
    }
  });
})