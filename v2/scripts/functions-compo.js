$(document).ready(function() {
	setInterval(renderMatchList, 5000);
});
function renderMatchList() {
	$.getJSON('../api/json/getMatchList.php' + '?id=' + compoId, function(data) {
		if (data.result) {
			$("#teamListArea").html("");
			renderPendingMatches(data.data.pending);
			renderCurrentMatches(data.data.current);
			renderFinishedMatches(data.data.finished);
		} else {
			error(data.message); 
		}
	});
}
function renderPendingMatches(pendingData) {
	var appendArray = [];
	appendArray.push("<h1>Ventende matcher</h1>");
	
	for(var i = 0; i < pendingData.length; i++) {
		appendArray.push('<h3>Match id: ' + pendingData[i].id + '</h3>');
		appendArray.push("<table>");
			appendArray.push('<tr>');
				appendArray.push('<td>');
					appendArray.push(pendingData[i].startTime);
				appendArray.push('</td>');
				appendArray.push('<td>');
					appendArray.push(pendingData[i].startString);
				appendArray.push('</td>');
				appendArray.push('<td>');
					appendArray.push(pendingData[i].connectData);
				appendArray.push('</td>');
			appendArray.push("</tr>");
			for(var x = 0; x < pendingData[i].participants.length; x++) {
				appendArray.push('<tr>');
					appendArray.push('<td colspan="2">');
						appendArray.push(pendingData[i].participants[x]);
					appendArray.push('</td>');
				appendArray.push('</tr>');
			}
		appendArray.push("</table>");
	}
	
	$("#teamListArea").append(appendArray.join(""));
}
function renderCurrentMatches(currentData) {

}
function renderFinishedMatches(finishedData) {
	
}