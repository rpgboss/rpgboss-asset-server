if(("WebSocket" in window) && sessionid != ""){

    var projectDataCMD = 'command;server;'+JSON.stringify({
        action : "getProjectData",
        value : packageid,
        value2 : packagetype,
        value3: packageversion
    });

    var socket2 = new WebSocket("ws://assets.rpgboss.com:8080");
    socket2.onopen = function(){

        socket2.send('set;server;'+JSON.stringify({
            value : sessionid
        }));
        if(typeof packageid != 'undefined') {

            socket2.send(projectDataCMD);
        }
    }
    socket2.onmessage = function(msg){

        var split = msg.data.split(';'),
            mode = split[1],
            command = split[0],
            data = JSON.parse(split[2]);
        console.log(msg);
        switch(data.action) {
            case "getProjectData":
                $.ajax({
                    type: 'POST',
                    url:"/api/v1/checkpackagedownload/"+packageid,
                    success: function(data2){
                        $('#commander #updateButton').removeClass('show');
                        $('#commander #importButton').removeClass('show');

                        if(data2=='0') {
                            $('#commanderFail').addClass('show');
                        } else {
                            $('#commander').addClass('show');
                            $('#commander #path').text(data.value);
                            if(data.value2=="true") {
                                $('#commanderExist').addClass('show');
                            } else {
                                $('#commanderExist').removeClass('show');
                            }
                            if(data.value3=="true") {
                                $('#commander #updateButton').addClass('show');
                                $('#commander #updateButton').click(initiateDownload);
                            } else {
                                $('#commander #importButton').addClass('show');
                                $('#commander #importButton').click(initiateDownload);
                            }

                        }
                    }
                });
                break;
            case "importStatus":
                switch(data.value) {
                    case "Downloading":
                        $('#status').text(data.value);
                        $('.statusbar-inner').animate({
                            'width' : '50%'
                        });
                        break;
                    case "Import":
                        $('#status').text(data.value);
                        $('.statusbar-inner').animate({
                            'width' : '100%'
                        });
                        break;
                }
                break;
            case "finishedDownload":
                setTimeout(function() {
                    $('#status').text("Finished");
                    setTimeout(function () {
                        $('#importStatus').removeClass('show');
                        $('.statusbar-inner').width(0);
                    }, 5000);
                    socket2.send(projectDataCMD);
                },1000);
                break;
        }

    }

    function initiateDownload(e) {
        e.preventDefault();

        $('#importStatus').addClass('show');

        socket2.send('command;server;'+JSON.stringify({
            action : "startDownload",
            value : packageid,
            value2: packagename,
            value3 : packagetype
        }));

        return false;
    }

}