var seatHandlerFunction = function(identifyer, seatDivId, taken, takenData) {
	if(!taken) {
		return "free";
	}
	if(takenData.id == ticketId) {
		return "current";
	}
	return "taken";
}
var callback = function() {
	for(var i = 0; i < seatmapData.rows.length; i++)
		{
			for(var s = 0; s < seatmapData.rows[i].seats.length; s++)
			{
				if(!seatmapData.rows[i].seats[s].occupied)
				{
					$("#seat" + seatmapData.rows[i].seats[s].id).click({seatId: seatmapData.rows[i].seats[s].id}, function(e) {
						updateSeat(e.data.seatId);
					});
				}
			}
		}
}
function updateSeat(seatId) {
	$.getJSON("../api/json/seatTicket.php?ticket=" + ticketId + "&seat="+seatId, function(data){
		if(data.result)
		{
			//downloadAndRenderSeatmap("#seatmapCanvas", seatHandlerFunction, callback);
			location.reload();
		}
		else
		{
			error(data.message);
		}
  	});
}