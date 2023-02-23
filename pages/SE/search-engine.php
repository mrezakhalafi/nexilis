<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSINT</title>
</head>

<style>

    .cloud {
        width: 200px;
        height: 60px;
        background: #fff;
        border-radius: 200px;
        -moz-border-radius: 200px;
        -webkit-border-radius: 200px;
        position: relative;
    }

    .cloud:before,n
    .cloud:after {
        content: '';
        position: absolute;
        background: #fff;
        width: 100px;
        height: 80px;
        position: absolute;
        top: -15px;
        left: 10px;
        border-radius: 100px;
        -moz-border-radius: 100px;
        -webkit-border-radius: 100px;
        -webkit-transform: rotate(30deg);
        transform: rotate(30deg);
        -moz-transform: rotate(30deg);
    }

    .cloud:after {
        width: 120px;
        height: 120px;
        top: -55px;
        left: auto;
        right: 15px;
    }

    .x1 {
        -webkit-animation: moveclouds 15s linear infinite;
        -moz-animation: moveclouds 15s linear infinite;
        -o-animation: moveclouds 15s linear infinite;
    }

    .x2 {
        left: 200px;
        -webkit-transform: scale(0.6);
        -moz-transform: scale(0.6);
        transform: scale(0.6);
        opacity: 0.6;
        -webkit-animation: moveclouds 25s linear infinite;
        -moz-animation: moveclouds 25s linear infinite;
        -o-animation: moveclouds 25s linear infinite;
    }

    .x3 {
        left: -250px;
        top: -200px;
        -webkit-transform: scale(0.8);
        -moz-transform: scale(0.8);
        transform: scale(0.8);
        opacity: 0.8;
        -webkit-animation: moveclouds 20s linear infinite;
        -moz-animation: moveclouds 20s linear infinite;
        -o-animation: moveclouds 20s linear infinite;
    }

    .x4 {
        left: 470px;
        top: -250px;
        -webkit-transform: scale(0.75);
        -moz-transform: scale(0.75);
        transform: scale(0.75);
        opacity: 0.75;
        -webkit-animation: moveclouds 18s linear infinite;
        -moz-animation: moveclouds 18s linear infinite;
        -o-animation: moveclouds 18s linear infinite;
    }

    .x5 {
        left: -150px;
        top: -150px;
        -webkit-transform: scale(0.8);
        -moz-transform: scale(0.8);
        transform: scale(0.8);
        opacity: 0.8;
        -webkit-animation: moveclouds 20s linear infinite;
        -moz-animation: moveclouds 20s linear infinite;
        -o-animation: moveclouds 20s linear infinite;
    }

    @-webkit-keyframes moveclouds {
        0% {
            margin-left: 1000px;
        }

        100% {
            margin-left: -1000px;
        }
    }

    @-moz-keyframes moveclouds {
        0% {
            margin-left: 1000px;
        }

        100% {
            margin-left: -1000px;
        }
    }

    @-o-keyframes moveclouds {
        0% {
            margin-left: 1000px;
        }

        100% {
            margin-left: -1000px;
        }
    }

    html,
    body {
        max-width: 100%;
        overflow-x: hidden;
    }

    form#searchForm {
        display: flex;
        flex-direction: row;
        border-radius: 20px;
        align-items: center;
        width:100%;
        box-shadow:none;
        border: 1px solid grey;
        background-color:transparent !important;
    }

    input#search {
        flex-grow: 2;
        border: none;
        margin-bottom: 0;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        background-color: transparent !important;
        padding: .375rem !important;
    }

    #delete-query {
        width: 20px;
        height: 20px;
        margin-right: 7px;
    }

    .btn-search {
        width: 24px;
        height: 24px;
    }

    .form-control:focus {
        box-shadow: none !important;
        background-color:transparent !important;
    }

    .nav-item {
        background-color: #868e95;
        border: 1px solid grey;
        border-radius: 20px;
        margin: 7px;
    }

    .nav-link {
        color: white !important;
    }

    .nav-pills{
        font-size: 15px;
    }

