/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2015 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

$(document).ready(function() {
    $('.compo-add').on('submit', function(event) {
	event.preventDefault();
	addCompo(this);
    });
    $('#server-add').on('submit', function(event) {
	event.preventDefault();
	//addServer(this);
    });

    $('.compo-edit').on('submit', function(event) {
	event.preventDefault();
	editCompo(this);
    });
});

function addCompo(form) {
	$.getJSON('../api/json/compo/addCompo.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
		    location.reload();
		} else {
			error(data.message);
		}
	});
}

function addServer(form) {
	$.getJSON('../api/json/compo/addServer.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
		    location.reload();
		} else {
			error(data.message);
		}
	});
}
function deleteServer(serverId) {
	$.getJSON('../api/json/compo/removeServer.php?id=' + serverId, function(data) {
		if (data.result) {
		    location.reload();
		} else {
		    error(data.message);
		}
	});
}

function editCompo(form) {
	$.getJSON('../api/json/compo/editCompo.php' + '?' + $(form).serialize(), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
}

function disqualifyClan(clanid) {
    $.getJSON('../api/json/compo/disqualifyClan.php' + '?id=' + encodeURIComponent(clanid), function(data) {
	if (data.result) {
	    location.reload();
	} else {
	    error(data.message);
	}
    });
}
function deleteClan(clanid, shouldask) {
    if(!shouldask || confirm('Er du sikker på at du vil slette clanen? Når den er slettet, kan du ikke angre!')) {
	$.getJSON('../api/json/compo/deleteClan.php' + '?id=' + encodeURIComponent(clanid), function(data) {
		if (data.result) {
			location.reload();
		} else {
			error(data.message);
		}
	});
    }
}
function qualifyClan(clanid) {
    $.getJSON('../api/json/compo/qualifyClan.php' + '?id=' + encodeURIComponent(clanid), function(data) {
	if (data.result) {
	    location.reload();
	} else {
	    error(data.message);
	}
    });
}
