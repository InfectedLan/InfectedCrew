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

$(function() {
	$('.chosen-select').chosen({
		allow_single_deselect: true,
		search_contains: true,
		no_results_text: "Ingen resultater for "
	});
	
	$('.editor').ckeditor();
});
					
function error(what, func) {
	//Do something
	$("#innerError").html(what);
	$("#error").fadeIn(300);
	if(typeof func === "undefined")
	{
		errorFunction = 0;
	}
	else
	{
		errorFunction = func;
	}
}

function info(what, func) {
	//Do even more something
	$("#innerInfo").html(what);
	$("#info").fadeIn(300);
	if(typeof func === "undefined")
	{
		infoFunction = 0;
	}
	else
	{
		infoFunction = func;
	}
}

function closeError() {
	$("#innerError").html("");
	$("#error").fadeOut(300);
}

function closeInfo() {
	$("#innerInfo").html("");
	$("#info").fadeOut(300);
}

$( document ).ready(function() {
    $("#errorClose").click(function() {
    	closeError();
    	errorFunction();
    });
	
    $("#infoClose").click(function() {
    	closeInfo();
    	infoFunction();
    });
});
var errorFunction = 0;
var infoFunction = 0;


var timers = [];

function registerTimer(target, targetTime) {
    timers.push({target: target, targetTime: targetTime});
}

$(document).ready(function(){
    setInterval(updateTimers, 1000);
});

function updateTimers() {
    var now = Math.round(Date.now() / 1000);
    for(var i = 0; i < timers.length; i++) {
	var difference = timers[i].targetTime - now;
	var negative = difference < 0;
	if(negative) {
	    difference = difference * -1;
	}
	var seconds = Math.floor(difference % 60);
	var minutes = Math.floor(difference / 60)%60;
	var hours = Math.floor(Math.floor(difference/60)/60)%24;
	var days = Math.floor(Math.floor(Math.floor(difference/60)/60)/24);
	//console.log("Difference: " + difference);
	var string = "" + (negative ? "for " : "om ") + (days > 0 ? days + " dager, " : "") + (hours > 0 ? hours + " timer, " : "") + (minutes > 0 ? minutes + " minutter, " : "") + seconds + " sekunder" + (negative ? " siden" : "");
	$(timers[i].target).html(string);
    }
}
