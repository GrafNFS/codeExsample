<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
for ($i = 0; $i <= 50; $i++) {
?>
[<?php echo $i?>](managers-phones)
callerid="Number <?php echo $i?>" <<?php echo $i?>>
<?php
}
?>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>
    var conn = new WebSocket('ws://appserv.247avare.com:10000/chat');
    conn.onmessage = function(e) {
        console.log('Response:' + e.data);
    };
    conn.onopen = function(e) {
        console.log("Connection established!");
        console.log('Hey!');
        conn.send('Hey!');
    };
//    $(function() { // /web/index.php/api/message
//        var chat = new WebSocket('ws://localhost:10009');
//        chat.onmessage = function(e) {
//            $('#response').text('');
//
//            var response = JSON.parse(e.data);
//            if (response.type && response.type == 'chat') {
//                $('#chat').append('<div><b>' + response.from + '</b>: ' + response.message + '</div>');
//                $('#chat').scrollTop = $('#chat').height;
//            } else if (response.message) {
//                $('#response').text(response.message);
//            }
//        };
//        chat.onopen = function(e) {
//            $('#response').text("Connection established! Please, set your username.");
//        };
//        $('#btnSend').click(function() {
//            if ($('#message').val()) {
//                chat.send( JSON.stringify({'action' : 'chat', 'message' : $('#message').val()}) );
//            } else {
//                alert('Enter the message')
//            }
//        })
//
//        $('#btnSetUsername').click(function() {
//            if ($('#username').val()) {
//                chat.send( JSON.stringify({'action' : 'setName', 'name' : $('#username').val()}) );
//            } else {
//                alert('Enter username')
//            }
//        })
//    })
</script>