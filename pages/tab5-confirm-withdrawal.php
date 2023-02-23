<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Project</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  <link href="../assets/css/tab5-style.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

<body class="bg-purple"> 

<!-- SECTION SUCCESS OPEN SHOP -->

<div class="section-success-shop text-center align-middle">
  <img class="success-shop-image" src="../assets/img/tab5/Withdrawal-Success.png">
  <p class="success-shop-title text-center">
    <b>Rp 4,650,000</b>
  </p>
  <div class="small-text">
    <span data-translate="tab5finance-11">Will be deposited in your bank</span>
  </div> 
  <div class="small-text">
    <span data-translate="tab5finance-12">In 1-2 business days.</span>
  </div>
  <a href="tab5-shop-manager.php">
  <button class="btn-upload-listing">
    <b data-translate="tab5finance-13">Done</b>
  </button>
  </a>
</div>

<!-- FOOTER -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="module" src="../assets/js/translate.js"></script>

<script>

	// SCRIPT CHANGE LANGUAGE

	$(document).ready(function() {
		function changeLanguage(){

		var lang = localStorage.lang;	
		change_lang(lang);
		
		}

		changeLanguage();

	});

</script>

</html>
