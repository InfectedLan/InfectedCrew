$(document).ready(function() {
	$('.register').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/register.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				$(location).attr('href', 'index.php');
			} else {
				error(data.message); 
			}
		});
	});
});