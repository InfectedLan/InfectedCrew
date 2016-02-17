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
require_once 'handlers/serverhandler.php';
require_once 'objects/compo.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.edit')) {
        $id = $_GET["id"];
        $compo = CompoHandler::getCompo($id);

        if($compo != null) {
	    if($compo->getConnectionType() == Compo::CONNECTION_TYPE_SERVER) {
		//echo '<h1>' . $compo->getTitle() . '</h1>';
		echo '<hr>';
		echo '<a href="index.php?page=compo-view&id=' . $compo->getId() . '">Oversikt</a> ';
		echo '<a href="index.php?page=compo-clans&id=' . $compo->getId() . '">Påmeldte klaner</a> ';
		echo '<a href="index.php?page=compo-matches&id=' . $compo->getId() . '">Matcher(Liste)</a> ';
		if($user->hasPermission('compo.bracketmanagement')) {
		    echo '<a href="index.php?page=compo-brackets&id=' . $compo->getId() . '">Rediger brackets</a> ';
		}
		if($user->hasPermission('compo.chat')) {
		    echo '<a href="index.php?page=compo-chat&id=' . $compo->getId() . '">Chatter</a> ';
		}
		if($user->hasPermission('compo.edit') && $compo->getConnectionType() == Compo::CONNECTION_TYPE_SERVER) {
		    echo '<a href="index.php?page=compo-servers&id=' . $compo->getId() . '">Servere</a> ';		
		}
		echo '<hr>';
		echo '<script scr="scripts/compo.js"></script>';
		echo '<h1>Servere</h1>';

		$servers = ServerHandler::getServersByCompo($compo);

		echo '<table>';
		echo '<tr>';
		echo '<td><b>Navn</b></td>';
		echo '<td><b>Tilkoblings-data</b></td>';
		echo '</tr>';
		foreach($servers as $server) {
		    echo '<tr>';
		    echo '<td>' . $server->getHumanName() . '</td>';
		    echo '<td><pre>' . $server->getConnectionData() . '</pre></td>';
		    echo '<td><input type="button" Value="Slett" onClick="deleteServer(' . $server->getId() . ')" /></td>';
		    echo '</tr>';
		}
		echo '</table>';

		echo '<h1>Lag en ny server</h1>';
		echo '<form class="server-add" method="get">';
		echo '<input type="hidden" name="compoId" value="' . $compo->getId() . '" />';
		echo '<table>';

		echo '<tr>';
		echo '<td>Navn: </td>';
		echo '<td><input type="text" name="humanName" placeholder="Skriv noe menneske-forståelig her..." /></td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td>Server-data:</td>';
		echo '<td><input type="text" name="connectionData" placeholder="La stå blank om usikker" /></td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td></td>';
		echo '<td><i>Håndtert av compo-plugin</i></td>';
		echo '</tr>';

		echo '<tr>';
		echo '<td><input type="submit" value="Legg til"></td>';
		echo '</tr>';
		
		echo '</table>';
		echo '</form>';
		echo '<i>Husk at serverne må settes opp slik at de fungerer med valgt compo-plugin</i>';
	    } else {
		echo '<b>Denne compoen har ikke servere</b>';
	    }
        } else {
            echo '<p>Compoen eksisterer ikke!</p>';
        }

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
