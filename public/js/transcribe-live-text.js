/*===========================================================================
*
*  LIVE TRANSCRIBE COUNTDOWN TIMER
*
*============================================================================*/
var time_limit;
var clock;

$.ajax({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
    },
    type: "GET",
    url: 'settings',

}).done(function(data) {

    time_limit = data['length_live'];

    clock = $(".countdown").FlipClock({
        clockFace: 'MinuteCounter',
        autoStart: false,
        countdown: false,
        showSeconds: false,
        callbacks: {
            interval: function() {
                var time = clock.getTime().time;

                // if it set according to time limit set in admin panel
                if (time >= time_limit) {
                    terminateRecording();
                    stopClock();
                    clock.stop();
                }
            }
        }
    });

    clock.setTime(0);

    // clock = $(".countdown").FlipClock(0, {
    //     countdown: true,
    //     clockFace: 'HourCounter',
    //     autoStart: false,
    //     callbacks: {
    //         stop: function() {
    //             terminateRecording();
    //             stopClock();
    //             clock.setTime(0);
    //         }
    //     }
    // });
    //
    // clock.setTime(0);

});



/*===========================================================================
*
*  02 - LIVE TRANSCRIBE ACTION BUTTON
*
*============================================================================*/
var seconds;
var initiate;
var refreshInterval;
var limit;

$(document).ready(function(){

    "use strict";

    /* -------------------------------------------- */
    /*    TOGGLE START/STOP BUTTON
    /* -------------------------------------------- */
    $("#start, #reply").on("click", function(e){

        "use strict";

        e.preventDefault();

        if ($('#start').hasClass('play')) {
            if (limit < 1) {
                $('#error').show().slideDown()
                var message = '<span>Not enough balance. Subscribe or Top up</span>';
                $('#error').show().slideDown()
                    .html(message)
                    .delay(8000)
                    .slideUp();
                clock.stop();
                $("#transcribe-audio-format").slideUp();

                $('#start').removeClass('stop').addClass('play').html('<i class="fa-solid fa-play"></i>');
                clock.setTime(0);

            } else {

                $('textarea').val('');

                $("#transcribe-audio-format").slideDown();

                initiateRecording();

                clock.start();

                $('#start').show()
                $('#start').removeClass('play').addClass('stop').html('<i class="fa fa-stop-circle"></i>');
                $('#reply').hide();
            }


        } else if ($(this).hasClass('stop')) {
            terminateRecording();
            stopClock();

            $("#transcribe-audio-format").slideUp();

            clock.stop();

            clock.setTime(0);

            $('#start').removeClass('stop').addClass('play').html('<i class="fa-solid fa-microphone-lines " ></i>');
            $('#reply').show();
            limits();

        }

    });

});


/* -------------------------------------------- */
/*    CALL WEBSOCKET.JS FUNCTIONS
/* -------------------------------------------- */

function initiateRecording() {
    "use strict";
console.log(231)
    // var language = $('#languages').find(':selected').attr('data-code');

    // setLanguage(language);
    startSeconds();
    // startLiveRecording();

}

function terminateRecording() {
    "use strict";

    // stopLiveRecording();


}

function stopClock() {
    "use strict";
    console.log('remove disable')
    $('#prev-btn, #next-btn').removeAttr('disabled');

    stopRecording();

    stopSeconds();
    limits();
    $("#transcribe-audio-format").slideUp();
    $('#start').removeClass('stop').addClass('play').html('<i class="fa-solid fa-microphone-lines " ></i>');
    $('#reply').show();
    clock.setTime(0);
}




function stopClockError() {
    "use strict";

    clock.stop();
    stopSeconds();
    $("#transcribe-audio-format").slideUp();
    $('#start').removeClass('stop').addClass('play').html('<i class="fa-solid fa-microphone-lines " ></i>');
    $('#reply').show();
    clock.setTime(0);
}


function startSeconds() {
    console.log('disable')
    "use strict";
    $("#prev-btn").prop("disabled", true);
    $("#next-btn").prop("disabled", true);

    startRecording();
    seconds = 0;
    refreshInterval = setInterval(() => {
        ++seconds;
    }, 1000);

}
function stopSeconds() {
    "use strict";
    clearInterval(refreshInterval);
}

/* -------------------------------------------- */
/*    Call Recording Js Function
/* -------------------------------------------- */

