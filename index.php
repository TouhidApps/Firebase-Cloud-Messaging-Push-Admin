<!DOCTYPE html>
<html>
<head>
    <title>Firebase Push Notification (Admin)</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>



    <style type="text/css">
        .container {
            height: 100%;
            justify-content: center;
            align-items: center;
        }
        .form-label {
            width: 100%;
            padding: 6px;
        }
    </style>
    
</head>
<body>
    <div class="container">
            
        <h1 class="text-center">Firebase Messeging (Push Notification)</h1>
        <hr/>
        <br/>
        <div>

            
            <div class="card" style="width: 100%">
                <div class="card-body">
                    <p class="form-text" id="btnInfoToken">
                        Click below button to get Google API token for one hour.
                    </p>
                    <input type="submit" id="btnToken" value="Enable Push Service" class="btn btn-outline-danger btn-sm" />
                    <p id="token"></p>
                    <p class="text-info" id="tokenCounter"></p>
                </div>
            </div>


            <br/>

<!-- Device Token / Topic -->
            <div class="row">
                <div class="col-sm-2 col-12">
                    <label for="pushToken" class="form-label">Device Token / Topic:</label>
                </div>

                <div class="col-sm-1 col-12">
                    <input type="radio" id="deviceToken" name="isDeviceToken" value="0">
                    <label for="deviceToken" class="form-text">Token</label><br>
                    <input type="radio" id="topic" name="isDeviceToken" value="1" checked>
                    <label for="topic" class="form-text">Topic</label><br>
                </div>
                
                <div class="col-sm-9 col-12">
                    <input placeholder="Push Token or Topic" type="text" id="pushToken" value="global" class="form-control" aria-describedby="pushTypeBlock"/>
                </div>
            </div>
            <div id="pushTypeBlock" class="form-text">
              &emsp; From app you can get device token to send to a specific device. If you want to send a group of user then use topic which is subscribed from the app.
            </div>

            <br/>
<!-- Push type -->
            <div class="row">
                <div class="col-sm-2 col-12">
                    <label for="pushType" class="form-label">Push Type:</label>
                </div>
                
                <div class="col-sm-10 col-12">
                    <input placeholder="Push Type" type="text" id="pushType" value="general" class="form-control" aria-describedby="pushTypeBlock"/>
                </div>
            </div>
            <div id="pushTypeBlock" class="form-text">
              &emsp; App is handling what type of push you are sending. Ex: general, transaction, greetings. Can be empty if app is not handling it.
            </div>

            <br/>
<!-- Push Title -->
            <div class="row">
                <div class="col-sm-2 col-12">
                    <label for="pushTitle" class="form-label">Push Title:</label>
                </div>
                
                <div class="col-sm-10 col-12">
                    <input placeholder="Push Title" type="text" id="pushTitle" class="form-control" aria-describedby="pushTitleBlock"/>
                </div>
            </div>
            <div id="pushTitleBlock" class="form-text">
              &emsp; Title will show as a push notificatin title.
            </div>
                
                
            <br/>
<!-- Push Body -->
            <div class="row">
                <div class="col-sm-2 col-12">
                    <label for="pushBody" class="form-label">Push Body:</label>
                </div>
                
                <div class="col-sm-10 col-12">
                    <textarea placeholder="Push Body" type="text" id="pushBody" class="form-control" aria-describedby="pushBodyBlock"></textarea>
                </div>
            </div>
            <div id="pushBodyBlock" class="form-text">
              &emsp; Body will show as a push detail text.
            </div>



            <br/>
<!-- Push Image -->
            <div class="row">
                <div class="col-sm-2 col-12">
                    <label for="pushImage" class="form-label">Push Image:</label>
                </div>
                
                <div class="col-sm-10 col-12">
                    <input placeholder="Push Image URL" type="text" id="pushImage" class="form-control" aria-describedby="pushImageBlock"/>
                </div>
            </div>
            <div id="pushBodyBlock" class="form-text">
              &emsp; Image will show in notification.
            </div>


            <br/>
            <p id="pushResponse"></p>
            <br/>
            <!-- Alert -->
            <div id="errorMessageDiv" class="alert alert-info alert-dismissible fade show" hidden>
                <strong>Alert!</strong> <span id="errorMessage"></span>
                <button id="btnAlert" type="button" class="btn-close" aria-label="Close"></button>
            </div>
            <br/>
            <input type="submit" id="btnSubmit" value="Send Push" class="btn btn-outline-info" />
            <br/>
            <br/>
            <p id="pushRequestObject"></p>
            <p id="pushResponseObject"></p>


        </div>




<!-- My App/WebSite Content List -->

        <h3 class="text-center">You can choose an item to fill up the push data</h3>

        <div class="list-group" id="myDataList">

            <a href="#" onclick="setPushDataToUI('MY_CONTENT', 'Title of Item 1', 'body text', 'https://img url');" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start">
                    <img src="https://via.placeholder.com/100" alt="Sample Image" class="img-fluid mr-3" style="width: 100px;"/>
                    <div class="ms-3">
                        <h5 class="mb-1">Title of Item 1</h5>
                        <p class="mb-1">Subtitle of Item 1</p>
                    </div>
                </div>
            </a>

            <a href="#" onclick="setPushDataToUI('MY_CONTENT', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'https://img url');" class="list-group-item list-group-item-action flex-column align-items-start">
                <div class="d-flex w-100 justify-content-start">
                    <img src="https://via.placeholder.com/100" alt="Sample Image" class="img-fluid mr-3" style="width: 100px;"/>
                    <div class="ms-3">
                        <h5 class="mb-1">Title of Item 1</h5>
                        <p class="mb-1">Subtitle of Item 1</p>
                    </div>
                </div>
            </a>


        </div>





<br/>
<br/>
<br/>
<br/>





    </div>

    
<script type="text/javascript" src="push.js"></script>
    
</body>
</html>
