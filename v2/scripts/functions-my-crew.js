function editPage(id) {
	$(location).attr('href', 'index.php?page=edit-page&id=' + id);
}

function removePage(id) {
	$(location).attr('href', 'index.php?page=application&id=' + id);
}