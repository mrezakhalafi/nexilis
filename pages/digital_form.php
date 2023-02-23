<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/chat_dbconn.php');
$dbconn = paliolite();
$dbNewNus = newnus();

if (isset($_GET['f_pin'])) {
  $f_pin = $_GET['f_pin'];
}

if (!isset($f_pin)) {
    die();
}

$bg_url = "";

if (isset($f_pin) && $f_pin != "") {
  $sqlBE = "SELECT be.COMPANY_ID
    FROM BUSINESS_ENTITY be
    LEFT JOIN USER_LIST ul ON ul.BE = be.ID
    WHERE ul.F_PIN = '$f_pin'";
} else if (isset($api_key) && $api_key != "") {
  $sqlBE = "SELECT be.COMPANY_ID
    FROM BUSINESS_ENTITY be
    WHERE be.API = '$api_key'";
}

$queBE = $dbconn->prepare($sqlBE);
$queBE->execute();
$resBE = $queBE->get_result()->fetch_assoc();
$c_id = $resBE["COMPANY_ID"];
$queBE->close();

$sqlBG = "SELECT APP_BG FROM WEBFORM WHERE COMPANY_ID = " . $c_id . " AND GENERATE_APK > 0 ORDER BY CREATED_AT DESC LIMIT 1";
$queBG = $dbNewNus->prepare($sqlBG);
$queBG->execute();
$resBG = $queBG->get_result()->fetch_assoc();
$bg_str = $resBG["APP_BG"];
$queBG->close();

