/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2018 Infected <http://infected.no/>.
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

function userSearch(query, callback, searchError) {

	if (query !== '') {
		$.getJSON('../api/json/user/findUser.php' + '?query=' + query, function(data) {
			var content = '';

			if (data.result) {
				callback(data.users);
			} else {
				if(searchError !== undefined) {
					searchError(data.message);
				}
			}

			$('ul.search-results').html(content);
		});
	}

	return false;
}
