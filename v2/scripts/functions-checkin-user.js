var passThroughId = 0;
function loadData() {
	var ticketId = $("#ticketId").val();
	passThroughId = ticketId;
	$.getJSON('../api/json/getticketdata.php?id=' + encodeURIComponent(ticketId), function(data){
		if(data.result) {
			$("#ticketDetails").append("<table>" +
				"<tr><td>Fullt navn</td><td>" + data.userData.fullName + "</td></tr>" +
				"<tr><td>Kjønn</td><td>" + data.userData.gender + "</td></tr>" +
				"<tr><td>Født</td><td>" + data.userData.birthDate + "</td></tr>" +
				"<tr><td>Alder</td><td>" + data.userData.age + "</td></tr>" +
				"</table><input type='button' value='Godkjenn' onClick='acceptTicket(" + passThroughId + ")' />");
		} else {
			error(data.message);
		}
	});
}
function acceptTicket(id) {
	$.getJSON('../api/json/checkinticket.php?id=' + encodeURIComponent(id), function(data){
		if(data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}