<?php
if (!isset($_GET['store_id'])) {
  die();
}
$store = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_stores_raw.php');
$store_thumb_id = $store[0]["THUMB_ID"];
$store_name = $store[0]["NAME"];
$store_code = $store[0]["CODE"];
$store_link = $store[0]["LINK"];
$store_follower = $store[0]["TOTAL_FOLLOWER"];
$store_isVerified = $store[0]["IS_VERIFIED"];
$store_category_raw = str_split($store[0]["CATEGORY"]);
$store_email = $store[0]["BE_EMAIL"];
$store_be = $store[0]["BE_ID"];
$store_desc = $store[0]["DESCRIPTION"];
$store_isArtist = ($store_category_raw[0] == '4');

$store_address = "";
if (!empty($store[0]["ADDRESS"])) {
  $store_address = $store_address . $store[0]["ADDRESS"];
}
if (!empty($store[0]["VILLAGE"])) {
  if (!empty($store_address)) {
    $store_address = $store_address . ", ";
  }
  $store_address = $store_address . $store[0]["VILLAGE"];
}
if (!empty($store[0]["DISTRICT"])) {
  if (!empty($store_address)) {
    $store_address = $store_address . ", ";
  }
  $store_address = $store_address . $store[0]["DISTRICT"];
}
if (!empty($store[0]["CITY"])) {
  if (!empty($store_address)) {
    $store_address = $store_address . ", ";
  }
  $store_address = $store_address . $store[0]["CITY"];
}
if (!empty($store[0]["PROVINCE"])) {
  if (!empty($store_address)) {
    $store_address = $store_address . ", ";
  }
  $store_address = $store_address . $store[0]["PROVINCE"];
}
if (!empty($store[0]["ZIP_CODE"]) && !empty($store_address)) {
  $store_address = $store_address . ", " . $store[0]["ZIP_CODE"];
}

$phone_number = '-';
if (!empty($store[0]["PHONE_NUMBER"])) {
  $phone_number = $store[0]["PHONE_NUMBER"];
}

// get number of posts
$store_id = $_GET['store_id'];
$f_pin = $_GET['f_pin'];

// SELECT USER PROFILE
if (!isset($store_id) && isset($_GET['store_id'])) {
  $store_id = $_GET['store_id'];
}
if (isset($store_id)) {
  $query = $dbconn->prepare("SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE WHERE p.SHOP_CODE = ? ORDER BY p.SCORE DESC, p.CREATED_DATE DESC");
  $query->bind_param("s", $store_id);
} else {
  $query = $dbconn->prepare("SELECT p.*, s.CODE as STORE_CODE, s.NAME as STORE_NAME, s.THUMB_ID as STORE_THUMB_ID, s.LINK as STORE_LINK, s.TOTAL_FOLLOWER as TOTAL_FOLLOWER FROM PRODUCT p join SHOP s on p.SHOP_CODE = s.CODE ORDER BY p.SCORE DESC, p.CREATED_DATE DESC");
};
$query->execute();
$groups = $query->get_result();
$query->close();

$products = array();
while ($group = $groups->fetch_assoc()) {
  $products[] = $group;
};

$count_products = count($products);


if (substr($store_thumb_id, 0, 4) !== "http") {
  $store_thumb_id = "/nexilis/images/" . $store_thumb_id;
}

function lookUpCategoryName($id)
{

  // get shop category
  $dbconn = paliolite();
  $query = $dbconn->prepare("SELECT * FROM `SHOP_CATEGORY` sc WHERE sc.ID = ?");
  $query->bind_param("i", $id);
  $query->execute();
  $groups  = $query->get_result()->fetch_assoc();
  $query->close();

  return $groups["NAME"];
}

$store_category_name = array();
for ($i = 0; $i < count($store_category_raw); $i++) {
  $store_category_name[] = "<a href=\"" . base_url() . "palio_browser/pages/timeline?f_pin=" . $f_pin . "&filter=" . $store_category_raw[$i] . "\">" . lookUpCategoryName($store_category_raw[$i]) . "</a>";
}

$rand_bg = rand(1, 45) . ".webp";
?>
<!doctype html>
<html lang="en">

<head>
  <title><?php echo $store_name ?></title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <!-- <link rel="stylesheet" href="../assets/css/roboto.css" /> -->
  <link rel="stylesheet" href="../assets/css/gridstack.min.css" />
  <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../assets/css/style-profile.css?random=<?= time(); ?>" />

