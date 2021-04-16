<?php

$dbname = 'database_name';
$dbuser = 'root';// NEVER use root in production!
$dbpass = 'your_password';
$dbhost = 'mysql';// see, that you can put container name here!

$link = mysqli_connect($dbhost, $dbuser, $dbpass) or die("Unable to Connect to '$dbhost'");
mysqli_select_db($link, $dbname) or die("Could not open the db '$dbname'");

$test_query = "SHOW TABLES FROM $dbname";
$result = mysqli_query($link, $test_query);
?>

<!DOCTYPE html>
<html>
    <head>
      <script src="js/mqttws31.min.js" type="text/javascript"></script>
      <script src="js/jquery-3.5.1.slim.min.js"></script>
        <title>TEST</title>
    </head>
    <body>
        <h1>HELLO WORLD</h1>
        <h2>Database check</h2>
          <?php
            $tblCnt = 0;
            while($tbl = mysqli_fetch_array($result)) {
              $tblCnt++;
              echo $tbl[0]."<br />\n";
            }
            
            if (!$tblCnt) {
              echo "There are no tables<br />\n";
            } else {
              echo "There are $tblCnt tables<br />\n";
            } 
          ?>
        <h2>MQTT check</h2>
          <button onclick="client.connect(options);" type="button">Connect</button>
          <button onclick="client.subscribe('#', {qos: 2}); $('#messages').prepend('<span>Subscribed</span><br/>');;" type="button">Subscribe</button>  
          <button onclick="client.disconnect();" type="button">Disconnect</button><br />  
          <button onclick="publish('TEST', 'wago/test', 2);" type="button">Test message</button><br />
          <samp id="messages"></samp>
        <h2>PHP check</h2>
        <?php
          phpinfo();
        ?>

        <script type="text/javascript">
          //Using the HiveMQ public Broker, with a random client Id
          var client = new Paho.MQTT.Client(window.location.hostname, Number(9001), "myclientid_" + parseInt(Math.random() * 100, 10));

          //Gets  called if the websocket/mqtt connection gets disconnected for any reason
          client.onConnectionLost = function (responseObject) {
            //Depending on your scenario you could implement a reconnect logic here
            //alert("connection lost: " + responseObject.errorMessage);
            $('#messages').prepend('<span>connection lost: ' + responseObject.errorMessage +'</span><br/>');
          };

          //Gets called whenever you receive a message for your subscriptions
          client.onMessageArrived = function (message) {
              //Do something with the push message you received
              var today = new Date();
              var msg = addZero(today.getHours()) + ':' + addZero(today.getMinutes()) + ':' + addZero(today.getSeconds()) +' | Topic: ' + message.destinationName + '  | ' + message.payloadString;
              $('#messages').prepend('<span>'+ msg + '</span><br/>');
          };

          //Connect Options
          var options = {
              timeout: 3,
              //Gets Called if the connection has sucessfully been established
              onSuccess: function () {
                //alert("Connected");
                $('#messages').prepend('<span>Connected</span><br/>');
              },
              //Gets Called if the connection could not be established
              onFailure: function (message) {
                //alert("Connection failed: " + message.errorMessage);
                $('#messages').prepend('<span>'+ "Connection failed: " + message.errorMessage + '</span><br/>');
              },
              cleanSession : true
          };

          //Creates a new Messaging.Message Object and sends it to the HiveMQ MQTT Broker
          var publish = function (payload, topic, qos) {
              //Send your message (also possible to serialize it as JSON or protobuf or just use a string, no limitations)
              var message = new Paho.MQTT.Message(payload);
              message.destinationName = topic;
              message.qos = qos;
              client.send(message);
          }

          function addZero(i) {
              if (i < 10) {
                  i = "0" + i;
              }
              return i;
          }
      </script>
    </body>
</html>


