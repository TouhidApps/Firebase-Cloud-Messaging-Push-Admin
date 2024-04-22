$('#errorMessageDiv').addClass('hidden');
var myApiToken = "";

$('#btnToken').click(function() {
    hideAlert();
    loadToken();
});

$('#btnSubmit').click(function() {
    hideAlert();
    sendPush();
});

$('#btnAlert').click(function() {
    hideAlert();
});

function showAlert(msg) {
    $('#errorMessage').html(msg);
    $('#errorMessageDiv').removeAttr("hidden", '');
} // showAlert

function hideAlert() {
    $('#errorMessage').html('');
    $('#errorMessageDiv').attr("hidden", '');
} // hideAlert

function makeTokenEmpty() {
    myApiToken = '';
    $('#token').html('');
    $('#tokenCounter').html('');
    $('#btnToken').removeAttr("hidden", '');
    $('#btnInfoToken').removeAttr("hidden", '');
} // resetToken

function loadToken() {
    $.ajax({
        url: 'token.php',
        type: 'GET',
        success: function(msg) {
           // alert('Email Sent--'+msg);
            console.log("success...." + msg);

            myApiToken = msg;
            $('#token').html('API Token Generated: ' + msg.substring(0, 20) + "...");
            $('#pushResponse').html(''); // clean message
            $('#btnToken').attr("hidden", '');
            $('#btnInfoToken').attr("hidden", '');

            startCountDown();
        }
    });
} // loadToken

function sendPush() {

    if (myApiToken == '') {
        
        showAlert('Please generate Google API token first (Press enable button)');
        
        return;
    }

    var isDeviceToken = $('#deviceToken').prop('checked');
    var tokenOrTopic  = $('#pushToken').val().trim();
    var pushType      = $('#pushType').val().trim();
    var pushTitle     = $('#pushTitle').val().trim();
    var pushBody      = $('#pushBody').val().trim();
    var pushImage     = $('#pushImage').val().trim();

    if (!tokenOrTopic) {
        showAlert('Please Enter Token or Topic');
        return;
    }


    if (!pushTitle) {
        showAlert('Please Enter a title');
        return;
    }

    if (!pushBody) {
        showAlert('Please Enter a body');
        return;
    }

    const dataPayload = {
        apiToken    : myApiToken,
        deviceToken : isDeviceToken ? tokenOrTopic : "/topics/" + tokenOrTopic,

        pushType    : pushType,
        pushTitle   : pushTitle,
        pushBody    : pushBody,
        pushImage   : pushImage
    }

    const jsonStrNewLine = JSON.stringify(dataPayload, null, "\t");
    $('#pushRequestObject').html("Request JSON:\n<pre>" + jsonStrNewLine + "</pre>");


    $.ajax({
        url: 'push.php',
        type: 'POST',
        data: dataPayload,
        success: function (data) {
            // let d = JSON.stringify(data, null, "\t");

            $("#pushResponseObject").html("Response:<br/><pre>"+data+"</pre>");


            try {
                var jsonObject = JSON.parse(data);

                if (jsonObject.error && jsonObject.error.status && jsonObject.error.status === "UNAUTHENTICATED") {
                    showAlert('Authenticate error, Reload the page and try gain.');
                }

                if (jsonObject.name) {
                    showAlert('Push send successfully ✅ ' + jsonObject.name);
                } else {

                    if (jsonObject.errorMessage) {
                        showAlert(jsonObject.errorMessage);
                    } else {
                        showAlert('Something went wrong! Please see the response data');
                    }
                    
                }

            } catch (e) {
                console.log("Error parsing JSON!", e);
            }




            console.log(data);
        },
        error: function (xhr, status, error) {
            $("#pushResponseObject").html("Response:<br/>Something wrong: (Please check: Server Key/Token/Topic) " + error);
            $("#pushResponseObject").css("background-color", "red");
            console.log(error);
        }
    });

} // sendPush



// https://stackoverflow.com/questions/41035992/jquery-countdown-timer-for-minutes-and-seconds
function startCountDown() {
    var timer2 = "59:01";
    var interval = setInterval(function() {
      var timer = timer2.split(':');
      //by parsing integer, I avoid all extra string processing
      var minutes = parseInt(timer[0], 10);
      var seconds = parseInt(timer[1], 10);
      --seconds;
      minutes = (seconds < 0) ? --minutes : minutes;
      if (minutes < 0) clearInterval(interval);
      seconds = (seconds < 0) ? 59 : seconds;
      seconds = (seconds < 10) ? '0' + seconds : seconds;
      // minutes = (minutes < 10) ?  minutes : minutes;
      $('#tokenCounter').html('This token will expire in: ' + minutes + ':' + seconds + ' Min');
      timer2 = minutes + ':' + seconds;

      if (minutes < 0) {
        
        makeTokenEmpty();
        
      }

    }, 1000);
} // startCountDown


function setPushDataToUI(pushType, title, body, imgUrl) {
    $('#pushType').val(pushType);
    $('#pushTitle').val(title.substring(0, 80));
    $('#pushBody').val(body.substring(0, 180));
    $('#pushImage').val(imgUrl);
}

function loadAppContent() {

    $.ajax({
        url: 'loadAppData.php',
        type: 'GET',
        dataType: 'json', // Expected data type from the server
        // dataType: 'jsonp', // Expected data type from the server
        success: function (data) {
            let d = JSON.stringify(data, null, "\t");

            // $("#pushResponseObject").html("Response:<br/><pre>"+data+"</pre>");

            var fullListHtml = '';
            for (let i = 0; i < data.myJsonObject.length; i++) {

                var mName = data.myJsonObject[i].name;
                var mDetails = data.myJsonObject[i].details;
                var imgUrl = data.myJsonObject[i].imgData.baseUrl + data.myJsonObject[i].imgData.fileName;

                var myDataRow = '<a href="#" onclick="setPushDataToUI(\'MY_CONTENT\', \'' + mName + '\', \'' + mDetails + '\', \'' + imgUrl + '\');" class="list-group-item list-group-item-action flex-column align-items-start">';
                myDataRow += '<div class="d-flex w-100 justify-content-start">';
                myDataRow += '<img src="' + imgUrl + '" alt="Sample Image" class="img-fluid mr-3" style="width: 100px;"/>';
                myDataRow += '<div class="ms-3">';

                myDataRow += '<h5 class="mb-1">' + mName + '</h5>';
                myDataRow += '<p class="mb-1">' + mDetails + '</p>';

                myDataRow += '</div>';
                myDataRow += '</div>';
                myDataRow += '</a>';
                fullListHtml += myDataRow;
            }

            

            // $('#myDataList').html(d);
            $('#myDataList').html(fullListHtml);


            // try {
            //     var jsonObject = JSON.parse(data);

            //     if (jsonObject.error && jsonObject.error.status && jsonObject.error.status === "UNAUTHENTICATED") {
            //         showAlert('Authenticate error, Reload the page and try gain.');
            //     }

            //     if (jsonObject.name) {
            //         showAlert('Push send success ' +jsonObject.name);
            //     } else {
            //         showAlert('Push send success 2 ' +jsonObject.name);
            //     }

            // } catch (e) {
            //     console.log("Error parsing JSON!", e);
            // }




            console.log(data);
        },
        error: function (xhr, status, error) {
            // $("#pushResponseObject").html("Response:<br/>Something wrong: (Please check: Server Key/Token/Topic) " + error);
            // $("#pushResponseObject").css("background-color", "red");
            console.log(""+error);
        }
    });

} // loadAppContent

loadAppContent(); // calling it to load when page load







