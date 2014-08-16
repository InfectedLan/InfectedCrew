$(document).ready(function() {
	$('.application').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addApplication.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				info(data.message);
			} else {
				error(data.message); 
			}
		});
	});
});