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