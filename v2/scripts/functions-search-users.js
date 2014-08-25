$(document).ready(function() {  
	$('.search input').on('keyup', function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));
		$(this).data('timer', setTimeout(search, 100));
	});
});

function search() {
	var query = $('.search input').val();
	
	if (query !== '') {
		$.getJSON('../api/json/searchForUser.php' + '?query=' + query, function(data) {
			var content = '';
			
			if (data.result) {
				for (var i = 0; i < data.users.length; i++) {
					var user = data.users[i];
					
					content += '<li><a href="index.php?page=profile&id=' + user.id + '">' + user.firstname + ' "' + user.nickname + '" ' + user.lastname + '</a></li>';
				}
			} else {
				content = '<li>Ingen resultater funnet.</li>';
			}
			
			$('ul.results').html(content);
		});
	}
	
	return false;    
}