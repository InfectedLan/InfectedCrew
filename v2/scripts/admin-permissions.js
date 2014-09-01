$(document).ready(function() {
	$('.admin-permissions-edit').submit(function(e) {
		e.preventDefault();
		$.getJSON('../api/json/addUserPermission.php' + '?' + $(this).serialize(), function(data) {
			if (data.result) {
				$(location).attr('href', 'index.php?page=admin-permissions');
			} else {
				error(data.message); 
			}
		});
	});
});

function editUserPermissions(id) {
	$(location).attr('href', 'index.php?page=admin-permissions&id=' + id);
}