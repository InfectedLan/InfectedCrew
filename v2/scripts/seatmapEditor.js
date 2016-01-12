/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

var xPos = 0;
var yPos = 0;
var seatmapData = null;
var selectedRow = 0;

function editSeatmap() {
	var seatmapId = $("#seatmapSelect").val();

	window.location = "index.php?page=admin-seatmap&id=" + seatmapId;
}

function cloneSeatmap() {
    var seatmapId = $("#seatmapSelect").val();
    $.getJSON('../api/json/seatmap/cloneSeatmap.php?id=' + encodeURIComponent(seatmapId), function(data) {
	if (data.result) {
	    window.location = "index.php?page=admin-seatmap&id=" + data.id;
	} else {
	    error(data.message);
	}
    });
}

function copySeatmap() {
    var copyTarget = $("#copySeatmapSourceSelect").val();
    $.getJSON('../api/json/seatmap/copySeatmap.php?to=' + encodeURIComponent(seatmapId) + '&from=' + encodeURIComponent(copyTarget), function(data) {
	if (data.result) {
	    renderSeatmap();
	} else {
	    error(data.message);
	}
	$("#copySeatmapDiv").fadeOut(200, function() {
	    $("#btnInitCopy").fadeIn(200);
	});	    
    });
}

function initCopy() {
    $("#btnInitCopy").fadeOut(200, function() {
	$("#copySeatmapDiv").fadeIn(200);
    });
}

function newSeatmapName() {
	$("#seatmapIntro").fadeOut(200, function() {
		$("#newSeatmapDiv").fadeIn(200);
	});
}

function backToMenuFromNewSeatmap() { //Urr... long name, perhaps? naaah
	$("#newSeatmapDiv").fadeOut(200, function() {
		$("#seatmapIntro").fadeIn(200);
	});
}

function newSeatmap() {
	$.getJSON('../api/json/seatmap/addSeatmap.php?name=' + encodeURIComponent($("#newSeatmapName").val()), function(data) {
		if (data.result) {
			window.location = "index.php?page=admin-seatmap&id=" + data.id;
		} else {
			error(data.message);
		}
  });
}

function redirectToSplash() {
	window.location = "index.php?page=admin-seatmap";
}

function addRow() {
	// TODO: seatmapId
	$.getJSON('../api/json/row/addRow.php?seatmap=' + seatmapId + "&x=" + xPos + "&y=" + yPos, function(data) {
		if (data.result) {
			renderSeatmap();
		} else {
			error("Det skjedde en feil under skapelsen av den nye raden!");
		}
	});
}

function getRowFromId(rowId) {
	for (var i = 0; i < seatmapData.rows.length; i++) {
		if (seatmapData.rows[i].id == rowId) {
			return seatmapData.rows[i].number;
		}
	}
}

function getRow(rowId) {
	for (var i = 0; i < seatmapData.rows.length; i++) {
		if (seatmapData.rows[i].id == rowId) {
			return seatmapData.rows[i];
		}
	}
}

function selectRow(rowId) {
	if (rowId == selectedRow) {
		selectedRow = null;
		$(".selectedRow").removeClass("selectedRow");
		$("#seatmapEditorContextButtons").html(""); //Clear it
	} else {
		selectedRow = rowId;
		$(".selectedRow").removeClass("selectedRow");
		$("#row" + rowId).addClass("selectedRow");
		//Add fancy buttons
		$("#seatmapEditorContextButtons").html(""); //Clear it
		$("#seatmapEditorContextButtons").append('<b>Rad ' + getRowFromId(rowId) + ':</b>');
		$("#seatmapEditorContextButtons").append('<input type="button" value="Slett" onclick="deleteRow(' + rowId + ')" />');
		$("#seatmapEditorContextButtons").append('<input type="button" value="Legg til seter" onclick="addSeats(' + rowId + ')" />');
		$("#seatmapEditorContextButtons").append('<input type="button" value="Fjern seter" onclick="removeSeats(' + rowId + ')" />');
		$("#seatmapEditorContextButtons").append('<input type="button" id="btnMoveRow" value="Flytt raden til [' + xPos + ',' + yPos + ']" onclick="moveRow(' + rowId + ')" />');
		$("#seatmapEditorContextButtons").append(' | ');

		var row = getRow(rowId);
		xPos = row.x;
		yPos = row.y;

		updatePlacementButtons();
	}
}

