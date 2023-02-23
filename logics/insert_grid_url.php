<?php

  include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
  $dbconn = paliolite();

  // GET USER PIN

  session_start();

  $targetLoc =  $_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/';
  $targetDir = array();

  $link_id = rand(0000000000, 9999999999);

  $imageUploader = strtolower(pathinfo($_FILES["choose-img"]["name"],PATHINFO_EXTENSION));
  $fotoProfile = "linkURL_" . $link_id .  ".webp";
  $targetDir["choose-img"] = $targetLoc . $fotoProfile;

  $uploadPhoto = true;
  $uploadPhoto = move_uploaded_file($_FILES["choose-img"]["tmp_name"], $targetDir["choose-img"]);

  $f_pin = '0275f69fe1';
  $link_img = $fotoProfile;
  $link_url = $_POST['link_url'];

  $queryGridUrl = "INSERT INTO grid_url (F_PIN, LINK_IMG, LINK_URL, LINK_ID) VALUES ('".$f_pin."', '".$link_img."', '".$link_url."', '".$link_id."')";

  if (mysqli_query($dbconn, $queryGridUrl)) {
      echo "Koneksi DB Berhasil";
  }
  else {
      echo "INSERT INTO grid_url (F_PIN, LINK_IMG, LINK_URL, LINK_ID) VALUES ('".$f_pin."', '".$link_img."', '".$link_url."', '".$link_id."')";
  }

?>
