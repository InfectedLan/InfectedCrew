$(document).ready(function() {
	$('.memberlist').submit(function(e) {
		e.preventDefault();
		location.href = '/api/pages/utils/printMemberList.php' + '?' + $(this).serialize();
	});
});