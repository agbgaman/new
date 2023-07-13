/*===========================================================================
*
*  AUDIO FILE UPLOAD - FILEPOND PLUGIN
*
*============================================================================*/

FilePond.registerPlugin( 

   FilePondPluginFileValidateSize,
   FilePondPluginFileValidateType

);

var pond = FilePond.create(document.querySelector('.filepond'));
var all_types;
var maxFileSize;
var type = '';
var typeShow = '';
var fileType;

$.ajax({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
    },
    type: "GET",
    url: 'speech-to-text/settings',

  }).done(function(data) {

      maxFileSize = data['size'] + 'MB';
      all_types = data['type'];

     checkExtensions(all_types, data['type_show']);
     
     fileType = type;

      FilePond.setOptions({
         
          allowMultiple: false,
          maxFiles: 1,
          allowReplace: true,
          maxFileSize: maxFileSize,
          labelIdle: "Drag & Drop your audio file or <span class=\"filepond--label-action\">Browse</span><br><span class='restrictions'>[<span class='restrictions-highlight'>" + maxFileSize + "</span>: " + typeShow + "]</span>",
          required: true,
          instantUpload:false,
          storeAsFile: true,
          acceptedFileTypes: fileType,
          labelFileProcessingError: (error) => {
            console.log(error);
          }
    
      });

});


function checkExtensions(all_types, types) {

  'use strict';

  var id = document.getElementById('languages').value;

  if (id > 37) {
    typeShow = "WAV, FLAC";
    all_types.forEach(function (item, index, array) {
        if (index === array.length - 1){ 
          if ((item == 'audio/wav') || (item == 'audio/flac')) {
            type += item;
          }          
        } else {
          if ((item == 'audio/wav') || (item == 'audio/flac')) {
            type += item + ',';
          }
        }        
    });

    FilePond.setOptions({
      labelIdle: "Drag & Drop your audio file or <span class=\"filepond--label-action\">Browse</span><br><span class='restrictions'>[<span class='restrictions-highlight'>" + maxFileSize + "</span>: " + typeShow + "]</span>",
      acceptedFileTypes: type,
    });

  } else {
    typeShow = "WAV, MP3, MP4, Ogg, WebM, FLAC";
    all_types.forEach(function (item, index, array) {
        if (index === array.length - 1){ 
            type += item;       
        } else {
            type += item + ',';
        }        
    });

    FilePond.setOptions({
      labelIdle: "Drag & Drop your audio file or <span class=\"filepond--label-action\">Browse</span><br><span class='restrictions'>[<span class='restrictions-highlight'>" + maxFileSize + "</span>: " + typeShow + "]</span>",
      acceptedFileTypes: type,
    });
  }  
}



/*===========================================================================
*
*  DISPLAY SPEAKER IDENTIFICATION
*
*============================================================================*/
$(document).ready(function(){

  "use strict";

  if (document.getElementById('type').value == 'true') {
    $('#speakers-box').fadeIn();
  } else {
      $('#speakers-box').fadeOut();
  }
})

function displaySpeakerIdentification() {

  "use strict";

  if (document.getElementById('type').value == 'true') {
      $('#speakers-box').fadeIn();
  } else {
      $('#speakers-box').fadeOut();
  }
}



