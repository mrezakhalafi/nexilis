var data = [];
var dataFiltered = [];

var STORE_ID = "";

let defaultCategory = '';
let visibleCategory = "";

window.addEventListener("storage", async function () {
  if (sessionStorage.getItem('refresh') == 1) {
    sessionStorage.removeItem('refresh');
    window.location.reload();
  }
}, false);

window.addEventListener('contextmenu', (e) => {
  e.preventDefault();
  e.stopPropagation();
}) 

let limit = 11;
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

var otherFilter = {
  friends: 0,
  verified: 0,
  others: 0
}

var ua = window.navigator.userAgent;
// var iOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
// var webkit = !!ua.match(/WebKit/i);
// var iOSSafari = iOS && webkit && !ua.match(/CriOS/i);
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
    // // // console.log("Request failed");
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

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

// function ext(url) {
//   return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf("."));
// }

function ext(url) {
  return (url = url.substr(1 + url.lastIndexOf("/")).split('?')[0]).split('#')[0].substr(url.lastIndexOf(".") + 1);
}

function editPost(code) {
  if (window.Android) {
    let f_pin = window.Android.getFPin();

    window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = "tab5-edit-post.php?f_pin=" + f_pin + "&post_id=" + code;
  }
}

// DELETE POST
function deletePost(post_id) {

  $('#just-post').addClass('d-none');
  $('#just-product').addClass('d-none');
  $('#both-pp').addClass('d-none');

  $('#modal-product').modal('hide');
  $('#modal-delete-question').modal('show');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);

  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

          var response = xmlHttp.response.split("|");
          console.log(response);

          var isPost = response[0];
          var isProduct = response[1];

          if (isPost == 1){
              $('#just-post').removeClass('d-none');
              $('#just-post').attr('onclick','deletePostOnly(`'+post_id+'`)');
          }

          if (isProduct == 1){
              $('#just-product').removeClass('d-none');
              $('#just-product').attr('onclick','deleteProductOnly(`'+post_id+'`)');
          }

          if (isPost == 1 & isProduct == 1){
              $('#both-pp').removeClass('d-none');
              $('#both-pp').attr('onclick','deleteBothContent(`'+post_id+'`)');
          }

      }
  } 
  xmlHttp.open("POST", "/nexilis/logics/check_delete");
  xmlHttp.send(formData);

}

function deletePostOnly(post_id){

  $('#modal-delete-question').modal('hide');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);
  formData.append('ec_date', new Date().getTime());
  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          if (xmlHttp.responseText == "Success") {
              console.log(post_id + ' deleted');
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>Post deleted.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Postingan telah dihapus.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                  window.location.reload();
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

function deleteProductOnly(post_id){

  $('#modal-delete-question').modal('hide');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('product_code', post_id);

  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          if (xmlHttp.responseText == "Success") {
              console.log(post_id + ' deleted');
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>Product deleted.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Produk telah dihapus.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                  window.location.reload();
              });
              $('#delete-post-info').modal('toggle');
          } else {
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>An error occured while deleting product. Please refresh and try again.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Error saat menghapus produk. Silahkan muat ulang dan coba lagi.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
              //   window.location.reload();
              // });
              $('#delete-post-info').modal('toggle');
          }
      }
  }
  xmlHttp.open("POST", "/nexilis/logics/delete_product_only");
  xmlHttp.send(formData);
}

function deleteBothContent(post_id){

  $('#modal-delete-question').modal('hide');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);
  formData.append('ec_date', new Date().getTime());
  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          if (xmlHttp.responseText == "Success") {
              console.log(post_id + ' deleted');
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>Post and Product deleted.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Postingan dan Produk telah dihapus.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                  window.location.reload();
              });
              $('#delete-post-info').modal('toggle');
          } else {
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>An error occured while deleting content. Please refresh and try again.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Error saat menghapus konten. Silahkan muat ulang dan coba lagi.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
              //   window.location.reload();
              // });
              $('#delete-post-info').modal('toggle');
          }
      }
  }
  xmlHttp.open("POST", "/nexilis/logics/delete_both_content");
  xmlHttp.send(formData);
}
// END DELETE POST

// REMOVE POST
function removePost(post_id) {

  $('#just-post').addClass('d-none');
  $('#just-product').addClass('d-none');
  $('#both-pp').addClass('d-none');

  $('#modal-product').modal('hide');
  $('#modal-delete-question').modal('show');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);

  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

          var response = xmlHttp.response.split("|");
          console.log(response);

          var isPost = response[0];
          var isProduct = response[1];

          if (isPost == 1){
              $('#just-post').removeClass('d-none');
              $('#just-post').attr('onclick','removePostOnly(`'+post_id+'`)');
          }

          if (isProduct == 1){
              $('#just-product').removeClass('d-none');
              $('#just-product').attr('onclick','removeProductOnly(`'+post_id+'`)');
          }

          if (isPost == 1 && isProduct == 1){
              $('#both-pp').removeClass('d-none');
              $('#both-pp').attr('onclick','removeBothContent(`'+post_id+'`)');
          }

      }
  } 
  xmlHttp.open("POST", "/nexilis/logics/check_remove");
  xmlHttp.send(formData);

}

function removePostOnly(post_id){

  $('#modal-delete-question').modal('hide');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);
  formData.append('ec_date', new Date().getTime());
  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          if (xmlHttp.responseText == "Success") {
              console.log(post_id + ' deleted');
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>Post removed.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Postingan telah dihilangkan.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                  window.location.reload();
              });
              $('#delete-post-info').modal('toggle');
          } else {
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>An error occured while removing post. Please refresh and try again.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Error saat menghilangkan post. Silahkan muat ulang dan coba lagi.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
              //   window.location.reload();
              // });
              $('#delete-post-info').modal('toggle');
          }
      }
  }
  xmlHttp.open("GET", "/nexilis/logics/admin_remove_post?post_id=" + post_id);
  xmlHttp.send(formData);
}

function removeProductOnly(post_id){

  $('#modal-delete-question').modal('hide');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('product_code', post_id);

  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          if (xmlHttp.responseText == "Success") {
              console.log(post_id + ' deleted');
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>Product removed.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Produk telah dihapus.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                  window.location.reload();
              });
              $('#delete-post-info').modal('toggle');
          } else {
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>An error occured while removing product. Please refresh and try again.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Error saat menghilangkan produk. Silahkan muat ulang dan coba lagi.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
              //   window.location.reload();
              // });
              $('#delete-post-info').modal('toggle');
          }
      }
  }
  xmlHttp.open("POST", "/nexilis/logics/remove_product_only");
  xmlHttp.send(formData);
}

function removeBothContent(post_id){

  $('#modal-delete-question').modal('hide');

  var xmlHttp = new XMLHttpRequest();

  let formData = new FormData();
  formData.append('post_id', post_id);
  formData.append('ec_date', new Date().getTime());
  xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
          if (xmlHttp.responseText == "Success") {
              console.log(post_id + ' deleted');
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>Post and Product deleted.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Postingan dan Produk telah dihapus.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              $('#delete-post-info .modal-footer #delete-post-close').click(function () {
                  window.location.reload();
              });
              $('#delete-post-info').modal('toggle');
          } else {
              if (localStorage.lang == 0) {
                  $('#delete-post-info .modal-body').html('<h6>An error occured while deleting content. Please refresh and try again.</h6>');
                  $('#delete-post-close').text('Close');
              } else {
                  $('#delete-post-info .modal-body').html('<h6>Error saat menghapus konten. Silahkan muat ulang dan coba lagi.</h6>');
                  $('#delete-post-close').text('Tutup');
              }
              // $('#delete-post-info .modal-footer #delete-post-close').click(function() {
              //   window.location.reload();
              // });
              $('#delete-post-info').modal('toggle');
          }
      }
  }
  xmlHttp.open("POST", "/nexilis/logics/remove_both_content");
  xmlHttp.send(formData);
}
// END REMOVE POST

// function removePost(code) {
//   let xmlHttp = new XMLHttpRequest();
//   xmlHttp.onreadystatechange = function () {
//     if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
//       $('#modal-confirm-remove').modal('hide');

//       let path = window.location.href.split('?')[0] + '?f_pin=' + F_PIN;

//       $('#modal-post-removed #post-removed-close').click(function () {
//         window.location.href = path;
//       })

//       $('#modal-post-removed').on('hidden.bs.modal', function () {
//         window.location.href = path;
//       })

//       $('#modal-post-removed').modal('show');
//     }
//   }
//   xmlHttp.open("get", "/nexilis/logics/admin_remove_post?post_id=" + code);
//   xmlHttp.send();
// }

