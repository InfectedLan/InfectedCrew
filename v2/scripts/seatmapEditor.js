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