function deleteRow(rowId) {
	$.getJSON('../api/json/row/removeRow.php?row=' + rowId, function(data) {
		if (data.result) {
			renderSeatmap();
		} else {
			error("Det skjedde en feil under slettingen av raden!");
		}
	});
}

function isNumber(obj) { return !isNaN(parseFloat(obj)); }

function addSeats(rowId) {
	var amount = window.prompt("Hvor mange seter vil du legge til?", "1");

	if (isNumber(amount)) {
		$.getJSON('../api/json/row/addSeatsToRow.php?row=' + rowId + "&numSeats=" + amount, function(data) {
			if(data.result) {
				renderSeatmap();
			} else {
				error(data.message);
			}
		});
	} else if (amount != null) {
		error("Du må skrive inn et tall!");
	}
}

function removeSeats(rowId) {
	var amount = window.prompt("Hvor mange seter vil du fjerne?", "1");

	if (isNumber(amount)) {
		$.getJSON('../api/json/row/removeSeatsFromRow.php?row=' + rowId + "&numSeats=" + amount, function(data) {
			if(data.result) {
				renderSeatmap();
			} else {
				error(data.message);
			}
		});
	} else if(amount != null) {
		error("Du må skrive inn et tall!");
	}
}

function moveRow(rowId) {
	$.getJSON('../api/json/row/moveRow.php?row=' + rowId + '&x=' + xPos + '&y=' + yPos, function(data) {
		if(data.result) {
			renderSeatmap();
		} else {
			error(data.message);
		}
	});
}

function renderSeatmap() {
	$.getJSON('../api/json/seatmap/getSeatmap.php?id=' + seatmapId, function(data) {
		if (data.result) {
			seatmapData = data;
			//Render seatmap
			$("#seatmapEditorCanvas").html('');
			$("#seatmapEditorCanvas").css('background-image', 'url("../api/content/seatmapBackground/' + data.backgroundImage + '")');

			for (var i = 0; i < data.rows.length; i++) {
				var returnData = [];
				returnData.push('<div class="editorRow" style="top: ' + data.rows[i].y + 'px; left: ' + data.rows[i].x + 'px;" id="row' + data.rows[i].id + '">');

				for (var s = 0; s < data.rows[i].seats.length; s++) {
					returnData.push('<div class="editorSeat" id="seat' + data.rows[i].seats[s].id + '">');
					returnData.push(data.rows[i].seats[s].humanName);
					returnData.push('</div>');
				}

				returnData.push('</div>');
				$("#seatmapEditorCanvas").append(returnData.join(""));

				var rowId = data.rows[i].id;

				$("#row" + data.rows[i].id).click({row: rowId}, function(e) {
					selectRow(e.data.row);
				});
			}
		} else {
			$("#seatmapEditorCanvas").html('<i>En feil oppstod under håndteringen av seatmappet...</i>');
		}
  });
}

function promptPosition() {
	xPos = window.prompt("Skriv inn en X-koordinat", xPos);
	yPos = window.prompt("Skriv inn en Y-koordinat", yPos);

	updatePlacementButtons();
}

function updatePlacementButtons() {
	$("#btnNewRow").attr("value", "Legg til rad på [" + xPos + "," + yPos + "]");

	if ($("#btnMoveRow").length ) { //Only if exists
		$("#btnMoveRow").attr("value", "Flytt raden til [" + xPos + "," + yPos + "]");
	}
}

$( document ).ready(function() {
	$("#seatmapEditorCanvas").mousemove(function(e) {
		//Mouse move handler
		var parentOffset = $(this).offset();
		//or $(this).offset(); if you really just want the current element's offset
	 	var tempXPos = Math.round(e.pageX - parentOffset.left);
	 	var tempYPos = Math.round(e.pageY - parentOffset.top);
	 	$("#mousePos").html("<i>Mus-posisjon: [" + tempXPos + "," + tempYPos + "]. Klikk for å velge.</i>");
	});

	$("#seatmapEditorCanvas").click(function(e) {
		if (e.target != this) {
			return;
		}

		//Mouse move handler
		var parentOffset = $(this).offset();
		//or $(this).offset(); if you really just want the current element's offset
	 	xPos = Math.round(e.pageX - parentOffset.left);
	 	yPos = Math.round(e.pageY - parentOffset.top);
	 	updatePlacementButtons();
	});
});
