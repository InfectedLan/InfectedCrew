$(document).ready(function() {
	$('#loginForm').submit( function(e) {
		e.preventDefault();
	    $.post( '../json/login.php', $('#loginForm').serialize(), function(data) { // TODO: Link this to api in a more elegant way.
	    	console.log('Got data!');
	        if(data.result==true)
	        {
	        	location.reload();
	        }
	        else
	        {
	         	error(data.message);
	        }
	       },
	       'json'
	    );
	});
});