"use strict";

function getPosition(mouseEvent, sigCanvas) {
    var x, y;
    if (mouseEvent.pageX != undefined && mouseEvent.pageY != undefined) {
        x = mouseEvent.pageX;
        y = mouseEvent.pageY;
    } else {
        x = mouseEvent.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
        y = mouseEvent.clientY + document.body.scrollTop + document.documentElement.scrollTop;
    }

    return { X: x - sigCanvas.offsetLeft, Y: y - sigCanvas.offsetTop };
}

function changeInputMethod(number){

    var radioTtd = "File";
    var radioEktp = "File";

    $("input[name=radio-file-chooser-"+number+"]:radio").on("click", function(){
        if($(this).val() == "File"){
            $("#question-"+number).prop('required',true);
            $("#inputfile_button-"+number).text("Choose File")
            $("#question-"+number).prop('accept',"image/*,profile_file/*")
            radioEktp = $(this).val();
        }
        else {
            $("#question-"+number).prop('required',false);
            $("#inputfile_button-"+number).text("Take Photo")
            $("#question-"+number).prop('accept',"image/*,profile_photo/*")
            radioEktp = $(this).val();
        }
    });

}

function changeInputMethodOCR(number){

    var radioTtd = "File";
    var radioEktp = "File";

    $("input[name=radio-file-chooser-"+number+"]:radio").on("click", function(){
        if($(this).val() == "File"){
            $("#question-"+number).prop('required',true);
            $("#inputfile_button-"+number).text("Choose File")
            $("#question-"+number).prop('accept',"image/*,ocr_file/*")
            radioEktp = $(this).val();
        }
        else {
            $("#question-"+number).prop('required',false);
            $("#inputfile_button-"+number).text("Take Photo")
            $("#question-"+number).prop('accept',"image/*,ocr_photo/*")
            radioEktp = $(this).val();
        }
    });

}

function initialize(number) {

    var sigCanvas;
    var canvas;
    var canvasSigned = false;
    var coors;

    sigCanvas = document.getElementById("canvasSignature-"+number);
    canvas = sigCanvas.getContext("2d");

    canvas.strokeStyle = 'Black';
    var is_touch_device = 'ontouchstart' in document.documentElement;

    if (is_touch_device) {

        var drawer = {
            isDrawing: false,
            touchstart: function (coors) {
                canvas.beginPath();
                canvas.moveTo(coors.x, coors.y);
                this.isDrawing = true;
            },
            touchmove: function (coors) {
                if (this.isDrawing) {
                canvas.lineTo(coors.x, coors.y);
                canvas.stroke();
                }
            },
            touchend: function (coors) {
                if (this.isDrawing) {
                this.touchmove(coors);
                this.isDrawing = false;
                canvasSigned = true;

                $('#question-'+number).val("Drawable");
                }
            }
        };

        function draw(event) {

            if(event.type != "touchend"){

                coors = {
                    x: event.targetTouches[0].pageX,
                    y: event.targetTouches[0].pageY
                };

                var obj = sigCanvas;

                if (obj.offsetParent) {

                    do {
                    coors.x -= obj.offsetLeft;
                    coors.y -= obj.offsetTop;
                    }

                    while ((obj = obj.offsetParent) != null);
                }
            }
        
            drawer[event.type](coors);
            
        }

        sigCanvas.addEventListener('touchstart', draw, false);
        sigCanvas.addEventListener('touchmove', draw, false);
        sigCanvas.addEventListener('touchend', draw, false);


        sigCanvas.addEventListener('touchmove', function (event) {
            event.preventDefault();
        }, false); 
    }
    else {

        $(sigCanvas).mousedown(function (mouseEvent) {
            var position = getPosition(mouseEvent, sigCanvas);

            canvas.moveTo(position.X, position.Y);
            canvas.beginPath();

            $(this).mousemove(function (mouseEvent) {
                drawLine(mouseEvent, sigCanvas, canvas);
            }).mouseup(function (mouseEvent) {
                finishDrawing(mouseEvent, sigCanvas, canvas);
                canvasSigned = true;
            }).mouseout(function (mouseEvent) {
                finishDrawing(mouseEvent, sigCanvas, canvas);
                canvasSigned = true;
            });
        });

    }
}

function drawLine(mouseEvent, sigCanvas, canvas) {

    var position = getPosition(mouseEvent, sigCanvas);

    canvas.lineTo(position.X, position.Y);
    canvas.stroke();
}

function finishDrawing(mouseEvent, sigCanvas, canvas) {
    drawLine(mouseEvent, sigCanvas, canvas);

    canvas.closePath();

    $(sigCanvas).unbind("mousemove")
                .unbind("mouseup")
                .unbind("mouseout");
}

function clearCanvas(){
    var height = sigCanvas.height;
    var width = sigCanvas.width;
    var grad = canvas.createLinearGradient(0, 0, width, 0);
    grad.addColorStop(0,"white");
    grad.addColorStop(1,"white");
    canvas.fillStyle = grad;
    canvas.fillRect(0,0,width,height);
    canvasSigned = false;
}

