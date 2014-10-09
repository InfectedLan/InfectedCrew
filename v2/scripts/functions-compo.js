$(document).ready(function() {
	setInverval(renderMatchList, 5000);
});
function renderMatchList() {
	$.getJSON('../api/json/getMatchList.php' + '?id=' + compoId, function(data) {
		if (data.result) {
			renderPendingMatches(data.data.pending);
			renderCurrentMatches(data.data.current);
			renderFinishedMatches(data.data.finished);
		} else {
			error(data.message); 
		}
	});
}
function renderPendingMatches(pendingData) {

}
function renderCurrentMatches(currentData) {

}
function renderFinishedMatches(finishedData) {
	
}