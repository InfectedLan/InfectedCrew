<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2018 Infected <https://infected.no/>.
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

require_once 'session.php';
require_once 'handlers/userhandler.php';
require_once 'handlers/sysloghandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('developer.maintenance')) {
		echo '<h1>Vedlikeholdsmodus</h1>';
		
        echo '<p>Dette vil sette nettsiden inn i en tidsbasert vedlikeholdsmodus, som for øyeblikket ikke kan avbrytes.</p>';
        echo '<form method="post" class="maintenance-submit"><input type="number" min="1" max="1800" name="duration"><i>sekunder</i><input type="submit" value="Vedlikehold!" ></form>';

        echo '<script>$document.onReady(function(){$(".maintenance-submit").on("submit", function() {$.post("", $(".maintenance-submit").serialize(), function(result){ if(result.result) {location.reload();} else {error(result.message);} });})});</script>';
        
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>
