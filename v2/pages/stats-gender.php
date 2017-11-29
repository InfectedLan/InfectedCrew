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
require_once 'objects/compo.php';

if (Session::isAuthenticated()) {
	$user = Session::getCurrentUser();

	if ($user->hasPermission('stats.gender')) {
		$colorList = [];
        echo '<script src="../api/scripts/Chart.min.js"></script>';
        echo '<h1>Kjønnsfordeling(%)</h1><canvas id="gender-chart" width="500" height="400"></canvas>';
        echo '<script>
        	var ctx = document.getElementById("gender-chart");
        	$(document).ready(function() {
        		var years = [];
        		//From here: https://github.com/Jam3/nice-color-palettes
        		$.when(';
        		$currEvent = EventHandler::getCurrentEvent();
        		for($i = $currEvent->getId(); $i>=1; $i--) {
        			echo '$.getJSON("../api/json/stats/eventGenderDistribution.php?id=' . $i . '", function(data) {
						if (data.result) {
						    console.log(data);
						    years[' . ($currEvent->getId()-$i) . '] = {label: "' . ($i == $currEvent->getId() ? "Nå" : EventHandler::getEvent($i)->getTitle()) . '", data: data.data};
						} else {
						    error(data.message);
						}
				   })';
				   if($i!=1) {
				   	echo ",";
				   }
        		}
				echo ').then(function() {
					console.log("Creating charts from: " + JSON.stringify(years));
					var boys = [];
					var girls = [];
					var crewBoys = [];
					var crewGirls = [];
					for(var i = 0; i < years.length; i++) {
						var participantTotal = years[i].data.participants.boys+years[i].data.participants.girls;
						var crewTotal = years[i].data.crew.boys+years[i].data.crew.girls;

						boys.push((participantTotal==0?0:years[i].data.participants.boys/participantTotal)*100);
						girls.push((participantTotal==0?0:years[i].data.participants.girls/participantTotal)*100);

						crewBoys.push((crewTotal==0?0:years[i].data.crew.boys/crewTotal)*100);
						crewGirls.push((crewTotal==0?0:years[i].data.crew.girls/crewTotal)*100);
					}
					var labels = [];
					for(var i = 0; i < years.length; i++) {
						labels.push(years[i].label);
					}
					var myLineChart = new Chart(ctx, {
					    type: "line",
					    data: {datasets: [
					    	{
					    		label: "Gutter",
					    		data: boys,
					    		borderColor: "#64B5F6"
					    	},
					    	{
					    		label: "Jenter",
					    		data: girls,
					    		borderColor: "#F06292"
					    	},
					    	{
					    		label: "Gutter(Crew)",
					    		data: crewBoys,
					    		borderColor: "#1E88E5"
					    	},
					    	{
					    		label: "Jenter(Crew)",
					    		data: crewGirls,
					    		borderColor: "#D81B60"
					    	}
					    	], labels: labels},
					    options: {}
					});
				});
			});
        </script>';

	} else {
		echo '<p>Du har ikke rettigheter til dette!</p>';
	}
} else {
	echo '<p>Du er ikke logget inn!</p>';
}
?>
