$(document).ready(function() {
	$('.admin-email-send').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/email/sendEmails.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
});