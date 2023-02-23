<?php
  if(!isset($_GET['store_id'])){
    die();
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Daftar Penjualan</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/roboto.css" />
    <link rel="stylesheet" href="../assets/css/gridstack.min.css"/>
    <link rel="stylesheet" href="../assets/css/gridstack-extra.min.css"/>
    <link rel="stylesheet" href="../assets/css/style-profile.css?random=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../assets/css/style-history_merchant.css?random=<?php echo time(); ?>" />
  </head>
  <body>
    <img id="scroll-top" class="rounded-circle" src="../assets/img/ic_collaps_arrow.png" onclick="topFunction()">
    <div id="header-layout" class="sticky-top">
      <div id="header" class="row justify-content-between">
        <div class="col-auto">
          <img id="header-logo" class="header-icon" src="../assets/img/PalioIcon.png">
          <span id="header-title" class="align-middle">Daftar Penjualan</h4>
        </div>
      </div>
    </div>
    <div class="container-fluid mt-3" id="history-container">
      <div class="row mt-5 d-none" id="no-history">
        <div class="col-sm-12 text-center">
          <h5>Tidak ada penjualan</h5>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
    <!-- <script type="text/javascript" src="../assets/js/gridstack-h5.js"></script> -->
    <script type="text/javascript" src="../assets/js/script-merchant_history.js?random=<?php echo time(); ?>"></script>
    <script type="text/javascript" src="../assets/js/gridstack-static.js"></script>
    <script src="../assets/js/isInViewport.min.js"></script>
    <!-- <script type="text/javascript" src="../assets/js/script-profile.js?random=<?= time(); ?>"></script> -->
    <script type="text/javascript" src="../assets/js/pulltorefresh.js"></script>
  </body>
</html> 