// ========================================================================================================================================

function ktpOcr(data){
    // nik name address
    var d = JSON.parse(data)
    $('#ektp').val(d['nik'])
    $('#name').val(d['name'])
    $('#address').val(d['address'])
}

function canvasToBlob(number){
    var dataUrl = document.getElementById("canvasSignature-"+number).toDataURL()

    // var blobBin = atob(dataUrl.split(',')[1]);
    // var array = [];
    // for(var i = 0; i < blobBin.length; i++) {
    //     array.push(blobBin.charCodeAt(i));
    // }
    // var file=new Blob([new Uint8Array(array)], {type: 'image/png'});
    // return file

    return dataUrl;
}

$("input[name=ttd_radio]:radio").on("click", function(){
    if($(this).val() == "File"){
        $('#fotoTtd').prop('required',true);
        $('.uploadTtdFile').show();
        $('.uploadTtdCanvas').hide();
        clearCanvas();
        radioTtd = $(this).val();
    }
    else {
        $('#fotoTtd').prop('required',false);
        $('.uploadTtdFile').hide();
        $('.uploadTtdCanvas').show();
        radioTtd = $(this).val();
    }
});
$('#radioFile').click();

$('#fotoEktp').change(function (e) { 
    e.preventDefault();
    $('#ektpFileName').text(this.files[0].name)
});

$("#simRequest").on("change", function() {
    $('.error').hide();
    if($(this).val() == "1"){
        $('.perpanjangan').hide();
        $('.baru').show();
        $('.fotoSim').hide();
        $('#fotoSim').prop('required', false);
        $('#fotoSim').removeClass('photo');
        $('.baru').children().prop('required', true);
    }
    else{
        $('.perpanjangan').show();
        $('.baru').hide();
        $('.fotoSim').show();
        $('#fotoSim').prop('required', true);
        $('#fotoSim').addClass('photo');
        $('.baru').children().prop('required', false);
    }
});
function underAgeValidate(birthday){

    var dob = new Date(birthday);
    
    //calculate month difference from current date in time  
    var month_diff = Date.now() - dob.getTime();  
    
    //convert the calculated difference in date format  
    var age_dt = new Date(month_diff);   
    
    //extract year from date      
    var year = age_dt.getUTCFullYear();  
    
    //now calculate the age of the user  
    var age = Math.abs(year - 1970);  
    
    return age >= 17;

}
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");
 
$('#simRequest').change();
$.validator.addMethod("validateAge", function (value, element, param) {
    return this.optional(element) || underAgeValidate(value);
}, 'Usia harus 17 tahun atau lebih');
$.validator.addMethod("photoSize", function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File must be JPG, GIF or PNG, less than 3MB');
$.validator.addMethod("checkPhoto", function (value, element, param) {
    return this.optional(element) || /png|jpe?g|gif/g.test(element.files[0].name.toLowerCase().split('.').pop());
}, 'File must be JPG, GIF or PNG, less than 3MB');
$('#e-sim-form').validate({
    rules: {
        ektp: {
            number: true
        },
        noSim: {
            number: true
        },
        dateOfBirth: {
            validateAge: true
        },
        fotoEktp: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoSim: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoTtd: {
            photoSize: 4000000,
            checkPhoto: true
        },
        pasFoto: {
            photoSize: 4000000,
            checkPhoto: true
        }
    },
    submitHandler: function(form,event) {
        modalProgress.style.display = "block";
        event.preventDefault();
        var myform = $("#e-sim-form")[0];
        var fd = new FormData(myform);
        $("#submit").prop("disabled", true);
        if(canvasSigned){
            var blob = canvasToBlob();
            fd.set("fotoTtd",blob,"ttd.png")
        }
        fd.append("f_pin",F_PIN);
        $.ajax({
            type: "POST",
            url: "/gaspol_web/logics/register_new_esim",
            data: fd,
            enctype: 'multipart/form-data',
            cache : false,
            processData: false,
            contentType: false,
            success: function (response) {
                modalProgress.style.display = "none";
                modalSuccess.style.display = "block";
                if (window.Android) {
                    window.Android.finishGaspolForm()
                }
                $("#submit").prop("disabled", false);
            },
            error: function (response) {
                modalProgress.style.display = "none";
                alert("Failed");
                $("#submit").prop("disabled", false);
            }
        });
        // $.post('/gaspol_web/logics/register_new_esim',fd,function(){
        //     alert( "success" );
        //   },"multipart/form-data").done(function() {
        //       alert( "second success" );
        //     }).fail(function() {
        //       alert( "error" );
        //     }).always(function() {
        //       alert( "finished" );
        //     });
    }
});

// When the user clicks the button, open the modal 
// btn.onclick = function() {
//   modal.style.display = "block";
// }

// When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//   modal.style.display = "none";
// }

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modalSuccess) {
    modalSuccess.style.display = "none";
  }
}