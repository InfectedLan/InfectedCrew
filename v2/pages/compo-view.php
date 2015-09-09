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

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('compo.management')) {
	   $id = $_GET["id"];
       $compo = CompoHandler::getCompo($id);

       if($compo != null) {
           //echo '<h1>' . $compo->getTitle() . '</h1>';
           echo '<hr>';
	           echo '<a href="index.php?page=compo-view&id=' . $compo->getId() . '">Oversikt</a> ';
               echo '<a href="index.php?page=compo-clans&id=' . $compo->getId() . '">Påmeldte klaner</a> ';
           echo '<hr>';
           echo '<p>Under construction.</p>';
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
