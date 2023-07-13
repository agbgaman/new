/*===========================================================================
*
*  SHOW AWS CONVERSATION RESULT
*
*============================================================================*/
let final_result;

function processAWSConversation(raw, text) {

    let speaker_number;
    let speaker_identity = "";

    let speakerNames = {};
        speakerNames['spk_0'] = "Speaker#1";
        speakerNames['spk_1'] = "Speaker#2";
        speakerNames['spk_2'] = "Speaker#3";
        speakerNames['spk_3'] = "Speaker#4";
        speakerNames['spk_4'] = "Speaker#5";
        speakerNames['spk_5'] = "Speaker#6";
        speakerNames['spk_6'] = "Speaker#7";

 
        let response = raw;
        let speaker_text = '';

        speaker_number = response.results.speaker_labels.speakers;                    
            
        for (let i = 0; i < response.results.speaker_labels.segments.length; i++) { 
    
            speaker_identity += speakerNames[response.results.speaker_labels.segments[i].speaker_label] + ":\n" + formatTime(response.results.speaker_labels.segments[i].start_time)+" - " + formatTime(response.results.speaker_labels.segments[i].end_time) + " \n";
            
            speaker_time = speakerNames[response.results.speaker_labels.segments[i].speaker_label] + ": " + formatTime(response.results.speaker_labels.segments[i].start_time)+" - " + formatTime(response.results.speaker_labels.segments[i].end_time);
            speaker_text = '';
            
             for(let j = 0; j < response.results.items.length; j++) {
    
                if(parseFloat(response.results.speaker_labels.segments[i].start_time) <= parseFloat(response.results.items[j].start_time) && parseFloat(response.results.items[j].end_time) <= parseFloat(response.results.speaker_labels.segments[i].end_time)) {

                    let wordBoundary = "";
    
                    switch(response.results.items[j].type) {
                        case "pronunciation":
                            wordBoundary = " ";
                            break;
                        case "punctuation":
                            wordBoundary = "";
                            break;
                        default:
                            wordBoundary = "";
                    }
    
                    speaker_identity += wordBoundary + (response.results.items[j].alternatives[0].content).replace(/\"/g, "") ;
                    speaker_text += wordBoundary + (response.results.items[j].alternatives[0].content).replace(/\"/g, "") ;
                }
            }

            $('#transcript-table').find('tbody:last').append('<tr><td>' + speaker_time + '</td><td>' + speaker_text + '</td></tr>');
    
            speaker_identity += "\n\n";             
            
        }

        final_result = speaker_identity;
}



/*===========================================================================
*
*  SHOW AWS DICTATION RESULT
*
*============================================================================*/

function processAWSDictation(raw, clean_text) {

    let response = raw;
    let text = '';
    let start_time = '';
    let end_time = '';
    let new_line = true;

    final_result = clean_text;
        
    for (let i = 0; i < response.results.items.length; i++) { 

        if (new_line) {
            start_time = formatTime(response.results.items[i].start_time);
        }
        
        if (response.results.items[i].type == 'punctuation' && response.results.items[i].alternatives[0].content == '.') {

            time = start_time + " - " + end_time;
            text += '.';

            $('#transcript-table').find('tbody:last').append('<tr><td>' + time + '</td><td>' + text + '</td></tr>');

            text = '';
            new_line = true;
    
            continue;
            
        } else {

            let wordBoundary = "";
            new_line = false;

            switch(response.results.items[i].type) {
                case "pronunciation":
                    wordBoundary = " ";
                    break;
                case "punctuation":
                    wordBoundary = "";
                    break;
                default:
                    wordBoundary = "";
            }

            text += wordBoundary + (response.results.items[i].alternatives[0].content).replace(/\"/g, "") ; 
        }

        if (response.results.items[i].type != 'punctuation') {
            end_time = formatTime(response.results.items[i].end_time);
        }        
        
    }
    
}



/*===========================================================================
*
*  SHOW GCP CONVERSATION RESULT
*
*============================================================================*/

function processGCPConversation(raw, text) {

        let response = raw;
        let words = response['alternatives'][0]['words'];
        let speaker = 0, start = '', end = '';
        let speaker_text = '';
        let speaker_time = '';
        let speaker_result = '';

        words.forEach(function (item, index) {

            if (speaker != item['speakerTag']) {

                speaker = item['speakerTag'];
                start = item['startTime'];
                end = item['endTime'];

                speaker_time = 'Speaker#' + speaker + ': ' + start + ' - ';
                speaker_text += item['word'] + " ";
                
                let dot = speaker_text.includes(".") ? true : false;
                if (dot) {
                    speaker_time += end;
                    showResults(speaker_time, speaker_text);
                    speaker_result += speaker_time + '\n';
                    speaker_result += speaker_text + '\n\n';
                    speaker_time = 'Speaker#' + speaker + ': ' + end + ' - ';
                    speaker_text = '';
                }
                   
            } else {
                speaker_text += item['word'] + " ";
                end = item['endTime'];
                
                let dot = speaker_text.includes(".") ? true : false;
                if (dot) {
                    speaker_time += end;
                    showResults(speaker_time, speaker_text)
                    speaker_result += speaker_time + '\n';
                    speaker_result += speaker_text + '\n\n';
                    speaker_time = 'Speaker#' + speaker + ': ' + end + ' - ';
                    speaker_text = '';
                }
            }
        });

        final_result = speaker_result;
}

function showResults(speaker_time, speaker_text) {
    $('#transcript-table').find('tbody:last').append('<tr><td>' + speaker_time + '</td><td>' + speaker_text + '</td></tr>');
}



/*===========================================================================
*
*  SHOW GCP DICTATION RESULT
*
*============================================================================*/

function processGCPDictation(clean_text, end_time) {

    let time = '00:00:00 - ' + end_time;

    final_result = clean_text;
        
    $('#transcript-table').find('tbody:last').append('<tr><td>' + time + '</td><td>' + clean_text + '</td></tr>');

}



/*===========================================================================
*
*  FORMAT TIME
*
*============================================================================*/

function formatTime(t) {
    let a = t.split(".");
    let date = new Date(null);
    date.setSeconds(a[0]); 
    let result = date.toISOString().substr(11, 8);
    return result + "." + a[1];
}



/*===========================================================================
*
*  DOWNLOAD TRANSCRIPT RESULT
*
*============================================================================*/

$('#download-now').on('click', function(e) {

    e.preventDefault();
    
    let d = new Date();
    let date = ("0" + d.getDate()).slice(-2) + "-" + ("0"+(d.getMonth()+1)).slice(-2) + "-" + d.getFullYear();

    let text = final_result;
    text = text.replace(/\n/g, "\r\n"); // To retain the Line breaks.
    let blob = new Blob([text], { type: "text/plain"});
    let anchor = document.createElement("a");
    anchor.download = date + "-transcribe-result.txt";
    anchor.href = window.URL.createObjectURL(blob);
    anchor.target ="_blank";
    anchor.style.display = "none"; // just to be safe!
    document.body.appendChild(anchor);
    anchor.click();
    document.body.removeChild(anchor);

});