if ($bg_str != null && $bg_str != "") {

  $bg_arr = explode(",", $bg_str);
  $rand_bg = $bg_arr[array_rand($bg_arr, 1)];
  $bg_url = "https://newuniverse.io/dashboardv2/uploads/background/" . $rand_bg;

} else {

  $rand_bg = rand(1, 18) . ".png";
  $bg_url = "../assets/img/lbackground_" . $rand_bg;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>

        body {
            background-image: url('../assets/img/lbackground_<?php echo $rand_bg; ?>');
            background-size: 100% auto;
            background-repeat: repeat-y;
        }

        input, select {
            width: 100%;
            display: block;
            border: none;
            border-bottom: 2px solid #ebebeb;
            /* padding: 10px 0; */
            color: #222;
            margin-bottom: 5px;
        }

        label {
            display: block;
        }

    </style>

</head>
<body>

<!-- Navbar -->

<div class="row gx-0 bg-dark" style="color: white">
    <div class="col-2 d-flex justify-content-center">
        <a href=""><img src="../assets/img/icons/Back-(White) - Copy.png" alt="" style="height: 36px; margin-top: 13px"></a>
    </div>
    <div class="col-9 d-flex justify-content-center">
        <p class="pt-3" style="font-size: 20px">DIGITAL FORM</p>
    </div>
</div>

<div id="main" style="margin-top: -25px">

    <!-- Header -->
    <form id="webappform" method="POST" enctype="multipart/form-data">
        <div class="container">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow p-3" style="border: 2px solid darkorange; border-left: 8px solid darkorange; border-right: 8px solid darkorange; border-radius: 10px">
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="Form Title" id="title" name="title" style="font-size: 22px; text-align: center">
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" value="Form Description" id="description" name="description" style="font-size: 12px; text-align: center">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="bg-dark mt-4" style="width: 100%; height: 10px"></div> -->

        <div id="question-section" class="container">

            <!-- First Default Form -->
            <div id="question-1" class="row mt-4">
                <div class="col-12">
                    <div class="card shadow p-2" style="border: 2px solid darkgreen; border-top: 8px solid darkgreen; border-radius: 10px">
                        <div class="card-body">
                            <div class="row">
                                <b class="text-center mb-4">Question 1</b>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="question-1" name="question-1" placeholder="Question" style="font-size: 14px">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <select id="question-type-select-1" class="form-select" style="font-size: 14px" onchange="changeQuestionType('1')">
                                        <option value="1" selected>Text Answer</option>
                                        <option value="2">Radio Button</option>
                                        <option value="3">Check Boxes</option>
                                        <option value="4">Date</option>
                                        <option value="5">File Upload</option>
                                        <option value="6">Drop Down</option>
                                    </select>
                                </div>
                                <div id="question-preview-1" class="container mt-2">
                                    <input type="text" class="mt-3 mb-2" style="font-size: 14px" placeholder="Text Answer" disabled/>
                                </div>
                                <input type="hidden" id="question-type-1" name="question-type-1" value="1"/>
                                <input type="hidden" id="total-radio-1" name="total-radio-1" value="2"/>
                                <input type="hidden" id="total-checkbox-1" name="total-checkbox-1" value="1"/>
                                <input type="hidden" id="total-select-1" name="total-select-1" value="1"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add More Form -->
            <div id="add-section" class="row mt-3 mb-3">
                <div class="col-12">
                    <div class="card shadow p-2" style="border-radius: 10px">
                        <div class="card-body d-flex justify-content-center">
                            <div class="btn btn-success" onclick="addQuestion()">Add Question</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <!-- Price Section -->
            <div id="price-section" class="row mt-4">
                <div class="col-12">
                    <div class="card shadow p-2" style="border: 2px solid darkorange; border-bottom: 8px solid darkorange; border-radius: 10px">
                        <div class="card-body">
                            <div class="row">
                                <b class="text-center mb-4">Pricing</b>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="regis_fee" name="regis_fee" placeholder="Registration Fee" style="font-size: 14px">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="admin_fee" name="admin_fee" placeholder="Administration Fee" style="font-size: 14px">
                                    </div>
                                </div>
                            </div>
                            <div class="row p-2">
                                <button class="btn btn-dark" type="button" style="height: 40px; margin-top: 10px" onclick="sendData()">SUBMIT</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="total-question" name="total-question" value="1"/>
    </form>

</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script>

    var question_no = 1;

    function addQuestion(){

        question_no = question_no + 1;

        var html = `<div id="question-`+question_no+`" class="row mt-4">
                        <div class="col-12">
                            <div class="card shadow p-2" style="border: 2px solid darkgreen; border-top: 8px solid darkgreen; border-radius: 10px">
                                <img src="../assets/img/close-icon.jpg" id="delete-question-`+question_no+`" style="width: 35px; height: 35px; position: absolute; right: 0; margin-right: 10px" onclick="deleteQuestion('`+question_no+`')">
                                <div class="card-body"> 
                                    <div class="row">
                                        <b class="text-center mb-4">Question `+question_no+`</b>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="question-`+question_no+`" name="question-`+question_no+`" placeholder="Question" style="font-size: 14px">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <select id="question-type-select-`+question_no+`" class="form-select" style="font-size: 14px" onchange="changeQuestionType('`+question_no+`')">
                                                <option value="1" selected>Text Answer</option>
                                                <option value="2">Radio Button</option>
                                                <option value="3">Check Boxes</option>
                                                <option value="4">Date</option>
                                                <option value="5">File Upload</option>
                                                <option value="6">Drop Down</option>
                                            </select>
                                        </div>
                                        <div id="question-preview-`+question_no+`" class="container mt-2">
                                            <input type="text" class="mt-3 mb-2" style="font-size: 14px" placeholder="Text Answer" disabled/>
                                        </div>
                                        <input type="hidden" id="question-type-`+question_no+`" name="question-type-`+question_no+`" value="1"/>
                                        <input type="hidden" id="total-radio-`+question_no+`" name="total-radio-`+question_no+`" value="2"/>
                                        <input type="hidden" id="total-checkbox-`+question_no+`" name="total-checkbox-`+question_no+`" value="1"/>
                                        <input type="hidden" id="total-select-`+question_no+`" name="total-select-`+question_no+`" value="1"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
        $('#question-section').append(html);
        $("#add-section").insertAfter("#question-"+question_no);

        $('#total-question').val(question_no);
        $('#delete-question-'+(question_no-1)).hide();

    }

    // ONCHANGE TYPE

    function changeQuestionType(number){
      
        var question_no = number;
        var type = $('#question-type-select-'+number).val();

        if (type == 1){

            var html = `<input type="text" class="mt-3 mb-2" style="font-size: 14px" placeholder="Text Answer" disabled/>`;

            $('#question-preview-'+question_no).html(html);
            $('#question-type-'+question_no).val('1');

        }else if(type == 2){

            var html = `<div class="row">
                            <div class="col-10">
                                <div id="radio-`+question_no+`-number-1" class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="radio-`+question_no+`" id="radio-`+question_no+`-number-1" checked>
                                    <label class="form-check-label" for="radio-`+question_no+`">
                                        <input type="text" name="radio-`+question_no+`-number-1" class="form-control" placeholder="Choice 1" style="font-size: 14px">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10">
                                <div id="radio-`+question_no+`-number-2" class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="radio-`+question_no+`" id="radio-`+question_no+`-number-2">
                                    <label class="form-check-label" for="radio-`+question_no+`">
                                        <input type="text" name="radio-`+question_no+`-number-2" class="form-control" placeholder="Choice 2" style="font-size: 14px">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="add-radio-`+question_no+`" class="mt-3 btn btn-secondary" onclick="addRadio('`+question_no+`','3')">Add Choice</button>`;

            $('#question-preview-'+question_no).html(html);
            $('#question-type-'+question_no).val('2');

        }else if(type == 3){

            var html = `<div class="row">
                            <div class="col-10">
                                <div id="checkbox-`+question_no+`-number-1" class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="" name="checkbox-`+question_no+`" id="checkbox-`+question_no+`-number-1" checked>
                                    <label class="form-check-label" for="checkbox-`+question_no+`">
                                        <input type="text" name="checkbox-`+question_no+`-number-1" class="form-control" placeholder="Choice 1" style="font-size: 14px">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="add-checkbox-`+question_no+`" class="mt-3 btn btn-secondary" onclick="addCheckbox('`+question_no+`','2')">Add Choice</button>`; 

            $('#question-preview-'+question_no).html(html);
            $('#question-type-'+question_no).val('3');
            
        }else if(type == 4){

            var html = `<input type="date" class="mt-3 mb-2" value="1970-01-01" disabled style="background-color: white; color: grey"/>`;

            $('#question-preview-'+question_no).html(html);
            $('#question-type-'+question_no).val('4');
            
        }else if(type == 5){

            var html = `<label style="color: #FFFFFF; background-color: #f66701; margin-right: 10px; margin-bottom: 10px" class="btn mt-3">Choose File</label>
                        <p style="display: inline;">No file chosen</p>
                        <input type="file" style="display:none;" disabled/>`;
            
            $('#question-preview-'+question_no).html(html);
            $('#question-type-'+question_no).val('5');

        }else if(type == 6){

            var html = `<div class="row">
                            <div class="col-10">
                                <input type="text" id="select-`+question_no+`-number-1" name="select-`+question_no+`-number-1" class="form-control mt-3" placeholder="Option 1" style="font-size: 14px">
                                <div id="add-select-`+question_no+`" class="mt-3 btn btn-secondary" onclick="addSelect('`+question_no+`','2')">Add Option</button>
                            </div>
                        </div>`;

            $('#question-preview-'+question_no).html(html);
            $('#question-type-'+question_no).val('6');
            
        }
        
    };

    // ADD RADIO

    function addRadio(question_no, radio_no){

        var question_no = question_no;
        var radio_no = radio_no;

        var html = `<div class="row" id="radio-choice-space-`+question_no+`-number-`+radio_no+`">
                        <div class="col-10">
                            <div id="radio-`+question_no+`-number-`+radio_no+`" class="form-check mt-3">
                                <input class="form-check-input" type="radio" name="radio-`+question_no+`" id="radio-`+question_no+`-number-`+radio_no+`">
                                <label class="form-check-label" for="radio-`+question_no+`-number-`+radio_no+`">
                                    <input type="text" name="radio-`+question_no+`-number-`+radio_no+`" class="form-control" placeholder="Choice `+radio_no+`" style="font-size: 14px">
                                </label>
                            </div>
                        </div>
                        <div class="col-2" id="delete-choice-space-sub-`+question_no+`-number-`+radio_no+`">
                            <img id="delete-choice-`+question_no+`-number-`+radio_no+`" src="../assets/img/close-icon.jpg" style="width: 30px; height: 30px; margin-top: 15px" onclick="deleteChoice('`+question_no+`','2','`+radio_no+`')">
                        </div>
                    </div>`;

        $('#question-preview-'+question_no).append(html);
        $("#add-radio-"+question_no).insertAfter('#radio-choice-space-'+question_no+"-number-"+radio_no);

        $('#total-radio-'+question_no).val(radio_no);

        // FOR DELETE X CHOICE

        $('#delete-choice-'+question_no+'-number-'+(radio_no-1)).remove()

        radio_no = parseInt(radio_no) + 1;
        $("#add-radio-"+question_no).attr("onclick","addRadio('"+question_no+"','"+radio_no+"')");

    }

    // ADD CHECKBOX

    function addCheckbox(question_no, checkbox_no){

        var question_no = question_no;
        var checkbox_no = checkbox_no;

        var html = `<div class="row" id="checkbox-choice-space-`+question_no+`-number-`+checkbox_no+`">
                        <div class="col-10">
                            <div id="checkbox-`+question_no+`-number-`+checkbox_no+`" class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="checkbox-`+question_no+`" id="checkbox-`+question_no+`-number-`+checkbox_no+`">
                                <label class="form-check-label" for="checkbox-`+question_no+`-number-`+checkbox_no+`">
                                    <input type="text" name="checkbox-`+question_no+`-number-`+checkbox_no+`" class="form-control" placeholder="Choice `+checkbox_no+`" style="font-size: 14px">
                                </label>
                            </div>
                        </div>
                        <div class="col-2" id="delete-choice-space-sub-`+question_no+`-number-`+checkbox_no+`">
                            <img id="delete-choice-`+question_no+`-number-`+checkbox_no+`" src="../assets/img/close-icon.jpg" style="width: 30px; height: 30px; margin-top: 15px" onclick="deleteChoice('`+question_no+`','3','`+checkbox_no+`')">
                        </div>
                    </div>`;

        $('#question-preview-'+question_no).append(html);
        $("#add-checkbox-"+question_no).insertAfter('#checkbox-choice-space-'+question_no+"-number-"+checkbox_no);

        $('#total-checkbox-'+question_no).val(checkbox_no);

         // FOR DELETE X CHOICE

         $('#delete-choice-'+question_no+'-number-'+(checkbox_no-1)).remove();

        checkbox_no = parseInt(checkbox_no) + 1;
        $("#add-checkbox-"+question_no).attr("onclick","addCheckbox('"+question_no+"','"+checkbox_no+"')");

    }

    // ADD SELECT

    function addSelect(question_no, select_no){

        var question_no = question_no;
        var select_no = select_no;

        var html = `<div class="row" id="select-choice-space-`+question_no+`-number-`+select_no+`">
                        <div class="col-10">
                            <input type="text" id="select-`+question_no+`-number-`+select_no+`" name="select-`+question_no+`-number-`+select_no+`"  class="form-control mt-3" placeholder="Option `+select_no+`" style="font-size: 14px">
                        </div>
                        <div class="col-2" id="delete-choice-space-sub-`+question_no+`-number-`+select_no+`">
                            <img id="delete-choice-`+question_no+`-number-`+select_no+`" src="../assets/img/close-icon.jpg" style="width: 30px; height: 30px; margin-top: 16px" onclick="deleteChoice('`+question_no+`','6','`+select_no+`')">
                        </div>
                    </div>`;
                    
        $('#question-preview-'+question_no).append(html);
        $("#add-select-"+question_no).insertAfter('#select-choice-space-'+question_no+"-number-"+select_no);

        $('#total-select-'+question_no).val(select_no);

         // FOR DELETE X CHOICE

         $('#delete-choice-'+question_no+'-number-'+(select_no-1)).remove();

        select_no = parseInt(select_no) + 1;
        $("#add-select-"+question_no).attr("onclick","addSelect('"+question_no+"','"+select_no+"')");

    }

    // SEND DATA

    function sendData(){

        var myform = $("#webappform")[0];

        console.log(myform);
        var fd = new FormData(myform);
        fd.append("f_pin", '<?= $_GET['f_pin'] ?>');

        $.ajax({
            type: "POST",
            url: "/nexilis/logics/insert_webform_question",
            data: fd,
            enctype: 'multipart/form-data',
            cache: false,
            processData: false,
            contentType: false,
            success: function (response) {
               alert("Success");
            },
            error: function (response) {
                alert("Logic Failed");
            }
        });

    }

    // DELETE QUESTION

    function deleteQuestion(no){

        $('#question-'+no).remove();

        $('#delete-question-'+(question_no-1)).show();
        
        question_no = question_no - 1;
        $('#total-question').val(question_no);
    }

    // DELETE CHOICE

    function deleteChoice(q_no, type, c_no){

        if (type == 2){

            $('#radio-choice-space-'+q_no+'-number-'+c_no).remove();

            // FOR SHOW AGAIN PREVIOUS X AFTER DELETE X BELOW

            var html = `<img id="delete-choice-`+q_no+`-number-`+(c_no-1)+`" src="../assets/img/close-icon.jpg" style="width: 30px; height: 30px; margin-top: 15px" onclick="deleteChoice('`+q_no+`','2','`+(c_no-1)+`')">`;
            $('#delete-choice-space-sub-'+q_no+'-number-'+(c_no-1)).html(html);
            $("#add-radio-"+q_no).attr("onclick","addRadio('"+q_no+"','"+c_no+"')");

            // DECREASE NUMBER OF VALUE TO DB

            radio_no = c_no - 1;
            $('#total-radio-'+q_no).val(radio_no);


        }else if(type == 3){

            $('#checkbox-choice-space-'+q_no+'-number-'+c_no).remove();
            
            // FOR SHOW AGAIN PREVIOUS X AFTER DELETE X BELOW

            var html = `<img id="delete-choice-`+q_no+`-number-`+(c_no-1)+`" src="../assets/img/close-icon.jpg" style="width: 30px; height: 30px; margin-top: 15px" onclick="deleteChoice('`+q_no+`','3','`+(c_no-1)+`')">`;
            $('#delete-choice-space-sub-'+q_no+'-number-'+(c_no-1)).html(html);
            $("#add-checkbox-"+q_no).attr("onclick","addCheckbox('"+q_no+"','"+c_no+"')");

            // DECREASE NUMBER OF VALUE TO DB

            checkbox_no = c_no - 1;
            $('#total-checkbox-'+q_no).val(checkbox_no);

        }else if(type == 6){

            $('#select-choice-space-'+q_no+'-number-'+c_no).remove();

             // FOR SHOW AGAIN PREVIOUS X AFTER DELETE X BELOW

            var html = `<img id="delete-choice-`+q_no+`-number-`+(c_no-1)+`" src="../assets/img/close-icon.jpg" style="width: 30px; height: 30px; margin-top: 15px" onclick="deleteChoice('`+q_no+`','6','`+(c_no-1)+`')">`;
            $('#delete-choice-space-sub-'+q_no+'-number-'+(c_no-1)).html(html);
            $("#add-select-"+q_no).attr("onclick","addSelect('"+q_no+"','"+c_no+"')");

            // DECREASE NUMBER OF VALUE TO DB

            select_no = c_no - 1;
            $('#total-select-'+q_no).val(select_no);

        }

    }

</script>