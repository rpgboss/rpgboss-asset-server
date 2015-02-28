/**
 * Created by hendrikweiler on 20.02.15.
 */

$('.chat').hide();
    var chatBody = $('.chat .body, .chat .body, .chat .inputmessage'),
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

    var socket = new WebSocket("ws://assets.rpgboss.com:8081");
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