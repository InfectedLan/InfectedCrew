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
require_once 'handlers/userhandler.php';
require_once 'handlers/sysloghandler.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('developer.syslog')) {
		echo '<h1>Systemlogg</h1>';
		$entries = SyslogHandler::getLastEntries(100);
		echo '<table>';
		echo '<tr>';
		echo '<td>Kilde</td>';
		echo '<td>Alvorsgrad</td>';
		echo '<td>Melding</td>';
		echo '<td>Metadata</td>';
		echo '<td>Klokkeslett</td>';
		echo '<td>Bruker</td>';
		echo '</tr>';
		foreach($entries as $entry) {
		    echo '<tr>';
		    echo '<td>' . $entry->getSource() . '</td>';
		    echo '<td>' . SyslogHandler::getSeverityString($entry->getSeverity()) . '</td>';
		    echo '<td>' . $entry->getMessage() . '</td>';
		    $metadata = $entry->getMetadata();
		    if(!is_array($metadata) || count($metadata)>0) {
			echo '<td><textarea rows="10" cols="50">' . json_encode($entry->getMetadata(), JSON_PRETTY_PRINT) . '</textarea></td>';
		    } else {
			echo '<td><i>Ingen metadata</i></td>';
		    }
		    echo '<td>' . date('Y-m-d H:i:s', $entry->getTimestamp()) . '</td>';
		    $causingUser = $entry->getUser();
		    if($causingUser == null) {
			echo '<td><b>Ingen</b></td>';
		    } else {
			if($user->hasPermission('user.search')) {
			    echo '<td><a href="index.php?page=user-profile&id=' . $causingUser->getId() . '">' . $causingUser->getUsername() . '(' . $causingUser->getId() . ')</a></td>';
			} else {
			    echo '<td>' . $causindUser->getDisplayName() . '</td>';
			}
		    }
		    echo '</tr>';
		}

		echo '</table>';

		echo '<i>Viser ' . count($entries) . ' logg-linjer</i>';
	} else {
		echo 'Du har ikke rettigheter til dette.';
	}
} else {
	echo 'Du er ikke logget inn.';
}
?>
