/*
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function initMatchList() {
	$(document).ready(function() {
		setInterval(renderMatchList, 5000);
		renderMatchList();
	});
}
function generateMatches() {
	var startTime = $("#startTime").val();
	var spacing = $("#compoSpacing").val();
	$.getJSON('../api/json/match/generateMatchesForCompo.php' + '?id=' + compoId + '&startTime=' + startTime + '&compoSpacing=' + spacing, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}
function renderMatchList() {
	$.getJSON('../api/json/match/getMatchList.php' + '?id=' + compoId, function(data) {
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
	
	appendArray.push('<hr />');
	for(var i = 0; i < pendingData.length; i++) {
		appendArray.push('<h3>Match id: ' + pendingData[i].id + '</h3>');
		appendArray.push('<b>Start: </b>' + pendingData[i].startString + ' (' + pendingData[i].startTime + ')<br />');
		appendArray.push('<b>Connect data: </b>' + pendingData[i].connectData + '<br />');
		appendArray.push('<b>Participants: </b><br />');
		appendArray.push("<table>");
			for(var x = 0; x < pendingData[i].participants.length; x++) {
				appendArray.push('<tr>');
					appendArray.push('<td>');
						appendArray.push(pendingData[i].participants[x]);
					appendArray.push('</td>');
				appendArray.push('</tr>');
			}
		appendArray.push("</table>");
		appendArray.push('<hr />');
	}
	
	$("#teamListArea").append(appendArray.join(""));
}
function renderCurrentMatches(currentData) {
	var appendArray = [];
	appendArray.push("<h1>Nåværende matcher</h1>");
	
	appendArray.push('<hr />');
	for(var i = 0; i < currentData.length; i++) {
		appendArray.push('<h3>Match id: ' + currentData[i].id + '</h3>');
		appendArray.push('<b>Start: </b>' + currentData[i].startString + ' (' + currentData[i].startTime + ')<br />');
		appendArray.push('<b>Connect data: </b>' + currentData[i].connectData + '<br />');
		appendArray.push('<b>Internal state: </b>' + (currentData[i].state == 0 ? 'Waiting for players to be ready' : (currentData[i].state == 1 ? 'Banning' : 'Ingame') ) + '<br />');
		appendArray.push('<b>Participants: </b><br />');
		appendArray.push("<table>");
			for(var x = 0; x < currentData[i].participants.list.length; x++) {
				appendArray.push('<tr>');
					appendArray.push('<td>');
						appendArray.push(currentData[i].participants.list[x].name + ' - ' + currentData[i].participants.list[x].tag + ' (id ' + currentData[i].participants.list[x].id + ')');
					appendArray.push('</td>');
					appendArray.push('<td>');
						appendArray.push('<input type="button" value="Sett vinner" onClick="setWinner(' + currentData[i].id + ', ' + currentData[i].participants.list[x].id + ')" />');
					appendArray.push('</td>');
				appendArray.push('</tr>');
			}
		appendArray.push("</table>");
		appendArray.push('<hr />');
	}
	
	$("#teamListArea").append(appendArray.join(""));
}
function renderFinishedMatches(finishedData) {
	var appendArray = [];
	appendArray.push("<h1>Ferdige matcher</h1>");
	
	appendArray.push('<hr />');
	for(var i = 0; i < finishedData.length; i++) {
		appendArray.push('<h3>Match id: ' + finishedData[i].id + '</h3>');
		appendArray.push('<b>Start: </b>' + finishedData[i].startString + ' (' + finishedData[i].startTime + ')<br />');
		appendArray.push('<b>Connect data: </b>' + finishedData[i].connectData + '<br />');
		appendArray.push('<b>Participants: </b><br />');
		appendArray.push("<table>");
			for(var x = 0; x < finishedData[i].participants.list.length; x++) {
				appendArray.push('<tr>');
					appendArray.push('<td>');
						appendArray.push(finishedData[i].participants.list[x].name + ' - ' + finishedData[i].participants.list[x].tag + ' (id ' + finishedData[i].participants.list[x].id + ')');
						if(finishedData[i].participants.list[x].id == finishedData[i].winner.id) {
							appendArray.push(' <b>Winner</b>');
						}
					appendArray.push('</td>');
				appendArray.push('</tr>');
			}
		appendArray.push("</table>");
		appendArray.push('<hr />');
	}
	
	$("#teamListArea").append(appendArray.join(""));
}
function setWinner(matchId, winnerId) {
	$.getJSON('../api/json/match/setmatchwinner.php?matchId=' + matchId + '&winnerId=' + winnerId, function(data) {
		if (data.result) {
			renderMatchList();
		} else {
			error(data.message); 
		}
	});
}