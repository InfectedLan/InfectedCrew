$(document).ready(function() {
	$('.admin-events-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/addEvent.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.admin-events-edit').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/editEvent.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});