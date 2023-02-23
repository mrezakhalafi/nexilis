<!doctype html>
<html lang="en">

<head>
  <title>Timeline</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/clean-switch.css" />
  <link rel="stylesheet" href="../assets/css/roboto.css" />
  <link rel="stylesheet" href="../assets/css/style-timeline.css?random=<?= time(); ?>" />
  <link rel="stylesheet" href="../assets/css/paliopay.css?random=<?= time(); ?>" />
  <script src="../assets/js/xendit.min.js"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jQueryRotate.js"></script>
  <script src="../assets/js/jquery.validate.js"></script>
  <script src="../assets/js/isInViewport.min.js"></script>
</head>

<body>
  <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction(true)">
  <div class="container-fluid">
    <div id="header-layout" class="sticky-top">
      <div id="header" class="row justify-content-between">
        <div class="col-auto">
          <img id="header-logo" class="header-icon" src="../assets/img/pb_palio_bni.png">
          <span id="header-title" class="align-middle">Produk-produk Hits</h4>
        </div>
        <div id="gear-div" class="col-auto">
          <span id="cart">
            <img class="header-icon" src="../assets/img/ShoppingCart1.png">
            <span id="cart-badge" class="badge badge-notify"></span>
          </span>
          <img id="gear" class="header-icon" src="../assets/img/jim_settings.png">
        </div>
      </div>
      <?php require('xpora_filter.php'); ?>
      <!-- <div id="searchFilter" class="d-none">
        <form id="searchFilterForm">
          <div id="searchbar">
            <div class="input-group">
              <?php
              $query = "";
              if (isset($_REQUEST['query'])) {
                $query = $_REQUEST['query'];
              }
              echo '<input id="query" type="text" class="search-query form-control" placeholder="Cari Produk" value="' . $query . '"/>';
              ?>
              <img id="mic" src="../assets/img/action_mic.png" onclick="voiceSearch()">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Cari</button>
              </div>
            </div>
          </div>
          <div>
            <label class="form-check-label cl-switch" for="switchAll">
              <span class="label">Semua</span>
              <input class="checkbox-filter form-check-input" type="checkbox" id="switchAll" <?php echo (isset($_REQUEST["filter"]) && $_REQUEST["filter"] != "1-2-3-4" ? '' : 'checked'); ?>>
              <span class="switcher"></span>
            </label>
          </div>
          <div>
            <div class="form-check-inline">
              <input class="checkbox-filter form-check-input" name="category[]" type="checkbox" id="checkboxKuliner" <?php echo (isset($_REQUEST["filter"]) && strpos($_REQUEST["filter"], '1') === false ? '' : 'checked'); ?>>
              <label class="form-check-label" for="checkboxKuliner">Kuliner</label>
            </div>
            <div class="form-check-inline">
              <input class="checkbox-filter form-check-input" name="category[]" type="checkbox" id="checkboxKriya" <?php echo (isset($_REQUEST["filter"]) && strpos($_REQUEST["filter"], '3') === false ? '' : 'checked'); ?>>
              <label class="form-check-label" for="checkboxKriya">Kriya</label>
            </div>
            <div class="form-check-inline">
              <input class="checkbox-filter form-check-input" name="category[]" type="checkbox" id="checkboxFashion" <?php echo (isset($_REQUEST["filter"]) && strpos($_REQUEST["filter"], '2') === false ? '' : 'checked'); ?>>
              <label class="form-check-label" for="checkboxFashion">Busana</label>
            </div>
            <div class="form-check-inline">
              <input class="checkbox-filter form-check-input" name="category[]" type="checkbox" id="checkboxLain" <?php echo (isset($_REQUEST["filter"]) && strpos($_REQUEST["filter"], '0') === false ? '' : 'checked'); ?>>
              <label class="form-check-label" for="checkboxLain">Lain-lain</label>
            </div>
          </div>
          <div id="checkboxGroup"></div>
        </form>
      </div> -->
      <div id="story-container">
        <?php require('timeline_story_container.php'); ?>
      </div>
    </div>
    <div class="timeline" id="pbr-timeline">
      <?php require('timeline_products.php'); ?>
    </div>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="../assets/js/script-filter.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score-shop.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/update-score.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/script-timeline.js?random=<?= time(); ?>"></script>

  <script src="../assets/js/paliopay-dictionary.js?random=<?= time(); ?>"></script>
  <script src="../assets/js/paliopay.js?random=<?= time(); ?>"></script>
</body>

</html>