</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<body style="background-image: url('../../assets/img/se-background.webp')">

    <div id="clouds">
        <div class="cloud x1"></div>
        <div class="cloud x2"></div>
        <div class="cloud x3"></div>
        <div class="cloud x4"></div>
        <div class="cloud x5"></div>
    </div>
    <div class="container" style="margin-top: -75px">
        <img src="../../assets/img/se-image.png" style="width: 100%">
        <div class="col-12 mt-3 mb-3 d-flex align-items-center justify-content-center" style="background-color:transparent;">
            <form id="searchForm" class="px-2">
                <img class="btn-search" style="margin-right: 10px" src="../../assets/img/tab5/search-black.png">
                <input id="search" type="text" class="form-control" placeholder="Type to search...">
                <img class="d-none" id="delete-query" src="../../assets/img/icons/X-fill.png">
            </form>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <button class="btn btn-secondary w-75" onclick="doSearch()">Search</button>
            </div>
        </div>
    <!-- <div class="mt-5 mb-2" style="font-size: 13px; color: grey; font-weight: bold">Type of search </div> -->
    <ul class="nav nav-pills mt-5">
        <li id="username" class="nav-item" onclick="goToLink('https://knowem.com/checkusernames.php?u=')">
            <a class="nav-link">Username</a>
        </li>
        <li id="email" class="nav-item" onclick="goToLink('https://hunter.io/try/search/')">
            <a class="nav-link">Email</a>
        </li>
        <li id="domain" class="nav-item" onclick="goToLink('https://whois.domaintools.com/')">
            <a class="nav-link">Domain</a>
        </li>
        <li id="ip" class="nav-item" onclick="goToLink('https://www.maxmind.com/en/geoip2-precision-demo?ip_address=')">
            <a class="nav-link">IP Address</a>
        </li>
        <li id="images" class="nav-item" onclick="goToLink('https://www.google.com/search?tbm=isch&q=')">
            <a class="nav-link">Images</a>
        </li>
        <li id="videos" class="nav-item" onclick="goToLink('https://www.google.com/search?tbm=vid&q=')">
            <a class="nav-link">Videos</a>
        </li>
        <li id="docs" class="nav-item" onclick="goToLink('https://www.google.com/search?q=site%3Adocs.google.com+')">
            <a class="nav-link">Docs</a>
        </li>
        <li id="social" class="nav-item" onclick="goToLink('https://www.social-searcher.com/social-buzz/?q5=')">
            <a class="nav-link">Social</a>
        </li>
        <li id="people" class="nav-item" onclick="goToLink('https://www.peekyou.com/')">
            <a class="nav-link">People Search</a>
        </li>
        <li id="business" class="nav-item" onclick="goToLink('https://opencorporates.com/companies?jurisdiction_code=&q=')">
            <a class="nav-link">Business Records</a>
        </li>
        <li id="maps" class="nav-item" onclick="goToLink('https://www.google.com/maps/search/')">
            <a class="nav-link">Maps</a>
        </li>
        <li id="terrorism" class="nav-item" onclick="goToLink('https://www.start.umd.edu/gtd/search/Results.aspx?sa.x=35&sa.y=6&search=')">
            <a class="nav-link">Terrorism</a>
        </li>
        <li id="darkweb" class="nav-item" onclick="goToLink('https://www.reddit.com/r/deepweb/search/?restrict_sr=1&q=')">
            <a class="nav-link">Dark Web</a>
        </li>
    </ul>

    </div>

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>

    $(document).ready(function() {

        eraseQuery();
        $('.nav-item').hide();

    })

    $("#search").keyup(function () {

        if ($("#search").val().match(/@/i)) {
            console.log("Email");
        }
        if ($("#search").val().match(/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/i)) {
            console.log("IP Address");
        }
        if ($("#search").val().match(/[.][A-Z][A-Z]/i)) {
            console.log("Domain");
        }

    });

    function eraseQuery() {

        $("#search").bind("change input keyup", function() {
            if ($('#search').val() != '') {
                $('#delete-query').removeClass('d-none');
            } else {
                $('#delete-query').addClass('d-none');
            }
        });

        $("#delete-query").click(function() {
            $('#search').val('');
            $('#delete-query').addClass('d-none');
        })
    }

    function doSearch(){

        $('.nav-item').hide();
        var query = $('#search').val();

        var regexIp = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/i;
        var regexEmail = /@/i;
        var regexDomain = /[.][A-Z][A-Z]/i;

        if (query.match(regexIp)) {
            $('#ip').show();
        }

        if (query.match(regexEmail)){
            $('#email').show();
        }

        if (query.match(regexDomain)){
            $('#domain').show();
            $('#email').show();
        }

        if (!query.match(regexIp) && !query.match(regexEmail) && !query.match(regexDomain)){
            $('.nav-item').show();
            $('#ip').hide();
        }

        // if (typeof query === 'string'){
        //     $('.nav-item').show();
        // }

    }

    function goToLink(link) {

        var query = $('#search').val();
        var link = link;

        if (link.includes("peekyou")) {
            query = query.replace(/\s/g, "_");
        }

        console.log(query);
        window.location.href = link + query;

    }

</script>