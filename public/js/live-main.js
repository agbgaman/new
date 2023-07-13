/*===========================================================================
*
*  LIVE TRANSCRIBE RESULT
*
*============================================================================*/

$(document).on('click', '.transcribeResult', function() {

    "use strict";

    let id = $(this).attr("id");
    document.getElementById("textarea").value = '';

    $("#live-save-status").css('display', 'none');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: 'result/transcript',
        data: {
            id: id,
            transcript: 'image'
        },
        success:function(data) {
            $('#transcriptModal').modal('show');
            $('#live-save').attr('data-id', id);
            document.getElementById("textarea").value = data.text;
            if (data.image){
                document.getElementById("edit-live-image-transcription").src =data.image.image;
            }
        }
    });

});

$(document).on('click', '.transcribeResultText', function() {

    "use strict";

    let id = $(this).attr("id");
    document.getElementById("textarea-transcript").value = '';

    $("#live-save-status").css('display', 'none');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: 'result/transcript',
        data: {
            id: id,
            transcript: 'text'
        },
        success:function(data) {
            console.log(data,'data')
            $('#transcriptModalText').modal('show');
            $('#live-save').attr('data-id', id);
            document.getElementById("textarea-transcript").value = data.text;
            document.getElementById("text-for-transcript").innerHTML = '';
            if (data.csv_text){
                document.getElementById("text-for-transcript").innerHTML = data.csv_text.text;
            }
        }
    });

});



/*===========================================================================
*
*  LIVE TRANSCRIPT RESULTS ACTION BUTTONS
*
*============================================================================*/

$(document).ready(function(){

    "use strict";

    $('#live-download-txt').on('click', function(e) {

        e.preventDefault();

        let d = new Date();
        let date = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + d.getFullYear();

        let text = document.getElementById("textarea").value;
        text = text.replace(/\n/g, "\r\n"); // To retain the Line breaks.
        let blob = new Blob([text], { type: "text/plain"});
        let anchor = document.createElement("a");
        anchor.download = date + "-live-transcribe-result.txt";
        anchor.href = window.URL.createObjectURL(blob);
        anchor.target ="_blank";
        anchor.style.display = "none"; // just to be safe!
        document.body.appendChild(anchor);
        anchor.click();
        document.body.removeChild(anchor);

    });

    $('#live-save').on('click', function(e) {

        e.preventDefault();

        let id = $(this).attr("data-id");
        let text = document.getElementById("textarea").value;

        $("#live-save-status").css('display', 'none');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: 'result/transcript/save',
            data: {
                id: id,
                text: text
            },
            beforeSend: function() {
                $("#live-save-status").css('display', 'none');
            },
            success:function(data) {
                document.getElementById("live-save-status").innerHTML = data;
                $("#live-save-status").css('display', 'block');
            }
        });

    });

});