</head>

<body>
  <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction()">
  <!-- <div id="header-layout" class="sticky-top">
      <div id="header" class="row justify-content-between">
        <div class="col-auto">
        <i class="fas fa-arrow-left" style="color: #555555;" onclick="goBack()"></i>
        &ensp;
          <img id="header-logo" class="header-icon" src="../assets/img/PalioIcon.png">
          <span id="header-title" class="align-middle"><?php echo $store_name ?></h4>
        </div>
        <div class="col-auto">
          <img id="gear" class="header-icon d-none" src="../assets/img/jim_settings.png">
        </div>
      </div>
    </div>
    <img src="/nexilis/assets/img/idn_nature_<?php echo $rand_bg; ?>" style="display:none;" alt="" />
    <div class="profile-header jumbotron jumbotron-fluid mx-auto text-center" style="background-image:url(/nexilis/assets/img/idn_nature_<?php echo $rand_bg; ?>)">
        <img class="profile-picture rounded-circle" src="<?php echo $store_thumb_id ?>" style="width:120px; height:120px;">

        <?php if ($store_isVerified == 1) { ?>
        <div style="background-color: rgb(245, 245, 245); border-radius: 5px; width:fit-content; position:absolute; right: .75rem; padding: .05rem .35rem">
          <span style="font-size: .9rem;">
            <img src="../assets/img/icons/Verified-(Black).png" style="height:.9rem; width: auto;"> Verified
          </span>
        </div>
        <?php } ?>

    </div>
    <div class="container-fluid text-center">
      <div class="row justify-content-center my-2">
        <div class="col-3 profile-status">
          <h5 id="post-count" class="status-value"><?php echo $count_products; ?></h5>
          <p class="status-value">Postingan</p>
        </div>
        <div class="col-3 profile-status">
          <h5 id="follower-count" class="status-value"><?php echo $store_follower ?></h5>
          <p class="status-value">Pengikut</p>
        </div>
        <div class="col-3 profile-status">
          <h5 id="following-count" class="status-value">0</h5>
          <p class="status-value">Mengikuti</p>
        </div>
      </div>
    </div> -->

  <div class="search-header">
    <div class="col-12">
      <div class="row" style="background-color: #000000; padding: 10px 0 10px 0;">
        <div class="col-1">
          <a href="tab1-main.php">
            <img src="../assets/img/icons/Back-(White).png" style="width:30px">
          </a>
        </div>
        <div id="searchFilter-a" class="col-10 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
          <form id="searchFilterForm-a" action="search-result.html" method=POST style="width: 90%;">
            <div class="d-flex align-items-center div-search">
              <?php
              $query = "";
              if (isset($_REQUEST['query'])) {
                $query = $_REQUEST['query'];
              }
              ?>
              <img class="search-voice" id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png" width="20px">
              <input placeholder="Search" type="text" class="form-control search-box" name="search-bar" onclick="onFocusSearch()" value="<?= $query; ?>">
            </div>
          </form>
        </div>
        <a class="col-1" style="padding: 0; margin-left: -10px;" href="cart.php?v=<?= time(); ?>">
          <div class="position-relative">
            <img class="float-end" src="../assets/img/icons/Shopping-Cart-(White).png" style="width:30px">
            <span id="counter-here"></span>
          </div>
        </a>
      </div>
    </div>
  </div>

  <div class="profile-header">

  </div>

  <ul class="nav nav-fill" id="tab" role="tablist">
    <li class="nav-item">
      <a class="nav-link" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab" aria-controls="timeline" aria-selected="true">
        <!-- <img class = "tab-icon" src="../assets/img/feed.png"> -->
        Posts
        <hr class="tab-line">
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
        <!-- <img class = "tab-icon" src="../assets/img/tagged.png"> -->
        Shop
        <hr class="tab-line">
      </a>
    </li>
  </ul>
  <div class="tab-content" id="profileTabContent">
    <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
      <div class="d-none" id="no-stores">
        <div class="col-sm mt-2">
          <?php if ($store_isArtist) { ?>
            <h5 class="prod-name" style="text-align:center;">Tidak ada konten</h5>
          <?php } else { ?>
            <h5 class="prod-name" style="text-align:center;">Tidak ada produk</h5>
          <?php } ?>
        </div>
      </div>
      <div id="loading">
        <div class="col-sm mt-2">
          <h5 class="prod-name" style="text-align:center;">Sedang memuat...</h5>
        </div>
      </div>
      <div id="content-grid" class="grid-stack grid-stack-3">
      </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
      <div class="text-center">
        <p class="profile-subtitle">Tentang Kami</p>
        <hr class="profile-border">
      </div>
      <div class="container-fluid" id="info-container">
        <div class="row" style="border-bottom: 1px solid #e6e6e6;">
          <!-- <input class="col-auto text-left my-auto profile-input" placeholder="Deskripsi" disabled/> -->
          <textarea class="col-auto text-left my-auto profile-input" name="store_description" placeholder="Deskripsi" rows="4" style="width:100%;height:auto;" disabled><?php echo htmlspecialchars($store_desc); ?></textarea>
        </div>
      </div>
      <div class="text-center mt-2">
        <p class="profile-subtitle">Profil</p>
        <hr class="profile-border">
      </div>
      <div class="container-fluid" id="info-container">
        <div class="row">
          <div class="col-1">
            <img src="../assets/img/baseline_business_black_36dp.png" style="opacity:36%;">
          </div>
          <!-- <input class="col-auto text-left my-auto profile-input" placeholder="Industri" disabled/> -->
          <div class="col-10 ml-4 text-left my-auto">
            <?php
            echo implode(', ', $store_category_name);
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-1">
            <img src="../assets/img/baseline_email_black_36dp.png" style="opacity:36%;">
          </div>
          <?php if ($store_email != null && $store_email != "") { ?>
            <div class="col-10 ml-4 text-left my-auto">
              <?php
              echo '<a href="mailto:"' . $store_email . '">' . $store_email . '</a>';
              ?>
            </div>
          <?php } else { ?>
            <input class="col-10 ml-4 text-left my-auto profile-input" placeholder="Alamat Email" disabled />
          <?php } ?>
          <!-- <div class="col-10 ml-4 text-left my-auto">
              <?php
              // echo '<a href="mailto:"' . $store_email . '">' . $store_email . '</a>';
              ?>
            </div> -->
        </div>
        <div class="row">
          <div class="col-1">
            <img src="../assets/img/baseline_phone_black_36dp.png" style="opacity:36%;">
          </div>
          <input class="col-10 ml-4 text-left my-auto profile-input" value="<?php echo $phone_number; ?>" placeholder="Nomor Telepon" disabled />
        </div>
        <div class="row">
          <div class="col-1">
            <img src="../assets/img/ic_web_asset.png">
          </div>
          <?php if ($store_link != "" && $store_link != null) { ?>
            <div class="col-10 ml-4 text-left my-auto">
              <a href="javascript:openStore('<?php echo $store_code; ?>','<?php echo $store_link; ?>')">
                <?php echo $store_link; ?>
              </a>
            </div>
          <?php } else if ($store_isArtist) { ?>
            <input class="col-10 ml-4 text-left my-auto profile-input" placeholder="Situs Artis" disabled />
          <?php } else { ?>
            <input class="col-10 ml-4 text-left my-auto profile-input" placeholder="Situs Toko" disabled />
          <?php } ?>
        </div>
        <div class="row" style="border-bottom: 1px solid #e6e6e6;">
          <div class="col-1">
            <img src="../assets/img/baseline_home_black_36dp.png" style="opacity:36%;">
          </div>
          <?php if ($store_address != null && $store_address != "") { ?>
            <div class="col-10 ml-4 text-left my-auto">
              <?php
              echo $store_address;
              ?>
            </div>
          <?php } else if ($store_isArtist) { ?>
            <input class="col-10 ml-4 text-left my-auto profile-input" placeholder="Alamat Artis" disabled />
          <?php } else { ?>
            <input class="col-10 ml-4 text-left my-auto profile-input" placeholder="Alamat Toko" disabled />
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <!-- <script type="text/javascript" src="../assets/js/gridstack-h5.js"></script> -->
  <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
  <script>
    const store_id = "<?php echo $_GET['store_id'] ?>";
    const store_code = "<?php echo $store_code ?>";
    const be_id = "<?php echo $store_be ?>";
  </script>
  <script src="../assets/js/isInViewport.min.js"></script>
  <script type="text/javascript" src="../assets/js/script-profile.js?random=<?= time(); ?>"></script>
  <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>
  <script src="../assets/js/update_counter.js"></script>
</body>

</html>