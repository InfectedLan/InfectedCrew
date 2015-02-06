$(document).ready(function() {
	$('.memberlist').submit(function(e) {
		e.preventDefault();
		window.open('/api/pages/utils/printMemberList.php' + '?' + $(this).serialize());
	});
});