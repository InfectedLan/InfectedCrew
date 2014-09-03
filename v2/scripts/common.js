$(function() {
	$('.chosen-select').chosen({
		search_contains: true,
		no_results_text: "Ingen resultater for "
	});
});

function error(what) {
	//Do something
	$("#innerError").html(what);
	$("#error").fadeIn(300);
}

function info(what) {
	//Do even more something
	$("#innerInfo").html(what);
	$("#info").fadeIn(300);
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
    });
	
    $("#infoClose").click(function() {
    	closeInfo();
    });
});