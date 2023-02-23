<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
session_start();
$dbconn = paliolite();
$dbNewNus = newnus();

if (!isset($_GET['product_code'])) {
  die();
}

if (isset($_GET['icp'])) {
  $icp = $_GET['icp'];
}

$lang = "0";
if (isset($_GET['lang'])) {
  $lang = $_GET['lang'];
}

if (isset($_GET['is_post'])) {
  $is_post = 0;
} else {
  $is_post = $_GET['is_post'];
}



$product = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_spesific_post.php');

$shop_thumb_id = $product[0]["THUMB_ID"];

if ($shop_thumb_id == null || $shop_thumb_id == "") {
  $shop_thumb_id = "/nexilis/assets/img/ic_person_boy.png";
} else {
  $shop_thumb_id = '/filepalio/image/' . $product[0]["THUMB_ID"];
}

if ($is_post == 0) {
  $product_name = "Nexilis";
  $shop_thumb_id = "/nexilis/assets/img/nxsport_icons/nx.png";
} else {
  $product_name = $product[0]["NAME"];
}

$product_description = $product[0]["DESCRIPTION"];
$product_date = $product[0]["CREATED_DATE"];

// 
// $shop_thumb_id = $shop_thumb[0];
$seconds = intval(intval($product_date) / 1000);
$printed_date = date("H:i", $seconds);
$date_explode = explode(":", $printed_date);
$hours = (int)$date_explode[0] + 7;
if ($hours >= 24) {
  $hours = $hours - 24;
  $hours = "{$hours}";
  if (strlen($hours) == 1) {
    $hours = "0" . $hours;
  }
}
$printed_date = $hours . ":" . $date_explode[1];

// if (!(substr($shop_thumb_id, 0, 4) === "http")) {
//   $shop_thumb_id = "/nexilis/images/" . $shop_thumb_id;
// }

if (isset($_GET['f_pin'])) {

  $f_pin = $_GET['f_pin'];
}

$bg_url = "";

if (isset($f_pin) && $f_pin != "") {
  // $sqlBE = "SELECT be.COMPANY_ID
  // FROM BUSINESS_ENTITY be
  // LEFT JOIN USER_LIST ul ON ul.BE = be.ID
  // WHERE ul.F_PIN = '$f_pin'";
  $sqlBE = "SELECT BE AS BE_ID, IS_CHANGED_PROFILE AS ICP FROM USER_LIST WHERE F_PIN = '$f_pin'";
} else if (isset($api_key) && $api_key != "") {
  // $sqlBE = "SELECT be.COMPANY_ID
  // FROM BUSINESS_ENTITY be
  // WHERE be.API = '$api_key'";
  $sqlBE = "SELECT ID AS BE_ID FROM BUSINESS_ENTITY WHERE API = '$api_key'";
}

$queBEID = $dbconn->prepare($sqlBE);
$queBEID->execute();
$resBEID = $queBEID->get_result()->fetch_assoc();
$queBEID->close();

$be = $resBEID["BE_ID"];

function richText($str)
{
  // let cont = content
  // 	.replace(/\*([^\*]+)\*/g, "<strong>$1</strong>")
  // 	.replace(/\^([^\^]+)\^/g, "<u>$1</u>")
  // 	.replace(/\_([^\_]+)\_/g, "<i>$1</i>")
  // 	.replace(/\~([^\~]+)\~/g, "<del>$1</del>")
  // 	.replace(/[\n\r]+/g, "<br>");
  // return cont;

  $newStr = preg_replace('/\*([^\*]+)\*/', '<strong>$1</strong>', $str);
  $newStr = preg_replace('/\^([^\^]+)\^/', '<u>$1</u>', $newStr);
  $newStr = preg_replace('/\_([^\_]+)\_/', '<i>$1</i>', $newStr);
  $newStr = preg_replace('/\~([^\~]+)\~/', '<del>$1</del>', $newStr);
  $newStr = preg_replace('/[\n\r]+/', '<br>', $newStr);

  return $newStr;
}

?>

<style>
  

  /* asdadasd */
