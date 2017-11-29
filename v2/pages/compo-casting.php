<?php
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

require_once 'session.php';
require_once 'handlers/compohandler.php';
require_once 'handlers/eventhandler.php';
require_once 'handlers/castingpagehandler.php';
require_once 'objects/compo.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.casting')) {
	    echo "<h1>Casting-sider</h1>";
        echo '<script src="../api/scripts/casting.js"></script>';
        $castingPages = CastingPageHandler::getCastingPages();
        echo '<table>';
        echo '<tr>';
        echo '<td><b>Navn</b></td>';
        echo '<td><b>Template</b></td>';
        echo '</tr>';
        foreach($castingPages as $castingPage) {
            echo '<tr>';
            echo '<td><b>' . $castingPage->getName() . '</b></td>';
            echo '<td>' . $castingPage->getTemplate() . '</td>';
            echo '<td><a href="../api/pages/cast.php?id=' . $castingPage->getId() . '" target="_blank">Cast</a></td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<h1>Ny casting-side</h1>';
        echo '<table>';
        echo '<tr>';
        echo '<td>Navn:</td>';
        echo '<td><input id="castingPageName" type="text" placeholder="Skriv et navn" /></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Template:</td>';
        echo '<td><select id="templateSelector">';
        echo '<option value="default">Default</option selected>';
        echo '<option value="csgoUserTemplate">CS:GO User</option>';
        echo '<option value="csgoTeamTemplate">CS:GO Team</option>';
	echo '<option value="castingCamTemplate">Casting camera template</option>';
        echo '</select></td>';
        echo '</tr>';
        echo '</table>';
        echo '<div id="customPreferences"></div>';
        echo '<input type="button" value="Lag!" onClick="createCastingPage()" />';
	} else {
	    echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
