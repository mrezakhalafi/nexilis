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

  // ID USER CHECK

  if (!isset($id_user)) {
    die("ID User Tidak Diset.");
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
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>

<body class="bg-white-background">
  <img src="../assets/img/lbackground_16.png" style="opacity: 0.5; position: fixed; left: 0; top: 0; width: 100%; height: auto; z-index: -9999">

  <!-- NAVBAR -->

  <nav class="navbar navbar-light" style="background-color: <?= $nexilis_configuration['COLOR_PALETTE'] ?>; border-bottom: 1px solid #d1d5db">
    <div class="container">
      <a href="tab5.php">
          <img src="../assets/img/tab5/Back-(Black).png" class="navbar-back-white">
      </a>
      <p class="navbar-title" style="color: #000000">Privacy Policy</p>
      <div class="navbar-brand pt-2 navbar-brand-slot">
        <img class="search-white-right">
      </div>
  </div>
  </nav>

  <!-- SECTION PRIVACY POLICY -->

  <!-- ENGLISH SECTION -->

  <div class="privacy-policy-english" style="font-size:11px">
    <div class="container mt-2">
      <div class="col-md-12 py-2" style="padding-right: 20px; margin-bottom: 50px">
        <ol type="1">
          <li class="fontRobBold fs-25 my-4"><b>Policy Perimeter and Definitions</b>
            <p class="fontRobLite fsz-15 my-2">
              This Privacy Policy applies to New Universe Enterprise online workplace productivity tools and platform, including the associated New Universe Enterprise mobile and New Universe Enterprise websites, you may have with New Universe Enterprise. If you do not agree with the terms, do not access or use the Services or Websites aspect of New Universe Enterprise business.<br>
              This Privacy Policy does not apply to any third party applications or software that integrate with the Services through the New Universe Enterprise platform, or any other third party products, services or businesses. In addition, a separate agreement governs delivery, access and use of the Services, including the processing of any messages, files or other content submitted through Services accounts. The organization that entered into the Customer Agreement controls their instance of the Services and any associated Customer Data.
            </p>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Information We Collect And Receive</b>
            <ol type="a" class="fontRobLite fsz-15">
              <li class="my-2">
                Workspace and Account Information. To create or update a Workspace account, you or your Customer supply New Universe Enterprise with an email address, phone number, password, domain and/or similar account details.
              </li>
              <li class="my-2">Usage Information
                <ul type="disc">
                  <li class="my-2">
                    <b>Services Metadata.</b> When an Authorized User interacts with the Services, metadata is generated that provides additional context about the way Authorized Users work.
                  </li>
                  <li class="my-2">
                    <b>Log data.</b> As with most websites and technology services delivered over the Internet, our servers automatically collect information when you access or use our Websites or Services and record it in log files. This log data may include the Internet Protocol (IP) address, the address of the web page visited before using the Website or Services, browser type and settings, the date and time the Services were used, information about browser configuration and plugins, language preferences and cookie data.
                  </li>
                  <li class="my-2">
                    <b>Device information.</b> New Universe Enterprise collects information about devices accessing the Services, including type of device, what operating system is used, device settings, application IDs, unique device identifiers and crash data. Whether we collect some or all of this Other Information often depends on the type of device used and its settings.
                  </li>
                  <li class="my-2">
                    <b>Location information.</b> We receive information from you, your Customer and other third-parties that helps us approximate your location.
                  </li>
                </ul>
              </li>
              <li class="my-2">
                Cookie Information. New Universe Enterprise uses cookies and similar technologies in our Websites and Services that help us collect Other Information.
              </li>
              <li class="my-2">
                Contact Information. In accordance with the consent process provided by your device, any contact information that an Authorized User chooses to import (such as an address book from a device) is collected when using the Services.
              </li>
              <li class="my-2">
                Third Party Data. New Universe Enterprise may receive data about organizations, industries, Website visitors, marketing campaigns and other matters related to our business from parent corporation(s), affiliates and subsidiaries, our partners or others that we use to make our own information better or more useful.
              </li>
              <li class="my-2">
                We receive Other Information when submitted to our Websites or if you participate in a focus group, contest, activity or event, apply for a job, request support, interact with our social media accounts.
              </li>
            </ol>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>How We Use Information</b>
            <p class="fontRobLite fsz-15 my-2">
              New Universe Enterprise uses Information in furtherance of our legitimate interests in operating our Services, Websites and business. More specifically :  
            </p>
            <ol type="a" class="fontRobLite fsz-15">
              <li class="my-2">To provide, update, maintain and protect our Services, Websites and business.</li>
              <li class="my-2">As required by applicable law, legal process or regulation.</li>
              <li class="my-2">To communicate with you by responding to your requests, comments and questions.</li>
              <li class="my-2">To develop and provide search, learning and productivity tools and additional features.</li>
              <li class="my-2">To send emails and other communications.</li>
              <li class="my-2">For billing, account management and other administrative matters.</li>
              <li class="my-2">To investigate and help prevent security issues and abuse.</li>
            </ol>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>How We Share And Disclose Information</b>
            <p class="fontRobLite fsz-15 my-2">
              Customers determine their own policies and practices for the sharing and disclosure of Information, and New Universe Enterprise does not control how they or any other third parties choose to share or disclose Information.
            </p>
            <ol type="a" class="fontRobLite fsz-15">
              <li class="my-2">
                <b>Customer’s Instructions.</b> New Universe Enterprise will solely share and disclose Customer Data in accordance with a Customer’s instructions, including any applicable terms.
              </li>
              <li class="my-2">
                <b>Displaying the Services.</b> When an Authorized User submits Other Information, it may be displayed to other Authorized Users in the same or connected Workspaces.
              </li>
              <li class="my-2">
                <b>Collaborating with Others.</b> The Services provide different ways for Authorized Users working in independent Workspaces to collaborate, such as shared channels.
              </li>
              <li class="my-2">
                <b>Customer Access.</b> Owners, administrators, Authorized Users and other Customer representatives and personnel may be able to access, modify or restrict access to Other Information.
              </li>
              <li class="my-2">
                <b>Third Party Service Providers and Partners.</b> We may engage third party companies or individuals as service providers or business partners to process Other Information and support our business.
              </li>
              <li class="my-2">
                <b>Third Party Services.</b> Customer may enable or permit Authorized Users to enable Third Party Services.
              </li>
              <li class="my-2">
                <b>Corporate Affiliates.</b> New Universe Enterprise may share Other Information with its corporate affiliates, parents and/or subsidiaries.
              </li>
              <li class="my-2">
                <b>Aggregated or De-identified Data.</b> We may disclose or use aggregated or de-identified Other Information for any purpose.
              </li>
              <li class="my-2">
                <b>To Comply with Laws.</b> If we receive a request for information, we may disclose Other Information if we reasonably believe disclosure is in accordance with or required by any applicable law, regulation or legal process.
              </li>
              <li class="my-2">
                <b>To enforce our rights, prevent fraud, and for safety.</b> To protect and defend the rights, property or safety of New Universe Enterprise or third parties, including enforcing contracts or policies, or in connection with investigating and preventing fraud or security issues.
              </li>
              <li class="my-2">
                <b>With Consent.</b> New Universe Enterprise may share Other Information with third parties when we have consent to do so.
              </li>
            </ol>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Security</b>
            <p class="fontRobLite fsz-15 my-2">
              New Universe Enterprise takes security of data very seriously. New Universe Enterprise works hard to protect Other Information you provide from loss, misuse, and unauthorized access or disclosure. These steps take into account the sensitivity of the Other Information we collect, process and store, and the current state of technology.
            </p>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Changes To This Privacy Policy</b>
            <p class="fontRobLite fsz-15 my-2">
              New Universe Enterprise may change this Privacy Policy from time to time. Laws, regulations and industry standards evolve, which may make those changes necessary, or we may make changes to our business. We will post the changes to this page and encourage you to review our Privacy Policy to stay informed.
            </p>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Data Protection Authority</b>
            <p class="fontRobLite fsz-15 my-2">
              Subject to applicable law, you also have the right to (i) restrict New Universe Enterprice use of Other Information that constitutes your Personal Data and (ii) lodge a complaint with your local data protection authority.
            </p>
          </li>
        </ol>
      </div>
    </div>
  </div>

  <!-- INDONESIA SECTION -->

  <div class="privacy-policy-indonesia" style="font-size:11px">
    <div class="container mt-2">
      <div class="col-md-12 py-2" style="padding-right: 20px">
        <ol type="1">
          <li class="fontRobBold fs-25 my-4"><b>Perimeter dan Definisi Kebijakan</b>
            <p class="fontRobLite fsz-15 my-2">
              Kebijakan Privasi ini berlaku untuk alat dan platform produktivitas tempat kerja online New Universe Enterprise, termasuk situs web seluler New Universe Enterprise dan New Universe Enterprise terkait, yang mungkin Anda miliki dengan New Universe Enterprise. Jika Anda tidak setuju dengan persyaratan, jangan mengakses atau menggunakan aspek Layanan atau Situs Web dari bisnis New Universe Enterprise.<br>
              Kebijakan Privasi ini tidak berlaku untuk aplikasi atau perangkat lunak pihak ketiga mana pun yang terintegrasi dengan Layanan melalui platform New Universe Enterprise, atau produk, layanan, atau bisnis pihak ketiga lainnya. Selain itu, perjanjian terpisah mengatur pengiriman, akses, dan penggunaan Layanan, termasuk pemrosesan pesan, file, atau konten lain apa pun yang dikirimkan melalui akun Layanan. Organisasi yang menandatangani Perjanjian Pelanggan mengontrol instans Layanan mereka dan Data Pelanggan terkait apa pun.
            </p>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Informasi yang Kami Kumpulkan Dan Terima</b>
            <ol type="a" class="fontRobLite fsz-15">
              <li class="my-2">
                Ruang Kerja dan Informasi Akun. Untuk membuat atau memperbarui akun Workspace, Anda atau Pelanggan Anda menyediakan New Universe Enterprise dengan alamat email, nomor telepon, kata sandi, domain, dan/atau detail akun serupa.</li>
              <li class="my-2">Usage Information
                <ul type="disc">
                  <li class="my-2">
                    <b>Layanan Metadata.</b> Saat Pengguna yang Sah berinteraksi dengan Layanan, metadata dihasilkan yang memberikan konteks tambahan tentang cara kerja Pengguna yang Sah.
                  </li>
                  <li class="my-2">
                    <b>Data Log.</b> Seperti kebanyakan situs web dan layanan teknologi yang dikirimkan melalui Internet, server kami secara otomatis mengumpulkan informasi saat Anda mengakses atau menggunakan Situs Web atau Layanan kami dan mencatatnya dalam file log. Data log ini dapat mencakup alamat Internet Protocol (IP), alamat halaman web yang dikunjungi sebelum menggunakan Situs Web atau Layanan, jenis dan pengaturan browser, tanggal dan waktu Layanan digunakan, informasi tentang konfigurasi dan plugin browser, preferensi bahasa dan data kuki.
                  </li>
                  <li class="my-2">
                    <b>Informasi Perangkat.</b> New Universe Enterprise mengumpulkan informasi tentang perangkat yang mengakses Layanan, termasuk jenis perangkat, sistem operasi apa yang digunakan, pengaturan perangkat, ID aplikasi, pengidentifikasi perangkat unik, dan data kerusakan. Apakah kami mengumpulkan sebagian atau seluruh Informasi Lain ini sering kali bergantung pada jenis perangkat yang digunakan dan pengaturannya.
                  </li>
                  <li class="my-2">
                    <b>Informasi Lokasi.</b> Kami menerima informasi dari Anda, Pelanggan Anda, dan pihak ketiga lainnya yang membantu kami memperkirakan lokasi Anda.
                  </li>
                </ul>
              </li>
              <li class="my-2">
                Informasi Cookie. New Universe Enterprise menggunakan cookie dan teknologi serupa di Situs Web dan Layanan kami yang membantu kami mengumpulkan Informasi Lainnya.
              </li>
              <li class="my-2">
                Informasi Kontak. Sesuai dengan proses persetujuan yang diberikan oleh perangkat Anda, setiap informasi kontak yang dipilih oleh Pengguna yang Sah untuk diimpor (seperti buku alamat dari perangkat) dikumpulkan saat menggunakan Layanan.
              </li>
              <li class="my-2">
                Data Pihak Ketiga. New Universe Enterprise dapat menerima data tentang organisasi, industri, pengunjung Situs Web, kampanye pemasaran, dan hal-hal lain yang terkait dengan bisnis kami dari perusahaan induk, afiliasi dan anak perusahaan, mitra kami, atau pihak lain yang kami gunakan untuk membuat informasi kami sendiri menjadi lebih baik atau lebih berguna .
              </li>
              <li class="my-2">
              Kami menerima Informasi Lain ketika dikirimkan ke Situs Web kami atau jika Anda berpartisipasi dalam grup fokus, kontes, aktivitas atau acara, melamar pekerjaan, meminta dukungan, berinteraksi dengan akun media sosial kami.
            </li>
            </ol>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Bagaimana Kami Menggunakan Informasi</b>
            <p class="fontRobLite fsz-15 my-2">
            New Universe Enterprise menggunakan Informasi untuk mendukung kepentingan sah kami dalam mengoperasikan Layanan, Situs Web, dan bisnis kami. Lebih spesifik :
            </p>
            <ol type="a" class="fontRobLite fsz-15">
            <li class="my-2">Untuk menyediakan, memperbarui, memelihara, dan melindungi Layanan, Situs Web, dan bisnis kami.</li>
              <li class="my-2">Sebagaimana diwajibkan oleh hukum, proses hukum, atau peraturan yang berlaku.</li>
              <li class="my-2">Untuk berkomunikasi dengan Anda dengan menanggapi permintaan, komentar, dan pertanyaan Anda.</li>
              <li class="my-2">Untuk mengembangkan dan menyediakan alat penelusuran, pembelajaran, dan produktivitas serta fitur tambahan.</li>
              <li class="my-2">Untuk mengirim email dan komunikasi lainnya.</li>
              <li class="my-2">Untuk penagihan, pengelolaan akun, dan masalah administrasi lainnya.</li>
              <li class="my-2">Untuk menyelidiki dan membantu mencegah masalah keamanan dan penyalahgunaan.</li>
              </ol>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Bagaimana Kami Berbagi Dan Mengungkapkan Informasi</b>
          <p class="fontRobLite fsz-15 my-2">
              Pelanggan menentukan kebijakan dan praktik mereka sendiri untuk berbagi dan pengungkapan Informasi, dan New Universe Enterprise tidak mengontrol bagaimana mereka atau pihak ketiga lainnya memilih untuk berbagi atau mengungkapkan Informasi.
            </p>
            <ol type="a" class="fontRobLite fsz-15">
              <li kelas="saya-2">
                <b>Petunjuk Pelanggan.</b> New Universe Enterprise hanya akan membagikan dan mengungkapkan Data Pelanggan sesuai dengan instruksi Pelanggan, termasuk persyaratan apa pun yang berlaku.
              </li>
              <li kelas="saya-2">
                <b>Menampilkan Layanan.</b> Saat Pengguna yang Sah mengirimkan Informasi Lain, Informasi tersebut dapat ditampilkan kepada Pengguna Sah lainnya di Ruang Kerja yang sama atau terhubung.
              </li>
              <li kelas="saya-2">
                <b>Berkolaborasi dengan Orang Lain.</b> Layanan menyediakan cara berbeda bagi Pengguna Resmi yang bekerja di Ruang Kerja independen untuk berkolaborasi, seperti saluran bersama.
              </li>
              <li kelas="saya-2">
                <b>Akses Pelanggan.</b> Pemilik, administrator, Pengguna Resmi, serta perwakilan dan personel Pelanggan lainnya mungkin dapat mengakses, mengubah, atau membatasi akses ke Informasi Lain.
              </li>
              <li kelas="saya-2">
                <b>Penyedia dan Mitra Layanan Pihak Ketiga.</b> Kami dapat melibatkan perusahaan atau individu pihak ketiga sebagai penyedia layanan atau mitra bisnis untuk memproses Informasi Lain dan mendukung bisnis kami.
              </li>
              <li kelas="saya-2">
                <b>Layanan Pihak Ketiga.</b> Pelanggan dapat mengaktifkan atau mengizinkan Pengguna yang Sah untuk mengaktifkan Layanan Pihak Ketiga.
              </li>
              <li kelas="saya-2">
                <b>Afiliasi Perusahaan.</b> New Universe Enterprise dapat membagikan Informasi Lain dengan afiliasi perusahaan, induk dan/atau anak perusahaannya.
              </li>
              <li kelas="saya-2">
                <b>Data Gabungan atau De-identifikasi.</b> Kami dapat mengungkapkan atau menggunakan Informasi Lain yang teragregasi atau tidak teridentifikasi untuk tujuan apa pun.
              </li>
              <li kelas="saya-2">
                <b>Untuk Mematuhi Hukum.</b> Jika kami menerima permintaan informasi, kami dapat mengungkapkan Informasi Lain jika kami cukup yakin bahwa pengungkapan tersebut sesuai dengan atau diwajibkan oleh hukum, peraturan, atau proses hukum yang berlaku.
              </li>
              <li kelas="saya-2">
                <b>Untuk menegakkan hak kami, mencegah penipuan, dan untuk keselamatan.</b> Untuk melindungi dan membela hak, properti, atau keselamatan New Universe Enterprise atau pihak ketiga, termasuk menegakkan kontrak atau kebijakan, atau sehubungan dengan penyelidikan dan pencegahan penipuan atau masalah keamanan.
              </li>
              <li kelas="saya-2">
                <b>Dengan Persetujuan.</b> New Universe Enterprise dapat membagikan Informasi Lain dengan pihak ketiga jika kami memiliki persetujuan untuk melakukannya.
              </li>
            </ol>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Keamanan</b>
            <p class="fontRobLite fsz-15 my-2">
              New Universe Enterprise memperhatikan keamanan data dengan sangat serius. New Universe Enterprise bekerja keras untuk melindungi Informasi Lain yang Anda berikan dari kehilangan, penyalahgunaan, dan akses atau pengungkapan yang tidak sah. Langkah-langkah ini mempertimbangkan sensitivitas Informasi Lain yang kami kumpulkan, proses dan simpan, dan keadaan teknologi saat ini.
            </p>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Perubahan Pada Kebijakan Privasi Ini</b>
            <p class="fontRobLite fsz-15 my-2">
              New Universe Enterprise dapat mengubah Kebijakan Privasi ini dari waktu ke waktu. Undang-undang, peraturan, dan standar industri berkembang, yang mungkin membuat perubahan tersebut diperlukan, atau kita dapat membuat perubahan pada bisnis kita. Kami akan memposting perubahan ke halaman ini dan mendorong Anda untuk meninjau Kebijakan Privasi kami untuk tetap mendapat informasi.
            </p>
          </li>
          <li class="fontRobBold fs-25 my-4"><b>Otoritas Perlindungan Data</b>
            <p class="fontRobLite fsz-15 my-2">
              Tunduk pada hukum yang berlaku, Anda juga berhak untuk (i) membatasi penggunaan Informasi Lain yang merupakan Data Pribadi Anda dan (ii) mengajukan keluhan kepada otoritas perlindungan data lokal Anda.
            </p>
          </li>
        </ol>
      </div>
    </div>
  </div>
</body>

<!-- FOOTER -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<!-- SCRIPT CHANGE LANGUAGE -->

<script>

  $('.privacy-policy-english').hide();
  $('.privacy-policy-indonesia').hide();

  if (localStorage.lang == 0 || localStorage.lang == null){
    $('.privacy-policy-english').show();
  }else if(localStorage.lang == 1){
    $('.privacy-policy-indonesia').show();
  }

</script>
</html>