/*===========================================================================
*
*  TRANSCRIBE AUDIO FILE
*
*============================================================================*/
 $('#transcribe-audio').on('submit',function(e) {

  "use strict";

  e.preventDefault()
  
  var inputAudio = [];
  var duration;
  var duration2;
  
  if (pond.getFiles().length !== 0) {   
      pond.getFiles().forEach(function(file) {
      inputAudio.push(file);
    });
  }

  var audio = document.createElement('audio');
  var objectUrl = URL.createObjectURL(inputAudio[0].file);

  audio.src = objectUrl;
  audio.addEventListener('loadedmetadata', function(){
    duration = audio.duration;
  },false);

  var form = $(this);
  var formData = new FormData(this);

  setTimeout(function() {

    formData.append('audiofile', inputAudio[0].file);
    formData.append('audiolength', duration);
    formData.append("taskType", 'file');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: form.attr('action'),
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function() {
            $('#transcribe').html('');
            $('#transcribe').prop('disabled', true);
            $('#processing').show().clone().appendTo('#transcribe'); 
            $('#processing').hide();          
        },
        complete: function() {
            $('#transcribe').prop('disabled', false);
            $('#processing', '#transcribe').empty().remove();
            $('#processing').hide();
            $('#transcribe').html('Transcribe');            
        },
        success: function(data) {},
        error: function(data) {
            if (data.responseJSON['error']) {
                $('#notificationModal').modal('show');
                $('#notificationMessage').text(data.responseJSON['error']);
            }

            $('#transcribe').prop('disabled', false);
            $('#transcribe').html('Transcribe');   
            
            if (pond.getFiles().length != 0) {
                for (var j = 0; j <= pond.getFiles().length - 1; j++) {
                    pond.removeFiles(pond.getFiles()[j].id);
                }
            }
          
            inputAudio = [];
        }
    }).done(function(data) {
      $("#audioResultsTable").DataTable().ajax.reload();
      if (pond.getFiles().length != 0) {
          for (var j = 0; j <= pond.getFiles().length - 1; j++) {
              pond.removeFiles(pond.getFiles()[j].id);
          }
      }
    
      inputAudio = [];
    })

  }, 500);  

});



/*===========================================================================
*
*  CHECK VENDOR LANGUAGE FEATURES
*
*============================================================================*/
$(document).ready(function() {
  
  "use strict";

  var id = document.getElementById('languages').value;

  if (id > 37) {

      var supported = [67, 78, 85, 88, 89, 95, 100, 109, 111, 129, 133, 154];
      if (supported.includes(parseInt(id))) {
          document.getElementById("removable-type").style.display = "block";
          document.getElementById("removable-speaker").style.display = "block";
      } else {
          document.getElementById("removable-type").style.display = "none";
          document.getElementById("removable-speaker").style.display = "none";
      }

  } else {
      document.getElementById("removable-type").style.display = "block";
      document.getElementById("removable-speaker").style.display = "block";
  }

});


function processLanguageFeature(value) {

  "use strict";

  if (value > 37) {

      checkVendorExtensions(['audio/flac', 'audio/wav']);

      var supported = [67, 78, 85, 88, 89, 95, 100, 109, 111, 129, 133, 154];
      if (supported.includes(parseInt(value))) {
          document.getElementById("removable-type").style.display = "block";
          document.getElementById("removable-speaker").style.display = "block";
      } else {
          document.getElementById("removable-type").style.display = "none";
          document.getElementById("removable-speaker").style.display = "none";
      }


  } else {

      checkVendorExtensions(['audio/flac', 'audio/mpeg', 'audio/mp4', 'audio/ogg', 'audio/webm', 'audio/wav']);

      document.getElementById("removable-type").style.display = "block";
      document.getElementById("removable-speaker").style.display = "block";

      $('.aws-speakers').css('display', 'block');
  }
}



function checkVendorExtensions(all_types) {

  'use strict';

  var id = document.getElementById('languages').value;
  var vendorType = '';

  if (id > 37) {
    typeShow = "WAV, FLAC";
    all_types.forEach(function (item, index, array) {
        if (index === array.length - 1){ 
          if ((item == 'audio/wav') || (item == 'audio/flac')) {
            vendorType += item;
          }          
        } else {
          if ((item == 'audio/wav') || (item == 'audio/flac')) {
            vendorType += item + ',';
          }
        }        
    });

    FilePond.setOptions({
      labelIdle: "Drag & Drop your audio file or <span class=\"filepond--label-action\">Browse</span><br><span class='restrictions'>[<span class='restrictions-highlight'>" + maxFileSize + "</span>: " + typeShow + "]</span>",
      acceptedFileTypes: vendorType,
    });

  } else {
    typeShow = "WAV, MP3, MP4, Ogg, WebM, FLAC";
    all_types.forEach(function (item, index, array) {
        if (index === array.length - 1){ 
            vendorType += item;       
        } else {
            vendorType += item + ',';
        }        
    });

    FilePond.setOptions({
      labelIdle: "Drag & Drop your audio file or <span class=\"filepond--label-action\">Browse</span><br><span class='restrictions'>[<span class='restrictions-highlight'>" + maxFileSize + "</span>: " + typeShow + "]</span>",
      acceptedFileTypes: vendorType,
    });
  }  
}


