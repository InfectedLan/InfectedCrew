$(document).ready(function() {
	$('.slide-add').submit(function(e) {
		e.preventDefault();
		addSlide(this);
	});
	
	$('.slide-edit').submit(function(e) {
		e.preventDefault();
		editSlide(this);
	});
});

function addSlide(form) {
	$.getJSON('../api/json/slide/addSlide.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function editSlide(form) {
	$.getJSON('../api/json/slide/editSlide.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message); 
		}
	});
}

function removeSlide(id) {
	$.getJSON('../api/json/slide/removeSlide.php?id=' + id, function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}