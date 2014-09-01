$(document).ready(function() {  
	$('input.search').on('keyup', function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$('ul.search-results').fadeOut();
		} else {
			$('ul.search-results').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
		};
	});
});

function search() {
	var query = $('input.search').val();
	$('b.search-string').html(query);
	
	if (query !== '') {
		$.getJSON('../api/json/searchForUser.php' + '?query=' + query, function(data) {
			var content = '';
			
			if (data.result) {
				for (var i = 0; i < data.users.length; i++) {
					var user = data.users[i];
					
					content += '<li><a href="index.php?page=my-profile&id=' + user.id + '"><b class="highlight">' + user.firstname + ' "' + user.nickname + '" ' + user.lastname + '</b></a></li>';
				}
			} else {
				content = '<li><b>Ingen resultater funnet.</b></li>';
			}
			
			$('ul.search-results').html(content);
		});
	}
	
	return false;    
}