</style>
<!-- <script src="../assets/js/script-data-comment.js?random=<?= time(); ?>"></script> -->
<div id="content-comment" style="margin-bottom:3.5rem">
  <?php


  $comments = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_posts_comments.php');

  function getReplies($reffs, $sub, $fpin, $lang)
  {
    ${"j" . $sub} = 0;
    foreach ($reffs as $reff) {
      ${"comment_id_reff" . $sub} = $reff["COMMENT_ID"];
      ${"f_pin_reff" . $sub} = $reff["F_PIN"];
      ${"comment_text_reff" . $sub} = $reff["COMMENT"];
      ${"created_date_reff" . $sub} = $reff["CREATED_DATE"];
      $isdeleted = $reff["IS_DELETE"];

      ${"seconds_reff" . $sub} = intval(intval(${"created_date_reff" . $sub}) / 1000);
      ${"printed_date_reff" . $sub} = date("H:i", ${"seconds_reff" . $sub});
      ${"date_explode_reff" . $sub} = explode(":", ${"printed_date_reff" . $sub});
      ${"hours_reff" . $sub} = (int)${"date_explode_reff" . $sub}[0] + 7;
      if (${"hours_reff" . $sub} >= 24) {
        ${"hours_reff" . $sub} = ${"hours_reff" . $sub} - 24;
        ${"hours_reff" . $sub} = "{" . ${"hours_reff" . $sub} . "}";
        if (strlen(${"hours_reff" . $sub}) == 1) {
          ${"hours_reff" . $sub} = "0" . ${"hours_reff" . $sub};
        }
      }
      ${"printed_date_reff" . $sub} = ${"hours_reff" . $sub} . ":" . ${"date_explode_reff" . $sub}[1];
      ${"parameter_reply_reff" . $sub} = "true," . "'user-name-reff-" . $sub . ${"j" . $sub} . "'," . "'" . ${'comment_id_reff' . $sub} . "'";
      ${"parameter_profile_reff" . $sub} = "'" . ${"f_pin_reff" . $sub} . "'";

      $displayPic = $reff['IMAGE'];
      $displayName = $reff['USERNAME'];

      if ($isdeleted == 1) {
        continue;
      }

      if ($displayPic == "") {
        $displayPic = "../assets/img/ic_person_boy.png";
      } else {
        $displayPic = "/filepalio/image/" . $displayPic;
      }

      if ($lang == "0") {
        $text_reply = "Reply";
        $text_remove = "Delete";
      } else {
        $text_reply = "Balas";
        $text_remove = "Hapus";
      }


      if ($fpin == $reff["F_PIN"]) {
        $deleteShow = "";
      } else {
        $deleteShow = "d-none";
      }

      echo '<div class="row comments cmt-reply" id="' . ${'comment_id_reff' . $sub} . '">';
      echo '<div class="commentId" style="display: none;">' . ${'comment_id_reff' . $sub} . '</div>';
      echo '<div class="fPin" style="display: none;">' . ${"f_pin_reff" . $sub} . '</div>';
      echo '<div class="col-2">';
      echo '<img onclick="window.location.href=\'tab3-profile?f_pin='.$_SESSION["f_pin"].'&store_id=' . ${"f_pin_reff" . $sub} . '\'" id="user-thumb-reff-' . $sub . ${"j" . $sub} . '" class="rounded-circle my-2" style="height:40px; width:40px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" id="display-pic" src="' . $displayPic . '">';
      echo '</div>';
      echo '<div class="col-10 text-break">';
      echo '<div style="font-weight: bold;" class="mt-2 mb-1 mr-3"><span id="user-name-reff-' . $sub . ${"j" . $sub} . '">' . $displayName . '</span><span style="font-weight: 300;"> ' . ${"comment_text_reff" . $sub} . '</h4></div>';
      echo '<div style="font-weight: 100; color: grey;" class="my-1">' . ${"printed_date_reff" . $sub} . '&emsp;<span class="text-replied" data-translate="comment-2" style="font-weight: 300;" onclick="onReply(' . ${"parameter_reply_reff" . $sub} . ');">'.$text_reply.'</span>&emsp;<span class="text-delete '.$deleteShow.'" style="font-weight: 300;" onclick="showSuccessModal(\'' . ${'comment_id_reff' . $sub} . '\');">'.$text_remove.'</span></div>';
  ?>

      <script>
        // if (localStorage.lang == 0) {
        //   $('.text-replied').text('Reply');
        // } else if (localStorage.lang == 1) {
        //   $('.text-replied').text('Balas');
        // }
      </script>

    <?php
      echo '</div>';
      echo '</div>';

      echo ('<script>getDisplayNameReff("' . ${"f_pin_reff" . $sub} . '","' . $sub . '","' . ${"j" . $sub} . '")</script>');
      echo ('<script>getThumbIdReff("' . ${"f_pin_reff" . $sub} . '","' . $sub . '","' . ${"j" . $sub} . '")</script>');
      ${"reffs" . $sub} = include($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_posts_comments.php');
      if (count(${"reffs" . $sub}) > 0) {
        getReplies(${"reffs" . $sub}, $sub + 1, $fpin, $lang);
      }
      ${"j" . $sub}++;
    }
  }

  $i = 0;

  foreach ($comments as $comment) {
    $comment_id = $comment["COMMENT_ID"];
    if ($is_post == 0) {
      $product_code = $comment["LINK_ID"];
    } else {
      $product_code = $comment["POST_ID"];
    }
    $comment_f_pin = $comment["F_PIN"];
    $comment_text = $comment["COMMENT"];
    $created_date = $comment["CREATED_DATE"];
    $displayPic = $comment['IMAGE'];
    $displayName = $comment['USERNAME'];
    $isdeleted = $comment["IS_DELETE"];

    if ($isdeleted == 1) {
      continue;
    }

    if ($displayPic == "") {
      $displayPic = "../assets/img/ic_person_boy.png";
    } else {
      $displayPic = "/filepalio/image/" . $displayPic;
    }

    $seconds = intval(intval($created_date) / 1000);
    $printed_date = date("H:i", $seconds);
    $date_explode = explode(":", $printed_date);
    $hours = (int)$date_explode[0] + 7;
    if ($hours >= 24) {
      $hours = $hours - 24;
      $hours = "{$hours}";
      if (strlen($hours) == 1) {
        $hours = "0" . $hours;
      }
    }

    if ($lang == "0") {
      $text_reply = "Reply";
      $text_remove = "Delete";
    } else {
      $text_reply = "Balas";
      $text_remove = "Hapus";
    }

    if ($comment_f_pin == $f_pin) {
      $deleteShow = "";
    } else {
      $deleteShow = "d-none";
    }

    $printed_date = $hours . ":" . $date_explode[1];
    $parameter_reply = "true," . "'user-name-" . $i . "'," . "'$comment_id'";
    echo '<div class="row mx-0 comments" id="' . $comment_id . '">';
    echo '<div class="commentId" style="display: none;">' . $comment_id . '</div>';
    echo '<div class="fPin" style="display: none;">' . $comment_f_pin . '</div>';
    echo '<div class="col-2">';
    echo '<img onclick="window.location.href=\'tab3-profile?f_pin='.$_SESSION["f_pin"].'&store_id=' . $comment_f_pin . '\'" class="rounded-circle my-2" style="height:40px; width:40px; cursor:pointer; object-position: center; background: grey; object-fit: cover;" id="display-pic" src="' . $displayPic . '">';
    echo '</div>';
    echo '<div class="col-10 text-break">';
    echo '<div style="font-weight: bold;" class="mt-2 mb-1 mr-3"><span id="user-name-' . $i . '">' . $displayName . '</span><span style="font-weight: 300;"> ' . $comment_text . '</h4></div>';
    echo '<div style="font-weight: 100; color: grey;" class="my-1">' . $printed_date . '&emsp;<span class="text-replied" style="font-weight: 300;" data-translate="comment-2" onclick="onReply(' . $parameter_reply . ');">'.$text_reply.'</span>&emsp;<span class="text-delete '.$deleteShow.'" style="font-weight: 300;" onclick="showSuccessModal(\'' . $comment_id . '\');">'.$text_remove.'</span></div>';
    ?>

    <script>
      // if (localStorage.lang == 0) {
      //   $('.text-replied').text('Reply');
      // } else if (localStorage.lang == 1) {
      //   $('.text-replied').text('Balas');
      // }
    </script>

  <?php
    echo '</div>';
    // $reffs = include($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_posts_comments.php');
    // if (count($reffs) > 0) {
    //   getReplies($reffs, 1);
    // }
    echo '</div>';

    echo ('<script>getDisplayName("' . $f_pin . '","' . $i . '")</script>');
    echo ('<script>getThumbId("' . $f_pin . '","' . $i . '")</script>');
    $reffs = include($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_posts_comments.php');
    if (count($reffs) > 0) {
      getReplies($reffs, 1, $f_pin, $lang);
    }
    $i++;
  }

  echo "<script>enableDelete();</script>"
  ?>
</div>

<div class="row fixed-bottom p-2 pb-3 <?= $icp == "1" ? "" : "d-none" ?>" style="background-color: white;" id="comment-input">
  <div style="width: 95%;height: 40px;background: #b0bec6;margin: 0 auto;padding: .5rem 0 0 0;" class="d-none row mb-2 pt-2" id="reply-div">
    <div class="col-10" style="color: grey; font-weight: 300; padding-left: 40px;" id="content-reply">
    </div>
    <div class="col-2 text-right">
      <i class="fas fa-times" style="color: white;" onclick="onReply(false);"></i>
    </div>
  </div>
  <div class="col-10">
    <input type="text" name="message" id="input" placeholder="<?= $lang == "0" ? "Write Comment" : "Tulis Komentar" ?>" data-translate-placeholder="comment-3" onclick="onFocusInput()" class="border-0 px-3 py-2 rounded shadow-sm">
  </div>
  <div class="col-2">
    <div id="buttond_send" class="px-3 py-2" onclick="commentProduct('<?php echo $product_code; ?>')">
      <img src="../assets/img/arrow_send.png" id="triangle-right">
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script> -->
<script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
<!-- <script src="../assets/js/script-comment.js?random=<?= time(); ?>"></script> -->

<script>
      console.log("LANG", localStorage.lang)
  if (localStorage.lang == 0) {
    $('#header-title').text('Comment');
    $('#input').attr('placeholder', 'Write a Comment');
  } else if (localStorage.lang == 1) {
    $('#header-title').text('Komentar');
    $('#input').attr('placeholder', 'Tulis Komentar');
  }

  var isChangedProfile = "<?= $resBEID["ICP"] ?>";

  if (isChangedProfile == "1") {
    $("#comment-input").removeClass("d-none")
  }
  // let xReply = 0;
  // let xReffReply = 0
</script>