<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Web Push</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="//messaging-public.realtime.co/js/2.1.0/ortc.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://www.gstatic.com/firebasejs/4.1.1/firebase.js"></script>
        <script>
            // get following from firebase by clicking web 
            // Initialize Firebase
            var config = {
                apiKey: " AIzaSyA6Cy7xoxG-gwBrtpLJ6W9NFvbU66XFY80",
                authDomain: "lead-jenie.firebaseapp.com",
                databaseURL: "",
                projectId: "lead-jenie",
                storageBucket: "",
                messagingSenderId: "963593115011"
            };
            firebase.initializeApp(config);

            // [START get_messaging_object]
            // Retrieve Firebase Messaging object.
            const messaging = firebase.messaging();
            // [END get_messaging_object]

            jQuery(document).ready(function ($) {

                if (location.protocol != 'https:') {
                    resetUI();

                    if (isTokenSentToServer()) {
                        getLocation();
                    }

                }else{
                    console.log('Here');
                }

            });

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    console.log("Geolocation is not supported by this browser.");
                }
            }
            function showPosition(position) {
                console.log("Latitude: ", position.coords.latitude,
                        "Longitude: ", position.coords.longitude);

//                var jqxhr = jQuery.ajax("/junaid/saveLatLng.php?mid=11111&lat=" + position.coords.latitude + "&lng=" + position.coords.longitude)
//                        .done(function () {
//                            console.log("success");
//
//                        })
//                        .fail(function () {
//                            console.log("error");
//
//                        })
//                        .always(function () {
//                            console.log("complete");
//                        });
            }

            // IDs of divs that display Instance ID token UI or request permission UI.
            const tokenDivId = 'token_div';
            const permissionDivId = 'permission_div';
            // [START refresh_token]
            // Callback fired if Instance ID token is updated.
            messaging.onTokenRefresh(function () {
                messaging.getToken()
                        .then(function (refreshedToken) {
                            console.log('Token refreshed.');
                            // Indicate that the new Instance ID token has not yet been sent to the
                            // app server.
                            setTokenSentToServer(false);
                            // Send Instance ID token to app server.

                            sendTokenToServer(refreshedToken);
                            // [START_EXCLUDE]
                            // Display new Instance ID token and clear UI of all previous messages.
                            resetUI();
                            // [END_EXCLUDE]
                        })
                        .catch(function (err) {
                            console.log('Unable to retrieve refreshed token ', err);
                            showToken('Unable to retrieve refreshed token ', err);
                        });
            });
            // [END refresh_token]
            // [START receive_message]
            // Handle incoming messages. Called when:
            // - a message is received while the app has focus
            // - the user clicks on an app notification created by a sevice worker
            //   `messaging.setBackgroundMessageHandler` handler.
            messaging.onMessage(function (payload) {
                console.log("Message received. ", payload);
                // [START_EXCLUDE]
                // Update the UI to include the received message.
                appendMessage(payload);
                // [END_EXCLUDE]
            });
            // [END receive_message]


            function resetUI() {
                clearMessages();
                showToken('loading...');
                // [START get_token]
                // Get Instance ID token. Initially this makes a network call, once retrieved
                // subsequent calls to getToken will return from cache.
                messaging.getToken()
                        .then(function (currentToken) {
                            if (currentToken) {
                                sendTokenToServer(currentToken);
//                                updateUIForPushEnabled(currentToken);
                            } else {
                                // Show permission request.
                                console.log('No Instance ID token available. Request permission to generate one.');
                                // Show permission UI.
                                requestPermission();
                                updateUIForPushPermissionRequired();
                                setTokenSentToServer(false);
                            }
                        })
                        .catch(function (err) {
                            console.log('An error occurred while retrieving token. ', err);
                            showToken('Error retrieving Instance ID token. ', err);
                            setTokenSentToServer(false);
                        });
            }
            // [END get_token]
            function showToken(currentToken) {
                // Show token in console and UI.
                //var tokenElement = document.querySelector('#token');
                //tokenElement.textContent = currentToken;
                console.log(currentToken);

            }
            // Send the Instance ID token your application server, so that it can:
            // - send messages back to this app
            // - subscribe/unsubscribe the token from topics
            function sendTokenToServer(currentToken) {
                if (!isTokenSentToServer()) {
                    console.log('Sending token to server...');
                    // TODO(developer): Send the current token to your server.
                    
                    console.log(currentToken);

//                    var jqxhr = jQuery.ajax("/junaid/Notify_saveIDs_v2.php?customerID=&device_OS=4&regID=" + currentToken)
//                            .done(function () {
//                                console.log("success");
//                                setTokenSentToServer(true);
//                            })
//                            .fail(function () {
//                                console.log("error");
//                                setTokenSentToServer(false);
//                            })
//                            .always(function () {
//                                console.log("complete");
//                            });


                } else {
                    console.log('Token already sent to server so won\'t send it again ' +
                            'unless it changes');
                }
            }
            function isTokenSentToServer() {
                return window.localStorage.getItem('sentToServer') == 1;
            }
            function setTokenSentToServer(sent) {
                window.localStorage.setItem('sentToServer', sent ? 1 : 0);
            }
            function showHideDiv(divId, show) {
                console.log("show div", show)
            }
            function requestPermission() {
                console.log('Requesting permission...');
                // [START request_permission]
                messaging.requestPermission()
                        .then(function () {
                            console.log('Notification permission granted.');
                            // TODO(developer): Retrieve an Instance ID token for use with FCM.
                            // [START_EXCLUDE]
                            // In many cases once an app has been granted notification permission, it
                            // should update its UI reflecting this.
                            resetUI();
                            // [END_EXCLUDE]
                        })
                        .catch(function (err) {
                            console.log('Unable to get permission to notify.', err);
                        });
                // [END request_permission]
            }
            function deleteToken() {
                // Delete Instance ID token.
                // [START delete_token]
                messaging.getToken()
                        .then(function (currentToken) {
                            messaging.deleteToken(currentToken)
                                    .then(function () {
                                        console.log('Token deleted.');
                                        setTokenSentToServer(false);
                                        // [START_EXCLUDE]
                                        // Once token is deleted update UI.
                                        resetUI();
                                        // [END_EXCLUDE]
                                    })
                                    .catch(function (err) {
                                        console.log('Unable to delete token. ', err);
                                    });
                            // [END delete_token]
                        })
                        .catch(function (err) {
                            console.log('Error retrieving Instance ID token. ', err);
                            showToken('Error retrieving Instance ID token. ', err);
                        });
            }
            // Add a message to the messages element.
            function appendMessage(payload) {
                console.log(payload);
            }
            // Clear the messages element of all children.
            function clearMessages() {
                console.log("messages cleared")
            }
            function updateUIForPushEnabled(currentToken) {
                showHideDiv(tokenDivId, true);
                showHideDiv(permissionDivId, false);
                showToken(currentToken);
            }
            function updateUIForPushPermissionRequired() {
                showHideDiv(tokenDivId, false);
                showHideDiv(permissionDivId, true);
            }

        </script>
    </head>
    <body>
        <div class="" id="token_div"></div>
        <div class="" id="permission_div"></div>
    </body>
</html>
