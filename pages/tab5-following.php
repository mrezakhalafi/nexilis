<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
	session_start();

  // GET USER FROM SESSION

  if (!isset($_SESSION['user_f_pin'])){
    $id_user = $_GET['f_pin'];
    $_SESSION['user_f_pin'] = $id_user;
  }else{
    $id_user = $_SESSION["user_f_pin"];
  }

  // CHECK USER

  if (!isset($id_user)) {
    die("ID User Tidak Diset.");
  }

	// SELECT USER FOLLOWING

  // IF SEARCH IS ACTIVE

  if (isset($_GET['query'])){

    $search_query = $_GET['query'];

    $query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW LEFT JOIN SHOP ON SHOP.CODE = 
                                SHOP_FOLLOW.STORE_CODE WHERE SHOP_FOLLOW.F_PIN = '$id_user' AND 
                                SHOP.NAME LIKE '%".$search_query."%' ORDER BY SHOP_FOLLOW.FOLLOW_DATE DESC");
    $query->execute();
    $user_following_a = $query->get_result();
    $query->close();

    $query = $dbconn->prepare("SELECT * FROM FOLLOW_LIST LEFT JOIN USER_LIST ON FOLLOW_LIST.L_PIN = 
                                USER_LIST.F_PIN WHERE FOLLOW_LIST.F_PIN = '$id_user' AND USER_LIST.FIRST_NAME LIKE
                                '%".$search_query."%'");
    $query->execute();
    $user_following_b = $query->get_result();
    $query->close();  

  }else{

    $query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW LEFT JOIN SHOP ON SHOP.CODE = 
                              SHOP_FOLLOW.STORE_CODE WHERE SHOP_FOLLOW.F_PIN = '$id_user' 
                              ORDER BY SHOP_FOLLOW.FOLLOW_DATE DESC");
    $query->execute();
    $user_following_a = $query->get_result();
    $query->close();

    $query = $dbconn->prepare("SELECT FOLLOW_LIST.*, USER_LIST.*, FOLLOW_LIST.CREATED_DATE AS CD FROM FOLLOW_LIST LEFT JOIN USER_LIST ON FOLLOW_LIST.L_PIN = 
                              USER_LIST.F_PIN WHERE FOLLOW_LIST.F_PIN = '$id_user' ORDER BY FOLLOW_LIST.CREATED_DATE DESC");
    $query->execute();
    $user_following_b = $query->get_result();
    $query->close();   
      
  }

  // SELECT COLOR CONFIGURATION

	$query = $dbconn->prepare("SELECT * FROM NEXILIS_CONFIGURATION WHERE ID = '2'");
	$query->execute();
	$nexilis_configuration = $query->get_result()->fetch_assoc();
	$query->close();

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Nexilis Sports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href="../assets/css/tab5-collection-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <script src="../assets/js/wishlist.js?v=<?php echo time(); ?>"></script>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_15.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; border-bottom: 1px solid #d1d5db">
    <div class="container">
      <a href="tab5.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
      </a>
      <p class="navbar-title" data-translate="tab5following-1" style="color: #000000">Following</p>
      <div id="searchBar" class="col-9 col-md-9 col-lg-9 d-flex align-items-center justify-content-center text-white pl-2 pr-2">
        <form id="searchFilterForm-a" action="tab5-following" method="GET" style="border: 1px solid #8a8a8a; width: 95%;">

          <?php
            $query = "";
            if (isset($_REQUEST['query'])) {
              $query = $_REQUEST['query'];
              
            }
          ?>

          <input id="query" placeholder="Search" type="text" class="search-query" name="query">
          <img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">
          <img id="voice-search" onclick="voiceSearch()" src="../assets/img/icons/Voice-Command-(Black).png">
        </form>
      </div>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img src="../assets/img/tab5/search-black.png" class="search-white-right">
      </div>
    </div>
  </nav>

  <!-- SECTION RECENT ACTIVITIES -->

  <div class="section-recent-activities">
    <div class="container recent-activities-title">
      <p class="text-purple small-text" data-translate="tab5following-2">Recent Activities</p>
    </div>
    <div class="container">

      <!-- IF USER HAVE FOLLOWERS -->

      <?php if (mysqli_num_rows($user_following_a) > 0 || mysqli_num_rows($user_following_b) > 0): ?>

      <?php foreach($user_following_a as $follow): ?>

        <?php $images = explode('|', $follow['THUMB_ID'] ); 
        $date = date_create($follow['CD']); ?>

        <div class="row small-text one-followers">
          <div class="col-1 col-md-1 col-lg-1" style="margin-right: 10px;">
            <a href="tab3-profile.php?store_id=<?= $follow['CODE'] ?>&f_pin=<?= $id_user ?>">
              <img src="../images/<?= $images[0] ?>" class="followers-ava" style="object-fit: cover; height: 30px; border-radius: 50%">
            </a>
          </div>
          <div class="col-7 col-md-7 col-lg-7" style="margin-right: 20px;">
            <a href="tab3-profile.php?store_id=<?= $follow['CODE'] ?>&f_pin=<?= $id_user ?>">
              <div><?= $follow['NAME'] ?></div>
            </a>
            <div class="smallest-text text-grey"><?= date('d/m/y', $follow['FOLLOW_DATE']/1000) ?></div>
            </div>
          <div class="col-3 col-md-3 col-lg-3">
            <input type="hidden" name="f_pin" value="<?= $id_user ?>">
            <input type="hidden" name="shop_id" value="<?= $follow['CODE'] ?>">
            <button class="btn-follow" onclick="openModal1('<?= $follow['NAME'] ?>','<?= $follow['CODE'] ?>')" type="button" style="padding-left:5px; padding-right:5px" data-translate="tab5following-3" data-toggle="modal" data-target="#unfollowModal1">Unfollow</button>
          </div>
        </div>

        <?php endforeach; ?>

        <?php foreach($user_following_b as $follow): ?>

          <?php $images = explode('|', $follow['IMAGE'] ); ?>

          <div class="row small-text one-followers">
            <div class="col-1 col-md-1 col-lg-1" style="margin-right: 10px;">
              <a href="tab5-profile.php?id_visit=<?= $follow['F_PIN'] ?>&f_pin=<?= $id_user ?>">
                
                <?php if ($follow['IMAGE']): ?>
                  <img src="https://newuniverse.io/filepalio/image/<?= $images[0] ?>" class="followers-ava" style="object-fit: cover; height: 30px; border-radius: 50%">
                <?php else: ?>
                  <img src="../assets/img/tab5/no-avatar.jpg" class="followers-ava" style="object-fit: cover; height: 30px; border-radius: 50%">
                <?php endif; ?>
                
              </a>
            </div>
            <div class="col-7 col-md-7 col-lg-7" style="margin-right: 20px;">
              <a href="tab5-profile.php?id_visit=<?= $follow['F_PIN'] ?>&f_pin=<?= $id_user ?>">
                <div><?= $follow['FIRST_NAME'] ?></div>
              </a>
              <div class="smallest-text text-grey"><?= date_format($date, 'd/m/y'); ?></div>
              </div>
            <div class="col-3 col-md-3 col-lg-3">
              <input type="hidden" name="f_pin" value="<?= $id_user ?>">
              <input type="hidden" name="l_pin" value="<?= $follow['F_PIN'] ?>">
              <button class="btn-follow" onclick="openModal2('<?= $follow['FIRST_NAME'] ?>','<?= $follow['F_PIN'] ?>')" type="button" style="padding-left:5px; padding-right:5px" data-translate="tab5following-3" data-toggle="modal" data-target="#unfollowModal2">Unfollow</button>
            </div>
          </div>

          <?php endforeach; ?>

      <?php else: ?>
      
        <p class="text-center small-text mt-5" data-translate="tab5following-4">You havent followed anyone yet.</p>

      <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- UNFOLLOW CONFIRMATION MODAL -->

  <div class="modal fade" id="unfollowModal1" tabindex="-1" role="dialog" aria-labelledby="unfollowModal1Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="height:100%">
        <div class="modal-body">
          <span data-translate="tab5following-5" style="font-size: 14px">Are you sure to unfollow</span><span style="font-size: 14px" id="text-shop-name1"></span><span style="font-size: 14px">?</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()" style="font-size: 14px; color: #000000; border: 1px solid #000000; background-color: #FFFFFF" data-dismiss="modal" data-translate="tab5following-7">No</button>
          <form action="../logics/tab5/unfollow_shop" method="POST">
            <input type="hidden" name="f_pin" value="<?= $id_user ?>">
            <input type="hidden" name="shop_id" id="shop_id" value="">
            <button type="submit" class="btn" style="font-size: 14px; color: #FFFFFF; background-color: #000000" data-translate="tab5following-6">Yes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="unfollowModal2" tabindex="-1" role="dialog" aria-labelledby="unfollowModal2Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="height:100%">
        <div class="modal-body">
          <span data-translate="tab5following-5" style="font-size: 14px">Are you sure to unfollow</span><span style="font-size: 14px" id="text-shop-name2"></span><span style="font-size: 14px">?</span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeModal()" style="font-size: 14px; color: #000000; border: 1px solid #000000; background-color: #FFFFFF" data-dismiss="modal" data-translate="tab5following-7">No</button>
          <form action="../logics/tab5/user_unfollow?src=following" method="POST">
            <input type="hidden" name="f_pin" value="<?= $id_user ?>">
            <input type="hidden" name="l_pin" id="l_pin" value="">
            <button type="submit" class="btn" style="font-size: 14px; color: #FFFFFF; background-color: #000000" data-translate="tab5following-6">Yes</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

  //  SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
    function changeLanguage() {

      var lang = localStorage.lang;
      change_lang(lang);

    }
    changeLanguage();
    $('body').show();
  });

  // OPEN & CLOSE MODAL

  function openModal1(name, code){
    $('#unfollowModal1').modal('show');
    $('#text-shop-name1').text(name);
    $('#shop_id').val(code);
  }

  function openModal2(name, code){
    $('#unfollowModal2').modal('show');
    $('#text-shop-name2').text(name);
    $('#l_pin').val(code);
  }

  function closeModal(){
    $('#unfollowModal1').modal('hide');
    $('#unfollowModal2').modal('hide');
  }

  // SCRIPT SEARCH

  $('#searchBar').attr('style','display:none !important');

  $(".search-white-right").click(function() {
    $('.navbar-title').hide();
    $('#searchBar').attr('style','display:block !important');
  });

  <?php
    if (isset($_GET['query'])){
      echo("
      $('.navbar-title').hide();
      $('#searchBar').attr('style','display:block !important');

      $('#query').val(localStorage.getItem('search_keyword'));
      $('#delete-query').removeClass('d-none');
      ");
    }
  ?>

  // FUNCTION SAVE SEARCH

  $('#query').on('change', function() {
    localStorage.setItem("search_keyword", this.value);
  });

  // FUNCTION X ON SEARCH

  $("#delete-query").click(function () {
    $('#query').val('');
    // localStorage.setItem("search_keyword", "");
    // $('#delete-query').addClass('d-none');
    window.location = 'tab5-following.php';
  })

  $('#query').keyup(function () {

    console.log('is typing: ' + $(this).val());

    if ($(this).val() != '') {
      $('#delete-query').removeClass('d-none');
    } else {
      $('#delete-query').addClass('d-none');
    }

  })

  // FUNCTION VOICE SEARCH

	function voiceSearch(){
		Android.toggleVoiceSearch();
	}

	function submitVoiceSearch(searchQuery){
    $('#query').val(searchQuery);
    $('#delete-query').removeClass('d-none');
	}


</script>
</html>