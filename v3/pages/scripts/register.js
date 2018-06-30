﻿/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$(function() {
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });

    $('[data-mask]').inputmask();

	$('.register').on('submit', function(event) {
		event.preventDefault();
		createUser(this);
	});
});

function createUser(form) {
	$.post('../api/rest/user/create.php', $(form).serialize(), function(data) {
		if (data.result) {
            $('.modal-success .modal-body').text(data.message);
		    $('.modal-success').modal('show');
		} else {
            $('.modal-danger .modal-body').text(data.message);
		    $('.modal-danger').modal('show');
		}
	});
}