URL = window.URL || window.webkitURL;
var gumStream;
var rec;
var input;
var audioStream;
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioLengthRecorded;

function startRecording() {

    /* Simple constraints object, for more advanced audio features see https://addpipe.com/blog/audio-constraints-getusermedia/ */
    var constraints = { audio: true, video:false }
    $("#recordings").slideUp();


    /*
        We're using the standard promise based getUserMedia()
        https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
    */

    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {

        /*
          create an audio context after getUserMedia is called
          sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
          the sampleRate defaults to the one set in your OS for your playback device

        */
        audioContext = new AudioContext({
            sampleRate: 48000,
        });


        $("#transcribe-audio-format").slideDown();

        /*  assign to gumStream for later use  */
        gumStream = stream;

        /* use the stream */
        input = audioContext.createMediaStreamSource(stream);

        /*
          Create the Recorder object and configure to record mono sound (1 channel)
          Recording 2 channels  will double the file size
        */
        rec = new Recorder(input,{numChannels:1})

        //start the recording process
        rec.record()


    }).catch(function(err) {



    });
}


function stopRecording() {

    "use strict";

    $("#transcribe-audio-format").slideUp();


    clock.setTime(0);

    //tell the recorder to stop the recording
    rec.stop();

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);

    $("#recordings").slideDown().css("display", "flex");
}

function createDownloadLink(blob) {

    "use strict";
console.log(211213)
    var url = URL.createObjectURL(blob);
    var audio = $('#audio');
    var link = $('#audio-link');
    audioStream = blob;


    //name of .wav file to use during upload and download (without extendion)
    var date = new Date().getTime();
    //add controls to the <audio> element
    audio[0].src = url;
    audio[0].addEventListener('canplaythrough', (event) => {

        var seconds = event.currentTarget.duration;

        audioLengthRecorded = seconds;
    });

    var filename = date + ".wav";

    //save to disk link
    link.href = url;
    link.download = filename;
    link.innerHTML = 'Download';

    $("#recordings").slideDown();
}

$("#transcribe-record").on("click",(function(e) {
    "use strict";

    e.preventDefault();

console.log(123)
    if (seconds != 0) {
        if (audioStream) {
            var audiofile = new Blob([audioStream], {type: "audio/wav"});
            // audioStream = blob;
            console.log(audiofile)
            recordResults(audiofile, seconds);
        }
    }
}));
$("#transcribe-cancel").on("click",(function(e) {
    "use strict";

    e.preventDefault();

    $("#recordings").slideUp();
    audioStream = '';
}));

function recordResults(blob, seconds) {
    "use strict";
    const progressBar = $('.progress-bar');
    console.log(progressBar.html())
    $('.progress').removeClass("hidden");
    $('.progress-notification').removeClass("hidden");
    $('.progress-text').text('20%');
    progressBar.css("width", "20%");

    const form          = document.getElementById("live-transcribe-form");
    const activeItemId  = $('.carousel-item.active').attr('id');
    const formData      = new FormData(form);

    // audio object from recording
    formData.append("extension", 'wav');
    formData.append("taskType", 'record');
    formData.append("audiofile", blob);
    formData.append("audiolength", seconds);
    formData.append("textId", activeItemId);
    $('.progress-text').text('30%');
    progressBar.css('width', '30%');
    const uploadingImage  = $('.uploadingImage').val(activeItemId);
    $("#next-btn").click();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: $(form).attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success: function(response) {
            progressBar.css('width', '100%');
            $('.progress-text').text('100%');

            $("#resultTable").DataTable().ajax.reload();

            const activeItemId  = $('.carousel-item.active').attr('id');
            if ($('.uploadingImage').val() == activeItemId){

                $("#next-btn").click();
                $(".uploadingImage").val('');
            }

            $(".uploadingImage").val('');

            $('.progress').addClass("hidden");
            $('.progress-notification').addClass("hidden");
            $("#recordings").slideUp();
            audioStream = '';
        },
        error: function (response)
        {
            seconds = 0;

            if (response.responseJSON['error']) {
                $('#notificationModal').modal('show');
                $('#notificationMessage').text(response.responseJSON['error']);
            }
        }

    }).done(function(response) {
        $("#resultTable").DataTable().ajax.reload();
        seconds = 0;
    })
}

function limits() {

    "use strict";

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        type: "GET",
        url: 'settings/live/limits',
    }).done(function(data) {
        limit = parseFloat(data['limits']);
    });
}

