function loadData() {
	var ticketId = $("#ticketId").val();

	$.getJSON('../api/json/ticket/getTicketData.php?id=' + encodeURIComponent(ticketId), function(data) {
		if (data.result) {
			// Remove old entries.
			$("#ticketDetails").empty();
			var user = data.userData;

			$("#ticketDetails").append('<table>' +
									   	   '<tr>' +
										       '<td>Navn:</td>' +
										       '<td>' + user.firstname + ' ' + user.lastname +'</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td>Adresse:</td>' +
										   	   '<td>' + user.address + '</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td></td>' +
										   	   '<td>' + user.city + '</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td>Kjønn:</td>' +
										   	   '<td>' + user.gender + '</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td>Født:</td>' +
										       '<td>' + user.birthdate + '</td>' +
										   '</tr>' +
										   '<tr>' +
										       '<td>Alder:</td>' +
										   	   '<td>' + user.age + ' År</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td>Brukernavn:</td>' +
										   	   '<td>' + user.username + '</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td>E-post:</td>' +
										   	   '<td>' + user.email + '</td>' +
										   '</tr>' +
										   '<tr>' +
										   	   '<td>Phone:</td>' +
										   	   '<td>' + user.phone + '</td>' +
										   '</tr>' +

									   '</table>' +
									   '<input type="button" value="Godkjenn" onClick="acceptTicket(' + ticketId + ')">');
		} else {
			error(data.message);
		}
	});
}

function acceptTicket(id) {
	$.getJSON('../api/json/ticket/checkInTicket.php?id=' + encodeURIComponent(id), function(data) {
		if (data.result) {
			// Remove the user information.
			$("#ticketDetails").empty();

			// Display confirmation message to the user.
			info(data.message);			
		} else {
			error(data.message);
		}
	});
}