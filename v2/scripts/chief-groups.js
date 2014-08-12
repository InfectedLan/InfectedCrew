$(document).ready(function() {
	$('.chief-groups-add').submit(function(e) {
		e.preventDefault();
		$.getJSON('../json/addGroup.php' + '?' + $('.chief-groups-add').serialize(), function(data){
			if(data.result) {
				//info(data.message); // TODO: Display "data.message" to user.
				location.reload();
			}
			else {
				error(data.message); // TODO: Display "data.message" to user.
			}
		});
	});
	
	$('.chief-groups-edit').submit(function(e) {
		e.preventDefault();
	    $.getJSON('../json/changeGroup.php' + '?' + $(this).serialize(), function(data){
			if(data.result) {
				//info(data.message); // TODO: Display "data.message" to user.
				location.reload();
			}
			else {
				error(data.message); // TODO: Display "data.message" to user.
			}
		});
	});
});

function removeGroup(id)
{
	$.getJSON('../json/removeGroup.php?id=' + id, function(data){
		if(data.result) {
			//info(data.message); // TODO: Display "data.message" to user.
			location.reload();
		}
		else {
			error(data.message); // TODO: Display "data.message" to user.
		}
	});
}