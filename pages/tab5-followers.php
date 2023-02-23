<?php

	// KONEKSI

	include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
	$dbconn = paliolite();
  session_start();

	// ID SHOP GET

	if (!isset($_SESSION['id_shop'])){
		$id_shop = $_GET['id'];
		$_SESSION['id_shop'] = $id_shop;
	}else{
		$id_shop = $_SESSION["id_shop"];
	}

  $id_user = $_SESSION["user_f_pin"];

  // ID SHOP CHECK

	if (!isset($id_shop)) {
		die("ID Shop Tidak Diset.");
	}

	// SELECT SHOP DATA

	$query = $dbconn->prepare("SELECT * FROM SHOP_FOLLOW LEFT JOIN USER_LIST ON SHOP_FOLLOW.F_PIN = 
                              USER_LIST.F_PIN WHERE STORE_CODE = '$id_shop' ORDER BY 
                              SHOP_FOLLOW.FOLLOW_DATE DESC");
	$query->execute();
	$shop_follow = $query->get_result();
	$query->close();

  require '../logics/tab5/followers.php';

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

  <title>Nexilis Sports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background" style="display:none">
  <img src="../assets/img/lbackground_6.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light navbar-shop-manager">
    <div class="container">
      <a href="tab5-shop-manager.php">
        <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-black">
      </a>
      <p class="navbar-title-2" data-translate="tab5followers-1">Followers</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="navbar-img-slot">
      </div>
    </div>
  </nav>

  <!-- SECTION FOLLOWERS DATE -->

  <div class="row gx-0">
    <div class="col-5 col-md-5 col-lg-5 col-xl-7"></div>
    <div class="col-7 col-md-7 col-lg-4 col-xl-4 d-flex justify-content-end">
      <input type="text" readonly="readonly" class="datepicker tbl-calendar-date" style="text-align: right; margin-right: 25px;"  
        value="Today : <?= date('d F Y') ?>">
      <img src="../assets/img/tab5/Down-(Black).png" class="small-arrow-calendar" 
        style="margin-left: -10px" onclick="show_datepicker()">
    </div>
  </div>

  <!-- SECTION FOLLOWERS GRAPH -->

  <div class="section-followers">
    <div class="container-fluid">
      <div class="graph-data shadow-sm">
          <div class="container">
            <div class="row">
              <span class="graph-data-title">
                <b><?= mysqli_num_rows($shop_follow) ?></b>
              </span>
            </div>
          </div>
          <div class="container">  
            <div class="graph-data-desc">
              <span class="text-green">
                <b>+<?= $day_followers1 ?></b>
              </span>
              <span class="text-grey" data-translate="tab5followers-2">Since yesterday</span>
            </div>
          </div>
          <div class="container">
            <div class="row d-flex justify-content-center">

              <!-- LOOPING GRAPH FOR WEEK -->

              <?php $i = 1; ?>
              <?php for ($x = -6; $x <= 0; $x++){ ?>

              <div class="col-1 col-md-1 col-lg-1 graph-one-bar">
                <div class="progress-2">

                  <!-- GRAPH COLOUR CHECK (TODAY = PURPLE) -->

                  <?php if ($x==0): ?>

                    <div class="progress-bar-purple" role="progressbar" aria-valuenow="10" id="progress-1" 
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers1/1000)*100 ?>%">
                    </div>

                  <?php else: ?>

                  <?php if ($x == -1): ?>

                    <div class="progress-bar-2" role="progressbar" aria-valuenow="10" id="progress-2" 
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers2/1000)*100 ?>%">
                    </div>

                  <?php elseif($x == -2): ?>

                    <div class="progress-bar-2" role="progressbar" aria-valuenow="10" id="progress-3" 
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers3/1000)*100 ?>%">
                    </div>

                  <?php elseif($x == -3): ?>

                    <div class="progress-bar-2" role="progressbar" aria-valuenow="10" id="progress-4" 
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers4/1000)*100 ?>%">
                    </div>

                  <?php elseif($x == -4): ?>

                    <div class="progress-bar-2" role="progressbar" aria-valuenow="10" id="progress-5"  
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers5/1000)*100 ?>%">
                    </div>

                  <?php elseif($x == -5): ?>

                    <div class="progress-bar-2" role="progressbar" aria-valuenow="10" id="progress-6" 
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers6/1000)*100 ?>%">
                    </div>

                  <?php elseif($x == -6): ?>

                    <div class="progress-bar-2" role="progressbar" aria-valuenow="10" id="progress-7" 
                      aria-valuemin="0" aria-valuemax="100" style="width:<?= ($day_followers7/1000)*100 ?>%">
                    </div>

                  <?php endif; ?>

                <?php endif; ?>

                </div>
                <span class="text-grey smallest-text graph-bottom-desc" id="date-text-<?= $i++ ?>">
                  <?= date('d/m', strtotime("$x day")); ?>
                </span>
              </div>

              <?php } ?>

              <div class="col-3 col-md-3 col-lg-3 d-flex justify-content-end graph-right">
                <div class="graph-right-desc">
                  <p class="smallest-text text-grey">1000</p>
                  <p class="smallest-text text-grey">500</p>
                  <p class="smallest-text text-grey">0</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION RECENT ACTIVITIES -->

  <div class="section-recent-activities">
    <div class="container recent-activities-title">
      <p class="text-purple small-text" data-translate="tab5followers-3">Recent Activities</p>
    </div>
    <div class="container">

      <!-- IF USER HAVE FOLLOWERS -->

      <?php if (mysqli_num_rows($shop_follow) > 0): ?>

        <?php foreach ($shop_follow as $follow):

          // SELECT USER - SHOP FOLLOW

          $query = $dbconn->prepare("SELECT * FROM USER_FOLLOW WHERE F_PIN = '".$follow['F_PIN']."'
                                    AND STORE_CODE = '".$id_shop."'");
          $query->execute();
          $follback_check = $query->get_result()->fetch_assoc();
          $query->close(); ?>

          <div class="row small-text one-followers">
            <div class="col-1 col-md-1 col-lg-1" style="margin-right: 10px;">
              
              <?php if ($follow['IMAGE']): ?>
                <?php if ($follow['F_PIN'] == $id_user): ?>
                  <a href="tab5-main">
                    <img src="https://newuniverse.io/filepalio/image/<?= $follow['IMAGE'] ?>" class="followers-ava">
                  </a>
                <?php else: ?>
                  <a href="tab5-profile?id_visit=<?= $follow['F_PIN'] ?>">
                    <img src="https://newuniverse.io/filepalio/image/<?= $follow['IMAGE'] ?>" class="followers-ava">
                  </a>
                <?php endif; ?>
              <?php else: ?>
                <?php if ($follow['F_PIN'] == $id_user): ?>
                  <a href="tab5-main">
                    <img src="../assets/img/tab5/profile.png" class="followers-ava">
                  </a>
                <?php else: ?>
                  <a href="tab5-profile?id_visit=<?= $follow['F_PIN'] ?>">
                    <img src="../assets/img/tab5/profile.png" class="followers-ava">
                  </a>
                <?php endif; ?>
              <?php endif; ?>

            </div>
            <div class="col-7 col-md-7 col-lg-8" style="margin-right: 20px;">

              <?php if ($follow['F_PIN'] == $id_user): ?>
                <a href="tab5-main">
                  <div><?= $follow['FIRST_NAME']." ".$follow['LAST_NAME'] ?><span data-translate="tab5followers-4"> started following you.<span></div>
                </a>
              <?php else: ?>
                <a href="tab5-profile?id_visit=<?= $follow['F_PIN'] ?>">
                  <div><?= $follow['FIRST_NAME']." ".$follow['LAST_NAME'] ?><span data-translate="tab5followers-4"> started following you.<span></div>
                </a>
              <?php endif; ?>
                
              <div class="smallest-text text-grey"><?= date('d/m/y', $follow['FOLLOW_DATE']/1000) ?></div>
            </div>
            <div class="col-2 col-md-2 col-lg-2">
              
              <?php if (!isset($follback_check)): ?>
                <form action="../logics/tab5/shop_following" method="POST">
                  <input type="hidden" name="f_pin" value="<?= $follow['F_PIN'] ?>">
                  <input type="hidden" name="store_code" value="<?= $id_shop ?>">
                  <button class="btn-follow" data-translate="tab5followers-5">Follow</button>
                </form>
              <?php else: ?>
                <form action="../logics/tab5/shop_unfollow" method="POST">
                  <input type="hidden" name="f_pin" value="<?= $follow['F_PIN'] ?>">
                  <input type="hidden" name="store_code" value="<?= $id_shop ?>">
                  <button class="btn-follow" style="padding-left: 8px; padding-right: 8px" data-translate="tab5followers-7">Unfollow</button>
                </form>
              <?php endif; ?>

            </div>
          </div>

          <?php endforeach; ?>

        <?php else: ?>
        
          <p class="text-center small-text mt-5" data-translate="tab5followers-6">Anda belum memiliki followers.</p>

        <?php endif; ?>

      </div>
    </div>
  </div>

  <input type="hidden" id="id_shop" value="<?= $id_shop ?>">

</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function(){
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

    if (localStorage.lang == 1){
      $(".tbl-calendar-date").each(function() {
        var text = $(this).val();
        text = text.replace("Today : ", "Hari ini : ");
        $(this).val(text);
      });
    }

		changeLanguage();
    $('body').show();
	});
  
  // SCRIPT DATEPICKER

  $('.datepicker').datepicker({
    format: "dd MM yyyy",
    autoclose: true,
  });
  
  function show_datepicker(){
    $(".datepicker").datepicker('show');
  }

  // ON CHANGE DATEPICKER CHANGE SUB DATE
  
  $('.datepicker').change(function() { 

    for (var i=1; i<8; i++){
      var date = $(this).datepicker('getDate');
      date.setDate(date.getDate()+(-7+i)); 

      $('#date-text-'+i).text($.datepicker.formatDate("dd/mm", date));
    }

    // REFRESH GRAPH FROM XMLHTTPREQUEST

    var formData = new FormData();

    var id_shop = $('#id_shop').val();
    var new_date = $.datepicker.formatDate("yy-mm-dd", date);

    formData.append('id_shop', id_shop);
    formData.append('date', new_date);

    console.log(new_date);
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function (){

      if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

        var array_result = xmlHttp.responseText.split(",");
        console.log(array_result);

        $('#progress-1').css('width',(array_result[0]/1000)*100+"%");
        $('#progress-2').css('width',(array_result[1]/1000)*100+"%");
        $('#progress-3').css('width',(array_result[2]/1000)*100+"%");
        $('#progress-4').css('width',(array_result[3]/1000)*100+"%");
        $('#progress-5').css('width',(array_result[4]/1000)*100+"%");
        $('#progress-6').css('width',(array_result[5]/1000)*100+"%");
        $('#progress-7').css('width',(array_result[6]/1000)*100+"%");
      }
    }
    xmlHttp.open("post", "../logics/tab5/update_followers");
    xmlHttp.send(formData);
      
  });
  
</script>
</html>