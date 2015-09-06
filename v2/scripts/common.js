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
		disable_search_threshold: 5,
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
