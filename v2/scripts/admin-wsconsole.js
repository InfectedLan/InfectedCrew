(function(){
    console.log("Wsconsole says hi!");

    $(document).ready(function(){
	$("#inputArea").find('input').keypress({}, function(e) {
            if (e.which == 13) {
		var command = $("#inputArea").find("input").val();
    		console.log("Sending message: " + command);
		println(">" + command);
		Websocket.sendIntent("command", [command]);
		$("#inputArea").find("input").val("");
  	    }
	});
	//Start websocket
	Websocket.onOpen = onConnect;
	Websocket.onClose = onDisconnect;
	Websocket.onAuthenticate = onAuthenticate;
	Websocket.connect(Websocket.getDefaultConnectUrl());
	Websocket.addHandler("commandResponse", function(data) {
	    for(var i = 0; i < data.length; i++) {
		println(data[i]);
	    }
	});
    });

    var onConnect = function() {
	println("Connected to websocket");
	Websocket.authenticate();
    };

    var onAuthenticate = function() {
	println("Authenticated - welcome to the admin console!");
    };

    var onDisconnect = function() {
	println("Lost connection to websocket...");
    };

    var println = function(string) {
	$("#consoleArea").append("<span>" + string + "</span><br />");
	$("#consoleArea").scrollTop($("#consoleArea")[0].scrollHeight);
    };
    
})();
