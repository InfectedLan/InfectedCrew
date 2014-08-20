function editUserPermissions(userId) {
	$(location).attr('href', 'index.php?page=edit-permissions&id=' + userId);
}