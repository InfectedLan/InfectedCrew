/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no>.
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
 * License along with this library.  If not, see <http://www.gnu.org/licenses>.
 */

function updateSearch(query) {
	menuHtml = "";
	var resultsFound = false;
	for(entry in menuData) {
		data = getHtml(menuData[entry], query, true);
		if(data != null) {
			menuHtml += data;
			resultsFound = true;
		}
	}
	if(query.length >= 3 && hasUserSearchPermission) {
		userSearch(query, function(list) {
			console.log("Got search results: ");
			console.log(list);
			userResultsList = '<li class="header">Brukere</li>';
			for(var i = 0; i < (list.length > 15 ? 15 : list.length); i++) {
				icon = "fa-user";
				userResultsList += '<li><a href="?page=user-profile&userId=' + list[i].id + '"><i class="fa ' + icon + '"></i><span>' + list[i].firstname + ' "' + list[i].username + '" ' + list[i].lastname + '</span></a></li>';
			}
			$(".sidebar-menu").append(userResultsList);
		});
	}
	//console.log(menuHtml);
	
	$(".sidebar-menu").html('<li class="header">Hovedmeny</li>' + menuHtml);
}

function getHtml(entryData, query, topOfTree) {
	//console.log("Fetching for ");
	if(entryData.entries === undefined) {
		if(entryData.length == 0 || !entryData.name.toLowerCase().includes(query.toLowerCase()))
			return null;
		//Url entry
		if(topOfTree) {
			return '<li><a href="' + entryData.url + '"><i class="fa ' + entryData.icon + '"></i><span>' + entryData.name + '</span></a></li>';
		} else {
			return '<li' + (entryData.active ? ' class="active"' : '') + '><a href="' + entryData.url + '"><i class="fa ' + entryData.icon + '"></i> ' + entryData.name + '</a></li>';
		}
	} else {
		header = '<li class="treeview' + (entryData.active || query.length != 0 ? " active" : "") + '">';
		header += '<a href=""><i class="fa ' + entryData.icon + '"></i><span>' + entryData.name + '</span><i class="fa fa-angle-left pull-right"></i></a>';
		header += '<ul class="treeview-menu">';
		var categoryMatch = entryData.name.toLowerCase().includes(query.toLowerCase());
		
		var notnull = false;
		for(index in entryData.entries) {
			result = getHtml(entryData.entries[index], categoryMatch?"":query, false);
			//Search check for children
			if(result !== null) {
				notnull = true;
				header += result
			}
		}
		//Dont care if no children followed the search terms
		if(!notnull && !categoryMatch) {
			return null;
		}
		return header + '</ul></li>';
	}
	//console.log(entryData);
} 

$(document).ready(function(){
	//updateSearch("");
	$('#sidebar-search').on('input',function(e){
	    //console.log(e.target.value);
	    updateSearch(e.target.value);
	});
	if(hasUserSearchPermission) {
		$("#sidebar-search").attr("placeholder", "Søk i menyen og brukere");
	}
});