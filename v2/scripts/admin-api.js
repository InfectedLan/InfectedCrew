var apiCommands = [
	{"name": "acceptApplication", "description": "Accepts application", "args": [{"name": "id", "desc": "ID of application to be accepted"}]}
];
$(document).ready(function(){
	//Populate api list
	for(var i = 0; i < apiCommands.length; i++) {
		var o = new Option(apiCommands[i].name, i);
		$(o).html(apiCommands[i].name);
		$("#apiName").append(o);
	}
});