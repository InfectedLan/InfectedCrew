function editSeatmap()
{
	var seatmapId = $("#seatmapSelect").val();
	window.location = "index.php?page=admin-seatmap&id=" + seatmapId;
}
function copySeatmap()
{
	var seatmapId = $("#seatmapSelect").val();
	alert("Copying seatmap " + seatmapId);
}
function newSeatmapName() 
{
	$("#seatmapIntro").fadeOut(200, function() {
		$("#newSeatmapDiv").fadeIn(200);
	});
}
function backToMenuFromNewSeatmap() //Urr... long name, perhaps? naaah
{
	$("#newSeatmapDiv").fadeOut(200, function() {
		$("#seatmapIntro").fadeIn(200);
	});
}
function newSeatmap()
{
	$.getJSON('../json/newSeatmap.php?name=' + encodeURIComponent($("#newSeatmapName").val()), function(data){
		if(data.result)
		{
			window.location = "index.php?page=admin-seatmap&id=" + data.id;
		}
		else
		{
			error(data.message);
		}
  	});
}
function redirectToSplash()
{
	window.location = "index.php?page=admin-seatmap";
}
function addRow()
{
	//TODO
	alert("todo");
}
function renderSeatmap()
{
	$.getJSON('../json/seatmap.php?id=' + seatmapId, function(data){
		if(data.result)
		{
			//Render seatmap
			$("#seatmapCanvas").html('');
			for(var i = 0; i < data.rows.length; i++)
			{
				var returnData = [];
				returnData.push('<div class="row" style="top: ' + data.rows[i].y + 'px; left: ' + data.rows[i].x + 'px;" id="row' + data.rows[i].id + '">');
				for(var s = 0; s < data.rows[i].seats.length; s++)
				{
					returnData.push('<div class="seat" id="seat' + data.rows[i].seats[s].id + '">');
					returnData.push(data.rows[i].seats[s].humanName);
					returnData.push('</div>');
				}
				returnData.push('</div>');
				$("#seatmapCanvas").append(returnData.join(""));
			}
		}
		else
		{
			$("#seatmapCanvas").html('<i>En feil oppstod under håndteringen av seatmappet...</i>');
		}
  	});
}