$(document).ready(function() {
	getPermissionDescription($('.admin-permissions-add-value').val());

	$('.admin-permissions-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addUserPermission.php' + '?' + $(this).serialize(), function(data){
			if (data.result) {
				location.reload();
			} else {
				error(data.message); 
			}
		});
	});
	
	$('.admin-permissions-add-value').change(function() {
		getPermissionDescription($(this).val());
	});
});

function getPermissionDescription(value) {
	$.getJSON('../api/json/getPermissionDescription.php?value=' + encodeURIComponent(value), function(data) {
		$('.admin-permissions-add-description').text(data.message);
	});
}

function removeUserPermission(userId, value) {
	$.getJSON('../api/json/removeUserPermission.php?userId=' + userId + '&value=' + value, function(data){
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}