<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Palio Browser</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/jQueryRotate.js"></script>
  <script src="../assets/js/jquery.validate.js"></script>
  <script src="../assets/js/isInViewport.min.js"></script>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/clean-switch.css" />
  <link rel="stylesheet" href="../assets/css/roboto.css" />
  <link rel="stylesheet" href="../assets/css/style-store_list.css?random=<?= time(); ?>">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/c6d7461088.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../assets/css/gridstack.min.css"/>
  <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css"/>
  <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
  <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>
</head>

<body>
  <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction()">
  <div class="container-fluid">
    <div id="header-layout" class="sticky-top">
      <div id="header" class="row justify-content-between">
        <div class="col-auto">
          <img id="header-logo" class="header-icon" src="../assets/img/PalioIcon.png">
          <span id="header-title" class="align-middle">Toko-toko Hits</h4>
        </div>
        <div class="col-auto">
          <img id="gear" class="header-icon" src="../assets/img/jim_settings.png">
        </div>
      </div>
      <?php require('filter.php'); ?>
    </div>
  </div>
  <div id="container">
    <div id="loading">
      <div class="col-sm mt-2">
        <h5 class="prod-name" style="text-align:center;">Sedang memuat. Tunggu sebentar...</h5>
      </div>
    </div>
    <div class="d-none" id="no-stores">
      <div class="col-sm mt-2">
        <h5 class="prod-name" style="text-align:center;">Tidak ada konten yang sesuai kriteria</h5>
      </div>
    </div>
    <div id="content-grid" class="grid-stack grid-stack-3">
    </div>
  </div>
  <script>
    const search = <?php if( isset($_GET['query']) ){ echo '"' . $_GET['query'] . '"' ; } else { echo "null";} ?>;
    const filter = <?php if( isset($_GET['filter']) ){ echo '"' . $_GET['filter'] . '"'; } else { echo "null";} ?>;
  </script>
  <script type="text/javascript" src="../assets/js/script-filter.js?random=<?= time(); ?>"></script>
  <script type="text/javascript" src="../assets/js/script-store_list.js?random=<?= time(); ?>"></script>
</body>