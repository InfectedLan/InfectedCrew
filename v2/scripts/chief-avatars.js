function acceptAvatar(id) {
	$.getJSON('../api/json/avatar/acceptAvatar.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function rejectAvatar(id) {
	$.getJSON('../api/json/avatar/rejectAvatar.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}