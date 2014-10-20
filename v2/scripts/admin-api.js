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
	showCommandParameters(0);
});
function showCommandParameters(index) {
	var command = apiCommands[index];
	var commandArgsText = [];
	commandArgsText.push('<table><tr><td><b>Argument name</b></td><td><b>Value</b></td><td><b>Description</b></td></tr>');
	for(var i = 0; i < command.args.length; i++) {
		commandArgsText.push('<tr>');
			commandArgsText.push('<td>');
				commandArgsText.push(command.args[i].name);
			commandArgsText.push('</td>');
			commandArgsText.push('<td>');
				commandArgsText.push('<input type="text" id="commandParameter' + i + '" />');
			commandArgsText.push('</td>');
			commandArgsText.push('<td>');
				commandArgsText.push(command.args[i].desc);
			commandArgsText.push('</td>');
		commandArgsText.push('</tr>');
	}
	commandArgsText.push('</table>');
	commandArgsText.push('<input type="button" value="Send!" onClick="submitQuery()" />');

	$("#commandArgBox").html(commandArgsText.join(""));
}
function submitQuery() {

}