function confirmRemovePost(code) {
  $('#modal-confirm-remove #remove-post-accept').off('click');

  $('#modal-confirm-remove #remove-post-accept').click(function () {
    removePost(code);
  })

  $('#modal-confirm-remove').modal('show');
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

  let f_pin = "";

  if (window.Android) {
    try {
      f_pin = window.Android.getFPin();
    } catch (err) {
      // console.log(err);
    }
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  // dataFiltered = shuffleArray(dataFiltered);



  dataFiltered.slice(off, lim + 1).forEach((element, idx) => {
    // // // console.log(idx);
    // // // console.log(element.CODE);
    var size;
    if (r == 1) {
      size = ((idx % 12 == 0 || idx % 12 == 7) ? 2 : 1);
    } else {
      size = ((idx % 12 == 1 || idx % 12 == 6) ? 2 : 1);
    }

    // // // console.log(idx + ', ' + size);


    var imageDivs = '';
    var imageArray = productImageMap.get(element.CONTEXT_LINK);
    // var delay = Math.floor(Math.random() * (baseDelay)) + 5000;

    var merchantWebURL = element.CONTEXT_LINK;

    let thumb_content = '';

    let thumb_id = element.THUMB;
    let thumb_ext = ext(thumb_id).substr(1);

    let checkThumbUrl = (!element.THUMB.includes("http") || element.THUMB.substr(0, 4) != "http") && !element.THUMB.includes("/nexilis/images/");

    // let thumb_arr = thumb_id.split('|');
    let thumb_arr_ = productImageMap.get(element.LINK_ID);
    let thumb_arr = thumb_arr_.filter(th => th.length > 0);

    // // console.log(thumb_arr);

    let domain = 'https://newuniverse.io';
    if (element.F_PIN == '02d7c16d7a') {
      domain = '';
    }

    if (thumb_arr.length > 1) {
      thumb_arr.forEach((image, jIdx) => {
        if (image.length > 0) {
          var imgElem = '';
          var fileExt = ext_re.exec(image)[1].trim();
          if ($image_type_arr.includes(fileExt)) {
            imgElem = '<img class="content-image" src="' + domain + '/nexilis/images/' + image.trim() + '" loading="lazy" />'
          } else if ($video_type_arr.includes(fileExt)) {
            var isAutoplay = size == 2 ? 'autoplay' : '';
            imgElem = '<video preload="metadata" muted loop playsinline class="content-image" id="video-' + element.LINK_ID + '" src="' + domain + '/nexilis/images/' + image.trim() + '#t=5" type="video/' + fileExt + '"></video>';
          }
          if (imgElem) {
            // if (thumb_arr.length > 1) {
            if (jIdx == 0) {
              imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
            } else {
              imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
            }
          }
          //  else {
          //   imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
          // }
          // }
        }
      });
      contents = '<div id="store-carousel-' + element.LINK_ID + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
        //  : (''))
        '<div class="carousel-inner">' +
        imageDivs +
        '</div>' +
        '</div>';
    } else {
      var imgElem = '';
      var fileExt = ext_re.exec(thumb_arr[0])[1].trim();
      if ($image_type_arr.includes(fileExt)) {
        imgElem = '<img class="content-image" src="' + domain + '/nexilis/images/' + thumb_arr[0].trim() + '" loading="lazy"/>'
      } else if ($video_type_arr.includes(fileExt)) {
        var isAutoplay = size == 2 ? 'autoplay' : '';
        imgElem = '<video muted loop playsinline class="content-image" id="video-' + element.LINK_ID + '" src="' + domain + '/nexilis/images/' + thumb_arr[0].trim() + '#t=1" type="video/' + fileExt + '"></video>';
      }
      contents = imgElem;
    }

    // } 

    // if ((!element.THUMB.includes("http") || element.THUMB.substr(0, 4) != "http") && !element.THUMB.includes("/nexilis/images/")) {
    //   element.THUMB = '/nexilis/images/' + element.THUMB;
    // }

    // let linkProfile = element.HAS_SHOP > 0 ? "tab3-profile.php?store_id=" + element.F_PIN + "&f_pin=" + f_pin : "tab3-profile-user.php?store_id=" + element.F_PIN + "&f_pin=" + f_pin;
    let linkProfile = "tab3-profile.php?store_id=" + element.F_PIN + "&f_pin=" + f_pin;
    var isBig = size == 2 ? 'big-grid' : 'small-grid';
    var computed =
      '<a onclick="showProductModal(\'' + element.LINK_ID + '\', 1)" id="' + element.LINK_ID + '">' +
      '<div class="inner ' + isBig + '">' +
      contents +
      '<div class="viewer-count" id="visitor-' + element.LINK_ID + '">' +
      '<img src="/nexilis/assets/img/jim_likes_red.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
      '<span class="visitor-amt" style="font-size:11px; color:white">' +
      new Intl.NumberFormat('en-US', {
        maximumFractionDigits: 1,
        notation: "compact"
      }).format(element.TOTAL_LIKES) +
      '</span>' +
      '<img src="/nexilis/assets/img/jim_comments_blue.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
      '<span class="follower-amt" style="font-size:11px; color:white">' +
      new Intl.NumberFormat('en-US', {
        maximumFractionDigits: 1,
        notation: "compact"
      }).format(element.TOTAL_COMMENTS) +
      '</span>' +
      '</div>' +
      '</div>' +
      '</a>';
    if (!gridCheck(gridElements, element.LINK_ID)) {
      gridElements.push({
        id: element.LINK_ID,
        minW: size,
        minH: size,
        maxW: size,
        maxH: size,
        content: computed
      });
    } else {
      // // console.log(element.THUMB);
    }
    // if (imageArray.length > 1) {
    //   carouselList.push('#store-carousel-' + element.CODE + '');
    // }
    // }
    busy = false;
  });


  // grid_stack.batchUpdate();

  // // console.log(gridElements);

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
  addToCartModal();
  // attachLongPress();
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  carouselIntervalId = setInterval(function () {
    carouselNext();
  }, 3000);

  // if (document.getElementById('gif-container') != null) {
  //   getGIFs();
  // }
  $('#loading').addClass('d-none');
};

function fillGridWidgets(grid, sort_by, lim, off) {
  let start = off;
  let end = off + lim;

  // // console.log('next page');

  var baseDelay = 5000; //(Math.max(5, dataFiltered.length) * 1000) / 2;
  // console.table(dataFiltered);
  var $image_type_arr = ["jpg", "jpeg", "png", "webp"];
  var $video_type_arr = ["mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg'];
  var $shop_blacklist = ["17b0ae770cd"]; //isi manual
  var ext_re = /(?:\.([^.]+))?$/;

  let f_pin = "";
  if (window.Android) {
    try {
      f_pin = window.Android.getFPin();
    } catch (err) {
      // // // console.log(err);
    }
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }

  let batch = dataFiltered.slice(start, end);

  batch.forEach((element, idx) => {
    if ($shop_blacklist.includes(element.CODE)) {
      return;
    }

    var size;
    if (r == 0) {
      size = ((idx % 12 == 0 || idx % 12 == 7) ? 2 : 1);
    } else {
      size = ((idx % 12 == 1 || idx % 12 == 6) ? 2 : 1);
    }

    // // // console.log(idx + ', '  + size);

    var imageDivs = '';
    var imageArray = productImageMap.get(element.CONTEXT_LINK);
    // var delay = Math.floor(Math.random() * (baseDelay)) + 5000;

    var merchantWebURL = element.CONTEXT_LINK;

    let thumb_content = '';

    let thumb_id = element.THUMB;
    // // console.log(thumb_id);
    let thumb_ext = ext(thumb_id).substr(1);
    // // console.log(thumb_ext);

    let checkThumbUrl = (!element.THUMB.includes("http") || element.THUMB.substr(0, 4) != "http") && !element.THUMB.includes("/nexilis/images/");



    let th_arr = thumb_id.split('|');

    let thumb_arr = th_arr.filter(th => th.length > 0);

    // console.log(thumb_arr);

    let domain = 'https://newuniverse.io';
    if (element.F_PIN == '02d7c16d7a') {
      domain = '';
    }

    if (thumb_arr.length > 1) {
      thumb_arr.forEach((image, jIdx) => {
        if (image.length > 0) {
          var imgElem = '';
          var fileExt = ext_re.exec(image)[1].trim();
          if ($image_type_arr.includes(fileExt)) {
            imgElem = '<img class="content-image" src="' + domain + '/nexilis/images/' + image.trim() + '" loading="lazy"/>'
          } else if ($video_type_arr.includes(fileExt)) {
            var isAutoplay = size == 2 ? 'autoplay' : '';
            imgElem = '<video preload="metadata" muted loop playsinline class="content-image" id="video-' + element.LINK_ID + '" src="' + domain + '/nexilis/images/' + image.trim() + '#t=5" type="video/' + fileExt + '"></video>';
          }
          if (imgElem) {
            if (jIdx == 0) {
              imageDivs = imageDivs + '<div class="carousel-item active">' + imgElem + '</div>';
            } else {
              imageDivs = imageDivs + '<div class="carousel-item">' + imgElem + '</div>';
            }
          }

        }
      });
      contents = '<div id="store-carousel-' + element.LINK_ID + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
        //  : (''))
        '<div class="carousel-inner">' +
        imageDivs +
        '</div>' +
        '</div>';
    } else {
      var imgElem = '';
      var fileExt = ext_re.exec(thumb_arr[0])[1].trim();
      if ($image_type_arr.includes(fileExt)) {
        imgElem = '<img class="content-image" src="' + domain + '/nexilis/images/' + thumb_arr[0].trim() + '" loading="lazy"/>'
      } else if ($video_type_arr.includes(fileExt)) {
        var isAutoplay = size == 2 ? 'autoplay' : '';
        imgElem = '<video muted loop playsinline class="content-image" id="video-' + element.LINK_ID + '" src="' + domain + '/nexilis/images/' + thumb_arr[0].trim() + '#t=1" type="video/' + fileExt + '"></video>';
      }
      contents = imgElem;
    }

    // } 

    // if ((!element.THUMB.includes("http") || element.THUMB.substr(0, 4) != "http") && !element.THUMB.includes("/nexilis/images/")) {
    //   element.THUMB = '/nexilis/images/' + element.THUMB;
    // }

    // let linkProfile = element.HAS_SHOP > 0 ? "tab3-profile.php?store_id=" + element.F_PIN + "&f_pin=" + f_pin : "tab3-profile-user.php?store_id=" + element.F_PIN + "&f_pin=" + f_pin;
    let linkProfile = "tab3-profile.php?store_id=" + element.F_PIN + "&f_pin=" + f_pin;
    var isBig = size == 2 ? 'big-grid' : 'small-grid';
    var computed =
      '<a onclick="showProductModal(\'' + element.LINK_ID + '\', 1)" id="' + element.LINK_ID + '">' +
      '<div class="inner ' + isBig + '">' +
      contents +
      // '<div id="store-carousel-' + element.LINK_ID + '" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">' +
      // '<div class="carousel-inner">' +
      // imageDivs +
      // '</div>' +
      // '</div>' +
      '<div class="viewer-count" id="visitor-' + element.LINK_ID + '">' +
      '<img src="/nexilis/assets/img/jim_likes_red.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
      '<span class="visitor-amt" style="font-size:11px; color:white">' +
      new Intl.NumberFormat('en-US', {
        maximumFractionDigits: 1,
        notation: "compact"
      }).format(element.TOTAL_LIKES) +
      '</span>' +
      '<img src="/nexilis/assets/img/jim_comments_blue.png" style="width:11px; height:11px; margin:auto .2rem;"/>' +
      '<span class="follower-amt" style="font-size:11px; color:white">' +
      new Intl.NumberFormat('en-US', {
        maximumFractionDigits: 1,
        notation: "compact"
      }).format(element.TOTAL_COMMENTS) +
      '</span>' +
      '</div>' +
      '</div>' +
      '</a>';
    if (!gridCheck(gridElements, element.LINK_ID)) {
      gridElements.push({
        id: element.LINK_ID,
        minW: size,
        minH: size,
        maxW: size,
        maxH: size,
        content: computed
      });
      grid_stack.addWidget({
        id: element.LINK_ID,
        minW: size,
        minH: size,
        maxW: size,
        maxH: size,
        content: computed
      });
    }
    // if (imageArray.length > 1) {
    //   carouselList.push('#store-carousel-' + element.CODE + '');
    // }
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
  addToCartModal();
  // attachLongPress();
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


function pauseAllVideo() {
  $('.grid-stack-item .carousel-item video, .grid-stack-item .carousel-item.active video').each(function () {
    let isPaused = $(this).get(0).paused;
    $(this).off("stop pause ended");
    // $(this).on("stop pause ended", function (e) {
    //   $(this).closest(".carousel").carousel();
    // });
    if (!isPaused) {
      $(this).get(0).pause();
    }
  });
}

function playAllVideo() {
  $('.grid-stack-item .carousel-item video, .grid-stack-item .carousel-item.active video').each(function () {
    // pause carousel when video is playing
    // $(this).off("play");
    // $(this).on("play", function (e) {
    //   $(this).closest(".carousel").carousel("pause");
    // })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  });
}

let popUpModal = document.getElementById('modal-product');

popUpModal.addEventListener('show.bs.modal', function (e) {
  // console.log("start", startTouchTime)
  // console.log("current", currentTouchTime);
  // let duration = currentTouchTime - startTouchTime;
  // console.log("duration", duration)
  // if (duration < 1000) {
  //   return e.preventDefault();
  // }
  pauseAllVideo();
})

function toggleVideoMute(code) {
  // console.log(code);
  let videoWrap = document.getElementById(code);
  let videoElement = videoWrap.querySelector('video');
  // // console.log(videoElement);

  // console.log('#' + code + ' .video-sound img');
  let muteIcon = document.querySelector('#' + code + ' .video-sound img');

  if (videoElement.muted) {
    videoElement.muted = false;
    muteIcon.src = "../assets/img/video_unmute.png";
  } else {
    videoElement.muted = true;
    muteIcon.src = "../assets/img/video_mute.png";
  }

  // console.log(code + ' ' + videoElement.muted);
}

popUpModal.addEventListener('shown.bs.modal', function () {
  // pauseAllVideo();

  // $('.carousel-item.active .video-wrap video, .video-wrap video').each(function(e) {
  //   const isVideoPlaying = video => !!(video.currentTime > 0 && !video.paused && !video.ended && video.readyState > 2);
  // })
  console.log("too soon", tooSoon)
  if (tooSoon) {
    closeModal();
  } else {

    let videoWrap = document.querySelectorAll('.carousel-item.active .video-wrap, .video-wrap');

    videoWrap.forEach(function (wrap) {
      let vid = wrap.querySelector('video');
      const isVideoPlayingMute = vid => !!(vid.currentTime > 0 && !vid.paused && !vid.ended && vid.readyState > 2 && vid.muted);
      if (isVideoPlayingMute) toggleVideoMute(wrap.id);
    })
  }
  
  tooSoon = false;
})

popUpModal.addEventListener('hide.bs.modal', function () {
  let videoPopUp = document.querySelector('#modal-product .modal-body video');
  if (videoPopUp) {
    videoPopUp.pause();
    videoPopUp.removeAttribute('src');
    videoPopUp.load();
  }
});

popUpModal.addEventListener('hidden.bs.modal', function () {
  // console.log('hidden');
  $('#modal-product .modal-body').html('');
  if (window.Android) {
    window.Android.setIsProductModalOpen(false);
  }
  if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
    window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
      param1: false
    });
  }
  modalIsOpen = false;
  checkVideoViewport();
})


var onlongtouch;
var timer;
var touchduration = 750;
let startTouchTime = 0;
let currentTouchTime = 0;
let tooSoon = false;

// function attachLongPress() {
//   let gridItem = Array.from(document.querySelectorAll('div.grid-stack-item-content a'));
//   gridItem.forEach(function (element) {
//     element.addEventListener('contextmenu', (e) => {
//       e.preventDefault();
//       e.stopPropagation();
//     })
//     let dragging = false;
    
//     element.addEventListener("touchstart", function (event) {
//       event.stopPropagation();
//       startTouchTime = new Date().getTime();
//       if (!timer) {
//         timer = setTimeout(function () {
//           console.log('touch', timer)
//           if (!dragging) {
//             showProductModal(element.id);
//             // closeModal();
//           }
//         }, touchduration);
//       }
//     }, false);
//     element.addEventListener('touchmove', function (evt) {
//       dragging = true;
//       closeModal();
//       startTouchTime = 0;
//       currentTouchTime = 0;
//       clearTimeout(timer);
//       timer = null;
//     })
//     element.addEventListener("touchend", function () {
//       dragging = false;
//       console.log('timer', timer);
//       currentTouchTime = new Date().getTime();
//       console.log("start", startTouchTime)
//       console.log("current", currentTouchTime);
//       let duration = currentTouchTime - startTouchTime;
//       console.log("duration", duration)
//       if (timer) {
//         closeModal();
//         startTouchTime = 0;
//         currentTouchTime = 0;
//       } 
//       if (duration < 1500) {
//         tooSoon = true;
//       }
//       clearTimeout(timer);
//       timer = null;
//     }, false);
//   });
// }

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
      // // // // console.log(resp);

      if (resp.length > 0) {
        resp.forEach(abc => {
          let storeIndex = dataFiltered.findIndex(dt => dt.CODE == abc.STORE_CODE);
          dataFiltered[storeIndex].REWARD_PTS = abc.AMOUNT;
          // // // // console.log(storeIndex);
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

let countVideoPlaying = 0;
var visibleCarousel = new Set();

function checkVideoViewport() {

  let videoWrapElements = document.querySelectorAll('.big-grid');
  let videoWrapArr = [].slice.call(videoWrapElements);
  // let carouselElements = document.querySelectorAll('.big-grid .carousel');
  // let carouselArr = [].slice.call(carouselElements);

  // let allElementsArr = videoWrapArr.concat(carouselArr);
  let allElementsArr = videoWrapArr.reverse();
  let observer = new IntersectionObserver((entries) => {

    entries.forEach(entry => {
      if (entry.intersectionRatio >= 1 && $('#modal-product').not('.show') && countVideoPlaying === 0) {
        playElement(entry.target, entry.intersectionRatio);
      } else if (entry.intersectionRatio < 1) {
        pauseElement(entry.target, entry.intersectionRatio);
      }
    });
  }, {
    threshold: 1
  });

  function playElement(el, ir) {
    let video = el.querySelector('video');
    let carousel = el.querySelector('.carousel');
    if (video != null && video.paused) {
      video.play();
      // console.log(video.id, 'play');
      countVideoPlaying = 1;
    }
  }

  function pauseElement(el, ir) {
    let video = el.querySelector('video');
    let carousel = el.querySelector('.carousel');
    // // console.log('video', video);
    // // console.log('carousel', carousel);
    if (video != null && !video.paused) {
      video.pause();
      countVideoPlaying = 0;
    }
    // else if (carousel != null && visibleCarousel.has(carousel.id)) {
    //   visibleCarousel.delete(carousel.id);
    //   $('#' + carousel.id).carousel('pause');
    // }

  }

  allElementsArr.forEach((elements) => {
    observer.observe(elements);
  });
}

function checkVideoCarousel() {
  // play video when active in carousel
  // $(".carousel").on("slid.bs.carousel", function (e) {
  //   if (palioBrowser && isChrome) {
  //     if ($(this).find("video").length) {
  //       if ($(this).find(".carousel-item").hasClass("active")) {
  //         $(this).find("video").get(0).play();
  //       } else {
  //         $(this).find("video").get(0).pause();
  //       }
  //     }
  //   }
  // });
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
    // updateStoreViewer();
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
  if (localStorage.getItem("store_data") !== null) {
    prefetchStores();
  }
  // fillFilter();
  // getFollowSetting();
  getShowLinklessSetting();
  getLikedProducts();
  getCommentedProducts();
  // fetchStores();
  // updateStoreViewer();
  $('#toggle-filter').click(function (e) {
    // $('#modal-categoryFilter').modal('toggle');
    e.stopPropagation();
    window.scrollTo({
      top: 0,
      behavior: 'instant',
    });
    showHideFilter($('#category-checkbox').hasClass('d-none'))
    // console.log(isSearchHidden);
    // navbarHeight = $('#header-layout').outerHeight();
    $('#header-layout').css('top', '0px');
  })
});

var storeMap = new Map();

function prefetchStores() {
  // console.log('PREFETCH');
  data = JSON.parse(localStorage.getItem("store_data"));
  // filterStoreData(filter, search, false);
  filterStoreData(defaultCategory, "", STORE_ID, otherFilter.verified, otherFilter.friends, otherFilter.others, false);
  dataFiltered.forEach(storeEntry => {
    storeMap.set(storeEntry.CODE, JSON.stringify(storeEntry));
  });
  dataFiltered = [];
  dataFiltered = dataFiltered.concat(data);

  // var productData = JSON.parse(localStorage.getItem("store_pics_data"));
  // productData.forEach(storeEntry => {
  //   $thumb_ids = storeEntry.THUMB_ID.split("|");
  //   $thumb_ids.forEach(function (thumbid, index) {
  //     if (!thumbid.startsWith("http")) {
  //       var root = 'http://' + location.host;
  //       var profPic = "";

  //       if (thumbid == null || thumbid == "") {
  //         profPic = "/nexilis/assets/img/palio.png";
  //       } else {
  //         // profpic = root + ":2809/file/image/" + storeEntry.THUMB_ID;
  //         profPic = "/nexilis/images/" + thumbid;
  //       }
  //       $thumb_ids[index] = profPic;
  //     }
  //   });
  //   if (!productImageMap.has(storeEntry.STORE_CODE)) {
  //     productImageMap.set(storeEntry.STORE_CODE, $thumb_ids);
  //   } else if (productImageMap.get(storeEntry.STORE_CODE).length < 3) {
  //     productImageMap.set(storeEntry.STORE_CODE, productImageMap.get(storeEntry.STORE_CODE).concat($thumb_ids));
  //   }
  // });
  // fillGridStack('#content-grid', currentSort, limit, offset);
  dataFiltered.forEach(storeEntry => {

    $thumb_ids = storeEntry.THUMB;

    if (productImageCountMap.has(storeEntry.LINK_ID)) {
      return;
    } else {
      let thumb_arr = $thumb_ids.split('|');
      let new_arr = [];
      thumb_arr.forEach((tid, idx) => {
        if (!tid.startsWith("http")) {
          var root = 'http://' + location.host;

          if (tid != null && tid != "") {
            let profPic = tid;
            // $thumb_ids[index] = profPic;
            new_arr.push(profPic);
          }
        }
      })
      productImageMap.set(storeEntry.LINK_ID, new_arr);
    }

    if (!productImageCountMap.has(storeEntry.LINK_ID)) {
      productImageCountMap.set(storeEntry.LINK_ID, 1);
    } else {
      productImageCountMap.set(storeEntry.LINK_ID, productImageCountMap.get(storeEntry.LINK_ID) + 1);
    }

    // if (storeEntry.ID === undefined) {
    //   // addLinks.push(storeEntry);
    // }
  });
  // fillGridStack('#content-grid', currentSort, limit, offset);
}

function getFollowSetting() {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      dataFollowSetting = JSON.parse(xhr.responseText);

      // // // // console.log(data);
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

      // // // // console.log(data);
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

function fetchLinks() {
  // console.log('FETCH');
  let f_pin = "";
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  var xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = async function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      data = JSON.parse(xmlHttp.responseText);
      // // console.log('data', data);
      if (data.length == 0) {
        // loadClient();
      } else {
        let store_id = new URLSearchParams(window.location.search).get('store_id');
        if (store_id != null) {
          STORE_ID = store_id.toString();
        };

        // filterStoreData("", "", STORE_ID, false);
        filterStoreData(defaultCategory, query, STORE_ID, otherFilter.verified, otherFilter.friends, otherFilter.others, false);

      }

      localStorage.setItem("store_data", xmlHttp.responseText);

      fetchProductPics(data);

    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_merchandise?f_pin=" + f_pin);

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

function fetchProductPics(arr) {
  // // console.log("array");

  arr.forEach(storeEntry => {

    $thumb_ids = storeEntry.THUMB;

    if (productImageCountMap.has(storeEntry.LINK_ID)) {
      return;
    } else {
      let thumb_arr = $thumb_ids.split('|');
      let new_arr = [];
      thumb_arr.forEach((tid, idx) => {
        if (!tid.startsWith("http")) {
          var root = 'http://' + location.host;

          if (tid != null && tid != "") {
            let profPic = tid;
            // $thumb_ids[index] = profPic;
            new_arr.push(profPic);
          }
        }
      })
      productImageMap.set(storeEntry.LINK_ID, new_arr);
    }

    if (!productImageCountMap.has(storeEntry.LINK_ID)) {
      productImageCountMap.set(storeEntry.LINK_ID, 1);
    } else {
      productImageCountMap.set(storeEntry.LINK_ID, productImageCountMap.get(storeEntry.LINK_ID) + 1);
    }

    // if (storeEntry.ID === undefined) {
    //   // addLinks.push(storeEntry);
    // }
  });
  fillGridStack('#content-grid', currentSort, limit, offset);
}

var hiddenStores = [];

function filterStoreData($filterCategory, $filterSearch, $storeId, verified, friends, others, isSearching) {
  // console.log('FILTERSTORE')
  if (window.Android) {
    try {
      hiddenStores = window.Android.getHiddenStores().split(",");
    } catch (error) {

    }
  }
  // console.log('FILTERSTORE', $filterCategory);
  dataFiltered = [];
  data.forEach(storeEntry => {
    // if (showLinkless == 2 || (showLinkless == 1 && !storeEntry.LINK) || (showLinkless == 0 && storeEntry.LINK)) {
    var isMatchCategory = false;

    if ($filterCategory) {

      // var categoryArray = $filterCategory;
      // isMatchCategory = storeEntry.CATEGORY == $filterCategory;
      var categoryArray = $filterCategory.split("-");

      isMatchCategory = categoryArray.indexOf(storeEntry.CATEGORY + "") > -1;
      // // console.log('fc', categoryArray.indexOf(storeEntry.CATEGORY + ""));
    } else {
      isMatchCategory = true;
    }

    var isMatchStoreId = false;
    if ($storeId) {
      isMatchStoreId = storeEntry.F_PIN == $storeId;
    } else {
      isMatchStoreId = true;
    }

    var isMatchSearch = false;
    // // console.log("filterSearch", $filterSearch);
    if ($filterSearch) {
      isMatchSearch = isMatchSearch || storeEntry.TITLE.toLowerCase().includes($filterSearch.toLowerCase());
      isMatchSearch = isMatchSearch || storeEntry.DESC.toLowerCase().includes($filterSearch.toLowerCase());
      // isMatchSearch = isMatchSearch || storeEntry.CONTEXT_LINK.toLowerCase().includes($filterSearch.toLowerCase());
      isMatchSearch = isMatchSearch || storeEntry.USERNAME.toLowerCase().includes($filterSearch.toLowerCase());
    } else {
      isMatchSearch = true;
    }

    var isVerified = true;
    if (parseInt(verified) == 1) {
      isVerified = storeEntry.OFFICIAL_ACCOUNT == 2;
    }

    var isFriends = true;
    if (parseInt(friends) == 1) {
      isFriends = storeEntry.IS_FRIEND == 1;
    }

    var isOthers = true;
    if (parseInt(others) == 1) {
      isOthers = storeEntry.OFFICIAL_ACCOUNT != 2 && storeEntry.IS_FRIEND == 0;
    }

    var uncheckBoth = isMatchCategory && isMatchSearch && isMatchStoreId;
    var checkVerified = uncheckBoth && isVerified;
    var checkFriends = uncheckBoth && isFriends;
    var checkOthers = uncheckBoth && isOthers;
    var checkBoth = checkVerified || checkFriends || checkOthers;

    // if (isMatchCategory && isMatchSearch && isMatchStoreId && (isVerified || isFriends)) {
    //   // // console.log('here');
    //   dataFiltered.push(storeEntry);
    // }
    if (parseInt(verified) == 1 && parseInt(friends) == 1 && parseInt(others) == 1) {
      if (checkBoth) {
        dataFiltered.push(storeEntry);
      }
    } else if (parseInt(verified) != 1 && parseInt(friends) == 1 && parseInt(others) != 1) {
      if (checkFriends) {
        dataFiltered.push(storeEntry);
      }
    } else if (parseInt(verified) != 1 && parseInt(friends) == 1 && parseInt(others) == 1) {
      if (checkFriends) {
        dataFiltered.push(storeEntry);
      }
      if (checkOthers) {
        dataFiltered.push(storeEntry);
      }
    } else if (parseInt(verified) == 1 && parseInt(friends) != 1 && parseInt(others) != 1) {
      if (checkVerified) {
        dataFiltered.push(storeEntry);
      }
    } else if (parseInt(verified) == 1 && parseInt(friends) != 1 && parseInt(others) == 1) {
      if (checkVerified) {
        dataFiltered.push(storeEntry);
      }
      if (checkOthers) {
        dataFiltered.push(storeEntry);
      }
    } else if (parseInt(verified) != 1 && parseInt(friends) != 1 && parseInt(others) == 1) {
      if (checkOthers) {
        dataFiltered.push(storeEntry);
      }
    } else if (parseInt(verified) == 1 && parseInt(friends) == 1 && parseInt(others) != 1) {
      if (checkVerified) {
        dataFiltered.push(storeEntry);
      }
      if (checkFriends) {
        dataFiltered.push(storeEntry);
      }
    } else {
      if (uncheckBoth) {
        dataFiltered.push(storeEntry);
      }
    }
  });

  console.log(dataFiltered)
  if (isSearching) {

  }
  // fetchProductPics(dataFiltered, isSearching);
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


function headerOut() {
  $('#category-checkbox').addClass('d-none');
  navbarHeight = $('#header-layout').outerHeight();
  $('#header-layout').css('top', '0px');
  isSearchHidden = true;
};

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

setInterval(function () {
  if (didScroll) {
    hasScrolled();
    didScroll = false;
  }
}, 10);


var finishScrollPos = 0;

$(function () {
  $(window).scroll(function () {

    finishScrollPos = $(document).scrollTop();
    if (!isSearchHidden && finishScrollPos != 0) {
      $('#category-checkbox').addClass('d-none')
      $('#toggle-filter').attr('src', '../assets/img/filter-icon-gray.png');
    }
    scrollFunction();
    didScroll = true;
  });
});

function scrollFunction() {
  if ($(document).scrollTop() > navbarHeight) {
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
  activeFilter = '';
  query = '';
  $('#query').val('');
  $('#delete-query').addClass('d-none')
  otherFilter.verified = 0;
  otherFilter.friends = 0;
  otherFilter.others = 0;
  $('#filter-verified').prop('checked', false);
  $('#filter-friends').prop('checked', false);
  $('#filter-others').prop('checked', false);
  // $('#root-category input:checkbox').each(function() {
  //     $(this).prop('checked', false);
  // })
  // showHideFilter(false);
  if (defaultCategory != "") {
    let defCat = defaultCategory.split('-');
    let visCat = visibleCategory.split('-');
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
        $("#root-category ul li").each(function () {
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
}

function changeBg(category) {
  let imgBg = document.querySelector('.demo-bg');
  // // // console.log(category);

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

let activeFilter = new URLSearchParams(window.location.search).get('filter') ? new URLSearchParams(window.location.search).get('filter') : "";

// if (localStorage.getItem('filter-cat')){
//   activeFilter = localStorage.getItem('filter-cat');
//   // searchFilter();
//   defaultCategory = activeFilter;
// }

let query = $('#query').val();

function horizontalScrollPos(selected) {
  let selectedPos = 0;
  try {
    selectedPos = document.querySelector('.has-story#store-' + selected).offsetLeft;
  } catch (e) {

  }

  $('#story-container ul').animate({
    scrollLeft: selectedPos
  })
}

function highlightStore() {

  if (STORE_ID != "") {
    selected_id = "#store-" + STORE_ID;
    // todo: kalo store ga ada
  } else {
    selected_id = '#all-store';
  }
  $('.has-story').removeClass('selected');
  $(selected_id).addClass("selected");
  horizontalScrollPos(STORE_ID);
}

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

function searchFilter() {
  // $('#loading').removeClass('d-none');
  $('body').css('visibility', 'hidden');
  setTimeout(function () {
    dataFiltered = [];
    // // // // console.log("here");
    var dest = "";
    var horizontal = 'timeline_story_container_grid.php';
    query = $('#query').val();
    // console.log(activeFilter);
    var filter = activeFilter;
    // if (STORE_ID != "") {
    //   dest = dest + "?store_id=" + STORE_ID;
    // }
    if (window.Android) {
      var f_pin = window.Android.getFPin();
      // var f_pin = new URLSearchParams(window.location.search).get('f_pin');
      if (f_pin) {
        dest = dest + "?f_pin=" + f_pin;
      }
    } else {
      var f_pin = new URLSearchParams(window.location.search).get('f_pin');
      if (f_pin) {
        dest = dest + "?f_pin=" + f_pin;
      }
    }
    if (STORE_ID != "") {
      dest = dest + "&store_id=" + STORE_ID;
    }
    if (query != "") {
      let urlEncodedQuery = encodeURIComponent(query);
      dest = dest + "&query=" + urlEncodedQuery;
    }
    if (filter != "") {
      let urlEncodedFilter = encodeURIComponent(filter);
      dest = dest + "&filter=" + urlEncodedFilter;
    }
    // check verified
    dest = dest + '&verified=' + otherFilter.verified;

    // check friends
    dest = dest + '&friends=' + otherFilter.friends;

    // check others
    dest = dest + '&others=' + otherFilter.others;

    horizontal = horizontal + dest;
    // window.location.href = dest;
    // if (!dest) dest = "?"
    history.pushState({
      'search': query,
      'filter': filter
    }, "Palio Browser", dest);
    offset = 0;
    r = Math.floor(Math.random() * (2 - 1 + 1)) + 1;
    // console.log(horizontal);
    $('#story-container').html('');
    $.get(horizontal, function (data) {
      $('#story-container').html(data);
      // highlightStore();
      hasStoreId();
      onClickHasStory();
    });
    filterStoreData(filter, query, STORE_ID, otherFilter.verified, otherFilter.friends, otherFilter.others, true);
    fillGridStack('#content-grid', currentSort, limit, offset);
    $('body').css('visibility', 'visible');
  }, 500);
}

// function selectCategoryFilter() {
//   $('#category-tabs .nav .nav-item .nav-link').each(function () {
//     $(this).click(function () {
//       // busy = true;
//       // STORE_ID = "";
//       activeFilter = $(this).attr('id').split('-')[1];
//       if (activeFilter == "all") {
//         activeFilter = "";
//       }
//       $(this).addClass('active');
//       $('#category-tabs .nav-link:not(#categoryFilter-' + activeFilter + ')').removeClass('active');
//       $('#content-grid').html('');

//       searchFilter();
//     })
//   });
// }

function selectCategoryFilter() {
  // busy = true;
  // STORE_ID = "";
  // activeFilter = $(this).attr('id').split('-')[1];
  // if (activeFilter == "all") {
  //   activeFilter = "";
  // }
  // $(this).addClass('active');
  // $('#category-tabs .nav-link:not(#categoryFilter-' + activeFilter + ')').removeClass('active');
  let selected = [];
  $('#root-category input:checked').each(function () {
    selected.push($(this).attr('id'));
  });

  // console.log('checked', selected);

  if (selected.length > 0) {
    activeFilter = selected.join('-');
  } else {
    activeFilter = '';
    let allcheck = [];
    let defCat = defaultCategory.split("-");
    if (BE_ID == "347") {
      $('#root-category input:checkbox').each(function () {
        if (defCat.includes($(this).attr('id'))) {
          $(this).prop('checked', true);
          allcheck.push($(this).attr('id'));
        }
      })
    } else {
      $('#root-category input:checkbox').each(function () {
        $(this).prop('checked', true);
        allcheck.push($(this).attr('id'));
      })
    }
    activeFilter = allcheck.join('-');
  }

  $('#other-category li input').each(function () {
    otherFilter.verified = $('#filter-verified').is(':checked') ? 1 : 0;
    otherFilter.friends = $('#filter-friends').is(':checked') ? 1 : 0;
    otherFilter.others = $('#filter-others').is(':checked') ? 1 : 0;
  });

  // console.log('selectcategoryfilter', activeFilter);
  searchFilter();
}

// function activeCategoryTab() {
//   let urlSearchParams = new URLSearchParams(window.location.search);
//   let activeParam = urlSearchParams.get('filter');
//   activeFilter = activeParam;

//   if (activeParam == null) {
//     activeParam = 1;
//     activeFilter = 0;
//   }

//   // // console.log("active filter", activeFilter);

//   $('#categoryFilter-' + activeParam).addClass('active');
//   $('#category-tabs .nav-link:not(#categoryFilter-' + activeParam + ')').removeClass('active');
// }

function activeCategoryTab() {
  let urlSearchParams = new URLSearchParams(window.location.search);
  let activeParam = urlSearchParams.get('filter');
  // console.log(activeParam);

  $('#filter-friends').prop('checked', otherFilter.friends == 1);
  $('#filter-verified').prop('checked', otherFilter.verified == 1);
  $('#filter-others').prop('checked', otherFilter.others == 1);
  if (activeParam == null) {
    activeParam = "";
    activeFilter = activeParam;
    // $('#root-category input:checkbox').each(function() {
    //   $(this).prop('checked',true);
    // })
  } else {
    activeFilter = activeParam;
    // defaultCategory = activeFilter;
    let filters = activeParam.split('-');

    filters.forEach(fi => {
      $('#root-category input#' + fi).prop('checked', true);
    })
  }
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

function pauseAll() {
  closeModal();
  resetFilter();
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
  // STORE_ID = '';
  // activeFilter = '';
  // query = '';
  // searchFilter();
}

function resumeAll() {
  checkVideoViewport();
  checkVideoCarousel();
  checkCarousel();
  // updateCounter();
  // fetchNotifCount();
  if (carouselIntervalId) {
    clearInterval(carouselIntervalId);
  }
  carouselIntervalId = setInterval(function () {
    carouselNext();
  }, 3000);
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

  let srcQuery = new URLSearchParams(window.location.search).get('query');
  // console.log('quer', srcQuery);
  // console.log(document.getElementById('delete-query').classList);

  if ($('#searchFilterForm-a input#query').val() != '' || (srcQuery != null && srcQuery != '')) {
    $('#delete-query').removeClass('d-none');
    document.getElementById('delete-query').classList.remove('d-none');
  }

  $("#delete-query").click(function () {
    $('#searchFilterForm-a input#query').val('');
    $('#delete-query').addClass('d-none');
    searchFilter();
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

  // console.log('total', totalNodes);

  for (let i = 0; i < totalNodes; i++) {
    let currentId = nodes[i].gridstackNode.id ? nodes[i].gridstackNode.id : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }

  // let dupes = Object.keys(ids).find(key => object[key] === value);;

  console.table(ids);
}

function checkDupesDataFiltered() {
  let nodes = dataFiltered;
  let ids = {};
  let totalNodes = nodes.length;

  for (let i = 0; i < totalNodes; i++) {
    let currentId = nodes[i].LINK_ID ? nodes[i].LINK_ID : "undefined";
    if (isNaN(ids[currentId])) {
      ids[currentId] = 0;
    }
    ids[currentId]++;
  }

  // let dupes = Object.keys(ids).find(key => object[key] === value);;

  console.table(ids);
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
      // if (currentClass && !currentClass.includes(",")) {ffetchLins
      // searchFilter();
    } else if (this.id == "store-nexilis") {
      STORE_ID = this.id;
    } else {
      let prev_STORE_ID = STORE_ID;
      STORE_ID = this.id.split("-")[1];
      // buttonTheme(STORE_ID);
      // fetchProductCount(STORE_ID, prev_STORE_ID);
    }
    console.log(activeFilter)
    searchFilter();
  });
}

$(function () {
  gapi.load("client");
  //updateCounter();

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

function getShopFromCrawler(result, existingArr, isSearching) {
  // console.log(result);

  let resultArr = JSON.parse(result);

  resultArr.forEach(res => {
    let isExist = existingArr.some(el => el.LINK_ID == res.LINK_ID);
    if (isExist == false) {
      res.TOTAL_LIKES = 0;
      res.TOTAL_COMMENTS = 0;
      res.DESC = res.TITLE;
      res.CATEGORY = "5";
      existingArr.push(res);
    }
  });
  // console.log(existingArr);
  filterStoreData(activeFilter, isSearching ? url : search, isSearching);
}

function pullShopFromCrawler(url, arr, isSearching) {
  // console.log(url);
  let words = url.split(" ");

  let terms = "";
  if (words.length > 1) {
    terms = words.join("+");
  } else {
    terms = words[0];
  }

  let searchUrl = "https://www.google.com/search?tbm=shop&q=" + terms;

  if (window.Android) {
    window.Android.pullShopFromCrawler(searchUrl, isSearching);
  } else {

    // console.log(searchUrl);

    $.get(searchUrl, function (data) {
      let doc = new DOMParser().parseFromString(data, "text/html");
      let elements = doc.getElementsByClassName("shntl sh-np__click-target");
      // let tempArr = [];

      Array.from(elements).forEach(ele => {
        let img = ele.getElementsByClassName("SirUVb sh-img__image")[0].querySelector("img").src;
        let title = ele.getElementsByClassName("sh-np__product-title")[0].textContent;
        // // console.log(ele.querySelector("a"));
        let context_link = "https://www.google.com" + ele.getAttribute("href");
        if (img.startsWith("http")) {
          let link_id = new URLSearchParams(new URL(img).search).get("q").split(":")[1];
          let obj = {
            "LINK_ID": link_id,
            "TITLE": title,
            "DESC": title,
            "THUMB": img,
            "CATEGORY": "5",
            "CONTEXT_LINK": context_link
          }
          let isExist = arr.some(el => el.LINK_ID == link_id);
          if (isExist == false) {
            obj.TOTAL_LIKES = 0;
            obj.TOTAL_COMMENTS = 0;
            arr.push(obj);
          }
        }
      });
      // console.log(arr);
      filterStoreData(activeFilter, isSearching ? url : search, isSearching);
    });
  }
}

function checkButtonPos() {
  let elem = document.querySelector('.prod-addtocart');
  let bounding = elem.getBoundingClientRect();

  if (bounding.bottom > (window.innerHeight || document.documentElement.clientHeight)) {
    // console.log('out')
    elem.style.bottom = elem.offsetHeight + 20 + 'px';
  } else {
    elem.style.bottom = '25px';
  }
}

function pullRefresh() {
  if (window.Android && $(window).scrollTop() == 0) {
    window.scrollTo(0, document.body.scrollHeight - (document.body.scrollHeight - 3));
  }
}

function pauseAllVideo() {
  $('.timeline-main .carousel-item video, .timeline-image video').each(function () {
    $(this).off("stop pause ended");
    $(this).on("stop pause ended", function (e) {
      $(this).closest(".carousel").carousel();
    });
    $(this).get(0).pause();
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
    $(this).off("play");
    $(this).on("play", function (e) {
      $(this).closest(".carousel").carousel("pause");
    })
    $(this).get(0).play();
    let $videoPlayButton = $(this).parent().find(".video-play");
    $videoPlayButton.addClass("d-none");
  })
}

var commentedProducts = [];

function getCommentedProducts() {
  var f_pin = ""
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  if (f_pin != "") {
    //   // // console.log("GETCOMMENTED");
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        let likeData = JSON.parse(xmlHttp.responseText);
        likeData.forEach(product => {
          var productCode = product.POST_ID;
          commentedProducts.push(productCode);
          $(".comment-icon-" + productCode).attr("src", "../assets/img/jim_comments_blue.png");
        });
        // console.log('get commented', commentedProducts);
      }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_products_commented?f_pin=" + f_pin);
    xmlHttp.send();
  }

}

function addToCartModal() {
  /* start handle detail product popup */
  const initPos = parseInt($('#header-layout').offset().top + $('#header-layout').outerHeight(true)) + "px";
  const fixedPos = JSON.parse(JSON.stringify(initPos));

  // let product_id = "";

  let init = parseInt(fixedPos.replace('px', ''));

  var ua = window.navigator.userAgent;

  $('#modal-addtocart').on('shown.bs.modal', function () {
    $('.modal').css('overflow', 'hidden');
    $('.modal').css('overscroll-behavior-y', 'contain');
    checkButtonPos();
    pullRefresh();
    pauseAllVideo();
    playModalVideo();

    if (window.Android) {
      window.Android.setIsProductModalOpen(true);
    }
  })

  $('.grid-stack-item-content a').click(function () {
    // console.log('init: ' + init);
    $('#modal-addtocart .modal-dialog').css('top', '55px');
    $('#modal-addtocart .modal-dialog').css('height', window.innerHeight - fixedPos);
  })

  $('#modal-addtocart').on('hidden.bs.modal', function () {
    $('.modal').css('overflow', 'auto');
    $('.modal').css('overscroll-behavior-y', 'auto');
    let modalVideo = $('#modal-addtocart').find('video');
    if (modalVideo.length > 0) {
      $('#modal-addtocart .modal-body video').get(0).pause();
    }
    pullRefresh();
    checkVideoViewport();

    if (window.Android) {
      window.Android.setIsProductModalOpen(false);
    }
  })

  /* end handle detail product popup */
}

var likedPost = [];

function likeProduct($productCode, isLiked, $is_post) {
  var score = parseInt($('#like-counter-' + $productCode).text());
  // var isLiked = false;

  // if (window.Android) {
  //   if (window.Android.checkProfile()) {

  //     if (likedPost.includes($productCode)) {
  //       likedPost = likedPost.filter(p => p !== $productCode);
  //       $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.png");
  //       if (score > 0) {
  //         $('#like-counter-' + $productCode).text(score - 1);
  //       }
  //       isLiked = false;
  //     } else {
  //       likedPost.push($productCode);
  //       $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.png");
  //       $('#like-counter-' + $productCode).text(score + 1);
  //       isLiked = true;
  //     }
  //   }
  // } else {
  //   // console.log('liked', likedPost);
  //   if (likedPost.includes($productCode)) {
  //     // console.log('exists', $productCode);
  //     likedPost = likedPost.filter(p => p !== $productCode);
  //     $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.png");
  //     if (score > 0) {
  //       $('#like-counter-' + $productCode).text(score - 1);
  //     }
  //     // isLiked = false;
  //   } else {
  //     // console.log('not exist', $productCode);
  //     likedPost.push($productCode);
  //     $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.png");
  //     $('#like-counter-' + $productCode).text(score + 1);
  //     // isLiked = true;
  //   }
  // }

  

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
  formData.append('flag_like', isLiked);
  formData.append('is_post', $is_post);

  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      // // // console.log(xmlHttp.responseText);
      // updateScore($productCode);

      if (isLiked == 1) {
        $("#like-" + $productCode).attr("src", "../assets/img/jim_likes_red.png");
        $('#like-counter-' + $productCode).text(score + 1);
      } else {
        $("#like-" + $productCode).attr("src", "../assets/img/jim_likes.png");
        $('#like-counter-' + $productCode).text(score - 1);
      }
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

function openComment(code, isPost, f_pin_link) {
  if (window.Android) {
    if (window.Android.checkProfile()) {
      let f_pin = window.Android.getFPin();

      window.location = "comment.php?product_code=" + code + "&is_post=" + isPost + "&f_pin=" + f_pin;
    }
  } else {
    let f_pin = new URLSearchParams(window.location.search).get("f_pin");

    window.location = "comment.php?product_code=" + code + "&is_post=" + isPost + "&f_pin=" + f_pin;
  }
}

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
        // console.log('get likes', likedPost);
      }
    }
    xmlHttp.open("get", "/nexilis/logics/fetch_products_liked?f_pin=" + f_pin);
    xmlHttp.send();
  }
}

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
      // console.log(gif_arr);
      let new_arr = gif_arr;
      if (gif_arr.length === 3) {
        new_arr = [gif_arr[1], gif_arr[0], gif_arr[2]];
      }
      drawGIFs(new_arr);
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
  // console.log(1);
  let url = "";
  if (pickGif.URL.includes('bni.co.id')) {
    url = pickGif.URL;
  }
  else {
    let path = window.location.pathname.split("/");
    if (path.includes("tab3-main")) {
      if (currURL.includes('newuniverse.io')) {
        url = pickGif.URL + '&f_pin=' + f_pin + '&origin=3&url_type=0';
      }
      else if (currURL.includes('108.137.84.148')) {
          url = pickGif.URL + '&f_pin=' + f_pin + '&origin=3&url_type=1';
      }
      else if (currURL.includes('palio.web')) {
        url = 'http://palio.web/nexilis/pages/digipos?env=2&f_pin=' + f_pin + '&origin=3&url_type=2';
      }
        // url = pickGif.URL + '&f_pin=' + f_pin + '&origin=3';
    } else if (path.includes("tab3-main-only")) {
      if (currURL.includes('newuniverse.io')) {
        url = pickGif.URL + '&f_pin=' + f_pin + '&origin=33&url_type=0';
      }
      else if (currURL.includes('108.137.84.148')) {
          url = pickGif.URL + '&f_pin=' + f_pin + '&origin=33&url_type=1';
      }
      else if (currURL.includes('palio.web')) {
        url = 'http://palio.web/nexilis/pages/digipos?env=2&f_pin=' + f_pin + '&origin=33&url_type=2';
      }
      // url = pickGif.URL + '&f_pin=' + f_pin + '&origin=33';
    }
  }
  let div = `
      <div id="gifs-${currentAd}" class="gifs">
      <a onclick="event.preventDefault(); goToURL('${url}');">
          <img src="/nexilis/assets/img/gif/${pickGif.FILENAME}">
        </a>
      </div>
    `;
  // console.log("dir", pickGif.DIRECTION);
  // if (pickGif.FILENAME === "ppob-4.gif") {

  //   // console.log('resize');
  //   $('.gifs img').css('width', '200px !important');
  //   $('.gifs img').css('height', 'auto !important');
  // }
  let dir = pickGif.DIRECTION;
  if (pickGif.DIRECTION === 'left') {
    // console.log('sdnka');
    // if (pickGif.FILENAME.includes('ppob-4')) {
    //   $('#gif-container .gifs img').css('width', '200px');
    //   $('#gif-container .gifs img').css('height', 'auto');
    // }
    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass('right');
    // document.getElementById('gif-container').classList.add = 'right';
  } else if (pickGif.DIRECTION === 'right') {
    // console.log('sdnka');
    $('#gif-container').addClass('bottom');
    $('#gif-container').addClass('left');
    // document.getElementById('gif-container').classList.add = 'left';
  } else if (pickGif.DIRECTION === 'up') {
    // console.log('sdnka');
    $('#gif-container').addClass('bottom');
    // document.getElementById('gif-container').classList.add = 'bottom';
    let dir = ['right', 'left'];
    let random = Math.floor(Math.random() * dir.length);
    // console.log('random', dir[random])
    $('#gif-container').addClass(dir[random]);
    // document.getElementById('gif-container').classList.add = dir[random];
  } else if (pickGif.DIRECTION === 'down') {
    // console.log('sdnka');
    $('#gif-container').addClass('top');
    // document.getElementById('gif-container').classList.add = 'top';
    let dir = ['right', 'left'];
    let random = Math.floor(Math.random() * dir.length);
    $('#gif-container').addClass(dir[random]);
    // document.getElementById('gif-container').classList.add = dir[random];
  } else if (pickGif.DIRECTION === null) {
    // console.log('sdnka');
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
    // // console.log('sini anjing');
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
  // console.log(which);
  // console.log(direction);
  var lineHeight = $('#gifs-' + current).width();
  // console.log(lineHeight);
  if (which === 'horizontal') { // move horizontal
    var windowHeight = $(window).width();
    var lineHeight = $('#gifs-' + current).width();
    var desiredBottom = 20;
    var newPosition = windowHeight - (115 + desiredBottom);
    if (direction === 'right') {

      // // console.log('lh', lineHeight);
      // // console.log('db', desiredBottom);
      // // console.log('np', newPosition);
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
    // console.log(desiredBottom);
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
    // console.log('scroll');
    if (scroll >= 150) {
      /* insert what happens after scroll bigger than 350px */
      // console.log('stop animate');
      $('#gif-container').stop(true, false);
      $('#gif-container').fadeOut();
    }
  })
}

let category_arr = [];

let categoryTree;


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
      // console.log(webform);
      if (webform.APP_URL === '1' || webform.APP_URL === '2') {
        if (webform.APP_URL_DEFAULT !== null && webform.APP_URL_DEFAULT !== '') {
          defaultCategory = webform.APP_URL_DEFAULT;
        }
      } else if (webform.CONTENT_TAB_LAYOUT === '1' || webform.CONTENT_TAB_LAYOUT === '2') {
        if (webform.CONTENT_TAB_DEFAULT !== null && webform.CONTENT_TAB_DEFAULT !== '') {
          defaultCategory = webform.CONTENT_TAB_DEFAULT;
        }
      }

      if (defaultCategory !== '' && defaultCategory !== null) {
        defaultCategory = defaultCategory.replaceAll(",", "-")
        visibleCategory = defaultCategory;
        let defCat = defaultCategory.split('-');
        let visCat = visibleCategory.split('-');

        if (activeFilter != "") {
          let filter = activeFilter.split("-");

          defCat.forEach(dc => {
            if (filter.includes(dc)) {
              $('#root-category input#' + dc).prop('checked', true);
            }
          })
        } else {
          defCat.forEach(dc => {
            $('#root-category input#' + dc).prop('checked', true);
          })
        }
        


        try {
          if (BE_ID === "347") {
            // console.log("BE_ID", BE_ID);
            // console.log("viscat", visCat);
            $("#root-category li").each(function () {
              let catId = $(this).attr('id').split("-")[1];
              // console.log("CATID", catId);
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
          // console.log(e);
        }

        
      } else {
        // defaultCategory = activeFilter;
      }

      fetchLinks();
    }
  }
  xmlHttp.open("get", "/nexilis/logics/fetch_default_category?f_pin=" + f_pin);
  xmlHttp.send();
}

function fetchCategory() {
  let f_pin = '';
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  let xmlHttp = new XMLHttpRequest();
  xmlHttp.onreadystatechange = function () {
    if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
      category_arr = JSON.parse(xmlHttp.responseText);
      // console.log(category_arr);

      if (category_arr.length > 0) {
        categoryTree = unflatten(category_arr);
        // console.log(categoryTree);

        let objTree = {
          CATEGORY_ID: "0",
          NAME: "root",
          CHILDREN: categoryTree
        }

        // console.log(objTree);

        createCategoryCheckbox($('#categoryFilter-body #root-category'), objTree);
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
  // console.log(branch);
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
  activeCategoryTab();
}

function checkboxBehavior() {
  $('#categoryFilter-body li :checkbox').on('click', function () {
    // console.log('asdmas');
    var isChecked = $(this).is(":checked");

    //down
    $(this).closest('ul').find("ul li input:checkbox").prop("checked", isChecked);
  });
}

function checkArray() {
  var selected = [];
  $('#categoryFilter-body input:checked').each(function () {
    selected.push($(this).attr('id'));
  });
  // console.log('checked', selected);
}

$(window).on('load', async function () {
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

  fetchCategory();

  // loadClient();
  activeCategoryTab();
  // await getSearchSettings();
  fetchDefaultCategory();
  // fetchLinks();
  hasStoreId();
  onClickHasStory();
  changeLayout();
  if (document.getElementById('gif-container') != null) {
    getGIFs();
  }

  $(window).scroll(function () {
    // make sure u give the container id of the data to be loaded in.
    // // // console.log(Math.ceil($(window).scrollTop() + $(window).height()) >= $("#content-grid").height());
    // // console.log(busy);
    if ((Math.ceil($(window).scrollTop() + ($(window).height() * 1.5)) >= $("#content-grid").height()) && !busy) {
      // // // console.log('scroll here');
      busy = true;
      offset = limit + offset;
      // displayRecords(limit, offset);
      fillGridWidgets('#content-grid', currentSort, limit, offset);
    }
  });
})

let video_arr = ['webm', 'mp4'];
let img_arr = ['png', 'jpg', 'webp', 'gif', 'jpeg'];

// let richText = (content) => {
// 	let cont = content
// 		.replace(/\*([^\*]+)\*/g, "<strong>$1</strong>")
// 		.replace(/\^([^\^]+)\^/g, "<u>$1</u>")
// 		.replace(/\_([^\_]+)\_/g, "<i>$1</i>")
// 		.replace(/\~([^\~]+)\~/g, "<del>$1</del>")
// 		.replace(/[\n\r]+/g, "<br>");
// 	return cont;
// };

class ShowProduct {

  constructor(async_result, comments="") {

    // console.log(async_result);

    let thumbs = async_result.thumb_id.split('|');
    let name = richText(decodeURIComponent((async_result.name + '').replace(/\+/g, '%20')));
    let description = richText(decodeURIComponent((async_result.description + '').replace(/\+/g, '%20')));

    // console.log(thumbs);

    let content = '';
    let domain = 'https://newuniverse.io';
    let preContent = '<div class="container-fluid">';

    // FOR X BUTTON

    preContent += `<img class="close-icon" onclick="closeModal()" src="../assets/img/close-icon.png" style="
                    position: absolute;
                    z-index: 500;
                    right: 0;
                    margin-right: 20px;
                    margin-top: 20px;
                    width: 17px;
                    height: 17px;
                    opacity: 0.8;
                    filter: drop-shadow(1px 0px 1px #222);
                ">`;

    if (thumbs.length == 1) {
      let type = ext(thumbs[0]);
      // console.log(type);
      if (video_arr.includes(type)) {
        // content = `
        //     <video muted autoplay loop class="d-block w-100">
        //     <source src="../images/${thumbs[0]}#t=0.5" type="video/${type}">
        //     </video>
        // `;
        content += `
                <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                <video class="myvid" autoplay muted playsinline src="${thumbs[0].includes("http") ? thumbs[0] : domain + "/nexilis/images/" + thumbs[0]}">
                </video>
                <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                <img src="../assets/img/video_mute.png" />
                </div>
                <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                '<img src="../assets/img/video_play.png" />
                </div>
                </div>
                `;
      } else if (img_arr.includes(type)) {
        content += `
                    <img src="${thumbs[0].substr(0,4) == "http" ? th : domain+'/nexilis/images/' + thumbs[0]}" class="d-block w-100">
                `;
      }
    } else {
      content = `
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
            `;

      let filteredThumbs = thumbs.filter(thumb => thumb.trim() != '' || thumb.length > 0);
      filteredThumbs.forEach((th, idx) => {
        if (th.trim() != '') {
          content += `<div class="carousel-item${idx == 0 ? ' active' : ''}">`;

          let type = ext(th);
          // console.log('type', type);
          if (video_arr.includes(type)) {
            //     content += `
            //     <video autoplay muted class="d-block w-100">
            //     <source src="${th.substr(0,4) == "http" ? th : 'https://qmera.io/nexilis/images/' + th}#t=0.5" type="video/${type}">
            //     </video>
            // `;
            content += `
                        <div class="video-wrap" id="videowrap-modal-${async_result.CODE}">
                        <video class="myvid" autoplay muted playsinline src="${th.includes("http") ? th : domain + "/nexilis/images/" + th}">
                        </video>
                        <div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute('videowrap-modal-${async_result.CODE}');">
                        <img src="../assets/img/video_mute.png" />
                        </div>
                        <div class="video-play d-none" onclick="event.stopPropagation(); playVid('videowrap-modal-${async_result.CODE}');">
                        '<img src="../assets/img/video_play.png" />
                        </div>
                        </div>
                        `;
          } else if (img_arr.includes(type)) {
            content += `
                        <img src="${th.substr(0,4) == "http" ? th : domain + '/nexilis/images/' + th}" class="d-block w-100">
                    `;
          }
          // console.log(content);
          content += `</div>`;
        }
      })

      


      if (filteredThumbs.length > 1) {
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
    }

    // LIKE, FOLLOW SECTION
    content += `
    <div class="row mt-3 align-items-center">
      <div class="col-3 p-2">
        <div class="like-button" onclick="likeProduct('${async_result.CODE}', '${async_result.IS_LIKED == 0 ? 1 : 0}', 0)">
          <img id="like-${async_result.CODE}" src="../assets/img/${async_result.IS_LIKED == 0 ? "jim_likes.png" : "jim_likes_red.png"}?v=2" height="25" width="25">
          <span id="like-counter-${async_result.CODE}">${async_result.TOTAL_LIKES}</span>
        </div>
        <div class="follower-button" onclick="followUser('${async_result.L_PIN}', '${async_result.IS_FOLLOW}', '${async_result.CODE}')">
          <img id="follow-${async_result.L_PIN}" class="comment-icon" src="../assets/img/social/${async_result.IS_FOLLOW > 0 ? "followed.svg" : "follow.svg"}" style="opacity: 0.6"> 
          <span id="follow-counter-${async_result.CODE}" style="color: #3678bd">${async_result.TOTAL_FOLLOW}</span>
        </div>
      </div>
    </div>
    </div>
    `;

    let link = '';
    let url_div = '';

    if (async_result.LINK != null && async_result.LINK != undefined && async_result.LINK.trim() != "") {
      link = async_result.LINK;
      if (link.substring(0, 4) != "http") {
        link = "https://" + link;
      }
      // console.log(link);
      // link_thumb = '<a href="' + link + '">' + thumb_content + '</a>';
      url_div = `
           <a href="${link}" style="text-decoration:underline; color:#0d6efd";>Link</a>
            `;
      if (async_result.CODE == '16467163130000246a901c4') {
        url_div = `
           <a href="${link + "?f_pin=" + f_pin}" style="text-decoration:underline; color:#0d6efd";>Register membership</a>
            `;
      }
    }

    let f_pin = '';
    if (window.Android) {
      f_pin = window.Android.getFPin();
    } else {
      f_pin = new URLSearchParams(window.location.search).get('f_pin');
    }

    // console.log(content)
    // codes below wil only run after getProductThumbs done executing
    this.html_body = preContent + content;
    let profpic = dataFiltered.find(df => df.LINK_ID == async_result.CODE).PROFPIC;

    if (profpic == null || profpic == "") {

      profpic = "/nexilis/assets/img/ic_person_boy.png";

    } else {

      profpic = "/filepalio/image/" + profpic;

    }

    

    let uname = dataFiltered.find(df => df.LINK_ID == async_result.CODE).USERNAME;
    let poster = dataFiltered.find(df => df.LINK_ID == async_result.CODE).F_PIN;
    // let verified_icon = dataFiltered.find(df => df.LINK_ID == async_result.CODE).OFFICIAL_ACCOUNT == 2 ? `<img src="/nexilis/assets/img/ic_verified_flag.png" style="width:15px; height:15px;"/>` : '';

    let is_official_acc = dataFiltered.find(df => df.LINK_ID == async_result.CODE).OFFICIAL_ACCOUNT;
    let verified_icon = "";

    if (is_official_acc == 1) {
      verified_icon = '<img src="/nexilis/assets/img/ic_official_flag.webp" style="width:15px; height:15px;"/>';
    } else if (is_official_acc == 2) {
      verified_icon = '<img src="/nexilis/assets/img/ic_verified_flag.png" style="width:15px; height:15px;"/>';
    }

    let reportPost = "Report/flag Content";
        let blockPost = "Remove/Block Content";
        let removeText = "Remove";
        let editText = "Edit";
        let deleteText = "Delete";

        if (localStorage.lang == 1) {
            reportPost = "Laporkan Konten";
            blockPost = "Hapus/Blokir Konten";
            removeText = "Hilangkan";
            editText = "Ubah";
            deleteText = "Hapus";
        } else {
            reportPost = "Report/flag Content";
            blockPost = "Remove/Block Content";
            removeText = "Remove";
            editText = "Edit";
            deleteText = "Delete";
        }

    this.html_header = `
    <div class="container-fluid">
      <div class="row">
        <div class="col-10 d-flex align-items-center">
          <a onclick="openProfile('tab3-profile?f_pin=${f_pin}&store_id=${poster}')">
            <img src="${profpic}" class="align-self-start rounded-circle me-2" style="width:35px; height:35px; object-fit:cover;">
            <div class="media-body" style="display:inline-block; flex-grow:1;">
              <h6>${verified_icon} ${uname}</h6>
            </div>
          </a>
        </div>
          
        <div class="col-2 d-flex align-items-center">
          <div class="dropdown dropdown-edit edit-menu-${poster}" data-isadmin="${user_type}"><a class="post-status dropdown-toggle" data-bs-toggle="dropdown" id="edt-del-${poster}"><img src="../assets/img/icons/More.png" height="25" width="25" style="background-color:unset; float:right"></a>
              <ul class="dropdown-menu"">
                  <li><a class="dropdown-item button_report ${poster != f_pin ? '' : 'd-none'}" onclick="reportContent('${async_result.CODE}')">Report/flag Content</a></li>
                  <li><a class="dropdown-item button_report ${poster != f_pin ? '' : 'd-none'}" onclick="reportUser('${poster}')">Report/flag User</a></li>
                  <li><a class="dropdown-item button_block ${poster != f_pin ? '' : 'd-none'}" onclick="blockContent('${async_result.CODE}')" style="color:darkred">Remove/Block Content</a></li>
                  <li><a class="dropdown-item button_block ${poster != f_pin ? '' : 'd-none'}" onclick="blockUser('${poster}')" style="color:darkred">Remove/Block User</a></li>
                  <li><a class="dropdown-item button_edit ${poster == f_pin ? '' : 'd-none'}" onclick="editPost('${async_result.CODE}')">Edit</a></li>
                  <li><a class="dropdown-item button_delete ${poster == f_pin ? '' : 'd-none'}" onclick="deletePost('${async_result.CODE}')">Delete</a></li>
                  
                  <li><a class="dropdown-item button_adminremove ${user_type == 1 && async_result.L_PIN != f_pin ? "" : "d-none"}" onclick="removePost('${async_result.CODE}')">${removeText}</a></li>
              </ul>
          </div>
        </div>
      </div>
    </div>`;

    this.html_footer = `
    
    ${url_div != "" && url_div != null ? "<span style='font-size:13px;'>"+url_div+"</span>" : ""}
    <div class="prod-desc ${description.length > 128 ? "truncate" : ""}" style="font-size:13px;">${description}</div>
    ${description.length > 128 ? "<span class=\"truncate-read-more\" onclick=\"toggleProdDesc(this);\">" + truncateText + "</span>" : ""}
    <div class="container" id="comments-modal"> 
    ${comments}
    </div>`;

    this.parent = document.body;
    this.modal_header = document.querySelector('#modal-product .modal-header');
    this.modal_body = document.querySelector('#modal-product .modal-body');
    this.modal_footer = document.querySelector('#modal-product .modal-footer');

    this.modal_header.innerHTML = " ";
    this.modal_body.innerHTML = " ";
    // this.modal_footer.innerHTML = " ";

    this._createModal();

    if (window.Android) {
      window.Android.setIsProductModalOpen(true);
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen) {
      window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
        param1: true
      });
    }
  }

  static async build(product_code, is_product) {
    let async_result = await getProductThumbs(product_code, is_product);
    console.log(async_result)
    let commentsection = "";
        // if (isChangedProfile == "1") {
            commentsection = await getComments(product_code);
        // }
        // let 
        return new ShowProduct(async_result, commentsection);
    // return new ShowProduct(async_result);
  }

  question() {
    // this.save_button = document.getElementById('confirm-changes');

    // return new Promise((resolve, reject) => {
    //     this.save_button.addEventListener("click", () => {
    //         event.preventDefault();
    //         resolve(true);
    //         this._destroyModal();
    //     })
    // })
  }

  _createModal() {

    // Main text

    this.modal_body.innerHTML = this.html_body;
    this.modal_header.innerHTML = this.html_header;
    this.modal_footer.innerHTML = this.html_footer;

    // Let's rock
    $('#modal-product').modal('show');
    $('#carouselExampleIndicators').carousel();
  }

  _destroyModal() {
    $('#modal-product').modal('hide');
  }
}

async function showProductModal(product_code, is_product) {

  // event.preventDefault();

  let add = await ShowProduct.build(product_code, is_product);
  // let response = await add.question();

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

              $('.follower-button').attr('onclick','followUser("'+l_pin+'","1","'+post_id+'")');

          }else{
              $("#follow-" + l_pin).attr("src", "../assets/img/social/follow.svg");

              currentFollow = currentFollow - 1;
              $('#follow-counter-'+post_id).text(currentFollow);

              // CHANGE FLAG IF DOUBLE CLICK TO FOLLOW UNFOLLOW

              $('.follower-button').attr('onclick','followUser("'+l_pin+'","0","'+post_id+'")');
          }

          console.log("After do :"+currentFollow);

      }
  }
  xmlHttp.open("post", "/nexilis/logics/follow_user");
  xmlHttp.send(formData);

  console.log("Follow = "+l_pin);
  
}

// SHOW PRODUCT FUNCTIONS
function getProductThumbs(product_code, is_product) {
  let formData = new FormData();
  formData.append("product_id", product_code);
  formData.append("is_product", is_product);

  let f_pin = "";
  if (window.Android) {
    f_pin = window.Android.getFPin();
  } else {
    f_pin = new URLSearchParams(window.location.search).get('f_pin');
  }
  formData.append("f_pin", f_pin);

  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/nexilis/logics/get_product_thumbs");

    xhr.onload = function () {
      if (this.status >= 200 && this.status < 300) {
        resolve({
          thumb_id: JSON.parse(xhr.response).THUMB_ID,
          L_PIN: JSON.parse(xhr.response).SHOP_CODE,
          name: JSON.parse(xhr.response).NAME,
          IS_FOLLOW: JSON.parse(xhr.response).IS_FOLLOW,
          TOTAL_FOLLOW: JSON.parse(xhr.response).TOTAL_FOLLOW,
          IS_LIKED: JSON.parse(xhr.response).IS_LIKED,
          TOTAL_LIKES: JSON.parse(xhr.response).TOTAL_LIKES,
          description: JSON.parse(xhr.response).DESCRIPTION,
          CODE: JSON.parse(xhr.response).CODE,
          LINK: JSON.parse(xhr.response).LINK,
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

function openProfile(url) {
  // localStorage.setItem('origin_page', location.href);
    
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
  // console.log("FINAL URL", finalUrl);

}

function closeModal(checkIOS = false) {
  $('#modal-product').modal('hide');
  $("#modal-product .modal-header").html("");
  $("#modal-product .modal-body").html("");

  if (window.Android) {
    window.Android.setIsProductModalOpen(false);
  }
  if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.setIsProductModalOpen && checkIOS == false) {
    window.webkit.messageHandlers.setIsProductModalOpen.postMessage({
      param1: false
    });
  }

}

$('.dropdown-edit').on('show.bs.dropdown', function () {
  $('.close-icon').hide();
});

$('.dropdown-edit').on('hide.bs.dropdown', function () {
  $('.close-icon').show();
});


function getComments(code) {
  // get comments
  
      return new Promise(function (resolve, reject) {
          let fpin = window.Android ? window.Android.getFPin() : new URLSearchParams(window.location.search).get("f_pin");
          let commentURL = "comment_modal.php?product_code=" + code + "&f_pin=" + fpin + "&is_post=1&icp=" + isChangedProfile + "&lang=" + localStorage.lang;
          console.log("code", code)
          console.log("ICP", isChangedProfile)
          xReply = 1;
          xReffReply = 10;
          var commentsection = "";
          // if (isChangedProfile == "1") {
              $.get(commentURL, function (data) {
                  // console.log("fetch comment", data)
                  // $('#comments-modal').html(data);
                  resolve(data);
              });
          // }
      })
      
  }


  