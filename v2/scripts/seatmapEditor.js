var xPos = 0;
var yPos = 0;
var seatmapData = null;
var selectedRow = 0;
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
	seatmapId
	$.getJSON('../json/newRow.php?seatmap=' + seatmapId + "&x=" + xPos + "&y=" + yPos, function(data){
		if(data.result)
		{
			renderSeatmap();
		}
		else
		{
			error("Det skjedde en feil under skapelsen av den nye raden!");
		}
	});
}
function getRowFromId(rowId)
{
	for(var i = 0; i < seatmapData.rows.length; i++)
	{
		if(seatmapData.rows[i].id == rowId)
		{
			return seatmapData.rows[i].number;
		}
	}
}
function selectRow(rowId)
{
	if(rowId==selectedRow)
	{
		selectedRow = null;
		$(".selectedRow").removeClass("selectedRow");
		$("#seatmapEditorContextButtons").html(""); //Clear it
	}
	else
	{
		selectedRow = rowId;
		$(".selectedRow").removeClass("selectedRow");
		$("#row" + rowId).addClass("selectedRow");
		//Add fancy buttons
		$("#seatmapEditorContextButtons").html(""); //Clear it
		$("#seatmapEditorContextButtons").append('<b>Rad ' + getRowFromId(rowId) + ':</b>');
		$("#seatmapEditorContextButtons").append('<input type="button" value="Slett" onclick="deleteRow(' + rowId + ')" />');
		$("#seatmapEditorContextButtons").append(' | ');
	}
}
function renderSeatmap()
{
	$.getJSON('../json/seatmap.php?id=' + seatmapId, function(data){
		if(data.result)
		{
			seatmapData = data;
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

				var rowId = data.rows[i].id;
				$("#row" + data.rows[i].id).click({row: rowId}, function(e) {
					selectRow(e.data.row);
				});
			}
		}
		else
		{
			$("#seatmapCanvas").html('<i>En feil oppstod under håndteringen av seatmappet...</i>');
		}
  	});
}
function promptPosition()
{
	xPos = window.prompt("Skriv inn en X-koordinat", xPos);
	yPos = window.prompt("Skriv inn en Y-koordinat", yPos);

	$("#btnNewRow").attr("value", "Legg til rad på [" + xPos + "," + yPos + "]");
}
$( document ).ready(function() {
    $("#seatmapCanvas").mousemove(function( e ) {
    	//Mouse move handler
    	var parentOffset = $(this).offset(); 
	  	//or $(this).offset(); if you really just want the current element's offset
	   	var tempXPos = Math.round(e.pageX - parentOffset.left);
	   	var tempYPos = Math.round(e.pageY - parentOffset.top);
	   	$("#mousePos").html("<i>Mus-posisjon: [" + tempXPos + "," + tempYPos + "]. Klikk for å velge.</i>");
    });
    $("#seatmapCanvas").click(function( e ) {
    	if(e.target != this) return;
    	//Mouse move handler
    	var parentOffset = $(this).offset(); 
	  	//or $(this).offset(); if you really just want the current element's offset
	   	xPos = Math.round(e.pageX - parentOffset.left);
	   	yPos = Math.round(e.pageY - parentOffset.top);
	   	$("#btnNewRow").attr("value", "Legg til rad på [" + xPos + "," + yPos + "]");
    });
});