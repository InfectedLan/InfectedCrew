$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
	    $.post('../json/chief-groups.php?action=1', $('.chief-groups-add').serialize(), function(data) {
			if (data.result) {
	        	info(data.message); // TODO: Display "data.message" to user.
	        } else {
	         	error(data.message); // TODO: Display "data.message" to user.
	        }
	    }, 'json');
	});
	
	$('.chief-groups-remove').submit(function(e) {
		e.preventDefault();
	    $.post($('.chief-groups-remove').attr('action'), $('.chief-groups-remove').serialize(), function(data) {
			if (data.result) {
	        	info(data.message); // TODO: Display "data.message" to user.
	        } else {
	         	error(data.message); // TODO: Display "data.message" to user.
	        }
	    }, 'json');
	});
	
	$('.chief-groups-change').submit(function(e) {
		e.preventDefault();
	    $.post('../json/chief-groups.php?action=3', $('.chief-groups-change').serialize(), function(data) {
			if (data.result) {
	        	info(data.message); // TODO: Display "data.message" to user.
	        } else {
	         	error(data.message); // TODO: Display "data.message" to user.
	        }
	    }, 'json');
	});
});