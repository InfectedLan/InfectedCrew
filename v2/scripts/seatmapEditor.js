function editSeatmap()
{
	var seatmapId = $("#seatmapSelect").val();
	alert("Editing seatmap " + seatmapId);
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
	$.getJSON('../json/newSeatmap.php?name=' + encodeURIComponent($("#newSeatmapName")), function(data){
		
  	});
}