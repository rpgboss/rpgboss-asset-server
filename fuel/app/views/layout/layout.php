<!DOCTYPE html>

<html>
<head>
    <title><?php print $title; ?></title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
    <link type='text/css' rel="stylesheet" media="screen" href="<?php print Uri::create("assets/css/bootstrap.min.css") ?>">
    <link type='text/css' rel="stylesheet" media="screen" href="<?php print Uri::create("assets/css/bootstrap-theme.min.css") ?>">
    <link type='text/css' rel="stylesheet" media="screen" href="<?php print Uri::create("assets/css/main.css") ?>">
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">
    <script src="/assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-ui.min.js" type="text/javascript"></script>
    <meta charset="utf-8">
    </head>
    <body>
    <div class="header">
    <div class="container">
    <div class="col-md-3 logo">
    <a href="http://rpgboss.com">
    <img src="/assets/img/rpgboss-logo.png" alt="">
    rpgboss
    </a>
    </div>
    <div class="col-md-9">
    <ul>
    <li><a href="https://github.com/rpgboss/rpgboss">Get the Source</a></li>
    <li><a href="https://github.com/rpgboss/rpgboss/issues">BugTracker</a></li>
    <li><a href="http://rpgboss.forumatic.com/">Community Forum</a></li>
    <li><a class="active" href="http://assets.rpgboss.com/">Assets & Games</a></li>
    <li><a href="https://github.com/rpgboss/rpgboss/wiki">Tutorials</a></li>
    </ul>
    </div>
    </div>
    </div>
    <div class="userheader">
    <div class="container">
    <div class="col-md-8">
    <?php if($isAuthed): ?>
        <a class="<?php print $userpanel==-1 ? 'active' : '' ?>" href="/"><span class="icon-home"></span>Home</a>
    <a class="<?php print $userpanel==1 ? 'active' : '' ?>" href="/packagemanagement"><span class="icon-package"></span>Packagemanagement</a>
        <a class="<?php print $userpanel==4 ? 'active' : '' ?>" href="/projectmanagement"><span class="icon-game"></span>Projectmanagement</a>
        <a class="<?php print $userpanel==2 ? 'active' : '' ?>" href="/profile"><span class="icon-profile"></span>Profile</a>
        <?php if(\Auth\Auth::get("group")==1): ?>
            <a class="<?php print $userpanel==3 ? 'active' : '' ?>" href="/adminpanel/unapproved"><span class="icon-settings"></span>Admin</a>
        <?php endif; ?>

    <?php endif; ?>
    </div>
    <div class="col-md-4 hello-col">
        <?php if($isAuthed): ?>
            <strong>Hello, <?php print \Auth\Auth::get("displayed_name") ?></strong>&nbsp;&nbsp;&nbsp;<a href="/logout" class="button">Logout</a>
        <?php else: ?>
            <strong>Hello, Guest</strong>&nbsp;&nbsp;&nbsp;<a href="/login" class="button">Login</a>
        <?php endif; ?>
    </div>
    </div>
    </div>
    <div class="container <?php print $contentCustomClass; ?>">
        <?php print $leftcol ?>
        <?php print $view ?>
    </div>
    <footer class="footer">
    <div class="container">
    2014 © rpgboss.com
    </div>
    </footer>
    <?php if($isAuthed): ?>
        <audio id="ccsound" src="<?php print \Fuel\Core\Uri::create('assets/sound/cc-sound.wav') ?>" preload="auto"></audio>
    <div class="chat row">
        <div class="headline row">
            <h1>Community Chat</h1>
        </div>
        <div class="body row">
            <div class="users col-xs-4">
                <span class="online"></span>
                <ul id="userlist"></ul>
            </div>
            <div class="messages col-xs-8"></div>
        </div>
        <div class="inputmessage row">
            <input type="text" placeholder="Write a message..."/>
            <a class="send" href="#">Send</a>
        </div>
    </div>
        <script>
            $('.chat').hide();
            var username = "<?php print \Auth\Auth::get('displayed_name') ?>",
                chatBody = $('.chat .body, .chat .body, .chat .inputmessage'),
                chatTrigger = true,
                chatMessageOdd=false,
                onlineInteravl = -1;

            function addMessage(msg) {
                var data = msg.split(';');
                var name = $('<strong>').text(data[0]);

                var stamp = new Date(data[2]*1000);
                var date = $('<span>').text('('+stamp.getHours()+':'+stamp.getMinutes()+')');

                var text = $('<span>').text(': '+data[1]);
                var classname = 'message';
                if(chatMessageOdd) {
                    classname = 'message odd';
                    chatMessageOdd = false;
                } else {
                    chatMessageOdd = true;
                }


                $('.chat .messages').append($('<p>',{'class':classname}).append(name, ' ', date, text));

                $('.chat .messages').scrollTop($('.chat .messages')[0].scrollHeight);

            }

            function blink() {

                setTimeout(function() {
                    $('.chat .headline').addClass('blink');
                    setTimeout(function() {
                        $('.chat .headline').removeClass('blink');

                        if(!chatTrigger) {
                            blink();
                        }
                    },800);}
                ,800);
            }

            if(("WebSocket" in window)){

                var socket = new WebSocket("ws://assets.rpgboss.com:8080/chat");
                socket.onopen = function(){
                    $('.chat').show();
                    socket.send('me<>add-user:'+username);
                }
                socket.onmessage = function(msg){

                    var split = msg.data.split('<>'),
                        type = parseInt(split[0]),
                        message = split[1];

                    switch(type) {
                        case 1:
                            addMessage(message);
                            if(!chatTrigger) {
                                document.getElementById('ccsound').play();
                                blink();
                            }
                            break;
                        case 2:
                            $('.online').text('('+message+' User)');
                            break;
                        case 4:
                            var names = message.split(',');
                            $('#userlist').empty();
                            $.each(names, function(key, name) {
                                $('#userlist').append($('<li>').append($('<span>',{'class':'icon-profile'}),' ',name));
                            });
                            break;
                        case 5:
                            var messages = atob(message).split('^^^');
                            $.each(messages, function(key, message) {
                                if(message!='') {
                                    addMessage(message.replace('1<>',''));
                                }
                            });
                            $('.chat .messages').append($('<div class="previousbreak">').text('last messages'));
                            break;
                    }

                }
                $('.chat .headline').click(function() {
                    if(!chatTrigger) {
                        chatBody.show();
                        $('.chat .messages').scrollTop($('.chat .messages')[0].scrollHeight);
                        chatTrigger = true;
                    } else {
                        chatBody.hide();
                        chatTrigger = false;
                    }
                });
                $('.chat input').keypress(function(event) {
                    if (event.keyCode == '13') {
                        $('.chat .send').trigger('click');
                    }
                });
                $('.chat .send').click(function(e) {
                    e.preventDefault();
                    var text = $('.chat input').val();
                    if(text==""){
                        alert('Please enter a message');
                        return ;
                    }
                    try{
                        var textMessage = username+';'+text;
                        socket.send("msg<>"+textMessage);
                    } catch(exception){

                    }

                    $('.chat input').val("");

                    return false;
                });
                onlineInteravl = setInterval(function() {
                    socket.send("me<>get-users");
                    socket.send("me<>get-usernames");
                },3000);

                $('.chat .headline').trigger('click');

                if(location.hash=='#showchat') {
                    $('.chat .headline').trigger('click');
                    location.hash = '';
                }

            }
        </script>
    <?php endif; ?>
    <script type="text/javascript">
    $(document).ready(function() {
        var bodyHeight = $("body").height();
        var vwptHeight = $(window).height();
        if (vwptHeight > bodyHeight) {
            $("footer").css("position","absolute").css("bottom",0);
        }
    });
    </script>
    </body>
</html>