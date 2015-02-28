if(("WebSocket" in window) && sessionid != ""){

    var socket2 = new WebSocket("ws://assets.rpgboss.com:8080");
    socket2.onopen = function(){

        socket2.send('set;server;'+JSON.stringify({
            value : sessionid
        }));
        if(typeof packageid != 'undefined') {
            socket2.send('command;server;'+JSON.stringify({
                action : "getProjectData",
                value : packageid,
                value2 : packagename
            }));
        }
    }
    socket2.onmessage = function(msg){

        var split = msg.data.split(';'),
            mode = split[1],
            command = split[0],
            data = JSON.parse(split[2]);

        switch(data.action) {
            case "getProjectData":
                $.ajax({
                    type: 'POST',
                    url:"/api/v1/checkpackagedownload/"+packageid,
                    success: function(data2){
                        if(data2=='0') {
                            var notice = $('<div>',{'class':'notice'}).text("This package can not be downloaded/updated directly.");
                            $('#commander').append(notice);
                        } else {
                            var a = $('<a>').text('Download to your project: ' + data.value);
                            a.attr('href','#');
                            a.addClass('button full');
                            a.click(initiateDownload)
                            $('#commander').append(a);
                        }
                    }
                });
                break;
        }

    }

    function initiateDownload(e) {
        e.preventDefault();

        socket2.send('command;server;'+JSON.stringify({
            action : "startDownload",
            value : packageid,
            value2: packagename
        }));

        return false;
    }

}