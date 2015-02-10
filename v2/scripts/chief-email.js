$(document).ready(function() {
	$('.chief-email-send').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/email/sendEmail.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				info(data.message);
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});